<?php

namespace backend\modules\admin\controllers;

use common\models\Billing;
use common\models\TechOfficial;
use common\models\TechProfile;
use common\models\TechSearch;
use common\models\TechVehicle;
use common\models\User;
use common\models\Vendor;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

//use yii\web\User;
//use yii\web\User;

class TechController extends Controller
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
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'getothers'],
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
//        $this->layout = "@app/modules/admin/views/layouts/main";
        $searchModel = new TechSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
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

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();
        Yii::$app->getSession()->setFlash('success', 'Tech deleted successfully');
        return $this->redirect(['index']);
    }

    public function actionUpdate($id)
    {
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
        $model->scenario = 'update';

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

    public function actionView($id)
    {
//        $this->layout = "@app/modules/admin/views/layouts/main";
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    protected function findModel($id)
    {
        if (($model = User::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
