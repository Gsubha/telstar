<?php

namespace backend\modules\admin\controllers;

use common\models\User;
use common\models\TechSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
//use yii\web\User;

//use yii\web\User;

class TechController extends Controller {

    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [''],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'create', 'update', 'view', 'delete'],
                        'allow' => true,
                        'roles' => ['@'],
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

    public function actionIndex() {
//        $this->layout = "@app/modules/admin/views/layouts/main";
        $searchModel = new TechSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate() {
//        $this->layout = "@app/modules/admin/views/layouts/main";
        $model = new User();
        $model->scenario = 'create';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $post = Yii::$app->request->post();
             $model->created_at = date('Y-m-d H:i:s');
            $model->created_by = Yii::$app->user->id;
            $model->save();
            Yii::$app->getSession()->setFlash('success', 'Tech created successfully');
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
            ]);
        }
    }

    public function actionDelete($id) {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', 'Tech deleted successfully');
        return $this->redirect(['index']);
    }

    public function actionUpdate($id) {
        $model = $this->findModel($id);
  $model->scenario = 'update';
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
              $post = Yii::$app->request->post();
               $model->created_at = date('Y-m-d H:i:s');
               if($post['User']['password_hash'])
            $model->password_hash = Yii::$app->security->generatePasswordHash($post['User']['password_hash']);
            $model->updated_by = Yii::$app->user->id;
            $model->save();
            Yii::$app->getSession()->setFlash('success', 'Tech updated successfully');
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
            ]);
        }
    }

    public function actionView($id) {
//        $this->layout = "@app/modules/admin/views/layouts/main";
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id) {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
