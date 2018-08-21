<?php

namespace backend\modules\admin\controllers;

use common\models\TechDeductionsSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\ArrayHelper;
use yii\web\Controller;

class ReportsController extends Controller
{
     public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => [''],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['index', 'techdeductions'],
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
    public function actionIndex()
    {
        return $this->redirect(['reports/techdeductions']);
    }
    public function actionTechdeductions(){
        $searchModel = new TechDeductionsSearch();
        if (isset($_GET['TechDeductionsSearch']['techid'])) {
            $searchModel->techid = $_GET['TechDeductionsSearch']['techid'];
        }
        if (isset($_GET['TechDeductionsSearch']['startdate'])) {
            $searchModel->startdate = $_GET['TechDeductionsSearch']['startdate'];
        }
        if (isset($_GET['TechDeductionsSearch']['enddate'])) {
            $searchModel->enddate = $_GET['TechDeductionsSearch']['enddate'];
        }
        $dataProvider = $searchModel->techDeductionSearch(Yii::$app->request->queryParams);
        $pagesize = (isset($_GET['pagesize'])) ? $_GET['pagesize'] : 50;
        $dataProvider->pagination->pageSize = $pagesize;
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
           // 'techids'=>$techids,
        ]);
    }

}
