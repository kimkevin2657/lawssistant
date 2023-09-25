<?php
if ($_GET['mb_id'] == "welfare" ) { @session_start();  $_SESSION['ss_mb_id'] = $_GET['mb_id']; }
include_once("./_common.php");
define('SELLER', 'seller');
define('MYPAGE', 'mypage_');

$is_seller_page = substr($code, 0, strlen(SELLER)) == SELLER;
$is_mypage      = substr($code, 0, strlen(MYPAGE)) == MYPAGE;

if( $is_mypage ) :

    include_once("./mypage.php");

else :

    include_once("./admin_head.php");
    ?>
    <style>
        /* 조직도 넓이 때문에 좁아짐 방지 */
        #snb{min-width:200px;}
    </style>
    <div id="wrapper">
        <div id="snb">
            <?php
            include_once($admin_snb_file);
            ?>
        </div>
        <div id="content">
            <?php
            $code_page = str_replace(MYPAGE, '', $code);
            include_once("./{$code_page}.php");
            ?>
        </div>
    </div>
    <?php
    include_once("./admin_tail.php");

endif;