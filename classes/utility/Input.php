<?php

class Input {

    public static function exists($type){
        switch ($type) {
            case 'post':
                return (!empty($_POST)) ? true : false;
            case 'get':
                return (!empty($_GET)) ? true: false;
            default:
                return false;
        }
    }
    
    public static function get($data) {
        if(isset($_POST[$data])){
            return $_POST[$data];
        }else if(!empty ($_GET[$data])){
            return $_GET[$data];
        }else{
            return '';
        }
    }
}