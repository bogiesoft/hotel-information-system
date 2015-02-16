<?php

/**
 * Description of LaporanKunjunganLoket
 *
 * @author feb
 */

class LaporanKunjunganLoket extends BaseLaporan {
    
    public function __construct() {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
    }
    
    private function process($year, $departemen_id){
        $reportName = "laporan_kunjungan_loket";
		
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
        
        //judul
        if(substr("".$departemen_id, 0, 4) == "wil_"){
            $puskesmas = Puskesmas::model()->findByPk(substr("".$departemen_id, 4));
            $departemen = NULL;
            $activeSheet->getCell("B3")->setValue("UPT Puskesmas: ".$puskesmas->nama." Tahun ".$year);
        }else{
            $departemen = Departemen::model()->findByPk($departemen_id);
            $puskesmas = Puskesmas::model()->findByPk($departemen->puskesmas_id);
            $activeSheet->getCell("B3")->setValue($departemen->nama." Tahun ".$year);
        }
        
        //header
        $col = 2;
        $poliPuskesmases = PoliPuskesmas::model()->findAllByAttributes(array("puskesmas_id"=>$puskesmas->id));
        foreach($poliPuskesmases as $poliPuskesmas){
            $poli = Poli::model()->findByPk($poliPuskesmas->poli_id);
            $activeSheet->getCellByColumnAndRow($col, 7)->setValue($poli->nama);
            $col++;
        }
        $activeSheet->getCellByColumnAndRow($col, 7)->setValue("Total");
        $activeSheet->mergeCellsByColumnAndRow(2, 6, $col, 6);
        
        //konten
        $baris = 8;
        for($bulan = 1;$bulan<=12;$bulan++){
            $col = 2;
            $total = 0;
            foreach($poliPuskesmases as $poliPuskesmas){
                //NodeLogger::sendLog("Poli ".$poliPuskesmas->poli_id);
                $poli_id = $poliPuskesmas->poli_id;
                if($departemen == NULL){
                    NodeLogger::sendLog("All");
                    $kunjungans = TransaksiKunjungan::model()->findAll("month(waktu) = '$bulan' AND year(waktu) = '$year' AND puskesmas_id = '".$puskesmas->id."'");
                }else{
                    NodeLogger::sendLog("Dept ".$departemen->id);
                    $kunjungans = TransaksiKunjungan::model()->findAll("departemen_id = '".$departemen->id."' AND month(waktu) = '$bulan' AND year(waktu) = '$year' AND puskesmas_id = '".$puskesmas->id."'");
                }
                //NodeLogger::sendLog("Jml Kunjungan Poli ".$poli_id." : ".count($kunjungans));
                $jml = 0;
                foreach ($kunjungans as $kunjungan) {
                    $jmlAntrian = TransaksiAntrian::model()->countByAttributes(array("poli_tujuan"=> $poli_id, "kunjungan_id"=>$kunjungan->id));
                    $jml += $jmlAntrian;
                    
                    /*$antrians = TransaksiAntrian::model()->findAllByAttributes(array("poli_tujuan"=> $poli_id, "kunjungan_id"=>$kunjungan->id));
                    if($departemen == NULL){
                        //semua
                        $jml += count($antrians);
                    }else if($departemen->id == $kunjungan->departemen_id){
                        $jml += count($antrians);
                    }*/
                }
                
                $activeSheet->getCellByColumnAndRow($col, $baris)->setValue("".$jml);
                
                $total += $jml;
                $col++;
                //break;
            }
            $activeSheet->getCellByColumnAndRow($col, $baris)->setValue($total);
            $baris++;
        }
        
        $col = 2;
        foreach($poliPuskesmases as $poliPuskesmas){
            $col++;
        }
        $activeSheet->getStyle("B20:".$this->getHuruf($col)."20")->getFill()->applyFromArray(array('type' => PHPExcel_Style_Fill::FILL_SOLID,
            'startcolor' => array('rgb' => "C7F2FF")
        ));
        
        //header
        $activeSheet->getStyle("B6:".$this->getHuruf($col)."7")->getFill()->applyFromArray(
            array(
                'type' => PHPExcel_Style_Fill::FILL_SOLID,
                'startcolor' => array('rgb' => "C7F2FF")
            )
        );
        
        $activeSheet->getStyle("B6:".$this->getHuruf($col)."20")->applyFromArray(array(
            'borders' => array(
              'allborders' => array(
                'style' => PHPExcel_Style_Border::BORDER_THIN
              )
            )
        ));
        
        
        return array(
            "phpexcel" => $objPHPExcel,
            "name" => $reportName . "_" . $year
        );
    }
    
