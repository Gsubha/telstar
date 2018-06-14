<?php

namespace backend\modules\admin\controllers;

use common\models\Billing;
use common\models\Location;
use common\models\Tech;
use common\models\TechOfficial;
use common\models\TechProfile;
use common\models\TechSearch;
use common\models\TechVehicle;
use common\models\User;
use common\models\Vendor;
use PHPExcel_IOFactory;
use PHPExcel_Shared_Date;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

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
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'getothers', 'import', 'download'],
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

    public function actionImport() {
        $model = new Tech();
        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $model->file = UploadedFile::getInstance($model, 'file');

            if (isset($model->file) && ($model->file->extension == 'xlsx' || $model->file->extension == 'xls')) {
                $date = date('Y-m-d H:i:s');
                $file = $model->file->name;
                $folder = Yii::$app->basePath . '/web/uploads/techprofiles/';
                $model->file->saveAs($folder . $date . $file);
                //return $this->redirect(['index']);
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
                $this->findImport($sheet);
                return $this->redirect(['index']);
            } else {
                \Yii::$app->session->setFlash('error', 'Only files with these extensions are allowed: xls, xlsx');
            }
        }

        return $this->render('import', [
                    'model' => $model,
        ]);
    }

    public function findImport($sheet) {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        if ($highestColumn == 'AB') {
            for ($row = 1; $row <= $highestRow; $row++) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                if ($row == 1) {
                    continue;
                }
                $techid = $rowData[0][21];
                $umodel = User::find()
                        ->where(['techid' => $techid])
                        ->one();
                if (!empty($umodel))
                    $uid = $umodel->id;
                else
                    $uid = '';
                /* User Table Insert/Updated - Started */
                if (empty($uid)) {
                    $user = new User();
                    $user->created_at = date('Y-m-d H:i:s');
                    $user->created_by = Yii::$app->user->id;
                    $user->techid = $techid;
                    $user->username = $techid;
                    $user->password_hash = Yii::$app->security->generatePasswordHash($techid);
                } else {
                    $user = $this->findModel($uid);
                    $user->updated_by = Yii::$app->user->id;
                    $user->updated_at = time();
                }
                $user->firstname = $rowData[0][2];
                $user->lastname = $rowData[0][3];
                $user->addr = $rowData[0][7];
                $user->city = $rowData[0][8];
                $user->state = $rowData[0][9];
                $user->zip = $rowData[0][10];
                $user->mobile = $rowData[0][6];
                $user->save();
                /* User Table Insert/Updated - Ended */
                if (!empty($user->id)) {
                    /* Tech Profile Table Insert/Updated - Started */
                    if (isset($umodel->profileUser)) {
                        $tech_imp = $umodel->profileUser;
                    } else {
                        $tech_imp = new TechProfile();
                        $tech_imp->user_id = $user->id;
                    }
                    if (User::validateDate($rowData[0][5])) {
                        $tech_imp->dob = date("Y-m-d", strtotime($rowData[0][5]));
                    } else {
                        $tech_imp->dob = NULL;
                    }
                    if ($rowData[0][0]) {
                        $tech_imp->vendor_id = Vendor::insertVendorId($rowData[0][0]);
                    }
                    if ($rowData[0][1]) {
                        $tech_imp->location_id = Location::insertLocation($rowData[0][1]);
                    }
                    $tech_imp->work_status = $rowData[0][4];
                    $tech_imp->save();
                    /* Tech Profile Table Insert/Updated - Ended */

                    /* Tech Vehicle Table Insert/Updated - Started */
                    if (isset($umodel->vehicleUser)) {
                        $tech_vehicle_imp = $umodel->vehicleUser;
                    } else {
                        $tech_vehicle_imp = new TechVehicle();
                        $tech_vehicle_imp->user_id = $user->id;
                    }
                    $tech_vehicle_imp->license_plate = $rowData[0][11];
                    $tech_vehicle_imp->state = $rowData[0][12];
                    $tech_vehicle_imp->registration = $rowData[0][13];
                    $tech_vehicle_imp->reg_exp = $rowData[0][14];
                    $tech_vehicle_imp->insurance_company = $rowData[0][15];
                    $tech_vehicle_imp->insurance_exp = $rowData[0][16];
                    $tech_vehicle_imp->driver_license = $rowData[0][17];
                    $tech_vehicle_imp->issuing_state = $rowData[0][18];
                    $tech_vehicle_imp->save();
                    /* Tech Vehicle Table Insert/Updated - Ended */

                    /* Tech Official Table Insert/Updated - Started */
                    if (isset($umodel->officialUser)) {
                        $tech_offcl_imp = $umodel->officialUser;
                    } else {
                        $tech_offcl_imp = new TechOfficial();
                        $tech_offcl_imp->user_id = $user->id;
                    }
                    if (User::validateDate($rowData[0][19])) {
                        $tech_offcl_imp->hire_date = date("Y-m-d", strtotime($rowData[0][19]));
                    } else {
                        $tech_offcl_imp->hire_date = NULL;
                    }
                    $tech_offcl_imp->job_desc = strtolower($rowData[0][20]);
                    $tech_offcl_imp->vid = $rowData[0][22];
                    $tech_offcl_imp->pid = $rowData[0][23];
                    if (User::validateDate($rowData[0][24])) {
                        $tech_offcl_imp->badge_exp_date = date("Y-m-d", strtotime($rowData[0][24]));
                    } else {
                        $tech_offcl_imp->badge_exp_date = NULL;
                    }
                    $tech_offcl_imp->insurance_exp = $rowData[0][16];
                    $tech_offcl_imp->last_background_check = date("Y-m-d", strtotime($rowData[0][25]));
                    if (User::validateDate($rowData[0][26])) {
                        $tech_offcl_imp->term_date = date("Y-m-d", strtotime($rowData[0][26]));
                    } else {
                        $tech_offcl_imp->term_date = NULL;
                    }
                    $rate_type_value = explode("-", $rowData[0][27]);
                    if (isset($rate_type_value[0])) {
                        $tech_offcl_imp->rate_code_type = $rate_type_value[0];
                    }
                    if (isset($rate_type_value[1])) {
                        $tech_offcl_imp->rate_code_val = $rate_type_value[1];
                    }
                    $tech_offcl_imp->save();
                    /* Tech Official Table Insert/Updated - Ended */
                }
            }
        }
    }

    public function actionCreate() {
//        $this->layout = "@app/modules/admin/views/layouts/main";
        $model = new User();
        $model->scenario = 'create';
        $tech = new TechProfile();
        $tech_offcl = new TechOfficial();
        $tech_vehicle = new TechVehicle();
        $tech->scenario = 'createvendor';
//         $tech_offcl->scenario ='createratecode';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $post = Yii::$app->request->post();

            $model->created_at = date('Y-m-d H:i:s');
            $model->created_by = Yii::$app->user->id;
            $model->save();
            if ($tech->load(Yii::$app->request->post())) {
                if ($post['TechProfile']['vendor_id'] == '-1') {
                    $vendor_id = Vendor::insertVendorId($post['TechProfile']['other']);
                }

                $tech->user_id = $model->id;
                if (!empty($post['TechProfile']['dob']))
                    $tech->dob = Billing::checkDate($post['TechProfile']['dob']);
                $tech->vendor_id = ($post['TechProfile']['vendor_id'] == '-1') ? $vendor_id : $post['TechProfile']['vendor_id'];
                $tech->save();
            }
            if ($tech_offcl->load(Yii::$app->request->post())) {
                if ($post['TechOfficial']['rate_code_type'] == 'In') {
                    $tech_offcl->rate_code_val = $post['TechOfficial']['inhouse'];
                } else {
                    $tech_offcl->rate_code_val = $post['TechOfficial']['corporate'];
                }
                $tech_offcl->user_id = $model->id;
                if (!empty($post['TechOfficial']['hire_date']))
                    $tech_offcl->hire_date = Billing::checkDate($post['TechOfficial']['hire_date']);
                if (!empty($post['TechOfficial']['badge_exp_date']))
                    $tech_offcl->badge_exp_date = Billing::checkDate($post['TechOfficial']['badge_exp_date']);
                if (!empty($post['TechOfficial']['term_date']))
                    $tech_offcl->term_date = Billing::checkDate($post['TechOfficial']['term_date']);
                if (!empty($post['TechOfficial']['last_background_check']))
                    $tech_offcl->last_background_check = Billing::checkDate($post['TechOfficial']['last_background_check']);
                $tech_offcl->save();
            }
            if ($tech_vehicle->load(Yii::$app->request->post())) {
                $tech_vehicle->user_id = $model->id;
                $tech_vehicle->save();
            }

            Yii::$app->getSession()->setFlash('success', 'Tech created successfully');
            return $this->redirect(['index']);
        } else {
            return $this->render('create', [
                        'model' => $model,
                        'tech' => $tech,
                        'tech_offcl' => $tech_offcl,
                        'tech_vehicle' => $tech_vehicle,
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

        $tech = TechProfile::find()->where(['user_id' => $id])->one();
        $tech_offcl = TechOfficial::find()->where(['user_id' => $id])->one();
//        $tech_offcl->scenario ='createratecode';
        $tech_vehicle = TechVehicle::find()->where(['user_id' => $id])->one();
        if (empty($tech))
            $tech = new TechProfile();
        if (empty($tech_offcl))
            $tech_offcl = new TechOfficial();
        if (empty($tech_vehicle))
            $tech_vehicle = new TechVehicle();
        //$model->scenario = 'update';

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {

            $post = Yii::$app->request->post();


            $model->created_at = date('Y-m-d H:i:s');
            if ($post['User']['password_hash'])
                $model->password_hash = Yii::$app->security->generatePasswordHash($post['User']['password_hash']);
            $model->updated_by = Yii::$app->user->id;
            $model->save();

            if ($tech->load(Yii::$app->request->post())) {
                $tech->user_id = $model->id;
                if (!empty($post['TechProfile']['dob']))
                    $tech->dob = Billing::checkDate($post['TechProfile']['dob']);
//                $tech->location_id = $post['TechProfile']['location_id'];
//                $tech->work_status = $post['TechProfile']['work_status'];
                $tech->save();
            }
            if ($tech_offcl->load(Yii::$app->request->post())) {
                if ($post['TechOfficial']['rate_code_type'] == 'In') {
                    $tech_offcl->rate_code_val = $post['TechOfficial']['inhouse'];
                } else {
                    $tech_offcl->rate_code_val = $post['TechOfficial']['corporate'];
                }
                $tech_offcl->user_id = $model->id;
                if (!empty($post['TechOfficial']['hire_date']))
                    $tech_offcl->hire_date = Billing::checkDate($post['TechOfficial']['hire_date']);
                if (!empty($post['TechOfficial']['badge_exp_date']))
                    $tech_offcl->badge_exp_date = Billing::checkDate($post['TechOfficial']['badge_exp_date']);
                if (!empty($post['TechOfficial']['term_date']))
                    $tech_offcl->term_date = Billing::checkDate($post['TechOfficial']['term_date']);
                if (!empty($post['TechOfficial']['last_background_check']))
                    $tech_offcl->last_background_check = Billing::checkDate($post['TechOfficial']['last_background_check']);
                $tech_offcl->save();
            }
            if ($tech_vehicle->load(Yii::$app->request->post())) {
                $tech_vehicle->user_id = $model->id;
                $tech_vehicle->save();
            }
            Yii::$app->getSession()->setFlash('success', 'Tech updated successfully');
            return $this->redirect(['index', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                        'model' => $model,
                        'tech' => $tech,
                        'tech_offcl' => $tech_offcl,
                        'tech_vehicle' => $tech_vehicle,
            ]);
        }
    }

    public function actionView($id) {
//        $this->layout = "@app/modules/admin/views/layouts/main";
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    public function actionDownload() {

        $url = $_GET["url"];

        $path = Yii::$app->basePath . "/$url";

        Yii::$app->response->sendFile($path);
    }

    protected function findModel($id) {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
