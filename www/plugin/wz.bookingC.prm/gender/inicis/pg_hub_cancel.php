<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$cancelFlag = "true";

if($cancelFlag == "true")
{
    include_once(WZB_PLUGIN_PATH.'/gender/inicis/config.php');

    $TID = $tno;
    $inipay->SetField("type", "cancel"); // 고정
    $inipay->SetField("tid", $TID); // 고정
    $inipay->SetField("cancelmsg", "DB FAIL"); // 취소사유
    $inipay->startAction();
    if($inipay->GetResult('ResultCode') == "00")
    {
        $inipay->MakeTXErrMsg(MERCHANT_DB_ERR,"Merchant DB FAIL");
    }
}
?>