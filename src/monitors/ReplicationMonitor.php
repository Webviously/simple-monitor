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
			$slave_io_running = $item["Slave_IO_Running"];
			$seconds_behind_master = $item["Seconds_Behind_Master"];
			$last_io_err_no = $item["Last_IO_Errno"];
			$last_sql_err_no = $item["Last_SQL_Errno"];
		}

		if ( $slave_io_running != 'Yes' )
			$this->fail( 'Slave IO is not running, or not connected.' );
		elseif ( $last_io_err_no != 0 || $last_sql_err_no != 0 )
			$this->fail( 'Slave has an IO or SQL error.' );
		elseif ( $seconds_behind_master > $this->threshold )
			$this->fail( 'Slave is more than ' . $this->threshold . ' seconds behind master.' );
		else
			$this->ok();
	}
}
