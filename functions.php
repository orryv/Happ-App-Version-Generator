<?php

    function strstr2($haystack, $needle, $beginend = false){
        if($beginend === false){
            $o = strstr($haystack, $needle);
            $o = substr($o, strlen($needle));
        } else
            $o = strstr($haystack, $needle, true);
        return $o;
    }

?>