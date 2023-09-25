<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

if ($wzcnf['cf_pg_service'] == 'inicis') {

    if (!function_exists('xml_set_element_handler')) {
        echo '<script>'.PHP_EOL;
        echo 'alert("XML 관련 함수를 사용할 수 없습니다.\n서버 관리자에게 문의해 주십시오.");'.PHP_EOL;
        echo '</script>'.PHP_EOL;
    }

    if (!function_exists('openssl_get_publickey')) {
        echo '<script>'.PHP_EOL;
        echo 'alert("OPENSSL 관련 함수를 사용할 수 없습니다.\n서버 관리자에게 문의해 주십시오.");'.PHP_EOL;
        echo '</script>'.PHP_EOL;
    }

    if (!function_exists('socket_create')) {
        echo '<script>'.PHP_EOL;
        echo 'alert("SOCKET 관련 함수를 사용할 수 없습니다.\n서버 관리자에게 문의해 주십시오.");'.PHP_EOL;
        echo '</script>'.PHP_EOL;
    }

    $log_path = WPOT_PLUGIN_PATH.'/gender/inicis/log';

    if(!is_dir($log_path)) {
        echo '<script>'.PHP_EOL;
        echo 'alert("'.str_replace(G5_PATH.'/', '', WPOT_PLUGIN_PATH).'/gender/inicis 폴더 안에 log 폴더를 생성하신 후 쓰기권한을 부여해 주십시오.\n> mkdir log\n> chmod 707 log");'.PHP_EOL;
        echo '</script>'.PHP_EOL;
    } else {
        if(!is_writable($log_path)) {
            echo '<script>'.PHP_EOL;
            echo 'alert("'.str_replace(G5_PATH.'/', '',$log_path).' 폴더에 쓰기권한을 부여해 주십시오.\n> chmod 707 log");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        }
    }
}
else if ($wzcnf['cf_pg_service'] == 'lg') {

    $log_path = WPOT_PLUGIN_PATH.'/gender/lg/lgxpay/lgdacom/log';

    if(!is_dir($log_path)) {
        echo '<script>'.PHP_EOL;
        echo 'alert("'.str_replace(G5_PATH.'/', '', WPOT_PLUGIN_PATH).'/gender/lg/lgxpay/lgdacom/log 폴더 안에 log 폴더를 생성하신 후 쓰기권한을 부여해 주십시오.\n> mkdir log\n> chmod 707 log");'.PHP_EOL;
        echo '</script>'.PHP_EOL;
    } else {
        if(!is_writable($log_path)) {
            echo '<script>'.PHP_EOL;
            echo 'alert("'.str_replace(G5_PATH.'/', '',$log_path).' 폴더에 쓰기권한을 부여해 주십시오.\n> chmod 707 log");'.PHP_EOL;
            echo '</script>'.PHP_EOL;
        }
    }
}
else if ($wzcnf['cf_pg_service'] == 'kcp') {

    if(!extension_loaded('openssl')) {
        echo '<script>'.PHP_EOL;
        echo 'alert("PHP openssl 확장모듈이 설치되어 있지 않습니다.\n모바일 쇼핑몰 결제 때 사용되오니 openssl 확장 모듈을 설치하여 주십시오.");'.PHP_EOL;
        echo '</script>'.PHP_EOL;
    }

    if(!extension_loaded('soap') || !class_exists('SOAPClient')) {
        echo '<script>'.PHP_EOL;
        echo 'alert("PHP SOAP 확장모듈이 설치되어 있지 않습니다.\n모바일 쇼핑몰 결제 때 사용되오니 SOAP 확장 모듈을 설치하여 주십시오.");'.PHP_EOL;
        echo '</script>'.PHP_EOL;
    }

    $is_linux = true;
    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
        $is_linux = false;

    $exe = '/kcp/bin/';
    if($is_linux) {
        if(PHP_INT_MAX == 2147483647) // 32-bit
            $exe .= 'pp_cli';
        else
            $exe .= 'pp_cli_x64';
    } else {
        $exe .= 'pp_cli_exe.exe';
    }

    // log 디렉토리 체크 후 있으면 경고
    if(is_dir(WPOT_PLUGIN_PATH.'/gender/kcp/log') && is_writable(WPOT_PLUGIN_PATH.'/gender/kcp/log')) {
        echo '<script>'.PHP_EOL;
        echo 'alert("웹접근 가능 경로에 log 디렉토리가 있습니다.\nlog 디렉토리를 웹에서 접근 불가능한 경로로 변경해 주십시오.")'.PHP_EOL;
        echo '</script>'.PHP_EOL;
    }

}

// 모바일 이니시스 계좌이체 결과 전달을 위한 테이블 추가
if(!sql_query(" DESCRIBE {$g5['wpot_order_inicis_log_table']} ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `{$g5['wpot_order_inicis_log_table']}` (
                  `oid` bigint(20) unsigned NOT NULL,
                  `P_TID` varchar(255) NOT NULL DEFAULT '',
                  `P_MID` varchar(255) NOT NULL DEFAULT '',
                  `P_AUTH_DT` varchar(255) NOT NULL DEFAULT '',
                  `P_STATUS` varchar(255) NOT NULL DEFAULT '',
                  `P_TYPE` varchar(255) NOT NULL DEFAULT '',
                  `P_OID` varchar(255) NOT NULL DEFAULT '',
                  `P_FN_NM` varchar(255) NOT NULL DEFAULT '',
                  `P_AUTH_NO` varchar(255) NOT NULL DEFAULT '',
                  `P_AMT` int(11) NOT NULL DEFAULT '0',
                  `P_RMESG1` varchar(255) NOT NULL DEFAULT '',
                  PRIMARY KEY (`oid`)
                ) ENGINE=MyISAM DEFAULT CHARSET=utf8 ", true);
}

$pglist = array();
$dirs = wz_get_gender_dir();
if(!empty($dirs)) {
    foreach($dirs as $dir) {
        $pglist[] = $dir;
    }
}
?>

<tr>
    <th scope="row"><label for="cf_pg_service">결제대행사</label></th>
    <td>
        <?php echo help('충전시 사용할 결제대행사를 선택합니다.'); ?>
        <select id="cf_pg_service" name="cf_pg_service">
            <option value="">사용안함</option>
            <?php
            foreach ($pglist as $k => $v) {
                echo '<option value="'.$v.'" '.get_selected($wzcnf['cf_pg_service'], $v).'>'.strtoupper($v).'</option>';
            }
            ?>
        </select>
    </td>
</tr>
<tr>
    <th scope="row"><label for="cf_pg_mid" id="pn-pg-title01">상점아이디</label></th>
    <td>
        <div id="pn-pg-desc01"><?php echo help("결제대행사에서 발급받은 상점아이디를 입력해주세요."); ?></div>
        <input type="text" name="cf_pg_mid" value="<?php echo $wzcnf['cf_pg_mid']; ?>" id="cf_pg_mid" class="frm_input" size="10" maxlength="20" style="font:bold 15px Verdana;">
    </td>
</tr>
<tr>
    <th scope="row"><label for="cf_pg_site_key" id="pn-pg-title02">상점키코드</label></th>
    <td>
        <div id="pn-pg-desc02"><?php echo help("결제대행사에서 발급받은 상점키값을 입력해주세요."); ?></div>
        <input type="text" name="cf_pg_site_key" value="<?php echo $wzcnf['cf_pg_site_key']; ?>" id="cf_pg_site_key" class="frm_input" size="70" maxlength="110">
    </td>
</tr>
<tr>
    <th scope="row"><label for="cf_pg_sign_key" id="pn-pg-title03">상점부가코드</label></th>
    <td>
        <div id="pn-pg-desc03"><?php echo help("결제대행사에서 발급받은 부가적인 코드정보를 입력해주세요."); ?></div>
        <input type="text" name="cf_pg_sign_key" value="<?php echo $wzcnf['cf_pg_sign_key']; ?>" id="cf_pg_sign_key" class="frm_input" size="70" maxlength="110">
    </td>
</tr>
<tr>
    <th scope="row">결제 테스트</th>
    <td>
        <?php echo help("PG사의 결제 테스트를 하실 경우에 체크하세요."); ?>
        <input type="radio" name="cf_pg_test" value="0" <?php echo $wzcnf['cf_pg_test']==0?"checked":""; ?> id="cf_pg_test1">
        <label for="cf_pg_test1">실결제 </label>&nbsp;
        <input type="radio" name="cf_pg_test" value="1" <?php echo $wzcnf['cf_pg_test']==1?"checked":""; ?> id="cf_pg_test2">
        <label for="cf_pg_test2">테스트결제</label>
    </td>
</tr>
<tr>
    <th scope="row"><label for="cf_pg_card_use">신용카드결제사용</label></th>
    <td>
        <?php echo help("주문시 신용카드 결제를 가능하게 할것인지를 설정합니다.", 50); ?>
        <select id="cf_pg_card_use" name="cf_pg_card_use">
            <option value="0" <?php echo get_selected($wzcnf['cf_pg_card_use'], 0); ?>>사용안함</option>
            <option value="1" <?php echo get_selected($wzcnf['cf_pg_card_use'], 1); ?>>사용</option>
        </select>
    </td>
</tr>
<tr>
    <th scope="row"><label for="cf_pg_dbank_use">계좌이체사용</label></th>
    <td>
        <?php echo help("주문시 계좌이체 결제를 가능하게 할것인지를 설정합니다.", 50); ?>
        <select id="cf_pg_dbank_use" name="cf_pg_dbank_use">
            <option value="0" <?php echo get_selected($wzcnf['cf_pg_dbank_use'], 0); ?>>사용안함</option>
            <option value="1" <?php echo get_selected($wzcnf['cf_pg_dbank_use'], 1); ?>>사용</option>
        </select>
    </td>
</tr>
<tr>
    <th scope="row"><label for="cf_pg_vbank_use">가상계좌사용</label></th>
    <td>
        <?php echo help("주문시 가상계좌 결제를 가능하게 할것인지를 설정합니다.", 50); ?>
        <select id="cf_pg_vbank_use" name="cf_pg_vbank_use">
            <option value="0" <?php echo get_selected($wzcnf['cf_pg_vbank_use'], 0); ?>>사용안함</option>
            <option value="1" <?php echo get_selected($wzcnf['cf_pg_vbank_use'], 1); ?>>사용</option>
        </select>

        <?php if ($wzcnf['cf_pg_service'] == 'inicis') { ?>
        <div style="margin:7px 0 0">
            <?php echo help("KG이니시스 가상계좌 사용시 다음 주소를 <strong><a href=\"https://iniweb.inicis.com/\" target=\"_blank\">KG이니시스 관리자</a> &gt; 거래조회 &gt; 가상계좌 &gt; 입금통보방식선택 &gt; URL 수신 설정</strong>에 넣으셔야 상점에 자동으로 입금 통보됩니다."); ?>
            <?php echo WPOT_PLUGIN_URL; ?>/gender/inicis/pg_vbank_sink.php
        </div>
        <?php } ?>

    </td>
</tr>
<tr>
    <th scope="row"><label for="cf_pg_hp_use">휴대폰결제사용</label></th>
    <td>
        <?php echo help("주문시 가상계좌 결제를 가능하게 할것인지를 설정합니다.", 50); ?>
        <select id="cf_pg_hp_use" name="cf_pg_hp_use">
            <option value="0" <?php echo get_selected($wzcnf['cf_pg_hp_use'], 0); ?>>사용안함</option>
            <option value="1" <?php echo get_selected($wzcnf['cf_pg_hp_use'], 1); ?>>사용</option>
        </select>
    </td>
</tr>

<script type="text/javascript">
<!--
jQuery(document).ready(function () {
    $(document).on("change", "#cf_pg_service", function() {
        set_pg_input();
    });
    set_pg_input();
});
function set_pg_input() {
    var pg_service = $("#cf_pg_service > option:selected").val();
    if (pg_service == 'etc') {

    }
    else if (pg_service == 'inicis') {
        $('#pn-pg-title01').text('상점아이디');
        $('#pn-pg-title02').text('KG이니시스 키패스워드');
        $('#pn-pg-title03').text('KG이니시스 웹결제 사인키');
        $('#pn-pg-desc01').html('<?php echo help("KG이니시스로부터 발급받으신 상점아이디(MID) 를 입력해주세요."); ?>');
        $('#pn-pg-desc02').html('<?php echo help("KG이니시스로부터 발급받은 상점 키패스워드를 입력합니다."); ?>');
        $('#pn-pg-desc03').html('<?php echo help("KG이니시스로부터 발급받은 웹결제 사인키를 입력합니다."); ?>');
    }
    else {
        $('#pn-pg-title01').text('상점아이디');
        $('#pn-pg-title02').text('상점키코드');
        $('#pn-pg-title03').text('상점부가코드');
        $('#pn-pg-desc01').html('<?php echo help("결제대행사에서 발급받은 상점아이디를 입력해주세요."); ?>');
        $('#pn-pg-desc02').html('<?php echo help("결제대행사에서 발급받은 상점키값을 입력해주세요."); ?>');
        $('#pn-pg-desc03').html('<?php echo help("결제대행사에서 발급받은 부가적인 코드정보를 입력해주세요."); ?>');
    }
}
//-->
</script>