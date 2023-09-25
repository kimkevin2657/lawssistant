<?php
if(!defined('_MALLSET_')) exit;

require_once(MS_SHOP_PATH.'/settle_kakaopay.inc.php');
?>

<!-- 주문서작성 시작 { -->
<p><img src="<?php echo MS_IMG_URL; ?>/orderform.gif"></p>

<p class="pg_cnt mart20">
	※ 주문하실 상품 내역에 <em>수량 및 주문금액</em>이 틀리지 않는지 반드시 확인하시기 바랍니다.
</p>

<form name="buyform" id="buyform" method="post" onsubmit="return fbuyform_submit(this);" autocomplete="off">

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
	$tot_point = 0;
	$tot_sell_price = 0;
	$tot_anew_price = 0;
	$tot_opt_price = 0;
	$tot_sell_qty = 0;
	$tot_sell_amt = 0;
	$seller_id = array();

	$io_pt_id = $member['pt_id'];
	$io_up_id = $member['up_id'];


    $gradeInfo = sql_fetch(" select min(buy_minishop_grade) min_grade, max(buy_minishop_grade) max_grade FROM shop_goods
WHERE index_no in (select gs_id
				   from shop_cart
				  where index_no IN ({$ss_cart_id})
					and ct_select = '0')");


    $buy_minishop_grade = 0;
    if( $gradeInfo['min_grade'] == $gradeInfo['max_grade'] ) {
        $buy_minishop_grade =  $gradeInfo['min_grade'];
    }
    $grade = minishop::grade($buy_minishop_grade);

	$sql = " select *
			   from shop_cart
			  where index_no IN ({$ss_cart_id})
				and ct_select = '0'
			  group by gs_id
			  order by index_no ";
	$result = sql_query($sql);

	$usablePoint = 0;
	Global $row, $item_sendcost;

	for($i=0; $row=sql_fetch_array($result); $i++) {
		Global $gs,$sr;

	    if( $row['up_id'] ) $io_up_id = $row['up_id'];
        if( $row['pt_id'] ) $io_pt_id = $row['pt_id'];

		$gs = get_goods($row['gs_id']);

		// 합계금액 계산
		$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((io_price + ct_price) * ct_qty))) as price,
                        SUM(ct_kv * ct_qty) as kv,
		                SUM(IF(io_type = 1, (io_price * ct_qty), ((io_supply_price + ct_supply_price) * ct_qty))) as supply_price,
						SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
						SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
						SUM(io_price * ct_qty) as opt_price
				   from shop_cart
				  where gs_id = '$row[gs_id]'
				    and ct_direct = '{$member['id']}'
				    and ct_select = '0'";
		$sum = sql_fetch($sql);

		$it_name = stripslashes($gs['gname']);
		$it_options = print_item_options($row['gs_id'], $member['id']);
		if($it_options){
			$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
		}

		if($is_member) {
			$point = $sum['point'];
		}

		$supply_price = $sum['supply_price'];
		$sell_price = $sum['price'];
		$sell_opt_price = $sum['opt_price'];
		$sell_qty = $sum['qty'];
		$sell_amt = $sum['price'] - $sum['opt_price'];

		// 배송비
		if($gs['use_aff'])
			$sr = get_minishop($gs['mb_id']);
		else
			$sr = get_seller_cd($gs['mb_id']);

		$info = get_item_sendcost($sell_price);

		$item_sendcost[] = $info['pattern'];

		$seller_id[$i] = $gs['mb_id'];

		$href = MS_SHOP_URL.'/view.php?index_no='.$row['gs_id'];
	?>
	<tr>
		<td class="tac">
			<input type="hidden" name="gs_id[<?php echo $i; ?>]" value="<?php echo $row['gs_id']; ?>">
			<input type="hidden" name="io_famiwel_no[<?php echo $i; ?>]" value="<?php echo $row['io_famiwel_no']; ?>">
			<input type="hidden" name="famiwel_mb_id[<?php echo $i; ?>]" value="<?php echo $row['famiwel_mb_id']; ?>">
			<input type="hidden" name="gs_notax[<?php echo $i; ?>]" value="<?php echo $gs['notax']; ?>">
			<input type="hidden" name="gs_price[<?php echo $i; ?>]" value="<?php echo $sell_price; ?>">
            <input type="hidden" name="gs_kv[<?php echo $i; ?>]" value="<?php echo $sum['kv']; ?>">
			<input type="hidden" name="seller_id[<?php echo $i; ?>]" value="<?php echo $gs['mb_id']; ?>">
			<input type="hidden" name="supply_price[<?php echo $i; ?>]" value="<?php echo $supply_price; ?>">
			<input type="hidden" name="sum_point[<?php echo $i; ?>]" value="<?php echo $point; ?>">
			<input type="hidden" name="sum_qty[<?php echo $i; ?>]" value="<?php echo $sell_qty; ?>">
			<input type="hidden" name="cart_id[<?php echo $i; ?>]" value="<?php echo $row['od_no']; ?>">
			<?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?>
		</td>
		<td class="td_name"><?php echo $it_name; ?></td>
		<td class="tac"><?php echo number_format($sell_qty); ?></td>
		<td class="tar"><?php echo number_format($sell_amt); ?></td>
		<td class="tar"><?php echo number_format($sell_price); ?></td>
		<td class="tar"><?php echo number_format($point); ?></td>
		<td class="tar"><?php echo number_format($info['price']); ?></td>
	</tr>
	<?php
		$tot_point += (int)$point;
		if( $gs['buy_minishop_grade'] > 0 ) {
		    $tot_anew_price += (int)$sell_price;
        }
		$tot_sell_price += (int)$sell_price;
		$tot_opt_price += (int)$sell_opt_price;
		$tot_sell_qty += (int)$sell_qty;
		$tot_sell_amt += (int)$sell_amt;

        $usablePoint +=  Good::pointPayAllow($gs, $sell_price, $sell_qty);


		${$gs['mb_id']."_sell_price"} += $sell_price;
		$gs_mb_id_arr[] = $gs['mb_id']."|".$gs['use_aff'];
	}
	$gs_mb_id_arr = array_unique($gs_mb_id_arr);

	// 배송비 검사
	$send_cost = 0;
	$com_send_cost = 0;
	$sep_send_cost = 0;
	$max_send_cost = 0;

	$k = 0;
	$condition = array();

	foreach($item_sendcost as $key) {
		list($userid, $bundle, $price) = explode('|', $key);
		$condition[$userid][$bundle][$k] = $price;
		$k++;
	}

	$com_array = array();
	$val_array = array();
	foreach($condition as $key=>$value) {
		if($condition[$key]['묶음']) {
			$com_send_cost += array_sum($condition[$key]['묶음']); // 묶음배송 합산
			$max_send_cost += max($condition[$key]['묶음']); // 가장 큰 배송비 합산
			$com_array[] = max(array_keys($condition[$key]['묶음'])); // max key
			$val_array[] = max(array_values($condition[$key]['묶음']));// max value
		}
		if($condition[$key]['개별']) {
			$sep_send_cost += array_sum($condition[$key]['개별']); // 묶음배송불가 합산
			$com_array[] = array_keys($condition[$key]['개별']); // 모든 배열 key
			$val_array[] = array_values($condition[$key]['개별']); // 모든 배열 value
		}
		if($max_send_cost>0){
			//$max_send_cost = get_item_tot_sendcost(${$key."_sell_price_r"},$key,0);
			${$key."_sell_price_t"} = get_item_tot_sendcost(${$key."_sell_price_r"},$key,0);
			$max_send_costz += ${$key."_sell_price_t"};
		}
	}


	$baesong_price = get_tune_sendcost($com_array, $val_array);

	$send_cost = $com_send_cost + $sep_send_cost; // 총 배송비합계
	$tot_send_cost = $max_send_costz + $sep_send_cost; // 최종배송비
		//공급사별로 배송비 체크하기
		foreach($gs_mb_id_arr as $gKey=>$gVal){
			$arr2 = explode("|",$gVal);
			//$tot_send_cost = get_item_tot_sendcost(${$arr2[0]."_sell_price"},$arr2[0],$arr2[1]);
		}


	if(!$tot_send_cost)		$baesong_price = 0;

	$tot_final_sum = $send_cost - $tot_send_cost; // 배송비할인
	$tot_price = $tot_sell_price + $tot_send_cost; // 결제예정금액
	$usablePoint = $tot_price; // 예외처리 : 포인트결제 숫자가 사라지는현상 및 전액사용버튼 불가 수정요청
	?>
	</tbody>
	</table>
