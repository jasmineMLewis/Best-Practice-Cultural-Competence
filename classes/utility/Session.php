<?php
/**
 *  A utility/helper class for input that permits the global variables $_SESSION 
 * from being accessed directly. It enables one to set, get, delete and find if
 * a session exists.
 * @version 1
 * @createdAtdate Jul 17, 2015
 * @author JasmineMonquieLewis
 */

class Session {
    public static function delete($name) {
        if(self::exists($name)){
            unset($_SESSION[$name]);
            session_destroy();
        }
    }
    
    public static function exists($name) {
        return (isset($_SESSION[$name])) ? true : false;
    }
    
    public static function get($name) {
        return $_SESSION[$name];
    }

    public static function set($name, $value) {
        return $_SESSION[$name] = $value;
    }    
}
