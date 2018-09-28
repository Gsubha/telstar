<?php

namespace backend\modules\admin\controllers;

use common\models\Deductions;
use common\models\InstalmentDeductions;
use common\models\TechDeductions;
use common\models\TechDeductionsSearch;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TechdeductionsController implements the CRUD actions for TechDeductions model.
 */
class TechdeductionsController extends Controller {

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
                        'actions' => ['index', 'create', 'update', 'view', 'delete', 'getprice'],
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

    public function actionGetprice() {
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
    public function actionIndex() {
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
    public function actionView($id) {
        return $this->render('view', [
                    'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new TechDeductions model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate() {
        $model = new TechDeductions();

        if ($model->load(Yii::$app->request->post())) {
            if ($this->_save($model)) {
                Yii::$app->getSession()->setFlash('success', 'Tech Deduction created successfully!');
                return $this->redirect(['tech/update?id=' . $model->user_id . "&tab=4"]);
            }
        }

//        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
//            $this->_save($model);
//            Yii::$app->getSession()->setFlash('success', 'Tech Deduction created successfully!');
//            return $this->redirect(['tech/update?id='.$model->user_id."&tab=4"]);
//        }

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
    public function actionUpdate($id) {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post())) {
            $this->_save($model);
            Yii::$app->getSession()->setFlash('success', 'Tech Deduction updated successfully');
            return $this->redirect(['tech/update?id=' . $model->user_id . "&tab=4"]);
        }

        return $this->render('update', [
                    'model' => $model,
        ]);
    }

    protected function _save($model) {
        $post = Yii::$app->request->post();
        if ($model->isNewRecord) {
            $model->created_at = date('Y-m-d H:i:s');
        }
        $model->updated_at = date('Y-m-d H:i:s');
        $main_cat = $post['TechDeductions']['category'];
        switch ($main_cat) {
            case "ongoing":
                $sub_cat = $post['TechDeductions']['deduction_info'];
                switch ($sub_cat) {
                    case "Meter":
                        $model->serial_num = $post['TechDeductions']['serial_num'];
                        $model->deduction_date = date('Y-m-d', strtotime($post['TechDeductions']['deduction_date']));
                        $model->total = $post['TechDeductions']['total'];

                        /* empty other fields - start */
                        $empty_other_meter_fields = ['vin', 'description', 'yes_or_no', 'startdate', 'enddate', 'percentage', 'work_order'];
                        foreach ($empty_other_meter_fields as $mk => $mv) {
                            $model->$mv = NULL;
                        }
                        /* empty other fields - end */

                        if ($model->save()) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case "Truck":
                        $model->yes_or_no = $post['TechDeductions']['van_yes'];
                        $model->vin = $post['TechDeductions']['vin'];
                        $model->deduction_date = date('Y-m-d', strtotime($post['TechDeductions']['van_deduction_date']));
                        $model->total = $post['TechDeductions']['van_amount'];
                        /* empty other fields - start */
                        $empty_other_truck_fields = ['serial_num', 'description', 'startdate', 'enddate', 'percentage', 'work_order'];
                        foreach ($empty_other_truck_fields as $mk => $mv) {
                            $model->$mv = NULL;
                        }
                        /* empty other fields - end */
                        if ($model->save()) {
                            return true;
                        } else {
                            return false;
                        }
                        break;

                    case "WC/GL":
                        $model->yes_or_no = $post['TechDeductions']['wcgl_yes'];
                        $model->percentage = $post['TechDeductions']['percentage'];
                        /* empty other fields - start */
                        $empty_other_wcgl_fields = ['vin', 'serial_num', 'description', 'startdate', 'enddate', 'percentage', 'work_order', 'deduction_date', 'total'];
                        foreach ($empty_other_wcgl_fields as $mk => $mv) {
                            $model->$mv = NULL;
                        }
                        /* empty other fields - end */
                        if ($model->save()) {
                            return true;
                        } else {
                            return false;
                        }
                        break;
                }
                break;

            case "onetime":
                $model->deduction_info = $post['TechDeductions']['onetime_deduction_type'];
                $model->deduction_date = date('Y-m-d', strtotime($post['TechDeductions']['deduction_date']));
                $model->total = $post['TechDeductions']['onetime_amt'];
                /* empty other fields - start */
                $empty_other_onetime_fields = ['vin', 'serial_num', 'startdate', 'enddate', 'percentage'];
                foreach ($empty_other_onetime_fields as $mk => $mv) {
                    $model->$mv = NULL;
                }
                /* empty other fields - end */
                if ($model->save()) {
                    return true;
                } else {
                    return false;
                }
                break;

            case "installment":
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    $model->deduction_info = $post['TechDeductions']['installment_type'];
                    $model->total = $post['TechDeductions']['installment_amount'];
                    $model->num_installment = $post['TechDeductions']['num_installment'];
                    $model->description = $post['TechDeductions']['installment_comment'];
                    $model->startdate = date('Y-m-d', strtotime($post['TechDeductions']['startdate']));
                    //$model->enddate = date('Y-m-d', strtotime($post['TechDeductions']['enddate']));
                    $days = (($model->num_installment * 7) - 1);
                    $model->enddate = date('Y-m-d', strtotime($post['TechDeductions']['startdate'] . " +$days days"));
                    /* empty other fields - start */
                    $empty_other_installment_fields = ['deduction_date', 'serial_num', 'yes_or_no', 'vin', 'percentage', 'work_order'];
                    foreach ($empty_other_installment_fields as $mk => $mv) {
                        $model->$mv = NULL;
                    }
                    /* empty other fields - end */
                    if ($model->save()) {

                        if ($model->isNewRecord) {
                            $pid = Yii::$app->db->getLastInsertID();
                        } else {
                            $pid = $model->id;
                            InstalmentDeductions::deleteAll(['tech_deductions_id' => $pid]);
                        }
                        $StDate = $model->startdate;
                        $num_inst = $model->num_installment;
                        $inst_price = number_format((float) ($model->total / $num_inst), 2, '.', '');
                        
                        for ($i = 1; $i <= $num_inst; $i++) {
                            $inst_model = new InstalmentDeductions();
                            $inst_model->tech_deductions_id = $pid;
                            $inst_model->inst_start_date = $StDate;
                            $inst_model->inst_end_date = date('Y-m-d', strtotime($StDate . ' + 6 days'));
                            $inst_model->inst_paid_amt = $inst_price;
                            $inst_model->total_paid_amt = $inst_price*$i;
                            $inst_model->remain_amt = $model->total - $inst_model->total_paid_amt;
                            $paidArr=$post['paid'];
                            $inst_model->paid_status = $paidArr['status'][$i];
                            if(empty($paidArr['date'][$i])){
                                $inst_model->paid_date = NULL;
                            }else{
                                $inst_model->paid_date = date("Y-m-d",strtotime($paidArr['date'][$i]));
                            }
                            $inst_model->save();
                            $StDate = date('Y-m-d', strtotime($StDate . ' + 7 days'));
                        }
                    }
                    $transaction->commit();
                    return true;
                } catch (Exception $e) {
                    $transaction->rollBack();
                    return false;
                }
//                if ($model->save()) {
//                    return true;
//                } else {
//                    return false;
//                }
                break;
        }
    }

    /**
     * Deletes an existing TechDeductions model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id) {
        $model = $this->findModel($id);
        $userid = $model->user_id;
        $model->delete();
        Yii::$app->getSession()->setFlash('success', 'Tech Deduction deleted successfully');
        return $this->redirect(['tech/update?id=' . $userid . "&tab=4"]);
    }

    /**
     * Finds the TechDeductions model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return TechDeductions the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = TechDeductions::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

}
