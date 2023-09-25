<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-11-28
 * Time: 03:46
 */

$tb['title'] = "마이페이지";
include_once("./_head.php");

$sql_common = " from shop_coupon_log ";
$sql_search = " where mb_id = '{$member['id']}' ";

// 사용가능한 쿠폰
$sql_search.= " and mb_use='0' and ( ";
$sql_search.= " (cp_inv_type='0' and (cp_inv_edate = '9999999999' or cp_inv_edate > curdate())) ";
$sql_search.= " or (cp_inv_type='1' and date_add(`cp_wdate`, interval `cp_inv_day` day) > now()) ";
$sql_search.= " ) ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$cp_count = $row['cnt'];

$pg_title = $board['boardname'];
Theme::get_theme_part(TB_THEME_PATH,'/aside_my.skin.php');
?>

<!-- 마이페이지 시작 { -->
<div id="con_lf">
    <h2 class="pg_tit">
        <span><?php echo $pg_title; ?></span>
        <p class="pg_nav">HOME<i>&gt;</i>마이페이지<i>&gt;</i><?php echo $pg_title; ?></p>
    </h2>

    <div id="content">
        <?php
        $file = TB_DATA_PATH.'/board/boardimg/'.$board['fileurl1'];
        if(is_file($file) && $board['fileurl1']) {
            $file = rpc($file, TB_PATH, TB_URL);
            echo '<p><img src="'.$file.'"></p>';
        }
?>

