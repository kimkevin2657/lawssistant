<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 ** Test for PMA_Util::getMessage from common.lib
 *
 * @package PhpMyAdmin-test
 * @group common.lib-tests
 */

/*
 * Include to test.
 */
require_once 'libraries/Util.class.php';
require_once 'libraries/Table.class.php';
require_once 'libraries/js_escape.lib.php';

class PMA_showMessage_test extends PHPUnit_Framework_TestCase
{
    function setUp()
    {
        global $cfg;
        if (! defined('VERSION_CHECK_DEFAULT')) {
            define('VERSION_CHECK_DEFAULT', 1);
        }

        include 'libraries/config.default.php';
    }

    function testShowMessageNotAjax()
    {
        global $cfg;

        $GLOBALS['is_ajax_request'] = true;
        $cfg['Server']['DisableIS'] = false;
        $GLOBALS['table'] = 'tbl';
        $GLOBALS['db'] = 'db';

        $_SESSION[' PMA_token '] = md5(uniqid(rand(), true));

        $GLOBALS['sql_query'] = "SELECT * FROM tblPatient ";

        $this->expectOutputString(
            "<div id=\"result_query\" align=\"\">
            <div class=\"notice\">msg</div><code class=\"sql\"><span class=\"syntax\"><span class=\"inner_sql\"><a href=\"./url.php?url=http%3A%2F%2Fdev.mysql.com%2Fdoc%2Frefman%2F5.0%2Fen%2Fselect.html&amp;server=server&amp;lang=en&amp;token=647a62ad301bf9025e3b13bc7caa02cb\" target=\"mysql_doc\"><span class=\"syntax_alpha syntax_alpha_reservedWord\">SELECT</span></a>  <span class=\"syntax_punct\">*</span> <br /><span class=\"syntax_alpha syntax_alpha_reservedWord\">FROM</span> <span class=\"syntax_alpha syntax_alpha_identifier\">tblPatient</span></span></span></code><div class=\"tools\"><form action=\"sql.php\" method=\"post\"><input type=\"hidden\" name=\"db\" value=\"db\" /><input type=\"hidden\" name=\"table\" value=\"tbl\" /><input type=\"hidden\" name=\"server\" value=\"server\" /><input type=\"hidden\" name=\"lang\" value=\"en\" /><input type=\"hidden\" name=\"token\" value=\"647a62ad301bf9025e3b13bc7caa02cb\" /><input type=\"hidden\" name=\"sql_query\" value=\"SELECT * FROM tblPatient \" /></form><script type=\"text/javascript\">
            //<![CDATA[
            $('.tools form').last().after('[<a href=\"#\" title=\"Inline edit of this query\" class=\"inline_edit_sql\">Inline</a>]');
            //]]>
            </script> [
            <a href=\"tbl_sql.php?db=db&amp;table=tbl&amp;sql_query=SELECT+%2A+FROM+tblPatient+&amp;show_query=1&amp;server=server&amp;lang=en&amp;token=647a62ad301bf9025e3b13bc7caa02cb#querybox\" onclick=\"PMA_querywindow.focus('SELECT * FROM tblPatient '); return false;\">Edit</a>
            ] [
            <a href=\"import.php?db=db&amp;table=tbl&amp;sql_query=EXPLAIN+SELECT+%2A+FROM+tblPatient+&amp;server=server&amp;lang=en&amp;token=647a62ad301bf9025e3b13bc7caa02cb\" >Explain SQL</a>
            ] [
            <a href=\"import.php?db=db&amp;table=tbl&amp;sql_query=SELECT+%2A+FROM+tblPatient+&amp;show_query=1&amp;show_as_php=1&amp;server=server&amp;lang=en&amp;token=647a62ad301bf9025e3b13bc7caa02cb\" >Create PHP Code</a>
            ] [
            <a href=\"import.php?db=db&amp;table=tbl&amp;sql_query=SELECT+%2A+FROM+tblPatient+&amp;show_query=1&amp;server=server&amp;lang=en&amp;token=647a62ad301bf9025e3b13bc7caa02cb\" >Refresh</a>
            ]</div></div>"
        );

        echo PMA_Util::getMessage("msg");

        //$this->assertEquals("", PMA_Util::getMessage("msg"));
        $this->assertTrue(true);
    }
}
