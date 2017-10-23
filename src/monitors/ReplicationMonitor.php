<?php

namespace simplemonitor\monitors;

class ReplicationMonitor extends Monitor
{
	public $threshold = 10;

	public $host = 'localhost';
	public $port = '3306';
	public $user;
	public $pass;
	public $database;
	protected $dbconn;

	public $sql = 'SHOW SLAVE STATUS';

	public function init()
	{
		parent::init();
		$this->dbconn = new \Pdo( 
			"mysql:host=" . $this->host . 
		 		";port=" . $this->port . 
				";dbname=" . $this->database,
			$this->user, $this->pass );
	}

	public function run()
	{
		$query = $this->dbconn->query( $this->sql ); 
		$res = $query->fetchall();

		foreach($res as $item){
			$seconds_behind_master = $item["Seconds_Behind_Master"];
		}

		if ( $seconds_behind_master > $this->threshold )
			$this->fail( 'Slave is more than ' . $this->threshold . ' seconds behind master.' );
		else
			$this->ok();
	}
}
