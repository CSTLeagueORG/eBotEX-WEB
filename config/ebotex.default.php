<?php

return [
	'anyone_is_admin' => false, // gives rights to add servers and create matches to everyone
	'bot_ip' => '127.0.0.1', // IP of second part of eBotEX
	'bot_port' => '12360', // it's port
	'demo_download' => true, // download matches demos after game&
	'demo_path' => '../../eBotEX-CSGO/demos', // path to folder with demos
	'default_max_round' => 15, // default max rounds in half
	'default_rules' => 'rules', // default config for matches
	'default_overtime_max_round' => 3, // default max rounds in overtime half
	'default_overtime_startmoney' => 10000, // default start money in overtime half
	'default_tac_pause_max' => 4, // default tactical timeout number
	'default_tac_pause_duration' => 30, // default tactical timeout duration
];
