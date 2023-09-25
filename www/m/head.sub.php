<?php
// 이 파일은 새로운 파일 생성시 반드시 포함되어야 함
if(!defined('_MALLSET_')) exit; // 개별 페이지 접근 불가

$begin_time = get_microtime();

if(!isset($ms['title'])) {
    $ms['title'] = get_head_title('head_title', $pt_id);
    $ms_head_title = $ms['title'];
}
else {
    $ms_head_title = $ms['title']; // 상태바에 표시될 제목
    $ms_head_title .= " | ".get_head_title('head_title', $pt_id);
}

// 현재 접속자
// 게시판 제목에 ' 포함되면 오류 발생
$ms['lo_location'] = addslashes($ms['title']);
if(!$ms['lo_location'])
    $ms['lo_location'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
$ms['lo_url'] = addslashes(clean_xss_tags($_SERVER['REQUEST_URI']));
if(strstr($ms['lo_url'], '/'.MS_ADMIN_DIR.'/') || is_admin()) $ms['lo_url'] = '';

/*
// 만료된 페이지로 사용하시는 경우
header("Cache-Control: no-cache"); // HTTP/1.1
header("Expires: 0"); // rfc2616 - Section 14.21
header("Pragma: no-cache"); // HTTP/1.0
*/
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<?php
$base_filename = basename($_SERVER['PHP_SELF']);
if($base_filename == "register_form.php"){
?>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
<?php
}else{
?>
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=3">
<?php
}
?>
<meta name="HandheldFriendly" content="true">
<meta name="format-detection" content="telephone=no">
<meta name="naver-site-verification" content="fdbc62b890dd89617dd8f0819134f8abf04a3b90" />
<?php
include_once(MS_LIB_PATH.'/seometa.lib.php');

if($config['add_meta'])
    echo $config['add_meta'].PHP_EOL;
?>
<title><?php echo $ms_head_title; ?></title>
<link rel="stylesheet" href="<?php echo MS_MCSS_URL; ?>/default.css?ver=<?php echo MS_CSS_VER;?>">
<link rel="stylesheet" href="<?php echo MS_MTHEME_URL; ?>/style.css?ver=<?php echo MS_CSS_VER;?>">
<?php if($ico = display_logo_url('favicon_ico')) { // 파비콘 ?>
<link rel="shortcut icon" href="<?php echo $ico; ?>" type="image/x-icon">
<?php } ?>
<script>
var tb_url = "<?php echo MS_URL; ?>";
var tb_bbs_url = "<?php echo MS_BBS_URL; ?>";
var tb_shop_url = "<?php echo MS_SHOP_URL; ?>";
var tb_mobile_url = "<?php echo MS_MURL; ?>";
var tb_mobile_bbs_url = "<?php echo MS_MBBS_URL; ?>";
var tb_mobile_shop_url = "<?php echo MS_MSHOP_URL; ?>";
var tb_is_member = "<?php echo $is_member; ?>";
var tb_is_mobile = "<?php echo MS_IS_MOBILE; ?>";
var tb_cookie_domain = "<?php echo MS_COOKIE_DOMAIN; ?>";
</script>
<script src="<?php echo MS_JS_URL; ?>/jquery-1.8.3.min.js"></script>
<script src="<?php echo MS_JS_URL; ?>/jquery-ui-1.10.3.custom.js"></script>
<script src="<?php echo MS_JS_URL; ?>/slick.js"></script>
<script src="<?php echo MS_MJS_URL; ?>/common.js?ver=<?php echo MS_JS_VER;?>"></script>
<script src="<?php echo MS_MJS_URL; ?>/iscroll.js?ver=<?php echo MS_JS_VER;?>"></script>
<?php
if($config['head_script']) { // head 내부태그
    echo $config['head_script'].PHP_EOL;
}
?>
</head>
<body class="mobile" <?php echo isset($ms['body_script']) ? $ms['body_script'] : ''; ?>>