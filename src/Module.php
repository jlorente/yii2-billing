<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente
 * @version     1.0
 */

namespace jlorente\billing;

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
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class Module extends BaseModule implements BootstrapInterface {

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        $this->setAliases([
            '@billingModule' => '@vendor/jlorente/yii2-billing/src'
        ]);
    }

    /**
     * @inheritdoc
     * 
     * @param \yii\web\Application $app
     */
    public function bootstrap($app) {
        
    }

}
