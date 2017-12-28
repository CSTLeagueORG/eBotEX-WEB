<?php

	namespace app\models\Matches;

	use Yii;
	use yii\base\Model;
	use app\models\Servers\Servers;
	use app\models\Seasons\Seasons;
	use app\models\Teams\Teams;
	use yii\base\Theme;

	class MatchesForm extends Model {
		public $season;
		public $team_a;
		public $team_b;
		public $team_a_name;
		public $team_b_name;
		public $team_a_flag;
		public $team_b_flag;
		public $rules;
		public $password;
		public $max_rounds;
		public $play_all_rounds;
		public $streamer_ready;
		public $knife_round;
		public $overtime;
		public $overtime_start_money;
		public $overtime_max_rounds;
		public $tac_pause;
		public $tac_pause_max;
		public $tac_pause_duration;
		public $autostart;
		public $startdate;
		public $start_before_startdate;
		public $map_selection_mode;
		public $maps;
		public $server;
		public $first_side;

		public function __construct (array $config = []) {
			$this->password = $this->randString(15);

			$this->rules = Yii::$app->params['default_rules'];
			$this->max_rounds = Yii::$app->params['default_max_round'];
			$this->map_selection_mode = 1;

			$this->overtime_max_rounds = Yii::$app->params['default_overtime_max_round'];
			$this->overtime_start_money = Yii::$app->params['default_overtime_startmoney'];

			$this->tac_pause_max = Yii::$app->params['default_tac_pause_max'];
			$this->tac_pause_duration = Yii::$app->params['default_tac_pause_duration'];

			parent::__construct($config);
		}

		public function getSeasons () {
			$seasons = Seasons::find()->where(['active' => '1'])->all();
			$seasonsArray = [];
			foreach($seasons as $season) {
				$seasonsArray[$season->id] = $season->name;
			}
			return $seasonsArray;
		}

		public function getTeams () {
			$teams = Teams::find()->all();
			$teamsArray = [];
			foreach($teams as $team) {
				$teamsArray[$team->id] = $team->name . ' (' . $team->flag . ')';
			}
			return $teamsArray;
		}

		public function getServers () {
			$servers = Servers::find()->all();
			$serversArray = ['rand' => 'Random'];
			foreach($servers as $server) {
				$serversArray[$server->id] = $server->hostname . ' - ' . $server->ip;
			}
			return $serversArray;
		}

		public function getSides () {
			return [
				'random' => 'Random',
				'ct'     => 'Team A CT / Team B T',
				't'      => 'Team A T / Team B CT',
			];
		}

		public function getMaxRounds () {
			return [
				'15' => 'MR15',
				'12' => 'MR12',
				'9'  => 'MR9',
				'5'  => 'MR5',
				'3'  => 'MR3',
			];
		}

		public function getMapSelectionModes () {
			return [
				1 => 'BO1',
				2 => 'BO2',
				3 => 'BO3',
				5 => 'BO5',
			];
		}

		public function getMaps () {
			$maps = Yii::$app->params['maps'];
			$mapsArray = ['tba' => "Choose by mapveto"];
			foreach($maps as $map) {
				$mapsArray[$map] = $map;
			}
			return $mapsArray;
		}

		public static function randString ($pass_len = 50) {
			$allchars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
			$string = "";
			mt_srand((double) microtime() * 1000000);
			for($i = 0; $i < $pass_len; $i++) {
				$string .= $allchars{mt_rand(0, strlen($allchars) - 1)};
			}
			return $string;
		}

		function createMatch () {
			if(!$this->validate()) {
				return false;
			}
			if($this->server == 'rand') {
				$this->server = Servers::find()->orderBy('RAND()')->one()->id;
			}

			$match = new Matches();
			$match->season_id = $this->season;
			$match->team_a = $this->team_a;
			$match->team_a_flag = $this->team_a_flag;
			$match->team_a_name = $this->team_a_name;
			$match->team_b = $this->team_b;
			$match->team_b_flag = $this->team_b_flag;
			$match->team_b_name = $this->team_b_name;
			$match->rules = $this->rules;
			$match->config_password = $this->password;
			$match->max_round = $this->max_rounds;
			$match->map_selection_mode = ($this->map_selection_mode == 1)? 'normal' : 'bo' . $this->map_selection_mode;
			$match->server_id = $this->server;
			$match->config_full_score = $this->play_all_rounds;
			$match->config_streamer = $this->streamer_ready;
			$match->config_knife_round = $this->knife_round;
			$match->config_ot = $this->overtime;
			$match->overtime_max_round = $this->overtime_max_rounds;
			$match->overtime_startmoney = $this->overtime_start_money;
			$match->tac_pause = $this->tac_pause;
			$match->tac_pause_max = $this->tac_pause_max;
			$match->tac_pause_duration = $this->tac_pause_duration;
			$match->auto_start = $this->autostart;
			$match->auto_start_time = $this->start_before_startdate;
			$match->startdate = $this->startdate;
			$match->config_authkey = $this->randString(127);
			$match->status = 0;
			$match->created_at = date('Y-m-d H:i:s');
			$match->updated_at = date('Y-m-d H:i:s');
			$match->score_a = 0;
			$match->score_b = 0;
			$match->enable = 0;
			$match->insert();
			$match->ip = $match->server->ip;
			if($match->teamA) {
				$match->team_a_name = $match->teamA->name;
				$match->team_a_flag = $match->teamA->flag;
			}
			if($match->teamB) {
				$match->team_b_name = $match->teamB->name;
				$match->team_b_flag = $match->teamB->flag;
			}
			$match->updated_at = date('Y-m-d H:i:s');
			$match->save();
			for($i = 0; $i < $this->map_selection_mode; $i++) {
				$map = new Maps();
				$map->map_name = (isset($this->maps[$i]))? $this->maps[$i] : 'tba';
				$map->score_1 = 0;
				$map->score_2 = 0;
				$map->current_side = $this->first_side;
				$map->status = 0;
				$map->nb_ot = 0;
				$map->maps_for = 'dafault';
				$map->match_id = $match->id;
				$map->created_at = date('Y-m-d H:i:s');
				$map->updated_at = date('Y-m-d H:i:s');
				$map->save();
				if($i == 0) {
					$match->current_map = $map->id;
					$match->updated_at = date('Y-m-d H:i:s');
					$match->save();
				}
			}
//		$match->ma = $this->maps;
//		$match->side = $this->first_side;
			return true;
		}

		function updateMatch ($match) {
			/** @var $match Matches */
			if(!$this->validate()) {
				return false;
			}
			if($this->server == 'rand') {
				$this->server = Servers::find()->orderBy('RAND()')->one()->id;
			}

			$match->season_id = $this->season;
			$match->team_a = $this->team_a;
			$match->team_a_flag = $this->team_a_flag;
			$match->team_a_name = $this->team_a_name;
			$match->team_b = $this->team_b;
			$match->team_b_flag = $this->team_b_flag;
			$match->team_b_name = $this->team_b_name;
			$match->rules = $this->rules;
			$match->config_password = $this->password;
			$match->max_round = $this->max_rounds;
			$match->map_selection_mode = ($this->map_selection_mode == 1)? 'normal' : 'bo' . $this->map_selection_mode;
			$match->server_id = $this->server;
			$match->config_full_score = $this->play_all_rounds;
			$match->config_streamer = $this->streamer_ready;
			$match->config_knife_round = $this->knife_round;
			$match->config_ot = $this->overtime;
			$match->overtime_max_round = $this->overtime_max_rounds;
			$match->overtime_startmoney = $this->overtime_start_money;
			$match->tac_pause = $this->tac_pause;
			$match->tac_pause_max = $this->tac_pause_max;
			$match->tac_pause_duration = $this->tac_pause_duration;
			$match->auto_start = $this->autostart;
			$match->auto_start_time = $this->start_before_startdate;
			$match->startdate = $this->startdate;
			$match->updated_at = date('Y-m-d H:i:s');
			$match->save();
			$match->ip = $match->server->ip;
			if($match->teamA) {
				$match->team_a_name = $match->teamA->name;
				$match->team_a_flag = $match->teamA->flag;
			}
			if($match->teamB) {
				$match->team_b_name = $match->teamB->name;
				$match->team_b_flag = $match->teamB->flag;
			}
			$match->updated_at = date('Y-m-d H:i:s');
			$match->save();
			for($i = 0; $i < $this->map_selection_mode; $i++) {
				$map = isset($match->maps[$i])? $match->maps[$i] : new Maps();
				$map->map_name = (isset($this->maps[$i]))? $this->maps[$i] : 'tba';
				$map->score_1 = 0;
				$map->score_2 = 0;
				$map->current_side = $this->first_side;
				$map->status = 0;
				$map->nb_ot = 0;
				$map->maps_for = 'dafault';
				$map->updated_at = date('Y-m-d H:i:s');
				if(!isset($map->id)) {
					$map->match_id = $match->id;
					$map->created_at = date('Y-m-d H:i:s');
					$map->save();
				} else {
					$map->save();
				}
				if($i == 0) {
					$match->current_map = $map->id;
					$match->updated_at = date('Y-m-d H:i:s');
					$match->save();
				}
			}
			for($i = count($match->maps) - 1; $i >= $this->map_selection_mode; $i--) {
				$map = $match->maps[$i];
				$map->delete();
			}
			return true;
		}

		function loadMatch ($match) {
			/** @var $match Matches */
			if($this->server == 'rand') {
				$this->server = Servers::find()->orderBy('RAND()')->one()->id;
			}

			$this->season = $match->season_id;
			$this->team_a = $match->team_a;
			$this->team_a_flag = $match->team_a_flag;
			$this->team_a_name = $match->team_a_name;
			$this->team_b = $match->team_b;
			$this->team_b_flag = $match->team_b_flag;
			$this->team_b_name = $match->team_b_name;
			$this->rules = $match->rules;
			$this->password = $match->config_password;
			$this->max_rounds = $match->max_round;
			$msm = str_replace('bo', '', $match->map_selection_mode);
			$this->map_selection_mode = ((int) $msm)? $msm : 1;
			$this->server = $match->server_id;
			$this->play_all_rounds = $match->config_full_score;
			$this->streamer_ready = $match->config_streamer;
			$this->knife_round = $match->config_knife_round;
			$this->overtime = $match->config_ot;
			$this->overtime_max_rounds = $match->overtime_max_round;
			$this->overtime_start_money = $match->overtime_startmoney;
			$this->tac_pause = $match->tac_pause;
			$this->tac_pause_max = $match->tac_pause_max;
			$this->tac_pause_duration = $match->tac_pause_duration;
			$this->autostart = $match->auto_start;
			$this->start_before_startdate = $match->auto_start_time;
			$this->startdate = $match->startdate;
			foreach($match->maps as $map) {
				$this->maps[] = $map->map_name;
			}
			return true;
		}

		public function rules () {
			return [
				[
					[
						'season',
						'max_rounds',
						'map_selection_mode',
						'maps',
						'server',
						'first_side',
						'play_all_rounds',
						'streamer_ready',
						'knife_round',
						'overtime',
						'tac_pause',
						'autostart',
						'overtime_start_money',
						'overtime_max_rounds',
						'tac_pause_max',
						'tac_pause_duration',
						'start_before_startdate',
					], 'default',
				],
				[['max_rounds', 'map_selection_mode', 'maps', 'server', 'first_side'], 'required'],
				[['team_a'], 'in', 'range' => array_keys($this->getTeams())],
				[['team_b'], 'in', 'range' => array_keys($this->getTeams())],
				[['first_side'], 'in', 'range' => array_keys($this->getSides())],
				[['max_rounds'], 'in', 'range' => array_keys($this->getMaxRounds())],
				[['overtime_max_rounds'], 'in', 'range' => array_keys($this->getMaxRounds())],
				[['team_a_flag'], 'in', 'range' => array_keys(Yii::$app->params['countries'])],
				[['team_b_flag'], 'in', 'range' => array_keys(Yii::$app->params['countries'])],
				['team_a', 'compare', 'compareAttribute' => 'team_b', 'operator' => '!='],
				['team_b', 'compare', 'compareAttribute' => 'team_a', 'operator' => '!='],
				[['team_a_name', 'team_b_name'], 'string', 'max' => 25],
				[['rules'], 'string', 'max' => 200],
				[['password'], 'string', 'max' => 50],
				[['season'], 'in', 'range' => array_keys($this->getSeasons())],
				[['server'], 'in', 'range' => array_keys($this->getServers())],
				[['maps'], 'in', 'allowArray' => true, 'range' => array_keys($this->getMaps())],
				[['map_selection_mode'], 'in', 'range' => array_keys($this->getMapSelectionModes())],
				[['play_all_rounds', 'streamer_ready', 'knife_round', 'overtime', 'tac_pause', 'autostart'], 'boolean'],
				[
					[
						'max_rounds', 'overtime_start_money', 'overtime_max_rounds', 'tac_pause_max',
						'tac_pause_duration', 'start_before_startdate', 'map_selection_mode',
					], 'number',
				],
			];
		}
	}