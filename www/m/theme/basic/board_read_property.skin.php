<?php
if(!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가
?>

<div class="m_bo_bg mart10">
	<div class="title"><?php echo $bo_subject; ?></div>
	<div class="title">등록자정보 : <?php echo $write['w_info']; ?></div>
	<div class="title">매물특징 : <?php echo $write['features']; ?> | 준공연도 : <?php echo $write['whenbuild']; ?></div>
	<div class="title">주소 : <?php echo $write['b_addr1']; ?>&nbsp;<?php echo $write['b_addr2']; ?></div>
	<div class="title">거래형태 : <?php echo $write['transaction_type']; ?></div>
<?php if($write['transaction_type'] == "전세"){ ?>
	<div class="title">전세보증금 : <?php echo $write['deposit_lease']; ?></div>
<?php }elseif($write['transaction_type'] == "월세"){ ?>
	<div class="title">보증금 : <?php echo $write['mon_rent_de']; ?>&nbsp;월세 : <?php echo $write['mon_rent']; ?></div>
<?php }elseif($write['transaction_type'] == "단기"){ ?>
	<div class="title">보증금 : <?php echo $write['short_rent_de']; ?>&nbsp;월세 : <?php echo $write['short_rent']; ?></div>
<?php }elseif($write['transaction_type'] == "매매"){ ?>
	<div class="title">매매 : <?php echo $write['dealing']; ?></div>
<?php } ?>
	<div class="title">관리비 : <?php if($write['expenses_c'] == "없음"){ echo "없음"; }else{ echo number_format($write['expenses'])."원"; } ?></div>
	<div class="title">공용관리비포함내역 : <?php echo $write['expenses_a1']; ?><?php if($write['expenses_a2']){ echo ","; }?><?php echo $write['expenses_a2']; ?><?php if($write['expenses_a3']){ echo ","; }?><?php echo $write['expenses_a3']; ?><?php if($write['expenses_a4']){ echo ","; }?><?php echo $write['expenses_a4']; ?><?php if($write['expenses_a5']){ echo ","; }?><?php echo $write['expenses_a5']; ?><?php if($write['expenses_a6']){ echo ","; }?><?php echo $write['expenses_a6']; ?></div>
	<div class="title">개별사용료 : <?php echo $write['expenses_b']; ?></div>
	<div class="title">융자여부 : <?php echo $write['loan_a']; ?></div>
	<div class="title">융자금 : <?php echo $write['loan']; ?></div>
	<div class="title">계약/전용면적 : <?php echo $write['contract']; ?></div>
	<div class="title">해당층/총층 : <?php echo $write['floor']; ?></div>
	<div class="title">방수/욕실수 : <?php echo $write['room']; ?></div>
	<div class="title">용도 : <?php echo $write['purpose']; ?></div>
	<div class="title">방구조 : <?php echo $write['structure']; ?></div>
	<div class="title">복층여부 : <?php echo $write['double_c']; ?></div>
	<div class="title">현관구조 : <?php echo $write['entrance']; ?></div>
	<div class="title">주실방향기준 : <?php echo $write['direction_a']; ?></div>
	<div class="title">추가옵션 : <?php echo $write['add_option1']; ?><?php if($write['add_option2']){ echo ","; }?><?php echo $write['add_option2']; ?><?php if($write['add_option3']){ echo ","; }?><?php echo $write['add_option3']; ?><?php if($write['add_option4']){ echo ","; }?><?php echo $write['add_option4']; ?><?php if($write['add_option5']){ echo ","; }?><?php echo $write['add_option5']; ?><?php if($write['add_option6']){ echo ","; }?><?php echo $write['add_option6']; ?><?php if($write['add_option7']){ echo ","; }?><?php echo $write['add_option7']; ?></div>
	<div class="title">방향 : <?php echo $write['direction']; ?></div>
	<div class="title">입주가능일 : <?php echo $write['come_date']; ?></div>
	<div class="title">총주차대수 : <?php echo $write['parking']; ?></div>
	<div class="title">해당면적 세대수 : <?php echo $write['households']; ?></div>
	<div class="title">난방(방식/연료) : <?php echo $write['heating']; ?></div>
	<div class="title">에어컨 : <?php echo $write['air_conditioner']; ?></div>
	<div class="title">매물번호 : <?php echo $write['index_no']; ?></div>
	<div class="wr_name"><?php echo $write['writer_s']; ?><span class="wr_day"><?php echo $bo_wdate; ?></span></div>
	<div class="wr_txt">
		<?php
		$file1 = MS_DATA_PATH."/board/{$boardid}/{$write['fileurl1']}";
		if(is_file($file1) && preg_match("/\.(gif|jpg|jpeg|png)$/i", $write['fileurl1'])) {
			$file1 = rpc($file1, MS_PATH, MS_URL);
		?>
		<img src="<?php echo $file1; ?>" class="img_fix">
		<?php } ?>
		<?php
		$file2 = MS_DATA_PATH."/board/{$boardid}/{$write['fileurl2']}";
		if(is_file($file2) && preg_match("/\.(gif|jpg|jpeg|png)$/i", $write['fileurl2'])) {
			$file2 = rpc($file2, MS_PATH, MS_URL);
		?>
		<img src="<?php echo $file2; ?>" class="img_fix">
		<?php } ?>
    <div class="view_movie"><iframe src="<?=$write['etc_2']?>" frameborder="0" allowfullscreen></iframe></div>
    <br/>
		<p><?php echo get_image_resize($write['memo']); ?></p>
	</div>
	<div class="title">내부시설 : <?php echo $write['facilities1']; ?><?php if($write['facilities2']){ echo ","; }?><?php echo $write['facilities2']; ?><?php if($write['facilities3']){ echo ","; }?><?php echo $write['facilities3']; ?><?php if($write['facilities4']){ echo ","; }?><?php echo $write['facilities4']; ?><?php if($write['facilities5']){ echo ","; }?><?php echo $write['facilities5']; ?><?php if($write['facilities6']){ echo ","; }?><?php echo $write['facilities6']; ?><?php if($write['facilities7']){ echo ","; }?><?php echo $write['facilities7']; ?><?php if($write['facilities8']){ echo ","; }?><?php echo $write['facilities8']; ?><?php if($write['facilities9']){ echo ","; }?><?php echo $write['facilities9']; ?><?php if($write['facilities10']){ echo ","; }?><?php echo $write['facilities10']; ?><?php if($write['facilities11']){ echo ","; }?><?php echo $write['facilities11']; ?><?php if($write['facilities12']){ echo ","; }?><?php echo $write['facilities12']; ?><?php if($write['facilities13']){ echo ","; }?><?php echo $write['facilities13']; ?><?php if($write['facilities14']){ echo ","; }?><?php echo $write['facilities14']; ?><?php if($write['facilities15']){ echo ","; }?><?php echo $write['facilities15']; ?><?php if($write['facilities16']){ echo ","; }?><?php echo $write['facilities16']; ?><?php if($write['facilities17']){ echo ","; }?><?php echo $write['facilities17']; ?><?php if($write['facilities18']){ echo ","; }?><?php echo $write['facilities18']; ?><?php if($write['facilities19']){ echo ","; }?><?php echo $write['facilities19']; ?><?php if($write['facilities20']){ echo ","; }?><?php echo $write['facilities20']; ?></div>
	<div class="title">보안 및 기타시설 : <?php echo $write['security1']; ?><?php if($write['security2']){ echo ","; }?><?php echo $write['security2']; ?><?php if($write['security3']){ echo ","; }?><?php echo $write['security3']; ?><?php if($write['security4']){ echo ","; }?><?php echo $write['security4']; ?><?php if($write['security5']){ echo ","; }?><?php echo $write['security5']; ?><?php if($write['security6']){ echo ","; }?><?php echo $write['security6']; ?><?php if($write['security7']){ echo ","; }?><?php echo $write['security7']; ?><?php if($write['security8']){ echo ","; }?><?php echo $write['security8']; ?><?php if($write['security9']){ echo ","; }?><?php echo $write['security9']; ?><?php if($write['security10']){ echo ","; }?><?php echo $write['security10']; ?><?php if($write['security11']){ echo ","; }?><?php echo $write['security11']; ?><?php if($write['security12']){ echo ","; }?><?php echo $write['security12']; ?><?php if($write['security13']){ echo ","; }?><?php echo $write['security13']; ?><?php if($write['security14']){ echo ","; }?><?php echo $write['security14']; ?></div>
</div>

<div class="btn_confirm">
	<a href="<?php echo MS_MBBS_URL; ?>/board_list.php?<?php echo $qstr1;?>" class="btn_medium bx-white">목록</a>
	<?php if($member['grade']<=$board['reply_priv'] && $board['usereply']=='Y') { ?>
	<a href="<?php echo MS_MBBS_URL; ?>/board_write.php?<?php echo $qstr2;?>&w=r" class="btn_medium bx-white">답변</a>
	<?php } if(($mb_no == $write['writer']) || is_admin()) { ?>
	<a href="<?php echo MS_MBBS_URL; ?>/board_write.php?<?php echo $qstr2;?>&w=u" class="btn_medium bx-white">수정</a>
	<a href="<?php echo MS_MBBS_URL; ?>/board_delete.php?<?php echo $qstr2;?>" class="btn_medium bx-white">삭제</a>
	<?php } ?>
</div>
