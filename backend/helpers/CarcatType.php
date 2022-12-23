<?php

namespace backend\helpers;

class CarcatType
{
    private static $data = [
        '1' => 'ส่วนหัว',
        '2' => 'ส่วนพ่วง'
    ];

    private static $dataobj = [
        ['id'=>'1','name' => 'ส่วนหัว'],
        ['id'=>'2','name' => 'ส่วนพ่วง']
    ];
    public static function asArray()
    {
        return self::$data;
    }
    public static function asArrayObject()
    {
        return self::$dataobj;
    }
    public static function getTypeById($idx)
    {
        if (isset(self::$data[$idx])) {
            return self::$data[$idx];
        }

        return 'Unknown Type';
    }
    public static function getTypeByName($idx)
    {
        if (isset(self::$data[$idx])) {
            return self::$data[$idx];
        }

        return 'Unknown Type';
    }
}