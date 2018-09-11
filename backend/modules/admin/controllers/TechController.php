<?php

namespace backend\modules\admin\controllers;

use common\components\Myclass;
use common\models\Billing;
use common\models\ImportFiles;
use common\models\Location;
use common\models\Tech;
use common\models\TechDeductions;
use common\models\TechDeductionsSearch;
use common\models\TechOfficial;
use common\models\TechProfile;
use common\models\TechSearch;
use common\models\TechVehicle;
use common\models\User;
use common\models\Vendor;
use PHPExcel_IOFactory;
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
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'getothers', 'import', 'download', 'techlist', 'myworks', 'sadmin', 'importtechdeduction'],
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
        $pagesize = (isset($_GET['pagesize'])) ? $_GET['pagesize'] : 50;
        $dataProvider->pagination->pageSize = $pagesize;
        return $this->render('index', [
                    'searchModel' => $searchModel,
                    'dataProvider' => $dataProvider,
        ]);
    }

    public function actionImporttechdeduction() {
        $model = new TechDeductions(['scenario' => 'import']);
        if ($model->load(Yii::$app->request->post())) {
            //$post = Yii::$app->request->post();
            $model->file = UploadedFile::getInstance($model, 'file');
            $ImportType = $model->category;
            if (isset($model->file) && ($model->file->extension == 'xlsx' || $model->file->extension == 'xls')) {
                $date = date('Y-m-d H:i:s');
                $time = time();
                $file = $model->file->name;
                $folder = Yii::$app->basePath . '/web/uploads/techdeductions/';
                $upld_id = NULL;
                $model->file->saveAs($folder . $time . '_' . $file);
                //return $this->redirect(['index']);
                try {
                    $filename = $folder . $time . '_' . $file;
                    /* Save Uploaded File Details - Start */
                    $import_files_model = new ImportFiles();
                    $import_files_model->cat = "Tech_Deduction";
                    $import_files_model->type = "Deductions";
                    $import_files_model->name = $time . '_' . $file;
                    $import_files_model->path = 'web/uploads/techdeductions';
                    $import_files_model->created_at = $time;
                    $import_files_model->created_by = Yii::$app->user->id;
                    if ($import_files_model->save())
                        $upld_id = Yii::$app->db->getLastInsertID();
                    /* Save Uploaded File Details - End */

                    $inputFileType = PHPExcel_IOFactory::identify($filename);
                    $objReader = PHPExcel_IOFactory::createReader($inputFileType);
                    $objReader->setReadDataOnly(true);
                    $objPHPExcel = $objReader->load($filename);
                } catch (Exception $e) {
                    die('Error');
                }

                $sheet = $objPHPExcel->getSheet(0);
                $this->findImporttechdeductions($sheet, $upld_id, $ImportType);
                //return $this->redirect(['index']);
            } else {
                \Yii::$app->session->setFlash('error', 'Only files with these extensions are allowed: xls, xlsx');
            }
        }

        return $this->render('importtechdeduction', [
                    'model' => $model,
        ]);
    }

    public function findImporttechdeductions($sheet, $upld_id = NULL, $ImportType = NULL) {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        if ($ImportType == "onetime" && $highestColumn == 'G') {
            /* One Time Deduction Import - Start */
            // [0] => Tech ID , [1] => Category, [2] => Deduction Type, [3] => Deduction Date, [4] => Work Order, [5] => Amount, [6] => Comments
            for ($row = 1; $row <= $highestRow; $row++) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                if ($row == 1) {
                    continue;
                }
                // trim the values
                foreach ($rowData[0] as $key => $val) {
                    $rowData[0][$key] = trim($rowData[0][$key]);
                }
                /* Check Tech ID Empty or not */
                $techid = $rowData[0][0];
                if ($techid == '') {
                    continue;
                }
                /* Check Tech ID Exist or not */
                $umodel = User::find()->where(['techid' => $techid])->one();
                if (!empty($umodel)) {
                    $uid = $umodel->id;
                } else {
                    continue;
                }
                $model = new TechDeductions();
                $model->user_id = $uid;

                /* Check Category type Onetime or not */
                if (strtolower($rowData[0][1]) == "onetime") {
                    $model->category = strtolower($rowData[0][1]);
                } else {
                    continue;
                }
                $model->deduction_info = $rowData[0][2];
                if (Myclass::validateDate($rowData[0][3])) {
                    $model->deduction_date = date("Y-m-d", strtotime($rowData[0][3]));
                } else {
                    $model->deduction_date = NULL;
                }
                $model->work_order = $rowData[0][4];
                $model->total = $rowData[0][5];
                $model->description = $rowData[0][6];
                $model->upload_id = $upld_id;
                $model->created_at = date('Y-m-d H:i:s');
                $model->created_by = Yii::$app->user->id;

                if ($model->save(false)) {
                    \Yii::$app->session->setFlash('success', 'OneTime Tech Deduction Imported Successfully');
                } else {
                    // $erros = json_encode($model->errors);
                    \Yii::$app->session->setFlash('error', 'Failed to Import OneTime Tech Deduction. Please try again');
                }
            }
            /* One Time Deduction Import - End */
        } else if ($ImportType == "ongoing" && $highestColumn == 'I') {
            /* Ongoing Deduction Import - Start */
            // [0] => Tech ID , [1] => Category, [2] => Deduction Type, [3] => Deduction Date, [4] => Serial Number, [5] => Amount, [6] => Yes/No
            // [7] => Vin#, [8] => Percentage
            $success_flag = 0;
            for ($row = 1; $row <= $highestRow; $row++) {
                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
                if ($row == 1) {
                    continue;
                }
                // trim the values
                foreach ($rowData[0] as $key => $val) {
                    $rowData[0][$key] = trim($rowData[0][$key]);
                }
                /* Check Tech ID Empty or not */
                $techid = $rowData[0][0];
                if ($techid == '') {
                    continue;
                }
                /* Check Tech ID Exist or not */
                $umodel = User::find()->where(['techid' => $techid])->one();
                if (!empty($umodel)) {
                    $uid = $umodel->id;
                } else {
                    continue;
                }

                $model = new TechDeductions();
                $model->user_id = $uid;

                /* Check Category type Onetime or not */
                if (strtolower($rowData[0][1]) == "ongoing") {
                    $model->category = strtolower($rowData[0][1]);
                } else {
                    continue;
                }

                if ($rowData[0][2] == '') {
                    continue;
                } else {
                    $model->deduction_info = $rowData[0][2];
                }
                $model->upload_id = $upld_id;
                $model->created_at = date('Y-m-d H:i:s');
                $model->created_by = Yii::$app->user->id;
                switch (strtolower($rowData[0][2])) {
                    case "meter":
                        if ($rowData[0][3] != '' && $rowData[0][4] != '' && $rowData[0][5] != '') {
                            if (Myclass::validateDate($rowData[0][3])) {
                                $model->deduction_date = date("Y-m-d", strtotime($rowData[0][3]));
                            } else {
                                $model->deduction_date = NULL;
                            }
                            $model->serial_num = $rowData[0][4];
                            $model->total = $rowData[0][5];
                            if ($model->save(false)) {
                                $success_flag = $success_flag + 1;
                            }
                        }
                        break;
                    case "truck":
                        if ($rowData[0][3] != '' && $rowData[0][6] != '' && $rowData[0][7] != '' && $rowData[0][5] != '') {
                            if (Myclass::validateDate($rowData[0][3])) {
                                $model->deduction_date = date("Y-m-d", strtotime($rowData[0][3]));
                            } else {
                                $model->deduction_date = NULL;
                            }
                            $yes_or_no = strtolower($rowData[0][6]);
                            $model->yes_or_no = ($yes_or_no == "yes") ? "1" : "0";
                            $model->vin = $rowData[0][7];
                            $model->total = $rowData[0][5];
                            if ($model->save(false)) {
                                $success_flag = $success_flag + 1;
                            }
                        }
                        break;
                    case "wc/gl":
                        if ($rowData[0][6] != '' && $rowData[0][8] != '') {
                            $yes_or_no = strtolower($rowData[0][6]);
                            $model->yes_or_no = ($yes_or_no == "yes") ? "1" : "0";
                            $model->percentage = $rowData[0][8];
                            if ($model->save(false)) {
                                $success_flag = $success_flag + 1;
                            }
                        }
                        break;
                    default:
                        \Yii::$app->session->setFlash('error', 'Deduction Type does not match. Please try again.');
                        break;
                }
                if ($success_flag) {
                    \Yii::$app->session->setFlash('success', 'Ongoing Tech Deduction Imported Successfully');
                } else {
                    //$erros = json_encode($model->errors);
                    \Yii::$app->session->setFlash('error', 'Failed to Import Ongoing Tech Deduction. Please try again');
                }
            }
            /* Ongoing Deduction Import - End */
        } 
