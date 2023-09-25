<?php
if(!defined('_MALLSET_')) exit;
?>
<link href="/lightbox/css/lightbox.css" rel="stylesheet" />
<script src="/lightbox/js/lightbox.js"></script>
<a name="it_comment"></a>
<div class="bx-danger">
	전체 <b><?php echo $item_use_count; ?></b>건의 상품평이 있습니다. 상품평 이외에 다른 목적이나 불건전한 내용을 올리실 경우 삭제 처리될 수 있습니다.
</div>

<table class="wfull">
<?php
$sql = "select * from shop_goods_review where gs_id = '$index_no' ";
if($default['de_review_wr_use']) { 
//	$sql .= " and pt_id = '$pt_id' ";
}
$sql .= " order by index_no desc ";
$res = sql_query($sql);
$k = 0;
while($row = sql_fetch_array($res)) {
?>
<tr>
	<td class="tal padl10 padt15 padb10 lh6">
  
  <span id="mallset_img"><?php echo nl2br($row['memo']); ?><?php if(is_admin() || ($member['id'] == $row['mb_id'])) { ?>&nbsp;&nbsp;&nbsp;<a href="javascript:tdel('<?php echo MS_SHOP_URL; ?>/view_user_update.php?index_no=<?php echo $index_no; ?>&it_mid=<?php echo $row['index_no']; ?>&mode=d');" class="btn_ssmall bx-white">삭제</a>&nbsp;&nbsp;&nbsp;<a href="<?php echo MS_SHOP_URL; ?>/orderreview_mod.php?gs_id=<?php echo $index_no; ?>&od_id=<?php echo $row['od_id']; ?>" onclick="win_open(this, 'winorderreview', '650', '530','yes');return false;" class="btn_ssmall bx-white">수정</a><?php } ?></span>
  <?php if($row['photo_file_1']) { ?>
  <br><br>
  <a href="<?=$row['photo_file_1']?>" data-lightbox="image-<?=$k?>" data-title=""><img src="<?=$row['photo_file_1']?>" style="width:50px;height:50px;border:1px solid"></a>
  <?php } ?>
  <?php if($row['photo_file_2']) { ?>
  <a href="<?=$row['photo_file_2']?>" data-lightbox="image-<?=$k?>" data-title=""><img src="<?=$row['photo_file_2']?>" style="width:50px;height:50px;border:1px solid"></a>
  <?php } ?>
  <?php if($row['photo_file_3']) { ?>
  <a href="<?=$row['photo_file_3']?>" data-lightbox="image-<?=$k?>" data-title=""><img src="<?=$row['photo_file_3']?>" style="width:50px;height:50px;border:1px solid"></a>
  <?php } ?>
  
  </td>
	<td width="130" class="tac"><?php echo cut_str(get_member_name($row['mb_id']), 12); ?></td>
	<td width="80" class="tac"><?php echo substr($row['reg_time'],0,10); ?></td>
	<td width="80" class="tac"><?php for($i=0;$i<(int)$row['score'];$i++) { ?><img src="<?php echo MS_IMG_URL; ?>/sub/comment_start.jpg" align="absmiddle"><?php } ?></td>
</tr>
<tr><td height="1" bgcolor="#eeeeee" colspan="4"></td></tr>
<?php $k++; } ?>
</table>

<form name="fuserform" id="fuserform" action="<?php echo MS_SHOP_URL; ?>/view_user_update.php" method="post" onsubmit="return fuserform_submit(this);" class="mart20" style="display:none;">
<input type="hidden" name="mode" value="w">
<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">
<input type="hidden" name="seller_id" value="<?php echo $gs['mb_id']; ?>">
<input type="hidden" name="token" value="<?php echo $token; ?>">

<table class="wfull">
<tr>
	<td class="tal">Name : <?php if($is_member) { echo $member['name']; } else { echo "로그인 후 작성하여 주십시오."; } ?></td>
	<td class="tar">
		<?php
		for($i=1; $i<=5; $i++) {
			$checked = "";
			if($i == 1) $checked = "checked";
		?>
		<input type="radio" name="score" value="<?php echo $i; ?>" <?php echo $checked; ?>>
		<img src="<?php echo MS_IMG_URL; ?>/sub/score_<?php echo $i; ?>.gif" align="absmiddle">
		<?php } ?>
	</td>
</tr>
</table>

<table class="wfull mart10">
<tr>
	<td><textarea name="memo" class="letter_bx" <?php if(!$is_member) { echo "disabled"; } ?>></textarea></td>
	<td width="10"></td>
	<td width="78">
		<?php if($is_member) { ?>
		<button type="submit" name="formimage1" class="btn_letter">사용후기<br>등록하기</button>
		<?php } else { ?>
		<a href="javascript:tguest();" class="btn_letter">사용후기<br>등록하기</a>
		<?php } ?>
	</td>
</tr>
</table>
</form>

<script>
function fuserform_submit(f){
	if(!f.memo.value){
		alert('내용을 입력하세요.');
		f.memo.focus();
		return false;
	}

	if(confirm("등록 하시겠습니까?") == false)
		return false;
}

function tdel(url){
	if(confirm('삭제 하시겠습니까?')){
		location.href = url;
	}
}

function tguest(){
	answer = confirm('로그인 하셔야 상품평 작성이 가능합니다. 로그인 하시겠습니까?');
	if(answer==true) {	
		location.href = tb_bbs_url+"/login.php?url=<?php echo $urlencode; ?>";
	}
}
</script>
