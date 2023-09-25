<?php
if(!defined('_MALLSET_')) exit;

$file = MS_DATA_PATH.'/board/boardimg/'.$board['fileurl2'];
if(is_file($file) && $board['fileurl2']) {
	$file = rpc($file, MS_PATH, MS_URL);
	echo '<p><img src="'.$file.'"></p>';
}
?>
		<?php
		include_once(MS_MYPAGE_PATH."/admin_tail.sub.php"); 
		?>
	</div>
</div>

<?php
include_once(MS_MYPAGE_PATH."/admin_tail.php"); 
?>