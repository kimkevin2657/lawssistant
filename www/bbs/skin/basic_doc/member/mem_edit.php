<?php
include_once("./_common.php");
include_once(G5_PATH."/head.sub.php");
include_once("/home/pulo/www/bbs/skin/basic_doc/skin.function.php");
$Search_box = new Frm_search(); // 검색폼 관련 Class

// 전자결재시 필수 변수값 체크
// 항상 list.skin.php 파일을 통해서 접속해야 함.
if(!$_SESSION['app_mb_info']) {
    die();
} else {
    $member = $_SESSION['app_mb_info'];
}

// 직원으로 등록되어 있는지 체크
checkStaff($member['id']);

if(!$bo_table) {
	alert("정상적인 접근이 아닙니다.");
}

if($mode) {

	if(!$is_admin) {
		alert('직원 등록 및 정보 수정 권한이 없습니다.');
	}

	switch($mode){

		case "save":
			## 사원정보 수정
			$sql = "update
						{$write_table}_member
					set
							mb_section	= '".$_POST['mb_section']."',
							mb_kind		= '".$_POST['mb_kind']."',
							mb_id		= '".$_POST['mb_id']."',
							mb_position	= '".$_POST['mb_position']."',
							mb_birth	= '".$_POST['mb_birth']."',
							mb_name		= '".$_POST['mb_name']."',
							mb_tel		= '".$_POST['mb_tel']."',
							mb_hp		= '".$_POST['mb_hp']."',
							mb_fax		= '".$_POST['mb_fax']."',
							mb_email	= '".$_POST['mb_email']."',
							join_date	= '".$_POST['join_date']."',
							retire_date	= '".$_POST['retire_date']."',
							mb_status	= '".$_POST['mb_status']."',
							mb_zip		= '".$_POST['mb_zip']."',
							mb_addr1	= '".$_POST['mb_addr1']."',
							mb_addr2	= '".$_POST['mb_addr2']."',
							mb_addr3	= '".$_POST['mb_addr3']."',
							car_no		= '".$_POST['car_no']."',
							car_name	= '".$_POST['car_name']."',
							car_holiday	= '".$_POST['car_holiday']."',
							mb_area		= '".$_POST['mb_area']."',
							mb_content	= '".$_POST['mb_content']."'
						where
							id_no = '".$_POST['id_no']."' ";
			sql_query($sql, true);
			goto_url("?bo_table={$bo_table}&id_no={$id_no}");
			break;

		case "new":
			// 신규 사원 등록.
			$sql = "insert into
						{$write_table}_member
					set
							mb_section	= '".$_POST['mb_section']."',
							mb_id		= '".$_POST['mb_id']."',
							mb_kind		= '".$_POST['mb_kind']."',
							mb_position	= '".$_POST['mb_position']."',
							mb_birth	= '".$_POST['mb_birth']."',
							mb_name		= '".$_POST['mb_name']."',
							mb_tel		= '".$_POST['mb_tel']."',
							mb_hp		= '".$_POST['mb_hp']."',
							mb_fax		= '".$_POST['mb_fax']."',
							join_date	= '".$_POST['join_date']."',
							retire_date	= '".$_POST['retire_date']."',
							mb_status	= '".$_POST['mb_status']."',
							mb_email	= '".$_POST['mb_email']."',
							mb_zip		= '".$_POST['mb_zip']."',
							mb_addr1	= '".$_POST['mb_addr1']."',
							mb_addr2	= '".$_POST['mb_addr2']."',
							mb_addr3	= '".$_POST['mb_addr3']."',
							car_no		= '".$_POST['car_no']."',
							car_name	= '".$_POST['car_name']."',
							car_holiday	= '".$_POST['car_holiday']."',
							mb_area		= '".$_POST['mb_area']."',
							mb_content	= '".$_POST['mb_content']."'
					";
			sql_query($sql);
			$id_no = sql_insert_id();
			goto_url("?bo_table={$bo_table}&id_no={$id_no}");
			break;

		case "delete":
			$sql = "delete
					from
						{$write_table}_member
					where
						id_no = '$id_no' ";
			sql_query($sql);
			echo "<script>alert('자료가 삭제되었습니다.'); location.href='?bo_table={$bo_table}&pg=member'; </script>";
			break;
	}
}

