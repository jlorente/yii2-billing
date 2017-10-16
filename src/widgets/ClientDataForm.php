<?php

/**
 * @author	José Lorente <jose.lorente.martin@gmail.com>
 * @copyright	José Lorente <jose.lorente.martin@gmail.com>
 * @version	1.0
 */

namespace backend\modules\user\widgets;

use Yii;
use yii\base\Model;
use yii\base\Widget;
use yii\helpers\Url;
use common\helpers\Html;
use kartik\select2\Select2;
use yii\widgets\ActiveForm;
use backend\modules\user\models\User;
use jlorente\template\inspinia\widgets\ICheck;
use jlorente\location\widgets\LocationFormWidget;
use common\assets\Select2AutoCompleteAsset;

/**
 * ClientDataForm class shows allows to integrate the bill client fields with 
 * other form fields.
 * 
 * @author José Lorente <jose.lorente.martin@gmail.com>
 */
class ClientDataForm extends Widget {

    /**
     *
     * @var Model
     */
    protected $model;

    /**
     *
     * @var ActiveForm
     */
    protected $form;

    /**
     *
     * @var boolean 
     */
    public $lockSelection = false;

    /**
     *
     * @var type 
     */
    public $relatedModel;

    /**
     *
     * @var string 
     */
    public $select2DataUrl;

    /**
     *
     * @var string 
     */
    public $userDataUrl;

    /**
     *
     * @var string
     */
    public $userDataUrlAttribute = 'id';

    /**
     *
     * @var User
     */
    protected $_userModel;

    /**
     * @inheritdoc
     */
    public function init() {
        parent::init();
        if (empty($this->relatedModel)) {
            $this->relatedModel = ['user_id', User::className()];
        }
        $this->initUserModel();
        Select2AutoCompleteAsset::register($this->view);
    }

    /**
     * Inits the user model.
     */
    public function initUserModel() {
        $field = $this->relatedModel[0];
        $class = $this->relatedModel[1];
        if ($this->model->{$field}) {
            $this->_userModel = $class::findOne($this->model->$field);
        } else {
            $this->_userModel = new $class();
            $this->_userModel->loadDefaultValues();
        }
    }

    public function run() {
        echo Html::tag('div', $this->content(), ['id' => $this->getId()]);
        $this->registerJs();
    }

    /**
     * @inheritdoc
     */
    public function content() {
        $data = [];
        $html = '';
        $field = $this->relatedModel[0];
        if ($this->model->{$field} !== null) {
            $data[] = [$this->_userModel->id => $this->_userModel->getFullName()];
        }
        $html .= $this->form->field($this->model, $field, [
                    'enableClientValidation' => false
                ])->widget(Select2::className(), [
            'language' => 'es'
            , 'data' => $data
            , 'pluginOptions' => [
                'ajax' => [
                    'url' => $this->select2DataUrl
                    , 'delay' => 500
                ]
                , 'placeholder' => Yii::t('general', 'Select one or leave it blank to create')
                , 'allowClear' => true
                , 'disabled' => $this->lockSelection
            ]
            , 'options' => [
                'id' => $this->getSelectorId($field)
            ]
        ]);
        $html .= Html::tag('div'
                        , Html::tag('div', $this->form->field($this->_userModel, 'client_name')->textInput(['name' => $this->getFieldName('name')]), ['class' => 'col-xs-6'])
                        . Html::tag('div', $this->form->field($this->_userModel, 'client_id')->textInput(['name' => $this->getFieldName('last_name')]), ['class' => 'col-xs-6'])
                        , ['class' => 'row']
        );
        $html .= Html::tag('div'
                        , Html::tag('div', $this->form->field($this->_userModel, 'client_address')->textInput(['name' => $this->getFieldName('nif')]), ['class' => 'col-xs-6'])
                        . Html::tag('div', $this->form->field($this->_userModel, 'client_id')->textInput(['name' => $this->getFieldName('email')]), ['class' => 'col-xs-6'])
                        , ['class' => 'row']
        );
        $html .= LocationFormWidget::widget([
                    'model' => $this->_userModel
                    , 'form' => $this->form
                    , 'template' => "{country}\n{region}\n{city}\n{address}\n{postalCode}"
                    , 'submitModelName' => $this->getFormName()
        ]);
        $html .= $this->form->field($this->_userModel, 'gender')->widget(ICheck::className(), [
            'mode' => ICheck::MODE_RADIO
            , 'list' => User::getFormGenders()
            , 'options' => [
                'name' => $this->getFieldName('gender')
                , 'unselect' => User::GENDER_EMPTY
            ]
        ]);
        return $html;
    }

    /**
     * 
     */
    public function registerJs() {
        $link = $this->userDataUrl;
        $placeholder = '000';
        $link[$this->userDataUrlAttribute] = $placeholder;
        $template = Url::to($link);
        $this->view->registerJs(<<<JS
$('#{$this->getSelectorId($this->relatedModel[0])}').select2AutoComplete('#{$this->getId()}', {
    "placeholder": '$placeholder'
    , "template": '$template'
});
JS
        );
    }

    /**
     * 
     * @param Model $model
     */
    public function setModel(Model $model) {
        $this->model = $model;
    }

    /**
     * 
     * @return Model
     */
    public function getModel() {
        return $this->model;
    }

    /**
     * 
     * @param ActiveForm $activeForm
     */
    public function setForm(ActiveForm $activeForm) {
        $this->form = $activeForm;
    }

    /**
     * 
     * @return ActiveForm
     */
    public function getForm() {
        return $this->form;
    }

    /**
     * 
     * @return string
     */
    protected function getFormName() {
        return $this->model->formName() . "[{$this->_userModel->formName()}]";
    }

    /**
     * 
     * @param string $fieldName
     * @return string
     */
    protected function getFieldName($fieldName) {
        return $this->getFormName() . "[$fieldName]";
    }

    /**
     * 
     * @param string $fieldName
     * @return string
     */
    protected function getSelectorId($fieldName) {
        return 'select_' . $fieldName;
    }

}
