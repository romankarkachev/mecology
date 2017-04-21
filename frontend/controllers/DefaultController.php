<?php
namespace frontend\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use common\models\OrdersTp;
use common\models\Fthcdc;
use frontend\models\FkkoSearch;
use frontend\models\Converter;
use frontend\models\CalculatorForm;
use frontend\models\ContactForm;

/**
 * Default controller
 */
class DefaultController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    //'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Отображает приветственную страницу сайта.
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Возвращает результат рендера строки табличной части.
     * @param $counter integer
     * @return string
     */
    public function actionCalculatorRenderRow($counter)
    {
        return $this->renderAjax('_calculator_row', [
            'calculator' => new CalculatorForm(),
            'model' => new OrdersTp(),
            'counter' => $counter,
        ]);
    }

    /**
     * Функция выполняет поиск кода ТН ВЭД по значению, переданному в параметрах.
     * @param string $q
     * @return array|\yii\db\ActiveRecord[]
     */
    public function actionFthcdcList($q, $counter = null)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $query = Fthcdc::find()->select([
            'id',
            'text' => 'CONCAT(`hs_code`, IF(`hs_name` IS NULL OR `hs_name` = "", "", CONCAT(" - (", `hs_name`, ")")))',
            'hs_ratio',
            'hs_rate',
            $counter . ' AS `counter`',
        ])
            ->limit(100)
            ->orFilterWhere(['like', 'hs_code', $q])
            ->orFilterWhere(['like', 'hs_name', $q]);

        return ['results' => $query->asArray()->all()];
    }

    /**
     * Отображает страницу "Калькулятор".
     * @return mixed
     */
    public function actionCalculator()
    {
        $model = new CalculatorForm();
        if ($model->load(Yii::$app->request->post())) {
            $tp = $model->makeTpModelsFromPostArray();
            if ($model->validate() && $model->createOrder($tp)) {
                Yii::$app->session->setFlash('success', 'Обращение успешно отправлено ответственному менеджеру. Благодарим за использование нашего сайта!');
                return $this->redirect(['/calculator']);
            }
            else {
                Yii::$app->session->setFlash('error', 'При обработке Вашей формы были обнаружены ошибки.');

                $model->tp = $tp;
                return $this->render('calculator', [
                    'model' => $model,
                    'tp' => $tp,
                ]);
            }
        }

        $model->tp = [new OrdersTp()];
        return $this->render('calculator', [
            'model' => $model,
        ]);
    }

    /**
     * Отображает страницу "Конвертер".
     * @return mixed
     */
    public function actionConverter()
    {
        $searchModel = new Converter();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if (isset($searchModel->searchMode))
            if ($searchModel->searchMode == Converter::SEARCH_MODE_2014)
                $columns = [
                    [
                        'attribute' => 'fkko_code',
                        'label' => 'Код-2014',
                        'options' => ['width' => 130],
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                    'fkko_name:ntext:Наименование-2014',
                    [
                        'attribute' => 'fkko2002_code',
                        'label' => 'Код-2002',
                        'options' => ['width' => 130],
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                    [
                        'attribute' => 'fkko2002_name',
                        'format' => 'ntext',
                        'contentOptions' => function ($model, $key, $index, $column) {
                            if ($model->fkko2002_name == null || $model->fkko2002_name == '') return ['class' => 'text-muted'];
                            return [];
                        },
                        'content' => function ($model) {
                            /* @var $model \common\models\Fkko */
                            if ($model->fkko2002_name == null || $model->fkko2002_name == '')
                                return '- отсутствовал -';
                            else
                                return $model->fkko2002_name;
                        },
                    ],
                ];
            else
                $columns = [
                    [
                        'attribute' => 'fkko2002_code',
                        'label' => 'Код-2002',
                        'options' => ['width' => 130],
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                    'fkko2002_name:ntext:Наименование-2014',
                    [
                        'attribute' => 'fkko_code',
                        'label' => 'Код-2014',
                        'options' => ['width' => 130],
                        'headerOptions' => ['class' => 'text-center'],
                        'contentOptions' => ['class' => 'text-center'],
                    ],
                    'fkko_name:ntext',
                ];
            else $columns = [];

        return $this->render('converter', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'columns' => $columns,
        ]);
    }

    /**
     * Отображает страницу "Поиск по ФККО".
     * @return mixed
     */
    public function actionFkko()
    {
        $searchModel = new FkkoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('fkko', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Отображает страницу обратной связи (пункт меню "Пожаловаться").
     * @return mixed
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Ваше сообщение отправлено.');
            } else {
                Yii::$app->session->setFlash('error', 'При отправке сообщения возникла ошибка.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }
}
