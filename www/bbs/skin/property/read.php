<?php
if(!defined('_MALLSET_')) exit;
?>

<div class="tbl_frm01 tbl_wrap">
	<table>
	<tbody>
	<?php if($bo_file1) { ?>
	<!--tr>
		<td Colspan="4">첨부파일1 : <a href="./download.php?file=<?php echo $bo_file1; ?>&mid=<?php  echo $boardid; ?>&url=<?php echo MS_DATA_URL; ?>/board/<?php echo $boardid; ?>/<?php echo $bo_file1; ?>"><b><?php echo $bo_file1; ?></b></a></td>
	</tr-->
	<?php } ?>
	<?php if($bo_file2) { ?>
	<!--tr>
		<td Colspan="4">첨부파일2 : <a href="./download.php?file=<?php echo $bo_file2; ?>&mid=<?php  echo $boardid; ?>&url=<?php echo MS_DATA_URL; ?>/board/<?php echo $boardid; ?>/<?php echo $bo_file2; ?>"><b><?php echo $bo_file2; ?></b></a></td>
	</tr-->
	<?php } ?>
	<tr>
		<td class="list1 fs14" Colspan="4"><b><?php echo $bo_subject; ?></b></td>
	</tr>
	<tr>
		<th>등록자정보</th>
		<td Colspan="3"><b><?php echo $write['w_info']; ?>[<?php echo $bo_writer_s; ?></b> <?php if($bo_writer_id){?>(<?php echo $bo_writer_id; ?>)<?php } ?>], <b>작성일</b> : <?php echo $bo_wdate; ?>, <b>조회수</b> : <?php echo $bo_hit; ?></b></td>
	</tr>
	<tr>
		<th>매물특징</th>
		<td><b><?php echo $write['features']; ?></b></td>
		<th>준공연도</th>
		<td><b><?php echo $write['whenbuild']; ?></b></td>
	</tr>
	<tr>
		<th>주소</th>
		<td Colspan="3"><b><?php echo $write['b_addr1']; ?>&nbsp;<?php echo $write['b_addr2']; ?></b>
		<a href="https://map.kakao.com/" target="_blank" class="btn_small">지도보기</a>
		</td>
		</tr>
		<tr>
		<th>거래형태</th>
		<td Colspan="3"><b><?php echo $write['transaction_type']; ?></b></td>
	</tr>
