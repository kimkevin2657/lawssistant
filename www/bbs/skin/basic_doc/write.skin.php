<?php
if (!defined('_MALLSET_')) exit; // 개별 페이지 접근 불가
include_once("/home/pulo/www/bbs/skin/basic_doc/skin.function.php");
$Search_box = new Frm_search(); // 검색폼 관련 Class

// 전자결재시 필수 변수값 체크
// 항상 list.skin.php 파일을 통해서 접속해야 함.
if(!$_SESSION['app_mb_info']) {
    goto_url("?bo_table={$bo_table}");
} else {
    $member = $_SESSION['app_mb_info'];
}


// 결재 진행중인 문서인지 체크
checkOngoing($write['wr_3']);

// 작성자의 문서 결재상태가 "승인"인지 체크
checkDoc($view, $member['id']);

// 직원으로 등록되어 있는지 체크
checkStaff($member['id']);

$MB_APP = $MB = get_memberlist(); // 직원 리스트 : {$write_table}_member
unset($MB_APP['ALL']); // 결재자 선택시에는 "전체공개"를 삭제
$SC = get_teamlist(); // 부서리스트

//print_r($write);

/* 참조자 생성 */
$wr_1 = json_decode($write['wr_1'], true);
$reperrer_list = ($write['wr_1']) ? $write['wr_1'] : "{}";
$cfg = array(
    "ca_name" => $write['ca_name'],
    "doc_list" => $board['bo_category_list'], //문서종류
    "doc_file" => $board['bo_1'], // 문서종류에 대한 파일명
    "wr_id" => $wr_id,
    "app_state" => $APP_STATE,
	"bo_table" => $bo_table,
	"board_skin_url" => MS_BBS_URL."/skin/basic_doc"
);

/* add_stylesheet("<link rel='stylesheet' href='".MS_PLUGIN_URL."/rumipopup/rumiPopup.css'>");
add_javascript("<script src='".MS_PLUGIN_URL."/rumipopup/jquery.rumiPopup.js'></script>");
add_stylesheet('<link rel="stylesheet" href="/home/pulo/www/bbs/skin/basic_doc/style.css">', 0);
add_javascript('<script src="/home/pulo/www/bbs/skin/basic_doc/js/doc.js"></script>', 0);
add_javascript('<script src="'.MS_PLUGIN_URL.'/rumiTable/jquery.number.js"></script>', 0);
add_javascript('<script src="'.MS_PLUGIN_URL.'/rumiTable/jquery.rumiTable.js"></script>',10); */
?>
<link rel='stylesheet' href='<? echo MS_PLUGIN_URL; ?>/rumipopup/rumiPopup.css'>
<script src="<? echo MS_PLUGIN_URL; ?>/rumipopup/jquery.rumiPopup.js"></script>

<link rel="stylesheet" href="<? echo MS_BBS_URL; ?>/skin/basic_doc/style.css">
<script src="<? echo MS_BBS_URL; ?>/skin/basic_doc/js/doc.js"></script>

<script src="<? echo MS_PLUGIN_URL; ?>/rumiTable/jquery.number.js"></script>
<script src="<? echo MS_PLUGIN_URL; ?>/rumiTable/jquery.rumiTable.js"></script>

<SCRIPT LANGUAGE="JavaScript">
var cfg = <?php echo json_encode($cfg); ?>;
var mb_reperrer = {}; // 참조자 명단 배열
var mb_section = {}; // 참조부서 명단 배열
var reperrer_list = <?php echo $reperrer_list; ?>;

