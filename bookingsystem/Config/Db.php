<?php
namespace bookingsystem\Config;


class Db {
    const DBHOST = 'localhost';
    const DBUSER = 'ss4u';
    const DBPASS = '123456';
    const DBNAME = 'booking';
    private static $instance = null;

    private function __construct()
    {
        $connection = new \mysqli(static::DBHOST, static::DBUSER, static::DBPASS, static::DBNAME);
        if ($connection->connect_error) {
            die('Connection failed: ' . $connection->connect_error);
        }
        static::$instance = $connection;
    }

    public static function getInstance()
    {
        if (!self::$instance) {
            new self();
        }
        return static::$instance;
    }
}
// there will be a allocation whiling creating of object everytime so 
//we have static function which does not need to create a object every time wen every we call the function it will return mysqli. 