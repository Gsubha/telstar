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
        ]);
        return $dataProvider;
    }

    public function techDeductionSearch($params) {
        $query = TechDeductions::find()
                ->select([
                    'tech_deductions.*',
                     'instalment_deductions.*',
                    /*'MIN(instalment_deductions.inst_start_date) AS inst_startdate',
                    'MAX(instalment_deductions.inst_end_date) AS inst_enddate',
                    'SUM(instalment_deductions.inst_paid_amt) AS total_amt_paid',
                    '(`tech_deductions`.`total`-(SUM(instalment_deductions.inst_paid_amt))) AS remain_amt'*/
                ])
                ->joinWith(['user', 'instalmentDeductions']);
        $this->load($params);
        if ($this->techid != '') {
            $query->andWhere(['tech_deductions.user_id' => $this->techid]);
        }
        if ($this->startdate != "") {
            if ($this->enddate == "") {
                $this->enddate = date('Y-m-d');
            }
            $query->andWhere("(DATE(`tech_deductions`.`deduction_date`) BETWEEN '" . date("Y-m-d", strtotime($this->startdate)) . "' AND '" . date("Y-m-d", strtotime($this->enddate)) . "')
           OR (DATE(`instalment_deductions`.`inst_start_date`) BETWEEN '" . date("Y-m-d", strtotime($this->startdate)) . "' AND '" . date("Y-m-d", strtotime($this->enddate)) . "') "
                    . "OR (DATE(`instalment_deductions`.`inst_end_date`) BETWEEN '" . date("Y-m-d", strtotime($this->startdate)) . "' AND '" . date("Y-m-d", strtotime($this->enddate)) . "')
          ");
        } else {
            $startdate = date('Y-m-d', strtotime('last Sunday'));
            $enddate = date('Y-m-d', strtotime('next Saturday'));
            $query->andWhere("(DATE(`tech_deductions`.`deduction_date`) BETWEEN '" . $startdate . "' AND '" . $enddate . "')
           OR (DATE(`instalment_deductions`.`inst_start_date`) BETWEEN '" . $startdate . "' AND '" . $enddate . "') "
                    . "OR (DATE(`instalment_deductions`.`inst_end_date`) BETWEEN '" . $startdate . "' AND '" . $enddate . "')
          ");
        }
        //$query->groupBy('tech_deductions.id');
        //echo $query->createCommand()->getRawSql();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => $this->pageSize, //50,
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
