<?php

use PHPUnit\Framework\TestCase;

require_once 'AddClass.php';

class TestAddClass extends TestCase
{
    protected $ac,
        $line = '{"bin":"45717360","amount":"100.00","currency":"EUR"}';

    public function setUp(): void
    {
        $this->ac = new AddClass('input.txt');
    }

    public function testIsEU()
    {
        $this->assertEquals(true, $this->ac->isEU('DK'));
        $this->assertEquals(false, $this->ac->isEU('US'));
    }

    public function testGetData()
    {
        $this->assertEquals(1.0, $this->ac->getData($this->line));
    }
}