if($id_no) {
	$rs = get_memInfo($id_no);
} else {
	if(!$is_admin) {
		die("접근 권한이 없습니다. 관리자에게 문의 바랍니다.");
	}
}

// 직원 부서
$mb_kind = get_teamlist();
$mb_kind['input_text'] = "[ 직접입력 ]";
unset($mb_kind["전체공개"]);



add_javascript(G5_POSTCODE_JS, 0);
include_once(G5_PLUGIN_PATH.'/jquery-ui/datepicker.php'); // 달력(한글로 출력)
?>
<script src="https://blingbeauty.shop/bbs/skin/basic_doc/js/doc.js"></script>
<link rel="stylesheet" href="https://blingbeauty.shop/bbs/skin/basic_doc/style.css">
<script>
$(document).ready(function(){

	/* 달력 */
	$(".mb_birth, .join_date, .retire_date").datepicker({ 
		numberOfMonths: 1,
		changeMonth: true,
		changeYear: true,
		dateFormat: "yy-mm-dd",
		showButtonPanel: true,
		yearRange: "c-30:c+20",
	});

	var idno = $(".mode").val();
	
	if(idno == 'new') { // 신규등록이면 취소 버튼을 숨긴다.
		$(":button:contains('삭제')", parent.document).hide();
	} else {
		$(":button:contains('삭제')", parent.document).show();
	}

	$("#mb_kind").change(function() {
		//var val = $(this).val();
		select_in($(this).val());
	});
});
</script>

