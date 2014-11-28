<?php

use kartik\markdown\Markdown;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
$this->title = Yii::t('store', 'Help');

$docdir = Yii::getAlias('@app').'/docs/';

if(isset($file)) {
	$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['/site/help']];
	if($file) {
		$fn = $docdir.$file.'.md';
		if(file_exists($fn))
			$content = file_get_contents ($fn);		
	}
}

$this->params['breadcrumbs'][] = isset($file) ? $file : $this->title;

?>
<div class="site-help">

<?php if (isset($content)): ?>
	<?= Markdown::convert($content);?>
<?php else: ?>
	<h1><?= Yii::t('store', 'Help') ?></h1>

	<ul>
	<?php
			foreach(new DirectoryIterator($docdir) as $file) {
				if(strrpos($file, '.md') == (strlen($file)-3)) {
					$fn = str_replace('.md', '', $file);
					echo '<li>'.Html::a($fn, Url::to(['/site/help', 'f' => $fn])).'</li>'; // ;
				}
			}
	?>
	</ul>
<?php endif;?>
</div>
