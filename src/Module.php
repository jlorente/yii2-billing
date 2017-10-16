<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente
 * @version     1.0
 */

namespace jlorente\billing;

use Yii;
use yii\helpers\ArrayHelper;
use yii\filters\AccessControl;
use yii\base\BootstrapInterface;
use yii\base\Module as BaseModule;

/**
 * Module class for the Jlorente Yii2 Billing module.
 * 
 * You must add this module to the module section and the bootstrap section of 
 * the application config file in order to make it work.
 * 
 * ../your_app/config/main.php
 * ```php
 * return [
 *     //Other configurations
 *     'modules' => [
 *         //Other modules
 *         'billing' => [
 *             'class' => 'jlorente\billing\Module'
 *          ]
 *     ],
 *     'bootstrap' => [
 *         //Other bootstrapped modules
 *         , 'billing'
 *     ]
 * ]
 * 
 * Options of the module
 * [
 *      'translations' => [
 *          'class' => 'yii\i18n\PhpMessageSource',
 *          'basePath' => 'PATH_TO_MY_TRANSLATIONS',
 *          'forceTranslation' => true
 *      ]
 * ]
 * 
 * Use the translation property to create your custom translation file. Inside 
 * the translation path you must add a folder called jlorente and inside it a 
 * file called billing.php. There, you can override the translations of the 
 * model.
 * 
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class Module extends BaseModule implements BootstrapInterface {

    const TABLE_INVOICE = 'jl_bil_invoice';
    const TABLE_ITEM = 'jl_bil_item';

    /**
     *
     * @var array 
     */
    public $translations = [];
    public $controllerAccess = [];

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->setAliases([
            '@jlorenteBilling' => '@vendor/jlorente/yii2-billing/src'
        ]);
        Yii::$app->i18n->translations['jlorente/billing'] = $this->getTranslationsConfig();
        $this->controllerAccess = ArrayHelper::merge([
                    'class' => AccessControl::className(),
                    'rules' => [
                        [
                            'actions' => ['index', 'view', 'create', 'update', 'delete'],
                            'allow' => true,
                            'roles' => ['@']
                        ]
                    ],
                        ], $this->controllerAccess);
    }

    /**
     * 
     * @return array
     */
    protected function getTranslationsConfig() {
        return array_merge([
            'class' => 'yii\i18n\PhpMessageSource',
            'basePath' => '@jlorenteBilling/messages',
            'forceTranslation' => true
                ], $this->translations);
    }

    /**
     * @inheritdoc
     * 
     * @param \yii\web\Application $app
     */
    public function bootstrap($app) {
        $app->getUrlManager()->addRules([
            'billing/invoice/index' => 'billing/invoice/index'
            , 'billing/invoice/create' => 'billing/invoice/create'
            , 'billing/invoice/update' => 'billing/invoice/update'
            , 'billing/invoice/delete' => 'billing/invoice/delete'
                ], false);
    }

}