</div>

<div id="sod_bsk_tot">
	<table class="wfull">
	<tr>
		<td class="w50p">
			<h2 class="anc_tit">장바구니에 담긴 상품통계</h2>
			<div class="tbl_frm01 tbl_wrap">
				<table>
				<colgroup>
					<col class="w140">
					<col class="w140">
					<col>
				</colgroup>
				<tr>
					<th scope="row">쇼핑포인트</th>
					<td class="tar">적립 쇼핑포인트</td>
					<td class="tar bl"><?php echo display_point($tot_point); ?></td>
				</tr>
				<tr>
					<th scope="row" rowspan="3">상품</th>
					<td class="tar">상품금액 합계</td>
					<td class="tar bl"><?php echo display_price2($tot_sell_amt); ?></td>
				</tr>
				<tr>
					<td class="tar">옵션금액 합계</td>
					<td class="tar bl"><?php echo display_price2($tot_opt_price); ?></td>
				</tr>
				<tr>
					<td class="tar">주문수량 합계</td>
					<td class="tar bl"><?php echo display_qty($tot_sell_qty); ?></td>
				</tr>
				<tr>
					<td class="list2 tac bold" colspan="2">현재 쇼핑포인트 보유잔액</td>
					<td class="list2 tar bold"><?php echo display_point($member['point']); ?></td>
				</tr>
				</table>
			</div>
		</td>
		<td class="w50p">
			<h2 class="anc_tit">결제 예상금액 통계</h2>
			<div class="tbl_frm01 tbl_wrap">
				<table>
				<colgroup>
					<col class="w140">
					<col class="w140">
					<col>
				</colgroup>
				<tr>
					<th scope="row">주문</th>
					<td class="tar">(A) 주문금액 합계</td>
					<td class="tar bl"><?php echo display_price2($tot_sell_price); ?></td>
				</tr>
				<tr>
					<th scope="row" rowspan="3">배송비</th>
					<td class="tar">상품 배송비합계</td>
					<td class="tar bl"><?php echo display_price2($send_cost); ?></td>
				</tr>
				<tr>
					<td class="tar">배송비할인</td>
					<td class="tar bl">(-) <?php echo display_price2($tot_final_sum); ?></td>
				</tr>
				<tr>
					<td class="tar">(B) 최종배송비</td>
					<td class="tar bl"><?php echo display_price2($tot_send_cost); ?></td>
				</tr>
				<tr>
					<td class="list2 tac bold" colspan="2">결제예정금액 (A+B)</td>
					<td class="list2 tar bold fc_red"><?php echo display_price2($tot_price); ?></td>
				</tr>
				</table>
			</div>
		</td>
	</tr>
	</table>
</div>
<input type="hidden" name="token" value="<?php echo $token; ?>">
<input type="hidden" name="ss_cart_id" value="<?php echo $ss_cart_id; ?>">
<input type="hidden" name="mb_point" value="<?php echo $member['point']; ?>">
<input type="hidden" name="mb_sp_point" value="<?php echo $member['sp_point']; ?>">
<input type="hidden" name="mb_ppay" value="<?php echo $member['pay']; ?>">
<!--<input type="hidden" name="pt_id" value="--><?php //echo $io_pt_id; ?><!--">-->
<!--<input type="hidden" name="up_id" value="--><?php //echo $io_up_id; ?><!--">-->
<input type="hidden" name="shop_id" value="<?php echo $pt_id; ?>">
<input type="hidden" name="coupon_total" value="0">
<input type="hidden" name="coupon_price" value="">
<input type="hidden" name="coupon_lo_id" value="">
<input type="hidden" name="coupon_cp_id" value="">
<input type="hidden" name="baesong_price" value="<?php echo $baesong_price; ?>">
<input type="hidden" name="salebaesong_price" value="<?php echo $tot_final_sum; ?>">
<input type="hidden" name="baesong_price2" value="0">
<input type="hidden" name="org_price" value="<?php echo $tot_price; ?>">
<?php if ( get_session('ss_expire') != 'after-order-complete' &&  $grade && $grade['gb_anew_price'] <= $tot_sell_price ) : ?>
<?php
$myGrade  = minishop::findTopId($member['id']);

if( isset($myGrade[$grade['gb_no']]) ) {
    $my_top_id = $myGrade[$grade['gb_no']];
} else {
    $my_top_id = $encrypted_admin;
}
$io_pt_id = $my_top_id;
$io_up_id = $my_top_id;

if( $io_pt_id ) $ptnm = get_member($io_pt_id, 'name');
if( $io_up_id ) $upnm = get_member($io_up_id, 'name');

    $curr_grade = Member::get_grade($member['grade']);
    $anew_grade = Member::get_grade_by_price($tot_sell_amt);

    $upgr_price = $curr_grade['gb_anew_price'] + $tot_sell_amt;
    $upgr_grade = Member::get_grade_by_price($upgr_price);
