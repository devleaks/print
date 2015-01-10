<?php

namespace app\widgets;

use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQueryInterface;
use yii\grid\Column;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Inflector;

class DataColumnPDF extends Column {

    public $attribute;
    public $label;
    public $value;
    public $format = 'text';
    public $summary;
    public $hAlign;
    public $vAlign;
	public $hidden;
	public $noWrap;
	public $width;
    public $pageSummaryFunc = GridViewPDF::F_SUM;
    public $pageSummary = false;
    public $pageSummaryOptions = [];
	protected $_rows;
    public $hidePageSummary = false;

	public $showBefore = false;
	public $showAfter  = false;
	public $hideShowBefore = false;
	public $hideShowAfter  = false;

	public function init() {
        $this->parseFormat();
        parent::init();
        $this->setPageRows();
	}
    /**
     * @inheritdoc
     */
    protected function renderHeaderCellContent()
    {
        if ($this->header !== null || $this->label === null && $this->attribute === null) {
            return parent::renderHeaderCellContent();
        }

        $provider = $this->grid->dataProvider;

        if ($this->label === null) {
            if ($provider instanceof ActiveDataProvider && $provider->query instanceof ActiveQueryInterface) {
                /* @var $model Model */
                $model = new $provider->query->modelClass;
                $label = $model->getAttributeLabel($this->attribute);
            } else {
                $models = $provider->getModels();
                if (($model = reset($models)) instanceof Model) {
                    /* @var $model Model */
                    $label = $model->getAttributeLabel($this->attribute);
                } else {
                    $label = Inflector::camel2words($this->attribute);
                }
            }
        } else {
            $label = $this->label;
        }

        return $label;
    }

