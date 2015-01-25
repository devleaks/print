<?php

namespace app\models;

use Yii;
use app\widgets\GridViewPDF;
use yii\grid\Column;
use yii\helpers\Html;
use yii\helpers\Url;

class DocumentActionColumn extends Column {
    public $label;

    public $hAlign;
    public $vAlign;
	public $hidden;
	public $noWrap;
	public $width;

    public $template = '{view} {print}';
	public $baseClass = 'btn btn-xs';	// 'btn btn-xs btn-block';
	public $buttonTemplate = '{icon}';	// '{icon} {text}';

    protected $buttons = [];
	protected $documentButtons;
	protected $documentLabels;

    /**
     * @inheritdoc
     */
    public function init()
    {
		$this->documentButtons = [
			// all
			'view'			=> [
				'icon' => 'eye-open',
				'label' => 'View',
				'title' => 'View',
				'color' => 'info',
				'action' => '/order/document/view'
			],
			'edit'			=> [
				'icon' => 'pencil',
				'label' => 'Modify',
				'title' => 'Modify',
				'color' => 'primary',
				'action' => '/order/document-line/create'
			],
			'cancel'		=> [
				'icon' => 'remove',
				'label' => 'Cancel',
				'title' => 'Cancel',
				'color' => 'warning',
				'action' => '/order/document/cancel',
				'confirm' => 'Cancel order?'
			],
			'print'			=> [
				'icon' => 'print',
				'label' => 'Print',
				'title' => 'Print',
				'color' => 'info',
				'action' => '/order/document/print'
			],
			// bids
			'convert'	=> [
				'icon' => 'ok',
				'label' => 'Convert to Order',
				'title' => 'Convert to Order',
				'color' => 'success',
				'action' => '/order/document/convert',
				'confirm' => 'Convert to order?'
			],
			// orders
			'submit'	=> [
				'icon' => 'cog',
				'label' => 'Submit Work',
				'title' => 'Submit Work',
				'color' => 'primary',
				'action' => '/order/document/submit',
				'confirm' => 'Submit work?'
			],
			'terminate'	=> [
				'icon' => 'play',
				'label' => 'Terminate',
				'title' => 'Terminate',
				'color' => 'primary',
				'action' => '/order/document/terminate',
				'confirm' => 'Is order ready?'
			],
			'bill'	=> [
				'icon' => 'ok',
				'label' => 'Bill To',
				'title' => 'Bill To',
				'color' => 'primary',
				'action' => '/order/document/convert',
				'confirm' => 'Send bill?'
			],
			'work'	=> [
				'icon' => 'tasks',
				'label' => 'Work',
				'title' => 'Work',
				'color' => 'primary',
				'action' => '/work/work/view',
			],
			'workterminate'	=> [
				'icon' => 'play',
				'label' => 'Terminate',
				'title' => 'Terminate',
				'color' => 'primary',
				'action' => '/work/work/terminate',
				'confirm' => 'Order is ready?'
			],
			'warn'	=> [
				'icon' => 'warning-sign',
				'label' => 'Warning',
				'title' => 'Warning',
				'color' => 'warning',
				'action' => '/work/work-line/detail',
			],
			// ticket
			'receive'	=> [ // no op placeholder, no billing.
				'icon' => 'ok-sign',
				'label' => 'Receive',
				'title' => 'Receive',
				'color' => 'primary',
				'action' => '/order/document/view',
			],
			// ticket
			'refund'	=> [ // no op placeholder, no billing.
				'icon' => 'euro',
				'label' => 'To Refund',
				'title' => 'To Refund',
				'color' => 'primary',
				'action' => '/order/document/sent',
			],
		];

		$this->documentLabels = [
			'TEST'	=> [
				'color' => 'success',
				'label' => 'TEST'
			],
			// all
			'cancelled'	=> [
				'color' => 'warning',	
				'label' => 'Cancelled'
			],
			'closed'	=> [
				'color' => 'success',	
				'label' => 'Closed'
			],
			// bids
			'ordered'	=> [
				'color' => 'success',	
				'label' => 'Ordered'
			],
			// orders
			'billed'	=> [
				'color' => 'success',	
				'label' => 'Billed'
			],
		];

		if(!$this->label) $this->label = Yii::t('store', 'Actions');

        $this->parseFormat();

        parent::init();

		$this->initDefaultButtons();
    }

