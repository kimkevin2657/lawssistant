<?php
$sub_menu = "600100";
include_once('./_common.php');

//auth_check($sub_menu);

if ($w == 'u'){
	$row = sql_fetch("select * from push_data where pu_id = '$pu_id'");
}

$g5['title'] .= 'Push 등록/수정';
include_once(G5_ADMIN_PATH.'/admin.head.php');
?>

<form name="fpushs" id="fpushs" action="./push_send_form_update.php" onsubmit="return fpushs_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="pu_id" value="<?=$row['pu_id']?>">

<div class="adm_tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">제목</th>
        <td><?=$row['pu_subject']?></td>
    </tr>
    <tr>
        <th scope="row">내용</th>
        <td><?=nl2br($row['pu_content'])?></td>
    </tr>
	<tr>
        <th scope="row">링크</th>
        <td><?=$row['pu_url']?></td>
    </tr>
    <!--
	<tr>
		<th>타겟</th>
		<td>		
			<p>
				지역: 
				<?
					$area = '서울|부산|대구|인천|대전|울산|광주|세종|경기|경남|경북|전남|전북|충남|충북|강원|제주';
					$area_arr = explode("|", $area);
					foreach($area_arr as $k=>$v)
						echo "<input type=\"checkbox\" id=\"area_".$k."\" name=\"pu_area[]\" value=\"".$v."\" checked=\"checked\" /><label for=\"area_".$k."\">".$v."</label>\n";
				?>
			</p>
			
			<p>
				성별: 
				<input type="checkbox" id="sex_2" name="pu_sex[]" value="M" checked="checked" /><label for="sex_2">남성</label>
				<input type="checkbox" id="sex_3" name="pu_sex[]" value="F" checked="checked" /><label for="sex_3">여성</label>
			</p>

			<p>
				연령대: 
				<input type="checkbox" id="age_1" name="pu_age[]" value="10" checked="checked" /><label for="age_1">10대</label>
				<input type="checkbox" id="age_2" name="pu_age[]" value="20" checked="checked" /><label for="age_2">20대</label>
				<input type="checkbox" id="age_3" name="pu_age[]" value="30" checked="checked" /><label for="age_3">30대</label>
				<input type="checkbox" id="age_4" name="pu_age[]" value="40" checked="checked" /><label for="age_4">40대 이상</label>
			</p>
		</td>
	</tr>
	-->
    </tbody>
    </table>
</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="발송" class="btn_submit" accesskey='s' style="height:30px;">
    <a href="./push_list.php?<?php echo $qstr ?>">목록</a>
</div>
</form>

<script>
function fpushs_submit(f)
{
    /*
	var count = 0;
	for(i = 0; i < $("input[name='pu_area[]']").length; i++){
		if($("input[name='pu_area[]']").is(":checked"))
			count++;
	}

	for(i = 0; i < $("input[name='pu_sex[]']").length; i++){
		if($("input[name='pu_sex[]']").is(":checked"))
			count++;
	}

	for(i = 0; i < $("input[name='pu_age[]']").length; i++){
		if($("input[name='pu_age[]']").is(":checked"))
			count++;
	}

	if(count == 0){
		alert("타겟을 선택해 주세요");
		return false;
	}
    */
    return true;
}
</script>

<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
