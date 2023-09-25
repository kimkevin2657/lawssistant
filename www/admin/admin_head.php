<?php
if(!defined('_MALLSET_')) exit;

if(!$ms['title'])
    $ms['title'] = '관리자 페이지';
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title><?php echo $ms['title']; ?></title>
<link rel="stylesheet" href="<?php echo MS_ADMIN_URL; ?>/css/admin.css?ver=<?php echo MS_CSS_VER; ?>">
<?php if($ico = display_logo_url('favicon_ico')) { // 파비콘 ?>
<link rel="shortcut icon" href="<?php echo $ico; ?>" type="image/x-icon">
<?php } ?>
<script>
// 자바스크립트에서 사용하는 전역변수 선언
var tb_url		 = "<?php echo MS_URL; ?>";
var tb_bbs_url	 = "<?php echo MS_BBS_URL; ?>";
var tb_shop_url  = "<?php echo MS_SHOP_URL; ?>";
var tb_admin_url = "<?php echo MS_ADMIN_URL; ?>";

var PARTNER_LEVEL_MAX = <?php echo minishop::LEVEL_MAX; ?>;
var PARTNER_LEVEL_MIN = <?php echo minishop::LEVEL_MIN; ?>;

</script>
<script src="<?php echo MS_JS_URL; ?>/jquery-1.8.3.min.js"></script>
<script src="<?php echo MS_JS_URL; ?>/jquery-ui-1.10.3.custom.js"></script>
<script src="<?php echo MS_JS_URL; ?>/common.js?ver=<?php echo MS_JS_VER; ?>"></script>
<script src="<?php echo MS_JS_URL; ?>/categorylist.js?ver=<?php echo MS_JS_VER; ?>"></script>
</head>
<body>
