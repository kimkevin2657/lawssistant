<?php
include_once('./_common.php');
include_once(G5_PLUGIN_PATH.'/wz.chargepoint/config.php');
include_once(WPOT_PLUGIN_PATH.'/gender/inicis/config.php');

// 세션 초기화
set_session('P_TID',  '');
set_session('P_AMT',  '');
set_session('P_HASH', '');

// 로그기록
$log_txt = date('Y-m-d H:i:s', time());
$log_txt .= '|IP : '.getenv("REMOTE_ADDR").' ';
foreach($_POST as $uk=>$uv) {
    if (is_array($uv)) {
        foreach($uv as $uk2=>$uv2) {
            $log_txt .= "\$_POST['".$uk."']['".$uk2."'] = '".$uv2."';";
        }
        continue;
    }
    $log_txt .= "\$_POST['".$uk."'] = '".$uv."';";
}
foreach($_GET as $uk=>$uv) {
    if (is_array($uv)) {
        foreach($uv as $uk2=>$uv2) {
            $log_txt .= "\$_GET['".$uk."']['".$uk2."'] = '".$uv2."';";
        }
        continue;
    }
    $log_txt .= "\$_GET['".$uk."'] = '".$uv."';";
}
$log_dir = G5_DATA_PATH.'/pglog';
@mkdir($log_dir, G5_DIR_PERMISSION);
@chmod($log_dir, G5_DIR_PERMISSION);
wz_fwrite_log($log_dir.'/pg_mobile_approval_'.date('Ymd').'.log', $log_txt);

$oid  = trim($_REQUEST['P_NOTI']);

$sql = " select * from {$g5['wpot_order_data_table']} where od_id = '$oid' ";
$row = sql_fetch($sql);

$data = unserialize(base64_decode($row['dt_data']));

$order_action_url = https_url(G5_PLUGIN_DIR.'/wz.chargepoint/request.update.php', true);
$page_return_url  = WPOT_STATUS_URL;

if($_REQUEST['P_STATUS'] != '00') {
    alert('오류 : '.iconv_utf8($_REQUEST['P_RMESG1']).' 코드 : '.$_REQUEST['P_STATUS'], $page_return_url);
} else {
    $post_data = array(
        'P_MID' => $wzcnf['cf_pg_mid'],
        'P_TID' => $_REQUEST['P_TID']
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $_REQUEST['P_REQ_URL']);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $return = curl_exec($ch);

    if(!$return)
        alert('KG이니시스와 통신 오류로 결제등록 요청을 완료하지 못했습니다.\\n결제등록 요청을 다시 시도해 주십시오.', $page_return_url);

    // 결과를 배열로 변환
    parse_str($return, $ret);
    $PAY = array_map('trim', $ret);

    if($PAY['P_STATUS'] != '00')
        alert('오류 : '.iconv_utf8($PAY['P_RMESG1']).' 코드 : '.$PAY['P_STATUS'], $page_return_url);

    // TID, AMT 를 세션으로 주문완료 페이지 전달
    $hash = md5($PAY['P_TID'].$PAY['P_MID'].$PAY['P_AMT']);
    set_session('P_TID',  $PAY['P_TID']);
    set_session('P_AMT',  $PAY['P_AMT']);
    set_session('P_HASH', $hash);
}

$g5['title'] = 'KG 이니시스 결제';
include_once(G5_PATH.'/head.sub.php');

$exclude = array('res_cd', 'P_HASH', 'P_TYPE', 'P_AUTH_DT', 'P_AUTH_NO', 'P_HPP_CORP', 'P_APPL_NUM', 'P_VACT_NUM', 'P_VACT_NAME', 'P_VACT_BANK', 'P_CARD_ISSUER', 'P_UNAME');

echo '<form name="forderform" method="post" action="'.$order_action_url.'" autocomplete="off">'.PHP_EOL;

foreach($data as $key=>$value) {
    if(!empty($exclude) && in_array($key, $exclude))
        continue;

    if(is_array($value)) {
        foreach($value as $k=>$v) {
            echo '<input type="hidden" name="'.$key.'['.$k.']" value="'.$v.'">'.PHP_EOL;
        }
    } else {
        echo '<input type="hidden" name="'.$key.'" value="'.$value.'">'.PHP_EOL;
    }
}

echo '<input type="hidden" name="res_cd"        value="'.$PAY['P_STATUS'].'">'.PHP_EOL;
echo '<input type="hidden" name="P_HASH"        value="'.$hash.'">'.PHP_EOL;
echo '<input type="hidden" name="P_TYPE"        value="'.$PAY['P_TYPE'].'">'.PHP_EOL;
echo '<input type="hidden" name="P_AUTH_DT"     value="'.$PAY['P_AUTH_DT'].'">'.PHP_EOL;
echo '<input type="hidden" name="P_AUTH_NO"     value="'.$PAY['P_AUTH_NO'].'">'.PHP_EOL;
echo '<input type="hidden" name="P_HPP_CORP"    value="'.$PAY['P_HPP_CORP'].'">'.PHP_EOL;
echo '<input type="hidden" name="P_APPL_NUM"    value="'.$PAY['P_APPL_NUM'].'">'.PHP_EOL;
echo '<input type="hidden" name="P_VACT_NUM"    value="'.$PAY['P_VACT_NUM'].'">'.PHP_EOL;
echo '<input type="hidden" name="P_VACT_NAME"   value="'.iconv_utf8($PAY['P_VACT_NAME']).'">'.PHP_EOL;
echo '<input type="hidden" name="P_VACT_BANK"   value="'.$BANK_CODE[$PAY['P_VACT_BANK_CODE']].'">'.PHP_EOL;
echo '<input type="hidden" name="P_CARD_ISSUER" value="'.$CARD_CODE[$PAY['P_CARD_ISSUER_CODE']].'">'.PHP_EOL;
echo '<input type="hidden" name="P_UNAME"       value="'.iconv_utf8($PAY['P_UNAME']).'">'.PHP_EOL;

echo '</form>'.PHP_EOL;
?>

<div id="show_progress">
    <span style="display:block; text-align:center;margin-top:120px"><img src="<?php echo WPOT_PLUGIN_URL; ?>/gender/inicis/img/loading.gif" alt=""></span>
    <span style="display:block; text-align:center;margin-top:10px; font-size:14px">주문완료 중입니다. 잠시만 기다려 주십시오.</span>
</div>

<script type="text/javascript">
function setPAYResult() {
    setTimeout( function() {
        document.forderform.submit();
    }, 300);
}
window.onload = function() {
    setPAYResult();
}
</script>

<?php
include_once(G5_PATH.'/tail.sub.php');
?>