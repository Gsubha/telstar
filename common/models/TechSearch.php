<?php

namespace common\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\User;

/**
 * UserSearch represents the model behind the search form of `common\models\User`.
 */
class TechSearch extends User {

    public $pageSize;
    public $keyword;
    public $location;
    public $vendor;

    /**
     * {@inheritdoc}
     */
    public function rules() {
        return [
            [['id', 'is_admin', 'parent_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['username', 'auth_key', 'password_hash', 'password_reset_token', 'email', 'firstname', 'techid', 'keyword', 'location', 'vendor'], 'safe'],
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
        $query = User::find()->joinWith(['locationInfo', 'vendorInfo']);
        $query->where("user.isDeleted =0 AND user.is_admin!=1");
        $this->load($params);
        // add conditions that should always apply here
        // print_r($this);
        if ($this->keyword != "") {
            $query->andWhere([
                'or',
                ['like', 'user.techid', $this->keyword],
                ['like', 'user.username', $this->keyword],
                ['like', 'user.firstname', $this->keyword],
                ['like', 'user.lastname', $this->keyword],
                ['like', 'user.email', $this->keyword],
            ]);
        }
        if ($this->location != "") {
            $query->andWhere( ['location.id' => $this->location]);  
            
        }
        if($this->vendor!='')
        {
            $query->andWhere(['vendor.id'=> $this->vendor]);
        }
        //echo $query->createCommand()->getRawSql();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => ['defaultOrder' => ['created_at' => 'DESC']],
            'pagination' => [
                'pageSize' => $this->pageSize, //50,
//                'params' => $datamod
            ],
        ]);

        $dataProvider->sort->attributes['vendorShortName'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['vendor.vendor_type' => SORT_ASC],
            'desc' => ['vendor.vendor_type' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['location'] = [
            // The tables are the ones our relation are configured to
            // in my case they are prefixed with "tbl_"
            'asc' => ['location.location' => SORT_ASC],
            'desc' => ['location.location' => SORT_DESC],
        ];


        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'user.id' => $this->id,
            'user.is_admin' => $this->is_admin,
            'user.parent_id' => $this->parent_id,
            'user.status' => $this->status,
            'user.created_at' => $this->created_at,
            'user.updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'user.username', $this->username])
                ->andFilterWhere(['like', 'user.auth_key', $this->auth_key])
                ->andFilterWhere(['like', 'user.password_hash', $this->password_hash])
                ->andFilterWhere(['like', 'user.password_reset_token', $this->password_reset_token])
                ->andFilterWhere(['like', 'user.email', $this->email])
                ->andFilterWhere(['like', 'user.firstname', $this->firstname])
                ->andFilterWhere(['like', 'user.techid', $this->techid]);

        return $dataProvider;
    }

}
