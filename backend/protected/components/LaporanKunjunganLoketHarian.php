<?php

/**
 * Description of LaporanKunjunganLoketHarian
 *
 * @author feb
 */
class LaporanKunjunganLoketHarian extends BaseLaporan {

    public function __construct() {
        ini_set('memory_limit', '-1');
        set_time_limit(0);
    }

    private function process($tanggal, $tanggal2, $departemen_id, $jenis_kunjungan, $jenis_pembayaran_id, $jenis_kelamin, $jenis_laporan, $klinik, $wilayah) {
        $reportName = "laporan_kunjungan_loket_harian";

        $tanggal = $this->reverseDate($tanggal);
        $tanggal2 = $this->reverseDate($tanggal2);

        $objPHPExcel = $this->loadPHPExcelLib($reportName);

        $rekapArray = array();
        foreach (JenisPembayaran::model()->findAll("status = '1'") as $jp) {
            $rekapArray[$jp->nama] = array();
            foreach (Poli::model()->findAllByAttributes(array("jenis" => 1, "status" => 1)) as $poli) {
                $rekapArray[$jp->nama][$poli->nama] = array("L" => 0, "P" => 0);
            }
        }

        $objPHPExcel->setActiveSheetIndex(0);
        $activeSheet = $objPHPExcel->getActiveSheet();

        //judul
        $activeSheet->getCell("B4")->setValue("Tanggal : " . $tanggal);

        $filter = "";
        if (substr("" . $departemen_id, 0, 4) == "wil_") {
            $puskesmas = Puskesmas::model()->findByPk(substr("" . $departemen_id, 4));
            $departemen = NULL;
            $activeSheet->getCell("B3")->setValue("Wilayah Puskesmas " . ucwords(strtolower($puskesmas->nama)));
        } else {
            $departemen = Departemen::model()->findByPk($departemen_id);
            $puskesmas = Puskesmas::model()->findByPk($departemen->puskesmas_id);
            $activeSheet->getCell("B3")->setValue($departemen->nama);
            $filter = " AND departemen_id = '" . $departemen->id . "'";
        }
        
        if($jenis_kunjungan != ""){
            $filter .= " AND jenis_kunjungan = '".$jenis_kunjungan."' ";
        }
        if($jenis_pembayaran_id != ""){
            $filter .= " AND jenis_pembayaran_id = '".$jenis_pembayaran_id."' ";
        }
        
        if($jenis_laporan != ""){
            $arrayDiagnosaKhusus = array();
            foreach (Lb1JenisLaporanDetail::model()->findAllByAttributes(array("jenis_id"=>$jenis_laporan)) as $data) {
                $arrayDiagnosaKhusus[] = $data->penyakit_id;
            }
        }

        //header
        $baris = 8;
        $no = 1;
        $transaksiArray = TransaksiKunjungan::model()->findAll("waktu between '$tanggal 00:00:00' AND '$tanggal2 23:59:00' AND puskesmas_id = '" . $puskesmas->id . "'" . $filter);
        foreach ($transaksiArray as $kunjungan) {
            $pasien = Pasien::model()->findByPk($kunjungan->pasien_id);
            
            if($jenis_kelamin != "" && $pasien->jenis_kelamin != $jenis_kelamin){
                continue;
            }
            
            if($wilayah != "" && $pasien->dalam_wilayah != $wilayah){
                continue;
            }
            
            $ada = FALSE;
            $kosong = TRUE;
            
            $jenis_pembayaran = JenisPembayaran::model()->findByPk($kunjungan->jenis_pembayaran_id);
            $poli = Poli::model()->findByPk($kunjungan->poli_tujuan);

            $diagnosaArray = array();
            $idDiagnosaArray = array();
            $meds = TransaksiMedicalRecord::model()->findAllByAttributes(array("kunjungan_id" => $kunjungan->id));
            foreach ($meds as $med) {
                $diags = TransaksiDiagnosa::model()->findAllByAttributes(array("medical_record_id" => $med->id));
                foreach ($diags as $diag) {
                    $kosong = FALSE;
                    $ada = TRUE;
                    
                    /*
                    if($jenis_laporan != "" && !in_array($diag->penyakit_id, $arrayDiagnosaKhusus)){
                        
                    }else{
                        $ada = TRUE;
                    }*/
                    
                    $penyakit = Penyakit::model()->findByAttributes(array("id" => $diag->penyakit_id));
                    $diagnosaArray[] = "[" . $penyakit->kode . "/" . $diag->jenis_kasus . "] " . ($penyakit->nama_indonesia == "" ? $penyakit->nama : $penyakit->nama_indonesia);
                }
            }
            
            $ada2 = FALSE;
            $kosong2 = TRUE;
            $bpArray = array();
            $ants = TransaksiAntrian::model()->findAllByAttributes(array("kunjungan_id" => $kunjungan->id));
            foreach ($ants as $ant) {
                $kosong2 = FALSE;
                if($klinik != "" && $ant->poli_tujuan != $klinik){
                    
                }else{
                    $ada2 = TRUE;
                }
                $polie = Poli::model()->findByPk($ant->poli_tujuan);
                $rekapArray[$jenis_pembayaran->nama][$polie->nama][$pasien->jenis_kelamin] += 1;
                $bpArray[] = $polie->nama;
            }
            
            if($kosong || $kosong2 || ($ada && $ada2)){
                
            }else{
                continue;
            }

            $activeSheet->getCellByColumnAndRow(1, $baris)->setValue($no . ".");
            $activeSheet->getCellByColumnAndRow(2, $baris)->setValue(date("d-m-Y", strtotime($kunjungan->waktu)));
            $activeSheet->getCellByColumnAndRow(3, $baris)->setValueExplicit($pasien->kode, PHPExcel_Cell_DataType::TYPE_STRING);
            $activeSheet->getCellByColumnAndRow(4, $baris)->setValue($pasien->nama);
            $age = date_diff(date_create($pasien->tanggal_lahir), date_create($kunjungan->waktu))->y;
            if ($age > 200) {
                $age = 0;
            }
            $activeSheet->getCellByColumnAndRow(5, $baris)->setValue($age);
            $activeSheet->getCellByColumnAndRow(6, $baris)->setValue($pasien->jenis_kelamin);
            $activeSheet->getCellByColumnAndRow(7, $baris)->setValue($pasien->alamat);
            $activeSheet->getCellByColumnAndRow(8, $baris)->setValue($pasien->nama_kepala_keluarga);
            $activeSheet->getCellByColumnAndRow(9, $baris)->setValueExplicit($pasien->no_ktp, PHPExcel_Cell_DataType::TYPE_STRING);
            $activeSheet->getCellByColumnAndRow(10, $baris)->setValue($jenis_pembayaran->nama);
            $activeSheet->getCellByColumnAndRow(11, $baris)->setValueExplicit($kunjungan->kode_asuransi, PHPExcel_Cell_DataType::TYPE_STRING);
            $activeSheet->getCellByColumnAndRow(12, $baris)->setValue($kunjungan->jenis_kunjungan == "B" ? "Baru" : "Lama");
            $activeSheet->getCellByColumnAndRow(13, $baris)->setValue(implode(", ", $diagnosaArray)); //diagnosa
            $activeSheet->getCellByColumnAndRow(14, $baris)->setValue(implode(", ", $bpArray)); //bp

            $tujuan = $poli->nama . " : " . ucwords(strtolower(Departemen::model()->findByPk($kunjungan->departemen_id)->nama));
            $activeSheet->getCellByColumnAndRow(15, $baris)->setValue($tujuan);

            $baris++;
            $no++;
        }

        $activeSheet->getStyle("B8:P" . ($baris - 1))->applyFromArray(array(
            'borders' => array(
                'allborders' => array(
                    'style' => PHPExcel_Style_Border::BORDER_THIN
                )
            )
        ));

        //Pindah ke sheet 2
        $objPHPExcel->setActiveSheetIndex(1);
        $activeSheet = $objPHPExcel->getActiveSheet();

        $smbaris = 4;
        foreach ($rekapArray as $key => $rekap) {
            $num = 2;
            $activeSheet->getCellByColumnAndRow(1, $smbaris)->setValue($key);
            foreach (Poli::model()->findAllByAttributes(array("jenis" => 1, "status" => 1)) as $poli) {
                if ($smbaris == 4) {
                    //set judul
                    $activeSheet->getCellByColumnAndRow($num, 2)->setValue($poli->nama);
                    $activeSheet->getCellByColumnAndRow($num, 3)->setValue("Lk");
                    $activeSheet->getCellByColumnAndRow($num + 1, 3)->setValue("Pr");
                    $activeSheet->getCellByColumnAndRow($num + 2, 3)->setValue("Total");
                    $activeSheet->mergeCellsByColumnAndRow($num, 2, $num + 2, 2);
                }
                
                $activeSheet->getCellByColumnAndRow($num, $smbaris)->setValue($rekap[$poli->nama]["L"]);
                $activeSheet->getCellByColumnAndRow($num+1, $smbaris)->setValue($rekap[$poli->nama]["P"]);
                $activeSheet->getCellByColumnAndRow($num+2, $smbaris)->setValue($rekap[$poli->nama]["L"]+$rekap[$poli->nama]["P"]);

                $num+=3;
            }
            
            if($smbaris == 4){
                $activeSheet->getStyle("B2:".$this->get_col_letter($num-1)."3")->applyFromArray(
                    array(
                        'fill' => array(
                            'type' => PHPExcel_Style_Fill::FILL_SOLID,
                            'color' => array('rgb' => '8B0000')
                        ),
                        'font'  => array(
                            'color' => array('rgb' => 'FFFFFF')
                        )
                    )
                );
            }
            
            $smbaris++;
        }
        
        $this->fillBorder($activeSheet, "B2:".$this->get_col_letter($num-1).($smbaris-1));
        $activeSheet->getStyle("B4:".$this->get_col_letter($num-1).($smbaris-1))->applyFromArray(
                    array(
                        'font'  => array(
                            'color' => array('rgb' => '000000'),
                            'size'  => 13,
                            'name'  => 'Calibri'
                        )
                    )
                );
        $activeSheet->getStyle("C4:".$this->get_col_letter($num-1).($smbaris-1))->getAlignment()
            ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        return array(
            "phpexcel" => $objPHPExcel,
            "name" => $reportName . "_" . $tanggal
        );
    }