$(document).ready(function(){

    // 문서 수정이며, 참조자 명단이 있으면 화면에 출력
    $.each(reperrer_list, function(idx, data) {
        var name = "<li data-id='"+data.id+"|"+data.name+"'>"+data.name+"<span class='re_del'><i class='fa fa-remove' aria-hidden='true'></i></span></li>";
      $(".refer").append(name);
    });

    // 문서 상세내역 가져오기, 문서종류 active 주기
	if(cfg.wr_id) {
        get_document(cfg.ca_name, 'write'); // 문서상세내역
        
        // lable에 active 주기 (문서종류버튼)
        var st = $(".xradio");
        for(i=0; i < st.length; i++) {
            var str = st.eq(i).text();
            if(str==cfg.ca_name) {
                st.eq(i).addClass("active");
                break;
            }
        }
    }

    // 참조자 추가하기
    $("#referrer").change(function(e) {
        var val = $(this).val();
        reperrerList(val);
        $(this).val('')
        //console.log(mb_reperrer);
    });

    // 참조자 삭제
    $(document).on("click", ".re_del", function(e) {
        var idx = $(this).index(".re_del");
        $(".refer li").eq(idx).remove();
        mb_reperrer = {};
        reperrerList('');
    })

    // 참조부서 추가하기
    $("#referrer2").change(function(e) {
        var val = $(this).val();
        reperrerList2(val);
        $(this).val('')
    });

    // 참조부서 삭제
    $(document).on("click", ".re_del2", function(e) {
        var idx = $(this).index(".re_del2");
        $(".refer2 li").eq(idx).remove();
        mb_section = {};
        reperrerList2('');
    })

    // 문서 선택
    $(".xradio").click(function() {
        var idx = $(this).index(".xradio");
        var cls = $(this).attr("class");
        var rst = cls.match(/active/);
        
        if(rst) {
            return false;
        }
        
        var doc = $(this).text();
        var chk = $(".ca_name").eq(idx).is(":checked");
        if(chk==true) {
            $(".xradio").removeClass("active");
            $(this).addClass("active");
            get_document(doc, 'write');
        } 
    });

});

</script>

<style>
#bo_w #doc_select { padding:8px; }
#bo_w #doc_select label { position:relative; overflow:hidden; cursor:pointer; display:inline-block; padding:14px 10px; background:#0087D2; color:#fff; width:60px; height:60px; margin:2px 5px 2px 0px; border-radius:3px; vertical-align:top; }
#bo_w #doc_select .active  { background:#dd6666; }
#bo_w #doc_select input[type=radio] { position:absolute; top:-20px; }
</style>

