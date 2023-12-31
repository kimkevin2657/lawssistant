<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 ** Test for PMA_Util::checkParameters from Util.class.php
 *
 * @package PhpMyAdmin-test
 * @group common.lib-tests
 */

/*
 * Include to test.
 */
require_once 'libraries/core.lib.php';
require_once 'libraries/Util.class.php';
require_once 'libraries/Theme.class.php';
require_once 'libraries/Config.class.php';
require_once 'libraries/select_lang.lib.php';

class PMA_checkParameters_test extends PHPUnit_Framework_TestCase
{
    function setup()
    {
        $GLOBALS['PMA_Config'] = new PMA_Config();
        $_SESSION['PMA_Theme'] = new PMA_Theme();
        $GLOBALS['cfg'] = array('ServerDefault' => 1);
        $GLOBALS['pmaThemeImage'] = 'theme/';
        $GLOBALS['lang'] = 'en';
        $GLOBALS['text_dir'] = 'ltr';
    }

    function testCheckParameterMissing()
    {
        $GLOBALS['PMA_PHP_SELF'] = PMA_getenv('PHP_SELF');
        $GLOBALS['pmaThemePath'] = $_SESSION['PMA_Theme']->getPath();

        $this->expectOutputRegex("/Missing parameter: field/");

        PMA_Util::checkParameters(
            array('db', 'table', 'field')
        );
    }

    function testCheckParameter()
    {
        $GLOBALS['PMA_PHP_SELF'] = PMA_getenv('PHP_SELF');
        $GLOBALS['pmaThemePath'] = $_SESSION['PMA_Theme']->getPath();
        $GLOBALS['db'] = "dbDatabase";
        $GLOBALS['table'] = "tblTable";
        $GLOBALS['field'] = "test_field";
        $GLOBALS['sql_query'] = "SELECT * FROM tblTable;";

        $this->expectOutputString("");
        PMA_Util::checkParameters(
            array('db', 'table', 'field', 'sql_query')
        );
    }
}