?>
    <input type="hidden" id="buy_minishop_grade" name="buy_minishop_grade" value="<?php echo $grade['gb_no']; ?>">
    <input type="hidden" id="current_grade" name="current_grade" value="<?php echo $member['grade']; ?>">
    <section class="mart30">
        <h2 class="anc_tit">승급 / 후원&bull;추천 ID</h2>
        <div class="tbl_frm01 tbl_wrap">
            <table>
                <tbody>
                <tr>
                    <th>신규 / 승급</th>
                    <td>
                        <input type="radio" name="buy_minishop_type" id="buy_minishop_type_anew" data-for-grade="<?php echo $anew_grade['gb_no']; ?>" value="anew" checked>
                        <label for="buy_minishop_type_anew">신규 하위 가맹 가입(<?php echo $anew_grade['gb_name']; ?>)</label>
                        <?php


                        if( $upgr_grade && $upgr_grade['gb_no'] < $curr_grade['gb_no']) : ?>
                            <input type="radio" name="buy_minishop_type" id=buy_"minishop_type_upgrade" data-for-grade="<?php echo $upgr_grade['gb_no']; ?>" value="upgrade">
                            <label for="buy_minishop_type_upgrade">가맹등급 승급(<?php echo  $upgr_grade['gb_name'] ?>)</label>
                        <?php
                        endif; ?>
                        <script>
                            (function($){
                                $(function(){
                                    $('[name=buy_minishop_type]').on('click', function(){
                                        $('#buy_minishop_grade').val( $(this).data('forGrade') );
                                        if( $(this).val() == 'upgrade' ) {
                                            $('.holder--anew-minishop').addClass('dpn');
                                        } else {
                                            $('.holder--anew-minishop').removeClass('dpn');
                                        }
                                    });
                                });
                            }(jQuery));
                        </script>
                    </td>
                </tr>
                <tr class="holder--anew-minishop">
                    <th><label for="pt_id">후원ID</label></th>
                    <td><input type="text" id="pt_id" itemname="후원ID" name="pt_id" readonly required value="<?php echo $io_pt_id; ?>" class="frm_input" size="16"/><input type="text" id="pt_nm" name="pt_nm" readonly value="<?php echo $ptnm['name']; ?>" class="frm_input" size="10"/>
                        <button type="button" class="btn_small grey holder--find-user" data-for="pt" >회원검색</button></td>
                </tr>
                <tr class="holder--anew-minishop <?php echo false && $grade['gb_no'] == $member['grade'] && is_minishop($member['id']) ? ' dpn ' : ''; ?>">
                    <th><label for="up_id">추천ID</label></th>
                    <td><input type="text" id="up_id" itemname="추천ID" name="up_id" readonly value="<?php echo $io_up_id; ?>" class="frm_input" size="16"/><input type="text" id="up_nm" name="up_nm" readonly value="<?php echo $upnm['name']; ?>" class="frm_input" size="10"/>
                        <button type="button" class="btn_small grey holder--find-user" data-for="up" >회원검색</button></td>
                </tr>
                </tbody>
            </table>
            <?php include_once(MS_PLUGIN_PATH.'/zentool/minishop/inc.find_user.php'); ?>

            <script>
                (function($){
                    $(document).ready(function(){
                        $('#up_id, #pt_id').on('blur', function(){
                            var grade = $('#buy_minishop_grade').val();
                            var id    = $(this).val();
                            if( id == '' || id == 'admin' || id == 'hellok' ) return;
                            $.ajax({
                                url : '/plugin/zentool/minishop/ajax.find_up_id.php',
                                data: { 'grade' : grade, 'id' : id },
                                type: 'POST',
                                // contentType: "application/json; charset=UTF-8",
                                dataType: 'json',
                                success : function(data){
                                    if( data.result == 'success' ) {

                                    } else {
                                        $(this).val('');
                                        alert(data.data);
                                    }
                                }.bind(this),
                                error   : function(res, data){
                                    console.log( arguments );
                                    $(this).val('');
                                }.bind(this)
                            });
                        });
                    });
                }(jQuery));
            </script>
        </div>
    </section>
<?php else : ?>
    <input type="hidden" name="pt_id" value="<?php echo $io_pt_id; ?>">
    <input type="hidden" name="up_id" value="<?php echo $io_up_id; ?>">
<?php endif; ?>
<section id="sod_fin_orderer">
	<h2 class="anc_tit">주문하시는 분</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<?php if(!$is_member) { // 비회원이면 ?>
		<tr>
			<th scope="row">비밀번호</th>
			<td>
				<input type="password" name="od_pwd" required itemname="비밀번호" class="frm_input required" size="20">
				<span class="frm_info">영,숫자 3~20자 (주문서 조회시 필요)</span>
			</td>
		</tr>
		<?php } ?>
		<tr>
			<th scope="row">이름</th>
			<td><input type="text" name="name" value="<?php echo $member['name']; ?>" required itemname="이름" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">전화번호</th>
			<td><input type="text" name="telephone" value="<?php echo $member['telephone']; ?>" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">핸드폰</th>
			<td><input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" required itemname="핸드폰" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">주소</th>
			<td>
				<div>
					<input type="text" name="zip" value="<?php echo $member['zip']; ?>" required itemname="우편번호" class="frm_input required" maxLength="5" size="8"> <a href="javascript:win_zip('buyform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">주소검색</a>
				</div>
				<div class="padt5">
					<input type="text" name="addr1" value="<?php echo $member['addr1']; ?>" required itemname="주소" class="frm_input required" size="60" readonly> 기본주소
				</div>
				<div class="padt5">
					<input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" class="frm_input" size="60"> 상세주소
				</div>
				<div class="padt5">
					<input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" class="frm_input" size="60" readonly> 참고항목
					<input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">E-mail</th>
			<td><input type="text" name="email" value="<?php echo $member['email']; ?>" _required email itemname="E-mail" class="frm_input _required" size="30"></td>
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
			<th scope="row">배송지선택</th>
			<td class="td_label">
				<label><input type="radio" name="ad_sel_addr" value="1"> 주문자와 동일</label>
				<label><input type="radio" name="ad_sel_addr" value="2"> 신규배송지</label>
				<?php if($is_member) { ?>
				<label><input type="radio" name="ad_sel_addr" value="3"> 배송지목록</label>
				<?php } ?>
			</td>
		</tr>
		<tr>
			<th scope="row">이름</th>
			<td><input type="text" name="b_name" required itemname="이름" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">전화번호</th>
			<td><input type="text" name="b_telephone" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">핸드폰</th>
			<td><input type="text" name="b_cellphone" required itemname="핸드폰" class="frm_input required" size="20"></td>
		</tr>
		<tr>
			<th scope="row">주소</th>
			<td>
				<div>
					<input type="text" name="b_zip" required itemname="우편번호" class="frm_input required" maxLength="5" size="8"> <a href="javascript:win_zip('buyform', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');" class="btn_small grey">주소검색</a>
				</div>
				<div class="padt5">
					<input type="text" name="b_addr1" required itemname="주소" class="frm_input required" size="60" readonly> 기본주소
				</div>
				<div class="padt5">
					<input type="text" name="b_addr2" class="frm_input" size="60"> 상세주소
				</div>
				<div class="padt5">
					<input type="text" name="b_addr3" class="frm_input" size="60" readonly> 참고항목
					<input type="hidden" name="b_addr_jibeon" value="">
				</div>
			</td>
		</tr>
		<tr>
			<th scope="row">전하실말씀</th>
			<td>
				<select name="sel_memo">
					<option value="">요청사항 선택하기</option>
					<option value="부재시 경비실에 맡겨주세요.">부재시 경비실에 맡겨주세요</option>
					<option value="빠른 배송 부탁드립니다.">빠른 배송 부탁드립니다.</option>
					<option value="부재시 핸드폰으로 연락바랍니다.">부재시 핸드폰으로 연락바랍니다.</option>
					<option value="배송 전 연락바랍니다.">배송 전 연락바랍니다.</option>
				</select>
				<textarea name="memo" class="frm_textbox h60 mart5" rows="3"></textarea>
				<span class="frm_info"><strong class="fc_red">"택배사원"</strong>에 전하실 말씀을 써주세요~!<br>C/S관련문의는 고객센터에 작성해주세요. 이곳에 남기시면 확인이 불가능합니다.</span>
			</td>
		</tr>
		</table>
	</div>
