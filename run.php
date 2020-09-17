<?php

require_once 'AddClass.php';

//// Run
$test = new AddClass($argv[1]);
if($test->getError()) {
    exit('Can\'t open a file!');
}
$test->start();
