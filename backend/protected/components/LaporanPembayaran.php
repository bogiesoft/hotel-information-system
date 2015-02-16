<?php

/**
 * Description of LaporanPembayaran
 *
 * @author feb
 */

class LaporanPembayaran extends BaseLaporan {
    
    public function __construct() {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
    }
    
    private function processKTP($tanggal, $tanggal2, $departemen_id){
        $reportName = "laporan_pembayaran_ktp";
		
		$objPHPExcel = $this->loadPHPExcelLib($reportName);
		$objPHPExcel->setActiveSheetIndex(0);
		$activeSheet = $objPHPExcel->getActiveSheet();
        
        $filter = "";
        if(substr("".$departemen_id, 0, 4) == "wil_"){
            $puskesmas = Puskesmas::model()->findByPk(substr("".$departemen_id, 4));
            $departemen = NULL;
            $activeSheet->setCellValue("A2", "Wilayah Puskesmas ".ucwords(strtolower($puskesmas->nama)));
        }else{
            $departemen = Departemen::model()->findByPk($departemen_id);
            $puskesmas = Puskesmas::model()->findByPk($departemen->puskesmas_id);
            $activeSheet->setCellValue("A2", $departemen->nama);
            $filter = " AND departemen_id = '".$departemen->id."'";
        }
        
        //judul
        $activeSheet->setCellValue("A4", "Tanggal ".$tanggal." s/d ".$tanggal2);
        
        $tanggal = $this->reverseDate($tanggal);
        $tanggal2 = $this->reverseDate($tanggal2);
        
        $baris = 10;
        $trs = TransaksiKunjungan::model()->findAll("waktu between '$tanggal 00:00:00' AND '$tanggal2 23:59:00' AND puskesmas_id = '".$puskesmas->id."'".$filter);
        foreach($trs as $t){
            if($t->jenis_pembayaran_id == 1){
                //UMUM
                $pasien = Pasien::model()->findByPk($t->pasien_id);
                if($pasien->dalam_wilayah == 1){
                    $wilayah[0] += 1;
                }else if($pasien->dalam_wilayah == 2){
                    $wilayah[1] += 1;
                }else{
                    $wilayah[2] += 1;
                }
                
                $umur = date_diff(date_create($pasien->tanggal_lahir), date_create(date("Y-m-d", strtotime($t->waktu))))->y;
                
                if($umur > 200){
                    $umur = 0;
                }
                
                $activeSheet->setCellValue("A".$baris, $baris-9);
                $activeSheet->setCellValueExplicit("B".$baris, $pasien->kode, PHPExcel_Cell_DataType::TYPE_STRING);
                $activeSheet->setCellValue("C".$baris, $pasien->nama);
                $activeSheet->setCellValue("D".$baris, $umur);
                
                $activeSheet->setCellValue("E".$baris, ($t->jenis_kunjungan == "B")?"1":"0");
                $activeSheet->setCellValue("F".$baris, ($t->jenis_kunjungan == "L")?"1":"0");
                
                $activeSheet->setCellValue("G".$baris, ($pasien->jenis_kelamin == "L")?"1":"0");
                $activeSheet->setCellValue("H".$baris, ($pasien->jenis_kelamin == "P")?"1":"0");
                $activeSheet->setCellValue("I".$baris, ($pasien->dalam_wilayah == "1")?"1":"0");
                $activeSheet->setCellValue("J".$baris, ($pasien->dalam_wilayah == "2")?"1":"0");
                $activeSheet->setCellValue("K".$baris, ($pasien->dalam_wilayah == "3")?"1":"0");
                $activeSheet->setCellValue("L".$baris, date("d-m-Y", strtotime($t->waktu)));
                $activeSheet->setCellValue("M".$baris, $pasien->alamat);
                $activeSheet->setCellValueExplicit("N".$baris, $pasien->no_ktp."", PHPExcel_Cell_DataType::TYPE_STRING);
                $activeSheet->setCellValue("O".$baris, "10000");
                
                $antrian = TransaksiAntrian::model()->findByAttributes(array("kunjungan_id"=>$t->id));
                //BP
                if($antrian->poli_tujuan == 1){
                    $activeSheet->setCellValue("P".$baris, ($t->jenis_kunjungan == "B")?"1":"0");
                    $activeSheet->setCellValue("Q".$baris, ($t->jenis_kunjungan == "L")?"1":"0");
                }
                if($antrian->poli_tujuan == 3){
                    $activeSheet->setCellValue("R".$baris, ($t->jenis_kunjungan == "B")?"1":"0");
                    $activeSheet->setCellValue("S".$baris, ($t->jenis_kunjungan == "L")?"1":"0");
                }
                if($antrian->poli_tujuan == 4){
                    $activeSheet->setCellValue("T".$baris, ($t->jenis_kunjungan == "B")?"1":"0");
                    $activeSheet->setCellValue("U".$baris, ($t->jenis_kunjungan == "L")?"1":"0");
                }
                
                $baris++;
            }
        }
        
        $activeSheet->getStyle("A10:S".($baris))->applyFromArray($this->getNormalStyle());
        
        $activeSheet->setCellValue("E".$baris, "=SUM(E10:E".($baris-1).")");
        /*$activeSheet->setCellValue("F".$baris, "=SUM(F10:F".($baris-1).")");
        $activeSheet->setCellValue("G".$baris, "=SUM(G10:G".($baris-1).")");
        $activeSheet->setCellValue("H".$baris, "=SUM(H10:H".($baris-1).")");
        $activeSheet->setCellValue("I".$baris, "=SUM(I10:I".($baris-1).")");
        
        $activeSheet->setCellValue("N".$baris, "=SUM(N10:N".($baris-1).")");
        $activeSheet->setCellValue("O".$baris, "=SUM(O10:O".($baris-1).")");
        $activeSheet->setCellValue("P".$baris, "=SUM(P10:P".($baris-1).")");
        $activeSheet->setCellValue("Q".$baris, "=SUM(Q10:Q".($baris-1).")");
        $activeSheet->setCellValue("R".$baris, "=SUM(R10:R".($baris-1).")");
        $activeSheet->setCellValue("S".$baris, "=SUM(S10:S".($baris-1).")");*/
        
        return array(
            "phpexcel" => $objPHPExcel,
            "name" => $reportName . "_" . $tanggal . "_" . $tanggal2
        );
    }
    
    
    public function downloadKTP($tanggal, $tanggal2, $departemen_id){
        $this->downloadFile($this->processKTP($tanggal, $tanggal2, $departemen_id));
    }
    
    public function previewKTP($tanggal, $tanggal2, $departemen_id){
        $this->previewFile($this->processKTP($tanggal, $tanggal2, $departemen_id));
    }
}