<?php
use app\assets\AppAsset;
use app\assets\CalculatorAsset;
use app\models\Backup;
use app\models\CaptureSearch;
use app\models\User;
use app\widgets\Alert;
use devleaks\chardinjs\ChardinJSAsset;
use devleaks\introjs\IntroJSAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\widgets\Breadcrumbs;

if(isset(Yii::$app->params['BootswatchTheme'])) {
	raoul2000\bootswatch\BootswatchAsset::$theme = Yii::$app->params['BootswatchTheme'];
	raoul2000\bootswatch\BootswatchAsset::register($this);
}

AppAsset::register($this);
CalculatorAsset::register($this);
IntroJSAsset::register($this);
ChardinJSAsset::register($this);
$apphomedir = Yii::getAlias('@app');
/* @var $this \yii\web\View */
/* @var $content string */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="icon" href="<?= Yii::$app->homeUrl ?>favicon.ico">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
			$name = Yii::$app->name . (YII_ENV_DEV ? ' –DEV='.`cd $apphomedir ; git describe --tags` : '') . (YII_DEBUG ? '–DEBUG' : '') ;
            NavBar::begin([
                'brandLabel' => $name,
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top'
                ],
            ]);

			$menu = [];
			$menu[] = ['label' => Yii::$app->formatter->asDate(date('c')), 'url' => "javascript:do_introjs();"];
				
            if(!Yii::$app->user->isGuest) {
				$menu[] = ['label' => Yii::t('store', 'Cash'), 'url' => ['/accnt/cash/list']];

				$work_menu = [];
				if(defined('YII_DEVLEAKS')) {
					$dev_menu = [];
                	$dev_menu[] = ['label' => Yii::t('store', 'All documents'), 'url' => ['/order/document/', 'sort' => '-updated_at']];
					$dev_menu[] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];
					$dev_menu[] = ['label' => Yii::t('store', 'History'), 'url' => ['/admin/history', 'sort' => '-created_at']];
					$dev_menu[] = ['label' => Yii::t('store', 'Gii'), 'url' => ['/gii']];
					$menu[] = ['label' => Yii::t('store', 'Development'), 'items' => $dev_menu];
				}

				if(User::hasRole(['admin', 'manager', 'compta', 'frontdesk', 'employee']))
                	$work_menu[] = ['label' => Yii::t('store', 'Cash'), 'url' => ['/accnt/cash/list']];
				if(User::hasRole(['admin', 'manager', 'compta', 'frontdesk', 'employee']))
                	$work_menu[] = ['label' => Yii::t('store', 'Orders'), 'url' => ['/order']];
				if(User::hasRole(['admin', 'manager', 'employee', 'worker']))
                	$work_menu[] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work']];
				if(User::hasRole(['admin', 'manager']))
                	$work_menu[] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store']];
				if(User::hasRole(['admin']))
                	$work_menu[] = ['label' => Yii::t('store', 'Administration'), 'url' => ['/admin']];
				if(User::hasRole(['admin', 'compta']))
                	$work_menu[] = ['label' => Yii::t('store', 'Accounting'), 'url' => ['/accnt']];

               	$work_menu[] = ['label' => Yii::t('store', 'Calculator'), 'url' => ['/assets/calculator/'], 'linkOptions' => ['target' => '_blank']];

			$menu[] = ['label' => Yii::t('store', 'Menu'), 'items' => $work_menu];


				$help_menu = [];
				// $help_menu[] = ['label' => 'Chardin',		'url' => "javascript:do_chardinjs();"];
				$help_menu[] = ['label' => 'Intro',			'url' => "javascript:do_introjs();"];
				$help_menu[] = ['label' => 'Documentation',	'url' => ['/help/guide-README.html']];

			$menu[] = ['label' => Yii::t('store', 'Help'), 'items' => $help_menu/*'url' => ['/help/guide-README.html']*/];


				$user_menu = [];
                $user_menu[] = ['label' => Yii::t('store', 'Profile'), 'url' => ['/user/settings']];
                $user_menu[] = ['label' => Yii::t('store', 'Logout'), 'url' => ['/user/security/logout'], 'linkOptions' => ['data-method' => 'post']];

            $menu[] = ['label' => Yii::$app->user->identity->username, 'items' => $user_menu];

            } else
				$menu[] = ['label' => 'Login', 'url' => ['/user/security/login']];

            echo Nav::widget([
                'options' => [
					'class' => 'navbar-nav navbar-right',
					'data-intro' => 'Menus vers écrans principaux, connexion ou déconnexion, et modification du profil (mot de passe, adresse email, etc.)'
				],
                'items' => $menu
            ]);


			/** Search field */
			$form = ActiveForm::begin(['action' => Url::to(['/order/document/search'])]);
			$model = new CaptureSearch();
			echo $form->field($model, 'search')->textInput(['maxlength' => 40, 'class' => 'input-sm pull-right', 'style' => 'margin-top: 10px;'])->label('');
			ActiveForm::end();


            NavBar::end();
        ?>

        <div class="container-fluid" style="margin-top: 60px;">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
				'options' => [
					'class' => 'breadcrumb',
					'data-intro' => 'Menu hiérarchique'
				]
            ]) ?>
        	<?= Alert::widget() ?> 
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container-fluid">
            <p class="pull-left">&copy; Labo JJ Micheli <?= date('Y') ?>
				<small><?php echo ' — Version '.`cd $apphomedir ; git describe --tags`;
					if(YII_DEBUG) {
						echo ' — Last commit: '.`git log -1 --format=%cd --relative-date`;
						echo ' — '.`hostname`;
						echo ' — '.Yii::$app->getDb()->dsn;
					}
				?></small>
			</p>
        </div>
    </footer>
<script type="text/javascript">
<?php
$this->beginBlock('PRINT_LAYOUT_HELP_JS'); ?>
function do_introjs() {
	introJs().setOptions({ 'nextLabel': 'Suivant', 'prevLabel': 'Précédent', 'doneLabel': 'Terminé', 'skipLabel': 'Sortir' }).start();
}
function do_chardinjs() {
	$('body').chardinJs('start');
}
<?php $this->endBlock(); ?>
</script>
<?php
$this->registerJs($this->blocks['PRINT_LAYOUT_HELP_JS'], yii\web\View::POS_END);

$this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>