</section>

<section id="sod_fin_pay">
	<h2 class="anc_tit">결제정보 입력</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">결제방법</th>
			<td class="td_label">
				<?php
				$escrow_title = "";
				if($default['de_escrow_use']) {
					$escrow_title = "에스크로 ";
				}

				if($is_kakaopay_use) {
					echo '<input type="radio" name="paymethod" id="paymethod_kakaopay" value="KAKAOPAY" onclick="calculate_paymethod(this.value);"> <label for="paymethod_kakaopay" class="kakaopay_icon">카카오페이</label>'.PHP_EOL;
				}
				if($default['de_bank_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_bank" value="무통장" onclick="calculate_paymethod(this.value);"> <label for="paymethod_bank">무통장입금</label>'.PHP_EOL;
				}
				if($default['de_card_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_card" value="신용카드" onclick="calculate_paymethod(this.value);"> <label for="paymethod_card">신용카드</label>'.PHP_EOL;
				}
				if($default['de_hp_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_hp" value="휴대폰" onclick="calculate_paymethod(this.value);"> <label for="paymethod_hp">휴대폰</label>'.PHP_EOL;
				}
				if($default['de_iche_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_iche" value="실시간계좌이체" onclick="calculate_paymethod(this.value);"> <label for="paymethod_iche">'.$escrow_title.'실시간계좌이체</label>'.PHP_EOL;
				}
				if($default['de_vbank_use']) {
					echo '<input type="radio" name="paymethod" id="paymethod_vbank" value="가상계좌" onclick="calculate_paymethod(this.value);"> <label for="paymethod_vbank">'.$escrow_title.'가상계좌</label>'.PHP_EOL;
				}
        //echo $gs['point_pay_allow'];
				if($is_member && $gs['point_pay_allow'] && $usablePoint > $config['usepoint'] && ($tot_price <= $member['point'])) {
					echo '<input type="radio" name="paymethod" id="paymethod_point" value="쇼핑포인트" onclick="calculate_paymethod(this.value);"> <label for="paymethod_point">쇼핑포인트 결제</label>'.PHP_EOL;
				}
        if($is_member && ($tot_price <= $member['sp_point']) && defined('USE_SHOPPING_PAY_BUY') && USE_SHOPPING_PAY_BUY && !($grade && $grade['gb_anew_price'] <= $tot_anew_price)) {
            echo '<input type="radio" name="paymethod" id="paymethod_sp_point" value="쇼핑페이" onclick="calculate_paymethod(this.value);"> <label for="paymethod_sp_point">쇼핑페이결제</label>'.PHP_EOL;
        }
        if( $tot_price <= $member['pay'] ) {
            echo '<input type="radio" name="paymethod" id="paymethod_ppay" value="마일리지" onclick="calculate_paymethod(this.value);"> <label for="paymethod_ppay">마일리지</label>'.PHP_EOL;
        }

				// PG 간편결제
				if($default['de_easy_pay_use']) {
					switch($default['de_pg_service']) {
                        case 'easypay':
                            $pg_easy_pay_name = "EASYPAY";
                            break;
						case 'lg':
							$pg_easy_pay_name = 'PAYNOW';
							break;
						case 'inicis':
							$pg_easy_pay_name = 'KPAY';
							break;
						case 'kcp':
							$pg_easy_pay_name = 'PAYCO';
							break;
					}

					echo '<input type="radio" name="paymethod" id="paymethod_easy_pay" value="간편결제" onclick="calculate_paymethod(this.value);"><label for="paymethod_easy_pay" class="'.$pg_easy_pay_name.'">'.$pg_easy_pay_name.'</label>'.PHP_EOL;
				}
				?>
				
			</td>
		</tr>
		<tr>
			<th scope="row">합계</th>
			<td class="bold"><?php echo display_price($tot_price); ?></td>
		</tr>
		<tr>
			<th scope="row">추가배송비</th>
			<td>
				<strong><span id="send_cost2">0</span>원</strong>
				<span class="fc_999">(지역에 따라 추가되는 도선료 등의 배송비입니다.)</span>
			</td>
		</tr>
		<?php
		if($is_member && $config['coupon_yes']) { // 보유쿠폰
			$cp_count = get_cp_precompose($member['id']);
		?>
		<tr>
			<th scope="row">할인쿠폰</th>
			<td>(-) <strong><span id="dc_amt">0</span>원 <span id="dc_cancel" style="display:none"><a href="javascript:coupon_cancel();">X</a></span></strong>
			<span id="dc_coupon"><a href="<?php echo MS_SHOP_URL; ?>/ordercoupon.php" onclick="win_open(this,'win_coupon','670','500','yes');return false"><span class='fc_198 tu'>사용 가능 쿠폰 <?php echo $cp_count[3]; ?>장</a> </span></span></td>
		</tr>
		<?php } ?>
		<?php
        // dd( compact('is_member', 'usablePoint', 'config'));
		if($is_member && $gs['point_pay_allow'] /*&& $usablePoint > $config['usepoint']*/) {  ?>
		<tr>
			<th scope="row">쇼핑포인트 결제</th>
			<td>
				<input type="text" name="use_point" value="0" class="frm_input" minval="<?php echo $config['usepoint'] ?>" maxval="<?php echo $usablePoint ?>" size="12" onkeyup="calculate_point(this.value);this.value=number_format(this.value);" style="font-weight:bold;"> 원 보유 쇼핑포인트 : <?php echo display_point($member['point']); ?>
				<?php if($config['usepoint']) { ?>
                    (<strong><?php echo display_point($config['usepoint']); ?></strong> 부터 <strong><?php echo display_point($usablePoint); ?></strong>까지  사용가능)
				<?php } ?>

                <button type="button" class="btn_small gray holder--btn-use-all">쇼핑포인트 전액사용</button> <a href="/bbs/board.php?bo_table=chargepoint" class="btn_small gray">쇼핑포인트 충전</a>
                <script>
                    (function($){
                        $(function(){
                            $('.holder--btn-use-all').on('click', function(){
                                var priceInfo = get_price_info();
                                var $usePoint = $('[name=use_point]');
                                var maxPoint  = priceInfo.mb_point;
                                var ablePoint =priceInfo.able_use_point();
                                if( maxPoint > ablePoint ) maxPoint = ablePoint;
                                $usePoint.val( number_format(maxPoint) );
                                calculate_point($usePoint.attr('maxval'));
                            });
                        });
                    }(jQuery));
                </script>
			</td>
		</tr>
       <?php } else { ?>
       <input type="hidden" minval="<?php echo $config['usepoint'] ?>"
              maxval="<?php echo $usablePoint ?>" name="use_point" value="0" class="frm_input">
		<?php } ?>
        <?php
        // dd( compact('is_member', 'usablePoint', 'config'));
        if($is_member && defined('USE_SHOPPING_PAY_BUY') && USE_SHOPPING_PAY_BUY&& !($grade && $grade['gb_anew_price'] <= $tot_anew_price)) { ?>
            <tr>
                <th scope="row">쇼핑페이결제</th>
                <td>
                    <input type="text" name="use_sp_point" value="0" class="frm_input" minval="<?php echo '0' ?>" maxval="<?php echo $member['sp_point'] ?>" size="12" onkeyup="calculate_sp_point(this.value);this.value=number_format(this.value);" style="font-weight:bold;"> 원 보유 쇼핑페이 : <?php echo display_point($member['sp_point']); ?>
                    <button type="button" class="btn_small gray holder--btn-use-sp-all">쇼핑페이 전액사용</button>
                    <script>
                        (function($){
                            $(function(){
                                $('.holder--btn-use-sp-all').on('click', function(){
                                    var priceInfo = get_price_info();
                                    var $usePoint = $('[name=use_sp_point]');
							        var maxVal = priceInfo.mb_sp_point;
									var ablePoint =priceInfo.able_use_point();
                                    $usePoint.val( number_format(ablePoint) );
                                    calculate_point(ablePoint);
                                });
                            });
                        }(jQuery));
                    </script>
                </td>
            </tr>
        <?php } else { ?>
            <input type="hidden"  minval="<?php echo '0' ?>"
                   maxval="<?php echo $member['sp_point'] ?>" name="use_sp_point" value="0" class="frm_input">
        <?php } ?>
        <?php if(is_minishop($member['id']) && $KGB == "KOREA") { ?>
			<tr>
                <th scope="row">마일리지</th>
                <td>
                    <input type="text" name="use_ppay" value="0" onkeyup="calculate_ppay(this.value); " onfocus="this.value=no_comma(this.value);" onblur="this.value=number_format(this.value);" maxval="<?php echo $member['pay']; ?>" class="frm_input w100"> (잔액 : <b><?php echo display_price($member['pay']); ?>)</b>

                    <button type="button" class="btn_small gray holder--btn-use-ppay-all">마일리지 전액사용</button>

                    <script>
                        (function($){
                            $(function(){
                                $('.holder--btn-use-ppay-all').on('click', function(){
                                    var priceInfo = get_price_info();
                                    var $usePoint = $('[name=use_ppay]');
                                    var maxPoint  = priceInfo.mb_ppay;
                                    var ablePoint =priceInfo.able_use_ppay();
                                    if( maxPoint > ablePoint ) maxPoint = ablePoint;
                                    $usePoint.val( number_format(maxPoint) );
                                    calculate_ppay($usePoint.attr('maxval'));
                                });
                            });
                        }(jQuery));
                    </script>

                </td>
            </tr>
        <?php } else { ?>
        <input type="hidden" name="use_ppay" max-value="0" value="0">
        <?php } ?>
		<tr>
			<th scope="row">총 결제금액</th>
			<td>
				<input type="text" name="tot_price" value="<?php echo number_format($tot_price); ?>" class="frm_input" size="12" readonly style="font-weight:bold;color:#ec0e03;"> 원
			</td>
		</tr>
		</table>
	</div>
</section>

<section id="bank_section" style="display:none;">
	<h2 class="anc_tit">입금하실 계좌</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">입금계좌선택</th>
			<td><?php echo get_bank_account("bank"); ?></td>
		</tr>
		<tr>
			<th scope="row">입금자명</th>
			<td><input type="text" name="deposit_name" value="<?php echo $member['name']; ?>" class="frm_input" size="12"></td>
		</tr>
		</table>
	</div>
</section>

<!--section id="return_bank_section" style="display:none;">
<br><br>
	<h2 class="anc_tit">환불받으실 계좌</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">은행명</th>
			<td><input type="text" name="refund_account_bank_name" value="<?php echo $member['refund_account_bank_name']; ?>" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">계좌번호</th>
			<td><input type="text" name="refund_account" value="<?php echo $member['refund_account']; ?>" class="frm_input" size="30"></td>
		</tr>
		<tr>
			<th scope="row">예금자명</th>
			<td><input type="text" name="refund_account_name" value="<?php echo $member['refund_account_name']; ?>" class="frm_input" size="20"></td>
		</tr>
		</table>
	</div>
</section-->

<?php if(!$config['company_type']) { ?>
<section id="tax_section" style="display:none;">
	<h2 class="anc_tit">증빙서류발급 요청</h2>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w140">
			<col>
		</colgroup>
		<tr>
			<th scope="row">현금영수증발행</th>
			<td class="td_label">
				<input type="radio" id="taxsave_1" name="taxsave_yes" value="Y" onclick="tax_bill(1);">
				<label for="taxsave_1">개인 소득공제용</label>
				<input type="radio" id="taxsave_2" name="taxsave_yes" value="S" onclick="tax_bill(2);">
				<label for="taxsave_2">사업자 지출증빙용</label>
				<input type="radio" id="taxsave_3" name="taxsave_yes" value="N" onclick="tax_bill(3);" checked>
				<label for="taxsave_3">미발행</label>
			</td>
		</tr>
		<tr id="taxsave_fld_1" style="display:none">
			<th scope="row">핸드폰번호</th>
			<td>
				<input type="text" name="tax_hp" class="frm_input" size="20">
				<span class="frm_info">
					현금영수증은 1원이상 현금 구매시 발급이 가능합니다.<br>
					현금영수증은 구매대금 입금확인일 다음날 발급됩니다.<br>
					현금영수증 홈페이지 :<A href="http://taxsave.go.kr/" target="_balnk"><b>http://www.taxsave.go.kr</b></a>
				</span>
			</td>
		</tr>
		<tr id="taxsave_fld_2" style="display:none">
			<th scope="row">사업자등록번호</th>
			<td><input type="text" name="tax_saupja_no" class="frm_input" size="20"></td>
		</tr>
		<tr>
			<th scope="row">세금계산서발행</th>
			<td class="td_label">
				<input type="radio" id="taxbill_1" name="taxbill_yes" value="Y" onclick="tax_bill(4);">
				<label for="taxbill_1">발행요청</label>
				<input type="radio" id="taxbill_2" name="taxbill_yes" value="N" onclick="tax_bill(5);" checked>
				<label for="taxbill_2">미발행</label>
			</td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">사업자등록번호</td>
			<td><input type="text" name="company_saupja_no" size="20" class="frm_input"></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">상호(법인명)</th>
			<td><input type="text" name="company_name" class="frm_input" size="20"> 예 : <?php echo $config['company_name']; ?></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">대표자</th>
			<td><input type="text" name="company_owner" class="frm_input" size="20"> 예 : 홍길동</td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">사업장주소</th>
			<td><input type="text" name="company_addr" class="frm_input" size="60"></td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">업태</th>
			<td><input type="text" name="company_item" class="frm_input" size="20"> 예 : 도소매</td>
		</tr>
		<tr class="taxbill_fld">
			<th scope="row">종목</th>
			<td><input type="text" name="company_service" class="frm_input" size="20"> 예 : 전자부품</td>
		</tr>
		</table>
	</div>
</section>
<?php } ?>

<?php if(!$is_member) { ?>
<section id="guest_privacy">
	<h3 class="anc_tit">개인정보 수집 및 이용</h3>
	<p>비회원으로 주문 시 쇼핑포인트 적립 및 추가 혜택을 받을 수 없습니다.</p>
	<div class="tbl_head02 tbl_wrap">
		<table>
		<thead>
		<tr>
			<th scope="col">목적</th>
			<th scope="col">항목</th>
			<th scope="col">보유기간</th>
		</tr>
		</thead>
		<tbody>
		<tr>
			<td>이용자 식별 및 본인 확인</td>
			<td>이름, 비밀번호</td>
			<td>5년(전자상거래등에서의 소비자보호에 관한 법률)</td>
		</tr>
		<tr>
			<td>배송 및 CS대응을 위한 이용자 식별</td>
			<td>주소, 연락처(이메일, 휴대전화번호)</td>
			<td>5년(전자상거래등에서의 소비자보호에 관한 법률)</td>
		</tr>
		</tbody>
		</table>
	</div>

	<fieldset id="guest_agree">
		<input type="checkbox" id="agree" value="1">
		<label for="agree">개인정보 수집 및 이용 내용을 읽었으며 이에 동의합니다.</label>
	</fieldset>
</section>
<?php } ?>

<div class="btn_confirm">
	<input type="submit" value="주문하기" class="btn_large wset">
	<a href="<?php echo MS_SHOP_URL; ?>/cart.php" class="btn_large bx-white">취소</a>
</div>

</form>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script>
$(function() {
    $("input[name=b_addr2]").focus(function() {
        var zip = $("input[name=b_zip]").val().replace(/[^0-9]/g, "");
        if(zip == "")
            return false;

        var code = String(zip);
        calculate_sendcost(code);
    });

	// 배송지선택
	$("input[name=ad_sel_addr]").on("click", function() {
		var addr = $(this).val();

		if(addr == "1") {
			gumae2baesong(true);
		} else if(addr == "2") {
			gumae2baesong(false);
		} else {
			win_open(tb_shop_url+'/orderaddress.php','win_address', 600, 600, 'yes');
		}
	});

    $("select[name=sel_memo]").change(function() {
         $("textarea[name=memo]").val($(this).val());
    });
});

// 도서/산간 배송비 검사
function calculate_sendcost(code) {
    $.post(
        tb_shop_url+"/ordersendcost.php",
        { zipcode: code },
        function(data) {
            $("input[name=baesong_price2]").val(data);
            $("#send_cost2").text(number_format(String(data)));

            calculate_order_price();
        }
    );
}

function get_price_info(){

    var priceInfo = {
        sell_price : 0,
        send_cost2 : 0,
        mb_coupon : 0,
        mb_point : 0,
        mb_sp_point : 0,
        mb_ppay : 0,
        use_point : 0,
        use_sp_point : 0,
        use_ppay : 0,
        tot_price : function(){
            return this.sell_price + this.send_cost2 - ( this.mb_coupon + this.use_point + this.use_sp_point  + this.use_ppay);
        },
        able_use_point : function(){
            return this.sell_price + this.send_cost2 - ( this.mb_coupon + this.use_sp_point + this.use_ppay);
        },
        able_use_sp_point : function(){
            return this.sell_price + this.send_cost2 - ( this.mb_coupon + this.use_point  + this.use_ppay);
        },
        able_use_ppay: function(){
            return this.sell_price + this.send_cost2 - ( this.mb_coupon + this.use_point + this.use_sp_point );
        },
        init : function(){

            var sell_price   = parseInt(no_comma($("input[name=org_price]").val())); // 합계금액
            var send_cost2   = parseInt(no_comma($("input[name=baesong_price2]").val())); // 추가배송비
            var mb_coupon    = parseInt(no_comma($("input[name=coupon_total]").val())); // 쿠폰할인
            var mb_point     = parseInt(no_comma($("input[name=mb_point]").val()));
            var mb_sp_point  = parseInt(no_comma($("input[name=mb_sp_point]").val()));
            var mb_ppay      = parseInt(no_comma($("input[name=mb_ppay]").val()));
            var use_point    = parseInt(no_comma($("input[name=use_point]").val()));
            var use_sp_point = parseInt(no_comma($("input[name=use_sp_point]").val()));
            var use_ppay     = parseInt(no_comma($("input[name=use_ppay]").val()));

            if( isNaN(sell_price) ) sell_price = 0;
            if( isNaN(send_cost2) ) send_cost2 = 0;
            if( isNaN(mb_coupon) ) mb_coupon = 0;
            if( isNaN(mb_point) ) mb_point = 0;
            if( isNaN(mb_sp_point) ) mb_sp_point = 0;
            if( isNaN(mb_ppay) ) mb_ppay = 0;
            if( isNaN(use_point) ) use_point = 0;
            if( isNaN(use_sp_point) ) use_sp_point = 0;
            if( isNaN(use_ppay) ) use_ppay = 0;

            this.sell_price = sell_price;
            this.send_cost2 = send_cost2;
            this.mb_coupon = mb_coupon;
            this.mb_point = mb_point;
            this.mb_sp_point = mb_sp_point;
            this.mb_ppay = mb_ppay;
            this.use_point = use_point;
            this.use_sp_point = use_sp_point;
            this.use_ppay = use_ppay;
        }
    };

    priceInfo.init();
    return priceInfo;
}

function calculate_ppay(){

    var pInfo = get_price_info();

    var $use_ppay= $('[name=use_ppay]');

    if( pInfo.use_ppay > pInfo.mb_ppay ) {
        $use_ppay.val(pInfo.mb_ppay);
        pInfo.init();
    }
    if( pInfo.use_ppay  > pInfo.able_use_ppay() ) {
        $use_ppay.val(pInfo.able_use_ppay());
        pInfo.init();
    }
    calculate_order_price();
    pInfo.init();
    if( pInfo.tot_price() == 0 ) {
        $('#paymethod_ppay').prop('checked', true);
    } else {
        $('#paymethod_ppay').prop('checked', false);
    }
}
function calculate_order_price() {
    var priceInfo = get_price_info();
	$("input[name=tot_price]").val(number_format(String(priceInfo.tot_price())));
}

var doubleSubmitFlag = false;
function doubleSubmitCheck(){
    if(doubleSubmitFlag){
        return doubleSubmitFlag;
    }else{
        doubleSubmitFlag = true;
        return false;
    }
}

function fbuyform_submit(f) {

    errmsg = "";
    errfld = "";

	var min_point	= parseInt("<?php echo $config['usepoint']; ?>");
    var max_point	= parseInt("<?php echo $usablePoint; ?>");

    var priceInfo   = get_price_info();

	var sell_price	= priceInfo.sell_price;
	var send_cost2	= priceInfo.send_cost2;
	var mb_coupon	= priceInfo.mb_coupon;
	var mb_point	= priceInfo.mb_point;
	var mb_sp_point = priceInfo.mb_sp_point;
    var use_point   = priceInfo.use_point;
    var use_sp_point= priceInfo.use_sp_point;
    var use_ppay    = priceInfo.use_ppay;

	var tot_price	= priceInfo.tot_price();

	console.log( [priceInfo, tot_price] );

    if(f.use_sp_point.value == '') {
        alert('쇼핑페이사용 금액을 입력하세요. 사용을 원치 않을경우 0을 입력하세요.');
        f.use_sp_point.value = 0;
        f.use_sp_point.focus();
        return false;
    }

    if(use_sp_point > mb_sp_point) {
        alert('쇼핑페이사용 금액은 현재 보유쇼핑페이 보다 클수 없습니다.');
        f.tot_price.value = number_format(String(tot_price));
        f.use_sp_point.value = 0;
        f.use_sp_point.focus();
        return false;
    }

    if(use_sp_point > priceInfo.able_use_sp_point()) {
        alert('쇼핑페이사용 금액은 최종결제금액 보다 클수 없습니다.');
        f.tot_price.value = number_format(String(tot_price));
        f.use_sp_point.value = 0;
        f.use_sp_point.focus();
        return false;
    }

	if(f.use_point.value == '') {
		alert('쇼핑포인트 사용 금액을 입력하세요. 사용을 원치 않을경우 0을 입력하세요.');
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(use_point > mb_point) {
		alert('쇼핑포인트 사용 금액은 현재 보유 쇼핑포인트 보다 클수 없습니다.');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}

	if(use_point > priceInfo.able_use_point()) {
		alert('쇼핑포인트 사용 금액은 최종결제금액 보다 클수 없습니다.');
		f.tot_price.value = number_format(String(tot_price));
		f.use_point.value = 0;
		f.use_point.focus();
		return false;
	}


	var paymethod_check = false;
	for(var i=0; i<f.elements.length; i++){
		if(f.elements[i].name == "paymethod" && f.elements[i].checked==true){
			paymethod_check = true;
		}
	}

    if(!paymethod_check) {
        alert("결제방법을 선택하세요.");
        return false;
    }

    if(typeof(f.od_pwd) != 'undefined') {
        clear_field(f.od_pwd);
        if( (f.od_pwd.value.length<3) || (f.od_pwd.value.search(/([^A-Za-z0-9]+)/)!=-1) )
            error_field(f.od_pwd, "회원이 아니신 경우 주문서 조회시 필요한 비밀번호를 3자리 이상 입력해 주십시오.");
    }

	if(getRadioVal(f.paymethod) == '무통장') {
		check_field(f.bank, "입금계좌를 선택하세요");
		check_field(f.deposit_name, "입금자명을 입력하세요");
	}

	if(getRadioVal(f.paymethod) == '쇼핑포인트') {
		if(f.use_point.value == '0') {
			alert('쇼핑포인트 사용 금액을 입력하세요.');
			f.use_point.focus();
			return false;
		}
	}

	<?php if(!$config['company_type']) { ?>
	if(getRadioVal(f.paymethod) == '무통장' && getRadioVal(f.taxsave_yes) == 'Y') {
		check_field(f.tax_hp, "핸드폰번호를 입력하세요");
	}

	if(getRadioVal(f.paymethod) == '무통장' && getRadioVal(f.taxsave_yes) == 'S') {
		check_field(f.tax_saupja_no, "사업자번호를 입력하세요");
	}

	if(getRadioVal(f.paymethod) == '무통장' && getRadioVal(f.taxbill_yes) == 'Y') {
		check_field(f.company_saupja_no, "사업자번호를 입력하세요");
		check_field(f.company_name, "상호명을 입력하세요");
		check_field(f.company_owner, "대표자명을 입력하세요");
		check_field(f.company_addr, "사업장소재지를 입력하세요");
		check_field(f.company_item, "업태를 입력하세요");
		check_field(f.company_service, "종목을 입력하세요");
	}
	<?php } ?>

    if(errmsg)
    {
        alert(errmsg);
        errfld.focus();
        return false;
    }

	if(getRadioVal(f.paymethod) == '계좌이체') {
		if(tot_price < 150) {
			alert("계좌이체는 150원 이상 결제가 가능합니다.");
			return false;
		}
	}

	if(getRadioVal(f.paymethod) == '신용카드') {
		if(tot_price < 1000) {
			alert("신용카드는 1000원 이상 결제가 가능합니다.");
			return false;
		}
	}

	if(getRadioVal(f.paymethod) == '휴대폰') {
		if(tot_price < 350) {
			alert("휴대폰은 350원 이상 결제가 가능합니다.");
			return false;
		}
	}

	if(document.getElementById('agree')) {
		if(!document.getElementById('agree').checked) {
			alert("개인정보 수집 및 이용 내용을 읽고 이에 동의하셔야 합니다.");
			return false;
		}
	}

	if(!confirm("주문내역이 정확하며, 주문 하시겠습니까?"))
		return false;

	if(doubleSubmitCheck()) return;

	f.use_point.value = no_comma(f.use_point.value);
    f.use_sp_point.value = no_comma(f.use_sp_point.value);
    f.use_ppay.value = no_comma(f.use_ppay.value);
	f.tot_price.value = no_comma(f.tot_price.value);

	f.action = "<?php echo $order_action_url; ?>";

	return true;
}

function calculate_point(val) {
    var f = document.buyform;

    $use_point = $(f.use_point);

    var temp_point = parseInt(no_comma(f.use_point.value));
    var org_price  = parseInt(no_comma(f.org_price.value));
    if( temp_point > org_price ) {
        temp_point = org_price;
        $use_point.val(temp_point);
    }

    calculate_order_price();
}


function calculate_sp_point(val) {
    var f = document.buyform;

    $use_point = $(f.use_sp_point);
    var minval = $use_point.attr('minval');
    var maxval = $use_point.attr('maxval');

    var temp_point = parseInt(no_comma(f.use_sp_point.value));
    var tot_price  = parseInt(no_comma(f.tot_price.value));
    if( temp_point > maxval ) {
        temp_point = maxval;
        $use_point.val(temp_point);
    }
    if( temp_point > tot_price ) {
        temp_point = tot_price;
        $use_point.val(temp_point);
    }

    calculate_order_price();

    priceInfo = get_price_info();
    if( priceInfo.tot_price() == 0 ) {
        $('#paymethod_sp_point').prop('checked', true);
    } else {
        $('#paymethod_sp_point').prop('checked', false);
    }
}

function calculate_paymethod(type) {
    var f = document.buyform;

    var priceInfo  = get_price_info();
    var sell_price = priceInfo.sell_price;//parseInt($("input[name=org_price]").val()); // 합계금액
	var send_cost2 = priceInfo.send_cost2;//parseInt($("input[name=baesong_price2]").val()); // 추가배송비
	var mb_coupon  = priceInfo.mb_coupon;//parseInt($("input[name=coupon_total]").val()); // 쿠폰할인
	var mb_point   = priceInfo.mb_point;//parseInt($("input[name=mb_point]").val()); // 보유 쇼핑포인트
	var tot_price  = priceInfo.tot_price();//sell_price + send_cost2 - mb_coupon;



	// 쇼핑포인트 잔액이 부족한가?
	if( type == '쇼핑포인트' && mb_point < priceInfo.able_use_point()) {
		alert('쇼핑포인트 잔액이 부족합니다.');

		$("#paymethod_bank").attr("checked", true);
		$("#bank_section").show();
		$("#return_bank_section").show();
		$("input[name=use_point]").val(0);
		$("input[name=use_point]").attr("readonly", false);
		calculate_order_price();
		<?php if(!$config['company_type']) { ?>
		$("#tax_section").show();
		<?php } ?>

		return;
	}


    if( type == '쇼핑페이' && priceInfo.mb_sp_point < priceInfo.able_use_sp_point()) {
        alert('쇼핑페이 잔액이 부족합니다.');

        $("#paymethod_bank").attr("checked", true);
        $("#bank_section").show();
		$("#return_bank_section").show();
        $("input[name=use_sp_point]").val(0);
        $("input[name=use_sp_point]").attr("readonly", false);
        calculate_order_price();
        <?php if(!$config['company_type']) { ?>
        $("#tax_section").show();
        <?php } ?>

        return;
    }

	switch(type) {
		case '무통장':
			$("#bank_section").show();
			$("#return_bank_section").show();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").show();
			<?php } ?>
			break;
        case '쇼핑페이':
			$use_point = $(f.use_sp_point);
			var minval = $use_point.attr('minval');
			var maxval = $use_point.attr('maxval');

			if(mb_point>=maxval){
				var use_point_ = maxval;
			}else{
				var use_point_ = mb_point;
			}

            $("#bank_section").hide();
			$("#return_bank_section").hide();
            $("input[name=use_sp_point]").val(number_format(String(use_point_)));
            $("input[name=use_sp_point]").attr("readonly", false);
            calculate_order_price();
        <?php if(!$config['company_type']) { ?>
            $("#tax_section").hide();
            $(".taxbill_fld").hide();
            $("#taxsave_3").attr("checked", true);
            $("#taxbill_2").attr("checked", true);
        <?php } ?>
            break;
		case '쇼핑포인트':
			$use_point = $(f.use_point);
			var minval = $use_point.attr('minval');
			var maxval = $use_point.attr('maxval');

			if(mb_point>=maxval){
				var use_point_ = maxval;
			}else{
				var use_point_ = mb_point;
			}

			$("#bank_section").hide();
			$("#return_bank_section").hide();
			$("input[name=use_point]").val(number_format(String(use_point_)));
			$("input[name=use_point]").attr("readonly", false);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").hide();
			$(".taxbill_fld").hide();
			$("#taxsave_3").attr("checked", true);
			$("#taxbill_2").attr("checked", true);
			<?php } ?>
			break;
		case '가상계좌':
			$("#bank_section").hide();
			$("#return_bank_section").show();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").hide();
			$(".taxbill_fld").hide();
			$("#taxsave_3").attr("checked", true);
			$("#taxbill_2").attr("checked", true);
			<?php } ?>
			break;
		default: // 그외 결제수단
			$("#bank_section").hide();
			$("#return_bank_section").hide();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#tax_section").hide();
			$(".taxbill_fld").hide();
			$("#taxsave_3").attr("checked", true);
			$("#taxbill_2").attr("checked", true);
			<?php } ?>
			break;
	}
}

function tax_bill(val) {
	switch(val) {
		case 1:
			$("#taxsave_fld_1").show();
			$("#taxsave_fld_2").hide();
			$(".taxbill_fld").hide();
			$("#taxbill_2").attr("checked", true);
			break;
		case 2:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").show();
			$(".taxbill_fld").hide();
			$("#taxbill_2").attr("checked", true);
			break;
		case 3:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			break;
		case 4:
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			$(".taxbill_fld").show();
			$("#taxsave_3").attr("checked", true);
			break;
		case 5:
			$(".taxbill_fld").hide();
			break;
	}
}

function coupon_cancel() {
	var f = document.buyform;
	var sell_price = parseInt(no_comma(f.tot_price.value)); // 최종 결제금액
	var mb_coupon  = parseInt(f.coupon_total.value); // 쿠폰할인
	var tot_price  = sell_price + mb_coupon;

	$("#dc_amt").text(0);
	$("#dc_cancel").hide();
	$("#dc_coupon").show();

	$("input[name=tot_price]").val(number_format(String(tot_price)));
	$("input[name=coupon_total]").val(0);
	$("input[name=coupon_price]").val("");
	$("input[name=coupon_lo_id]").val("");
	$("input[name=coupon_cp_id]").val("");
}

// 구매자 정보와 동일합니다.
function gumae2baesong(checked) {
    var f = document.buyform;

    if(checked == true) {
		f.b_name.value			= f.name.value;
		f.b_cellphone.value		= f.cellphone.value;
		f.b_telephone.value		= f.telephone.value;
		f.b_zip.value			= f.zip.value;
		f.b_addr1.value			= f.addr1.value;
		f.b_addr2.value			= f.addr2.value;
		f.b_addr3.value			= f.addr3.value;
		f.b_addr_jibeon.value	= f.addr_jibeon.value;

        calculate_sendcost(String(f.b_zip.value));
    } else {
		f.b_name.value			= '';
		f.b_cellphone.value		= '';
		f.b_telephone.value		= '';
		f.b_zip.value			= '';
		f.b_addr1.value			= '';
		f.b_addr2.value			= '';
		f.b_addr3.value			= '';
		f.b_addr_jibeon.value	= '';

		calculate_sendcost('');
    }
}

gumae2baesong(true);
</script>
<!-- } 주문서작성 끝 -->
