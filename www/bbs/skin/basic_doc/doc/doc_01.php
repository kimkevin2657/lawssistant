<?php
include_once("_common.php");
include_once("/home/pulo/www/bbs/skin/basic_doc/skin.function.php");

// 지출내역 불러오기
$dataA = get_subdata($wr_id);
?>
<script>

var array = <?php echo json_encode($dataA); ?>; // DB 값
var calculate = []; // 계산에 필요한 정보를 담을 배열
var supply = []; // 합게금액
$(document).ready(function() {

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
		delUrl  :  cfg.board_skin_url+"/ajax.delete.php?bo_table="+cfg.bo_table,
		autoTabclass : "input.insTab",
		dbData : array,
		items : [
			{ selector:'doc_sub', type:'text', number:false },
			{ selector:'id_no', type:'text', number:false },			
			{ selector:'doc_cost', type:'text', number:true },
			{ selector:'doc_etc', type:'text', number:false }
		],
		// row 삭제시 실행될 사용자 함수
		delSuccess : function(e){
			
			// 삭제성공시 사용자 추가 함수를 실행할 수 있습니다.
            // 선택한 행 삭제후 실행되며, 삭제된 이후의 행이 한단계씩 올라가므로 계산에 필요한 배열을 초기화후 재배열
			var tr_cnt = $(".doc_cost").length;
			calculate = []; // 계산에 필요한 배열 초기화
            for(i=0; i < tr_cnt; i++) {
				var val = $(".doc_cost").val() * 1;
				if(val > 0) {
					calCulate(i);
				}
			}			
        }
	});

	$(".work_item").on("keyup", "input.doc_cost", function(e) {
		var idx = $(this).index(".doc_cost");
		calCulate(idx);
	});
	
	$(".doc_cost").number(true, 0); 
	
	array_sum(array);
});

/**
 * 입력한 금액을 배열에 추가 ( 주의 : 서식파일에 포함되어야 합니다.)
 * @param {Number} idx 행번호
 */
function calCulate(idx){

	// 입력한 금액을 배열로 담는다.
	calculate[idx] = { 
		doc_cost : $('.doc_cost').eq(idx).val() * 1
	};

	supply[idx]= calculate[idx].doc_cost; // 필드별 합계
	sum_supply = supply_sum(supply); // 소계의 합계

	$(".cost_sum").val(number_format(sum_supply));
	$(".cost_sum_txt").text(number_format(sum_supply));

}
</script>
<table>
	<colgroup>
		<col width="6%" />
		<col width="*" />
		<col width="13%" />
		<col width="20%" />
		<col width="5%" />
	</colgroup>
	<thead>
		<tr>
			<th>순번</th>
			<th>적요</th>
			<th>금액</th>
			<th>비고</th>
			<th>삭제</th>
		</tr>
	</thead>
	<tbody class="work_item">
		<tr>
			<td class="del_btn"></td>
			<td>
				<input type="text" name="doc_sub[]"  class="doc_sub insTab"  value="" />
				<input type="hidden" name="id_no[]"  class="id_no"           value="" />
			</td>
			<td><input type="text" name="doc_cost[]" class="doc_cost insTab td_center" value="" /></td>
			<td><input type="text" name="doc_etc[]"  class="doc_etc insTab"  value="" /></td>
			<td class="del_btn delItemBtn hand"></td>
		</tr>
	</tbody>
	<tfoot>
		<tr>
			<th colspan="2">합계</th>
			<th class="tbl_sum"><input type="hidden" name="cost_sum" class="cost_sum" value="" /><span class="cost_sum_txt"></span></th>
			<th colspan="2"></th>
		</tr>
	</tfoot>
</table>

<div class="addItemBtn">
	<button type="button" class="add_five" onclick="rumiTable.add_item(5);">입력항목 5개 추가</button>
</div>
