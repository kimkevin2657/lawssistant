<?php
define('_NEWWIN_', true);
include_once('./_common.php');
include_once(MS_ADMIN_PATH."/admin_access.php");

$ms['title'] = "가맹점 조직도";
include_once(MS_ADMIN_PATH."/admin_head.php");

?>
<div id="orgchart_pop" class="new_win">
	<h1><?php echo $ms['title']; ?></h1>

	<section class="new_win_desc marb50">
        <?php
        Organization::printChart($mb_id, 'pt_id', 100000, 100000);
        ?>
	<div class="btn_confirm">
		<button type="button" class="btn_medium bx-white" onclick="window.close();">닫기</button>
	</div>
	</section>
</div>

<?php
include_once(MS_ADMIN_PATH."/admin_tail.sub.php");
?>