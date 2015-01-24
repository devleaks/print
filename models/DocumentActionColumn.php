<?php

namespace app\models;

use Yii;
use Closure;
use yii\helpers\Html;
use yii\helpers\Url;

class DocumentActionColumn extends Column {
    /**
     * @var string the ID of the controller that should handle the actions specified here.
     * If not set, it will use the currently active controller. This property is mainly used by
     * [[urlCreator]] to create URLs for different actions. The value of this property will be prefixed
     * to each action name to form the route of the action.
     */
    public $controller;

    public $template = '{view} {update} {delete}';

    public $buttons = [];

    public $urlCreator;

	public $baseClass;

	public $buttonTemplate = '{icon} {text}';

	protected $iconsAndTexts;
    /**
     * @inheritdoc
     */
    public function init()
    {
		$iconsAndTexts = [
			'view'		=> ['icon' => 'eye-open',		'text' => 'View'],
			
		];
	
        parent::init();
        $this->initDefaultButtons();
    }

	/**
	 * Generates buttons with icon and or label
	 *
	 * @return string HTML fragment
	 */
	public function getButton($mode) {
		$data = $this->iconsAndTexts[$mode];
		$label = Yii::t('store', $data['text']);
		return str_replace(
			'{icon}', '<span class="glyphicon glyphicon-'.$data['icon'].'"></span> ', str_replace(
				'{text}', $label, $this->template
			)
		);
	}
	
	/**
	 * Generates buttons with icon and or label
	 *
	 * @return string HTML fragment
	 */
	public function getAnchor($url, $mode) {
		$data = $this->iconsAndTexts[$mode];
		$label = Yii::t('store', $data['text']);
		return Html::a(
			str_replace(
				'{icon}', '<span class="glyphicon glyphicon-'.$data['icon'].'"></span> ', str_replace(
					'{text}', $label, $this->buttonTemplate
				)
			),
			$url,
			[
            	'title' => $label,
                'data-pjax' => '0',
			]
		);
	}
	
	public function getPrintDropdown($model) {
		return ' <div class="btn-group"><button type="button" class="'.$this->baseClass.' btn-info dropdown-toggle" data-toggle="dropdown">'.
		        	$this->getButton('print'). ' <span class="caret"></span></button><ul class="dropdown-menu" role="menu">'.
					'<li>'.Html::a('Page (A4)', ['/order/document/print', 'id' => $model->id], ['target' => '_blank', 'title' => Yii::t('store', 'Print on full A4 page')]).'</li>'.
					'<li>'.Html::a('Ticket (A5)', ['/order/document/print', 'id' => $model->id, 'format' => 'A5'], ['target' => '_blank', 'title' => Yii::t('store', 'Print on reduced A5 ticket')]).'</li>'.
     				'</ul></div>';
	}
	
    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
        if (!isset($this->buttons['view'])) {
            $this->buttons['view'] = function ($url, $model, $key) {
                return $this->getAnchor($url, 'view');
            };
        }
        if (!isset($this->buttons['print'])) {
            $this->buttons['print'] = function ($url, $model, $key) {
                return $this->getPrintDropdown($model);
            };
        }
/*
        if (!isset($this->buttons['delete'])) {
            $this->buttons['delete'] = function ($url, $model, $key) {
                return Html::a('<span class="glyphicon glyphicon-trash"></span>', $url, [
                    'title' => Yii::t('yii', 'Delete'),
                    'data-confirm' => Yii::t('yii', 'Are you sure you want to delete this item?'),
                    'data-method' => 'post',
                    'data-pjax' => '0',
                ]);
            };
        }
*/
    }

    /**
     * Creates a URL for the given action and model.
     * This method is called for each button and each row.
     * @param string $action the button name (or action ID)
     * @param \yii\db\ActiveRecord $model the data model
     * @param mixed $key the key associated with the data model
     * @param integer $index the current row index
     * @return string the created URL
     */
    public function createUrl($action, $model, $key, $index)
    {
        if ($this->urlCreator instanceof Closure) {
            return call_user_func($this->urlCreator, $action, $model, $key, $index);
        } else {
            $params = is_array($key) ? $key : ['id' => (string) $key];
            $params[0] = $this->controller ? $this->controller . '/' . $action : $action;

            return Url::toRoute($params);
        }
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
            $name = $matches[1];
            if (isset($this->buttons[$name])) {
                $url = $this->createUrl($name, $model, $key, $index);

                return call_user_func($this->buttons[$name], $url, $model, $key);
            } else {
                return '';
            }
        }, $this->template);
    }
}
