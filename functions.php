<?php

    function strstr2($haystack, $needle, $beginend = false){
        if($beginend === false){
            $o = strstr($haystack, $needle);
            $o = substr($o, strlen($needle));
        } else
            $o = strstr($haystack, $needle, true);
        return $o;
    }


    function scandirc($dir, $remove = array(), $includeRegexMatch = false, $dontIncludeRegexMatch = false){

        $dirs = scandir($dir);

        $remove[] = '.';
        $remove[] = '..';

        if($remove)
            foreach ($remove as $value)
                if (($key = array_search($value, $dirs)) !== false)
                    unset($dirs[$key]);

        if($includeRegexMatch)
            foreach ($dirs as $key => $value)
                if(!preg_match($includeRegexMatch, $value))
                    unset($dirs[$key]);

        if($dontIncludeRegexMatch)
            foreach ($dirs as $key => $value)
                if(preg_match($dontIncludeRegexMatch, $value))
                    unset($dirs[$key]);

        $dirs = array_values($dirs);

        return $dirs;
    }

?>