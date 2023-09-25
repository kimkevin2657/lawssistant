<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

$xpay->Rollback($cancel_msg . " [TID:" . $xpay->Response("LGD_TID",0) . ",MID:" . $xpay->Response("LGD_MID",0) . ",OID:" . $xpay->Response("LGD_OID",0) . "]");