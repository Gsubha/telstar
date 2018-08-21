<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TechDeductions;

/**
 * TechDeductionsSearch represents the model behind the search form of `common\models\TechDeductions`.
 */
class TechDeductionsSearch extends TechDeductions {
    public $pageSize;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'user_id'], 'integer'],
            [['qty', 'total'], 'number'],
            [['created_at', 'updated_at', 'deleted_at', 'description', 'deduction_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios() {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params) {
//        echo "<pre>";
//        print_r($params);
//        exit;
        $query = TechDeductions::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            // 'id' => $this->id,
            'user_id' => @$params['id'],
//            'deduction_id' => $this->deduction_id,
//            'qty' => $this->qty,
//            'total' => $this->total,
//            'created_by' => $this->created_by,
//            'updated_by' => $this->updated_by,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
//            'deleted_at' => $this->deleted_at,
//            'deduction_date' => $this->deduction_date,
        ]);

        //  $query->andFilterWhere(['like', 'description', $this->description]);

        return $dataProvider;
    }

    public function techDeductionSearch($params) {
        $query = TechDeductions::find()->joinWith(['user']);
        $this->load($params);
        if ($this->techid != '') {
            $query->andWhere(['tech_deductions.user_id' => $this->techid]);
        }
        if ($this->startdate != "") {
          if ($this->enddate == "") {
          $this->enddate = date('Y-m-d');
          }
          $query->andWhere("`tech_deductions`.`deduction_date` IS NOT NULL");
          $query->andWhere("(DATE(`tech_deductions`.`deduction_date`) BETWEEN '".date("Y-m-d",strtotime($this->startdate))."' AND '".date("Y-m-d",strtotime($this->enddate))."')
           OR (DATE(`tech_deductions`.`startdate`) BETWEEN '".date("Y-m-d",strtotime($this->startdate))."' AND '".date("Y-m-d",strtotime($this->enddate))."') "
           . "OR (DATE(`tech_deductions`.`enddate`) BETWEEN '".date("Y-m-d",strtotime($this->startdate))."' AND '".date("Y-m-d",strtotime($this->enddate))."')
          ");
          //echo $query->createCommand()->getRawSql();
        }else{
             $startdate = date('Y-m-d',strtotime('last Sunday'));  
              $enddate = date('Y-m-d',strtotime('next Saturday'));
                     $query->andWhere("`tech_deductions`.`deduction_date` IS NOT NULL");
          $query->andWhere("(DATE(`tech_deductions`.`deduction_date`) BETWEEN '".$startdate."' AND '".$enddate."')
           OR (DATE(`tech_deductions`.`startdate`) BETWEEN '".$startdate."' AND '".$enddate."') "
           . "OR (DATE(`tech_deductions`.`enddate`) BETWEEN '".$startdate."' AND '".$enddate."')
          ");
    
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->pageSize, //50,
//                'params' => $datamod
            ],
        ]);
        /*$dataProvider->sort->attributes['vendorShortName'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['vendor.vendor_type' => SORT_ASC],
            'desc' => ['vendor.vendor_type' => SORT_DESC],
        ];*/

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        return $dataProvider;
        /*
          // add conditions that should always apply here
          if ($this->started_at != "") {
          if ($this->ended_at == "") {
          $this->ended_at = date('Y-m-d');
          }
          $query->andWhere('DATE_FORMAT(wo_complete_date ,"%Y-%m-%d") >= "' . date('Y-m-d',strtotime($this->started_at)). '" AND DATE_FORMAT(wo_complete_date,"%Y-%m-%d") <= "' .date('Y-m-d',strtotime($this->ended_at)) . '"');
          $datamod['BillingSearch']['started_at'] =date('Y-m-d',strtotime($this->started_at));
          $datamod['BillingSearch']['ended_at'] =date('Y-m-d',strtotime($this->ended_at));
          }else{
          $staticstart = date('Y-m-d',strtotime('last Sunday'));
          $staticfinish = date('Y-m-d',strtotime('next Saturday'));
          $query->andWhere('DATE_FORMAT(wo_complete_date ,"%Y-%m-%d") >= "' . $staticstart. '" AND DATE_FORMAT(wo_complete_date,"%Y-%m-%d") <= "' .$staticfinish . '"');
          $datamod['BillingSearch']['started_at'] =$staticstart;
          $datamod['BillingSearch']['ended_at'] =$staticfinish;

          }

          if ($this->techid != "") {
          $query->andWhere( ['billing.techid' => $this->techid]);

          }
          $query->groupBy(['billing.user_id']);
          // echo "<div style='padding:10px;border:1px solid;color:green;'>".$query->createCommand()->getRawSql()."</div>";
          $dataProvider = new ActiveDataProvider([
          'query' => $query,
          'sort' => ['defaultOrder' => ['wo_complete_date' => 'DESC']],
          'pagination' => [
          'pageSize' => $this->pageSize,//50,
          //                'params' => $datamod
          ],
          ]);
          $dataProvider->sort->attributes['vendorShortName'] = [
          // The tables are the ones our relation are configured to
          // in my case they are prefixed with "tbl_"
          'asc' => ['vendor.vendor_type' => SORT_ASC],
          'desc' => ['vendor.vendor_type' => SORT_DESC],
          ];

          $this->load($params);

          if (!$this->validate()) {
          // uncomment the following line if you do not want to return any records when validation fails
          // $query->where('0=1');
          return $dataProvider;
          }
          return $dataProvider; */
    }

}
