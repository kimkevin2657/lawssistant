<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if($tno) {
    $cancel_msg = '주문정보 입력 오류';
    switch($wzcnf['cf_pg_service']) {
        default:
            include WPOT_PLUGIN_PATH.'/gender/'.$wzcnf['cf_pg_service'].'/pg_hub_cancel.php';
            break;
    }
}
?>