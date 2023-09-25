<?php
$sub_menu = "600100";
include_once('./_common.php');

//auth_check($sub_menu);

$pu_id = $_GET['pu_id'];
$w = $_GET['w'];

if ($w == 'u'){
	$row = sql_fetch("select * from push_data where pu_id = '$pu_id'");
}

$g5['title'] .= 'Push 등록/수정';
include_once(G5_ADMIN_PATH.'/admin.head.php');
?>

<form name="fpush" id="fpush" action="<? echo MS_PUSH_URL; ?>/push_index.php?code=push_send_form_update" onsubmit="return fpush_submit(this);" method="post" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="sst" value="<?php echo $sst ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">
<input type="hidden" name="token" value="">
<input type="hidden" name="pu_id" value="<?=$row['pu_id']?>">
<input type="hidden" name="set_lang" id="set_lang" value="<? echo $row['set_lang']?>">
<input type="hidden" name="set_lng" id="set_lng" value="<? echo $row['set_lng']?>">

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">지역</th>
        <td>
        <!-- <input type="text" id="sample6_postcode" placeholder="우편번호"> -->
        <input type="button" onclick="sample6_execDaumPostcode()" value="주소 찾기">
        <input type="text" id="sample6_address" name="set_addr" placeholder="주소" style="width:30%;" value="<? echo $row['set_addr']; ?>">
        <span class="setAddr btn btn_01" style="padding:5px; background:#333;color:white;cursor:pointer;">좌표설정</span>
<!--         <input type="text" id="sample6_detailAddress" placeholder="상세주소"> -->
        <!-- <select name="sido" title="지역" class="it_select" required>
            <option value="">도/시</option>
            <?
                $sql = "select distinct(sido) from area order by sido";
                $rs = sql_query($sql);
                while($row2 = sql_fetch_array($rs))
                    echo '<option value="'.$row2['sido'].'" '.($row['pu_sido'] == $row2['sido'] ? 'selected="selected"' : '').'>'.$row2['sido'].'</option>'.PHP_EOL;
            ?>
        </select>
        <select name="gugun" title="지역" class="it_select" required>
            <option value="">구/군</option>
            <?
                if($row['pu_sido'] != ''){
                    $sql = "select gugun from area where sido = '{$row['pu_sido']}' group by gugun";
                    $rs = sql_query($sql);
                    while($row2 = sql_fetch_array($rs))
                        echo '<option value="'.$row2['gugun'].'" '.($row['pu_gugun'] == $row2['gugun'] ? 'selected="selected"' : '').'>'.$row2['gugun'].'</option>'.PHP_EOL;
                }
            ?>
        </select> -->
        </td>
    </tr>

    <tr>
        <th scope="row">회원레벨</th>
        <td colspan="3">
            <?php echo get_member_select("pu_grade", $row['pu_grade']); ?>
            <select name="category_name">
				<option value="">전체</option>
				<?
					$sql2 = sql_query("SELECT * FROM category_manage");
					while($row2 = sql_fetch_array($sql2)){
				?>
				<option value="<? echo $row2['category_name']; ?>" <? echo $row2['category_name'] == $bn['category_name'] ? "selected":""; ?> ><? echo $row2['category_name']; ?></option>
				<? } ?>
			</select>
        </td>
    </tr>
    <tr>
        <th scope="row">성별</th>
        <td>
            <select name="pu_sex">
                <option value="" <? echo $row['pu_sex'] == "" ? "selected":""; ?> >선택</option>
                <option value="M" <? echo $row['pu_sex'] == "M" ? "selected":""; ?> >남성</option>
                <option value="F" <? echo $row['pu_sex'] == "F" ? "selected":""; ?> >여성</option>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row">나이별</th>
        <td>
            <select name="pu_age">
                <option value="">선택</option>
                <option value="10" <? echo $row['pu_age'] == "10" ? "selected":""; ?> >10대</option>
                <option value="20" <? echo $row['pu_age'] == "20" ? "selected":""; ?> >20대</option>
                <option value="30" <? echo $row['pu_age'] == "30" ? "selected":""; ?> >30대</option>
                <optione value="40" <? echo $row['pu_age'] == "40" ? "selected":""; ?> >40대</optione>
                <option value="50" <? echo $row['pu_age'] == "50" ? "selected":""; ?> >50대</option>
                <option value="60" <? echo $row['pu_age'] == "60" ? "selected":""; ?> >60대</option>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row">거리별</th>
        <td>
            <select name="set_meter">
                <option value="">선택</option>
                <option value="1" <? echo $row['set_meter'] == "1" ? "selected":""; ?> >1km</option>
                <option value="5" <? echo $row['set_meter'] == "5" ? "selected":""; ?> >5km</option>
                <option value="10" <? echo $row['set_meter'] == "10" ? "selected":""; ?> >10km</option>
                <optione value="20" <? echo $row['set_meter'] == "20" ? "selected":""; ?> >20km</optione>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row">제목</th>
        <td><input type="text" name="pu_subject" class="frm_input" value="<?=$row['pu_subject']?>" size="50" /></td>
    </tr>
    <tr>
        <th scope="row">내용</th>
        <td><textarea name="pu_content" class="frm_input" style="height: 60px;"><?=$row['pu_content']?></textarea></td>
    </tr>
    <tr>
        <th scope="row">링크</th>
        <td><input type="text" name="pu_link" class="frm_input" value="<?=$row['pu_link']?>" size="50" /></td>
    </tr>
    </tbody>
    </table>
