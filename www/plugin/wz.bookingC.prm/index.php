<?php
if(!defined('_MALLSET_')) exit;
include_once(MS_PLUGIN_PATH.'/wz.bookingC.prm/config.php');
include_once(MS_PLUGIN_PATH.'/wz.bookingC.prm/lib/function.lib.php');
add_stylesheet('<link rel="stylesheet" href="http://fonts.googleapis.com/earlyaccess/nanumgothic.css">', 9);
add_stylesheet('<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Montserrat">', 9);
add_stylesheet('<link rel="stylesheet" href="'.WZB_PLUGIN_URL.'/css/font-awesome.min.css">', 10);
add_stylesheet('<link rel="stylesheet" href="'.WZB_PLUGIN_URL.'/css/wetoz_bootstrap.css?v=181001">', 11);
add_stylesheet('<link rel="stylesheet" href="'.WZB_PLUGIN_URL.'/css/magnific-popup.css?v=180122">', 12);
add_stylesheet('<link rel="stylesheet" href="'.WZB_PLUGIN_URL.'/css/style.css?v=181001">', 13);
add_stylesheet('<link rel="stylesheet" href="'.WZB_PLUGIN_URL.'/css/style_skin.css?v=180122">', 14);
add_javascript('<script type="text/javascript" src="'.WZB_PLUGIN_URL.'/js/jquery.magnific-popup.min.js"></script>', 12);
add_javascript('<script type="text/javascript" src="'.WZB_PLUGIN_URL.'/js/common.js"></script>', 13);

wz_ready_order_cancel(); // 설정된 시간이 지나면 예약대기건은 자동으로 취소처리.

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
<script type="text/javascript"> var cp_code = '<?php echo $cp_code?>'; </script>

<div class="wetoz">
        
    <div class="col-md-12">

        <?php
        switch ($mode) {
            case 'step1': // 객실정보선택
                include_once(WZB_PLUGIN_PATH.'/step.1.skin.php');
                break;
            case 'step2': // 예약자 정보 입력 및 동의
                include_once(WZB_PLUGIN_PATH.'/step.2.skin.php');
                break;
            case 'step3': // 예약결과 
                include_once(WZB_PLUGIN_PATH.'/step.3.skin.php');
                break;
            case 'ordercheck': // 비회원예약검증 
                include_once(WZB_PLUGIN_PATH.'/order.check.skin.php');
                break;
            case 'orderlist': // 예약확인 
                include_once(WZB_PLUGIN_PATH.'/order.list.skin.php');
                break;
            case 'orderdetail': // 예약상세확인 
                include_once(WZB_PLUGIN_PATH.'/order.view.skin.php');
                break;
            case 'info': // 예약안내 
                include_once(WZB_PLUGIN_PATH.'/info.skin.php');
                break;
            default: // 기본화면 : 달력
                include_once(WZB_PLUGIN_PATH.'/step.1.skin.php');
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