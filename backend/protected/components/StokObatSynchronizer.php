<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * class yang digunakan untuk menggenerate stok obat lama
 * di database. fungsi ini dipanggil ketika login.
 *
 * @author feb
 */
class StokObatSynchronizer {
    public static function synchronize($puskesmas_id){
        //get bulan di database
        $value = Yii::app()->cache->get("bulan_stok");
        if($value === false){
            //jika nggak ada, maka simpan data dulu.
            Yii::app()->cache->set("bulan_stok", date("Ym"));
        }else{
            //jika ada, bandingkan
            if($value*1 < date("Ym")*1){
                //jika ada yg lebih baru (ganti bulan)
                //maka lakukan generate
                StokObatSynchronizer::generate($puskesmas_id);
                Yii::app()->cache->set("bulan_stok", date("Ym"));
            }else{
                //jika tidak alias sama, maka nggak ngapa2in.
            }
        }
    }
    
    public static function generate($puskesmas_id){
        $obats = Obat::model()->findAll();
        foreach ($obats as $obat) {
            $o = new ObatStokHistory();
            $o->bulan = date("m", strtotime(date("Y-m-d")." -1 month"));
            $o->tahun = date("Y", strtotime(date("Y-m-d")." -1 month"));
            $o->puskesmas_id = $puskesmas_id;
            $o->obat_id = $obat->id;
            $o->jumlah = $obat->getStokTotal($puskesmas_id);
            $o->save();
        }
    }
}
