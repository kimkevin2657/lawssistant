<?php
if (!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가
include_once(MS_LIB_PATH.'/thumbnail.lib.php');
include_once('/home/pulo/www/bbs/skin/basic_doc/skin.function.php');

// 전자결재시 필수 변수값 체크
// 항상 list.skin.php 파일을 통해서 접속해야 함.
if(!$_SESSION['app_mb_info']) {
    goto_url($list_href);
} else {
    $member = $_SESSION['app_mb_info'];
}

// 직원으로 등록되어 있는지 체크
checkStaff($member['id']);

// 작성자의 문서 결재상태가 "승인"인지 체크
checkDoc($view, $member['id']);

// 문서 결재선, 참조자, 참조부서에 해당되는지 체크
checkViewDoc($write, $member['id']);

// 참조직원
$wr_1 = json_decode($write['wr_1'], true);
$reperrer_list = ($write['wr_1']) ? $write['wr_1'] : "{}";

// 참조부서
$wr_11 = json_decode($write['wr_11'], true);
$section_list = ($write['wr_11']) ? $write['wr_11'] : "{}";

$list = array();
$list = get_subdata($wr_id); // 문서 세부 내역 리스트 : {$write_table}_sub
$log = get_log($wr_id); // 결재현황 로그 리스트 : {$write_table}_log

// 자바스크립트에서 사용될 변수
$cfg = array(
	"s1" => explode("|", $view['wr_3']),
	"s2" => explode("|", $view['wr_5']),
	"s3" => explode("|", $view['wr_7']),
	"s4" => explode("|", $view['wr_9']),
    "ca_name" => $view['ca_name'],
    "doc_list" => $board['bo_category_list'], //문서종류
    "doc_file" => $board['bo_1'], // 문서종류에 대한 파일명
    "list_cnt" => count($list),
	"wr_id" => $wr_id,
    "bo_table" => $bo_table,
    "app_state" => $APP_STATE,
    "section_name" => $member['mb_kind'],
    "username" => $member['name'],
	"board_skin_url" =>  MS_BBS_URL."/".$board_skin_url,
	"update_url" => MS_BBSORG_URL."/write.php?w=u&bo_table={$bo_table}&wr_id={$wr_id}".$qstr
);

//echo MS_BBS_URL."/".$board_skin_url;

$app_rst = get_btns($view); // 결재상태에 따라 기능 버튼 가져오기

/* 작성자, 결재1 ~ 결재4 진행상태 */
$name1 = mb_name($view['wr_2']);
$name2 = mb_name($view['wr_4']);
$name3 = mb_name($view['wr_6']);
$name4 = mb_name($view['wr_8']);

$approval[0] = approval2($view['wr_2'], $view['wr_3'], "1|0", $view['wr_5'], '1');
$approval[1] = approval2($view['wr_4'], $view['wr_5'], $view['wr_3'], $view['wr_7'], '2');
$approval[2] = approval2($view['wr_6'], $view['wr_7'], $view['wr_5'], $view['wr_9'], '3');
$approval[3] = approval2($view['wr_8'], $view['wr_9'], $view['wr_7'], '', '4');

/* add_stylesheet("<link rel='stylesheet' href='".MS_PLUGIN_URL."/rumipopup/rumiPopup.css'>");
add_javascript("<script src='".MS_PLUGIN_URL."/rumipopup/jquery.rumiPopup.js'></script>");
add_javascript('<script src="'.MS_BBS_URL.'/skin/basic_doc/js/doc.js"></script>', 0);
add_javascript('<script src="'.MS_JS_URL.'/viewimageresize.js"></script>', 0);
add_stylesheet('<link rel="stylesheet" href="'.MS_BBS_URL.'/skin/basic_doc/style.css">', 0); */

$qstr = "&sfl={$sfl}&stx={$stx}&page={$page}";
?>
<link rel='stylesheet' href='<? echo MS_PLUGIN_URL; ?>/rumipopup/rumiPopup.css'>
<script src="<? echo MS_PLUGIN_URL; ?>/rumipopup/jquery.rumiPopup.js"></script>

<link rel="stylesheet" href="<? echo MS_BBS_URL; ?>/skin/basic_doc/style.css">
<script src="<? echo MS_BBS_URL; ?>/skin/basic_doc/js/doc.js"></script>

<script src="<? echo MS_JS_URL; ?>/viewimageresize.js"></script>
<script src="<? echo MS_PLUGIN_URL; ?>/rumiTable/jquery.number.js"></script>
<script src="<? echo MS_PLUGIN_URL; ?>/rumiTable/jquery.rumiTable.js"></script>
<script>
var cfg = <?php echo json_encode($cfg); ?>;
var reperrer_list = <?php echo $reperrer_list; ?>;
var section_list = <?php echo $section_list; ?>;
$(document).ready(function() {

    // 문서 수정이며, 참조자 명단이 있으면 화면에 출력
    var cls = "";
    $.each(reperrer_list, function(idx, data) {
        if(data.name==cfg.username || data.name=="전체 공개") { cls = "mb_active";  }
        var name = "<li data-id='"+data.id+"|"+data.name+"' class='"+cls+"'>"+data.name+"<span class='re_del'><i class='fa fa-remove' aria-hidden='true'></i></span></li>";
      $(".refer").append(name);
    });

    // 문서 수정, 참조 부서 
    $.each(section_list, function(idx, data) {
        cls = (data==cfg.section_name || data.name=="전체 공개") ? "mb_active" : "";
        var name = "<li data-id='"+data+"' class='"+cls+"'>"+data+"<span class='re_del2'><i class='fa fa-remove' aria-hidden='true'></i></span></li>";
      $(".refer2").append(name);
    });

    if(cfg.list_cnt > 0) { 
        get_document(cfg.ca_name, "view");
    }
    
});
</script>

<!-- 게시물 상단 버튼 시작 { -->
<article id="bo_v">
    
<div class="top_btns">
		<?php if ($prev_href || $next_href) { ?>
			<ul style="float:left;margin:20px 0px;">
				<?php if ($prev_href) { ?><li style="display:inline-block !important;"><a href="<?php echo $prev_href ?>" class="btn_b01 btn" style="border-radius:3px;">이전문서</a></li><?php } ?>
				<?php if ($next_href) { ?><li style="display:inline-block !important;"><a href="<?php echo $next_href ?>" class="btn_b01 btn" style="border-radius:3px;">다음문서</a></li><?php } ?>
			</ul>
		<?php } ?>

		<ul class="bo_v_com">
            <?php echo $app_rst['app_btn']; ?>
			<?php if ($update_href) { ?><li onclick="docEdit();"><a href="#" class="btn_b01 btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 문서수정</a></li><?php } ?>
			<?php if ($delete_href) { ?><li><a href="<?php echo $delete_href ?>" class="btn_b01 btn" onclick="del(this.href); return false;"><i class="fa fa-trash-o" aria-hidden="true"></i> 삭제</a></li><?php } ?>
			<?php if ($search_href) { ?><li><a href="<?php echo $search_href ?>" class="btn_b01 btn"><i class="fa fa-search" aria-hidden="true"></i> 검색목록</a></li><?php } ?>
			<li><a href="<?php echo $list_href ?>" class="btn_b01 btn"><i class="fa fa-list" aria-hidden="true"></i> 문서목록</a></li>
			<?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b02 btn"><i class="fa fa-pencil" aria-hidden="true"></i> 문서작성</a></li><?php } ?>
		</ul>
	</div>

	<!-- } 게시물 상단 버튼 끝 -->

	<div id="bo_v">
		<input type="hidden" name="update_url" class="update_url" value="<?php echo $update_href; ?>" />
		<input type="hidden" name="s1" class="s1" value="<?php echo $s1[0]; ?>" />
		<input type="hidden" name="s2" class="s2" value="<?php echo $s2[0]; ?>" />
		<input type="hidden" name="s3" class="s3" value="<?php echo $s3[0]; ?>" />
		<input type="hidden" name="s4" class="s4" value="<?php echo $s4[0]; ?>" />
		<table>
			<colgroup>
				<col width="*" />
				<col width="3%" />
				<col width="15%" />
				<col width="15%" />
				<col width="15%" />
				<col width="15%" />
			</colgroup>
			<tr>
				<td rowspan="2" class="doc_title"><?php echo $view['ca_name']; ?></td>
				<th rowspan="2">결<br/>재<br/>선</th>
				<th class="<?php if($name1==$member['name']) { echo "mb_active"; } ?>"><?php echo $name1; ?></th>
				<th class="<?php if($name2==$member['name']) { echo "mb_active"; } ?>"><?php echo $name2; ?></th>
				<th class="<?php if($name3==$member['name']) { echo "mb_active"; } ?>"><?php echo $name3; ?></th>
				<th class="<?php if($name4==$member['name']) { echo "mb_active"; } ?>"><?php echo $name4; ?></th>
			</tr>
			<tr>
				<td class="td_center"><span class="ss1"><?php echo $approval[0]['status']; ?></span></td>
				<td class="td_center"><span class="ss2"><?php echo $approval[1]['status']; ?></span></td>
				<td class="td_center"><span class="ss3"><?php echo $approval[2]['status']; ?></span></td>
				<td class="td_center"><span class="ss4"><?php echo $approval[3]['status']; ?></span></td>
			</tr>
		</table>


		<table>
			<colgroup>
				<col width="9%" />
				<col width="16%" />
				<col width="9%" />
				<col width="16%" />
				<col width="9%" />
				<col width="16%" />
				<col width="9%" />
				<col width="16%" />
			</colgroup>
			<tr>
				<th>작성자</th>
				<td><?php echo mb_name($view['mb_id']); ?></td>
				<th>최초작성일</th>
				<td><?php echo $view['wr_datetime']; ?></td>
				<th>최종수정일</th>
				<td><?php echo $view['wr_last']; ?></td>
				<th>열람횟수</th>
				<td><?php echo $view['wr_hit']; ?>회</td>
			</tr>

            <tr>
				<th>참조직원</th>
                <td colspan="7" clAss="td_left">
                    <?php //echo $reperrer_list; ?>
                    <div class="re_box"></div>
                    <div class="re_box">
                        <ui class='refer'></ui>
                    </div>
                </td>
            </tr>
            
            <tr>
				<th>참조부서</th>
                <td colspan="7" clAss="td_left">
                    <?php //echo $reperrer_list; ?>
                    <div class="re_box2"></div>
                    <div class="re_box2">
                        <ui class='refer2'></ui>
                    </div>
                </td>
			</tr>

			<tr>
				<th>제목</th>
				<td colspan="7" clAss="td_left"><?php echo $view['wr_subject']; ?></td>
			</tr>
			<tr>
				<td colspan="8" class="td_left wr_content">
					<?php echo get_view_thumbnail($view['content']); ?>
				</td>
			</tr>
		</table>
	</div>

    <section id="bo_v_atc">
        <h2 id="bo_v_atc_title">본문</h2>
        <?php
        // 파일 출력
        $v_img_count = count($view['file']);
        if($v_img_count) {
            echo "<div id=\"bo_v_img\">\n";
            for ($i=0; $i<=count($view['file']); $i++) {
                if ($view['file'][$i]['view']) {
                    //echo $view['file'][$i]['view'];
                    echo get_view_thumbnail($view['file'][$i]['view']);
                }
            }
            echo "</div>\n";
        }
        ?>

		<!-- 본문 내용 시작 { -->
		<div id="doc_zone"></div><!-- 문서 상세내역 -->
        <!-- } 본문 내용 끝 -->

        <div id="app_log">
            <table>
                <colgroup>
                    <col width="4%" />
                    <col width="12%" />
                    <col width="9%" />
                    <col width="*" />
                    <col width="15%" />
                </colgroup>
                <thead>
                    <th>번호</th>
                    <th>성명</th>
                    <th>결재상태</th>
                    <th>추가의견</th>
                    <th>시간</th>
                </thead>
                <tbody>
                    <?php
                    for($i=0; $i < $log->total_count; $i++) {
                        
                        switch($log->rows[$i]['current']) {
                            case "0" : // 미결재
                                $cls = "txt_red";
                                break; 
                            case "1" : // 승인
                                $cls = "txt_blue";
                                break;
                            case "2" : // 반려
                                $cls = "txt_red";
                                break;
                            case "3" : // 보류
                                $cls = "txt_gray";
                                break;
                            case "99" : // 취소
                                $cls = "txt_gray";
                                break;
                                
                        }
                    
                    ?>
                    <tr>
                        <td><?php echo ($i + 1); ?></td>
                        <td><?php echo $log->rows[$i]['name']; ?></td>
                        <td class="<?php echo $cls; ?>"><?php echo $APP_STATE[$log->rows[$i]['current']]; ?></td>
                        <td class="td_left"><?php echo $log->rows[$i]['memo']; ?></td>
                        <td><?php echo $log->rows[$i]['datetime']; ?></td>
                    </tr>
                    <?php } 
                    if($log->total_count==0) {
                        echo "<tr><td class='no_data' colspan='5'>자료가 없습니다.</td></tr>";
                    }                    
                    ?>
                </tbody>
            </table>
        </div>

    </section>

    <?php
    include_once(G5_SNS_PATH."/view.sns.skin.php");
    ?>

	<!-- <div id="bo_v_share">
        <?php if ($scrap_href) { ?><a href="<?php echo $scrap_href;  ?>" target="_blank" class="btn btn_b03" onclick="win_scrap(this.href); return false;"><i class="fa fa-thumb-tack" aria-hidden="true"></i> 스크랩</a><?php } ?>
        <?php
        include_once(G5_SNS_PATH."/view.sns.skin.php");
        ?>
    </div> -->

    <?php
    $cnt = 0;
    //echo "count:".$view['file']['count'];
    if ($view['file']['count']) {
        for ($i=0; $i<count($view['file']); $i++) {
            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view'])
                $cnt++;
        }
    }
    ?>

    <?php if($cnt) { ?>
    <!-- 첨부파일 시작 { -->
    <section id="bo_v_file">
        <h2>첨부파일</h2>
        <ul>
        <?php
        // 가변 파일
        //echo "cnt:".count($view['file']);


        for ($i=0; $i<count($view['file']); $i++) {
            if (isset($view['file'][$i]['source']) && $view['file'][$i]['source'] && !$view['file'][$i]['view']) {
         ?>
            <li>
                <i class="fa fa-download" aria-hidden="true"></i>
                <a href="<?php echo $view['file'][$i]['href'];  ?>" class="view_file_download">
                    <strong><?php echo $view['file'][$i]['source'] ?></strong>
                </a>
                <?php echo $view['file'][$i]['content'] ?> (<?php echo $view['file'][$i]['size'] ?>)
                <span class="bo_v_file_cnt"><?php echo $view['file'][$i]['download'] ?>회 다운로드 | DATE : <?php echo $view['file'][$i]['datetime'] ?></span>
            </li>
        <?php
            }
        }
         ?>
        </ul>
    </section>
    <!-- } 첨부파일 끝 -->
    <?php } ?>

    <?php if(array_filter($view['link'])) { ?>
    <!-- 관련링크 시작 { -->
    <section id="bo_v_link">
        <h2>관련링크</h2>
        <ul>
        <?php
        // 링크
        $cnt = 0;
        for ($i=1; $i<=count($view['link']); $i++) {
            if ($view['link'][$i]) {
                $cnt++;
                $link = cut_str($view['link'][$i], 70);
            ?>
            <li>
                <i class="fa fa-link" aria-hidden="true"></i> <a href="<?php echo $view['link_href'][$i] ?>" target="_blank">
                    
                    <strong><?php echo $link ?></strong>
                </a>
                <span class="bo_v_link_cnt"><?php echo $view['link_hit'][$i] ?>회 연결</span>
            </li>
            <?php
            }
        }
        ?>
        </ul>
    </section>
    <!-- } 관련링크 끝 -->
    <?php } ?>

    <!-- 게시물 상단 버튼 시작 { -->
    <div id="bo_v_top">
        <ul class="bo_v_left">
            <?php if ($update_href) { ?><li onclick="docEdit();"><a href="#" class="btn_b01 btn"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> 문서수정</a></li><?php } ?>
            <?php if ($delete_href) { ?><li><a href="<?php echo $delete_href ?>" class="btn_b01 btn" onclick="del(this.href); return false;"><i class="fa fa-trash-o" aria-hidden="true"></i> 문서삭제</a></li><?php } ?>
            <?php if ($search_href) { ?><li><a href="<?php echo $search_href ?>" class="btn_b01 btn"><i class="fa fa-search" aria-hidden="true"></i> 검색</a></li><?php } ?>
        </ul>

        <ul class="bo_v_com">
            <?php echo $app_rst['app_btn']; ?>
            <li><a href="<?php echo $list_href ?>" class="btn_b01 btn"><i class="fa fa-list" aria-hidden="true"></i> 문서목록</a></li>
            <?php if ($reply_href) { ?><!-- <li><a href="<?php echo $reply_href ?>" class="btn_b01 btn"><i class="fa fa-reply" aria-hidden="true"></i> 답변</a></li> --><?php } ?> 
            <?php if ($write_href) { ?><li><a href="<?php echo $write_href ?>" class="btn_b02 btn"><i class="fa fa-pencil" aria-hidden="true"></i> 문서작성</a></li><?php } ?>
        </ul>

        <?php if ($prev_href || $next_href) { ?>
        <ul class="bo_v_nb">
            <?php if ($prev_href) { ?><li class="btn_prv"><span class="nb_tit"><i class="fa fa-caret-up" aria-hidden="true"></i> 이전글</span><a href="<?php echo $prev_href ?>"><?php echo $prev_wr_subject;?></a> <span class="nb_date"><?php echo str_replace('-', '.', substr($prev_wr_date, '2', '8')); ?></span></li><?php } ?>
            <?php if ($next_href) { ?><li class="btn_next"><span class="nb_tit"><i class="fa fa-caret-down" aria-hidden="true"></i> 다음글</span><a href="<?php echo $next_href ?>"><?php echo $next_wr_subject;?></a>  <span class="nb_date"><?php echo str_replace('-', '.', substr($next_wr_date, '2', '8')); ?></span></li><?php } ?>
        </ul>
        <?php } ?>
    </div>
    <!-- } 게시물 상단 버튼 끝 -->
	
	<?php
    // 코멘트 입출력
    include_once(G5_BBS_PATH.'/view_comment.php');
     ?>

</article>
<!-- } 게시판 읽기 끝 -->

<script>
<?php if ($board['bo_download_point'] < 0) { ?>
$(function() {
    $("a.view_file_download").click(function() {
        if(!g5_is_member) {
            alert("다운로드 권한이 없습니다.\n회원이시라면 로그인 후 이용해 보십시오.");
            return false;
        }

        var msg = "파일을 다운로드 하시면 포인트가 차감(<?php echo number_format($board['bo_download_point']) ?>점)됩니다.\n\n포인트는 게시물당 한번만 차감되며 다음에 다시 다운로드 하셔도 중복하여 차감하지 않습니다.\n\n그래도 다운로드 하시겠습니까?";

        if(confirm(msg)) {
            var href = $(this).attr("href")+"&js=on";
            $(this).attr("href", href);

            return true;
        } else {
            return false;
        }
    });
});
<?php } ?>

function board_move(href)
{
    window.open(href, "boardmove", "left=50, top=50, width=500, height=550, scrollbars=1");
}
</script>

<script>
$(function() {
    $("a.view_image").click(function() {
        window.open(this.href, "large_image", "location=yes,links=no,toolbar=no,top=10,left=10,width=10,height=10,resizable=yes,scrollbars=no,status=no");
        return false;
    });

    // 추천, 비추천
    $("#good_button, #nogood_button").click(function() {
        var $tx;
        if(this.id == "good_button")
            $tx = $("#bo_v_act_good");
        else
            $tx = $("#bo_v_act_nogood");

        excute_good(this.href, $(this), $tx);
        return false;
    });

    // 이미지 리사이즈
    $("#bo_v_atc").viewimageresize();

    //sns공유
    $(".btn_share").click(function(){
        $("#bo_v_sns").fadeIn();
   
    });

    $(document).mouseup(function (e) {
        var container = $("#bo_v_sns");
        if (!container.is(e.target) && container.has(e.target).length === 0){
        container.css("display","none");
        }	
    });
});

function excute_good(href, $el, $tx)
{
    $.post(
        href,
        { js: "on" },
        function(data) {
            if(data.error) {
                alert(data.error);
                return false;
            }

            if(data.count) {
                $el.find("strong").text(number_format(String(data.count)));
                if($tx.attr("id").search("nogood") > -1) {
                    $tx.text("이 글을 비추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                } else {
                    $tx.text("이 글을 추천하셨습니다.");
                    $tx.fadeIn(200).delay(2500).fadeOut(200);
                }
            }
        }, "json"
    );
}
</script>
<!-- } 게시글 읽기 끝 -->