<?php

namespace backend\controllers;

use common\models\FthcdcRatios;
use Yii;
use common\models\Fthcdc;
use common\models\FthcdcSearch;
use common\models\OrdersTp;
use backend\models\FthcdcImport;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use moonland\phpexcel\Excel;

/**
 * FthcdcController implements the CRUD actions for Fthcdc model.
 */
class FthcdcController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'create', 'update', 'delete', 'clear', 'import'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['root'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Fthcdc models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FthcdcSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $searchApplied = Yii::$app->request->get($searchModel->formName()) != null;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchApplied' => $searchApplied,
        ]);
    }

    /**
     * Creates a new Fthcdc model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Fthcdc();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/fthcdc']);
        };

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Fthcdc model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/fthcdc']);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Fthcdc model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['/fthcdc']);
    }

    /**
     * Finds the Fthcdc model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Fthcdc the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fthcdc::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('Запрошенная страница не существует.');
        }
    }

    /**
     * Выполняет удаление всех моделей.
     */
    public function actionClear()
    {
        // определяем идентификаторы элементов, которые используются в данный момент в других объектах
        // в заявках:
        $used = OrdersTp::find()->select('hs_id')->distinct()->asArray()->column();

        // удаляем остальные
        Fthcdc::deleteAll(['not in', 'id', $used]);

        if (count($used) > 0)
            Yii::$app->getSession()->setFlash('info', 'Некоторые записи удалены не были, потому что используются в других объектах.');
        else
            Yii::$app->getSession()->setFlash('success', 'Все записи были удалены.');

        return $this->redirect(['/fthcdc']);
    }

    /**
     * Импорт из Excel.
     * @inheritdoc
     */
    public function actionImport()
    {
        $model = new FthcdcImport();

        if (Yii::$app->request->isPost) {
            // дурацкое действие, но что поделать, когда зубная паста недоступна
            $model->year = Yii::$app->request->post()['FthcdcImport']['year'];

            $model->importFile = UploadedFile::getInstance($model, 'importFile');
            $filename = Yii::getAlias('@uploads').'/'.Yii::$app->security->generateRandomString().'.'.$model->importFile->extension;
            if ($model->upload($filename)) {
                $model->load(Yii::$app->request->post());
                // если файл удалось успешно загрузить на сервер
                // выбираем все данные из файла в массив
                $data = Excel::import($filename);
                if (count($data) > 0) {
                    // если удалось прочитать, сразу удаляем файл
                    unlink($filename);

                    // выборка существующих позиций номенклатуры для исключения создания дубликатов
                    // в процессе выполнения цикла пополняется
                    $exists_nom = Fthcdc::find()->select(['fthcdc_ratios.id', 'hs_code', 'hs_ratio'])
                        ->leftJoin('fthcdc_ratios', 'fthcdc_ratios.hs_id = fthcdc.id AND fthcdc_ratios.year = ' . intval($model->year))
                        ->orderBy('hs_code')->asArray()->all();

                    // перебираем массив и создаем новые элементы
                    $new_records_count = 0; // массив успешно созданных записей
                    $errors_import = array(); // массив для ошибок при импорте
                    $row_number = 1; // 0-я строка - это заголовок
                    foreach ($data as $row) {
                        // проверяем обязательные поля, если хоть одно не заполнено, пропускаем строку
                        if (trim($row['code']) == '' || trim($row['ratio']) == '' || trim($row['rate']) == '') {
                            $errors_import[] = 'В строке '.$row_number.' одно из обязательных полей не заполнено!';
                            $row_number++;
                            continue;
                        }

                        // преобразуем наименование в человеческий вид
                        $code = trim($row['code']);

                        // преобразуем норматив
                        $hs_ratio = floatval(str_replace(',', '.', $row['ratio']));

                        // проверка на существование
                        $key = array_search($code, array_column($exists_nom, 'hs_code'));
                        if ($key !== false) {
                            // такой код ТН ВЭД уже есть в базе
                            // проверим, не изменился ли норматив
                            if (floatval($exists_nom[$key]['hs_ratio']) != $hs_ratio) {
                                // норматив изменился, произведем обновление норматива
                                FthcdcRatios::updateAll([
                                    'hs_ratio' => $hs_ratio,
                                ], [
                                    'id' => $exists_nom[$key]['id'],
                                    'year' => $model->year,
                                ]);

                                $errors_import[] = 'Обновлен норматив ' . $code . ': ' . $exists_nom[$key]['hs_ratio'] . ' -> ' . $hs_ratio;
                                $row_number++;
                                continue;
                            }
                            else {
                                // обнаружен дубликат, норматив совпадает
                                $row_number++;
                                continue;
                            }
                        }

                        // код ТН ВЭД не обнаружен, создаем новый и к нему норматив
                        $new_record = new Fthcdc();
                        $new_record->hs_code = $code;
                        $new_record->hs_group = intval($row['group']);
                        $new_record->hs_name = trim($row['name']);
                        $new_record->hs_rate = intval(str_replace(',', '.', $row['rate']));

                        if (!$new_record->save()) {
                            $details = '';
                            foreach ($new_record->errors as $error)
                                foreach ($error as $detail)
                                    $details .= '<p>'.$detail.'</p>';
                            $errors_import[] = 'В строке '.$row_number.' не удалось сохранить новый элемент!'.$details;
                        }
                        else {
                            // увеличиваем количество успешно созданных записей
                            $new_records_count++;

                            $exists_nom[] = $new_record->hs_code;
                            $ratio = new FthcdcRatios();
                            $ratio->year = $model->year;
                            $ratio->hs_id = $new_record->id;
                            $ratio->hs_ratio = $hs_ratio;
                            if (!$ratio->save()) {
                                $details = '';
                                foreach ($new_record->errors as $error)
                                    foreach ($error as $detail)
                                        $details .= '<p>'.$detail.'</p>';
                                $errors_import[] = 'В строке '.$row_number.' не удалось сохранить норматив!'.$details;
                            }
                        }

                        $row_number++;
                    }; // foreach

                    // зафиксируем ошибки, чтобы показать
                    if (count($errors_import) > 0) {
                        $errors = '';
                        foreach ($errors_import as $error)
                            $errors .= '<p>'.$error.'</p>';
                        Yii::$app->getSession()->setFlash('error', $errors);
                    } else {
                        $addition = '';
                        if ($new_records_count > 0)
                            $addition = ' Добавлено новых записей: ' . $new_records_count . '.';
                        Yii::$app->getSession()->setFlash('success', 'Импорт завершен.' . $addition);
                    }

                }; // count > 0

                return $this->redirect(['/fthcdc']);
            }
        };

        $model->year = date('Y');

        return $this->render('import', [
            'model' => $model,
        ]);
    }
}
