<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * Tests for PMA_isValid() from libraries/core.lib.php
 *
 * @package PhpMyAdmin-test
 */

/*
 * Include to test.
 */
require_once 'libraries/core.lib.php';

class PMA_isValid_test extends PHPUnit_Framework_TestCase
{
    public static function providerNoVarTypeProvider()
    {
        return array(
            array(0, false, 0),
            array(0, false, 1),
            array(1, false, null),
            array(1.1, false, null),
            array('', false, null),
            array(' ', false, null),
            array('0', false, null),
            array('string', false, null),
            array(array(), false, null),
            array(array(1, 2, 3), false, null),
            array(true, false, null),
            array(false, false, null));
    }

    /**
     *
     * @param mixed $var
     * @param mixed $type
     * @param mixed $compare
     *
     * @dataProvider providerNoVarTypeProvider
     */
    public function testNoVarType($var, $type, $compare)
    {
        $this->assertTrue(PMA_isValid($var, $type, $compare));
    }

    public function testVarNotSetAfterTest()
    {
        PMA_isValid($var);
        $this->assertFalse(isset($var));
    }

    public function testNotSet()
    {
        $this->assertFalse(PMA_isValid($var));
    }

    public function testEmptyString()
    {
        $var = '';
        $this->assertFalse(PMA_isValid($var));
    }

    public function testNotEmptyString()
    {
        $var = '0';
        $this->assertTrue(PMA_isValid($var));
    }

    public function testZero()
    {
        $var = 0;
        $this->assertTrue(PMA_isValid($var));
        $this->assertTrue(PMA_isValid($var, 'int'));
    }

    public function testNullFail()
    {
        $var = null;
        $this->assertFalse(PMA_isValid($var));

        $var = 'null_text';
        $this->assertFalse(PMA_isValid($var, 'null'));
    }

    public function testNotSetArray()
    {
        /** @var $array undefined array */
        $this->assertFalse(PMA_isValid($array['x']));
    }

    public function testScalarString()
    {
        $var = 'string';
        $this->assertTrue(PMA_isValid($var, 'len'));
        $this->assertTrue(PMA_isValid($var, 'scalar'));
        $this->assertTrue(PMA_isValid($var));
    }

    public function testScalarInt()
    {
        $var = 1;
        $this->assertTrue(PMA_isValid($var, 'int'));
        $this->assertTrue(PMA_isValid($var, 'scalar'));
    }

    public function testScalarFloat()
    {
        $var = 1.1;
        $this->assertTrue(PMA_isValid($var, 'float'));
        $this->assertTrue(PMA_isValid($var, 'double'));
        $this->assertTrue(PMA_isValid($var, 'scalar'));
    }

    public function testScalarBool()
    {
        $var = true;
        $this->assertTrue(PMA_isValid($var, 'scalar'));
        $this->assertTrue(PMA_isValid($var, 'bool'));
        $this->assertTrue(PMA_isValid($var, 'boolean'));
    }

    public function testNotScalarArray()
    {
        $var = array('test');
        $this->assertFalse(PMA_isValid($var, 'scalar'));
    }

    public function testNotScalarNull()
    {
        $var = null;
        $this->assertFalse(PMA_isValid($var, 'scalar'));
    }

    public function testNumericInt()
    {
        $var = 1;
        $this->assertTrue(PMA_isValid($var, 'numeric'));
    }

    public function testNumericFloat()
    {
        $var = 1.1;
        $this->assertTrue(PMA_isValid($var, 'numeric'));
    }

    public function testNumericZero()
    {
        $var = 0;
        $this->assertTrue(PMA_isValid($var, 'numeric'));
    }

    public function testNumericString()
    {
        $var = '+0.1';
        $this->assertTrue(PMA_isValid($var, 'numeric'));
    }

    public function testValueInArray()
    {
        $var = 'a';
        $this->assertTrue(PMA_isValid($var, array('a', 'b',)));
    }

    public function testValueNotInArray()
    {
        $var = 'c';
        $this->assertFalse(PMA_isValid($var, array('a', 'b',)));
    }

    public function testNumericIdentical()
    {
        $var = 1;
        $compare = 1;
        $this->assertTrue(PMA_isValid($var, 'identic', $compare));

        $var = 1;
        $compare += 2;
        $this->assertFalse(PMA_isValid($var, 'identic', $compare));

        $var = 1;
        $compare = '1';
        $this->assertFalse(PMA_isValid($var, 'identic', $compare));
    }

    public function providerSimilarType()
    {
        return array(
            array(1, 1),
            array(1.5, 1.5),
            array(true, true),
            array('string', "string"),
            array(array(1, 2, 3.4), array(1, 2, 3.4)),
            array(array(1, '2', '3.4', 5, 'text'), array('1', '2', 3.4,'5'))
        );
    }

    /**
     *
     * @param mixed $var
     * @param mixed $compare
     *
     * @dataProvider providerSimilarType
     */
    public function testSimilarType($var, $compare)
    {
        $this->assertTrue(PMA_isValid($var, 'similar', $compare));
        $this->assertTrue(PMA_isValid($var, 'equal', $compare));
        $this->assertTrue(PMA_isValid($compare, 'similar', $var));
        $this->assertTrue(PMA_isValid($compare, 'equal', $var));

    }

    public function testOtherTypes()
    {
        $var = new PMA_isValid_test();
        $this->assertFalse(PMA_isValid($var, 'class'));
    }

}

?>