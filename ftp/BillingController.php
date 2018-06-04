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
                        'actions' => ['index', 'create', 'update', 'view', 'delete','findImport','download'],
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

            if ($model->validate()) {
                if (isset($model->file)) {
                    $date = date('Y-m-d H:i:s');
                    $file = $model->file->name;
                    $folder = Yii::$app->basePath . '/web/uploads/';
                    $model->file->saveAs($folder . $date . $file);
                    try {
                        $filename = $folder . $date . $file;

                        $inputFileType = PHPExcel_IOFactory::identify($filename);
                        $objReader = PHPExcel_IOFactory::createReader($inputFileType);

                        $objPHPExcel = $objReader->load($filename);
                    } catch (Exception $e) {
                        die('Error');
                    }

                    $sheet = $objPHPExcel->getSheet(0);
                    $this->findImport($sheet,$model->type);
                     
//                    die('okay');
                }
               
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
    
     public function findImport($sheet,$type) {
         $highestRow = $sheet->getHighestRow();
                    $highestColumn = $sheet->getHighestColumn();
                    if ($type == 'access_point_details' && $highestColumn == 'D') {
                        for ($row = 1; $row <= $highestRow; $row++) {
                            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                            if ($row == 1) {
                                continue;
                            }
                            $billing = new Billing();
                            $billing->date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($rowData[0][0]));
                            $billing->type = $type;
                            $billing->user_id = $billing->insertTechId($rowData[0][1]);
                            $billing->techid = $rowData[0][1];
                            $billing->work_order = $rowData[0][2];
                            $billing->total = $rowData[0][3];
                            $billing->created_by = Yii::$app->user->id;
                            $billing->save(false);
                        }
                          return $this->redirect(['index']);
                    } else if ($type == 'billing_details' && $highestColumn == 'P') {
                        for ($row = 1; $row <= $highestRow; $row++) {
                            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                            if ($row == 1) {
                                continue;
                            }
                            $billing = new Billing();
                            $billing->wo_complete_date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($rowData[0][0]));
                            $billing->type = $type;
                            $billing->work_order = $rowData[0][2];
                            $billing->techid = $rowData[0][4];
                            $billing->user_id = $billing->insertTechId($rowData[0][4]);
                            $billing->work_code = $rowData[0][8];
                            $billing->total = $rowData[0][15];
                            $billing->created_by = Yii::$app->user->id;
                            $billing->save(false);
                            
                        }
                        return $this->redirect(['index']);
                    } else if ($type == 'all_digital_details' && $highestColumn == 'N') {
                        for ($row = 1; $row <= $highestRow; $row++) {
                            $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                            if ($row == 1) {
                                continue;
                            }
                            $billing = new Billing();
                            $billing->type = $type;
                            $billing->techid = $rowData[0][3];
                            $billing->user_id = $billing->insertTechId($rowData[0][3]);
                            $billing->work_order = $rowData[0][4];
                            $billing->date = date("Y-m-d", PHPExcel_Shared_Date::ExcelToPHP($rowData[0][5]));
                            $billing->total = $rowData[0][13];
                            $billing->created_by = Yii::$app->user->id;
                            $billing->save(false);
                            
                        }
                        return $this->redirect(['index']);
                    } else {
//                         \Yii::$app->session->setFlash('flashMessage', 'Error in the Excel sheet');
//                         \Yii::$app->session->hasFlash('flashMessage');
                         \Yii::$app->session->setFlash('error', 'Type and excelsheet format should be same');
//                        return $this->redirect(['create']);
                    }
     }
     
    

public function actionDownload(){
   
    $url= $_GET["url"];
    
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
