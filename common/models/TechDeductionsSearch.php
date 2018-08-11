<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\TechDeductions;

/**
 * TechDeductionsSearch represents the model behind the search form of `common\models\TechDeductions`.
 */
class TechDeductionsSearch extends TechDeductions
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'integer'],
            [['qty', 'total'], 'number'],
            [['created_at', 'updated_at', 'deleted_at', 'description', 'deduction_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
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
    public function search($params)
    {
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
}
