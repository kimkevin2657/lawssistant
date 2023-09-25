<?php
if(!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가

require_once(MS_SHOP_PATH.'/settle_kakaopay.inc.php');
?>

<!-- 주문서작성 시작 { -->
<div id="sod_approval_frm">
<?php
ob_start();
?>
    <p>주문하실 상품을 확인하세요.</p>

    <ul class="sod_list">
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
		global $row, $item_sendcost;
		for($i=0; $row=sql_fetch_array($result); $i++) {
			global $gs, $sr;

            if( $row['up_id'] ) $io_up_id = $row['up_id'];
            if( $row['pt_id'] ) $io_pt_id = $row['pt_id'];

            $gs = get_goods($row['gs_id']);

			// 합계금액 계산
			$sql = " select SUM(IF(io_type = 1, (io_price * ct_qty), ((io_price + ct_price) * ct_qty))) as price,
                            SUM(ct_kv * ct_qty ) kv,
							SUM(IF(io_type = 1, (io_price * ct_qty), ((io_supply_price + ct_supply_price) * ct_qty))) as supply_price,
							SUM(IF(io_type = 1, (0),(ct_point * ct_qty))) as point,
							SUM(IF(io_type = 1, (0),(ct_qty))) as qty,
							SUM(io_price * ct_qty) as opt_price
					   from shop_cart
					  where gs_id = '$row[gs_id]'
						and ct_direct = '{$member['id']}'
						and ct_select = '0'";
			$sum = sql_fetch($sql);

			$it_name = '<strong>'.stripslashes($gs['gname']).'</strong>';
			$it_options = mobile_print_item_options($row['gs_id'], $member['id']);
			if($it_options){
				$it_name .= '<div class="sod_opt">'.$it_options.'</div>';
			}

			$point = $sum['point'];
			$supply_price = $sum['supply_price'];
			$sell_price = $sum['price'];
			$sell_opt_price = $sum['opt_price'];
			$sell_qty = $sum['qty'];
			$sell_amt = $sum['price'] - $sum['opt_price'];

			// 회원이 아니면 쇼핑포인트초기화
			if(!$is_member) $point = 0;

			// 배송비
			if($gs['use_aff'])
				$sr = get_minishop($gs['mb_id']);
			else
				$sr = get_seller_cd($gs['mb_id']);

			$info = get_item_sendcost($sell_price);
			$item_sendcost[] = $info['pattern'];

			$seller_id[$i] = $gs['mb_id'];

			$href = MS_MSHOP_URL.'/view.php?gs_id='.$row['gs_id'];
		?>

        <li class="sod_li">
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

			<div class="li_name">
                <?php echo $it_name; ?>
                <div class="li_mod" style="padding-left:100px;"></div>
                <span class="total_img"><?php echo get_it_image($row['gs_id'], $gs['simg1'], 80, 80); ?></span>
            </div>
            <div class="li_prqty">
                <span class="prqty_price li_prqty_sp"><span>판매가</span>
				<?php echo number_format($sell_amt); ?></span>
                <span class="prqty_qty li_prqty_sp"><span>수량</span>
				<?php echo number_format($sell_qty); ?></span>
                <span class="prqty_sc li_prqty_sp"><span>배송비</span>
				<?php echo number_format($info['price']); ?></span>
            </div>
            <div class="li_total">
                <span class="total_price total_span"><span>소계</span>
				<strong><?php echo number_format($sell_price); ?></strong></span>
                <span class="total_point total_span"><span>쇼핑포인트적립</span>
				<strong><?php echo number_format($point); ?></strong></span>
            </div>
        </li>

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

        } // for 끝

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


        if($i == 0) {
            alert('장바구니가 비어 있습니다.', MS_MSHOP_URL.'/cart.php');
        }
        ?>
    </ul>

    <dl id="sod_bsk_tot">
        <dt class="sod_bsk_sell"><span>주문</span></dt>
        <dd class="sod_bsk_sell"><strong><?php echo number_format($tot_sell_price); ?> 원</strong></dd>
        <dt class="sod_bsk_dvr"><span>배송비</span></dt>
        <dd class="sod_bsk_dvr"><strong><?php echo number_format($tot_send_cost); ?> 원</strong></dd>
        <dt class="sod_bsk_cnt"><span>총계</span></dt>
        <dd class="sod_bsk_cnt"><strong><?php echo number_format($tot_price); ?> 원</strong></dd>
        <dt class="sod_bsk_point"><span>쇼핑포인트</span></dt>
        <dd class="sod_bsk_point"><strong><?php echo number_format($tot_point); ?> P</strong></dd>
    </dl>

<?php
$content = ob_get_contents();
ob_end_clean();
?>
</div>

<div id="sod_frm">
	<form name="buyform" id="buyform" method="post" action="<?php echo $order_action_url; ?>" onsubmit="return fbuyform_submit(this);" autocomplete="off">
	<input type="hidden" name="token" value="<?php echo $token; ?>">
	<input type="hidden" name="ss_cart_id" value="<?php echo $ss_cart_id; ?>">
	<input type="hidden" name="mb_point" value="<?php echo $member['point']; ?>">
    <input type="hidden" name="mb_sp_point" value="<?php echo $member['sp_point']; ?>">
    <input type="hidden" name="mb_ppay" value="<?php echo $member['pay']; ?>">

	<input type="hidden" name="shop_id" value="<?php echo $pt_id; ?>">
	<input type="hidden" name="coupon_total" value="0">
	<input type="hidden" name="coupon_price" value="">
	<input type="hidden" name="coupon_lo_id" value="">
	<input type="hidden" name="coupon_cp_id" value="">
	<input type="hidden" name="baesong_price" value="<?php echo $baesong_price; ?>">
	<input type="hidden" name="baesong_price2" value="0">
	<input type="hidden" name="org_price" value="<?php echo $tot_price; ?>">


	<?php echo $content; ?>
    <?php if ( get_session('ss_expire') != 'after-order-complete' && $grade && $grade['gb_anew_price'] <= $tot_anew_price ) : ?>
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
        <section id="sod_frm_orderer">
            <h2 class="anc_tit">승급 / 후원&bull;추천 ID</h2>
            <div class="odf_tbl">
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
	<section id="sod_frm_orderer">
		<h2 class="anc_tit">주문하시는 분</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<?php if(!$is_member) { // 비회원이면 ?>
			<tr>
				<th scope="row">비밀번호</th>
				<td>
					<input type="password" name="od_pwd" required class="frm_input required" maxlength="20">
					<span class="frm_info">영,숫자 3~20자 (주문서 조회시 필요)</span>
				</td>
			</tr>
			<?php } ?>
            <tr>
				<th scope="row">이름</th>
                <td><input type="text" name="name" value="<?php echo $member['name']; ?>" required class="frm_input required" maxlength="20"></td>
            </tr>
			<tr>
				<th scope="row">핸드폰</th>
				<td><input type="text" name="cellphone" value="<?php echo $member['cellphone']; ?>" required class="frm_input required" maxlength="20"></td>
			</tr>
			<tr>
				<th scope="row">전화번호</th>
				<td><input type="text" name="telephone" value="<?php echo $member['telephone']; ?>" class="frm_input" maxlength="20"></td>
			</tr>
			<tr>
				<th scope="row">주소</th>
				<td>
                    <input type="text" name="zip" value="<?php echo $member['zip']; ?>" required class="frm_input required" size="5" maxlength="5">
                    <button type="button" onclick="win_zip('buyform', 'zip', 'addr1', 'addr2', 'addr3', 'addr_jibeon');" class="btn_small grey">주소검색</button><br>
                    <input type="text" name="addr1" value="<?php echo $member['addr1']; ?>" required class="frm_input frm_address required"><br>
                    <input type="text" name="addr2" value="<?php echo $member['addr2']; ?>" class="frm_input frm_address"><br>
                    <input type="text" name="addr3" value="<?php echo $member['addr3']; ?>" class="frm_input frm_address" readonly><br>
                    <input type="hidden" name="addr_jibeon" value="<?php echo $member['addr_jibeon']; ?>">
				</td>
			</tr>
			<tr>
				<th scope="row">E-mail</th>
				<td><input type="text" name="email" value="<?php echo $member['email']; ?>" _required class="frm_input _required wfull"></td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<section id="sod_frm_taker">
		<h2 class="anc_tit">받으시는 분</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<th scope="row">배송지선택</th>
				<td>
					<input type="radio" name="ad_sel_addr" value="1" id="sel_addr1" class="css-checkbox lrg">
					<label for="sel_addr1" class="css-label padr5">주문자와 동일</label><br>
					<input type="radio" name="ad_sel_addr" value="2" id="sel_addr2" class="css-checkbox lrg">
					<label for="sel_addr2" class="css-label">신규배송지</label>
					<?php if($is_member) { ?>
					<br><input type="radio" name="ad_sel_addr" value="3" id="sel_addr3" class="css-checkbox lrg">
					<label for="sel_addr3" class="css-label">배송지목록</label>
					<?php } ?>
				</td>
			</tr>
			<tr>
				<th scope="row">이름</th>
				<td><input type="text" name="b_name" required class="frm_input required"></td>
			</tr>
			<tr>
				<th scope="row">핸드폰</th>
				<td><input type="text" name="b_cellphone" required class="frm_input required"></td>
			</tr>
			<tr>
				<th scope="row">전화번호</th>
				<td><input type="text" name="b_telephone" class="frm_input"></td>
			</tr>
			<tr>
				<th scope="row">주소</th>
				<td>
                    <input type="text" name="b_zip" required class="frm_input required" size="5" maxlength="5">
                    <button type="button" onclick="win_zip('buyform', 'b_zip', 'b_addr1', 'b_addr2', 'b_addr3', 'b_addr_jibeon');" class="btn_small grey">주소검색</button><br>
                    <input type="text" name="b_addr1" required class="frm_input frm_address required"><br>
                    <input type="text" name="b_addr2" class="frm_input frm_address"><br>
                    <input type="text" name="b_addr3" class="frm_input frm_address" readonly><br>
					<input type="hidden" name="b_addr_jibeon" value="">
				</td>
			</tr>
			<tr>
				<th scope="row">전하실말씀</th>
				<td>
					<select name="sel_memo" class="wfull">
						<option value="">요청사항 선택하기</option>
						<option value="부재시 경비실에 맡겨주세요.">부재시 경비실에 맡겨주세요</option>
						<option value="빠른 배송 부탁드립니다.">빠른 배송 부탁드립니다.</option>
						<option value="부재시 핸드폰으로 연락바랍니다.">부재시 핸드폰으로 연락바랍니다.</option>
						<option value="배송 전 연락바랍니다.">배송 전 연락바랍니다.</option>
					</select>
					<div class="padt5">
						<textarea name="memo" id="memo" class="frm_textbox"></textarea>
					</div>
				</td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<?php
	$escrow_title = "";
	if($default['de_escrow_use']) {
		$escrow_title = "에스크로 ";
	}

	$multi_settle = '';
	if($is_kakaopay_use)
		$multi_settle .= "<option value='KAKAOPAY'>카카오페이</option>\n";
	if($default['de_bank_use'])
		$multi_settle .= "<option value='무통장'>무통장입금</option>\n";
	if($default['de_card_use'])
		$multi_settle .= "<option value='신용카드'>신용카드</option>\n";
	if($default['de_hp_use'])
		$multi_settle .= "<option value='휴대폰'>휴대폰</option>\n";
	if($default['de_iche_use'])
		$multi_settle .= "<option value='실시간계좌이체'>".$escrow_title."실시간계좌이체</option>\n";
	if($default['de_vbank_use'])
		$multi_settle .= "<option value='가상계좌'>".$escrow_title."가상계좌</option>\n";
	if($is_member && $gs['point_pay_allow'] && $usablePoint > $config['usepoint'] && ($tot_price <= $member['point']))
		$multi_settle .= "<option value='포인트'>쇼핑포인트결제</option>\n";
    if($is_member && ($tot_price <= $member['sp_point']) && defined('USE_SHOPPING_PAY_BUY') && USE_SHOPPING_PAY_BUY && $grade && !($grade['gb_anew_price'] <= $tot_anew_price))
        $multi_settle .= "<option value='쇼핑페이' id='paymethod_sp_point'>쇼핑페이</option>\n";
    if($is_member && ($tot_price <= $member['pay']))
        $multi_settle .= "<option value='마일리지' id='paymethod_ppay'>마일리지</option>\n";
	// PG 간편결제
	if($default['de_easy_pay_use']) {
		switch($default['de_pg_service']) {
			case 'lg':
				$pg_easy_pay_name = 'PAYNOW';
				break;
			case 'inicis':
				$pg_easy_pay_name = 'KPAY';
				break;
			case 'kcp':
				$pg_easy_pay_name = 'PAYCO';
				break;
            case 'easypay':
                $pg_easy_pay_name = 'EASYPAY';
				break;
		}
		$multi_settle .= "<option value='간편결제'>{$pg_easy_pay_name}</option>\n";
	}

	// 이니시스를 사용중일때만 삼성페이 결제가능
	if($default['de_samsung_pay_use'] && ($default['de_pg_service'] == 'inicis')) {
		$multi_settle .= "<option value='삼성페이'>삼성페이</option>\n";
	}
	?>

	<section id="sod_frm_pay">
		<h2 class="anc_tit">결제정보 입력</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<th scope="row">결제방법</th>
				<td>
					<select name="paymethod" onchange="calculate_paymethod(this.value);" class="wfull">
						<option value="">선택하기</option>
						<?php echo $multi_settle; ?>
					</select>
				</td>
			</tr>
			<tr>
				<th scope="row">합계</th>
				<td><strong><?php echo display_price($tot_price); ?></strong></td>
			</tr>
			<tr>
				<th scope="row">추가배송비</th>
				<td>
					<strong><span id="send_cost2">0</span>원</strong>
					<span class="fc_999">(지역에 따라 추가되는 배송비)</span>
				</td>
			</tr>
			<?php
			if($is_member && $config['coupon_yes']) { // 보유쿠폰
				$sp_count = get_cp_precompose($member['id']);
			?>
			<tr>
				<th scope="row">할인쿠폰</th>
				<td>
					<span id="dc_coupon"><a href="javascript:window.open('<?php echo MS_MSHOP_URL; ?>/ordercoupon.php');" class="btn_small bx-red">사용 가능 쿠폰 <?php echo $sp_count[3]; ?>장</a>&nbsp;</span>(-)&nbsp;&nbsp;<strong><span id="dc_amt">0</strong>원&nbsp;<span id="dc_cancel" style="display:none;"><a href="javascript:coupon_cancel();" class="btn_small grey">삭제</a></span></span>
				</td>
			</tr>
			<?php } ?>
			<?php
			if($is_member && $gs['point_pay_allow'] /*&& $usablePoint > $config['usepoint']*/) { ?>
			<tr>
				<th scope="row">쇼핑포인트결제</th>
				<td>
					<input type="text" name="use_point" value="0" onkeyup="calculate_point(this.value); this.value=number_format(this.value);"
                           minval="<?php echo $config['usepoint'] ?>"
                           maxval="<?php echo $usablePoint ?>"
                           class="frm_input w100"> 원
					<div>잔액 : <b><?php echo display_point($member['point']); ?></b> (<?php echo display_point($config['usepoint']); ?> 부터 <strong><?php echo display_point($usablePoint); ?></strong>까지 사용가능)</div>
                    <button type="button" class="btn_small gray holder--btn-use-all">쇼핑포인트 전액사용</button>
				    <a href="/m/bbs/board.php?bo_table=chargepoint" class="btn_small gray">쇼핑포인트 충전</a>
                    <script>
                        (function($){
                            $(function(){
                                $('.holder--btn-use-all').on('click', function(){
                                    var priceInfo = get_price_info();
                                    var $usePoint = $('[name=use_point]');
                                    var maxPoint  = priceInfo.mb_point;
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
            <input type="hidden" minval="<?php echo $config['usepoint'] ?>"
                   maxval="<?php echo $usablePoint ?>" name="use_point" value="0" class="frm_input">
            <?php } ?>
            <?php
            // dd( compact('is_member', 'usablePoint', 'config'));
            if($is_member && defined('USE_SHOPPING_PAY_BUY') && USE_SHOPPING_PAY_BUY && $grade && !($grade['gb_anew_price'] <= $tot_anew_price)) { ?>
            <tr>
                <th scope="row">쇼핑페이결제</th>
                <td>
                    <input type="text" name="use_sp_point" value="0" class="frm_input"
                           minval="<?php echo '0' ?>"
                           maxval="<?php echo $member['sp_point'] ?>"
                           size="12" onkeyup="calculate_sp_point(this.value);this.value=number_format(this.value);" style="font-weight:bold;"> 원 보유 쇼핑페이 : <?php echo display_point($member['sp_point']); ?>
                    <button type="button" class="btn_small gray holder--btn-use-sp-all">쇼핑페이 전액사용</button>
                    <script>
                        (function($){
                            $(function(){
                                $('.holder--btn-use-sp-all').on('click', function(){
                                    var priceInfo = get_price_info();
                                    var $usePoint = $('[name=use_sp_point]');
                                    var maxVal = priceInfo.mb_sp_point;
                                    var ablePrice = priceInfo.able_use_sp_point();
                                    if( maxVal > ablePrice ) maxVal = ablePrice;
                                    $usePoint.val( number_format(maxVal) );
                                    calculate_sp_point(maxVal);
                                });
                            });
                        }(jQuery));
                    </script>
                </td>
            </tr>
            <?php } else { ?>
            <input type="hidden" name="use_sp_point"  minval="<?php echo '0' ?>"
                   maxval="<?php echo $member['sp_point'] ?>" value="0" class="frm_input">
            <?php } ?>
            <?php if(is_minishop($member['id']) && $KGB == "KOREA") { ?>
				<tr>
                    <th scope="row">마일리지</th>
                    <td>
                        <?php echo CURRENCY_UNIT; ?>
                        <input type="text" name="use_ppay" value="0" onkeyup="calculate_ppay(this.value); "
                               onfocus="this.value=no_comma(this.value);" onblur="this.value=number_format(this.value);"
                               maxval="<?php echo $member['pay']; ?>"
                               class="frm_input w100"> (<?php _e('잔액'); ?> : <b><?php echo display_price($member['pay']); ?>)</b>

                        <button type="button" class="btn_small gray holder--btn-use-ppay-all">마일리지 전액사용</button>
                        <script>
                            (function($){
                                $(function(){
                                    $('.holder--btn-use-ppay-all').on('click', function(){
                                        var priceInfo = get_price_info();
                                        var $usePoint = $('[name=use_ppay]');
                                        var maxPoint  = priceInfo.mb_point;
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
					<input type="text" name="tot_price" value="<?php echo number_format($tot_price); ?>" class="frm_input w100" readonly style="background:#f1f1f1;color:red;font-weight:bold;"> 원
				</td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<section id="bank_section" style="display:none;">
		<h2 class="anc_tit">입금하실 계좌</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<th scope="row">무통장계좌</th>
				<td>
					<?php echo mobile_bank_account("bank"); ?>
					</td>
			</tr>
			<tr>
				<th scope="row">입금자명</th>
				<td><input type="text" name="deposit_name" value="<?php //echo $member['name']; ?>" class="frm_input w100"></td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

<section id="return_bank_section" style="display:none;">
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
</section>

	<section id="taxsave_section" style="display:none;">
		<h2 class="anc_tit">증빙서류 발급</h2>
		<div class="odf_tbl">
			<table>
			<tbody>
			<tr>
				<th scope="row">현금영수증</th>
				<td>
					<select name="taxsave_yes" onchange="tax_save(this.value);" class="wfull">
						<option value="N">발행안함</option>
						<option value="Y">개인 소득공제용</option>
						<option value="S">사업자 지출증빙용</option>
					</select>
					<div id="taxsave_fld_1" style="display:none;">
						<input type="text" name="tax_hp" class="frm_input frm_address" placeholder="핸드폰번호">
					</div>
					<div id="taxsave_fld_2" style="display:none;">
						<input type="text" name="tax_saupja_no" class="frm_input frm_address" placeholder="사업자등록번호">
					</div>
				</td>
			</tr>
			<tr>
				<th scope="row">세금계산서</th>
				<td>
					<select name="taxbill_yes" onchange="tax_bill(this.value);" class="wfull">
						<option value="N">발행안함</option>
						<option value="Y">발행요청</option>
					</select>
					<div id="taxbill_section" style="display:none;">
						<input type="text" name="company_saupja_no" class="frm_input frm_address" placeholder="사업자등록번호"><br>
						<input type="text" name="company_name" class="frm_input frm_address" placeholder="상호(법인명)"><br>
						<input type="text" name="company_owner" class="frm_input frm_address" placeholder="대표자명"><br>
						<input type="text" name="company_addr" class="frm_input frm_address" placeholder="사업장주소"><br>
						<input type="text" name="company_item" class="frm_input frm_address" placeholder="업태"><br>
						<input type="text" name="company_service" class="frm_input frm_address" placeholder="종목">
					</div>
				</td>
			</tr>
			</tbody>
			</table>
		</div>
	</section>

	<?php if(!$is_member) { ?>
    <section id="guest_privacy">
		<h2 class="anc_tit">비회원 구매</h2>
		<div class="tbl_head01 tbl_wrap">
			<table>
			<thead>
			<tr>
				<th>목적</th>
				<th>항목</th>
				<th>보유기간</th>
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

		<div id="guest_agree">
			<input type="checkbox" id="agree" value="1" class="css-checkbox lrg">
			<label for="agree" class="css-label">개인정보 수집 및 이용 내용을 읽었으며 이에 동의합니다.</label>
		</div>
	</section>
	<?php } ?>

	<div class="btn_confirm">
		<input type="submit" value="주문하기" class="btn_medium wset">
		<a href="<?php echo MS_MSHOP_URL; ?>/cart.php" class="btn_medium bx-white">주문취소</a>
	</div>
</div>
</form>
<script src="//cdnjs.cloudflare.com/ajax/libs/numeral.js/2.0.6/numeral.min.js"></script>
<script>
$(function() {
    var zipcode = "";

    $("input[name=b_addr2]").focus(function() {
        var zip = $("input[name=b_zip]").val().replace(/[^0-9]/g, "");
        if(zip == "")
            return false;

        var code = String(zip);

        if(zipcode == code)
            return false;

        zipcode = code;
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
			win_open('./orderaddress.php','win_address');
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

            var sell_price = parseInt(no_comma($("input[name=org_price]").val())); // 합계금액
            var send_cost2 = parseInt(no_comma($("input[name=baesong_price2]").val())); // 추가배송비
            var mb_coupon  = parseInt(no_comma($("input[name=coupon_total]").val())); // 쿠폰할인
            var mb_point   = parseInt(no_comma($("input[name=mb_point]").val()));
            var mb_sp_point= parseInt(no_comma($("input[name=mb_sp_point]").val()));
            var mb_ppay= parseInt(no_comma($("input[name=mb_ppay]").val()));
            var use_point  = parseInt(no_comma($("input[name=use_point]").val()));
            var use_sp_point=parseInt(no_comma($("input[name=use_sp_point]").val()));
            var use_ppay=parseInt(no_comma($("input[name=use_ppay]").val()));

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

    $use_point = $('[name=use_ppay]');

    if( pInfo.use_ppay > pInfo.mb_ppay ) {
        $use_point.val(pInfo.mb_ppay);
        pInfo.init();
    }
    if( pInfo.use_ppay  > pInfo.able_use_ppay() ) {
        $use_point.val(pInfo.able_use_ppay());
        pInfo.init();
    }
    calculate_order_price();
    pInfo.init();
    if( pInfo.tot_price() == 0 ) {
        $('#paymethod_ppay').prop('selected', true);
    } else {
        $('#paymethod_ppay').prop('selected', false);
    }
}

function calculate_order_price() {
    var priceInfo = get_price_info();
    $("input[name=tot_price]").val(number_format(String(priceInfo.tot_price())));
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
        alert('쇼핑포인트사용 금액을 입력하세요. 사용을 원치 않을경우 0을 입력하세요.');
        f.use_point.value = 0;
        f.use_point.focus();
        return false;
    }

    if(use_point > mb_point) {
        alert('쇼핑포인트사용 금액은 현재 보유쇼핑포인트 보다 클수 없습니다.');
        f.tot_price.value = number_format(String(tot_price));
        f.use_point.value = 0;
        f.use_point.focus();
        return false;
    }

    if(use_point > priceInfo.able_use_point()) {
        alert('쇼핑포인트사용 금액은 최종결제금액 보다 클수 없습니다.');
        f.tot_price.value = number_format(String(tot_price));
        f.use_point.value = 0;
        f.use_point.focus();
        return false;
    }


	if(getSelectVal(f["paymethod"]) == ''){
		alert("결제방법을 선택하세요.");
		f.paymethod.focus();
		return false;
	}

    if(typeof(f.od_pwd) != 'undefined') {
        clear_field(f.od_pwd);
        if( (f.od_pwd.value.length<3) || (f.od_pwd.value.search(/([^A-Za-z0-9]+)/)!=-1) )
            error_field(f.od_pwd, "회원이 아니신 경우 주문서 조회시 필요한 비밀번호를 3자리 이상 입력해 주십시오.");
    }

	if(getSelectVal(f["paymethod"]) == '무통장'){
		check_field(f.bank, "입금계좌를 선택하세요");
		check_field(f.deposit_name, "입금자명을 입력하세요");
	}

	if(getSelectVal(f["paymethod"]) == '쇼핑포인트') {
		if(f.use_point.value == '0') {
			alert('쇼핑포인트사용 금액을 입력하세요.');
			f.use_point.focus();
			return false;
		}
	}

	<?php if(!$config['company_type']) { ?>
	if(getSelectVal(f["paymethod"]) == '무통장' && getSelectVal(f["taxsave_yes"]) == 'Y') {
		check_field(f.tax_hp, "핸드폰번호를 입력하세요");
	}

	if(getSelectVal(f["paymethod"]) == '무통장' && getSelectVal(f["taxsave_yes"]) == 'S') {
		check_field(f.tax_saupja_no, "사업자번호를 입력하세요");
	}

	if(getSelectVal(f["paymethod"]) == '무통장' && getSelectVal(f["taxbill_yes"]) == 'Y') {
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

	if(getSelectVal(f["paymethod"]) == '계좌이체') {
		if(tot_price < 150) {
			alert("계좌이체는 150원 이상 결제가 가능합니다.");
			return false;
		}
	}

	if(getSelectVal(f["paymethod"]) == '신용카드') {
		if(tot_price < 1000) {
			alert("신용카드는 1000원 이상 결제가 가능합니다.");
			return false;
		}
	}

	if(getSelectVal(f["paymethod"]) == '휴대폰') {
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

	f.use_point.value = no_comma(f.use_point.value);
    f.use_sp_point.value = no_comma(f.use_sp_point.value);
    f.use_ppay.value = no_comma(f.use_ppay.value);
	f.tot_price.value = no_comma(f.tot_price.value);

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
        $('#paymethod_sp_point').prop('selected', true);
    } else {
        $('#paymethod_sp_point').prop('selected', false);
    }
}

// 결제방법
function calculate_paymethod(type) {

    var priceInfo  = get_price_info();

    var sell_price = priceInfo.sell_price;//parseInt($("input[name=org_price]").val()); // 합계금액
    var send_cost2 = priceInfo.send_cost2;//parseInt($("input[name=baesong_price2]").val()); // 추가배송비
    var mb_coupon  = priceInfo.mb_coupon;//parseInt($("input[name=coupon_total]").val()); // 쿠폰할인
    var mb_point   = priceInfo.mb_point;//parseInt($("input[name=mb_point]").val()); // 보유쇼핑포인트
    var tot_price  = priceInfo.tot_price();//sell_price + send_cost2 - mb_coupon;

	// 쇼핑포인트잔액이 부족한가?
	if( type == '쇼핑포인트' && mb_point < tot_price ) {
		alert('쇼핑포인트 잔액이 부족합니다.');

		$("select[name=paymethod]").val('무통장');
		$("#bank_section").show();
		$("#return_bank_section").show();
		$("input[name=use_point]").val(0);
		$("input[name=use_point]").attr("readonly", false);
		calculate_order_price();
		<?php if(!$config['company_type']) { ?>
		$("#taxsave_section").show();
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
			$("#taxsave_section").show();
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
			case '포인트':
<?php //	$use_point = $(f.use_point);
		//	var minval = $use_point.attr('minval');
		//	var maxval = $use_point.attr('maxval');

		//	if(mb_point>=maxval){
		//		var use_point_ = maxval;
		//	}else{
		//		var use_point_ = mb_point;
		//	}
		?>
			var use_point_ = priceInfo.able_use_sp_point();

			$("#bank_section").hide();
			$("#return_bank_section").hide();
			$("input[name=use_point]").val(number_format(String(use_point_)));
			$("input[name=use_point]").attr("readonly", false);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#taxsave_section").hide();
			$("#taxbill_section").hide();
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			<?php } ?>
			break;
		case '가상계좌':
			$("#bank_section").hide();
			$("#return_bank_section").show();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#taxsave_section").hide();
			$("#taxbill_section").hide();
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			<?php } ?>
			break;
		default: // 그외 결제수단
			$("#bank_section").hide();
			$("input[name=use_point]").val(0);
			$("input[name=use_point]").attr("readonly", false);
			calculate_order_price();
			<?php if(!$config['company_type']) { ?>
			$("#taxsave_section").hide();
			$("#taxbill_section").hide();
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			<?php } ?>
			break;
	}
}

// 현금영수증
function tax_save(val) {
	switch(val) {
		case 'Y': // 개인 소득공제용
			$("#taxsave_fld_1").show();
			$("#taxsave_fld_2").hide();
			$("#taxbill_section").hide();
			$("select[name=taxbill_yes]").val('N');
			break;
		case 'S': // 지출증빙용
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").show();
			$("#taxbill_section").hide();
			$("select[name=taxbill_yes]").val('N');
			break;
		default: // 발행안함
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			break;
	}
}

// 세금계산서
function tax_bill(val) {
	switch(val) {
		case 'Y':  // 발행함
			$("#taxsave_fld_1").hide();
			$("#taxsave_fld_2").hide();
			$("select[name=taxsave_yes]").val('N');
			$("#taxbill_section").show();
			break;
		case 'N': //미발행
			$("#taxbill_section").hide();
			break;
	}
}

// 할인쿠폰 삭제
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