	/**
	 * Generates colored labels for Document. Color depends on document status.
	 *
	 * @return string HTML fragment
	 */
	public function getLinkLabel($name, $model) {
		$data = $this->documentLabels[$name];
		$ret = '';
		switch($name) {
			case 'ordered':
				if( $order = $model->getDocuments()->where(['document_type' => [$model::TYPE_ORDER, $model::TYPE_TICKET]])->one() )
					$ret = Html::a('<span class="label label-success">'.Yii::t('store', 'Order Placed').'</span>',
										['/order/document/view', 'id' => $order->id], ['data-method' => 'post', 'title' => Yii::t('store', 'View Order')]);
				else
					$ret = $this->getActionLabel($name);
				break;
			case 'billed':
				$bill = $model->bom_bool ?
					Bill::findOne($model->parent_id) // for BOM we set inverse relation, parent_id points to collective bill
					:
					$model->getDocuments()->where(['document_type' => $model::TYPE_BILL])->one(); // or Bill::findOne(['parent_id'=>$this->id]) ?
	
				if( $bill )
					$ret = Html::a('<span class="label label-success">'.Yii::t('store', 'Billed').'</span>',
										['/order/document/view', 'id' => $bill->id], ['title' => Yii::t('store', 'View Bill'), 'data-method' => 'post']);
				else
					$ret = $this->getActionLabel($name);
				break;
		}
		return $ret;
	}

	/**
	 * Generates colored labels for Document. Color depends on document status.
	 *
	 * @return string HTML fragment
	 */
	public function getActionLabel($name) {
		$data = $this->documentLabels[$name];
		return '<span class="label label-'.$data['color'].'">'.Yii::t('store', $data['label']).'</span>';
	}

	/**
	 * Generates buttons with icon and or label
	 *
	 * @return string HTML fragment
	 */
	public function getButtonUrl($name, $model) {
		$data = $this->documentButtons[$name];
		return Url::to([$data['action'], 'id' => $model->id]);
	}

	/**
	 * Generates buttons with icon and or label
	 *
	 * @return string HTML fragment
	 */
	public function getButton($name) {
		$data = $this->documentButtons[$name];
		$label = Yii::t('store', $data['label']);
		return str_replace(
			'{icon}', '<span class="glyphicon glyphicon-'.$data['icon'].'"></span> ', str_replace(
				'{text}', $label, $this->buttonTemplate
			)
		);
	}


	/**
	 * Generates buttons with icon and or label
	 *
	 * @return string HTML fragment
	 */
	public function getAnchor($url, $name) {
		$data = $this->documentButtons[$name];
		$label = Yii::t('store', $data['label']);
		return Html::a(
			str_replace(
				'{icon}', '<span class="glyphicon glyphicon-'.$data['icon'].'"></span> ', str_replace(
					'{text}', $label, $this->buttonTemplate
				)
			),
			$url,
			[
				'class' => $this->baseClass . ' btn-' . $data['color'],
            	'title' => Yii::t('store', $data['title']),
                'data-pjax' => '0',
				'data-method' => isset($data['method']) ? $data['method'] : null,
				'data-confirm' => isset($data['confirm']) ? Yii::t('store', $data['confirm']) : null,
			]
		);
	}

	
	private function getDropdown($id, $name) {
		$data = $this->documentButtons[$name];
		if($name == 'print')
			return '<div class="btn-group"><button type="button" class="'.$this->baseClass.' btn-'.$data['color'].' dropdown-toggle" data-toggle="dropdown">'.
			        	$this->getButton('print'). ' <span class="caret"></span></button><ul class="dropdown-menu" role="menu">'.
						'<li>'.Html::a('Page (A4)', [$data['action'], 'id' => $id], ['target' => '_blank', 'title' => Yii::t('store', 'Print on full A4 page')]).'</li>'.
						'<li>'.Html::a('Ticket (A5)', [$data['action'], 'id' => $id, 'format' => 'A5'], ['target' => '_blank', 'title' => Yii::t('store', 'Print on reduced A5 ticket')]).'</li>'.
					'</ul></div>';
		if($name == 'convert')
			return '<div class="btn-group"><button type="button" class="'.$this->baseClass.' btn-'.$data['color'].' dropdown-toggle" data-toggle="dropdown">'.
			        	$this->getButton('convert'). ' <span class="caret"></span></button><ul class="dropdown-menu" role="menu">'.
						'<li>'.Html::a(Yii::t('store', 'Convert to order'), [$data['action'], 'id' => $id], ['title' => Yii::t('store', 'Convert to order')]).'</li>'.
						'<li>'.Html::a(Yii::t('store', 'Convert to sale'),  [$data['action'], 'id' => $id, 'ticket' => true], ['title' => Yii::t('store', 'Convert to sale')]).'</li>'.
					'</ul></div>';
		return '';
	}
	
	
    /**
     * Initializes the default button rendering callbacks.
     */
    protected function initDefaultButtons()
    {
		foreach(array_keys($this->documentButtons) as $action) {
	        if (!isset($this->buttons[$action])) {
				Yii::trace($action, 'DocumentActionColumn::ACTION');
				switch($action) {
					case 'convert';
					case 'print':
			            $this->buttons[$action] = function ($url, $model, $key, $name) {
							Yii::trace($url, 'DocumentActionColumn::ACTION');
							return $this->getDropdown($url, $name);
			            };
						break;
					default:
			            $this->buttons[$action] = function ($url, $model, $key, $name) {
							//Yii::trace($url, 'DocumentActionColumn::ACTION');
							return $this->getAnchor($url, $name);
			            };
						break;
				}
	        }
		}
    }

