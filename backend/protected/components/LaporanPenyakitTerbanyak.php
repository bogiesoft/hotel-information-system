<?php

/**
 * Description of LaporanPenyakitTerbanyak
 *
 * @author feb
 */

class LaporanPenyakitTerbanyak extends BaseLaporan {
    
    public function __construct() {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
    }
    
    private function process($tanggal, $tanggal2, $departemen_id){
        $reportName = "laporan_penyakit_terbanyak";
		
		$objPHPExcel = $this->loadPHPExcelLib($reportName);
		$objPHPExcel->setActiveSheetIndex(0);
		$activeSheet = $objPHPExcel->getActiveSheet();
        
        //judul
        $activeSheet->getCell("A3")->setValue("Tanggal ".$tanggal." s/d ".$tanggal2);
        
        $filter = "";
        if(substr("".$departemen_id, 0, 4) == "wil_"){
            $puskesmas = Puskesmas::model()->findByPk(substr("".$departemen_id, 4));
            $departemen = NULL;
            $activeSheet->getCell("A2")->setValue("Wilayah Puskesmas ".ucwords(strtolower($puskesmas->nama)));
            $activeSheet->getCell("A29")->setValue("Sumber : Laporan Wilayah Puskesmas ".ucwords(strtolower($puskesmas->nama)));
        }else{
            $departemen = Departemen::model()->findByPk($departemen_id);
            $puskesmas = Puskesmas::model()->findByPk($departemen->puskesmas_id);
            $activeSheet->getCell("A2")->setValue($departemen->nama);
            $activeSheet->getCell("A29")->setValue("Sumber : Laporan ".$departemen->nama);
            $filter = " AND departemen_id = '".$departemen->id."'";
        }
        
        $tanggal = $this->reverseDate($tanggal);
        $tanggal2 = $this->reverseDate($tanggal2);
        
        //mencari penyakit terbanyak
        $baris = 6;
        $sql = "SELECT penyakit_id, COUNT(*) as is_dirujuk
                FROM `transaksi_diagnosa`
                where jenis_kasus = 'B' and waktu between '$tanggal 00:00:00' AND '$tanggal2 23:59:00' AND puskesmas_id = '".$puskesmas->id."'".$filter."
                group by penyakit_id
                order by is_dirujuk DESC
                limit 0,20";
        $transaksiDiagnosa = TransaksiDiagnosa::model()->findAllBySql($sql);
        foreach ($transaksiDiagnosa as $transaksi) {
            $penyakit = Penyakit::model()->findByAttributes(array("id"=>$transaksi->penyakit_id));
            $nama = $penyakit->nama_indonesia;
            if($nama == NULL){
                $nama = $penyakit->nama;
            }
            $activeSheet->getCell("B".$baris)->setValue($penyakit->kode);
            $activeSheet->getCell("C".$baris)->setValue($nama);
            $activeSheet->getCell("D".$baris)->setValue($transaksi->is_dirujuk);
            $baris++;
        }
        
        $jml = TransaksiDiagnosa::model()->count("waktu between '$tanggal 00:00:00' AND '$tanggal2 23:59:00' AND puskesmas_id = '".$puskesmas->id."'".$filter);
        $activeSheet->getCell("D28")->setValue($jml);
        
        return array(
            "phpexcel" => $objPHPExcel,
            "name" => $reportName . "_" . $tanggal . "_" . $tanggal2
        );
    }
    
