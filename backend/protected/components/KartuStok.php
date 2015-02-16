<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of KartuStok
 *
 * @author feb
 */
class KartuStok {

    public static function kalkulasiStokObat($id, $tujuan_id = 0, $departemen_id, $puskesmas_id, $include_obat_rusak = TRUE) {
        $dataTransaksi = array();

        //untuk satu wilayah atau satu departemen
        if ($tujuan_id == 0) {
            //untuk satu wilayah
            if($departemen_id == 0){
                //ambil data stok awal
                $list = Yii::app()->db->createCommand(
                                "select stok_awal.tanggal, stok_awal_detail.obat_id, stok_awal_detail.jumlah
                        from stok_awal_detail, stok_awal
                    where 
                        stok_awal_detail.stok_awal_id = stok_awal.id 
                        and stok_awal.puskesmas_id = '$puskesmas_id'
                        and stok_awal_detail.obat_id = '$id'")->queryAll();

                foreach ($list as $item) {
                    $obj = array();
                    $obj["date"] = $item['tanggal'];
                    $obj["keterangan"] = "Stok Awal";
                    $obj["masuk"] = $item['jumlah'];
                    $obj["keluar"] = 0;
                    $obj["data"] = "STOK_AWAL";
                    $dataTransaksi[] = $obj;
                }

                //ambil data penerimaan
                $list = Yii::app()->db->createCommand(
                                "select penerimaan_obat.tanggal, penerimaan_obat_detail.obat_id, penerimaan_obat_detail.jumlah
                        from penerimaan_obat_detail, penerimaan_obat
                    where 
                        penerimaan_obat_detail.penerimaan_obat_id = penerimaan_obat.id 
                        and penerimaan_obat.puskesmas_id = '$puskesmas_id'
                        and penerimaan_obat_detail.obat_id = '$id'")->queryAll();

                foreach ($list as $item) {
                    $obj = array();
                    $obj["date"] = $item['tanggal'];
                    $obj["keterangan"] = "Penerimaan dari GFLK";
                    $obj["masuk"] = $item['jumlah'];
                    $obj["keluar"] = 0;
                    $obj["data"] = "PENERIMAAN";
                    $dataTransaksi[] = $obj;
                }

                //ambil data pemakaian (obat keluar)
                $filter = array(
                    "puskesmas_id" => $puskesmas_id,
                    "obat_id" => $id
                );
                $pemakaianArr = PemakaianObat::model()->findAllByAttributes($filter);
                foreach ($pemakaianArr as $pakai) {
                    $tujuan = DistribusiObatTujuan::model()->findByPk($pakai->distribusi_obat_tujuan_id);
                    $departemen = Departemen::model()->findByPk($pakai->departemen_id);
                    $obj = array();
                    $obj["date"] = $pakai->tanggal;
                    $obj["keterangan"] = "Pemakaian " . ucwords(strtolower($tujuan->nama)) . " " . ucwords(strtolower($departemen->nama));
                    if ($pakai->keterangan != "") {
                        $obj["keterangan"] = $pakai->keterangan;
                    }
                    $obj["masuk"] = 0;
                    $obj["keluar"] = $pakai->jumlah;
                    $obj["data"] = "PEMAKAIAN";
                    $dataTransaksi[] = $obj;
                }
            }else{
                //ambil data transfer dari gudang obat
                $list = Yii::app()->db->createCommand(
                    "select distribusi_obat.tanggal, distribusi_obat.asal_id, distribusi_obat.asal_departemen_id, distribusi_obat.tujuan_id, distribusi_obat.departemen_id, distribusi_obat_detail.obat_id, distribusi_obat_detail.jumlah
                         from distribusi_obat_detail, distribusi_obat
                     where 
                         distribusi_obat_detail.distribusi_obat_id = distribusi_obat.id 
                         and distribusi_obat.asal_id = '1'
                         and distribusi_obat.departemen_id = '".$departemen_id."'
                         and distribusi_obat.puskesmas_id = '" . $puskesmas_id . "' 
                         and distribusi_obat_detail.obat_id = '$id'")->queryAll();

                foreach ($list as $item) {
                    $obj = array();
                    $obj["date"] = $item['tanggal'];
                    
                    $tujuan = DistribusiObatTujuan::model()->findByPk($item["asal_id"]);
                    $departemen = Departemen::model()->findByPk($item["asal_departemen_id"]);
                    $obj["keterangan"] = "Transfer dari " . $tujuan->nama . " " . $departemen->nama;
                    $obj["masuk"] = $item['jumlah'];
                    $obj["keluar"] = 0;
                    $obj["data"] = "DISTRIBUSI";
                    $dataTransaksi[] = $obj;
                }
                
                //ambil data pemakaian (obat keluar)
                $filter = array(
                    "puskesmas_id" => $puskesmas_id,
                    "obat_id" => $id,
                    "departemen_id" => $departemen_id
                );
                $pemakaianArr = PemakaianObat::model()->findAllByAttributes($filter);
                foreach ($pemakaianArr as $pakai) {
                    $tujuan = DistribusiObatTujuan::model()->findByPk($pakai->distribusi_obat_tujuan_id);
                    $departemen = Departemen::model()->findByPk($pakai->departemen_id);
                    $obj = array();
                    $obj["date"] = $pakai->tanggal;
                    $obj["keterangan"] = "Pemakaian " . ucwords(strtolower($tujuan->nama)) . " " . ucwords(strtolower($departemen->nama));
                    if ($pakai->keterangan != "") {
                        $obj["keterangan"] = $pakai->keterangan;
                    }
                    $obj["masuk"] = 0;
                    $obj["keluar"] = $pakai->jumlah;
                    $obj["data"] = "PEMAKAIAN";
                    $dataTransaksi[] = $obj;
                }
            }
        }else if ($tujuan_id == 1) {
            //PENERIMAAN OBAT DARI GFLK
            //ambil data stok awal
            $list = Yii::app()->db->createCommand(
                "select stok_awal.tanggal, stok_awal_detail.obat_id, stok_awal_detail.jumlah
                    from stok_awal_detail, stok_awal
                where 
                    stok_awal_detail.stok_awal_id = stok_awal.id 
                    and stok_awal.puskesmas_id = '$puskesmas_id'
                    and stok_awal_detail.obat_id = '$id'")->queryAll();

            foreach ($list as $item) {
                $obj = array();
                $obj["date"] = $item['tanggal'];
                $obj["keterangan"] = "Stok Awal";
                $obj["masuk"] = $item['jumlah'];
                $obj["keluar"] = 0;
                $obj["data"] = "STOK_AWAL";
                $dataTransaksi[] = $obj;
            }

            //ambil data penerimaan
            $list = Yii::app()->db->createCommand(
                "select penerimaan_obat.tanggal, penerimaan_obat_detail.obat_id, penerimaan_obat_detail.jumlah
                    from penerimaan_obat_detail, penerimaan_obat
                where 
                    penerimaan_obat_detail.penerimaan_obat_id = penerimaan_obat.id 
                    and penerimaan_obat.puskesmas_id = '$puskesmas_id'
                    and penerimaan_obat_detail.obat_id = '$id'")->queryAll();

            foreach ($list as $item) {
                $obj = array();
                $obj["date"] = $item['tanggal'];
                $obj["keterangan"] = "Penerimaan dari GFLK";
                $obj["masuk"] = $item['jumlah'];
                $obj["keluar"] = 0;
                $obj["data"] = "PENERIMAAN";
                $dataTransaksi[] = $obj;
            }
            
            //ambil data distribusi 
            $list = Yii::app()->db->createCommand(
                "select distribusi_obat.tanggal, distribusi_obat.asal_id, distribusi_obat.asal_departemen_id, distribusi_obat.tujuan_id, distribusi_obat.departemen_id, distribusi_obat_detail.obat_id, distribusi_obat_detail.jumlah
                     from distribusi_obat_detail, distribusi_obat
                 where 
                     distribusi_obat_detail.distribusi_obat_id = distribusi_obat.id 
                     and distribusi_obat.puskesmas_id = '" . $puskesmas_id . "' 
                     and distribusi_obat_detail.obat_id = '$id'")->queryAll();

            foreach ($list as $item) {
                $obj = array();
                $obj["date"] = $item['tanggal'];
                if($item["asal_id"] == 1){
                    $tujuan = DistribusiObatTujuan::model()->findByPk($item["tujuan_id"]);
                    $departemen = Departemen::model()->findByPk($item["departemen_id"]);
                    $obj["keterangan"] = "Distribusi ke " . $tujuan->nama . " " . $departemen->nama;
                    $obj["masuk"] = 0;
                    $obj["keluar"] = $item['jumlah'];
                }else if($item["tujuan_id"] == 1){
                    $tujuan = DistribusiObatTujuan::model()->findByPk($item["asal_id"]);
                    $departemen = Departemen::model()->findByPk($item["asal_departemen_id"]);
                    $obj["keterangan"] = "Pengembalian dari " . $tujuan->nama . " " . $departemen->nama;
                    $obj["masuk"] = $item['jumlah'];
                    $obj["keluar"] = 0;
                }
                $obj["data"] = "DISTRIBUSI";
                $dataTransaksi[] = $obj;
            }
        } else {
            //STOK OBAT DI APOTEK / PUSTU / DLL
            //ambil data penerimaan dari distribusi (obat masuk)
            
            //ambil data distribusi 
            $list = Yii::app()->db->createCommand(
                "select distribusi_obat.tanggal, distribusi_obat.asal_id, distribusi_obat.asal_departemen_id, distribusi_obat.tujuan_id, distribusi_obat.departemen_id, distribusi_obat_detail.obat_id, distribusi_obat_detail.jumlah
                     from distribusi_obat_detail, distribusi_obat
                 where 
                     distribusi_obat_detail.distribusi_obat_id = distribusi_obat.id 
                     and 
                     ((
                        distribusi_obat.tujuan_id = '" . $tujuan_id . "' 
                        and 
                        distribusi_obat.departemen_id = '" . $departemen_id . "' 
                     ) 
                     or 
                     (
                        distribusi_obat.asal_id = '" . $tujuan_id . "' 
                        and 
                        distribusi_obat.asal_departemen_id = '" . $departemen_id . "' 
                     ))
                     and distribusi_obat.puskesmas_id = '" . $puskesmas_id . "' 
                     and distribusi_obat_detail.obat_id = '$id'")->queryAll();

            foreach ($list as $item) {
                $obj = array();
                $obj["date"] = $item['tanggal'];
                if($item["asal_id"] == 1){
                    $tujuan = DistribusiObatTujuan::model()->findByPk($item["asal_id"]);
                    $departemen = Departemen::model()->findByPk($item["asal_departemen_id"]);
                    $obj["keterangan"] = "Transfer dari " . $tujuan->nama . " " . $departemen->nama;
                    $obj["masuk"] = $item['jumlah'];
                    $obj["keluar"] = 0;
                }else if($item["tujuan_id"] == 1){
                    $tujuan = DistribusiObatTujuan::model()->findByPk($item["tujuan_id"]);
                    $departemen = Departemen::model()->findByPk($item["departemen_id"]);
                    $obj["keterangan"] = "Pengembalian ke " . $tujuan->nama . " " . $departemen->nama;
                    $obj["masuk"] = 0;
                    $obj["keluar"] = $item['jumlah'];
                }
                $obj["data"] = "DISTRIBUSI";
                $dataTransaksi[] = $obj;
            }

            //ambil data pemakaian (obat keluar)
            $pemakaianArr = PemakaianObat::model()->findAllByAttributes(array(
                "puskesmas_id" => $puskesmas_id,
                "obat_id" => $id,
                "distribusi_obat_tujuan_id" => $tujuan_id,
                "departemen_id" => $departemen_id,
            ));
            foreach ($pemakaianArr as $pakai) {
                $tujuan = DistribusiObatTujuan::model()->findByPk($pakai->distribusi_obat_tujuan_id);
                $departemen = Departemen::model()->findByPk($pakai->departemen_id);
                $obj = array();
                $obj["date"] = $pakai->tanggal;
                $obj["keterangan"] = "Pemakaian " . ucwords(strtolower($tujuan->nama)) . " " . ucwords(strtolower($departemen->nama));
                if ($pakai->keterangan != "") {
                    $obj["keterangan"] = $pakai->keterangan;
                }
                $obj["masuk"] = 0;
                $obj["keluar"] = $pakai->jumlah;
                $obj["data"] = "PEMAKAIAN";
                $dataTransaksi[] = $obj;
            }
        }
        
        //ambil data stock opname
        $stockOpnameArr = StockOpnameObat::model()->findAllByAttributes(array(
            "puskesmas_id" => $puskesmas_id,
            "obat_id" => $id,
            "distribusi_obat_tujuan_id" => $tujuan_id,
            "departemen_id" => $departemen_id,
        ));
        foreach ($stockOpnameArr as $pakai) {
            $tujuan = DistribusiObatTujuan::model()->findByPk($pakai->distribusi_obat_tujuan_id);
            $departemen = Departemen::model()->findByPk($pakai->departemen_id);
            $obj = array();
            $obj["date"] = date("Y-m-d", strtotime($pakai->waktu));
            $obj["keterangan"] = "Perubahan Stok Oleh ".User::model()->findByPk($pakai->user_id)->nama_lengkap;
            $obj["masuk"] = $pakai->jumlah_masuk;
            $obj["keluar"] = $pakai->jumlah_keluar;
            $obj["data"] = "STOCK_OPNAME";
            $dataTransaksi[] = $obj;
        }
        
        if($include_obat_rusak == TRUE){
            //ambil data obat rusak
            $list = Yii::app()->db->createCommand(
                "select 
                    obat_rusak.waktu, obat_rusak.distribusi_obat_tujuan_id, obat_rusak.departemen_id, obat_rusak.keterangan,
                    obat_rusak_detail.obat_id, obat_rusak_detail.jumlah
                 from obat_rusak_detail, obat_rusak
                 where 
                    obat_rusak.distribusi_obat_tujuan_id = '".$tujuan_id."'
                    AND obat_rusak.departemen_id = '".$departemen_id."'
                    AND obat_rusak_detail.obat_rusak_id = obat_rusak.id 
                    AND obat_rusak.puskesmas_id = '" . $puskesmas_id . "' 
                    AND obat_rusak_detail.obat_id = '$id'")->queryAll();

            foreach ($list as $item) {
                $obj = array();
                $obj["date"] = date("Y-m-d", strtotime($item['waktu']));
                $obj["keterangan"] = "Obat Rusak, Ket : ".$item["keterangan"];
                $obj["masuk"] = 0;
                $obj["keluar"] = $item['jumlah'];
                $obj["data"] = "OBAT_RUSAK";
                $dataTransaksi[] = $obj;
            }
        }

        //echo count($dataTransaksi)."<br>";

        usort($dataTransaksi, array("KartuStok", "compareData"));

        return $dataTransaksi;
    }
    
    public static function getLPLPOData($month, $year, $jenisLaporan, $puskesmas_id, $departemen_id){
        $output = array();
        $obats = Obat::model()->findAll();
        foreach ($obats as $obat) {
            $stokAwal = 0;
            $penerimaan = 0;
            $pemakaian = 0;
            $saldo = 0;
            $info = array();
            if($jenisLaporan == "summary"){
                //summary seluruh wilayah
                $info = KartuStok::kalkulasiStokObat($obat->id, 0, 0, $puskesmas_id);
            }else if($jenisLaporan == "per_departemen"){
                $info = KartuStok::kalkulasiStokObat($obat->id, 0, $departemen_id, $puskesmas_id);
            }
            foreach ($info as $inf) {
                //echo $year.str_pad($month, 2, "0", STR_PAD_LEFT) . " DAN " . $bulanTahun;
                $bulanTahun = date("Ym", strtotime($inf["date"]));
                if($year.str_pad($month, 2, "0", STR_PAD_LEFT) == $bulanTahun){
                    $penerimaan += $inf["masuk"];
                    $pemakaian += $inf["keluar"];
                }else if($bulanTahun*1 < ($year.str_pad($month, 2, "0", STR_PAD_LEFT))*1){
                    $stokAwal += $inf["masuk"];
                    $stokAwal -= $inf["keluar"];
                }
            }
            
            $rusak = KartuStok::getJumlahObatRusak($puskesmas_id, $departemen_id, $obat->id, $year, $month);
            $saldo = $stokAwal + $penerimaan - $pemakaian - $rusak;
            $obj = array();
            $obj['nama_obat'] = $obat->nama;
            $obj['kemasan'] = CHtml::value(ObatSatuan::model()->findByPk($obat->kemasan_id), "nama", "-");
            $obj['stok_awal'] = $stokAwal;
            $obj['penerimaan'] = $penerimaan;
            $obj['pemakaian'] = $pemakaian;
            $obj['saldo'] = $saldo;
            $obj['rusak'] = $rusak;
            $output[] = $obj;
        }
        return $output;
    }
    
    public static function getJumlahObatRusak($puskesmas_id, $departemen_id, $obat_id, $tahun, $bulan){
        //ambil data obat rusak
        $sql = "select 
                obat_rusak.waktu, obat_rusak.distribusi_obat_tujuan_id, obat_rusak.departemen_id, obat_rusak.keterangan,
                obat_rusak_detail.obat_id, obat_rusak_detail.jumlah
             from obat_rusak_detail, obat_rusak
             where 
                
                obat_rusak_detail.obat_rusak_id = obat_rusak.id 
                AND obat_rusak.puskesmas_id = '" . $puskesmas_id . "' 
                AND year(obat_rusak.waktu) = '".$tahun."'
                AND month(obat_rusak.waktu) = '".$bulan."'
                AND obat_rusak_detail.obat_id = '$obat_id'";
        
        if($departemen_id != NULL){
            $sql .= " AND obat_rusak.departemen_id = '".$departemen_id."' ";
        }
        
        $list = Yii::app()->db->createCommand($sql)->queryAll();
        
        $jml = 0;

        foreach ($list as $item) {
            $jml += $item['jumlah'];
        }
        
        return $jml;
    }

    public static function compareData($a, $b) {
        $dateA = strtotime($a["date"]);
        $dateB = strtotime($b["date"]);
        if ($dateA == $dateB) {
            return 0;
        }
        return ($dateA < $dateB) ? -1 : 1;
    }
    
    public static function adjustStokObat($tujuan_id, $departemen_id, $puskesmas_id){
        foreach (Obat::model()->findAll() as $obat) {
            $dataTransaksi = KartuStok::kalkulasiStokObat($obat->id, $tujuan_id, $departemen_id, $puskesmas_id);

            $saldo = 0;
            foreach ($dataTransaksi as $key => $trans) {
                $saldo = $saldo + $trans["masuk"] - $trans["keluar"];
            }

            $obatDept = ObatDepartemen::model()->findByAttributes(
                    array("puskesmas_id" => $puskesmas_id,
                        "departemen_id" => $departemen_id,
                        "distribusi_obat_tujuan_id" => $tujuan_id,
                        "obat_id" => $obat->id));
            if ($obatDept == NULL) {
                $obatDept = new ObatDepartemen();
                $obatDept->puskesmas_id = $puskesmas_id;
                $obatDept->departemen_id = $departemen_id;
                $obatDept->distribusi_obat_tujuan_id = $tujuan_id;
                $obatDept->obat_id = $obat->id;
            }
            $obatDept->stok = $saldo;
            $obatDept->save();
        }
    }

}
