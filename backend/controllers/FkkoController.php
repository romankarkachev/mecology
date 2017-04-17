<?php

namespace backend\controllers;

use Yii;
use common\models\Fkko;
use backend\models\FkkoSearch;
use backend\models\FkkoImport;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use moonland\phpexcel\Excel;

/**
 * FkkoController implements the CRUD actions for Fkko model.
 */
class FkkoController extends Controller
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
     * Lists all Fkko models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new FkkoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $searchApplied = Yii::$app->request->get($searchModel->formName()) != null;

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'searchApplied' => $searchApplied,
        ]);
    }

    /**
     * Creates a new Fkko model.
     * If creation is successful, the browser will be redirected to the 'index' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Fkko();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/fkko']);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Fkko model.
     * If update is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['/fkko', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Fkko model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['/fkko']);
    }

    /**
     * Finds the Fkko model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Fkko the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Fkko::findOne($id)) !== null) {
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
        Fkko::deleteAll();

        Yii::$app->getSession()->setFlash('success', 'Все записи были удалены.');

        return $this->redirect(['/fkko']);
    }

    /**
     * Импорт из Excel.
     * @inheritdoc
     */
    public function actionImport()
    {
        $model = new FkkoImport();

        if (Yii::$app->request->isPost) {
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
                    $exists_nom = Fkko::find()->select('fkko_code')->orderBy('fkko_code')->column();

                    // перебираем массив и создаем новые элементы
                    $errors_import = array(); // массив для ошибок при импорте
                    $row_number = 1; // 0-я строка - это заголовок
                    foreach ($data as $row) {
                        // проверяем обязательные поля, если хоть одно не заполнено, пропускаем строку
                        if (trim($row['id']) == '' || trim($row['date']) == '' || trim($row['fkko']) == '' || trim($row['name']) == '') {
                            $errors_import[] = 'В строке '.$row_number.' одно из обязательных полей не заполнено!';
                            $row_number++;
                            continue;
                        }

                        // преобразуем код в человеческий вид
                        $fkko = $row['fkko'];
                        $fkko = str_replace(chr(194).chr(160), '', $fkko);
                        $fkko = str_replace(' ', '', $fkko);
                        $fkko = str_replace("\r\n", '', $fkko);
                        $fkko = str_replace("\n", '', $fkko);

                        // преобразуем наименование в человеческий вид
                        $name = FkkoImport::cleanName($row['name']);

                        // проверка на существование
                        // отключена, потому что группы и элементы могут быть похожими
//                        if (in_array($fkko, $exists_nom)) {
//                            $errors_import[] = 'Обнаружен дубликат: ' . $fkko . '. Пропущен.';
//                            $row_number++;
//                            continue;
//                        }

                        // пустые наименования и бессмысленные пропускаем
                        if ($name == '' || $name == '...') {
                            $row_number++;
                            continue;
                        }

                        $new_record = new Fkko();
                        // данные из источника сохраняем в качестве оригинала
                        $new_record->src_id = trim($row['id']);
                        $new_record->src_name = trim($row['name']);
                        $new_record->src_fkko = trim($row['fkko']);

                        // ФККО-2014
                        $new_record->fkko_code = $fkko;
                        $new_record->fkko_name = $name;
                        $date2014 = trim($row['date']);
                        if ($date2014 != '') $new_record->fkko_date = FkkoImport::transformDate($date2014);

                        // ФККО-2002
                        $fkko2002 = trim($row['fkko2002']);
                        $name2002 = trim($row['name2002']);
                        if ($fkko2002 != '') $new_record->fkko2002_code = $fkko2002;
                        if ($name2002 != '') $new_record->fkko2002_name = $name2002;

                        // класс опасности
                        $new_record->fkko_dc = FkkoImport::DangerClassRep(substr(trim($fkko), -1));

                        if (!$new_record->save()) {
                            $details = '';
                            foreach ($new_record->errors as $error)
                                foreach ($error as $detail)
                                    $details .= '<p>'.$detail.'</p>';
                            $errors_import[] = 'В строке '.$row_number.' не удалось сохранить новый элемент.'.$details;
                        }
                        else $exists_nom[] = $new_record->fkko_code;

                        $row_number++;
                    }; // foreach

                    // зафиксируем ошибки, чтобы показать
                    if (count($errors_import) > 0) {
                        $errors = '';
                        foreach ($errors_import as $error)
                            $errors .= '<p>'.$error.'</p>';
                        Yii::$app->getSession()->setFlash('error', $errors);
                    };

                }; // count > 0

                return $this->redirect(['/fkko']);
            }
        };

        return $this->render('import', [
            'model' => $model,
        ]);
    }
}
