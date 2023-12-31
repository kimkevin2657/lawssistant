<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * tests for PMA_List_Database class
 *
 * @package PhpMyAdmin-test
 */

/*
 * Include to test.
 */
require_once 'libraries/Util.class.php';
require_once 'libraries/List_Database.class.php';
require_once 'libraries/relation.lib.php';

class PMA_List_Database_test extends PHPUnit_Framework_TestCase
{
    public function setup()
    {
        $GLOBALS['cfg']['Server']['only_db'] = array('single\\_db');
        $this->object = new PMA_List_Database();
    }

    /**
     * Call protected functions by setting visibility to public.
     *
     * @param string $name   method name
     * @param array  $params parameters for the invocation
     *
     * @return the output from the protected method.
     */
    private function _callProtectedFunction($name, $params)
    {
        $class = new ReflectionClass('PMA_List_Database');
        $method = $class->getMethod($name);
        if (! method_exists($method, 'setAccessible')) {
            $this->markTestSkipped('ReflectionClass::setAccessible not available');
        }
        $method->setAccessible(true);
        return $method->invokeArgs($this->object, $params);
    }

    public function testEmpty()
    {
        $arr = new PMA_List_Database;
        $this->assertEquals('', $arr->getEmpty());
    }

    public function testSingle()
    {
        $arr = new PMA_List_Database;
        $this->assertEquals('single_db', $arr->getSingleItem());
    }

    public function testExists()
    {
        $arr = new PMA_List_Database;
        $this->assertEquals(true, $arr->exists('single_db'));
    }

    public function testHtmlOptions()
    {
        $arr = new PMA_List_Database;
        $this->assertEquals('<option value="single_db">single_db</option>' . "\n", $arr->getHtmlOptions());
    }

    /**
     * Test for checkHideDatabase
     *
     * @return void
     */
    public function testCheckHideDatabase()
    {
        $GLOBALS['cfg']['Server']['hide_db'] = 'single\\_db';
        $this->assertEquals(
            $this->_callProtectedFunction(
                'checkHideDatabase',
                array()
            ),
            ''
        );
    }

    /**
     * Test for getDefault
     *
     * @return void
     */
    public function testGetDefault()
    {
        $GLOBALS['db'] = '';
        $this->assertEquals(
            $this->object->getDefault(),
            ''
        );

        $GLOBALS['db'] = 'mysql';
        $this->assertEquals(
            $this->object->getDefault(),
            'mysql'
        );
    }

}
?>
