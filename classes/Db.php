<?php
    //Abstract class means we cannot get a new instance (object) of this class HOWEVER we van adress it to get a connection established
    abstract class Db{
        private static $conn; //Using static since we don't get any instances
        
        public static function getConnection(){

            if( self::$conn != null ){ //using 'self' since 'this' can only be used on an instance/object
                //connection found, return connection
                return self::$conn;
            }else{
                //no connection found, establisch a new one with config.ini file
                $config = parse_ini_file("config.ini");
                self::$conn = new PDO("mysql:host=" . $config['host'] . ";dbname=" . $config['db_name'], $config['db_user'], $config['db_password']);
                return self::$conn;
            }

        }

    }