</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey='s' style="height:30px;">
    <a href="./push_index.php?code=push_list">목록</a>
</div>
</form>

<script>
$(function(){
    $("select[name='sido']").change(function() {
        $.getJSON("<?=MS_BBS_URL?>/json.get_next_addr.php?sido="+encodeURIComponent($("select[name='sido']").val()), function(r){
            $("select[name='gugun']").find("option").not(":eq(0)").remove();
            $(r.option).insertAfter($("select[name='gugun']").find("option:last"));
        });
    });
})

function fpush_submit(f)
{
	if(f.pu_subject.value == ''){
		alert("제목을 입력하세요.");
		f.pu_subject.focus();
	    return false;
	}

	if(f.pu_content.value == ''){
		alert("내용을 입력하세요.");
		f.pu_content.focus();
	    return false;
	}

    return true;
}
</script>
<script type="text/javascript" src="//dapi.kakao.com/v2/maps/sdk.js?appkey=ac4ff377303ec171373bc69bc9a30030&libraries=services"></script>
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<script>

    $(".setAddr").on("click", function(){
        addr = $("#sample6_address").val();
        if(addr == ""){
            alert("설정된 주소가 존재하지 않습니다.");
            return false;
        }else{
            var geocoder = new kakao.maps.services.Geocoder();

            // 주소로 좌표를 검색합니다
            geocoder.addressSearch(addr, function(result, status) {

                // 정상적으로 검색이 완료됐으면 
                if (status === kakao.maps.services.Status.OK) {

                    var coords = new kakao.maps.LatLng(result[0].y, result[0].x);
                    //console.log(result[0].y, result[0].x);
                    $("#set_lang").val(result[0].y);
                    $("#set_lng").val(result[0].x);
                } 
            });

            alert("좌표가 설정되었습니다."); 
        }
    })

    function sample6_execDaumPostcode() {
        new daum.Postcode({
            oncomplete: function(data) {
                // 팝업에서 검색결과 항목을 클릭했을때 실행할 코드를 작성하는 부분.

                // 각 주소의 노출 규칙에 따라 주소를 조합한다.
                // 내려오는 변수가 값이 없는 경우엔 공백('')값을 가지므로, 이를 참고하여 분기 한다.
                var addr = ''; // 주소 변수
                var extraAddr = ''; // 참고항목 변수

                //사용자가 선택한 주소 타입에 따라 해당 주소 값을 가져온다.
                if (data.userSelectedType === 'R') { // 사용자가 도로명 주소를 선택했을 경우
                    addr = data.roadAddress;
                } else { // 사용자가 지번 주소를 선택했을 경우(J)
                    addr = data.jibunAddress;
                }

                // 사용자가 선택한 주소가 도로명 타입일때 참고항목을 조합한다.
                if(data.userSelectedType === 'R'){
                    // 법정동명이 있을 경우 추가한다. (법정리는 제외)
                    // 법정동의 경우 마지막 문자가 "동/로/가"로 끝난다.
                    if(data.bname !== '' && /[동|로|가]$/g.test(data.bname)){
                        extraAddr += data.bname;
                    }
                    // 건물명이 있고, 공동주택일 경우 추가한다.
                    if(data.buildingName !== '' && data.apartment === 'Y'){
                        extraAddr += (extraAddr !== '' ? ', ' + data.buildingName : data.buildingName);
                    }
                    // 표시할 참고항목이 있을 경우, 괄호까지 추가한 최종 문자열을 만든다.
                    if(extraAddr !== ''){
                        extraAddr = ' (' + extraAddr + ')';
                    }
                    // 조합된 참고항목을 해당 필드에 넣는다.
                    //document.getElementById("sample6_extraAddress").value = extraAddr;
                
                } else {
                    //document.getElementById("sample6_extraAddress").value = '';
                }

                // 우편번호와 주소 정보를 해당 필드에 넣는다.
                //document.getElementById('sample6_postcode').value = data.zonecode;
                document.getElementById("sample6_address").value = addr;
                // 커서를 상세주소 필드로 이동한다.
                //document.getElementById("sample6_detailAddress").focus();
            }
        }).open();
    }
</script>
<?php
include_once(G5_ADMIN_PATH.'/admin.tail.php');
?>
