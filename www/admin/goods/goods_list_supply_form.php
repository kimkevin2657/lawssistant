<?php
if(!defined('_MALLSET_')) exit;

		$sql = " select * from shop_down_excel where muc_code='$muc_code' order by sunbun_c asc";
		//echo $sql;
		$result = sql_query($sql);


?>
<form name="fregform" method="post" onsubmit="return fregform_submit(this);">
<input type="hidden" name="token" value="">
<? if($ms['no']){ ?>
<input type="hidden" name="w" value="u">
<input type="hidden" name="muc_code" value="<?php echo $ms['muc_code']; ?>">
<? } ?>
<h2>기본설정</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">다운로드명칭</th>
		<td class="td_label">
			<label><input type="text" name="title" value="<?php echo $ms['title']; ?>" required itemname="명칭" class="frm_input required" size="60"></label>
		</td>
	</tr>
	</table>
<link rel="stylesheet" href="//netdna.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
        <div class="row">
            <div class="col-sm-5">
                <select name="from[]" id="multiselect" class="form-control" size="8" multiple="multiple">
						<option value="gcode">상품코드[필수]</option>
						<option value="gname">상품명[필수]</option>
						<option value="explan">짧은설명</option>
						<option value="keywords">키워드</option>
						<option value="shop_state">승인상태</option>
						<option value="isopen">판매여부</option>
						<option value="normal_price">시중가격</option>
						<option value="supply_price">공급가격</option>
						<option value="goods_price">판매가격</option>
						<option value="simg1">1번 이미지url</option>
						<option value="simg2">2번 이미지url</option>
						<option value="maker">제조사</option>
						<option value="origin">원산지</option>
						<option value="model">모델명</option>
						<option value="notax">과세유무</option>
						<option value="reg_time">상품등록일시</option>
						<option value="memo">상세설명</option>
						<option value="admin_memo">관리자메모</option>
						<option value="update_time">상품수정일시</option>
						<option value="brand_nm">브랜드명</option>
						<option value="sc_type">배송비유형</option>
						<option value="sc_minimum">조건배송비</option>
						<option value="sc_amt">기본배송비</option>
                </select>
            </div>
            
            <div class="col-sm-2">
                <button type="button" id="multiselect_rightAll" class="btn btn-block"><i class="glyphicon glyphicon-forward"></i></button>
                <button type="button" id="multiselect_rightSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-right"></i></button>
                <button type="button" id="multiselect_leftSelected" class="btn btn-block"><i class="glyphicon glyphicon-chevron-left"></i></button>
                <button type="button" id="multiselect_leftAll" class="btn btn-block"><i class="glyphicon glyphicon-backward"></i></button>
            </div>
            
            <div class="col-sm-5">
                <select name="to[]" id="multiselect_to" class="form-control" size="8" multiple="multiple">
<?
for($i=0; $row=sql_fetch_array($result); $i++) { ?>
<option value="<?=$row['tablecode_c']?>"><?=$row['tablename_c']?></option>

<? } ?>
                </select>

                <div class="row">
                    <div class="col-sm-6">
                        <button type="button" id="multiselect_move_up" class="btn btn-block"><i class="glyphicon glyphicon-arrow-up"></i></button>
                    </div>
                    <div class="col-sm-6">
                        <button type="button" id="multiselect_move_down" class="btn btn-block col-sm-6"><i class="glyphicon glyphicon-arrow-down"></i></button>
                    </div>
                </div>
            </div>
        </div>
</div>
<script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="multiselect.js"></script>
<script type="text/javascript">
jQuery(document).ready(function($) {
    $('#multiselect').multiselect();
});
</script>
</div>


<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
<span class="btn_large"><a href="goods.php?code=list" style="color:white" >상품리스트로 돌아가기</a></span>
<span class="btn_large"><a href="goods.php?code=list_supply" style="color:white" >엑셀양신관리리스트로 돌아가기</a></span>
</div>
</form>

<script>
function fregform_submit(f) {

	f.action = "./goods/goods_list_supply_form_update.php";
    return true;
}
</script>
