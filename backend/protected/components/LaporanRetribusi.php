<?php

/**
 * Description of LaporanRetribusi
 *
 * @author feb
 */

class LaporanRetribusi extends BaseLaporan {
    
    public function __construct() {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
    }
    
    private function process($tanggal, $tanggal2, $departemen_id){
        $reportName = "laporan_tindakan";
        
        $tahun = date("Y", strtotime($this->reverseDate($tanggal)));
		
		$objPHPExcel = $this->loadPHPExcelLib($reportName);
		$objPHPExcel->setActiveSheetIndex(0);
		$activeSheet = $objPHPExcel->getActiveSheet();
        
        $filter = "";
        if(substr("".$departemen_id, 0, 4) == "wil_"){
            $puskesmas = Puskesmas::model()->findByPk(substr("".$departemen_id, 4));
            $departemen = NULL;
            $activeSheet->setCellValue("A4", "WILAYAH ".$puskesmas->nama);
        }else{
            $departemen = Departemen::model()->findByPk($departemen_id);
            $puskesmas = Puskesmas::model()->findByPk($departemen->puskesmas_id);
            $activeSheet->setCellValue("A4", $departemen->nama);
            $filter = " AND departemen_id = '".$departemen->id."'";
        }
        
        //judul
        $activeSheet->setCellValue("A1", "REKAPITULASI RETRIBUSI PUSKESMAS TAHUN ".$tahun);
        $activeSheet->setCellValue("A3", "PUSKESMAS ".$puskesmas->nama);
        $activeSheet->setCellValue("A4", "Tanggal ".$tanggal." s/d ".$tanggal2);
        
        $tanggal = $this->reverseDate($tanggal);
        $tanggal2 = $this->reverseDate($tanggal2);
        
        $query = "puskesmas_id = '".$puskesmas->id."' AND waktu between '$tanggal 00:00:00' AND '$tanggal2 23:59:00' ";
        
        for($baris = 7;$baris <= 42;$baris ++){
            $i = $activeSheet->getCell("I".$baris)->getValue();
            $j = $activeSheet->getCell("J".$baris)->getValue();
            $k = $activeSheet->getCell("K".$baris)->getValue();
            $l = $activeSheet->getCell("L".$baris)->getValue();
            
            $tes = "0";
            
            $data = "";
            if($i != ""){
                $jml = TransaksiTindakan::model()->count("tindakan_id = '".$i."' AND ".$query.$filter);
                $tes = "1";
            }else if($j != ""){
                //echo "select transaksi_tindakan.* from transaksi_tindakan, tindakan WHERE transaksi_tindakan.tindakan_id = tindakan.id AND tindakan.kategori_id = '".$j."' AND transaksi_tindakan.".$query.$filter."<br>";
                $jml = TransaksiTindakan::model()->countBySql("select count(transaksi_tindakan.id) from transaksi_tindakan, tindakan WHERE transaksi_tindakan.tindakan_id = tindakan.id AND tindakan.kategori_id = '".$j."' AND transaksi_tindakan.".$query.$filter);
                $tes = "2";
            }else if($k != ""){
                $jml = TransaksiLaborat::model()->count("laborat_id = '".$k."' AND ".$query.$filter);
                $tes = "3";
            }else if($l != ""){
                $jml = TransaksiLaborat::model()->countBySql("select count(transaksi_laborat.id) from transaksi_laborat, laborat WHERE transaksi_laborat.laborat_id = laborat.id AND laborat.kategori_id = '".$l."' AND transaksi_laborat.".$query.$filter);
                $tes = "4";
            }
            
            //$activeSheet->setCellValue("H".$baris, $tes);
            
            $activeSheet->setCellValue("E".$baris, $jml*1);
        }
        
        $activeSheet->removeColumn("L");
        $activeSheet->removeColumn("K");
        $activeSheet->removeColumn("J");
        $activeSheet->removeColumn("I");
        
        $askes = TransaksiKunjungan::model()->count("jenis_pembayaran_id = '5' AND ".$query.$filter);
        $gratis = TransaksiKunjungan::model()->count("jenis_pembayaran_id = '11' AND ".$query.$filter);
        
        $activeSheet->setCellValue("E43", $askes);
        $activeSheet->setCellValue("E44", $gratis);
        $activeSheet->setCellValue("F43", $askes);
        $activeSheet->setCellValue("F44", $gratis);
        
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
}