    private function processLP($tanggal, $tanggal2, $departemen_id){
        $reportName = "laporan_penyakit_terbanyak_lp";
		
		$objPHPExcel = $this->loadPHPExcelLib($reportName);
		$objPHPExcel->setActiveSheetIndex(0);
		$activeSheet = $objPHPExcel->getActiveSheet();
        
        $filter = "";
        if(substr("".$departemen_id, 0, 4) == "wil_"){
            $puskesmas = Puskesmas::model()->findByPk(substr("".$departemen_id, 4));
            $departemen = NULL;
            $activeSheet->getCell("A2")->setValue("10 PENYAKIT TERBANYAK DI PUSKESMAS ".ucwords(strtolower($puskesmas->nama)));
            $activeSheet->getCell("A3")->setValue("Tanggal ".$tanggal." s/d ".$tanggal2);
            $activeSheet->getCell("A31")->setValue("Sumber : Laporan Wilayah Puskesmas ".ucwords(strtolower($puskesmas->nama)));
            $activeSheet->getCell("A64")->setValue("Sumber : Laporan Wilayah Puskesmas ".ucwords(strtolower($puskesmas->nama)));
        }else{
            $departemen = Departemen::model()->findByPk($departemen_id);
            $puskesmas = Puskesmas::model()->findByPk($departemen->puskesmas_id);
            $activeSheet->getCell("A2")->setValue("10 PENYAKIT TERBANYAK DI ".ucwords(strtolower($departemen->nama)));
            $activeSheet->getCell("A3")->setValue("Tanggal ".$tanggal." s/d ".$tanggal2);
            $activeSheet->getCell("A31")->setValue("Sumber : Laporan Wilayah ".ucwords(strtolower($departemen->nama)));
            $activeSheet->getCell("A64")->setValue("Sumber : Laporan Wilayah ".ucwords(strtolower($departemen->nama)));
            $filter = " AND transaksi_diagnosa.departemen_id = '".$departemen->id."'";
        }
        
        $tanggal = $this->reverseDate($tanggal);
        $tanggal2 = $this->reverseDate($tanggal2);
        
        //mencari penyakit terbanyak laki-laki
        $baris = 8;
        $sql = "SELECT penyakit_id, COUNT(*) as is_dirujuk
                FROM `transaksi_diagnosa`, `transaksi_medical_record`, `pasien`
                where
                    transaksi_diagnosa.medical_record_id = transaksi_medical_record.id AND
                    transaksi_medical_record.pasien_id = pasien.id AND
                    pasien.jenis_kelamin = 'L' AND
                    jenis_kasus = 'B' and waktu between '$tanggal 00:00:00' AND '$tanggal2 23:59:00' AND transaksi_diagnosa.puskesmas_id = '".$puskesmas->id."'".$filter."
                group by penyakit_id
                order by is_dirujuk DESC
                limit 0,20";
        $transaksiDiagnosa = TransaksiDiagnosa::model()->findAllBySql($sql);
        foreach ($transaksiDiagnosa as $transaksi) {
            $penyakit = Penyakit::model()->findByAttributes(array("id"=>$transaksi->penyakit_id));
            $nama = $penyakit->nama_indonesia;
            if($nama == NULL){
                $nama = $penyakit->nama;
            }
            $activeSheet->getCell("B".$baris)->setValue($penyakit->kode);
            $activeSheet->getCell("C".$baris)->setValue($nama);
            $activeSheet->getCell("D".$baris)->setValue($transaksi->is_dirujuk);
            $baris++;
        }
        
        $jml = TransaksiDiagnosa::model()->countBySql(
                "SELECT COUNT(*)
                FROM `transaksi_diagnosa`, `transaksi_medical_record`, `pasien`
                where
                    transaksi_diagnosa.medical_record_id = transaksi_medical_record.id AND
                    transaksi_medical_record.pasien_id = pasien.id AND
                    pasien.jenis_kelamin = 'L' AND
                    jenis_kasus = 'B' and waktu between '$tanggal 00:00:00' AND '$tanggal2 23:59:00' AND transaksi_diagnosa.puskesmas_id = '".$puskesmas->id."'".$filter."

                order by is_dirujuk DESC
                limit 0,20");
        $activeSheet->getCell("D30")->setValue($jml);
        
        //mencari penyakit terbanyak perempuan
        $baris = 41;
        $sql = "SELECT penyakit_id, COUNT(*) as is_dirujuk
                FROM `transaksi_diagnosa`, `transaksi_medical_record`, `pasien`
                where
                    transaksi_diagnosa.medical_record_id = transaksi_medical_record.id AND
                    transaksi_medical_record.pasien_id = pasien.id AND
                    pasien.jenis_kelamin = 'P' AND
                    jenis_kasus = 'B' and waktu between '$tanggal 00:00:00' AND '$tanggal2 23:59:00' AND transaksi_diagnosa.puskesmas_id = '".$puskesmas->id."'".$filter."
                group by penyakit_id
                order by is_dirujuk DESC
                limit 0,20";
        $transaksiDiagnosa = TransaksiDiagnosa::model()->findAllBySql($sql);
        foreach ($transaksiDiagnosa as $transaksi) {
            $penyakit = Penyakit::model()->findByAttributes(array("id"=>$transaksi->penyakit_id));
            $nama = $penyakit->nama_indonesia;
            if($nama == NULL){
                $nama = $penyakit->nama;
            }
            $activeSheet->getCell("B".$baris)->setValue($penyakit->kode);
            $activeSheet->getCell("C".$baris)->setValue($nama);
            $activeSheet->getCell("D".$baris)->setValue($transaksi->is_dirujuk);
            $baris++;
        }
        
        $jml = TransaksiDiagnosa::model()->countBySql(
                "SELECT COUNT(*)
                FROM `transaksi_diagnosa`, `transaksi_medical_record`, `pasien`
                where
                    transaksi_diagnosa.medical_record_id = transaksi_medical_record.id AND
                    transaksi_medical_record.pasien_id = pasien.id AND
                    pasien.jenis_kelamin = 'P' AND
                    jenis_kasus = 'B' and waktu between '$tanggal 00:00:00' AND '$tanggal2 23:59:00' AND transaksi_diagnosa.puskesmas_id = '".$puskesmas->id."'".$filter."

                order by is_dirujuk DESC
                limit 0,20");
        $activeSheet->getCell("D63")->setValue($jml);
        
        return array(
            "phpexcel" => $objPHPExcel,
            "name" => $reportName . "_" . $tanggal . "_" . $tanggal2
        );
    }
    
    public function download($tanggal, $tanggal2, $departemen_id){
        $this->downloadFile($this->process($tanggal, $tanggal2, $departemen_id));
    }
    
    public function preview($tanggal, $tanggal2, $departemen_id){
        $this->previewFile($this->process($tanggal, $tanggal2, $departemen_id));
    }
    
    public function download2($tanggal, $tanggal2, $departemen_id){
        $this->downloadFile($this->processLP($tanggal, $tanggal2, $departemen_id));
    }
    
    public function preview2($tanggal, $tanggal2, $departemen_id){
        $this->previewFile($this->processLP($tanggal, $tanggal2, $departemen_id));
    }
}