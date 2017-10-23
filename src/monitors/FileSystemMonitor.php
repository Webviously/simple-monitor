<?php

namespace simplemonitor\monitors;

class FileSystemMonitor extends Monitor
{
	public $threshold = 50;
	public $directory = '.';

	public function run()
	{
		$total = disk_total_space( $this->directory );
		$free = disk_free_space( $this->directory );
		$percent_free = $free/$total*100;

		if ( $percent_free < $this->threshold )
			$this->fail( 'Disk space critical (' . number_format($percent_free,2) . '%)' );
	}
}
	

