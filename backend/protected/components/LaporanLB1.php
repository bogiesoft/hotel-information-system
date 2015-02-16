<?php

/**
 * Description of LaporanLB1
 *
 * @author feb
 */
class LaporanLB1 extends BaseLaporan {

    public function __construct() {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
    }

    private function getKategoriUmur($pasien) {
        $tz = new DateTimeZone('Europe/Brussels');
        //echo $pasien->tanggal_lahir."<br>";
        if($pasien->tanggal_lahir != NULL){
            $diff = DateTime::createFromFormat('Y-m-d', $pasien->tanggal_lahir, $tz)->diff(new DateTime('now', $tz));
            $tahun = $diff->y;
            $bulan = $diff->m;
            $hari = $diff->d;
            $jmlHari = $diff->days;
        } else {
            $tahun = $pasien->umur;
            $jmlHari = $tahun * 365;
        }



        if ($jmlHari <= 7) {
            return 0;
        } else if ($jmlHari >= 8 && $jmlHari <= 28) {
            return 1;
        } else if ($jmlHari <= 365) {
            return 2;
        } else if ($tahun >= 1 && $tahun <= 4) {
            return 3;
        } else if ($tahun >= 5 && $tahun < 6) {
            return 4;
        } else if ($tahun >= 6 && $tahun <= 9) {
            return 5;
        } else if ($tahun >= 10 && $tahun <= 11) {
            return 6;
        } else if ($tahun >= 12 && $tahun <= 14) {
            return 7;
        } else if ($tahun >= 15 && $tahun <= 17) {
            return 8;
        } else if ($tahun >= 18 && $tahun <= 19) {
            return 9;
        } else if ($tahun >= 20 && $tahun <= 24) {
            return 10;
        } else if ($tahun >= 25 && $tahun <= 34) {
            return 11;
        } else if ($tahun >= 35 && $tahun <= 44) {
            return 12;
        } else if ($tahun >= 45 && $tahun <= 54) {
            return 13;
        } else if ($tahun >= 55 && $tahun <= 59) {
            return 14;
        } else if ($tahun >= 60 && $tahun <= 64) {
            return 15;
        } else if ($tahun >= 65 && $tahun <= 69) {
            return 16;
        } else if ($tahun >= 70) {
            return 17;
        }
    }

