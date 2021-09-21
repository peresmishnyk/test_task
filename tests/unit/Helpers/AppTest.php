<?php
namespace Helpers;

class AppTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;
    
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testDefinition()
    {
        $this->assertEquals(DIRECTORY_SEPARATOR, DS);
        $this->assertTrue(function_exists('app_path'));
        $this->assertTrue(function_exists('config_path'));
        $this->assertTrue(function_exists('config'));
        $this->assertTrue(function_exists('app'));
    }

    public function testPathAndFiles(){
        $this->assertDirectoryExists(app_path());
        $this->assertDirectoryExists(config_path());
        $this->assertFileExists(config_path('app'));
    }

}