<div id="bo_w">
    <div class="topBtns">
        <span id="" class="sap_btn" onclick="approvalSave();">결재선저장</span>
        <span id="" class="sap_btn" onclick="approvalOpen();">결재선선택</span>
    </div>
    <!-- 게시물 작성/수정 시작 { -->
    <form name="fwrite" id="fwrite" action="/bbs_origin/write_update.php" onsubmit="return fwrite_submit(this);" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="uid" value="<?php echo get_uniqid(); ?>" />
    <input type="hidden" name="w" value="<?php echo $w ?>" />
    <input type="hidden" name="bo_table" value="<?php echo $bo_table ?>" />
    <input type="hidden" name="wr_id" value="<?php echo $wr_id ?>" />
    <input type="hidden" name="sca" value="<?php echo $sca ?>" />
    <input type="hidden" name="sfl" value="<?php echo $sfl ?>" />
    <input type="hidden" name="stx" value="<?php echo $stx ?>" />
    <input type="hidden" name="spt" value="<?php echo $spt ?>" />
    <input type="hidden" name="sst" value="<?php echo $sst ?>" />
    <input type="hidden" name="sod" value="<?php echo $sod ?>" />
    <input type="hidden" name="page" value="<?php echo $page ?>" />
    <input type="hidden" name="wr_1" id="wr_1" value='<?php echo $write['wr_1']; ?>' />
    <input type="hidden" name="wr_11" id="wr_11" value='<?php echo $write['wr_11']; ?>' />
    <input type="hidden" name="wr_option" value="html1">
	<input type="hidden" name="section" value="approval">
	
	<table>
		<colgroup>
			<col width="*" />
			<col width="3%" />
			<col width="16%" />
			<col width="16%" />
			<col width="16%" />
			<col width="16%" />

		</colgroup>
		<tr>
			<td rowspan="2">
				<span class="doc_title">문서종류를 선택해주세요.</span>
			</td>
			<th rowspan="2">결<br/>재<br/>선</th>
			<th><span class="doc_app">작성자</span></th>
			<th><span class="doc_app">결재 1</span></th>
			<th><span class="doc_app">결재 2</span></th>
			<th><span class="doc_app">결재 3</span></th>
		</tr>
		<tr>
			<td class="td_center"><?php echo $member['name']; ?></td>
			<td><?php $Search_box->var_mode('A', $MB_APP);echo $Search_box->Select('== 선택 ==', 'app_2', 'app_2', 'app', $write['wr_4']); ?></td>
			<td><?php $Search_box->var_mode('A', $MB_APP);echo $Search_box->Select('== 선택 ==', 'app_3', 'app_3', 'app', $write['wr_6']); ?></td>
			<td><?php $Search_box->var_mode('A', $MB_APP);echo $Search_box->Select('== 선택 ==', 'app_4', 'app_4', 'app', $write['wr_8']); ?></td>
		</tr>
	</table>

	<table>
		<colgroup>
			<col width="15%" />
			<col width="*" />
		</colgroup>
		<tbody>
			<tr>
				<th><label for="category">문서종류<strong class="sound_only">필수</strong></label></th>
				<td class="td_left">
                    <div id="doc_select">
                        <?php echo xradio($board['bo_category_list'], $board['bo_category_list'], $write['ca_name'], 'ca_name'); ?>
                    </div>
				</td>
			</tr>

            <tr>
				<th><label for="wr_subject">참조직원<strong class="sound_only">필수</strong></label></th>
				<td class="td_left">
                    <div class="re_box">                        
                        <?php 
                        $Search_box->var_mode('A', $MB);
                        echo $Search_box->Select('== 선택 ==', 'referrer', 'referrer', 'referrer', '');
                        ?>
                    </div>
                    <div class="re_box">
                        <ui class='refer'></ui>
                    </div>
                </td>
			</tr>

            <tr>
				<th><label for="wr_subject">참조부서<strong class="sound_only">필수</strong></label></th>
				<td class="td_left">
                    <div class="re_box2">
                        <?php 
                        $Search_box->var_mode('A', $SC);
                        echo $Search_box->Select('== 선택 ==', 'referrer2', 'referrer2', 'referrer2', '');
                        ?>
                    </div>
                    <div class="re_box2">
                        <ui class='refer2'></ui>
                    </div>
                </td>
			</tr>
			
			<tr>
				<th><label for="wr_subject">문서제목<strong class="sound_only">필수</strong></label></th>
				<td class="td_left wr_sub">
					<input type="text" name="wr_subject" value="<?php echo $subject ?>" id="wr_subject" required class="wr_subject required" size="50" maxlength="255" placeholder="문서의 제목을 입력하세요.">
				</td>
            </tr>
        </tbody>
    </table>
    
	<div id="content">
        <?php echo $editor_html; ?>
        <?php //echo editor_html('wr_content', get_text($write['wr_content'], 0)); ?>
    </div>		
	<div id="doc_zone"></div>

	 <?php for ($i=1; $is_link && $i<=G5_LINK_COUNT; $i++) { ?>
    <div class="bo_w_link write_div">
        <label for="wr_link<?php echo $i ?>"><i class="fa fa-link" aria-hidden="true"></i><span class="sound_only"> 링크  #<?php echo $i ?></span></label>
        <input type="text" name="wr_link<?php echo $i ?>" value="<?php if($w=="u"){echo $write['wr_link'.$i];} ?>" id="wr_link<?php echo $i ?>" class="frm_input full_input" size="50">
    </div>
    <?php } ?>

    <?php for ($i=0; $i<$file_count; $i++) { ?>
    <div class="bo_w_flie write_div">
        <div class="file_wr write_div">
            <label for="bf_file_<?php echo $i+1 ?>" class="lb_icon"><i class="fa fa-download" aria-hidden="true"></i><span class="sound_only"> 파일 #<?php echo $i+1 ?></span></label>
            <input type="file" name="bf_file[]" id="bf_file_<?php echo $i+1 ?>" title="파일첨부 <?php echo $i+1 ?> : 용량 <?php echo $upload_max_filesize ?> 이하만 업로드 가능" class="frm_file ">
        </div>
        <?php if ($is_file_content) { ?>
        <input type="text" name="bf_content[]" value="<?php echo ($w == 'u') ? $file[$i]['bf_content'] : ''; ?>" title="파일 설명을 입력해주세요." class="full_input frm_input" size="50" placeholder="파일 설명을 입력해주세요.">
        <?php } ?>

        <?php if($w == 'u' && $file[$i]['file']) { ?>
        <span class="file_del">
            <input type="checkbox" id="bf_file_del<?php echo $i ?>" name="bf_file_del[<?php echo $i;  ?>]" value="1"> <label for="bf_file_del<?php echo $i ?>"><?php echo $file[$i]['source'].'('.$file[$i]['size'].')';  ?> 파일 삭제</label>
        </span>
        <?php } ?>
        
    </div>
    <?php } ?>

    <?php if ($is_use_captcha) { //자동등록방지  ?>
    <div class="write_div">
        <?php echo $captcha_html ?>
    </div>
    <?php } ?>

    <div class="btn_confirm">
        <input type="submit" value="작성완료" id="btn_submit" accesskey="s" class="rumiBtn btn_submit">
        <a href="<?php echo get_pretty_url($bo_table); ?>" class="rumiBtn btn_cancel">취소</a>
	</div>
	
    </form>
</div>




<script>
    
    function html_auto_br(obj)
    {
        if (obj.checked) {
            result = confirm("자동 줄바꿈을 하시겠습니까?\n\n자동 줄바꿈은 게시물 내용중 줄바뀐 곳을<br>태그로 변환하는 기능입니다.");
            if (result)
                obj.value = "html2";
            else
                obj.value = "html1";
        }
        else
            obj.value = "";
    }

    function fwrite_submit(f)
    {
        <?php echo $editor_js; // 에디터 사용시 자바스크립트에서 내용을 폼필드로 넣어주며 내용이 입력되었는지 검사함   ?>

        var subject = "";
        var content = "";
        $.ajax({
            url: g5_bbs_url+"/ajax.filter.php",
            type: "POST",
            data: {
                "subject": f.wr_subject.value,
                "content": f.wr_content.value
            },
            dataType: "json",
            async: false,
            cache: false,
            success: function(data, textStatus) {
                subject = data.subject;
                content = data.content;
            }
        });

        if (subject) {
            alert("제목에 금지단어('"+subject+"')가 포함되어있습니다");
            f.wr_subject.focus();
            return false;
        }

        if (content) {
            alert("내용에 금지단어('"+content+"')가 포함되어있습니다");
            if (typeof(ed_wr_content) != "undefined")
                ed_wr_content.returnFalse();
            else
                f.wr_content.focus();
            return false;
        }

        if (document.getElementById("char_count")) {
            if (char_min > 0 || char_max > 0) {
                var cnt = parseInt(check_byte("wr_content", "char_count"));
                if (char_min > 0 && char_min > cnt) {
                    alert("내용은 "+char_min+"글자 이상 쓰셔야 합니다.");
                    return false;
                }
                else if (char_max > 0 && char_max < cnt) {
                    alert("내용은 "+char_max+"글자 이하로 쓰셔야 합니다.");
                    return false;
                }
            }
        }

        <?php echo $captcha_js; // 캡챠 사용시 자바스크립트에서 입력된 캡챠를 검사함  ?>

        document.getElementById("btn_submit").disabled = "disabled";

        return true;
    }
    </script>
</section>