<?php if($write['transaction_type'] == "전세"){ ?>
	<tr>
		<th>전세보증금</th>
		<td Colspan="3"><b><?php echo $write['deposit_lease']; ?></b></td>
	</tr>
<?php }elseif($write['transaction_type'] == "월세"){ ?>
	<tr>
		<th>보증금</th>
		<td><b><?php echo $write['mon_rent_de']; ?></b></td>
		<th>월세</th>
		<td><b><?php echo $write['mon_rent']; ?></b></td>
	</tr>
<?php }elseif($write['transaction_type'] == "단기"){ ?>
	<tr>
		<th>보증금</th>
		<td><b><?php echo $write['short_rent_de']; ?></b></td>
		<th>월세</th>
		<td><b><?php echo $write['short_rent']; ?></b></td>
	</tr>
<?php }elseif($write['transaction_type'] == "매매"){ ?>
	<tr>
		<th>매매</th>
		<td Colspan="3"><b><?php echo $write['dealing']; ?></b></td>
	</tr>
<?php } ?>
	<tr>
		<th>관리비</th>
		<td><b><?php if($write['expenses_c'] == "없음"){ echo "없음"; }else{ echo number_format($write['expenses'])."원"; } ?></b></td>
		<th>공용관리비포함내역</th>
		<td><b><?php echo $write['expenses_a1']; ?><?php if($write['expenses_a2']){ echo ","; }?><?php echo $write['expenses_a2']; ?><?php if($write['expenses_a3']){ echo ","; }?><?php echo $write['expenses_a3']; ?><?php if($write['expenses_a4']){ echo ","; }?><?php echo $write['expenses_a4']; ?><?php if($write['expenses_a5']){ echo ","; }?><?php echo $write['expenses_a5']; ?><?php if($write['expenses_a6']){ echo ","; }?><?php echo $write['expenses_a6']; ?></b></td>
	</tr>
	<tr>
		<th>개별사용료</th>
		<td Colspan="3"><b><?php echo $write['expenses_b']; ?></b></td>
	</tr>
	<tr>
		<th>융자여부</th>
		<td><b><?php echo $write['loan_a']; ?></b></td>
		<th>융자금</th>
		<td><b><?php echo $write['loan']; ?></b></td>
	</tr>
	<tr>
		<th>계약/전용면적</th>
		<td Colspan="3"><b><?php echo $write['contract']; ?></b></td>
	</tr>
	<tr>
		<th>해당층/총층</th>
		<td><b><?php echo $write['floor']; ?></b></td>
		<th>방수/욕실수</th>
		<td><b><?php echo $write['room']; ?></b></td>
	</tr>
	<tr>
		<th>용도</th>
		<td><b><?php echo $write['purpose']; ?></b></td>
		<th>방구조</th>
		<td><b><?php echo $write['structure']; ?></b></td>
	</tr>
	<tr>
		<th>복층여부</th>
		<td><b><?php echo $write['double_c']; ?></b></td>
		<th>현관구조</th>
		<td><b><?php echo $write['entrance']; ?></b></td>
	</tr>
	<tr>
		<th>주실방향기준</th>
		<td><b><?php echo $write['direction_a']; ?></b></td>
		<th>추가옵션</th>
		<td><b><?php echo $write['add_option1']; ?><?php if($write['add_option2']){ echo ","; }?><?php echo $write['add_option2']; ?><?php if($write['add_option3']){ echo ","; }?><?php echo $write['add_option3']; ?><?php if($write['add_option4']){ echo ","; }?><?php echo $write['add_option4']; ?><?php if($write['add_option5']){ echo ","; }?><?php echo $write['add_option5']; ?><?php if($write['add_option6']){ echo ","; }?><?php echo $write['add_option6']; ?><?php if($write['add_option7']){ echo ","; }?><?php echo $write['add_option7']; ?></b></td>
	</tr>
	<tr>
		<th>방향</th>
		<td><b><?php print_r($write['direction']); ?></b></td>
		<th>입주가능일</th>
		<td><b><?php echo $write['come_date']; ?></b></td>
	</tr>
	<tr>
		<th>총주차대수</th>
		<td><b><?php echo $write['parking']; ?></b></td>
		<th>해당면적 세대수</th>
		<td><b><?php echo $write['households']; ?></b></td>
	</tr>
	<tr>
		<th>난방(방식/연료)</th>
		<td><b><?php echo $write['heating']; ?></b></td>
		<th>에어컨</th>
		<td><b><?php echo $write['air_conditioner']; ?></b></td>
	</tr>
	<tr>
		<th>매물번호</th>
		<td Colspan="3"><b><?php echo $write['index_no']; ?></b></td>
	</tr>
	<tr>
		<th>매물설명</th>
		<td style="height:200px;vertical-align:top;" Colspan="4">
		<?php
		// 픽셀 (게시판에서 출력되는 이미지의 폭 크기)
		if($board['width'] > 100) {
			$thumbnail_width = $board['width'];
		} else {
			$thumbnail_width = 730;
		}
		if($bo_file1 && preg_match("/\.(gif|jpg|jpeg|png)$/i", $bo_file1))
		{
			$file1anal = explode(".",$bo_file1);
			if(in_array($file1anal[1],$accept))
			{
				$imgsize1 = getimagesize(MS_DATA_PATH."/board/".$boardid."/".$bo_file1);
				if($imgsize1[0] > $thumbnail_width) {
					$width = $thumbnail_width;
					$height = ($imgsize1[1] / $imgsize1[0]) * $thumbnail_width;
				} else {
					$width = $imgsize1[0];
					$height = $imgsize1[1];
				}
			}
		?>
		<a href="javascript:imgview('<?php echo MS_DATA_URL; ?>/board/<?php echo $boardid; ?>/<?php echo $bo_file1; ?>');"><img src="<?php echo MS_DATA_URL; ?>/board/<?php echo $boardid; ?>/<?php echo $bo_file1; ?>" width="200" height="200"></a>&nbsp;&nbsp;&nbsp;
		<?php
		}

		if($bo_file2 && preg_match("/\.(gif|jpg|jpeg|png)$/i", $bo_file2))
		{
			$file2anal = explode(".",$bo_file2);
			if(in_array($file2anal[1],$accept))
			{
				$imgsize1 = getimagesize(MS_DATA_PATH."/board/".$boardid."/".$bo_file2);
				if($imgsize1[0] > $thumbnail_width) {
					$width = $thumbnail_width;
					$height = ($imgsize1[1] / $imgsize1[0]) * $thumbnail_width;
				} else {
					$width = $imgsize1[0];
					$height = $imgsize1[1];
				}
			}
		?>
		<a href="javascript:imgview('<?php echo MS_DATA_URL; ?>/board/<?php echo $boardid; ?>/<?php echo $bo_file2; ?>');"><img src="<?php echo MS_DATA_URL; ?>/board/<?php echo $boardid; ?>/<?php echo $bo_file2; ?>" width="200" height="200"></a><br><br><br>
		<?php
		}

		echo get_view_thumbnail(conv_content($bo_memo, 1), $thumbnail_width);
		?>
		</td>
	</tr>
	<tr>
		<th>내부시설</th>
		<td Colspan="3"><b><?php echo $write['facilities1']; ?><?php if($write['facilities2']){ echo ","; }?><?php echo $write['facilities2']; ?><?php if($write['facilities3']){ echo ","; }?><?php echo $write['facilities3']; ?><?php if($write['facilities4']){ echo ","; }?><?php echo $write['facilities4']; ?><?php if($write['facilities5']){ echo ","; }?><?php echo $write['facilities5']; ?><?php if($write['facilities6']){ echo ","; }?><?php echo $write['facilities6']; ?><?php if($write['facilities7']){ echo ","; }?><?php echo $write['facilities7']; ?><?php if($write['facilities8']){ echo ","; }?><?php echo $write['facilities8']; ?><?php if($write['facilities9']){ echo ","; }?><?php echo $write['facilities9']; ?><?php if($write['facilities10']){ echo ","; }?><?php echo $write['facilities10']; ?><?php if($write['facilities11']){ echo ","; }?><?php echo $write['facilities11']; ?><?php if($write['facilities12']){ echo ","; }?><?php echo $write['facilities12']; ?><?php if($write['facilities13']){ echo ","; }?><?php echo $write['facilities13']; ?><?php if($write['facilities14']){ echo ","; }?><?php echo $write['facilities14']; ?><?php if($write['facilities15']){ echo ","; }?><?php echo $write['facilities15']; ?><?php if($write['facilities16']){ echo ","; }?><?php echo $write['facilities16']; ?><?php if($write['facilities17']){ echo ","; }?><?php echo $write['facilities17']; ?><?php if($write['facilities18']){ echo ","; }?><?php echo $write['facilities18']; ?><?php if($write['facilities19']){ echo ","; }?><?php echo $write['facilities19']; ?><?php if($write['facilities20']){ echo ","; }?><?php echo $write['facilities20']; ?></b></td>
	</tr>
	<tr>
		<th>보안 및 기타시설</th>
		<td Colspan="3"><b><?php echo $write['security1']; ?><?php if($write['security2']){ echo ","; }?><?php echo $write['security2']; ?><?php if($write['security3']){ echo ","; }?><?php echo $write['security3']; ?><?php if($write['security4']){ echo ","; }?><?php echo $write['security4']; ?><?php if($write['security5']){ echo ","; }?><?php echo $write['security5']; ?><?php if($write['security6']){ echo ","; }?><?php echo $write['security6']; ?><?php if($write['security7']){ echo ","; }?><?php echo $write['security7']; ?><?php if($write['security8']){ echo ","; }?><?php echo $write['security8']; ?><?php if($write['security9']){ echo ","; }?><?php echo $write['security9']; ?><?php if($write['security10']){ echo ","; }?><?php echo $write['security10']; ?><?php if($write['security11']){ echo ","; }?><?php echo $write['security11']; ?><?php if($write['security12']){ echo ","; }?><?php echo $write['security12']; ?><?php if($write['security13']){ echo ","; }?><?php echo $write['security13']; ?><?php if($write['security14']){ echo ","; }?><?php echo $write['security14']; ?></b></td>
	</tr>
	</tbody>
	</table>
