<?php

/**
 * Description of LaporanLPLPOUnit
 *
 * @author feb
 */
class LaporanLPLPOUnit extends BaseLaporan {

    public function __construct() {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
    }

    private function process($_tanggal, $_tanggal2, $tujuan_id) {
        $reportName = "laporan_obat";

        $objPHPExcel = $this->loadPHPExcelLib($reportName);
        $objPHPExcel->setActiveSheetIndex(0);
        $activeSheet = $objPHPExcel->getActiveSheet();
        
        $tanggal = $this->reverseDate($_tanggal);
        $tanggal2 = $this->reverseDate($_tanggal2);
        
        $puskesmas_id = Yii::app()->user->puskesmas_id;
        $puskesmas = Puskesmas::model()->findByPk($puskesmas_id);
        
        $departemen_id = Departemen::model()->findByAttributes(array("puskesmas_id"=>$puskesmas_id, "departemen_jenis_id"=>"1"))->id;
        
        $tujuan = DistribusiObatTujuan::model()->findByPk($tujuan_id);
        $activeSheet->getCell("A8")->setValue(strtoupper($tujuan->nama." - PUSKESMAS ".$puskesmas->nama));
        $activeSheet->getCell("A5")->setValue("PEMAKAIAN TANGGAL " . $_tanggal ." S/D ".$_tanggal2);
        
        //echo "$tujuan_id, $departemen_id, $puskesmas_id";
        
        $no = 1;
        $baris = 12;
        $obatArr = Obat::model()->findAll();
        foreach ($obatArr as $obat) {
            $data = KartuStok::kalkulasiStokObat($obat->id, $tujuan_id, $departemen_id, $puskesmas_id, FALSE);
            
            $masuk = 0;
            $keluar = 0;
            foreach($data as $d){
                //echo "Perbandingan : ".strtotime($tanggal)." > ".strtotime($d["date"])." > ".strtotime($tanggal2);
                //echo "<br>";
                if(strtotime($tanggal) <= strtotime($d["date"]) && strtotime($d["date"]) < (strtotime($tanggal2)+86400)){
                    $masuk += $d["masuk"];
                    $keluar += $d["keluar"];
                }else{
                    continue;
                }
            }
            
            $activeSheet->setCellValue("A" . $baris, $no);
            $activeSheet->setCellValue("B" . $baris, $obat->nama);
            $activeSheet->setCellValue("C" . $baris, ObatSatuan::model()->findByPk($obat->kemasan_id)->nama);
            $activeSheet->setCellValue("D" . $baris, $masuk);
            $activeSheet->setCellValue("E" . $baris, $keluar);

            $no++;
            $baris++;
        }

        $activeSheet->getStyle("A12:E" . ($baris - 1))->applyFromArray(array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        ));

        return array(
            "phpexcel" => $objPHPExcel,
            "name" => $reportName . "_" . $_tanggal . "_" . $_tanggal2
        );
    }

    public function getDataResep($year, $month, $departemen_id, $puskesmas_id) {
        $data = array();
        $jps = JenisPembayaran::model()->findAllByAttributes(array("status" => "1"));
        foreach ($jps as $jp) {
            $data[$jp->id] = 0;
        }

        $filter = "year(waktu) = '$year' AND month(waktu) = '$month' AND puskesmas_id = '$puskesmas_id' ";
        if ($departemen_id != NULL) {
            $filter = $filter . " AND departemen_id = '" . $departemen_id . "'";
        }
        foreach (TransaksiObatNonInjeksi::model()->findAll($filter . " GROUP BY medical_record_id ASC") as $transaksi) {
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
    public function download($tgl, $tgl2, $tujuan_id) {
        $this->downloadFile($this->process($tgl, $tgl2, $tujuan_id));
    }

    public function preview($tgl, $tgl2, $tujuan_id) {
        $this->previewFile($this->process($tgl, $tgl2, $tujuan_id));
    }

}