//        else if ($ImportType == "installment" && $highestColumn == 'H') {
//            /* Installment Deduction Import - Start */
//            // [0] => Tech ID , [1] => Category, [2] => Deduction Type, [3] => Amount, [4] => Number of installments, [5] => Comments, [6] => StartWeek Date, [7] => EndWeek Date
//            for ($row = 1; $row <= $highestRow; $row++) {
//                $rowData = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
//                if ($row == 1) {
//                    continue;
//                }
//                // trim the values
//                foreach ($rowData[0] as $key => $val) {
//                    $rowData[0][$key] = trim($rowData[0][$key]);
//                }
//                /* Check Tech ID Empty or not */
//                $techid = $rowData[0][0];
//                if ($techid == '' || $rowData[0][6] == '' || $rowData[0][7] == '' || $rowData[0][4] == '') {
//                    continue;
//                }
//                /* Check Tech ID Exist or not */
//                $umodel = User::find()->where(['techid' => $techid])->one();
//                if (!empty($umodel)) {
//                    $uid = $umodel->id;
//                } else {
//                    continue;
//                }
//                $model = new TechDeductions();
//                $model->user_id = $uid;
//
//                /* Check Category type Installment or not */
//                if (strtolower($rowData[0][1]) == "installment") {
//                    $model->category = strtolower($rowData[0][1]);
//                } else {
//                    continue;
//                }
//
//                $model->deduction_info = $rowData[0][2];
//                $model->total = $rowData[0][3];
//                $model->num_installment = $rowData[0][4];
//                $model->description = $rowData[0][5];
//
//                if (Myclass::validateDate($rowData[0][6])) {
//                    $model->startdate = date("Y-m-d", strtotime($rowData[0][6]));
//                } else {
//                    $model->startdate = NULL;
//                }
//
//                if (Myclass::validateDate($rowData[0][7])) {
//                    $model->enddate = date("Y-m-d", strtotime($rowData[0][7]));
//                } else {
//                    $model->enddate = NULL;
//                }
//
//                $model->upload_id = $upld_id;
//                $model->created_at = date('Y-m-d H:i:s');
//                $model->created_by = Yii::$app->user->id;
//
//                if ($model->save(false)) {
//                    \Yii::$app->session->setFlash('success', 'Installment Tech Deduction Imported Successfully');
//                } else {
//                    // $erros = json_encode($model->errors);
//                    \Yii::$app->session->setFlash('error', 'Failed to Import Installment Tech Deduction. Please try again');
//                }
//            }
//            /* Installment Deduction Import - End */
//        } 
        else {
            \Yii::$app->session->setFlash('error', 'Type and excelsheet format should be same');
        }
    }

    public function actionImport() {
        $model = new Tech();
        if ($model->load(Yii::$app->request->post())) {
            $post = Yii::$app->request->post();
            $model->file = UploadedFile::getInstance($model, 'file');

            if (isset($model->file) && ($model->file->extension == 'xlsx' || $model->file->extension == 'xls')) {
                $date = date('Y-m-d H:i:s');
                $time = time();
                $file = $model->file->name;
                $folder = Yii::$app->basePath . '/web/uploads/techprofiles/';
                $model->file->saveAs($folder . $time . '_' . $file);
                //return $this->redirect(['index']);
                try {
                    $filename = $folder . $time . '_' . $file;
                    /* Save Uploaded File Details - Start */
                    $import_files_model = new ImportFiles();
                    $import_files_model->cat = "Tech";
                    $import_files_model->type = "Profile";
                    $import_files_model->name = $time . '_' . $file;
                    $import_files_model->path = 'web/uploads/techprofiles';
                    $import_files_model->created_at = $time;
                    $import_files_model->created_by = Yii::$app->user->id;
                    $import_files_model->save();
                    /* Save Uploaded File Details - End */

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
                if ($row == 1 || (empty(trim($rowData[0][21])))) {
                    continue;
                }
                // trim the values
                foreach ($rowData[0] as $key => $val) {
                    $rowData[0][$key] = trim($rowData[0][$key]);
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
                    if ($user->validateDate($rowData[0][5])) {
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
                    if (isset($umodel->techOfficial)) {
                        $tech_offcl_imp = $umodel->techOfficial;
                    } else {
                        $tech_offcl_imp = new TechOfficial();
                        $tech_offcl_imp->user_id = $user->id;
                    }
                    if ($user->validateDate($rowData[0][19])) {
                        $tech_offcl_imp->hire_date = date("Y-m-d", strtotime($rowData[0][19]));
                    } else {
                        $tech_offcl_imp->hire_date = NULL;
                    }
                    $tech_offcl_imp->job_desc = strtolower($rowData[0][20]);
                    $tech_offcl_imp->vid = $rowData[0][22];
                    $tech_offcl_imp->pid = $rowData[0][23];
                    if ($user->validateDate($rowData[0][24])) {
                        $tech_offcl_imp->badge_exp_date = date("Y-m-d", strtotime($rowData[0][24]));
                    } else {
                        $tech_offcl_imp->badge_exp_date = NULL;
                    }
                    $tech_offcl_imp->insurance_exp = $rowData[0][16];
                    $tech_offcl_imp->last_background_check = date("Y-m-d", strtotime($rowData[0][25]));
                    if ($user->validateDate($rowData[0][26])) {
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
                    $tech_offcl_imp->rate_percent = $tech_offcl_imp->getratecode($tech_offcl_imp->rate_code_type, $tech_offcl_imp->rate_code_val);
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
                    $tech_offcl->rate_percent = $tech_offcl->getratecode("In", $tech_offcl->rate_code_val);
                } else {
                    $tech_offcl->rate_code_val = $post['TechOfficial']['corporate'];
                    $tech_offcl->rate_percent = $tech_offcl->getratecode("Cp", $tech_offcl->rate_code_val);
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
        $billing = new Billing();
        $billing->changeDeleteStatus($id);
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
                    $tech_offcl->rate_percent = $tech_offcl->getratecode("In", $tech_offcl->rate_code_val);
                } else {
                    $tech_offcl->rate_code_val = $post['TechOfficial']['corporate'];
                    $tech_offcl->rate_percent = $tech_offcl->getratecode("Cp", $tech_offcl->rate_code_val);
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

            $searchModel = new TechDeductionsSearch();
            $data = Yii::$app->request->queryParams;
            $dataProvider = $searchModel->search($data);
            $dataProvider->pagination->pageSize = 10;
            $dataProvider->pagination->params = $data + ['tab' => 4];

            return $this->render('update', [
                        'model' => $model,
                        'tech' => $tech,
                        'tech_offcl' => $tech_offcl,
                        'tech_vehicle' => $tech_vehicle,
                        'dataProvider' => $dataProvider
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

    /* Mocukp Pages */

    public function actionTechlist() {
        return $this->render('/mockups/tech_list');
    }

    public function actionMyworks() {
        return $this->render('/mockups/myworks');
    }

    public function actionSadmin() {
        return $this->render('/mockups/sub_admin_list');
    }

}
