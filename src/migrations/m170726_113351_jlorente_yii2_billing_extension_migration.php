<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente
 * @version     1.0
 */
use yii\db\Schema;
use yii\db\Migration;
use yii\helpers\Inflector;
use jlorente\billing\Module;

/**
 * Migration that creates the billing module tables.
 * 
 * To apply this migration run:
 * ```bash
 * $ ./yii migrate --migrationPath=@app/vendor/jlorente/yii2-billing/src/migrations
 * ```
 * or extend this migration in your project and apply it as usually.
 * 
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class m170726_113351_jlorente_yii2_billing_extension_migration extends Migration {

    /**
     * @inheritdoc
     */
    public function up() {
        $this->createTable(Module::TABLE_INVOICE, [
            'id' => Schema::TYPE_PK
            , 'number' => Schema::TYPE_STRING . ' NOT NULL'
            , 'invoiced_at' => Schema::TYPE_INTEGER . ' NOT NULL'
            , 'client_name' => Schema::TYPE_STRING . ' NOT NULL'
            , 'client_id' => Schema::TYPE_STRING . ' NOT NULL'
            , 'client_location_id' => Schema::TYPE_INTEGER
            , 'supplier_name' => Schema::TYPE_STRING . ' NOT NULL'
            , 'supplier_id' => Schema::TYPE_STRING . ' NOT NULL'
            , 'supplier_location_id' => Schema::TYPE_INTEGER
            , 'status' => Schema::TYPE_SMALLINT . ' NOT NULL DEFAULT 1'
            , 'extra_data' => Schema::TYPE_TEXT
            , 'created_at' => Schema::TYPE_INTEGER
            , 'created_by' => Schema::TYPE_INTEGER
            , 'updated_at' => Schema::TYPE_INTEGER
            , 'updated_by' => Schema::TYPE_INTEGER
        ]);

        $this->createTable(Module::TABLE_ITEM, [
            'id' => Schema::TYPE_PK
            , 'invoice_id' => Schema::TYPE_INTEGER . ' NOT NULL'
            , 'name' => Schema::TYPE_STRING . ' NOT NULL'
            , 'description' => Schema::TYPE_TEXT
            , 'tax_base' => Schema::TYPE_MONEY . ' NOT NULL'
            , 'tax_rate' => Schema::TYPE_MONEY . ' NOT NULL'
            , 'quantity' => Schema::TYPE_INTEGER . ' NOT NULL DEFAULT 1'
        ]);

        $invInfTable = Inflector::camelize(Module::TABLE_INVOICE);
        $this->createIndex("UNIQUE_{$invInfTable}_Number", Module::TABLE_INVOICE, 'number', true);
        $this->addForeignKey($this->getForeignKeyName(), Module::TABLE_ITEM, 'invoice_id', Module::TABLE_INVOICE, 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down() {
        $this->dropForeignKey($this->getForeignKeyName(), Module::TABLE_ITEM);
        $this->dropTable(Module::TABLE_ITEM);
        $this->dropTable(Module::TABLE_INVOICE);
    }

    /**
     * Gets the Foreign Key name of the invoice-item 0-n relation.
     * 
     * @return string
     */
    protected function getForeignKeyName() {
        $invInfTable = Inflector::camelize(Module::TABLE_INVOICE);
        $itemInfTable = Inflector::camelize(Module::TABLE_ITEM);
        return "FK_{$invInfTable}_{$itemInfTable}_InvoiceId";
    }

}
