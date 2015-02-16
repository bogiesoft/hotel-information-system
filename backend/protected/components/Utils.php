<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Utils
 *
 * @author feb
 */
class Utils {
    public static function toTitle($str){
        $str = str_replace("_", " ", $str);
        $str = ucwords(strtolower($str));
        return $str;
    }
    
    public static function lowerFirst($str){
        return strtolower(substr($str, 0, 1)).substr($str, 1);
    }
    
    public static function splitCamel($ccWord){
        $re = '/(?#! splitCamelCase Rev:20140412)
            # Split camelCase "words". Two global alternatives. Either g1of2:
              (?<=[a-z])      # Position is after a lowercase,
              (?=[A-Z])       # and before an uppercase letter.
            | (?<=[A-Z])      # Or g2of2; Position is after uppercase,
              (?=[A-Z][a-z])  # and before upper-then-lower case.
            /x';
        return ucwords(implode(" ", preg_split($re, $ccWord)));
    }
}
