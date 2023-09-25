<?php
if(!defined('_MALLSET_')) exit;
?>

<form name="forderdelivery" id="forderdelivery" method="post" onsubmit="return forderdelivery_submit(this);" enctype="MULTIPART/FORM-DATA">

<div class="tbl_frm02">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">네이버최저가URL업로드</th>
		<td><a href="<?php echo MS_ADMIN_URL; ?>/goods/naverexcelsample.xls" class="btn_small bx-blue"><i class="fa fa-download"></i> 작성양식 다운로드</a></td>
	</tr>
	<tr>
		<th scope="row">파일 업로드</th>
		<td><input type="file" name="excelfile"></td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="상품업데이트" class="btn_large">
</div>
</form>

<div class="information">
	<h4>도움말</h4>
	<div class="content">
		<div class="desc02">
			<p>ㆍ엑셀자료는 1회 업로드당 최대 1,000건까지 이므로 1,000건씩 나누어 업로드 하시기 바랍니다.</p>
			<p>ㆍ형식은 <strong>작성양식 다운로드</strong>버튼을 클릭하여 엑셀파일을 다운받으신후 상품정보를 입력하시면 됩니다.</p>
			<p>ㆍ수정 완료 후 엑셀파일을 업로드하시면 상품정보가 일괄등록됩니다.</p>
			<p>ㆍ엑셀파일을 저장하실 때는 <strong>Excel 97 - 2003 통합문서 (*.xls)</strong> 로 저장하셔야 합니다.</p>
			<p>ㆍ옵션이 있는 경우 옵션의 명칭과 100% 일치해야 합니다. 글자 하나라도 틀리게 입력되면 상품수정이 성공하지 못합니다.</p>
			<p>ㆍ엑셀데이터는 2번째 라인부터 저장되므로 타이틀은 지우시면 안됩니다.</p>
		</div>
	 </div>
</div>

<script>
function forderdelivery_submit(f)
{
    if(!f.excelfile.value) {
        alert('(*.xls) 파일을 업로드해주십시오.');
        return false;
    }
	
	if(!f.excelfile.value.match(/\.(xls)$/i) && f.excelfile.value) {
        alert('(*.xls) 파일만 등록 가능합니다.');
        return false;
    }

	if(!confirm("상품정보를 업데이트 하시겠습니까?"))
		return false;
	
	f.action = tb_admin_url+"/goods.php?code=excel_ing_update";
	return true;
}
</script>
