<?php

namespace backend\helpers;

class WorkQueueType
{
    private static $data = [
        '1' => 'ใบคิวงานไป',
        '2' => 'ใบคิวงานของกลับ'
    ];

    private static $dataobj = [
        ['id'=>'1','name' => 'ใบคิวงานไป'],
        ['id'=>'2','name' => 'ใบคิวงานของกลับ']
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
