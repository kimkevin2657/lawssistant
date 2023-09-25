<?php
include_once('./_common.php');
include_once(G5_PLUGIN_PATH.'/wz.bookingC.prm/config.php');
include_once(G5_PLUGIN_PATH.'/wz.bookingC.prm/lib/function.lib.php');

/*
xpay_approval.php 에서 세션에 저장했던 파라미터 값이 유효한지 체크
세션 유지 시간(로그인 유지시간)을 적당히 유지 하거나 세션을 사용하지 않는 경우 DB처리 하시기 바랍니다.
*/

if(!isset($_SESSION['PAYREQ_MAP'])){
    alert('세션이 만료 되었거나 유효하지 않은 요청 입니다.', WZB_STATUS_URL);
}

$payReqMap      = $_SESSION['PAYREQ_MAP']; //결제 요청시, Session에 저장했던 파라미터 MAP
$LGD_RESPCODE   = $_REQUEST['LGD_RESPCODE'];
$LGD_RESPMSG    = $_REQUEST['LGD_RESPMSG'];
$LGD_PAYKEY     = '';
$LGD_OID        = $payReqMap['LGD_OID'];

$sql = " select * from {$g5['wzb_booking_data_table']} where od_id = '$LGD_OID' ";
$row = sql_fetch($sql);

$data = unserialize(base64_decode($row['dt_data']));

$action_url = https_url(G5_PLUGIN_DIR.'/wz.bookingC.prm/step.2.update.php', true);

if($LGD_RESPCODE == '0000') {
    $LGD_PAYKEY                = $_REQUEST['LGD_PAYKEY'];
    $payReqMap['LGD_RESPCODE'] = $LGD_RESPCODE;
    $payReqMap['LGD_RESPMSG']  = $LGD_RESPMSG;
    $payReqMap['LGD_PAYKEY']   = $LGD_PAYKEY;
} else {
    alert('LGD_RESPCODE:' . $LGD_RESPCODE . ' ,LGD_RESPMSG:' . $LGD_RESPMSG, WZB_STATUS_URL); //인증 실패에 대한 처리 로직 추가
}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="ko" xml:lang="ko">
<head>
<title>스마트폰 웹 결제창</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Cache-Control" content="No-Cache">
<meta http-equiv="Pragma" content="No-Cache">
<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=0,maximum-scale=10">
<meta name="HandheldFriendly" content="true">
<meta name="format-detection" content="telephone=no">

</head>
<body onload="setLGDResult();">

    <?php
    $exclude = array('res_cd', 'LGD_PAYKEY');

    echo '<form name="forderform" method="post" action="'.$action_url.'" autocomplete="off">'.PHP_EOL;

    $field = '';
    foreach($data as $key=>$value) {
        if(!empty($exclude) && in_array($key, $exclude))
            continue;

        if(is_array($value)) {
            foreach($value as $k=>$v) {
                $field .= '<input type="hidden" name="'.$key.'['.$k.']" value="'.$v.'">'.PHP_EOL;
            }
        } else {
            $field .= '<input type="hidden" name="'.$key.'" value="'.$value.'">'.PHP_EOL;
        }
    }

    echo $field;

    echo '<input type="hidden" name="res_cd" value="'.$LGD_RESPCODE.'">'.PHP_EOL;
    echo '<input type="hidden" name="LGD_PAYKEY" value="'.$LGD_PAYKEY.'">'.PHP_EOL;

    echo '</form>'.PHP_EOL;
    ?>

    <div>
        <div id="show_progress">
            <span style="display:block; text-align:center;margin-top:120px"><img src="<?php echo WZB_PLUGIN_URL; ?>/gender/lg/img/loading.gif" alt=""></span>
            <span style="display:block; text-align:center;margin-top:10px; font-size:14px">주문완료 중입니다. 잠시만 기다려 주십시오.</span>
        </div>
    </div>

    <script type="text/javascript">
    function setLGDResult() {
        setTimeout( function() {
            document.forderform.submit();
        }, 300);
    }
    </script>

</body>
</html>