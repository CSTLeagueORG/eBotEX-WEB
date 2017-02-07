<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Matches;

/**
 * MatchesSearch represents the model behind the search form about `app\models\Matches`.
 */
class MatchesSearch extends Matches {
	/**
	 * @inheritdoc
	 */
	public function rules () {
		return [
			[
				[
					'id',
					'server_id',
					'season_id',
					'team_a',
					'team_b',
					'status',
					'is_paused',
					'score_a',
					'score_b',
					'max_round',
					'tac_pause_max',
					'tac_pause_duration',
					'overtime_startmoney',
					'overtime_max_round',
					'config_full_score',
					'config_ot',
					'config_streamer',
					'config_knife_round',
					'config_switch_auto',
					'config_auto_change_password',
					'config_heatmap',
					'enable',
					'ingame_enable',
					'current_map',
					'force_zoom_match',
					'auto_start',
					'auto_start_time',
				],
				'integer',
			], [
				[
					'ip',
					'team_a_flag',
					'team_a_name',
					'team_b_flag',
					'team_b_name',
					'rules',
					'config_password',
					'config_authkey',
					'map_selection_mode',
					'identifier_id',
					'startdate',
					'created_at',
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
		$query = Matches::find()->orderBy(['enable' => SORT_DESC, 'startdate' => SORT_DESC]);

// add conditions that should always apply here

		$dataProvider = new ActiveDataProvider([
			'query' => $query,
		]);

		$status = null;
		if(is_array($this->status)) {
			$status = $this->status;
		}

		$this->load($params);

		if(is_array($status) and count($status) == 2) $query->andFilterWhere([$status[0], 'status', $status[1]]);

		if(!$this->validate()) {
			return $dataProvider;
		}

// grid filtering conditions
		$query->andFilterWhere([
			'id'                          => $this->id,
			'server_id'                   => $this->server_id,
			'season_id'                   => $this->season_id,
			'team_a'                      => $this->team_a,
			'team_b'                      => $this->team_b,
			'status'                      => $this->status,
			'is_paused'                   => $this->is_paused,
			'score_a'                     => $this->score_a,
			'score_b'                     => $this->score_b,
			'max_round'                   => $this->max_round,
			'tac_pause_max'               => $this->tac_pause_max,
			'tac_pause_duration'          => $this->tac_pause_duration,
			'overtime_startmoney'         => $this->overtime_startmoney,
			'overtime_max_round'          => $this->overtime_max_round,
			'config_full_score'           => $this->config_full_score,
			'config_ot'                   => $this->config_ot,
			'config_streamer'             => $this->config_streamer,
			'config_knife_round'          => $this->config_knife_round,
			'config_switch_auto'          => $this->config_switch_auto,
			'config_auto_change_password' => $this->config_auto_change_password,
			'config_heatmap'              => $this->config_heatmap,
			'enable'                      => $this->enable,
			'ingame_enable'               => $this->ingame_enable,
			'current_map'                 => $this->current_map,
			'force_zoom_match'            => $this->force_zoom_match,
			'startdate'                   => $this->startdate,
			'auto_start'                  => $this->auto_start,
			'auto_start_time'             => $this->auto_start_time,
			'created_at'                  => $this->created_at,
			'updated_at'                  => $this->updated_at,
		]);

		$query->andFilterWhere(['like', 'ip', $this->ip])
			->andFilterWhere(['like', 'team_a_flag', $this->team_a_flag])
			->andFilterWhere(['like', 'team_a_name', $this->team_a_name])
			->andFilterWhere(['like', 'team_b_flag', $this->team_b_flag])
			->andFilterWhere(['like', 'team_b_name', $this->team_b_name])
			->andFilterWhere(['like', 'rules', $this->rules])
			->andFilterWhere(['like', 'config_password', $this->config_password])
			->andFilterWhere(['like', 'config_authkey', $this->config_authkey])
			->andFilterWhere(['like', 'map_selection_mode', $this->map_selection_mode])
			->andFilterWhere(['like', 'identifier_id', $this->identifier_id]);

		return $dataProvider;
	}
}
