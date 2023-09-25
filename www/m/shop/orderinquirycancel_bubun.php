<?php
include_once("./_common.php");
$ms['title'] = 'KG 이니시스 결제취소';
$ms['body_script'] = ' onload="setPAYResult_cel();"';
$od_no	= $_GET['od_no'];

$od = sql_fetch("select * from shop_order where od_no = '$od_no'");
$item = sql_fetch("select * from shop_goods where index_no = '{$od['gs_id']}'");
$od_id = $od['od_id'];

$subject_1 = '주문취소';
$message_1 = $od['name'].'님의
'.$od['od_id'].'
'.$item['gname'].'의 주문이 취소되었습니다.

이용해주셔서 감사합니다.';

/*
결제 단계
value="1"> 입금대기</label>
value="2"> 입금완료</label>
value="3"> 배송준비</label>
value="4"> 배송중</label>
value="5"> 배송완료</label>
value="6"> 취소</label>
value="7"> 반품</label>
value="8"> 교환</label>
value="9"> 환불</label>
*/

if($od['dan'] == '2'){
	if ($od['famiwel_op_no'] > '0' ) {
		$dan = '6';
		$rete = famiwel_status_send_go($dl_comcode,$od['famiwel_od_id'],$od['famiwel_op_no'],$dan);
	}
	aligo_sms('TE_4901', $od['cellphone'], $od['name'], $subject_1, $message_1);
	change_order_status_6($od_no);
}else{

goto_url(MS_MSHOP_URL."/orderinquiryview.php?od_id=$od_id");
}

?>
<body <?php echo $ms['body_script'];?>>
<form name="forderpartcancel" method="post" action="./orderpartcancelupdate.php" onsubmit="return form_check(this);">
<input type="hidden" name="od_id" value="<?php echo $od_id; ?>"> <!-- 주문코드 -->
<input type="hidden" name="od_no" value="<?php echo $od_no; ?>"> <!-- 주문상세번호 -->
<input type="hidden" name="mod_tax_mny" value="<?php echo $od['use_price']; ?>"> <!-- 과세취소 -->
<input type="hidden" name="mod_free_mny" value=""> <!-- 비과세취소 -->
<input type="hidden" name="mod_memo" value="고객변심"></form>

<script>
function form_check(f)
{
    var max_mny = parseInt(2556);
    var tax_mny = parseInt(f.mod_tax_mny.value.replace("/[^0-9]/g", ""));
    var free_mny = 0;
    if(typeof f.mod_free.mny.value != "undefined")
        free_mny = parseInt(f.mod_free_mny.value.replace("/[^0-9]/g", ""));

    if(!tax_mny && !free_mny) {
        alert("과세 취소금액 또는 비과세 취소금액을 입력해 주십시오.",MS_MSHOP_URL."/orderinquiryview.php?od_id=$od_id");
        return false;
    }

    if((tax_mny && free_mny) && (tax_mny + free_mny) > max_mny) {
        alert("과세, 비과세 취소금액의 합을 "+number_format(String(max_mny))+"원 이하로 입력해 주십시오.",MS_MSHOP_URL."/orderinquiryview.php?od_id=$od_id");
        return false;
    }

    if(tax_mny && tax_mny > max_mny) {
        alert("과세 취소금액을 "+number_format(String(max_mny))+"원 이하로 입력해 주십시오.",MS_MSHOP_URL."/orderinquiryview.php?od_id=$od_id");
        return false;
    }

    if(free_mny && free_mny > max_mny) {
        alert("비과세 취소금액을 "+number_format(String(max_mny))+"원 이하로 입력해 주십시오.",MS_MSHOP_URL."/orderinquiryview.php?od_id=$od_id");
        return false;
    }

    return true;
}
</script>
<script type="text/javascript">
function setPAYResult_cel() {
    setTimeout( function() {
        document.forderpartcancel.submit();
    }, 300);
}
</script>


<div id="ajax-loading"><img src="/img/ajax-loader.gif"></div>
<script src="/admin/js/admin.js?ver=20221123061307"></script>
<script src="/js/wrest.js"></script>
</body>
</html>