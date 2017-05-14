<?php

	namespace app\models\Stats;

	use Yii;
	use yii\base\Model;
	use yii\data\ActiveDataProvider;
	use app\models\Stats\Players;

	/**
	 * PlayersSearch represents the model behind the search form about `app\models\Players`.
	 */
	class PlayersSearch extends Players {
		/**
		 * @inheritdoc
		 */
		public function rules () {
			return [
				[
					[
						'id', 'match_id', 'map_id', 'nb_kill', 'assist', 'death', 'point', 'hs', 'defuse', 'bombe',
						'tk',
						'nb1', 'nb2', 'nb3', 'nb4', 'nb5', 'nb1kill', 'nb2kill', 'nb3kill', 'nb4kill', 'nb5kill',
						'pluskill', 'firstkill',
					], 'integer',
				],
				[
					[
						'player_key', 'team', 'ip', 'steamid', 'first_side', 'current_side', 'pseudo', 'created_at',
						'updated_at',
					], 'safe',
				],
			];
		}

		/**
		 * @inheritdoc
		 */
		public function scenarios () {
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
		public function search ($params) {
			$query = Players::find();

// add conditions that should always apply here

			$dataProvider = new ActiveDataProvider([
				'query' => $query,
			]);

			$this->load($params);

			if(!$this->validate()) {
// uncomment the following line if you do not want to return any records when validation fails
// $query->where('0=1');
				return $dataProvider;
			}

// grid filtering conditions
			$query->andFilterWhere([
				'id'         => $this->id,
				'match_id'   => $this->match_id,
				'map_id'     => $this->map_id,
				'nb_kill'    => $this->nb_kill,
				'assist'     => $this->assist,
				'death'      => $this->death,
				'point'      => $this->point,
				'hs'         => $this->hs,
				'defuse'     => $this->defuse,
				'bombe'      => $this->bombe,
				'tk'         => $this->tk,
				'nb1'        => $this->nb1,
				'nb2'        => $this->nb2,
				'nb3'        => $this->nb3,
				'nb4'        => $this->nb4,
				'nb5'        => $this->nb5,
				'nb1kill'    => $this->nb1kill,
				'nb2kill'    => $this->nb2kill,
				'nb3kill'    => $this->nb3kill,
				'nb4kill'    => $this->nb4kill,
				'nb5kill'    => $this->nb5kill,
				'pluskill'   => $this->pluskill,
				'firstkill'  => $this->firstkill,
				'created_at' => $this->created_at,
				'updated_at' => $this->updated_at,
			]);

			$query->andFilterWhere(['like', 'player_key', $this->player_key])
				->andFilterWhere(['like', 'team', $this->team])
				->andFilterWhere(['like', 'ip', $this->ip])
				->andFilterWhere(['like', 'steamid', $this->steamid])
				->andFilterWhere(['like', 'first_side', $this->first_side])
				->andFilterWhere(['like', 'current_side', $this->current_side])
				->andFilterWhere(['like', 'pseudo', $this->pseudo]);

			return $dataProvider;
		}
	}
