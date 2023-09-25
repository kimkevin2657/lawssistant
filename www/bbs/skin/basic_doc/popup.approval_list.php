<?php
include_once("./_common.php");
$oper = $_POST['oper'];
if(!$oper) {
    include_once(MS_PATH."/head.sub.php");
}

include_once("/home/pulo/www/bbs/skin/basic_doc/skin.function.php");
$Search_box = new Frm_search(); // 검색폼 관련 Class

$name = mb_name($member['id']);
if(!$name) {
    die("직원으로 등록되어 있지 않으면 이용하실 수 없습니다.");
}

// 한행 삭제
$oper = $_POST['oper'];
if($oper=="del") {
    $id = $_POST['id'];
    $sql ="delete from {$write_table}_line where id_no = '{$id}' ";
    sql_query($sql);
    $cnt = doc_affected_rows();
    echo json_encode($cnt);
    die();
}

// 전체 저장
$mode = $_POST['mode'];
if($mode=="save") {
    $app1 = array();
    $id_no = $_POST['id_no'];
    $app1 = $_POST['app1'];
    $app2 = $_POST['app2'];
    $app3 = $_POST['app3'];

    for($i=0; $i < count($app1); $i++) {        
        
        $approval = $app1[$i]."|".$app2[$i]."|".$app3[$i];
        
        if($approval != "||") {
            $sql = "select
                        count(*) as cnt
                    from
                        {$write_table}_line
                    where
                        mb_id = '{$member['id']}' and approval = '{$approval}' ";
            
            //echo $sql."<br>";

            $row = sql_fetch($sql, true);
            
            if($row['cnt'] == 0) {

                $sql = "INSERT INTO
                            {$write_table}_line
                            (`id_no`, `mb_id`, `approval`)
                        VALUES
                            ('{$id_no[$i]}', '{$member['id']}', '{$approval}')
                        ON DUPLICATE KEY UPDATE
                            `approval` = '{$approval}' ";
                sql_query($sql,true);
            }
        }        
    } // end for
    
    goto_url("?bo_table={$bo_table}");
}

/* add_javascript('<script src="'.MS_PLUGIN_URL.'/rumiTable/jquery.rumiTable.js"></script>',10);
add_javascript('<script src="'.MS_PLUGIN_URL.'/rumiTable/jquery.number.js"></script>', 0);
add_stylesheet('<link rel="stylesheet" href="'.MS_BBS_URL.'/skin/basic_doc/style.css">', 0);
add_javascript('<script src="'.MS_BBS_URL.'/skin/basic_doc/js/doc.js"></script>', 0); */

$data = get_approvallsit(); // 개인별 결재선 가져오기
$MB = get_memberlist(); // 직원리스트
unset($MB['ALL']); // 결재자 선택시에는 "전체공개"를 삭제

$cfg = array(
    "ca_name" => $write['ca_name'],
    "doc_list" => $board['bo_category_list'], //문서종류
    "doc_file" => $board['bo_1'], // 문서종류에 대한 파일명
    "wr_id" => $wr_id,
    "app_state" => $APP_STATE,
	"bo_table" => $bo_table,
	"board_skin_url" => $board_skin_url
);
?>
<link rel="stylesheet" href="<? echo MS_BBS_URL; ?>/skin/basic_doc/style.css">
<script src="<? echo MS_BBS_URL; ?>/skin/basic_doc/js/doc.js"></script>

<script src="<? echo MS_PLUGIN_URL; ?>/rumiTable/jquery.number.js"></script>
<script src="<? echo MS_PLUGIN_URL; ?>/rumiTable/jquery.rumiTable.js"></script>

<script>
var array = <?php echo json_encode($data); ?>; // DB 값
var cfg = <?php echo json_encode($cfg); ?>;

console.log(array);

