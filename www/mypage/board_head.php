<?php
if(!defined('_MALLSET_')) exit;
$is_seller_page = $board['gr_id'] == 'gr_item';
include_once(MS_MYPAGE_PATH."/admin_head.php");
$pg_title = $board['boardname'];

?>

<div id="wrapper">
	<div id="snb">
		<?php
		include_once($admin_snb_file);
		?>
	</div>
	<div id="content">
		<?php
		include_once(MS_MYPAGE_PATH."/admin_head.sub.php");

		$file = MS_DATA_PATH.'/board/boardimg/'.$board['fileurl1'];
		if(is_file($file) && $board['fileurl1']) {
			$file = rpc($file, MS_PATH, MS_URL);
			echo '<p><img src="'.$file.'"></p>';
		}
		?>
