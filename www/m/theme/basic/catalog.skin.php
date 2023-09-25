<?php
if(!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가
?>

<div class="sp_wrap">
	<div class="sp_sub_wrap">
		<div class="v_cont">
			<ul class="v_horiz">
				<li><?php echo get_it_image($gs_id, $gs['simg2'], $default['de_item_medium_wpx'], $default['de_item_medium_hpx'], 'name="slideshow"'); ?></li>
			</ul>
		</div>
	</div>
	<div class="subject">
		<?php echo get_text($gs['gname']); ?>
		<?php if($gs['explan']) { ?>
		<p class="sub_txt"><?php echo get_text($gs['explan']); ?></p>
		<?php } ?>
	</div>

	<div class="sp_sns">
		만족도 : <?php echo $aver_score; ?>% <span class="hline"></span>상품평 : <?php echo number_format($item_use_count); ?>건
	</div>

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
		<?php if(false && is_minishop($member['id']) && $config['pf_payment_yes']) { ?>
		<ul class="mart3">
			<li class='tlst'>판매수익</li>
			<li class="trst"><?php echo display_price2(get_payment($gs_id)); ?></li>
		</ul>
		<?php } ?>
	</div>
	<?php } ?>
	<?php if(!$is_only && !$is_pr_msg && !$is_buy_only && !$is_soldout && $gpoint) { ?>
	<div class="sp_tbox">
		<ul>
			<li class='tlst'>쇼핑포인트</li>
			<li class='trst strong'><?php echo $gpoint; ?></li>
		</ul>
	</div>
	<?php } ?>
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


	<div class="sp_msgt">아래 상품정보는 옵션 및 사은품 정보 등 실제 상품과 차이가 있을수 있습니다</div>
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
			<?php echo get_image_resize($gs['memo']); ?>
		</div>

	</div>


</div>

<script>

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

</script>