$(document).ready(function() {
    //alert(cfg.board_skin_url+"/popup.approval_list.php?bo_table="+cfg.bo_table);
	/**
	 * 동적테이블 플러그인 시작.
	 * @param { string } autoTabId 엔터키 입력시 커서(focus) 자동 이동 적용될 영역의 className 또는 IdNmae
	 * @param { string } selector 동적테이블의 <tbody>의 className 또는 IdNmae
	 * @param { string } delBtn 테이블 ROW 삭제버튼의 ClassName
	 * @param { string } unique 자료의 고유번호 선택자(id_no, wr_id, no, ids... 등등..)
	 * @param { string } delUrl 자료삭제 URL (행 자료삭제)
	 * @param { string } autoTabclass 엔터키입력시 자동으로 포커스 이동 가능 ClassName
	 * @param { Object } dbData (배열값) DB 데이타 배열.
	 * @param { Array } items (배열값) 동적테이블을 구성하고 있는 요소들의 속성. 
	 * @param { function } delSucess 행 삭제시 추가로 실행될 사용자 함수
	 */
	rumiTable.rumiLoad({
		autoTabId : "#doc_zone",
		selector: ".work_item",
		delBtn  : ".delItemBtn",
		unique  : ".id_no",
		delUrl  :  cfg.board_skin_url+"/popup.approval_list.php?bo_table="+cfg.bo_table,
		autoTabclass : "input.insTab",
		dbData : array,
		items : [
			{ selector:'id_no', type:'select', number:false },
			{ selector:'app1', type:'select', number:false },			
			{ selector:'app2', type:'select', number:false },
			{ selector:'app3', type:'select', number:false }
		],
		// row 삭제시 실행될 사용자 함수
		delSuccess : function(e){
        }
    });
    
    $(".select").click(function(e) {
        //e = e.target || e.toElement || e.currentTarget || e.srcElement || e.delegateTarget;
        var idx = $(this).index(".select");
        var app1 = $(".app1").eq(idx).val();
        var app2 = $(".app2").eq(idx).val();
        var app3 = $(".app3").eq(idx).val();

        $("#app_2", parent.document).val(app1);
        $("#app_3", parent.document).val(app2);
        $("#app_4", parent.document).val(app3);

        parent.rumiPopup.close();
    });


});
</script>

<div id="doc_zone" style="padding-right:20px;">
    <form name="fwrite" id="fwrite" action="?bo_table=<?php echo $bo_table; ?>" method="post" enctype="multipart/form-data" autocomplete="off">
    <input type="hidden" name="mode" id="mode" value="save" />
    <input type="hidden" name="bo_table" id="bo_table" value="<?php echo $bo_table; ?>" />
    <table>
        <colgroup>
            <col width="8%" />
            <col width="9%" />
            <col width="25%" />
            <col width="25%" />
            <col width="25%" />
            <col width="8%" />
        </colgroup>
        <thead>
            <tr>
                <th>번호</th>    
                <th>선택</th>
                <th>결재 1</th>
                <th>갤재 2</th>
                <th>결재 3</th>
                <th>삭제</th>
            </tr>
        </thead>
        <tbody class="work_item">
            <tr>
                <td class="del_btn"></td>
                <td><span class="select hand">선택</span></td>
                <td><?php
					$Search_box->var_mode('A', $MB);
					echo $Search_box->Select('', 'app1[]', '', 'app1 insTab', '');
					?>
                    <input type="hidden" name="id_no[]"  class="id_no"  value="" />
                </td>
                <td><?php
					$Search_box->var_mode('A', $MB);
					echo $Search_box->Select('', 'app2[]', '', 'app2 insTab', '');
                    ?>
                </td>
                <td><?php
					$Search_box->var_mode('A', $MB);
					echo $Search_box->Select('', 'app3[]', '', 'app3 insTab', '');
                    ?>
                </td>
                <td class="del_btn delItemBtn hand"></td>
            </tr>
        </tbody>
    </table>
    
    <div class="addItemBtn" style="display:none;">
        <button type="button" class="add_five" onclick="rumiTable.add_item(5);">입력항목 5개 추가</button>
        <button type="submit" id="submit" class="submit" >저장</button>
    </div>
    
    </form>
</div>

<?php include_once(G5_PATH."/tail.sub.php"); ?>