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
wz_fwrite_log($log_dir.'/pg_mobile_return_'.date('Ymd').'.log', $log_txt);

$oid  = trim($_REQUEST['P_NOTI']);

$sql = " select * from {$g5['wpot_order_data_table']} where od_id = '$oid' ";
$row = sql_fetch($sql);

$data = unserialize(base64_decode($row['dt_data']));

$order_action_url = https_url(G5_PLUGIN_DIR.'/wz.chargepoint/request.update.php', true);
$page_return_url  = WPOT_STATUS_URL;

$sql = " select * from {$g5['wpot_order_inicis_log_table']} where oid = '$oid' ";
$row = sql_fetch($sql);

if(!$row['oid'])
    alert('결제 정보가 존재하지 않습니다.\\n\\n올바른 방법으로 이용해 주십시오.', $page_return_url);

if($row['P_STATUS'] != '00')
    alert('오류 : '.$row['P_RMESG1'].' 코드 : '.$row['P_STATUS'], $page_return_url);

$PAY = array_map('trim', $row);

// TID, AMT 를 세션으로 주문완료 페이지 전달
$hash = md5($PAY['P_TID'].$PAY['P_MID'].$PAY['P_AMT']);
set_session('P_TID',  $PAY['P_TID']);
set_session('P_AMT',  $PAY['P_AMT']);
set_session('P_HASH', $hash);

// 로그 삭제
@sql_query(" delete from {$g5['wpot_order_inicis_log_table']} where oid = '$oid' ");

$g5['title'] = 'KG 이니시스 결제';
include_once(G5_PATH.'/head.sub.php');

$exclude = array('res_cd', 'P_HASH', 'P_TYPE', 'P_AUTH_DT', 'P_VACT_BANK', 'P_AUTH_NO');

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

echo '<input type="hidden" name="res_cd"      value="'.$PAY['P_STATUS'].'">'.PHP_EOL;
echo '<input type="hidden" name="P_HASH"      value="'.$hash.'">'.PHP_EOL;
echo '<input type="hidden" name="P_TYPE"      value="'.$PAY['P_TYPE'].'">'.PHP_EOL;
echo '<input type="hidden" name="P_AUTH_DT"   value="'.$PAY['P_AUTH_DT'].'">'.PHP_EOL;
echo '<input type="hidden" name="P_VACT_BANK" value="'.$PAY['P_FN_NM'].'">'.PHP_EOL;
echo '<input type="hidden" name="P_AUTH_NO"   value="'.$PAY['P_AUTH_NO'].'">'.PHP_EOL;

echo '</form>'.PHP_EOL;
?>

<div id="pay_working" style="display:none;">
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