    /**
     * Returns the data cell value.
     * @param mixed $model the data model
     * @param mixed $key the key associated with the data model
     * @param integer $index the zero-based index of the data model among the models array returned by [[GridView::dataProvider]].
     * @return string the data cell value
     */
    public function getDataCellValue($model, $key, $index)
    {
        if ($this->value !== null) {
            if (is_string($this->value)) {
                return ArrayHelper::getValue($model, $this->value);
            } else {
                return call_user_func($this->value, $model, $key, $index, $this);
            }
        } elseif ($this->attribute !== null) {
            return ArrayHelper::getValue($model, $this->attribute);
        }
        return null;
    }

    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        if ($this->content === null) {
            return $this->grid->formatter->format($this->getDataCellValue($model, $key, $index), $this->format);
        } else {
            return parent::renderDataCellContent($model, $key, $index);
        }
    }

    /**
     * Checks `hidden` property and hides the column from display
     */
    protected function parseVisibility() {
        if ($this->hidden === true) {
            Html::addCssStyle($this->headerOptions, "display: none;");
            Html::addCssStyle($this->contentOptions, "display: none;");
            Html::addCssStyle($this->pageSummaryOptions, "display: none;");
            Html::addCssStyle($this->footerOptions, "display: none;");
        }
    }

    /**
     * Parses and formats a grid column
     */
    protected function parseFormat() {
        if ($this->hAlign === GridViewPDF::ALIGN_LEFT || $this->hAlign === GridViewPDF::ALIGN_RIGHT || $this->hAlign === GridViewPDF::ALIGN_CENTER) {
            Html::addCssStyle($this->headerOptions, "text-align:{$this->hAlign};");
            Html::addCssStyle($this->contentOptions, "text-align:{$this->hAlign};");
            Html::addCssStyle($this->pageSummaryOptions, "text-align:{$this->hAlign};");
            Html::addCssStyle($this->footerOptions, "text-align:{$this->hAlign};");
        }
        if ($this->noWrap) {
            Html::addCssStyle($this->headerOptions, "white-space: nowrap;");
            Html::addCssStyle($this->contentOptions, "white-space: nowrap;");
            Html::addCssStyle($this->pageSummaryOptions, "white-space: nowrap;");
            Html::addCssStyle($this->footerOptions, "white-space: nowrap;");
        }
        if ($this->vAlign === GridViewPDF::ALIGN_TOP || $this->vAlign === GridViewPDF::ALIGN_MIDDLE || $this->vAlign === GridViewPDF::ALIGN_BOTTOM) {
            Html::addCssStyle($this->headerOptions, "vertical-align:{$this->hAlign};");
            Html::addCssStyle($this->contentOptions, "vertical-align:{$this->hAlign};");
            Html::addCssStyle($this->pageSummaryOptions, "vertical-align:{$this->hAlign};");
            Html::addCssStyle($this->footerOptions, "vertical-align:{$this->hAlign};");
        }
        if (trim($this->width) != '') {
            Html::addCssStyle($this->headerOptions, "width:{$this->width};");
            Html::addCssStyle($this->contentOptions, "width:{$this->width};");
            Html::addCssStyle($this->pageSummaryOptions, "width:{$this->width};");
            Html::addCssStyle($this->footerOptions, "width:{$this->width};");
        }
    }
    
    /**
     * Store all rows for the column for the current page
     */
    protected function setPageRows()
    {
        if ($this->grid->showPageSummary && isset($this->pageSummary) && $this->pageSummary !== false && !is_string($this->pageSummary)) {
            $provider = $this->grid->dataProvider;
            $models = array_values($provider->getModels());
            $keys = $provider->getKeys();
            foreach ($models as $index => $model) {
                $key = $keys[$index];
                $this->_rows[] = $this->getDataCellValue($model, $key, $index);
            }
        }
    }

    /**
     * Calculates the summary of an input data based on aggregration function
     *
     * @param array $data the input data
     * @param string $type the summary aggregation function
     * @return float
     */
    protected function calculateSummary()
    {
        if (empty($this->_rows)) {
            return '';
        }
        $data = $this->_rows;
        $type = $this->pageSummaryFunc;
        switch ($type) {
            case null:
                return array_sum($data);
            case GridViewPDF::F_SUM:
                return array_sum($data);
            case GridViewPDF::F_COUNT:
                return count($data);
            case GridViewPDF::F_AVG:
                return count($data) > 0 ? array_sum($data) / count($data) : null;
            case GridViewPDF::F_MAX:
                return max($data);
            case GridViewPDF::F_MIN:
                return min($data);
        }
        return '';
    }

    /**
     * Renders the page summary cell.
     *
     * @return string the rendered result
     */
    public function renderPageSummaryCell()
    {
        $prepend = ArrayHelper::remove($this->pageSummaryOptions, 'prepend', '');
        $append = ArrayHelper::remove($this->pageSummaryOptions, 'append', '');
        return Html::tag('td', $prepend . $this->renderPageSummaryCellContent() . $append, $this->pageSummaryOptions);
    }

    /**
     * Gets the raw page summary cell content.
     *
     * @return string the rendered result
     */
    protected function getPageSummaryCellContent()
    {
        if ($this->pageSummary === true || $this->pageSummary instanceof \Closure) {
            $summary = $this->calculateSummary();
            return ($this->pageSummary === true) ? $summary : call_user_func($this->pageSummary, $summary, $this->_rows, $this);
        }
        if ($this->pageSummary !== false) {
            return $this->pageSummary;
        }
        return null;
    }

    /**
     * Renders the page summary cell content.
     *
     * @return string the rendered result
     */
    protected function renderPageSummaryCellContent()
    {
        if ($this->hidePageSummary) {
            return $this->grid->emptyCell;
        }
        $content = $this->getPageSummaryCellContent();
        if ($this->pageSummary === true) {
            return $this->grid->formatter->format($content, $this->format);
        }
        return ($content === null) ? $this->grid->emptyCell : $content;
    }

    /**
     * Get the raw footer cell content.
     *
     * @return string the rendered result
     */
    protected function getFooterCellContent()
    {
        return $this->footer;
    }

	/** CELL BEFORE **/



	/**
     * Compute "Before" Data
     *
     * @param array $data the input data
     * @param string $type the summary aggregation function
     * @return float
     */
    protected function getShowBefore()
    {
        if (empty($this->_rows)) {
            return '';
        }
        $data = $this->_rows;
        $type = $this->pageSummaryFunc;
        switch ($type) {
            case null:
                return array_sum($data);
            case GridViewPDF::F_SUM:
                return array_sum($data);
            case GridViewPDF::F_COUNT:
                return count($data);
            case GridViewPDF::F_AVG:
                return count($data) > 0 ? array_sum($data) / count($data) : null;
            case GridViewPDF::F_MAX:
                return max($data);
            case GridViewPDF::F_MIN:
                return min($data);
        }
        return '';
    }

    /**
     * Renders the page summary cell.
     *
     * @return string the rendered result
     */
    public function renderShowBeforeCell()
    {
        $prepend = ArrayHelper::remove($this->pageSummaryOptions, 'prepend', '');
        $append = ArrayHelper::remove($this->pageSummaryOptions, 'append', '');
        return Html::tag('td', $prepend . $this->renderShowBeforeCellContent() . $append, $this->pageSummaryOptions);
    }

    /**
     * Gets the raw page summary cell content.
     *
     * @return string the rendered result
     */
    protected function getShowBeforeCellContent()
    {
		return 'Ah!';
        if ($this->showBefore === true || $this->showBefore instanceof \Closure) {
            $before = $this->getShowBefore();
            return ($this->showBefore === true) ? $before : call_user_func($this->showBefore, $before, $this->_rows, $this);
        }
        if ($this->showBefore !== false) {
            return $this->showBefore;
        }
        return null;
    }

    /**
     * Renders the page summary cell content.
     *
     * @return string the rendered result
     */
    protected function renderShowBeforeCellContent()
    {
        if ($this->hideShowBefore) {
            return $this->grid->emptyCell;
        }
        $content = $this->getShowBeforeCellContent();
        if ($this->showBefore === true) {
            return $this->grid->formatter->format($content, $this->format);
        }
        return ($content === null) ? $this->grid->emptyCell : $content;
    }


	/** CELL AFTER **/



	/**
     * Compute "After" Data
     *
     * @param array $data the input data
     * @param string $type the summary aggregation function
     * @return float
     */
    protected function getShowAfter()
    {
        if (empty($this->_rows)) {
            return '';
        }
        $data = $this->_rows;
        $type = $this->pageSummaryFunc;
        switch ($type) {
            case null:
                return array_sum($data);
            case GridViewPDF::F_SUM:
                return array_sum($data);
            case GridViewPDF::F_COUNT:
                return count($data);
            case GridViewPDF::F_AVG:
                return count($data) > 0 ? array_sum($data) / count($data) : null;
            case GridViewPDF::F_MAX:
                return max($data);
            case GridViewPDF::F_MIN:
                return min($data);
        }
        return '';
    }

    /**
     * Renders the page summary cell.
     *
     * @return string the rendered result
     */
    public function renderShowAfterCell()
    {
        $prepend = ArrayHelper::remove($this->pageSummaryOptions, 'prepend', '');
        $append = ArrayHelper::remove($this->pageSummaryOptions, 'append', '');
        return Html::tag('td', $prepend . $this->renderShowAfterCellContent() . $append, $this->pageSummaryOptions);
    }

    /**
     * Gets the raw page summary cell content.
     *
     * @return string the rendered result
     */
    protected function getShowAfterCellContent()
    {
		return 'Oh!';
        if ($this->showAfter === true || $this->showAfter instanceof \Closure) {
            $after = $this->getShowAfter();
            return ($this->showAfter === true) ? $after : call_user_func($this->showAfter, $after, $this->_rows, $this);
        }
        if ($this->showAfter !== false) {
            return $this->showAfter;
        }
        return null;
    }

    /**
     * Renders the page summary cell content.
     *
     * @return string the rendered result
     */
    protected function renderShowAfterCellContent()
    {
        if ($this->hideShowAfter) {
            return $this->grid->emptyCell;
        }
        $content = $this->getShowAfterCellContent();
        if ($this->showAfter === true) {
            return $this->grid->formatter->format($content, $this->format);
        }
        return ($content === null) ? $this->grid->emptyCell : $content;
    }

}
