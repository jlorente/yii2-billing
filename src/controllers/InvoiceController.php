<?php

/**
 * @author      José Lorente <jose.lorente.martin@gmail.com>
 * @license     The MIT License (MIT)
 * @copyright   José Lorente
 * @version     1.0
 */

namespace jlorente\billing\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\VerbFilter;
use jlorente\billing\Module;
use jlorente\billing\db\Invoice;
use yii\web\NotFoundHttpException;
use jlorente\billing\models\SearchInvoice;

/**
 * Invoice controller of the jlorente billing module.
 * 
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class InvoiceController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => Module::getInstance()->controllerAccess
            , 'verbs' => [
                'class' => VerbFilter::className()
                , 'actions' => [
                    'index' => ['get']
                    , 'create' => ['get', 'post']
                    , 'update' => ['get', 'post']
                    , 'delete' => ['post']
                    , 'view' => ['get']
                ]
            ]
        ];
    }

    /**
     * Lists all models.
     * 
     * @return mixed
     */
    public function actionIndex() {
        $model = new SearchInvoice();
        $dataProvider = $model->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'model' => $model,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single model.
     * 
     * @param integer $id
     * @return mixed
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new model.
     * 
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Invoice();
        $model->loadDefaultValues();
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('general', 'The creation was successful'));
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing model.
     * 
     * If update is successful, the browser will be redirected to the 'view' page.
     * 
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('general', 'The modification was successful'));
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing model.
     * 
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * 
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();
        Yii::$app->session->setFlash('success', Yii::t('general', 'The deletion was successful'));
        return $this->redirect(['index']);
    }

    /**
     * Finds the model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * 
     * @param integer $id
     * @return User the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Invoice::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
