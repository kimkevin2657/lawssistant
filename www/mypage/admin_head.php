<?php
include_once("./_common.php");
include_once(MS_MYPAGE_PATH."/admin_menu.php");

if(is_admin() && !$boardid) {
	alert('현재 관리자로 접속중입니다.', MS_URL);
}

if(!$is_member) {
	alert('로그인 후 이용하세요.', MS_BBS_URL.'/login.php');
}

if(is_minishop($member['id']) || is_seller($member['id'])) {
	if($member['homepage'])
		$admin_shop_url = set_https($member['homepage']);
	else
		$admin_shop_url = set_https($member['id'].'.'.$config['admin_shop_url']);

	// 월관리비를 사용중인가?
	if($config['pf_expire_use'] && !is_null_time($member['term_date'])) {
		$minishop_term = '<em>(미니샵 만료일: '.$member['term_date'].')</em>';
	}
} else {
	$admin_shop_url = set_https($config['admin_shop_url']);
}
?>
<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title>마이페이지</title>
<link rel="stylesheet" href="<?php echo MS_MYPAGE_URL; ?>/css/mypage.css?ver=<?php echo MS_CSS_VER; ?>">
<?php if($ico = display_logo_url('favicon_ico')) { // 파비콘 ?>
<link rel="shortcut icon" href="<?php echo $ico; ?>" type="image/x-icon">
<?php } ?>
<script>
// 자바스크립트에서 사용하는 전역변수 선언
var tb_url		 = "<?php echo MS_URL; ?>";
var tb_bbs_url	 = "<?php echo MS_BBS_URL; ?>";
var tb_shop_url  = "<?php echo MS_SHOP_URL; ?>";
var tb_admin_url = "<?php echo MS_ADMIN_URL; ?>";
</script>
<script src="<?php echo MS_JS_URL; ?>/jquery-1.8.3.min.js"></script>
<script src="<?php echo MS_JS_URL; ?>/jquery-ui-1.10.3.custom.js"></script>
<script src="<?php echo MS_JS_URL; ?>/common.js?ver=<?php echo MS_JS_VER; ?>"></script>
<script src="<?php echo MS_JS_URL; ?>/categorylist.js?ver=<?php echo MS_JS_VER; ?>"></script>
</head>
<body>
<div id="header"<?php if($admin_seller) { ?> class="supply"<?php } ?>>
	<?php if($admin_minishop) { ?>
	<h1><a href="<?php echo MS_MYPAGE_URL; ?>/page.php?code=minishop_info">미니샵 관리자</a></h1>
	<?php } ?>
	<?php if($admin_seller) { ?>
	<h1><a href="<?php echo MS_MYPAGE_URL; ?>/page.php?code=seller_main">공급사 관리자</a></h1>
	<?php } ?>
	<div id="tnb">
		<ul>
			<li><?php echo get_text($member['name']); ?>님! 접속중..<?php echo $minishop_term; ?></li>
			<li>고객센터 : <?php echo $config['company_tel']; ?></li>
			<li><a href="<?php echo $admin_shop_url; ?>">쇼핑몰</a></li>
			<?php if(is_minishop($member['id'])) { ?>
			<li><a href="<?php echo MS_MYPAGE_URL; ?>/page.php?code=minishop_info">미니샵 관리</a></li>
			<?php } ?>
			<?php if(is_seller($member['id'])) { ?>
			<li><a href="<?php echo MS_MYPAGE_URL; ?>/page.php?code=seller_main">공급사 관리</a></li>
			<?php } ?>
			<li id="tnb_logout"><a href="<?php echo MS_BBS_URL; ?>/logout.php">로그아웃</a></li>
		</ul>
	</div>
</div>
