<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente
 * @version     1.0
 */

namespace jlorente\billing\db;

use Yii;
use yii\db\ActiveRecord,
    yii\db\ActiveQuery;
use yii\helpers\ArrayHelper;
use jlorente\billing\Module;
use yii\behaviors\TimestampBehavior,
    yii\behaviors\BlameableBehavior;

/**
 * This is the model class for the invoice table of the jlorente billing module.
 *
 * @property integer $id
 * @property string $number
 * @property integer $invoiced_at
 * @property string $client_name
 * @property string $client_id
 * @property integer $client_location_id
 * @property string $supplier_name
 * @property string $supplier_id
 * @property integer $supplier_location_id 
 * @property integer $status
 * @property string $extra_data
 * 
 * @property Item[] $items A collection of the items of this invoice.
 * @property Location $clientLocation The client location data object.
 * @property Location $supplierLocation The supplier location data object.

 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class Invoice extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return Module::TABLE_INVOICE;
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['number', 'invoiced_at', 'client_name', 'client_id', 'supplier_name', 'supplier_id', 'status'], 'required']
            , [['extra_data'], 'string']
            , [['invoiced_at', 'status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer']
            , [['number', 'client_name', 'client_id', 'supplier_name', 'supplier_id'], 'string', 'max' => 255]
            , [['number'], 'unique']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('jlorente/billing', 'ID')
            , 'number' => Yii::t('jlorente/billing', 'Number')
            , 'invoiced_at' => Yii::t('jlorente/billing', 'Invoiced At')
            , 'client_name' => Yii::t('jlorente/billing', 'Client Name')
            , 'client_id' => Yii::t('jlorente/billing', 'Client Id')
            , 'client_location_id' => Yii::t('jlorente/billing', 'Client Location')
            , 'supplier_name' => Yii::t('jlorente/billing', 'Supplier Name')
            , 'supplier_id' => Yii::t('jlorente/billing', 'Supplier Id')
            , 'supplier_location_id' => Yii::t('jlorente/billing', 'Supplier Location')
            , 'status' => Yii::t('jlorente/billing', 'Status')
            , 'extra_data' => Yii::t('jlorente/billing', 'Extra Data')
            , 'created_at' => Yii::t('jlorente/billing', 'Created At')
            , 'created_by' => Yii::t('jlorente/billing', 'Created By')
            , 'updated_at' => Yii::t('jlorente/billing', 'Updated At')
            , 'updated_by' => Yii::t('jlorente/billing', 'Updated By')
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return ArrayHelper::merge(parent::behaviors(), [
                    TimestampBehavior::className()
                    , BlameableBehavior::className()
        ]);
    }

    /**
     * 
     * @return ItemQuery
     */
    public function getItems() {
        return $this->hasMany(Item::className(), ['invoice_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return InvoiceQuery the active query class used by this AR class.
     */
    public static function find() {
        return new InvoiceQuery(get_called_class());
    }

}

/**
 * The ActiveQuery class for the Invoice model.
 * 
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class InvoiceQuery extends ActiveQuery {
    
}
