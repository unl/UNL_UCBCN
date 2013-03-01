<?php
namespace UNL\UCBCN\ActiveRecord;

class Database
{
    protected static $db_settings = array(
        'host'     => 'localhost',
        'user'     => 'buros',
        'password' => 'buros',
        'dbname'   => 'buros',
    );

    public static function setDbSettings($settings = array())
    {
        self::$db_settings = $settings + self::$db_settings;
    }

    public static function getDbSettings()
    {
        return self::$db_settings;
    }

    /**
     * Connect to the database and return it
     *
     * @return \mysqli
     */
    public static function getDB()
    {
        static $db = false;
        if (!$db) {
            $settings = self::getDbSettings();
            $db = new \mysqli($settings['host'], $settings['user'], $settings['password'], $settings['dbname']);
            if ($db->connect_error) {
                die('Connect Error (' . $db->connect_errno . ') '
                        . $db->connect_error);
            }
            $db->set_charset('utf8');
        }

        return $db;
    }
}
