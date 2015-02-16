<?php

class Tanggal {

    public static function reverse($date) {
        $arr = explode(" ", $date);
        if (count($arr) == 1) {
            $tgl = $arr[0];
            $tglArr = explode("-", $tgl);
            $tgl = implode("-", array_reverse($tglArr));
            return $tgl;
        } else {
            $tgl = $arr[0];
            $jam = $arr[1];
            $tglArr = explode("-", $tgl);
            $tgl = implode("-", array_reverse($tglArr));
            return $tgl . " " . $jam;
        }
    }

    public static function timeElapsedString($ptime) {
        $etime = time() - $ptime;

        if ($etime < 1) {
            return '0 seconds';
        }

        $a = array(365 * 24 * 60 * 60 => 'tahun',
            30 * 24 * 60 * 60 => 'bulan',
            24 * 60 * 60 => 'hari',
            60 * 60 => 'jam',
            60 => 'menit',
            1 => 'detik'
        );
        $a_plural = array('tahun' => 'tahun',
            'bulan' => 'bulan',
            'hari' => 'hari',
            'jam' => 'jam',
            'menit' => 'menit',
            'detik' => 'detik'
        );

        foreach ($a as $secs => $str) {
            $d = $etime / $secs;
            if ($d >= 1) {
                $r = round($d);
                return $r . ' ' . ($r > 1 ? $a_plural[$str] : $str) . ' yang lalu';
            }
        }
    }
    
    public static function getWeekDateRange($date){
        $tahun = date("Y", strtotime($date));
        $bulan = date("m", strtotime($date));
        $arrOfWeek = $this->weekOfMonth($tahun, $bulan);
        //return "JML".count($arrOfWeek);
        $num = 1;
        foreach ($arrOfWeek as $week) {
            //echo $week[0]." ++ ".$date." ++ ".$week[1]."<br>";
            if(strtotime($week[0]) <= strtotime($date) && strtotime($date) <= strtotime($week[1])){
                return $week;
            }
            $num++;
        }
        return NULL;
    }
    
    public static function getDateRange($year, $month, $weekNum){
        $arrOfWeek = Tanggal::weekOfMonth($year, $month);
        //return "JML".count($arrOfWeek);
        $num = 0;
        foreach ($arrOfWeek as $week) {
            if($num == $weekNum){
                return $week;
            }
            $num++;
        }
        return NULL;
    }
    
    public static function getWeekNum($date){
        $tahun = date("Y", strtotime($date));
        $bulan = date("m", strtotime($date));
        $arrOfWeek = Tanggal::weekOfMonth($tahun, $bulan);
        //return "JML".count($arrOfWeek);
        $num = 0;
        foreach ($arrOfWeek as $week) {
            //echo $week[0]." ++ ".$date." ++ ".$week[1]."<br>";
            if(strtotime($week[0]) <= strtotime($date) && strtotime($date) <= strtotime($week[1])){
                return $num;
            }
            $num++;
        }
        return -1;
    }

    public static function weekOfMonth($year, $month) {
        //$year = str_pad($year, 2, "0", STR_PAD_LEFT);
        $month = str_pad($month, 2, "0", STR_PAD_LEFT);
        
        $jml = array(
            "01"=>31,
            "02"=>28,
            "03"=>31,
            "04"=>30,
            "05"=>31,
            "06"=>30,
            "07"=>31,
            "08"=>31,
            "09"=>30,
            "10"=>31,
            "11"=>30,
            "12"=>31,
        );
        
        $jmlHari = $jml[$month];
        $noAwalHari = 1; //senin
        
        $arrayHari = array();
        
        for($i=1;$i<=$jmlHari;$i++){
            $hari = $year."-".$month."-".str_pad($i, 2, "0", STR_PAD_LEFT);
            if(strtotime($hari) > strtotime(date("Y-m-d"))){
                //break;
            }
            $noHari = date("N", strtotime($hari));
            if($noHari == $noAwalHari){
                //echo $noHari." - ".$noAwalHari." - ".$hari."<br>";
                $kemudian = date("Y-m-d", strtotime($hari." +6 days"));
                
                $obj = array(
                    $hari,
                    $kemudian
                );
                $arrayHari[] = $obj;
            }
        }
        
        return $arrayHari;
    }
    
    public static function isInsideRange($date, $range1, $range2) {
        if(strtotime($range1) <= strtotime($date) && strtotime($date) <= strtotime($range2)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    public static function getUmur($tgl_lahir) {
        $tz = new DateTimeZone('Asia/Jakarta');
        //echo $pasien->tanggal_lahir."<br>";
        if($tgl_lahir != NULL){
            $diff = DateTime::createFromFormat('Y-m-d', $tgl_lahir, $tz)->diff(new DateTime('now', $tz));
            return $diff->y;
        }
        return 0;
    }
}
