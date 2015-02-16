<?php

/**
 * Description of LaporanLPLPO
 *
 * @author feb
 */

class LaporanLPLPO extends BaseLaporan {
    
    public function __construct() {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
    }
    
    private function process($year, $month, $departemen_id){
        $reportName = "laporan_lplpo";
		
		$objPHPExcel = $this->loadPHPExcelLib($reportName);
		$objPHPExcel->setActiveSheetIndex(0);
		$activeSheet = $objPHPExcel->getActiveSheet();
        
        $namaBulan = array(
            "1" => "Januari",
            "2" => "Februari",
            "3" => "Maret",
            "4" => "April",
            "5" => "Mei",
            "6" => "Juni",
            "7" => "Juli",
            "8" => "Agustus",
            "9" => "September",
            "10" => "Oktober",
            "11" => "November",
            "12" => "Desember"
        );
        
        $puskesmas_id = NULL;
        $jenisLaporan = "";
        $filter = "";
        if (substr("" . $departemen_id, 0, 4) == "wil_") {
            $jenisLaporan = "summary";
            $puskesmas = Puskesmas::model()->findByPk(substr("" . $departemen_id, 4));
            $puskesmas_id = $puskesmas->id;
            $departemen = NULL;
            $departemen_id = NULL;
            $activeSheet->getCell("A8")->setValue("Wilayah Kerja Puskesmas " . ucwords(strtolower($puskesmas->nama)));
        } else {
            $jenisLaporan = "per_departemen";
            $departemen = Departemen::model()->findByPk($departemen_id);
            $puskesmas = Puskesmas::model()->findByPk($departemen->puskesmas_id);
            $puskesmas_id = $puskesmas->id;
            $activeSheet->getCell("A8")->setValue($departemen->nama);
            $filter = " AND departemen_id = '" . $departemen->id . "'";
        }
        
        //echo "JL : ".$jenisLaporan.", Pusk : ".$puskesmas_id.", DPID : ".$departemen_id;
        
        //judul
        $activeSheet->getCell("A5")->setValue("PEMAKAIAN BULAN ".strtoupper($namaBulan[$month]));
        
        $no = 1;
        $baris = 12;
        $output = KartuStok::getLPLPOData($month, $year, $jenisLaporan, $puskesmas_id, $departemen_id);
        foreach($output as $element){
            $activeSheet->setCellValue("A".$baris, $no);
            $activeSheet->setCellValue("B".$baris, $element["nama_obat"]);
            $activeSheet->setCellValue("C".$baris, $element["kemasan"]);
            $activeSheet->setCellValue("D".$baris, $element["stok_awal"]);
            $activeSheet->setCellValue("E".$baris, $element["penerimaan"]);
            $activeSheet->setCellValue("F".$baris, $element["pemakaian"]);
            $activeSheet->setCellValue("G".$baris, $element["rusak"]);
            $activeSheet->setCellValue("H".$baris, $element["saldo"]);
            
            $no++;
            $baris++;
        }
        
        $activeSheet->getStyle("A12:H".($baris-1))->applyFromArray(array(
            'borders' => array(
              'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              )
            )
        ));
        
        $i = 4;
        $reseps = $this->getDataResep($year, $month, $departemen_id, $puskesmas_id);
        foreach($reseps as $key => $element){
            $activeSheet->setCellValueByColumnAndRow($i, 5, $key);
            $activeSheet->setCellValueByColumnAndRow($i, 6, $element);
            $activeSheet->getStyleByColumnAndRow($i, 5)->applyFromArray(array(
                'borders' => array(
                  'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  )
                )
            ));
            $activeSheet->getStyleByColumnAndRow($i, 6)->applyFromArray(array(
                'borders' => array(
                  'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                  )
                )
            ));
            $i++;
        }
        
        return array(
            "phpexcel" => $objPHPExcel,
            "name" => $reportName . "_" . $namaBulan[$month] . "_" . $year
        );
    }
    
    public function getDataResep($year, $month, $departemen_id, $puskesmas_id){
        $data = array();
        $jps = JenisPembayaran::model()->findAllByAttributes(array("status"=>"1"));
        foreach ($jps as $jp) {
            $data[$jp->id] = 0;
        }
        
        $filter = "year(waktu) = '$year' AND month(waktu) = '$month' AND puskesmas_id = '$puskesmas_id' ";
        if($departemen_id != NULL){
            $filter = $filter . " AND departemen_id = '".$departemen_id."'";
        }
        foreach(TransaksiObatNonInjeksi::model()->findAll($filter." GROUP BY medical_record_id ASC") as $transaksi){
            $medical = TransaksiMedicalRecord::model()->findByPk($transaksi->medical_record_id);
            $kunjungan = TransaksiKunjungan::model()->findByPk($medical->kunjungan_id);
            $data[$kunjungan->jenis_pembayaran_id] += 1;
        }
        $output = array();
        foreach ($jps as $jp) {
            $output[$jp->nama] = $data[$jp->id];
        }
        return $output;
    }
    
    //$year, $month, $puskemas_id
    public function download($year, $month, $departemen_id){
        $this->downloadFile($this->process($year, $month, $departemen_id));
    }
    
    public function preview($year, $month, $departemen_id){
        $this->previewFile($this->process($year, $month, $departemen_id));
    }
}