<?php

namespace app\controllers;

use app\models\Kurs;
use app\models\Money;
use app\models\search\KursSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * KursController implements the CRUD actions for Kurs model.
 */
class KursController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Kurs models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new KursSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Kurs model.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Kurs model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Kurs();

        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Kurs model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Kurs model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Kurs model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Kurs the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Kurs::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionGetLatest(){

        $xml = simplexml_load_file('http://www.cbr.ru/scripts/XML_daily.asp');

        if ($xml) {
            $xml = json_decode(json_encode((array) $xml), 1);
            $selected = Money::find()->where('in_kurs = 1')->asArray()->all();

            foreach ($xml['Valute'] as $value){
                if (in_array($value['NumCode'], array_column($selected, 'num_code')))
                {
                    $today = date('Y-m-d');

                    $base_late = Kurs::find()->where(['date' => $today, 'num_code' => $value['NumCode']])->orderBy('date DESC')->one();
                    if ($base_late){
                        if ($value['Nominal'] > 1)
                            $base_late->rate = (float)($value['Value'] / $value['Nominal']);
                        else
                            $base_late->rate = (float)($value['Value']);

                        $base_late->save(false);
                    }else{
                        $model = new Kurs();
                        $model->date = $today;
                        $model->num_code = $value['NumCode'];
                        if ($value['Nominal'] > 1)
                            $model->rate = (float)($value['Value'] / $value['Nominal']);
                        else
                            $model->rate = (float)($value['Value']);
                        $model->save();
                        print_r($model->errors);
                    }

                }
            }
            $this->redirect(['kurs/index']);
        } else {
            exit('Failed to get data.');
        }
    }
}
