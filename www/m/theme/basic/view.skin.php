<?php
if(!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가
?>

<script src="<?php echo MS_MJS_URL; ?>/shop.js?ver=11"></script>
<link href="/lightbox/css/lightbox.css" rel="stylesheet" />
<script src="/lightbox/js/lightbox.js"></script>
<form name="fbuyform" id="fbuyform" method="post">
<input type="hidden" name="gs_id[]" value="<?php echo $gs_id; ?>">
<input type="hidden" id="it_price" value="<?php echo get_sale_price($gs_id); ?>">
<?php
if( $gs['point_pay_allow'] && Good::usablePoint($gs)) : ?>
<input type="hidden" id="it_price2" value="<?php echo Good::usablePoint($gs); ?>">
<?php endif; ?>
<input type="hidden" name="ca_id" value="<?php echo $ca['gcate']; ?>">
<input type="hidden" name="sw_direct">

<div class="sp_wrap">
	<div class="sp_sub_wrap">
		<div class="v_cont">
			<ul class="v_horiz">
				<li><?php echo get_it_image($gs_id, $gs['simg1'], $default['de_item_medium_wpx'], $default['de_item_medium_hpx'], 'name="slideshow"'); ?></li>
			</ul>
		</div>
		<a class="sp_b_a fa fa-angle-left" href="javascript:chgimg(-1)"></a>
		<a class="sp_b_a fa fa-angle-right" href="javascript:chgimg(1)"></a>
	</div>
	<div class="subject">
		<?php echo get_text($gs['gname']); ?>
		<?php if($gs['explan']) { ?>
		<p class="sub_txt"><?php echo get_text($gs['explan']); ?></p>
		<?php } ?>
	</div>

	<div class="sp_sns">
		<?php echo $sns_share_links; ?>
	</div>
	<div class="sp_sns">
		만족도 : <?php echo $aver_score; ?>% <span class="hline"></span>상품평 : <?php echo number_format($item_use_count); ?>건
	</div>

	<?php if($is_social_end) { ?>
	<div class="sp_tol">
		<div class="sp_fpg">
			<span class="sp_s_n"> <?php echo $is_social_txt; ?> </span>
		</div>
	</div>
	<?php } ?>

	<?php if($is_social_ing) { ?>
	<div class="sp_tol">
		<div class="social">
			<?php include_once(M_TIMESALE); ?>
		</div>
	</div>
	<?php } ?>

	<?php if(!$is_only) { ?>
	<div class="sp_tbox">
		<?php if(!$is_pr_msg && !$is_buy_only && !$is_soldout && $gs['normal_price']) { ?>
		<ul>
			<li class='tlst'>시중가격</li>
			<li class='trst fc_137 tl'><?php echo display_price2($gs['normal_price']); ?></li>
		</ul>
		<?php } ?>
		<ul class="mart3">
			<li class='tlst padt8'>판매가격</li>
			<li class='trst'>
				<div class='trst-amt'><?php echo mobile_price($gs_id); ?></div>
			</li>
		</ul>
        <?php
        if( $gs['point_pay_allow'] && Good::usablePoint($gs)) : ?>
            <ul class="mart3">
                <li class='tlst fc_red'><?php echo('쇼핑포인트 할인(%)'); ?></li>
                <li class="trst fc_red"><?php echo Good::displayUsablePoint($gs); ?></li>
            </ul>
        <?php endif; ?>
        <?php
        if( false ) :
        $usablePoint = $gs['point_pay_per'] ? $gs['goods_price'] / 100 * $gs['point_pay_per'] : $gs['point_pay_max'] ;
        if( $gs['point_pay_allow'] && $usablePoint > 0) : ?>
            <ul class="mart3">
                <li class='tlst fc_red'>쇼핑포인트 전액사용</li>
                <li class="trst fc_red"><?php echo display_price2($usablePoint, '('.ceil( $usablePoint / $gs['goods_price'] * 100 ).'%)'); ?></li>
            </ul>
        <?php endif;
        endif;?>
		<?php if(false && is_minishop($member['id']) && $config['pf_payment_yes']) { ?>
            <ul class="mart3">
                <li class='tlst'>판매수익</li>
                <li class="trst"><?php echo display_price2(get_payment($gs_id)); ?></li>
            </ul>
		<?php } ?>
	</div>
	<?php } ?>
    <?php if( $config['usekv_yes'] ) : ?>
    <div class="sp_tbox">
        <ul>
            <li class="tlst" style="color:#5353ff"><?php echo('마일리지 적립'); ?></li>
            <li class="trst strong" style="color:#5353ff"><?php echo $goods_kv; ?></li>
        </ul>
    </div>
    <?php endif; ?>
    <?php //if(!$is_only && !$is_pr_msg && !$is_buy_only && !$is_soldout && $gpoint) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst' style="color:#ff6868">쇼핑포인트 적립</li>
			<li class='trst strong' style="color:#ff6868"><?php echo $gpoint; ?></li>
		</ul>
	</div>
	<?php //} ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>상품코드</li>
			<li class='trst strong' ><?php echo $gs['gcode']; ?></li>
		</ul>
	</div>

	<?php if(!$is_only && !$is_pr_msg && !$is_buy_only && !$is_soldout && $cp_used) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>쿠폰발급</li>
			<li class='trst-cp'><?php echo $cp_btn; ?></li>
		</ul>
	</div>
	<?php } ?>
	<?php if($gs['brand_nm']) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>브랜드</li>
			<li class='trst'><?php echo $gs['brand_nm']; ?></li>
		</ul>
	</div>
	<?php } ?>
	<?php if($gs['model']) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>모델명</li>
			<li class='trst'><?php echo $gs['model']; ?></li>
		</ul>
	</div>
	<?php } ?>
	<?php if($gs['odr_min']) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>최소구매수량</li>
			<li class='trst'><?php echo display_qty($gs['odr_min']); ?></li>
		</ul>
	</div>
	<?php } ?>
	<?php if($gs['odr_max']) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>최대구매수량</li>
			<li class='trst'><?php echo display_qty($gs['odr_max']); ?></li>
		</ul>
	</div>
	<?php } ?>
	<?php
	$sc_class = "sp_tbox";
	if(in_array($gs['sc_type'], array('2','3')) && $gs['sc_method'] == '2') {
		$sc_class = "sp_obox";
	}
	?>
	<div class="<?php echo $sc_class; ?>">
		<ul>
			<li class='tlst'>배송비</li>
			<li class='trst'><?php echo mobile_sendcost_amt(); ?></li>
		</ul>
	</div>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>배송가능지역</li>
			<li class='trst padt2'><?php echo $gs['zone']; ?> <?php echo $gs['zone_msg']; ?></li>
		</ul>
	</div>
    <div class="sp_tbox">
		<ul>
			<li class='tlst'>최저가</li>
			<li class='trst padt2'><?php
				$tt = sql_fetch(" select * from naver_list where gcode='{$gs['gcode']}' order by price asc limit 1");  
					if(!$tt["price"]){ echo "오마켓 전용가"; }else{
					echo number_format($tt["price"]-$tt["delivery"])."원"; } ?></li>
		</ul>
	</div>  
    <div class="sp_tbox">
		<ul>
			<li class='tlst'>상품 업데이트 일자</li>
			<li class='trst padt2'><?php echo $gs['update_time']; ?></li>
		</ul>
	</div>  
    <div class="sp_tbox">
		<ul>
			<li class='tlst'>상품 판매정책</li>
			<li class='trst padt2'>
				<?php 
				$arr = explode(",", $gs['icon_img']);
				for($a=1; $a<11; $a++) {
					$z = $a -1;
					if($arr[$z] != ""){
						if($a == '1'){
							echo "<span class= \"interest\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '2'){
							echo "<span class= \"mdchoice\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '3'){
							echo "<span class= \"new\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '4'){
							echo "<span class= \"new\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '5'){
							echo "<span class= \"new\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '6'){
							echo "<span class= \"new\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '7'){
							echo "<span class= \"new\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '8'){
							echo "<span class= \"new\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '9'){
							echo "<span class= \"new\">".$gs['icon_text'.$a]."</span>";
						}
						if($a == '10'){
							echo "<span class= \"new\">".$gs['icon_text'.$a]."</span>";
						}
					}
				}
				?>
			</li>
		</ul>
	</div>  
	<?php if(!$is_only && !$is_pr_msg && !$is_buy_only && !$is_soldout) { ?>
	<?php if($option_item) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst strong'>주문옵션</li>
			<li class='trst fs11 padt2'>아래옵션은 필수선택 옵션입니다</li>
		</ul>
	</div>
	<?php echo $option_item; ?>
	<?php } ?>

	<?php if($supply_item) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst strong'>추가구성</li>
			<li class='trst fs11 padt2'>추가구매를 원하시면 선택하세요</li>
		</ul>
	</div>
	<?php echo $supply_item; ?>
	<?php } ?>

	<!-- 선택된 옵션 시작 { -->
	<div id="option_set_list">
		<?php if(!$option_item) { ?>
		<ul id="option_set_added">
			<li class="sit_opt_list">
				<div class="sp_tbox">
				<input type="hidden" name="io_type[<?php echo $gs_id; ?>][]" value="0">
				<input type="hidden" name="io_id[<?php echo $gs_id; ?>][]" value="">
				<input type="hidden" name="io_value[<?php echo $gs_id; ?>][]" value="<?php echo $gs['gname']; ?>">
				<input type="hidden" class="io_price" value="0">
				<input type="hidden" class="io_stock" value="<?php echo $gs['stock_qty']; ?>">
					<ul>
						<li class='tlst padt5'>
							<span class="sit_opt_subj">수량</span>
							<span class="sit_opt_prc"></span>
						</li>
						<li class='trst'>
							<dl>
								<dt class='fl padr3'><button type="button" class="btn_small grey">-</button></dt>
								<dt class='fl padr3'><input type="text" name="ct_qty[<?php echo $gs_id; ?>][]"
								value="<?php echo $odr_min; ?>" title="수량설정"></dt>
								<dt class='fl padr3'><button type="button" class="btn_small grey">+</button><dt>
								<dt class='fl padt4 tx_small'> (남은수량 : <?php echo $gs['stock_mod'] ? $gs['stock_qty'].'개' : '무제한'; ?>)</dt>
							</dl>
						</li>
					</ul>
				</div>
			</li>
		</ul>
		<script>
		$(function() {
			price_calculate();
		});
		</script>
		<?php } ?>
	</div>
	<!-- } 선택된 옵션 끝 -->



    <?php // 추춴ID, 추천ID 입력 ; ?>
    <?php if( false && $gs['buy_minishop_grade'] && is_minishop($member['id']) ) : ?>
        <?php
        $myGrade  = minishop::findTopId($member['id']);

        if( isset($myGrade[$gs['buy_minishop_grade']]) ) {
            $my_top_id = $myGrade[$gs['buy_minishop_grade']];
        } else {
            $my_top_id = $encrypted_admin;
        }
        $io_pt_id = $my_top_id;
        $io_up_id = $my_top_id;
        ?>
    <input type="hidden" id="buy_minishop_grade" name="buy_minishop_grade" value="<?php echo $gs['buy_minishop_grade']; ?>">
    <input type="hidden" id="current_grade" name="current_grade" value="<?php echo $member['grade']; ?>">


        <div class="sp_tbox <?php echo false && $gs['buy_minishop_grade'] == $member['grade'] && is_minishop($member['id']) ? ' dpn ' : ''; ?>">
            <ul>
                <li class='tlst'><label for="io_up_id">추천ID</label></li>
                <li class='trst padt2'><input type="text" id="io_up_id" name="io_up_id" value="<?php echo  $io_up_id  ?>" class="frm_input w200" size="20"/></li>
            </ul>
        </div>
        <div class="sp_tbox">
            <ul>
                <li class='tlst'><label for="io_pt_id">후원ID</label></li>
                <li class='trst padt2'><input type="text" id="io_pt_id" name="io_pt_id" value="<?php echo  $io_pt_id  ?>" class="frm_input w200" size="20"/></li>
            </ul>
        </div>

        <script>
            (function($){
                $(document).ready(function(){
                    $('#io_up_id, #io_pt_id').on('blur', function(){
                        var grade = $('#buy_minishop_grade').val();
                        var id    = $(this).val();
                        if( id == '' || id == 'admin' || id == 'k2k9' ) return;
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
    <?php endif; ?>
    <?php //\\ 추춴ID, 추천ID 입력 ; ?>



	<!-- 총 구매액 -->
	<?php
        if( $gs['point_pay_allow'] && Good::usablePoint($gs)) {
	?>
	<!--	<div id="sit_tot_views" class="dn">
			<div class="sp_tot">
				<ul>
					<li class='tlst strong'>쇼핑포인트 할인 전 금액</li>
					<li class='trst'><span id="sit_tot_price" class="trss-amt"></span><span class="trss-amt">원</span></li>
				</ul>
			</div>
		</div>
		<div id="sit_tot_views2" class="dn">
			<div class="sp_tot">
				<ul>
					<li class='tlst strong'>쇼핑포인트 할인 후 금액</li>
					<li class='trst'><span id="sit_tot_price2" class="trss-amt"></span><span class="trss-amt">원</span></li>
				</ul>
			</div>
		</div> -->
	<?php
		}else{
		?>
		<div id="sit_tot_views" class="dn">
			<div class="sp_tot">
				<ul>
					<li class='tlst strong'>총 합계 금액</li>
					<li class='trst'><span id="sit_tot_price" class="trss-amt"></span><!--<span class="trss-amt">원</span>--></li>
				</ul>
			</div>
		</div>
		<?php
		}
	?>

	<?php } ?>

	<?php if(!$is_pr_msg) { ?>
	<div class="sp_vbox tac">
		<?php echo mobile_buy_button($script_msg, $gs_id); ?>
		<?php if($naverpay_button_js) { ?>
		<div class="naverpay-item"><?php echo $naverpay_request_js.$naverpay_button_js; ?></div>
		<?php } ?>
	</div>
	<?php } ?>
<?php if($gs['compare']=="Y" && ($gs['compare_0'] || $gs['compare_1'] || $gs['compare_2'] || $gs['compare_3'] || $gs['compare_4'] || $gs['compare_5'] || $gs['compare_6'] || $gs['compare_7'] || $gs['compare_8'] || $gs['compare_9'])) { ?>
  <div class="CMPR">
    <p class="tit">최저가를 한방에 알 수 있는 블링뷰티 복지몰 국내 쇼핑몰 최저가 비교</p>
    <p class="des">블링뷰티 복지몰에서는 국내대표 쇼핑몰(쿠팡,네이버,11번가,옥션) 판매상품 빅데이터를 분석하여 최저가로 회원 여러분께 제공해드립니다.<br>가격비교 사이트(다나와,에누리)에서 시간만 소비하셨나요? 이젠 블링뷰티 복지몰에서 한방에 최저가 구입하세요!<br>(국내 가격비교 사이트는 최저가를 검색해서 찾아가지만 많은 옵션선택 배송비 결제방식 멤버쉽가입여부에 따라 판매가격이 달라지게 됩니다. 블링뷰티복지몰은 빅데이터로 최저가를 찾아내고 전문인력이 직접 확인하므로 정확합니다.)</p>
    <table>
      <tr style="border:1px solid #EFF0F1;width:100%;height:45px;">
        <th style="width:20%;">쇼핑몰</th>
        <th>상품정보</th>
        <th style="width:20%;">판매가</th>
        <th style="width:10%;">기타정보</th>
      </tr>
      <?php if(is_array($compare_0) && $gs['compare_0'] && $gs['compare']=="Y") { ?>
      <tr>
        <td rowspan="2" class="r2shpm"><?php echo $compare_0['0']; ?></td>
        <td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_0['1']; ?></td>
        <td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_0['3']; ?></td>
        <td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
      </tr>
      <tr>
        <td class="left"><?php echo $compare_0['2']; ?></td>
        <td><img src="../../../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_0['4']; ?></td>
      </tr>
      <?php } ?>
      <?php if(is_array($compare_1) && $gs['compare_1'] && $gs['compare']=="Y") { ?>
      <tr>
        <td rowspan="2" class="r2shpm"><?php echo $compare_1['0']; ?></td>
        <td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_1['1']; ?></td>
        <td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_1['3']; ?></td>
        <td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
      </tr>
      <tr>
        <td class="left"><?php echo $compare_1['2']; ?></td>
        <td><img src="../../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_1['4']; ?></td>
      </tr>
      <?php } ?>
      <?php if(is_array($compare_2) && $gs['compare_2'] && $gs['compare']=="Y") { ?>
      <tr>
        <td rowspan="2" class="r2shpm"><?php echo $compare_2['0']; ?></td>
        <td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_2['1']; ?></td>
        <td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_2['3']; ?></td>
        <td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
      </tr>
      <tr>
        <td class="left"><?php echo $compare_2['2']; ?></td>
        <td><img src="../../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_2['4']; ?></td>
      </tr>
      <?php } ?>
      <?php if(is_array($compare_3) && $gs['compare_3'] && $gs['compare']=="Y") { ?>
      <tr>
        <td rowspan="2" class="r2shpm"><?php echo $compare_3['0']; ?></td>
        <td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_3['1']; ?></td>
        <td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_3['3']; ?></td>
        <td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
      </tr>
      <tr>
        <td class="left"><?php echo $compare_3['2']; ?></td>
        <td><img src="../../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_3['4']; ?></td>
      </tr>
      <?php } ?>
      <?php if(is_array($compare_4) && $gs['compare_4'] && $gs['compare']=="Y") { ?>
      <tr>
        <td rowspan="2" class="r2shpm"><?php echo $compare_4['0']; ?></td>
        <td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_4['1']; ?></td>
        <td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_4['3']; ?></td>
        <td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
      </tr>
      <tr>
        <td class="left"><?php echo $compare_4['2']; ?></td>
        <td><img src="../../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_4['4']; ?></td>
      </tr>
      <?php } ?>
      <?php if(is_array($compare_5) && $gs['compare_5'] && $gs['compare']=="Y") { ?>
      <tr>
        <td rowspan="2" class="r2shpm"><?php echo $compare_5['0']; ?></td>
        <td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_5['1']; ?></td>
        <td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_5['3']; ?></td>
        <td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
      </tr>
      <tr>
        <td class="left"><?php echo $compare_5['2']; ?></td>
        <td><img src="../../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_5['4']; ?></td>
      </tr>
      <?php } ?>
      <?php if(is_array($compare_6) && $gs['compare_6'] && $gs['compare']=="Y") { ?>
      <tr>
        <td rowspan="2" class="r2shpm"><?php echo $compare_6['0']; ?></td>
        <td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_6['1']; ?></td>
        <td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_6['3']; ?></td>
        <td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
      </tr>
      <tr>
        <td class="left"><?php echo $compare_6['2']; ?></td>
        <td><img src="../../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_6['4']; ?></td>
      </tr>
      <?php } ?>
      <?php if(is_array($compare_7) && $gs['compare_7'] && $gs['compare']=="Y") { ?>
      <tr>
        <td rowspan="2" class="r2shpm"><?php echo $compare_7['0']; ?></td>
        <td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_7['1']; ?></td>
        <td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_7['3']; ?></td>
        <td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
      </tr>
      <tr>
        <td class="left"><?php echo $compare_7['2']; ?></td>
        <td><img src="../../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_7['4']; ?></td>
      </tr>
      <?php } ?>
      <?php if(is_array($compare_8) && $gs['compare_8'] && $gs['compare']=="Y") { ?>
      <tr>
        <td rowspan="2" class="r2shpm"><?php echo $compare_8['0']; ?></td>
        <td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_8['1']; ?></td>
        <td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_8['3']; ?></td>
        <td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
      </tr>
      <tr>
        <td class="left"><?php echo $compare_8['2']; ?></td>
        <td><img src="../../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_8['4']; ?></td>
      </tr>
      <?php } ?>
      <?php if(is_array($compare_9) && $gs['compare_9'] && $gs['compare']=="Y") { ?>
      <tr>
        <td rowspan="2" class="r2shpm"><?php echo $compare_9['0']; ?></td>
        <td class="left" style="font-size:13px;vertical-align:bottom"><?php echo $compare_9['1']; ?></td>
        <td style="color:red;font-size:13px;font-weight:700;vertical-align:bottom"><?php echo $compare_9['3']; ?></td>
        <td rowspan="2" class="r2free"><?php echo $compare_0['5']; ?></td>
      </tr>
      <tr>
        <td class="left"><?php echo $compare_9['2']; ?></td>
        <td><img src="../../theme/basic/img/tabler-icon-truck.svg" class="icon"><?php echo $compare_9['4']; ?></td>
      </tr>
      <?php } ?>
    </table>
    <style>
      .CMPR{width:94%;margin:20px 0;padding:0 3%;}
      .CMPR .tit{height:50px;font-size:18px;font-weight:600;overflow:visible;}
      .CMPR .des{font-size:14px;line-height:22px;}
      .CMPR table{width:100%;text-align:center;margin-top:20px}
      .CMPR td, .CMPR th{height:30px;}
      .CMPR th{font-size:12px;font-weight:500;}
      .CMPR .left{text-align:left;}
      .CMPR .icon{width:18px;margin-right:5px;}
      .CMPR tr:nth-child(2n+1), .CMPR  .r2free{border-bottom:1px solid #eee;}
      .CMPR .r2shpm{font-size:1rem;font-weight:600;border-bottom:1px solid #eee;}
    </style>
  </div>
<?php } ?>
	<div class="sp_tab">
		<nav role="navigation">
			<ul>
				<li id='d1' class="active"> <a href="javascript:chk_tab(1);">상품정보</a> </li>
				<li id='d2'> <a href="javascript:chk_tab(2);">구매후기</a> </li>
				<li id='d3'> <a href="javascript:chk_tab(3);">Q&A</a> </li>
				<li id='d4'> <a href="javascript:chk_tab(4);">반품/교환</a> </li>
			</ul>
		</nav>
	</div>

	<div class="sp_msgt">아래 상품정보는 실제 상품과 차이가 있을수 있습니다</div>
	<div id="v1">
		<div class="sp_vbox">
			<ul>
				<li class='tlst'>&#183;&nbsp;&nbsp;상품번호</li>
				<li class='trst'><?php echo $gs['gcode']; ?></li>
			</ul>
			<ul>
				<li class='tlst padt2'>&#183;&nbsp;&nbsp;제조사</li>
				<li class='trst padt2'><?php echo $gs['maker']; ?></li>
			</ul>
			<ul>
				<li class='tlst padt2'>&#183;&nbsp;&nbsp;원산지 (생산국)</li>
				<li class='trst padt2'><?php echo $gs['origin']; ?></li>
			</ul>
			<ul>
				<li class='tlst padt2'>&#183;&nbsp;&nbsp;A/S 가능여부</li>
				<li class='trst padt2'><?php echo $gs['repair']; ?></li>
			</ul>
		</div>

		<div class="sp_vbox_mr">
			<ul>
				<li class='tlst'>전자상거래 등에서의 상품정보제공 고시</li>
				<li class='trst'><a href="javascript:chk_show('extra');" id="extra">보기 <span class='im im_arr'></span></a></li>
			</ul>
		</div>

		<?php
		if($gs['info_value']) {
			$info_data = unserialize(stripslashes($gs['info_value']));
			if(is_array($info_data)) {
				$gubun = $gs['info_gubun'];
				$info_array = $item_info[$gubun]['article'];
		?>
		<div class="sp_vbox" id="ids_extra" style="display:none;">
			<?php
			foreach($info_data as $key=>$val) {
				$ii_title = $info_array[$key][0];
				$ii_value = $val;
			?>
			<ul>
				<li class='tlst<?php echo $pd_t2; ?>'>&#183;&nbsp;&nbsp;<?php echo $ii_title; ?></li>
				<li class='trst<?php echo $pd_t2; ?>'><?php echo $ii_value; ?></li>
			</ul>
			<?php
				$pd_t2 = ' padt2';
			} //foreach
			?>
		</div>
		<?php
			} //array
		} //if
		?>

		<div class="sp_vbox">
		<?php echo mobile_banner(13, $pt_id, $member['grade'], $member['mb_category']); ?>
			<?php echo get_image_resize($gs['memo']); ?>
		</div>

		<?php
		$sql = " select b.*
				   from shop_goods_relation a left join shop_goods b ON (a.gs_id2=b.index_no)
				  where a.gs_id = '{$gs_id}'
					and b.shop_state = '0'
					and b.isopen < 3 ";
		$res = sql_query($sql);
		$rel_count = sql_num_rows($res);
		if($rel_count > 0) {
		?>
		<div class="sp_rel">
			<h3><span>현재상품과 연관된 상품</span></h3>
			<div>
				<?php
				for($i=0; $row=sql_fetch_array($res); $i++) {
					$it_href = MS_MSHOP_URL.'/view.php?gs_id='.$row['index_no'];
					$it_name = cut_str($row['gname'], 50);
					$it_imageurl = get_it_image_url($row['index_no'], $row['simg1'], 400, 400);
					$it_price = mobile_price($row['index_no']);
					$it_amount = get_sale_price($row['index_no']);
					$it_point = display_point($row['gpoint']);

					// (시중가 - 할인판매가) / 시중가 X 100 = 할인률%
					$it_sprice = $sale = '';
					if($row['normal_price'] > $it_amount && !is_uncase($row['index_no'])) {
						$sett = ($row['normal_price'] - $it_amount) / $row['normal_price'] * 100;
						$sale = '<span class="sale">['.number_format($sett,0).'%]</span>';
						$it_sprice = display_price2($row['normal_price']);
					}
				?>
				<dl>
				<a href="<?php echo $it_href; ?>">
					<dt><img src="<?php echo $it_imageurl; ?>"></dt>
					<dd class="pname"><?php echo $it_name; ?></dd>
					<?php
					if($row['info_color']) {
						echo "<dd class=\"op_color\">\n";
						$arr = explode(",", trim($row['info_color']));
						for($g=0; $g<count($arr); $g++) {
							echo get_color_boder(trim($arr[$g]), 1);
						}
						echo "</dd>\n";
					}
					?>
					<dd class="price"><?php echo $it_sprice; ?><?php echo $it_price; ?></dd>
				</a>
				</dl>
				<?php } ?>
			</div>
			<?php if($rel_count > 3) { ?>
			<script>
			$(document).ready(function(){
				$('.sp_rel div').slick({
					autoplay: false,
					dots: false,
					arrows: true,
					infinite: false,
					slidesToShow: 3,
					slidesToScroll: 1
				});
			});
			</script>
			<?php } ?>
		</div>
		<?php } ?>
	</div>

	<div id="v2" style="display:none;">
		<?php echo mobile_goods_review("구매후기", $item_use_count, $gs_id); ?>
	</div>

	<div id="v3" style="display:none;">
		<?php echo mobile_goods_qa("Q&A", $itemqa_count, $gs_id); ?>
	</div>

	<div id="v4" style="display:none;">
		<div class="sp_vbox">
			<?php echo get_policy_content($gs_id); ?>
		</div>
	</div>
</div>
</form>
<div class="btn_result">
</div>
<script>
$(function() {
    $("#requestBtn").on("click", function() {
		var f = document.fbuyform;
		if(fsubmit_check(f) == true){
			var params = jQuery("#fbuyform").serialize(); // serialize() : 입력된 모든Element(을)를 문자열의 데이터에 serialize 한다.
			  $.ajax({
				url:"./cartupdate2.php",
				type:"post" , 
				dataType: 'html',
				data:params,
				success:function(data){ //통신이 성공적일때 출력
				  $(".btn_result").html(data);
				},
				error:function(xhr,status,error){ //에러시 출력
				  alert("ERROR xhr :"+xhr+", status :"+status+", error :"+error); 
				} 
			});
		}
	});
});
// 상품보관
function item_wish(f)
{
	f.action = "./wishupdate.php";
	f.submit();
}

function fsubmit_check(f)
{
    // 판매가격이 0 보다 작다면
    if (document.getElementById("it_price").value < 0) {
        alert("전화로 문의해 주시면 감사하겠습니다.");
        return false;
    }

	if($(".sit_opt_list").size() < 1) {
		alert("주문옵션을 선택해주시기 바랍니다.");
		return false;
	}

    var val, io_type, result = true;
    var sum_qty = 0;
	var min_qty = parseInt('<?php echo $odr_min; ?>');
	var max_qty = parseInt('<?php echo $odr_max; ?>');
    var $el_type = $("input[name^=io_type]");

    $("input[name^=ct_qty]").each(function(index) {
        val = $(this).val();

        if(val.length < 1) {
            alert("수량을 입력해 주십시오.");
            result = false;
            return false;
        }

        if(val.replace(/[0-9]/g, "").length > 0) {
            alert("수량은 숫자로 입력해 주십시오.");
            result = false;
            return false;
        }

        if(parseInt(val.replace(/[^0-9]/g, "")) < 1) {
            alert("수량은 1이상 입력해 주십시오.");
            result = false;
            return false;
        }

        io_type = $el_type.eq(index).val();
        if(io_type == "0")
            sum_qty += parseInt(val);
    });

    if(!result) {
        return false;
    }

    if(min_qty > 0 && sum_qty < min_qty) {
		alert("주문옵션 개수 총합 "+number_format(String(min_qty))+"개 이상 주문해 주세요.");
        return false;
    }

    if(max_qty > 0 && sum_qty > max_qty) {
		alert("주문옵션 개수 총합 "+number_format(String(max_qty))+"개 이하로 주문해 주세요.");
        return false;
    }

    return true;
}

// 바로구매, 장바구니 폼 전송
function fbuyform_submit(sw_direct)
{
	var f = document.fbuyform;
	f.sw_direct.value = sw_direct;

	if(sw_direct == "cart") {
		f.sw_direct.value = 0;
	} else { // 바로구매
		f.sw_direct.value = 1;
	}

	if($(".sit_opt_list").size() < 1) {
		alert("주문옵션을 선택해주시기 바랍니다.");
		return;
	}

	var val, io_type, result = true;
	var sum_qty = 0;
	var min_qty = parseInt('<?php echo $odr_min; ?>');
	var max_qty = parseInt('<?php echo $odr_max; ?>');
	var $el_type = $("input[name^=io_type]");

	$("input[name^=ct_qty]").each(function(index) {
		val = $(this).val();

		if(val.length < 1) {
			alert("수량을 입력해 주세요.");
			result = false;
			return;
		}

		if(val.replace(/[0-9]/g, "").length > 0) {
			alert("수량은 숫자로 입력해 주세요.");
			result = false;
			return;
		}

		if(parseInt(val.replace(/[^0-9]/g, "")) < 1) {
			alert("수량은 1이상 입력해 주세요.");
			result = false;
			return;
		}

		io_type = $el_type.eq(index).val();
		if(io_type == "0")
			sum_qty += parseInt(val);
	});

	if(!result) {
		return;
	}

	if(min_qty > 0 && sum_qty < min_qty) {
		alert("주문옵션 개수 총합 "+number_format(String(min_qty))+"개 이상 주문해 주세요.");
		return;
	}

	if(max_qty > 0 && sum_qty > max_qty) {
		alert("주문옵션 개수 총합 "+number_format(String(max_qty))+"개 이하로 주문해 주세요.");
		return;
	}

	f.action = "./cartupdate.php";
	f.submit();
}

// 전자상거래 등에서의 상품정보제공 고시
var old = '';
function chk_show(name) {
	submenu=eval("ids_"+name+".style");

	if(old!=submenu) {
		if(old) { old.display='none'; }

		submenu.display='';
		eval("extra").innerHTML = "닫기";
		old = submenu;

	} else {
		submenu.display='none';
		eval("extra").innerHTML = "보기";
		old = '';
	}
}

// 상품문의
var qa_old = '';
function qna(name){
	qa_submenu = eval("qna"+name+".style");

	if(qa_old!=qa_submenu) {
		if(qa_old) { qa_old.display='none'; }

		qa_submenu.display='block';
		qa_old=qa_submenu;

	} else {
		qa_submenu.display='none';
		qa_old='';
	}
}

// 상품문의 삭제
$(function(){
    $(".itemqa_delete").click(function(){
        return confirm("정말 삭제 하시겠습니까?\n\n삭제후에는 되돌릴수 없습니다.");
    });
});

// 탭메뉴 컨트롤
function chk_tab(n) {
	for(var i=1; i<=4; i++) {
		if(eval("d"+i).className == "" && i == n) {
			eval("d"+i).className = "active";
			eval("v"+i).style.display = "";
		} else {

			if(i != n) {
				eval("d"+i).className = "";
				eval("v"+i).style.display = "none";
			}
		}
	}
}

// 미리보기 이미지
var num = 0;
var img_url = '<?php echo $slide_url; ?>';
var img_max = '<?php echo $slide_cnt; ?>';
var img_arr = img_url.split('|');
var slide   = [];
for(var i=0 ;i<parseInt(img_max);i++) {
	slide[i] = img_arr[i];
}

var cnt = slide.length-1;

function chgimg(ergfun) {
	if(document.images) {
		num = num + ergfun;
		if(num > cnt) { num = 0; }
		if(num < 0) { num = cnt; }

		document.slideshow.src = slide[num];
	}
}
</script>
