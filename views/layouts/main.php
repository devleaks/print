<?php
use app\assets\AppAsset;
use app\assets\CalculatorAsset;
use devleaks\introjs\IntroJSAsset;
use devleaks\chardinjs\ChardinJSAsset;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;

AppAsset::register($this);
CalculatorAsset::register($this);
IntroJSAsset::register($this);
ChardinJSAsset::register($this);

/* @var $this \yii\web\View */
/* @var $content string */
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>
    <div class="wrap">
        <?php
            NavBar::begin([
                'brandLabel' => 'Labo JJ Micheli',
                'brandUrl' => Yii::$app->homeUrl,
                'options' => [
                    'class' => 'navbar-inverse navbar-fixed-top'
                ],
            ]);

			$menu = [];
			$menu[] = ['label' => Yii::$app->formatter->asDate(date('c')), 'url' => "#"];
				
            if(!Yii::$app->user->isGuest) {

				$work_menu = [];
				if(Yii::$app->user->identity->role == 'manager' || Yii::$app->user->identity->role == 'admin')
                	$work_menu[] = ['label' => Yii::t('store', 'Orders'), 'url' => ['/order/']];
                $work_menu[] = ['label' => Yii::t('store', 'Works'), 'url' => ['/work/']];
				if(Yii::$app->user->identity->role == 'manager' || Yii::$app->user->identity->role == 'admin')
                	$work_menu[] = ['label' => Yii::t('store', 'Management'), 'url' => ['/store/']];
				if(Yii::$app->user->identity->role == 'admin')
                	$work_menu[] = ['label' => Yii::t('store', 'Administration'), 'url' => ['/admin/']];

               	$work_menu[] = ['label' => Yii::t('store', 'Calculator'), 'url' => ['/assets/calculator/'], 'linkOptions' => ['target' => '_blank']];

			$menu[] = ['label' => Yii::t('store', 'Menu'), 'items' => $work_menu];


				$help_menu = [];
				$help_menu[] = ['label' => 'Chardin',
					'url' => "javascript:$('body').chardinJs('start');"];
				$help_menu[] = ['label' => 'Intro',
					'url' => "javascript:introJs().setOptions({ 'nextLabel': 'Suivant', 'prevLabel': 'Précédent', 'doneLabel': 'Terminé', 'skipLabel': 'Sortir' }).start();"];
				$help_menu[] = ['label' => 'Documentation',
					'url' => ['/site/help']];

			$menu[] = ['label' => Yii::t('store', 'Help'), 'items' => $help_menu/*'url' => ['/site/help']*/];


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
            NavBar::end();
        ?>

        <div class="container">
            <?= Breadcrumbs::widget([
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
				'options' => [
					'class' => 'breadcrumb',
					'data-intro' => 'Menu hiérarchique'
				]
            ]) ?>

			<?php foreach(array('success', 'error', 'warning', 'info') as $category): ?>
				<?php if (Yii::$app->session->hasFlash($category)): ?>
		                <div class="alert alert-<?= $category == 'error' ? 'danger' : $category ?>">
							<a href="#" class="close" data-dismiss="alert">&times;</a>
		                    <?= Yii::$app->session->getFlash($category) ?>
		                </div>
				<?php endif; ?>
			<?php endforeach; ?>
 
            <?= $content ?>
        </div>
    </div>

    <footer class="footer">
        <div class="container">
            <p class="pull-left">&copy; Labo JJ Micheli <?= date('Y') ?></p>
        </div>
    </footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
