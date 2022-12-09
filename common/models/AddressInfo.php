<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "address_info".
 *
 * @property int $id
 * @property int|null $party_type
 * @property int|null $party_id
 * @property string|null $address
 * @property string|null $street
 * @property int|null $district_id
 * @property int|null $city_id
 * @property int|null $province_id
 * @property string|null $zipcode
 * @property int|null $status
 */
class AddressInfo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'address_info';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['party_type', 'party_id', 'district_id', 'city_id', 'province_id', 'status'], 'integer'],
            [['address', 'street', 'zipcode'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'party_type' => 'Party Type',
            'party_id' => 'Party ID',
            'address' => 'Address',
            'street' => 'Street',
            'district_id' => 'District ID',
            'city_id' => 'City ID',
            'province_id' => 'Province ID',
            'zipcode' => 'Zipcode',
            'status' => 'Status',
        ];
    }
}
