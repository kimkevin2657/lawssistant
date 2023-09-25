<?php
if(!defined('_MALLSET_')) exit;

require_once(MS_SHOP_PATH.'/settle_easypay.inc.php');

// 결제대행사별 코드 include (스크립트 등)
require_once(MS_SHOP_PATH.'/easypay/orderform1.php');
?>

<!-- LG유플러스결제 시작 { -->
<p><img src="<?php echo MS_IMG_URL; ?>/orderform.gif"></p>

<p class="pg_cnt mart20">
	※ 주문하실 상품 내역에 <em>수량 및 주문금액</em>이 틀리지 않는지 반드시 확인하시기 바랍니다.
</p>

<div class="tbl_head02 tbl_wrap">
	<table>
	<colgroup>
		<col class="w120">
		<col>
		<col class="w60">
		<col class="w90">
		<col class="w90">
		<col class="w90">
		<col class="w90">
	</colgroup>
	<thead>
	<tr>
		<th scope="col">이미지</th>
		<th scope="col">상품/옵션정보</th>
		<th scope="col">수량</th>
		<th scope="col">상품금액</th>
		<th scope="col">소계</th>
		<th scope="col">쇼핑포인트</th>
		<th scope="col">배송비</th>
	</tr>
	</thead>
	<tbody>
	<?php
	$goods = '';
	$goods_count = -1;

	$sql = " select *
			   from shop_cart
			  where od_id = '$od_id'
				and ct_select = '0'
			  group by gs_id
			  order by index_no ";
	$result = sql_query($sql);

	for($i=0; $row=sql_fetch_array($result); $i++) {
		$rw = get_order($row['od_no']);
		$gs = get_goods($row['gs_id'], 'gname,simg1');

		if(!$goods)
			$goods = preg_replace("/\'|\"|\||\,|\&|\;/", "", $gs['gname']);

		$goods_count++;

		$it_name = stripslashes($gs['gname']);
		$it_options = print_complete_options($row['gs_id'], $od_id);
		if($it_options){
			$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
		}
	?>
	<tr>
		<td class="tac"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?></td>
		<td class="td_name"><?php echo $it_name; ?></td>
		<td class="tac"><?php echo number_format($rw['sum_qty']); ?></td>
		<td class="tar"><?php echo number_format($rw['goods_price']); ?></td>
		<td class="tar"><?php echo number_format($rw['use_price']); ?></td>
		<td class="tar"><?php echo number_format($rw['sum_point']); ?></td>
		<td class="tar"><?php echo number_format($rw['baesong_price']); ?></td>
	</tr>
	<?php
	}

	if($goods_count) $goods .= ' 외 '.$goods_count.'건';

	// 복합과세처리
	$comm_tax_mny  = 0; // 과세금액
	$comm_vat_mny  = 0; // 부가세
	$comm_free_mny = 0; // 면세금액
	if($default['de_tax_flag_use']) {
		$info = comm_tax_flag($od_id);
		$comm_tax_mny  = $info['comm_tax_mny'];
		$comm_vat_mny  = $info['comm_vat_mny'];
		$comm_free_mny = $info['comm_free_mny'];
	}
	?>
	</tbody>
	<tfoot>
	<tr>
		<td class="tar" colspan="7">
			(상품금액 : <strong><?php echo display_price($stotal['price']); ?></strong> +
			배송비 : <strong><?php echo display_price($stotal['baesong']); ?></strong>) -
			(쿠폰할인 : <strong><?php echo display_price($stotal['coupon']); ?></strong> +
			쇼핑포인트결제 : <strong><?php echo display_price($stotal['usepoint']); ?></strong>) =
			총계 : <strong class="fc_red"><?php echo display_price($stotal['useprice']); ?></strong>
		</td>
	</tr>
	</tfoot>
	</table>
</div>

<form name="forderform" id="forderform" method="post" action="<?php echo $order_action_url; ?>" autocomplete="off">

<?php
// 결제대행사별 코드 include (결제대행사 정보 필드)
require_once(MS_SHOP_PATH.'/easypay/orderform2.php');
?>

<section id="sod_fin_orderer">
	<h2 class="anc_tit">주문하시는 분</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col width="140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">이름</th>
			<td><?php echo $od['name']; ?></td>
		</tr>
		<tr>
			<th scope="row">전화번호</th>
			<td><?php echo $od['telephone']; ?></td>
		</tr>
		<tr>
			<th scope="row">핸드폰</th>
			<td><?php echo $od['cellphone']; ?></td>
		</tr>
		<tr>
			<th scope="row">주소</th>
			<td><?php echo print_address($od['addr1'], $od['addr2'], $od['addr3'], $od['addr_jibeon']); ?></td>
		</tr>
		<tr>
			<th scope="row">E-mail</th>
			<td><?php echo $od['email']; ?></td>
		</tr>
		</table>
	</div>
</section>

<section id="sod_fin_receiver">
	<h2 class="anc_tit">받으시는 분</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">이름</th>
			<td><?php echo $od['b_name']; ?></td>
		</tr>
		<tr>
			<th scope="row">전화번호</th>
			<td><?php echo $od['b_telephone']; ?></td>
		</tr>
		<tr>
			<th scope="row">핸드폰</th>
			<td><?php echo $od['b_cellphone']; ?></td>
		</tr>
		<tr>
			<th scope="row">주소</th>
			<td><?php echo print_address($od['b_addr1'], $od['b_addr2'], $od['b_addr3'], $od['b_addr_jibeon']); ?></td>
		</tr>
		<?php if($od['memo']) { ?>
		<tr>
			<th scope="row">전하실 말씀</th>
			<td><?php echo conv_content($od['memo'], 0); ?></td>
		</tr>
		<?php } ?>
		</table>
	</div>
</section>

<section id="sod_fin_pay">
	<h2 class="anc_tit">결제정보</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">결제방법</th>
			<td><?php echo $od['paymethod']; ?></td>
		</tr>
		<tr>
			<th scope="row">결제금액</th>
			<td class="fs14 bold"><?php echo display_price($tot_price); ?></td>
		</tr>
		</table>
	</div>
</section>

<div id="display_pay_button" class="btn_confirm">
	<input type="button" value="결제하기" onclick="forderform_check(this.form);" class="btn_large wset">
    <a href="<?php echo MS_URL; ?>" class="btn_large bx-white">취소</a>
</div>
<div id="display_pay_process" style="display:none">
    <img src="<?php echo MS_IMG_URL; ?>/ajax-loader.gif" alt="">
    <span>주문완료 중입니다. 잠시만 기다려 주십시오.</span>
</div>

</form>

<script type="text/javascript">
    /* 인증창 호출, 인증 요청 */
    function forderform_check()
    {
        var frm_pay = document.forderform;

        /*  주문정보 확인 */
        if( !frm_pay.EP_order_no.value )
        {
            alert("가맹점주문번호를 입력하세요!!");
            frm_pay.EP_order_no.focus();
            return;
        }

        if( !frm_pay.EP_product_amt.value )
        {
            alert("상품금액을 입력하세요!!");
            frm_pay.EP_product_amt.focus();
            return;
        }

        /* UTF-8 사용가맹점의 경우 EP_charset 값 셋팅 필수 */
        if( frm_pay.EP_charset.value == "UTF-8" )
        {
            // 한글이 들어가는 값은 모두 encoding 필수.
            frm_pay.EP_mall_nm.value        = encodeURIComponent( frm_pay.EP_mall_nm.value );
            frm_pay.EP_product_nm.value     = encodeURIComponent( frm_pay.EP_product_nm.value );
            frm_pay.EP_user_nm.value        = encodeURIComponent( frm_pay.EP_user_nm.value );
            frm_pay.EP_user_addr.value      = encodeURIComponent( frm_pay.EP_user_addr.value );
        }


        /* 가맹점에서 원하는 인증창 호출 방법을 선택 */

        if( frm_pay.EP_window_type.value == "iframe" )
        {
            easypay_webpay(frm_pay,"./easypay/iframe_req.php","hiddenifr","0","0","iframe",30);

            if( frm_pay.EP_charset.value == "UTF-8" )
            {
                // encoding 된 값은 모두 decoding 필수.
                frm_pay.EP_mall_nm.value        = decodeURIComponent( frm_pay.EP_mall_nm.value );
                frm_pay.EP_product_nm.value     = decodeURIComponent( frm_pay.EP_product_nm.value );
                frm_pay.EP_user_nm.value        = decodeURIComponent( frm_pay.EP_user_nm.value );
                frm_pay.EP_user_addr.value      = decodeURIComponent( frm_pay.EP_user_addr.value );
            }
        }
        else if( frm_pay.EP_window_type.value == "popup" )
        {
            easypay_webpay(frm_pay,"./easypay/popup_req.php","hiddenifr","","","popup",30);

            if( frm_pay.EP_charset.value == "UTF-8" )
            {
                // encoding 된 값은 모두 decoding 필수.
                frm_pay.EP_mall_nm.value        = decodeURIComponent( frm_pay.EP_mall_nm.value );
                frm_pay.EP_product_nm.value     = decodeURIComponent( frm_pay.EP_product_nm.value );
                frm_pay.EP_user_nm.value        = decodeURIComponent( frm_pay.EP_user_nm.value );
                frm_pay.EP_user_addr.value      = decodeURIComponent( frm_pay.EP_user_addr.value );
            }
        }
    }

</script>
