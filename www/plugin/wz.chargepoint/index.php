<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if (!$is_member) {
    alert("회원만 서비스이용이 가능합니다.");
}

include_once(G5_PLUGIN_PATH.'/wz.chargepoint/config.php');
include_once(WPOT_PLUGIN_PATH.'/lib/function.lib.php');
add_stylesheet('<link rel="stylesheet" href="'.WPOT_PLUGIN_URL.'/css/style.css?v=210108">', 13);
add_stylesheet('<link rel="stylesheet" href="'.WPOT_PLUGIN_URL.'/css/wetoz_bootstrap.css?v=190906">', 13);
add_javascript('<script type="text/javascript" src="'.WPOT_PLUGIN_URL.'/js/common.js"></script>', 13);

if (isset($_REQUEST['mode'])) {
    $mode = preg_replace('/[^a-z0-9_]/i', '', trim($_REQUEST['mode']));
    $mode = substr($mode, 0, 20);
} else {
    $mode = '';
}
?>
<style>
#aside{display:none;}
</style>
<div class="wetoz">

    <div class="col-md-12">

        <?php
        switch ($mode) {
            case 'result': // 충전결과
                include_once(WPOT_PLUGIN_PATH.'/result.skin.php');
                break;
            case 'orderlist': // 충전내역
                include_once(WPOT_PLUGIN_PATH.'/order.list.skin.php');
                break;
            case 'orderdetail': // 충전상세확인
                include_once(WPOT_PLUGIN_PATH.'/order.view.skin.php');
                break;
            default: // 기본화면
                include_once(WPOT_PLUGIN_PATH.'/request.skin.php');
                break;
        }
        ?>

        <div class="clearfix" style="height:10px;"></div>

    </div>

</div>

<script type="text/javascript">
<!--
    Object.defineProperty(console, '_commandLineAPI', { get : function() { throw '콘솔을 사용할 수 없습니다.' } });
//-->
</script>