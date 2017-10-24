<?php

namespace simplemonitor\monitors;

class CpuMonitor extends Monitor
{
	// Total utilization ( 5-min load avg / cores )
	public $threshold = 0.70;

	public function run()
	{
		$info = \ezcSystemInfo::getInstance();
		$load = sys_getloadavg();
		$real_load = $load[1] / $info->cpuCount;
		
		if ( $real_load > $this->threshold )
			$this->fail( 'CPU Utilization Critical' );
		else
			$this->ok();
	}
}
	

