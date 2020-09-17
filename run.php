<?php

class A {
    static public function write()
    {
        echo 'A';
    }

    static public function exe()
    {
        static::write();
    }
}

class B extends A {
    static public function write()
    {
        echo 'B';
    }
}
die;

B::write();

require_once 'AddClass.php';

//// Run
$test = new AddClass($argv[1]);
if($test->getError()) {
    exit('Can\'t open a file!');
}
$test->start();
