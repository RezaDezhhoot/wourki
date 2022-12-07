<?php

if(!function_exists('make_url_validate')){
    function make_url_validate($url){
        if(!$url){
            return null;
        }
        if(strpos($url , 'http://')  === false && strpos($url , 'https://') === false ){
            return 'http://' . $url;
        }else{
            return $url;
        }
    }
}