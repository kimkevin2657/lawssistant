<?php
if(!defined('_MALLSET_')) exit;
?>

<div id="ft">
	<p>Copyright &copy; <?php echo $config['company_name']; ?>. All rights reserved.</p>
</div>

<div id="ajax-loading"><img src="<?php echo MS_IMG_URL; ?>/ajax-loader.gif"></div>
<div id="anc_header"><a href="#anc_hd"><span></span>TOP</a></div>

<?php if(!$boardid) { // �Խ����ϰ�� admin.js ������ �����ϸ� �ȵ� ?>
<script src="<?php echo MS_ADMIN_URL; ?>/js/admin.js?ver=<?php echo MS_JS_VER;?>"></script>
<?php } ?>

<script src="<?php echo MS_JS_URL; ?>/wrest.js"></script>
</body>
</html>
<?php echo html_end(); // HTML ������ ó�� �Լ� : �ݵ�� �־��ֽñ� �ٶ��ϴ�. ?>