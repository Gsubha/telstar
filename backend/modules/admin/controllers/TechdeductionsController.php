<?php

namespace backend\modules\admin\controllers;

use common\models\Deductions;
use Yii;
use common\models\TechDeductions;
use common\models\TechDeductionsSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * TechdeductionsController implements the CRUD actions for TechDeductions model.
 */
class TechdeductionsController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    public function actionGetprice()
    {
        $price = "0.00";
        if (Yii::$app->request->isAjax && Yii::$app->request->post('deduction_id')) {
            $id = Yii::$app->request->post('deduction_id');
            $model = Deductions::findOne($id);
            $price = $model->price;
        }
        echo $price;
        exit;
    }

    /**
     * Lists all TechDeductions models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TechDeductionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single TechDeductions model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TechDeductions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new TechDeductions();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $this->_save($model);
            Yii::$app->getSession()->setFlash('success', 'Tech Deduction created successfully!');
            return $this->redirect(['tech/update?id='.$model->user_id."&tab=4"]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing TechDeductions model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $this->_save($model);
            Yii::$app->getSession()->setFlash('success', 'Tech Deduction updated successfully');
            return $this->redirect(['tech/update?id='.$model->user_id."&tab=4"]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    protected function _save($model){
        $post = Yii::$app->request->post();
        if ($model->isNewRecord) {
            $model->created_at = date('Y-m-d H:i:s');
        }else {
            $model->updated_at = date('Y-m-d H:i:s');
        }
        $model->deduction_date = date('Y-m-d', strtotime($post['TechDeductions']['deduction_date']));
        $model->startdate = ($post['TechDeductions']['startdate']) ? date('Y-m-d', strtotime($post['TechDeductions']['startdate'])) : null;
        $model->enddate = ($post['TechDeductions']['enddate']) ? date('Y-m-d', strtotime($post['TechDeductions']['enddate'])) : null;
        $model->save();
    }


    /**
     * Deletes an existing TechDeductions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $userid = $model->user_id;
        $model->delete();
        Yii::$app->getSession()->setFlash('success', 'Tech Deduction deleted successfully');
        return $this->redirect(['tech/update?id='.$userid."&tab=4"]);

    }

    /**
     * Finds the TechDeductions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TechDeductions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TechDeductions::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
