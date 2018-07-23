<?php

namespace common\models;

use common\models\Billing;
use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * BillingSearch represents the model behind the search form of `common\models\Billing`.
 */
class BillingSearch extends Billing {

    public $started_at;
    public $ended_at;
    public $keyword;
    public $pageSize,$price;
    public $vendor;
    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'user_id', 'created_at', 'updated_at', 'created_by', 'updated_by', 'deleted_at'], 'integer'],
            [['type', 'wo_complete_date', 'work_order', 'techid', 'work_code', 'date' ,'started_at', 'ended_at','keyword','vendor'], 'safe'],
            [['total'], 'number'],
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
        $query = Billing::find()->joinWith(['techProfileInfo','vendorInfo']);
        $query->where("billing.deleted_at =0");
         $this->load($params);
        // add conditions that should always apply here
        if ($this->started_at != "") {
            if ($this->ended_at == "") {
                $this->ended_at = date('Y-m-d');
            }
            $query->andWhere('DATE_FORMAT(billing.wo_complete_date ,"%Y-%m-%d") >= "' . date('Y-m-d',strtotime($this->started_at)). '" AND DATE_FORMAT(billing.wo_complete_date,"%Y-%m-%d") <= "' .date('Y-m-d',strtotime($this->ended_at)) . '"');
            $datamod['BillingSearch']['started_at'] =date('Y-m-d',strtotime($this->started_at));
            $datamod['BillingSearch']['ended_at'] =date('Y-m-d',strtotime($this->ended_at));
        }else{
             $staticstart = date('Y-m-d',strtotime('last Sunday'));  
              $staticfinish = date('Y-m-d',strtotime('next Saturday'));
                    $query->andWhere('DATE_FORMAT(billing.wo_complete_date ,"%Y-%m-%d") >= "' . $staticstart. '" AND DATE_FORMAT(billing.wo_complete_date,"%Y-%m-%d") <= "' .$staticfinish . '"');
            $datamod['BillingSearch']['started_at'] =$staticstart;
            $datamod['BillingSearch']['ended_at'] =$staticfinish;
    
        }
        
          if ($this->keyword != "") {
               $query->andWhere([
                        'or',
                       ['like', 'billing.work_code', $this->keyword],
                       ['like', 'billing.work_order', $this->keyword],
                       ['like', 'billing.techid', $this->keyword],
                       ]);
        }
        if ($this->type != "") {
            $query->andWhere( ['billing.type' => $this->type]);  
            
        }
        if($this->vendor!='')
        {
            $query->andWhere(['vendor.id'=> $this->vendor]);
        }
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
             'sort' => ['defaultOrder' => ['wo_complete_date' => 'DESC']],
            'pagination' => [
                'pageSize' => $this->pageSize,//50,
//                'params' => $datamod
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'wo_complete_date' => $this->wo_complete_date,
            'total' => $this->total,
            'date' => $this->date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
                ->andFilterWhere(['like', 'work_order', $this->work_order])
                ->andFilterWhere(['like', 'techid', $this->techid])
                ->andFilterWhere(['like', 'work_code', $this->work_code]);

        return $dataProvider;
    }
     public function techsearch($params) {
        $query = Billing::find();
        $query->where("deleted_at =0");
        $query->andWhere(["user_id"=>Yii::$app->user->id]);
         $this->load($params);
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
        if ($this->type != "") {
            $query->andWhere( ['type' => $this->type]);  
            
        }
        
          if ($this->keyword != "") {
               $query->andWhere([
                        'or',
                       ['like', 'work_code', $this->keyword],
                       ['like', 'work_order', $this->keyword],
                       ]);
        }
      
        
        
               
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['wo_complete_date' => 'DESC']],
            'pagination' => [
                
                'pageSize' =>  $this->pageSize,//50,
//                'params' => $datamod
            ],
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'user_id' => $this->user_id,
            'wo_complete_date' => $this->wo_complete_date,
            'total' => $this->total,
            'date' => $this->date,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'created_by' => $this->created_by,
            'updated_by' => $this->updated_by,
            'deleted_at' => $this->deleted_at,
        ]);

        $query->andFilterWhere(['like', 'type', $this->type])
                ->andFilterWhere(['like', 'work_order', $this->work_order])
                ->andFilterWhere(['like', 'techid', $this->techid])
                ->andFilterWhere(['like', 'work_code', $this->work_code]);

        return $dataProvider;
    }
    
    public function techOverviewSearch($params) {
        $query = Billing::find()->joinWith(['user','techOfficial']);
        if($this->location!='' || $this->vendor!=''){
            $query->joinWith('techProfile');
        }
        $query->select(['billing.user_id','user.username','billing.techid','count(billing.id) as jobs','SUM(ROUND((billing.total),2)) AS price','SUM(ROUND(((billing.total)*(tech_official.rate_percent)/100),2)) AS total_dAmt','tech_official.rate_code_val', 'tech_official.rate_code_type']);
        $query->where("deleted_at =0");
         $this->load($params);
         if($this->location!='')
         {
             $query->andWhere(['tech_profile.location_id'=>$this->location]);
         }
         if($this->vendor!='')
         {
             $query->andWhere(['tech_profile.vendor_id'=>$this->vendor]);
         }
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

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }
        return $dataProvider;
    }

}
