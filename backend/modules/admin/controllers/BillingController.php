<?php

namespace backend\modules\admin\controllers;

use common\models\Billing;
use common\models\BillingSearch;
use PHPExcel_IOFactory;
use PHPExcel_Shared_Date;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

/**
 * BillingController implements the CRUD actions for Billing model.
 */
class BillingController extends Controller {

    /**
     * {@inheritdoc}
     */
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
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'findImport', 'download'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
//                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Billing models.
     * @return mixed
     */
    public function actionIndex() {
//        $this->layout = "@app/modules/admin/views/layouts/main";
        $searchModel = new BillingSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Billing model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Billing model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new Billing();
        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $model->file = UploadedFile::getInstance($model, 'file');
            
            $model->created_by = Yii::$app->user->id;

//            if ($model->validate()) {
                if (isset($model->file) && ($model->file->extension=='xlsx' || $model->file->extension=='xls')) {
                    $date = date('Y-m-d H:i:s');
                    $file = $model->file->name;
                    $folder = Yii::$app->basePath . '/web/uploads/';
                    $model->file->saveAs($folder . $date . $file);
                    try {
                        $filename = $folder . $date . $file;
                        $inputFileType = PHPExcel_IOFactory::identify($filename);
                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                        $objReader->setReadDataOnly(true);
                        $objPHPExcel = $objReader->load($filename);
                    } catch (Exception $e) {
                        die('Error');
                    }

                    $sheet = $objPHPExcel->getSheet(0);
                    $this->findImport($sheet, $model->type);
//                    die('okay');
                }
                else{
                 \Yii::$app->session->setFlash('error', 'Only files with these extensions are allowed: xls, xlsx');
                }
        }

        return $this->render('create', [
                    'model' => $model,
        ]);
    }

    /**
     * Updates an existing Billing model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function findImport($sheet, $type) {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
//        $count=0;
        if ($type == 'access_point_details' && $highestColumn == 'D') {
            for ($row = 1; $row <= $highestRow; $row++) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                if ($row == 1) {
                    continue;
                }
                //$rowData[0][0]= DATE, $rowData[0][1]= TECHID, $rowData[0][2]= WORK ORDER,$rowData[0][3]= TOTAL 
                $date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($rowData[0][0]));
                $user_id = Billing::insertTechId($rowData[0][1]);
                $model = Billing::find()
                        ->where(['techid' => $rowData[0][1]])
                        ->andWhere(['type' => $type])
                        ->andWhere(['date' => $date])
                        ->andWhere(['work_order' => $rowData[0][2]])
                        ->andWhere(['user_id' => $user_id])
                        ->one();
                if (empty($model)) {
                    $check = Billing::checkAccessPoint($type, $date, $rowData[0][1], $rowData[0][2], $user_id, $rowData[0][3], Yii::$app->user->id);
                } else {
                    $model->total = $rowData[0][3];
                    $model->save(false);
                }
//                 $count++;
            }
//             Yii::$app->getSession()->setFlash('success', 'You have added '.$count.' records');
            return $this->redirect(['index']);
             
        } else if ($type == 'billing_details'  && $highestColumn == 'P') {
            for ($row = 1; $row <= $highestRow; $row++) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                if ($row == 1) {
                    continue;
                }
                //$rowData[0][0]= wo complete date, $rowData[0][4]= TECHID, $rowData[0][2]= WORK ORDER,$rowData[0][15]= TOTAL , $rowData[0][8]= WORK CODE 
                   $date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($rowData[0][0]));
                $user_id = Billing::insertTechId($rowData[0][4]);
                $model = Billing::find()
                        ->where(['techid' => $rowData[0][4]])
                        ->andWhere(['type' => $type])
                        ->andWhere(['wo_complete_date' => $date])
                        ->andWhere(['work_order' => $rowData[0][2]])
                        ->andWhere(['user_id' => $user_id])
                        ->andWhere(['work_code' => $rowData[0][8]])
                        ->one();
                    if (empty($model)) {
                    $check = Billing::checkBillingDetails($type, $date, $rowData[0][4], $rowData[0][2], $user_id, $rowData[0][15], Yii::$app->user->id,$rowData[0][8]);
                } else {
                    $model->total = $rowData[0][15];
                    $model->save(false);
                }
                
            }
            return $this->redirect(['index']);
        } else if ($type == 'all_digital_details' && $highestColumn == 'N') {
            for ($row = 1; $row <= $highestRow; $row++) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                if ($row == 1) {
                    continue;
                }
                //$rowData[0][5]= wo complete date, $rowData[0][3]= TECHID, $rowData[0][4]= WORK ORDER,$rowData[0][13]= TOTAL 
                   $date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($rowData[0][5]));
                $user_id = Billing::insertTechId($rowData[0][3]);
                $model = Billing::find()
                        ->where(['techid' => $rowData[0][3]])
                        ->andWhere(['type' => $type])
                        ->andWhere(['date' => $date])
                        ->andWhere(['work_order' => $rowData[0][4]])
                        ->andWhere(['user_id' => $user_id])
                        ->one();
                
                    if (empty($model)) {
                    $check = Billing::checkAccessPoint($type, $date, $rowData[0][3], $rowData[0][4], $user_id, $rowData[0][13], Yii::$app->user->id);
                } else {
                   
                    $model->total = $rowData[0][13];
                    
                    $model->save(false);
                }
            }
            return $this->redirect(['index']);
        } else {
            \Yii::$app->session->setFlash('error', 'Type and excelsheet format should be same');
        }
    }

    public function actionDownload() {

        $url = $_GET["url"];

        $path = Yii::$app->basePath . "/$url";

        Yii::$app->response->sendFile($path);
    }

    public function actionUpdate($id) {
//        $this->layout = "@app/modules/admin/views/layouts/main";
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['index', 'id' => $model->id]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Billing model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Billing model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Billing the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Billing::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
