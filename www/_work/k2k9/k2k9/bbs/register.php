<?php
include_once("./_common.php");

if(TB_IS_MOBILE) {
	goto_url(TB_MBBS_URL.'/register.php');
}

set_session("j_key", "");
set_session("allow", "");

if($is_member) {
	goto_url(TB_URL);
}

// 본사쇼핑몰에서 회원가입을 받지 않을때
$config['admin_reg_msg'] = str_replace("\r\n", "\\r\\n", $config['admin_reg_msg']);
if($config['admin_reg_yes'] && $pt_id == 'admin') {
	alert($config['admin_reg_msg'], TB_URL);
}

$tb['title'] = '회원가입';
include_once("./_head.php"); 

// 실명인증 사용시
if($default['de_certify_use']) {
	$regReqSeq = get_session('REQ_SEQ');
	$sql = " delete from shop_joincheck where j_key = '$regReqSeq' ";
	sql_query($sql, FALSE);

	@include_once(TB_PLUGIN_PATH."/chekplus/checkplus_main.php");
	@include_once(TB_PLUGIN_PATH."/chekplus/ipin_main.php");
}

$register_action_url = TB_BBS_URL.'/register_form.php';

if( defined('USE_MOBILE_JOIN_FORM') && USE_MOBILE_JOIN_FORM ) :
?>
    <link rel="stylesheet" href="<?php echo TB_MCSS_URL; ?>/default.css?ver=<?php echo TB_CSS_VER;?>">
    <link rel="stylesheet" href="<?php echo TB_MTHEME_URL; ?>/style.css?ver=<?php echo TB_CSS_VER;?>">
    <style>
        #fregister .fregister_agree { padding: 0; }
    </style>
<?php
    Theme::get_theme_part(TB_MTHEME_PATH,'/register.skin.php');
else :
    Theme::get_theme_part(TB_THEME_PATH,'/register.skin.php');
endif;

include_once("./_tail.php");
?>