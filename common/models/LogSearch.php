<?php

namespace common\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use common\models\Log;

/**
 * LogSearch represents the model behind the search form of `\common\models\Log`.
 */
class LogSearch extends Log
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'level'], 'integer'],
            [['category', 'prefix', 'message'], 'safe'],
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
    public function search($params, $category = '')
    {
        $query = Log::find();
        if($category != ''){
            $query->where(['category' => $category]);
        }else{
            $query->where(['!=', 'category', 'api']);
        }
        $query->orderBy(['id' => SORT_DESC]);

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
            'id' => $this->id,
            'level' => $this->level,
            'category' => $this->category,
        ]);

        $query->andFilterWhere(['like', 'prefix', $this->prefix])
            ->andFilterWhere(['like', 'message', $this->message]);

        return $dataProvider;
    }
}
