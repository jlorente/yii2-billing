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
use jlorente\billing\Module;

/**
 * This is the model class for the item table of the jlorente billing module.
 *
 * @property integer $id
 * @property integer $invoice_id
 * @property string $name
 * @property string $description
 * @property string $tax_base
 * @property string $tax_rate
 * @property integer $quantity
 * 
 * @property Invoice $invoice The invoice which this item belongs.
 * 
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class Item extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return Module::TABLE_ITEM;
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['invoice_id', 'name', 'tax_base', 'tax_rate'], 'required']
            , [['description'], 'string']
            , [['tax_base', 'tax_rate'], 'number']
            , [['invoice_id', 'quantity'], 'integer']
            , [['name'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => Yii::t('jlorente/billing', 'ID')
            , 'invoice_id' => Yii::t('jlorente/billing', 'Invoice')
            , 'name' => Yii::t('jlorente/billing', 'Name')
            , 'description' => Yii::t('jlorente/billing', 'Description')
            , 'tax_base' => Yii::t('jlorente/billing', 'Tax Base')
            , 'tax_rate' => Yii::t('jlorente/billing', 'Tax Rate')
            , 'quantity' => Yii::t('jlorente/billing', 'Quantity')
        ];
    }

    /**
     * 
     * @return InvoiceQuery
     */
    public function getInvoice() {
        return $this->hasOne(Invoice::className(), ['id' => 'invoice_id']);
    }

    /**
     * @inheritdoc
     * @return ItemQuery the active query class used by this AR class.
     */
    public static function find() {
        return new ItemQuery(get_called_class());
    }

}

/**
 * The ActiveQuery class for the Item model.
 * 
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class ItemQuery extends ActiveQuery {
    
}
