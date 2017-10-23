<?php

require_once __DIR__ . '/../vendor/autoload.php';

use simplemonitor\Runner;

$runner = new Runner();
$runner->add( 'ReplicationMonitor',
	[
		'host' => 'localhost',
		'user' => 'root',
		'pass' => 'yourpass',
		'dbname' => 'yourdb',
	] );

$runner->add( 'FileSystemMonitor', [ 'directory' => '/var/lib/' ] );
$results = $runner->run();

header( 'Content-type:application/json' );
echo json_encode( $results );