    /**
     * Creates a URL for the given action and model.
     * This method is called for each button and each row.
     * @param string $action the button name (or action ID)
     * @param \yii\db\ActiveRecord $model the data model
     * @return string the created URL
     */
    public function createUrl($action, $model, $key, $index)
    {
		$data = $this->documentButtons[$action];
		switch($action) {
			case 'work':
				if($work = $model->getWorks()->one())
					$url = Url::to([$data['action'], 'id' => $work->id, 'sort' => 'position']);
				else
					$url = '#';
				break;
			case 'workterminate':
				if($work = $model->getWorks()->one())
					$url = Url::to([$data['action'], 'id' => $work->id]);
				else
					$url = '#';
				break;
			case 'warn':
				$url = '#';
				$task = null;
				foreach($model->getWorks()->each() as $work)
					foreach($work->getWorkLines()->where(['status' => Work::STATUS_WARN])->each() as $wl)
						if(!$task) $task = $wl;
				if( $task  )
					$url = Url::to([$data['action'], 'id' => $task->id]);
				break;
			case 'print':
			case 'convert':
				$url = $model->id; // url built later
				break;
			default:
				$url = Url::to([$data['action'], 'id' => $model->id]);
				break;
		}
		return $url;
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
		$this->template = $model->getActions();
		
		$step2 = preg_replace_callback('/\\{label:([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
            $name = $matches[1];
			return $this->getActionLabel($name);
        }, $this->template);

		$step1 = preg_replace_callback('/\\{link:([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
            $name = $matches[1];
			return $this->getLinkLabel($name, $model);
        }, $step2);

		Yii::trace($step1, 'DocumentActionColumn::renderDataCellContent');
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
            $name = $matches[1];
            if (isset($this->buttons[$name])) {
				Yii::trace($name, 'DocumentActionColumn::renderDataCellContent');
                $url = $this->createUrl($name, $model, $key, $index);

                return call_user_func($this->buttons[$name], $url, $model, $key, $name);
            } else {
                return '';
            }
        }, $step1);
    }

	/** Wrapper to call function outside of gridview.
	 *
	 *	@param app\models\Document $model Model to display action for.
	 *	@return string HTML fragment with anchor/buttons to trigger actions.
	 *
	 */
	public function getButtons($model) {
		return $this->renderDataCellContent($model, null, null);
	}

    /**
     * Parses and formats a grid column
     */
    protected function parseFormat() {
        if ($this->hAlign === GridViewPDF::ALIGN_LEFT || $this->hAlign === GridViewPDF::ALIGN_RIGHT || $this->hAlign === GridViewPDF::ALIGN_CENTER) {
            Html::addCssStyle($this->headerOptions, "text-align:{$this->hAlign};");
            Html::addCssStyle($this->contentOptions, "text-align:{$this->hAlign};");
            Html::addCssStyle($this->footerOptions, "text-align:{$this->hAlign};");
        }
        if ($this->noWrap) {
            Html::addCssStyle($this->headerOptions, "white-space: nowrap;");
            Html::addCssStyle($this->contentOptions, "white-space: nowrap;");
            Html::addCssStyle($this->footerOptions, "white-space: nowrap;");
        }
        if ($this->vAlign === GridViewPDF::ALIGN_TOP || $this->vAlign === GridViewPDF::ALIGN_MIDDLE || $this->vAlign === GridViewPDF::ALIGN_BOTTOM) {
            Html::addCssStyle($this->headerOptions, "vertical-align:{$this->hAlign};");
            Html::addCssStyle($this->contentOptions, "vertical-align:{$this->hAlign};");
            Html::addCssStyle($this->footerOptions, "vertical-align:{$this->hAlign};");
        }
        if (trim($this->width) != '') {
            Html::addCssStyle($this->headerOptions, "width:{$this->width};");
            Html::addCssStyle($this->contentOptions, "width:{$this->width};");
            Html::addCssStyle($this->footerOptions, "width:{$this->width};");
        }
    }

	public function renderPageSummaryCell() { } // when used in kartik's GridView
    
}