    function get_col_letter($num) {
        $comp = 0;
        $pre = '';
        $letters = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z');

        //if the number is greater than 26, calculate to get the next letters
        if ($num > 26) {
            //divide the number by 26 and get rid of the decimal
            $comp = floor($num / 26);

            //add the letter to the end of the result and return it
            if ($comp != 0)
            // don't subtract 1 if the comparative variable is greater than 0
                return $this->get_col_letter($comp) . $letters[($num - $comp * 26)];
            else
                return $this->get_col_letter($comp) . $letters[($num - $comp * 26) - 1];
        } else
        //return the letter
            return $letters[($num - 1)];
    }

    public function download($tanggal, $tanggal2, $departemen_id, $jenis_kunjungan, $jenis_pembayaran_id, $jenis_kelamin, $jenis_laporan, $klinik, $wilayah) {
        //$this->downloadFile($this->process($tanggal, $tanggal2, $departemen_id));
        $output = $this->process($tanggal, $tanggal2, $departemen_id, $jenis_kunjungan, $jenis_pembayaran_id, $jenis_kelamin, $jenis_laporan, $klinik, $wilayah);
        //echo $output["name"];
        $this->downloadFile($output);
    }

    public function preview($tanggal, $tanggal2, $departemen_id, $jenis_kunjungan, $jenis_pembayaran_id, $jenis_kelamin, $jenis_laporan, $klinik, $wilayah) {
        $this->previewFile($this->process($tanggal, $tanggal2, $departemen_id, $jenis_kunjungan, $jenis_pembayaran_id, $jenis_kelamin, $jenis_laporan, $klinik, $wilayah));
    }

}
