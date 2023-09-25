<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

include_once(WZB_PLUGIN_PATH.'/gender/lg/config.php');

$LGD_TID = $tno;

$xpay = new XPay($configPath, $CST_PLATFORM);

// Mert Key 설정
$xpay->set_config_value('t'.$LGD_MID, $wzpconfig['pn_pg_site_key']);
$xpay->set_config_value($LGD_MID, $wzpconfig['pn_pg_site_key']);

$xpay->Init_TX($LGD_MID);

$xpay->Set('LGD_TXNAME', 'Cancel');
$xpay->Set('LGD_TID', $LGD_TID);

if ($xpay->TX()) {
    $res_cd = $xpay->Response_Code();
    if($res_cd != '0000' && $res_cd != 'AV11') {
        $pg_res_cd = $res_cd;
        $pg_res_msg = $xpay->Response_Msg();
    }
} else {
    $pg_res_cd = $xpay->Response_Code();
    $pg_res_msg = $xpay->Response_Msg();
}