    private function process2($year, $departemen_id){
        $reportName = "laporan_kunjungan_loket_lengkap";
		
		$objPHPExcel = $this->loadPHPExcelLib($reportName);
		$objPHPExcel->setActiveSheetIndex(0);
		$activeSheet = $objPHPExcel->getActiveSheet();
        
        $activeSheet->setCellValue("A1", "Tahun ".$year);
        
        $filter = "";
        if(substr("".$departemen_id, 0, 4) == "wil_"){
            $puskesmas = Puskesmas::model()->findByPk(substr("".$departemen_id, 4));
            $departemen = NULL;
            $activeSheet->getCell("C3")->setValue($puskesmas->nama);
        }else{
            $departemen = Departemen::model()->findByPk($departemen_id);
            $puskesmas = Puskesmas::model()->findByPk($departemen->puskesmas_id);
            $activeSheet->getCell("C3")->setValue($departemen->nama);
            $filter = "AND departemen_id = '".$departemen_id."' ";
        }
        
        
        
        $baris = 11;
        for($bulan = 1;$bulan <= 12;$bulan++){
            $baseQuery = "year(waktu) = '$year' AND month(waktu) = '$bulan' AND puskesmas_id = '".$puskesmas->id."'".$filter;
            
            //kunjungan baru
            $kunjunganBaru = TransaksiKunjungan::model()->count($baseQuery." AND jenis_kunjungan = 'B'");
            $activeSheet->setCellValue("C".$baris, $kunjunganBaru);
            //kunjungan lama
            $kunjunganLama = TransaksiKunjungan::model()->count($baseQuery." AND jenis_kunjungan = 'L'");
            $activeSheet->setCellValue("D".$baris, $kunjunganLama);
            
            //BP
            $criteria = new CDbCriteria();
            $criteria->condition = $baseQuery." AND poli_tujuan IN(1, 24)";
            $criteria->order = "id";
            $criteria->group = "kunjungan_id";
            $bp = TransaksiAntrian::model()->count($criteria);
            $activeSheet->setCellValue("F".$baris, $bp);
            
            $criteria = new CDbCriteria();
            $criteria->condition = $baseQuery." AND poli_tujuan = '3'";
            $criteria->order = "id";
            $criteria->group = "kunjungan_id";
            $bpg = TransaksiAntrian::model()->count($criteria);
            $activeSheet->setCellValue("G".$baris, $bpg);
            
            $criteria = new CDbCriteria();
            $criteria->condition = $baseQuery." AND poli_tujuan IN(4,5,6)";
            $criteria->order = "id";
            $criteria->group = "kunjungan_id";
            $kia = TransaksiAntrian::model()->count($criteria);
            $activeSheet->setCellValue("H".$baris, $kia);
            
            $criteria = new CDbCriteria();
            $criteria->condition = $baseQuery." AND poli_tujuan = '17'";
            $criteria->order = "id";
            $criteria->group = "kunjungan_id";
            $p2 = TransaksiAntrian::model()->count($criteria);
            $activeSheet->setCellValue("I".$baris, $p2);
            
            $criteria = new CDbCriteria();
            $criteria->condition = $baseQuery." AND poli_tujuan IN(2,12,13)";
            $criteria->order = "id";
            $criteria->group = "kunjungan_id";
            $ugd = TransaksiAntrian::model()->count($criteria);
            $activeSheet->setCellValue("J".$baris, $ugd);
            
            //dalam luar wilayah
            $wilayah = array(0,0,0);
            //jenis pembayaran
            $jp = array(0,0,0,0,0,0);
            
            $trs = TransaksiKunjungan::model()->findAll($baseQuery);
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
                }else if($t->jenis_pembayaran_id == 3){
                    //PBI
                    $jp[0] += 1;
                }else if($t->jenis_pembayaran_id == 5){
                    //NON PBI ASKES / TNI POLRI
                    $jp[1] += 1;
                }else if($t->jenis_pembayaran_id == 4){
                    //NON MANDIRI
                    $jp[2] += 1;
                }else if($t->jenis_pembayaran_id == 6){
                    //JAMKESDA
                    $jp[3] += 1;
                }else if($t->jenis_pembayaran_id == 2){
                    //JAMKESDA
                    $jp[4] += 1;
                }else if(in_array ($t->jenis_pembayaran_id, array(7,8,9,10,11))){
                    //GRATIS
                    $jp[5] += 1;
                }
            }
            $activeSheet->setCellValue("L".$baris, $wilayah[0]);
            $activeSheet->setCellValue("M".$baris, $wilayah[1]);
            $activeSheet->setCellValue("N".$baris, $wilayah[2]);
            
