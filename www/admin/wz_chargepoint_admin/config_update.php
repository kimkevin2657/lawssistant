<?php
include_once('./_common.php');

check_demo();

/* if ($is_admin != 'super')
    alert('최고관리자만 접근 가능합니다.'); */

$sql_common = " cps_sms_receive = '{$cps_sms_receive}',
                cps_sms1_use_user = '".(int)$cps_sms1_use_user."',
                cps_sms1_con_user = '".$cps_sms1_con_user."',
                cps_sms2_use_user = '".(int)$cps_sms2_use_user."',
                cps_sms2_con_user = '".$cps_sms2_con_user."',
                cps_sms3_use_user = '".(int)$cps_sms3_use_user."',
                cps_sms3_con_user = '".$cps_sms3_con_user."',
                cps_sms1_use_adm = '".(int)$cps_sms1_use_adm."',
                cps_sms1_con_adm = '".$cps_sms1_con_adm."',
                cps_sms2_use_adm = '".(int)$cps_sms2_use_adm."',
                cps_sms2_con_adm = '".$cps_sms2_con_adm."',
                cps_sms3_use_adm = '".(int)$cps_sms3_use_adm."',
                cps_sms3_con_adm = '".$cps_sms3_con_adm."'
            ";

$sql = " update {$g5['wpot_config_table']}
            set cf_bank_info            = '{$_POST['cf_bank_info']}',
                cf_con_notice           = '{$_POST['cf_con_notice']}',
                cf_con_refund           = '{$_POST['cf_con_refund']}',
                cf_bank_use             = '".(int)$_POST['cf_bank_use']."',
                cf_pg_service           = '".$_POST['cf_pg_service']."',
                cf_pg_card_use          = '".$_POST['cf_pg_card_use']."',
                cf_pg_dbank_use         = '".$_POST['cf_pg_dbank_use']."',
                cf_pg_vbank_use         = '".$_POST['cf_pg_vbank_use']."',
                cf_pg_hp_use            = '".$_POST['cf_pg_hp_use']."',
                cf_pg_mid               = '".$_POST['cf_pg_mid']."',
                cf_pg_site_key          = '".$_POST['cf_pg_site_key']."',
                cf_pg_sign_key          = '".$_POST['cf_pg_sign_key']."',
                cf_pg_test              = '".(int)$_POST['cf_pg_test']."',
                cf_point_pay_type       = '".$_POST['cf_point_pay_type']."',
                cf_point_pay_ratio      = '".$_POST['cf_point_pay_ratio']."',
                $sql_common
            ";
sql_query($sql, true);

// 등록된 포인트정보 삭제
foreach ($_POST['cfp_ix_del'] as $key => $value) {
    $cfp_ix = (int)trim($value);
    if ($cfp_ix) {
        $query = "delete from {$g5['wpot_config_point_table']} where cfp_ix = '$cfp_ix'";
        sql_query($query);
    }
}

// 포인트정보 등록
foreach ($_POST['cfp_point'] as $key => $value) {

    $cfp_point      = (int)$value;
    $cfp_price      = (int)$_POST['cfp_price'][$key];
    $cfp_ix         = (int)$_POST['cfp_ix'][$key];

    if ($cfp_ix) {
        $sql = "update {$g5['wpot_config_point_table']} set cfp_point = '$cfp_point', cfp_price = '$cfp_price' where cfp_ix = '".$cfp_ix."' ";
        sql_query($sql);
    }
    else {
        $sql = "insert into {$g5['wpot_config_point_table']} set cfp_point = '$cfp_point', cfp_price = '$cfp_price' ";
        sql_query($sql);
    }
}

goto_url('./point_list.php?code=config', false);
?>