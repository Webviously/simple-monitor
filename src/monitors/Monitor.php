<?php

namespace simplemonitor\monitors;

abstract class Monitor 
{
	public function __construct($config=[])
	{
		foreach ( $config as $property => $value )
		{
			if ( property_exists( $this, $property ) )
				$this->{$property} = $value;
		}
		$this->init();
		if ( ! $this->id )
		{
			$this->id = get_class( $this );
		}
	}
	public function ok()
	{
		$this->status = $this->success_status;
		$this->message = '';
	}
	public function fail($msg)
	{
		$this->status = $this->failure_status;
		$this->message = $msg;
	}
	public function export()
	{
		return [
			'id' => $this->id,
			'status' => $this->status,
			'message' => $this->message,
		];
	}
	public function init() {}
	abstract public function run();
	public $id;
	public $threshold;
	public $status;
	public $message;
	public $failure_status = 'WARN';
	public $success_status = 'OK';
}