    private function process($month, $year, $departemen_id, $kategori_penyakit) {
        $reportName = "laporan_lb1";

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
        $activeSheet->getCell("B3")->setValue(": " . Setting::getNamaPuskesmas());
        $activeSheet->getCell("B4")->setValue(": " . Setting::getKodePuskesmas());
        $activeSheet->getCell("B5")->setValue(": " . CHtml::value(WilayahKabupaten::model()->findByPk(Setting::getKabupaten()), "nama"));
        $activeSheet->getCell("AW4")->setValue($namaBulan[$month]);
        $activeSheet->getCell("AW5")->setValue($year);

        $filter = "";
        if (substr("" . $departemen_id, 0, 4) == "wil_") {
            $puskesmas = Puskesmas::model()->findByPk(substr("" . $departemen_id, 4));
            $departemen = NULL;
        } else {
            $departemen = Departemen::model()->findByPk($departemen_id);
            $puskesmas = Puskesmas::model()->findByPk($departemen->puskesmas_id);
            $filter = " AND departemen_id = '" . $departemen->id . "'";
        }

        $kodePusk = Setting::getKodePuskesmas();

        //konten
        $globaldata = array();
        $baris = 11;
        $trs = array();
        if ($kategori_penyakit == "") {
            //$trs = Penyakit::model()->findAll("lb1_status = '1'");
            foreach(Penyakit::model()->findAll("lb1_status = '1'") as $p){
                $trs[] = $p->id;
            }
        } else {
            foreach (Lb1JenisLaporanDetail::model()->findAllByAttributes(array("jenis_id" => $kategori_penyakit)) as $detail) {
                //$penyakit = Penyakit::model()->findByAttributes(array("id" => $detail->penyakit_id));
                $trs[] = $detail->penyakit_id;
            }
        }
        
        $jumlahData = 0;
        
        foreach ($trs as $p) {
            $activeSheet->getCellByColumnAndRow(0, $baris)->setValue($kodePusk);
            $activeSheet->getCellByColumnAndRow(1, $baris)->setValue($namaBulan[$month]);
            $activeSheet->getCellByColumnAndRow(2, $baris)->setValue($year);
            $activeSheet->getCellByColumnAndRow(3, $baris)->setValue($p->kode);
            $activeSheet->getCellByColumnAndRow(4, $baris)->setValue($p->nama_indonesia);
            //$activeSheet->getCell("CE".$baris)->setValue($p->id);

            $output = array();

            for ($i = 0; $i < 18; $i++) {
                $output[$i . "_L_B"] = 0;
                $output[$i . "_L_L"] = 0;
                $output[$i . "_P_B"] = 0;
                $output[$i . "_P_L"] = 0;
            }

            $globaldata[$p] = $output;
            $baris ++;
            $jumlahData++;
        }

        $diags = TransaksiDiagnosa::model()->findAll("puskesmas_id = '" . $puskesmas->id . "' AND year(waktu) = '{$year}' AND month(waktu) = '{$month}'" . $filter);
        foreach ($diags as $diag) {
            $med = TransaksiMedicalRecord::model()->findByPk($diag->medical_record_id);
            $pasien = Pasien::model()->findByPk($med->pasien_id);
            $jenis_kelamin = $pasien->jenis_kelamin;
            $kategoriUmur = $this->getKategoriUmur($pasien);
            if(in_array($diag->penyakit_id, $trs)){
                $globaldata[$diag->penyakit_id][$kategoriUmur . "_" . $jenis_kelamin . "_" . $diag->jenis_kasus] += 1;
            }
        }

        $jumlahIsi = 0;
        $baris = 11;
        foreach ($globaldata as $penyakit_id => $output) {

            /*
              $kunjungans = TransaksiKunjungan::model()->findAll("puskesmas_id = '{$puskemas_id}' AND year(waktu) = '{$year}' AND month(waktu) = '{$month}'");
              foreach($kunjungans as $kunjungan){
              $pasien = Pasien::model()->findByPk($kunjungan->pasien_id);
              $jenis_kelamin = $pasien->jenis_kelamin;

              //get kategori umur
              $kategoriUmur = $this->getKategoriUmur($pasien);

              $medRecArray = array();
              $antrians = TransaksiAntrian::model()->findAll("kunjungan_id = '{$kunjungan->id}'");
              foreach($antrians as $antrian){
              $medRecArray[$antrian->medical_record_id] = 1;
              }

              foreach($medRecArray as $key => $val){
              $diags = TransaksiDiagnosa::model()->findAll("puskesmas_id = '{$puskemas_id}' AND medical_record_id = '{$key}' AND penyakit_id = '{$p->id}'");
              foreach($diags as $diag){
              $output[$kategoriUmur."_".$jenis_kelamin."_".$diag->jenis_kasus] += 1;
              }
              }
              } */

            $jml = array();
            $jml["L_B"] = 0;
            $jml["L_L"] = 0;
            $jml["P_B"] = 0;
            $jml["P_L"] = 0;

            for ($kat = 0; $kat <= 17; $kat ++) {
                $kolom = 10 + $kat * 4;
                $activeSheet->getCellByColumnAndRow($kolom, $baris)->setValue($output[$kat . "_L_B"]);
                $activeSheet->getCellByColumnAndRow($kolom + 1, $baris)->setValue($output[$kat . "_L_L"]);
                $activeSheet->getCellByColumnAndRow($kolom + 2, $baris)->setValue($output[$kat . "_P_B"]);
                $activeSheet->getCellByColumnAndRow($kolom + 3, $baris)->setValue($output[$kat . "_P_L"]);
                $jml["L_B"] += $output[$kat . "_L_B"];
                $jml["L_L"] += $output[$kat . "_L_L"];
                $jml["P_B"] += $output[$kat . "_P_B"];
                $jml["P_L"] += $output[$kat . "_P_L"];
            }
            
            $p = Penyakit::model()->findByAttributes(array("id"=>$penyakit_id));
            $activeSheet->getCellByColumnAndRow(0, $baris)->setValue($kodePusk);
            $activeSheet->getCellByColumnAndRow(1, $baris)->setValue($namaBulan[$month]);
            $activeSheet->getCellByColumnAndRow(2, $baris)->setValue($year);
            $activeSheet->getCellByColumnAndRow(3, $baris)->setValue($p->kode);
            $nama = $p->nama_indonesia;
            if($nama == ""){
                $nama = $p->nama;
            }
            $activeSheet->getCellByColumnAndRow(4, $baris)->setValue($nama);

            $activeSheet->getCellByColumnAndRow(5, $baris)->setValue("=SUM(G" . $baris . ":J" . $baris . ")");
            $activeSheet->getCellByColumnAndRow(6, $baris)->setValue($jml["L_B"]);
            $activeSheet->getCellByColumnAndRow(7, $baris)->setValue($jml["L_L"]);
            $activeSheet->getCellByColumnAndRow(8, $baris)->setValue($jml["P_B"]);
            $activeSheet->getCellByColumnAndRow(9, $baris)->setValue($jml["P_L"]);
            
            /*
            if($jumlahIsi>$jumlahData){
                $p = Penyakit::model()->findByAttributes(array("id"=>$penyakit_id));
                $activeSheet->getCellByColumnAndRow(0, $baris)->setValue($kodePusk);
                $activeSheet->getCellByColumnAndRow(1, $baris)->setValue($namaBulan[$month]);
                $activeSheet->getCellByColumnAndRow(2, $baris)->setValue($year);
                $activeSheet->getCellByColumnAndRow(3, $baris)->setValue($p->kode);
                $nama = $p->nama_indonesia;
                if($nama == ""){
                    $nama = $p->nama;
                }
                $activeSheet->getCellByColumnAndRow(4, $baris)->setValue($nama);
                $this->fillBorder($activeSheet, "A".$baris.":CD".$baris);
            }*/
            
            //$activeSheet->getCell("CF".$baris)->setValue($penyakit_id);
            $baris ++;
            $jumlahIsi++;
        }
        
        $this->fillBorder($activeSheet, "A11:CD".($baris-1));

        return array(
            "phpexcel" => $objPHPExcel,
            "name" => $reportName . "_" . $namaBulan[$month] . "_" . $year
        );
    }

    //$year, $month, $puskemas_id
    public function download($bulan, $tahun, $departemen_id, $kategori_penyakit) {
        $this->downloadFile($this->process($bulan, $tahun, $departemen_id, $kategori_penyakit));
    }

    public function preview($bulan, $tahun, $departemen_id, $kategori_penyakit) {
        $this->previewFile($this->process($bulan, $tahun, $departemen_id, $kategori_penyakit));
    }

}
