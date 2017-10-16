<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente
 * @version     1.0
 */

namespace jlorente\billing\models;

use jlorente\billing\db\Invoice,
    jlorente\billing\db\InvoiceQuery;
use yii\data\ActiveDataProvider;

/**
 * Class SearchInvoice to perform invoices search.
 * 
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class SearchInvoice extends Invoice {

    /**
     *
     * @var int 
     */
    public $invoiced_from;

    /**
     *
     * @var int 
     */
    public $invoiced_to;

    /**
     * @inheritdoc
     */
    public function init() {
        $this->detachBehaviors();
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['status', 'created_at', 'created_by', 'updated_at', 'updated_by'], 'integer']
            , [['number', 'client_name', 'client_id', 'supplier_name', 'supplier_id'], 'string', 'max' => 255]
        ];
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
        $query = $this->getQuery();
        $dataProvider = new ActiveDataProvider([
            'query' => $query
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $this->addFilters($query);
        return $dataProvider;
    }

    /**
     * Gets the base query for the search
     * 
     * @return ActiveQuery
     */
    public function getQuery() {
        $query = static::find();
        return $query;
    }

    /**
     * Add filters to the query depending on the loaded properties.
     * 
     * @param InvoiceQuery $query
     */
    public function addFilters(InvoiceQuery $query) {
        if (!empty($this->id)) {
            $query->andFilterWhere(['user.id' => $this->id]);
        }
        if (!empty($this->number)) {
            $query->andFilterWhere(['like', 'number', '%' . $this->number . '%', false]);
        }
        if (!empty($this->client_name)) {
            $query->andFilterWhere(['like', 'client_name', '%' . $this->client_name . '%', false]);
        }
        if (!empty($this->client_id)) {
            $query->andFilterWhere(['like', 'client_id', '%' . $this->client_id . '%', false]);
        }
        if (!empty($this->supplier_name)) {
            $query->andFilterWhere(['like', 'supplier_name', '%' . $this->supplier_name . '%', false]);
        }
        if (!empty($this->supplier_id)) {
            $query->andFilterWhere(['like', 'supplier_id', '%' . $this->supplier_id . '%', false]);
        }
        if (!empty($this->status)) {
            $query->andWhere(['status' => $this->status]);
        }
        if (!empty($this->invoiced_from)) {
            $query->andWhere(['>=', 'invoced_at', $this->invoiced_from]);
        }
        if (!empty($this->invoiced_to)) {
            $query->andWhere(['<=', 'invoced_at', $this->invoiced_to]);
        }
    }

}