</div>
<div class="page_wrap">
	<div class="lbt_box">
		<a href="./list.php?<?php echo $qstr1; ?>" class="btn_lsmall bx-white">목록</a>
	</div>
	<div class="rbt_box">
		<?php if(($member['index_no'] == $bo_writer) || is_admin()) { ?>
		<a href="./modify.php?<?php echo $qstr2; ?>" class="btn_lsmall bx-white">수정</a>
		<?php } ?>
		<?php if($member['index_no'] && $member['grade']<=$board['reply_priv'] && $board['usereply']=='Y') { ?>
		<a href="./reply.php?<?php echo $qstr2; ?>" class="btn_lsmall bx-white">답글</a>
		<?php } ?>
		<?php if(($member['index_no'] == $bo_writer) || is_admin()) { ?>
		<a href="./del.php?<?php echo $qstr2; ?>" class="btn_lsmall bx-white">삭제</a>
		<?php } ?>
		<?php if($member['grade'] <= $board['write_priv']){ ?>
		<a href="./write.php?boardid=<?php echo $boardid; ?>" class="btn_lsmall">글쓰기</a>
		<?php } ?>
	</div>
</div>

<!--코멘트 출력부분-->
<?php if($board['usetail']=='Y') { ?>
<form name="fboardform" id="fboardform" method="post" action="<?php echo $from_action_url; ?>" onsubmit="return fboardform_submit(this);">
<input type="hidden" name="mode" value="w">
<input type="hidden" name="index_no" value="<?php echo $index_no; ?>">
<input type="hidden" name="boardid" value="<?php echo $boardid; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<?php
$sql = "select * from shop_board_{$boardid}_tail where board_index='$index_no' order by wdate asc";
$res = sql_query($sql);
if(sql_num_rows($res)) {
?>
<div class="tbl_frm02 tbl_wrap marb10">
	<table>
	<tbody>
	<?php
	while($row=sql_fetch_array($res)) {
		$bo_wdate = date("Y-m-d H:i:s",$row['wdate']);
	?>
	<tr class="list1">
		<td>작성자 : <b><?php echo $row['writer_s']; ?></b> (<?php echo $bo_wdate; ?>) <?php echo "<a href=\"./tail_del.php?tailindex={$row['index_no']}&{$qstr2}\" class=\"btn_ssmall bx-white\">삭제</a>"; ?></td>
	</tr>
	<tr>
		<td><?php echo conv_content($row['memo'], 0); ?></td>
	</tr>
	<?php } ?>
	</tbody>
	</table>
</div>
<?php } ?>

<?php if($is_member) { ?>
<table class="wfull bd">
<tr height="80" class="list1">
	<td width="9%" class="tac bold"><?php echo $member['name']; ?></td>
	<td width="81%"><textarea name="memo" class="frm_textbox h60"></textarea></td>
	<td class="tar padr10 padl10">
		<?php if($member['grade'] > $board['tail_priv']) { ?>
		<input type="button" onclick="alert('댓글을 작성할 권한이 없습니다.');" value="댓글입력" class="btn_medium grey h60">
		<?php } else {  ?>
		<input type="hidden" name="writer_s" value="<?php echo $member['name']; ?>">
		<input type="submit" value="댓글입력" class="btn_medium grey h60">
		<?php } ?>
	</td>
</tr>
</table>
<?php
} else {
	if($board['tail_priv'] == '99') {
?>
<div class="tbl_frm01 tbl_wrap">
	<table>
	<tr>
		<td colspan="2">
			작성자 : <input type="text" name="writer_s" class="frm_input marr15" size="20">
			비밀번호 : <input type="password" name="passwd" class="frm_input" size="20">
		</td>
	</tr>
	<tr class="list1">
		<td width="90%"style="padding:10px 0 10px 10px"><textarea name="memo" class="frm_textbox h60"></textarea></td>
		<td class="tar padr10 padl10"><input type="submit" value="댓글입력" class="btn_medium grey h60"></td>
	</tr>
	</table>
</div>
<?php } else { ?>
<table class="wfull bd">
<tr height="80" class="list1">
	<td width="9%" class="tac bold"><?php echo $bo_writer_s; ?></td>
	<td width="81%"><textarea name="memo" class="frm_textbox h60"></textarea></td>
	<td class="tar padr10 padl10"><input type="button" onclick="alert('로그인후 댓글을 작성 가능합니다.');" value="댓글입력" class="btn_medium grey h60"></td>
</tr>
</table>
<?php }
}
?>
</form>
<?php } ?>

<script>
function fboardform_submit(f)
{
	<?php if(!$is_member) { ?>
	if(!f.writer_s.value) {
		alert('작성자명을 입력하세요.');
		f.writer_s.focus();
		return false;
	}
	if(!f.passwd.value) {
		alert('비밀번호를 입력하세요.');
		f.passwd.focus();
		return false;
	}
	<?php } ?>

	if(!f.memo.value) {
		alert('댓글을 작성하지 않았습니다!');
		f.memo.focus();
		return false;
	}

	return true;
}

function imgview(img) {
	 window.open("imgviewer.php?img="+img,"img",'width=150,height=150,status=no,top=0,left=0,scrollbars=yes');
}
</script>

<?php
include_once(MS_BBS_PATH."/skin/{$board['list_skin']}/read_list.php");
?>