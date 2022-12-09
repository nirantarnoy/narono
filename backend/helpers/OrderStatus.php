<?php

namespace backend\helpers;

class OrderStatus
{
    const STATUS_OPEN= 1;
    const STATUS_CLOSE = 2;
    const STATUS_CANCEL = 3;

    private static $data = [
        '1' => 'Open',
        '2' => 'Closed',
        '3' => 'Cancel'
    ];

    /**
     * @var \string[][]
     */
    private static $dataobj = array(
        array('id'=>'1','name' => 'Open'),
        array('id'=>'2','name' => 'Closed'),
        array('id'=>'3','name' => 'Cancel')
    );
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

        return 'Unknown';
    }
    public static function getTypeByName($idx)
    {
        if (isset(self::$data[$idx])) {
            return self::$data[$idx];
        }

        return 'Unknown';
    }
}
