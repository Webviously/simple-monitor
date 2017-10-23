# simple-monitor
A simple server monitoring framework, for getting some basic server and application stats.  Our example shows outputting to JSON for a web monitor to pick up (see /web/example.php).

##Installing
Clone this repository, and install using composer:
```
git clone https://github.com/webviously/simple-monitor.git
composer install
```

Or include it in your project using composer:
```
composer require webviously/simple-monitor
```

## Example
An example is located in web/example.php.  Here's a basic run-down of what is going on:

First, we load the composer autoloader, and use the Runner.

```
<?php

// require the composer autoloader
require_once __DIR__ . '/../vendor/autoload.php';

use simplemonitor\Runner;
```

Next, we create an instance of the _Runner_ and add a monitor with a basic config.  This one is checking that replication status of a MySQL database is less than 10 seconds behind the master.

```
$runner = new Runner();
$runner->add( 'ReplicationMonitor',
        [
                'host' => 'localhost',
                'user' => 'root',
                'pass' => 'yourpass',
                'dbname' => 'yourdb',
        ] );
```
Notice the _add()_ method takes a class name (found in the src/monitors/ folder), and an option config array.

Next, let's add a file system monitor, checking for free space:

```
$runner->add( 'FileSystemMonitor', [ 'directory' => '/var/lib/' ] );
```
Finally, we run the Runner:
```
$results = $runner->run();
```
Now we will want to do something with the results:
```
header( 'Content-type:application/json' );
echo json_encode( $results );
```
That's it!  Simple monitoring!

## Monitor States
After running, a monitor should be in one of three states: OK, WARN, or INFO.  These state labels are configurable using the monitor's _failure_status_, _success_status_, and _info_status_ parameters.


## Custom Monitors
You can create new, custom monitors to use in the framework.  Simply base them on the *simplemonitor\monitors\Monitor* class.  All the processing is done within the *run()* method.

From within the run() method, call $this->ok() or $this->fail($msg), depending if your monitor passed or failed, or $this->info($msg) if you're just providing info.

For example:
```
<?php
use simplemonitor\monitors\Monitor;

class MyMontitor extends Monitor
{
    public function run()
    {
        // Test something
        // ...
        $result = false;
        if ( $result )
            $this->ok();
        else
            $this->fail('Something went wrong!'); 
    }
} 
```

An info-only monitor might do something like this:
```
<?php
use simplemonitor\monitors\Monitor;

class MyInfoMontitor extends Monitor
{
    public function run()
    {
        // Get some info....
        $info = "Hello World";
        $this->info($info); 
    }
} 
```

## Contributing
If you build a monitor that you think would be useful to others, feel free to submit it using a pull request.  Thanks!
