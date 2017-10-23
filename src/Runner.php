<?php 
namespace simplemonitor;
use simplemonitor\monitors;

class Runner
{
	public $results = [];
	public $monitors = [];

	public function run()
	{
		$results = [];

		foreach( $this->monitors as $monitor )
		{
			$monitor->run();
			$this->results[] = $monitor->export();
		}

		return $this->results;
	}

	public function add( $class, $config=[])
	{
		$class = 'simplemonitor\\monitors\\' . $class;
		$this->monitors[] = new $class( $config );
	}
}