<div id="mem_edit">
	
	<form name="fmData"  method="post" action="./mem_edit.php" onsubmit="return fmData_check(this);">
	<input type="hidden" name="mode" class="mode" value='<?php echo ($id_no)? "save":"new"; ?>' />
	<input type="hidden" name="section" class="section" value='<?php echo $section; ?>' />
	<input type="hidden" name="id_no" id="id_no" value="<?php echo $id_no?>" />
	<input type="hidden" name="mb_id" value="<?php echo ($rs['mb_id']) ? $rs['mb_id'] : $member['id']; ?>" />
	<input type="hidden" name="bo_table" value="<?php echo $bo_table ?>" />

	<table>
		<colgroup>
			<col width="12%">
			<col width="21%">
			<col width="12%">
			<col width="21%">
			<col width="12%">
			<col width="22%">
		</colgroup>
		<tr>
			<th>구분</th>
			<td colspan="5">
				&nbsp;&nbsp;&nbsp;
				<?php
				$Search_box->var_mode('A', $MB_SECTION);
				echo $Search_box->radio('= 담당구분 =', 'mb_section', 'mb_section', 'mb_section', $rs['mb_section']);
				?>		
			</td>
		</tr>
		<tr>
			<th>이름</th>
			<td><input type="text" name="mb_name" class="mb_name" value="<?php echo $rs['mb_name'];?>" required/></td>
			<th>회원아이디</th>
			<td><input type="text" name="mb_id" class="mb_id" value="<?php echo $rs['mb_id']; ?>"/></td>
			<th></th>
			<td></td>
		</tr>
		<tr>
			<th>부서명</th>
			<td>
				<div id="mb_kind_zone">
					<?php
					$Search_box->var_mode('A', $mb_kind);
					echo $Search_box->Select('= 부서명 =', 'mb_kind', 'mb_kind', 'mb_kind', $rs['mb_kind']);
					?>
				</div>
			</td>
			<th>직책(직위)</th>
			<td><input type="text" name="mb_position" class="mb_position" value="<?php echo $rs['mb_position']; ?>"/></td>
			<th>이메일</th>
			<td><input type="text" name="mb_email" class="mb_email" value="<?php echo $rs['mb_email'];?>"/></td>
		</tr>
		<tr>
			<th>일반전화</th>
			<td><input type="text" name="mb_tel" class="mb_tel" value="<?php echo $rs['mb_tel'];?>"/></td>
			<th>휴대전화</th>
			<td><input type="text" name="mb_hp" class="mb_hp" value="<?php echo $rs['mb_hp'];?>"/></td>
			<th>팩스</th>
			<td><input type="text" name="mb_fax" class="mb_fax" value="<?php echo $rs['mb_fax']; ?>"/></td>
		</tr>
		<tr>
			<th>입사일</th>
			<td><input type="text" name="join_date" class="join_date" value="<?php echo $rs['join_date']; ?>"/></td>
			<th>퇴사일</th>
			<td><input type="text" name="retire_date" class="retire_date" value="<?php echo $rs['retire_date'];?>"/></td>
			<th>현재상태</th>
			<td>
				<select name="mb_status" id="mb_status" class="mb_status" itemname="현재상태">
					<?php echo xoption_str("= 선 택 =|근무|퇴사|휴직","|근무|퇴사|휴직",$rs['mb_status']);?>
				</select>
			</td>
		</tr>
		<tr>
			<th>차량번호</th>
			<td><input type="text" name="car_no" class="car_no" value="<?php echo $rs['car_no']?>"/></td>
			<th>차량명칭</th>
			<td><input type="text" name="car_name" class="car_name" value="<?php echo $rs['car_name']?>"/></td>
			<th>생년월일</th>
			<td><input type="text" name="mb_birth" class="mb_birth" value="<?php echo $rs['mb_birth'];?>"/></td>

		</tr>
		<tr>
			<th>주소</th>
			<td colspan="5" class="address">
		            <label for="reg_mb_zip" class="sound_only">우편번호</label>
			        <input type="text" name="mb_zip" value="<?php echo $rs['mb_zip']?>" id="reg_zip" maxlength="6"/>
				    <button type="button" onclick="win_zip('fmData', 'mb_zip', 'mb_addr1', 'mb_addr2', 'mb_addr3', 'mb_addr_jibeon');">주소 검색</button><br>
					<input type="text" name="mb_addr1" value="<?php echo $rs['mb_addr1']; ?>" id="reg_addr1"/>
	                <label for="reg_addr1">기본주소 <strong class="sound_only"> 필수</strong></label><br>
		            <input type="text" name="mb_addr2" value="<?php echo $rs['mb_addr2']; ?>" id="reg_addr2"/>
			        <label for="reg_addr2">상세주소</label><br>
	                <input type="text" name="mb_addr3" value="<?php echo $rs['mb_addr3']; ?>" id="reg_addr3" readonly="readonly"/>
	                <label for="reg_addr3">참고항목</label>
		            <input type="hidden" name="jibeon" value="<?php echo $rs['jibeon']; ?>"/>
			</td>
		</tr>
		<tr>
			<th>메모</th>
			<td colspan="5">
				<textarea name="mb_content" class="mb_content" rows="5"><?php echo $rs['mb_content']; ?></textarea>
			</td>
		</tr>
	</table>
	<div class="bottom_msg">
	</div>

	<div class="button_zone">
		<button type="button" name="save" id="save" onclick="submit_mode('save');">저장</button>
		<button type="button" name="save" id="delete" onclick="submit_mode('delete');">삭제</button>
		<button type="button" name="cancel" id="cancel">취소</button>
	</div>
	</form>
</div>

<iframe name="ifm_chk" style="display:none;"></iframe>

<script language='Javascript'>
function submit_mode(mode) {
	var f = document.fmData;

	// 부서구분
	if(f.mb_kind.value == '') {
		alert("[부서명, 담당자명]은 필수 입력항목입니다.");
		f.mb_kind.focus();
		$(".mb_kind").css("background","#FFFF00");
		return false;
	}

	// 담당자
	if(f.mb_name.value == '') {
		alert("[부서명, 담당자명]은 필수 입력항목입니다.");
		f.mb_name.focus();
		$(".mb_name").css("background","#FFFF00");
		return false;
	}

	if(mode=='save') {
			
		f.action = "./mem_edit.php";
		f.submit();

	} else if(mode=='delete') {
		
		if(confirm("한번 삭제된 자료는 복구가 불가능합니다.\n\n그래도 삭제를 하시겠습니까?")) {
			f.mode.value="delete";
			f.action = "./mem_edit.php";
			f.submit();
		} else {
			return false;
		}
	}
}
</script>
<?php include_once(G5_PATH."/tail.sub.php"); ?>