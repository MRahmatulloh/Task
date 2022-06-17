<?php

namespace app\controllers;

use app\models\Money;
use app\models\search\MoneySearch;
use Yii;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * MoneyController implements the CRUD actions for Money model.
 */
class MoneyController extends Controller
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
     * Lists all Money models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new MoneySearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Money model.
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
     * Creates a new Money model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Money();

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
     * Updates an existing Money model.
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
     * Deletes an existing Money model.
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
     * Finds the Money model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Money the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Money::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionSettings()
    {
        $searchModel = new MoneySearch();
        $model = new Money();
        $searchModel->in_kurs = 1;
        $dataProvider = $searchModel->search($this->request->queryParams);
        $time['time'] = 3600;

        if ($model->load(Yii::$app->request->post()))
        {
            if ($model->in_kurs && is_array($model->in_kurs)){
                $list_k = $model->in_kurs;
                $update = Yii::$app->getDb()->createCommand('update money set in_kurs = null, in_widget = null')->execute();
                foreach ($list_k as $item){
                    $money = Money::findOne(['num_code'=>$item]);
                    if ($money){
                        $money->in_kurs = 1;
                        $money->save(false);
                    }
                }
            }

            if ($model->in_widget && is_array($model->in_widget)){
                $list_k = $model->in_widget;
                $update = Yii::$app->getDb()->createCommand('update money set in_widget = null')->execute();
                foreach ($list_k as $item){
                    $money = Money::findOne(['num_code'=>$item]);
                    if ($money){
                        $money->in_widget = 1;
                        $money->save(false);
                    }
                }
            }

            if ($model->num_code){
                $time = ['time'=>$model->num_code];
                file_put_contents("data/time.json",json_encode($time));
            }

            $model = new Money();
        }

        if(is_file('data/time.json'))
            $time = json_decode(file_get_contents('data/time.json'), true);

        return $this->render('settings', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'model' => $model,
            'time' => $time
        ]);
    }
}
