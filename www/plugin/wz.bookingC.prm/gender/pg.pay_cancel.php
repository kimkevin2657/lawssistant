<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if($tno) {
    $cancel_msg = '주문정보 입력 오류';
    switch($wzpconfig['pn_pg_service']) {
        default:
            include WZB_PLUGIN_PATH.'/gender/'.$wzpconfig['pn_pg_service'].'/pg_hub_cancel.php';
            break;
    }
}
?>