            $activeSheet->setCellValue("O".$baris, $jp[0]);
            $activeSheet->setCellValue("P".$baris, $jp[1]);
            $activeSheet->setCellValue("Q".$baris, $jp[2]);
            $activeSheet->setCellValue("R".$baris, $jp[3]);
            $activeSheet->setCellValue("S".$baris, $jp[4]);
            $activeSheet->setCellValue("T".$baris, $jp[5]);
            
            //rujuk
            //dalam luar wilayah
            $wilayah = array(0,0,0);
            //jenis pembayaran
            $jp = array(0,0,0,0,0,0);
            
            
            $baseQuery = "year(waktu_periksa) = '$year' AND month(waktu_periksa) = '$bulan' AND puskesmas_id = '".$puskesmas->id."'".$filter;
            $trans = TransaksiMedicalRecord::model()->findAll($baseQuery." AND keadaan_akhir_id = '9'");
            foreach($trans as $meds){
                $t = TransaksiKunjungan::model()->findByPk($meds->kunjungan_id);
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
                }else if($t->jenis_pembayaran_id == 3){
                    //PBI
                    $jp[0] += 1;
                }else if($t->jenis_pembayaran_id == 5){
                    //NON PBI ASKES / TNI POLRI
                    $jp[1] += 1;
                }else if($t->jenis_pembayaran_id == 4){
                    //NON MANDIRI
                    $jp[2] += 1;
                }else if($t->jenis_pembayaran_id == 6){
                    //JAMKESDA
                    $jp[3] += 1;
                }else if($t->jenis_pembayaran_id == 2){
                    //JAMKESDA
                    $jp[4] += 1;
                }else if(in_array ($t->jenis_pembayaran_id, array(7,8,9,10,11))){
                    //GRATIS
                    $jp[5] += 1;
                }
            }
            $activeSheet->setCellValue("V".$baris, $wilayah[0]);
            $activeSheet->setCellValue("W".$baris, $wilayah[1]);
            $activeSheet->setCellValue("X".$baris, $wilayah[2]);
            
            $activeSheet->setCellValue("Y".$baris, $jp[0]);
            $activeSheet->setCellValue("Z".$baris, $jp[1]);
            $activeSheet->setCellValue("AA".$baris, $jp[2]);
            $activeSheet->setCellValue("AB".$baris, $jp[3]);
            $activeSheet->setCellValue("AC".$baris, $jp[4]);
            $activeSheet->setCellValue("AD".$baris, $jp[5]);
            
            $baris++;
        }
        
        return array(
            "phpexcel" => $objPHPExcel,
            "name" => $reportName . "_" . $year
        );
    }
    
    public function download($year, $departemen_id){
        $this->downloadFile($this->process($year, $departemen_id));
    }
    
    public function preview($year, $departemen_id){
        $this->previewFile($this->process($year, $departemen_id));
    }
    
    public function download2($year, $departemen_id){
        $this->downloadFile($this->process2($year, $departemen_id));
    }
    
    public function preview2($year, $departemen_id){
        $this->previewFile($this->process2($year, $departemen_id));
    }
}