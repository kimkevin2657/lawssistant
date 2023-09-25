<?php
//echo $goods_kv_basic."<br>".$gpoint_basic;
// 마일리지 적용에 따른 출력형태
if($goods_kv_basic=='1'){
	if(($gs['goods_kv'] > 0 || $gs['goods_kv_per'] > 0 ) && $gs['goods_price'] > 0){
		if($gs['goods_kv']>0&&$gs['goods_kv_per']>0){		// 둘다 있을경우 %를 기준으로
			$gs['goods_kv'] = round($gs['goods_price'] * $gs['goods_kv_per'] / 100);
		}elseif($gs['goods_kv']>0&&!$gs['goods_kv_per']){

		}elseif(!$gs['goods_kv']&&$gs['goods_kv_per']>0){
			$gs['goods_kv'] = round($gs['goods_price'] * $gs['goods_kv_per'] / 100);
		}
		$rate2 = number_format((($gs['goods_kv'] / $gs['goods_price']) * 100), 0);
		$usablePoint .= '<p class="mpr" style="color:blue !important;font-weight:normal;"><em class="fc_90" style="color:blue !important;font-size:13px;">마일리지 적립</em> '.number_format($gs['goods_kv']).'원 ('.$rate2.'%)</p>';
		$goods_kv = display_price($gs['goods_kv'])." <span class='fc_107'>(".$rate2."%)</span>";
	}
}else{
	if(($config['basic_goods_kv'] > 0 || $config['basic_goods_kv_per'] > 0 ) && $gs['goods_price'] > 0){
		if($config['basic_goods_kv']>0&&$config['basic_goods_kv_per']>0){		// 둘다 있을경우 %를 기준으로
			$gs['goods_kv'] = round($gs['goods_price'] * $config['basic_goods_kv_per'] / 100);
		}elseif($config['basic_goods_kv']>0&&!$config['basic_goods_kv_per']){

		}elseif(!$config['basic_goods_kv']&&$config['basic_goods_kv_per']>0){
			$gs['goods_kv'] = round($gs['goods_price'] * $config['basic_goods_kv_per'] / 100);
		}
		$rate2 = number_format((($gs['goods_kv'] / $gs['goods_price']) * 100), 0);
		$usablePoint .= '<p class="mpr" style="color:blue !important;font-weight:normal;"><em class="fc_90" style="color:blue !important;font-size:13px;">마일리지 적립</em> '.number_format($gs['goods_kv']).'원 ('.$rate2.'%)</p>';
		$goods_kv = display_price($gs['goods_kv'])." <span class='fc_107'>(".$rate2.")</span>";
	}
}

// 쇼핑포인트 적용에 따른 출력형태
if($gpoint_basic=='1'){
	if(($gs['gpoint'] > 0 || $gs['gpoint_per'] > 0 ) && $gs['goods_price'] > 0){
		if($gs['gpoint']>0&&$gs['gpoint_per']>0){		// 둘다 있을경우 %를 기준으로
			$gs['gpoint'] = round($gs['goods_price'] * $gs['gpoint_per'] / 100);
		}elseif($gs['gpoint']>0&&!$gs['gpoint_per']){

		}elseif(!$gs['gpoint']&&$gs['gpoint_per']>0){
			$gs['gpoint'] = round($gs['goods_price'] * $gs['gpoint_per'] / 100);
		}
		$rate = number_format((($gs['gpoint'] / $gs['goods_price']) * 100), 0);
		$usablePoint .= '<p class="mpr" style="color:#000 !important;font-weight:normal;"><em class="fc_90" style="color:#000 !important;font-size:13px;">쇼핑포인트 적립</em> '.number_format($gs['gpoint']).'원 ('.$rate.'%)</p>';
		$gpoint = display_point($gs['gpoint'])." <span class='fc_107'>(".$rate."%)</span>";
	}
}else{
	if(($config['basic_gpoint'] > 0 || $config['basic_gpoint_per'] > 0 ) && $gs['goods_price'] > 0){
		if($config['basic_gpoint']>0&&$config['basic_gpoint_per']>0){		// 둘다 있을경우 %를 기준으로
			$gs['gpoint'] = round($gs['goods_price'] * $config['basic_gpoint_per'] / 100);
		}elseif($config['basic_gpoint']>0&&!$config['basic_gpoint_per']){

		}elseif(!$config['basic_gpoint']&&$config['basic_gpoint_per']>0){
			$gs['gpoint'] = round($gs['goods_price'] * $config['basic_gpoint_per'] / 100);
		}
		$rate = number_format((($gs['gpoint'] / $gs['goods_price']) * 100), 0);
		$usablePoint .= '<p class="mpr" style="color:#000 !important;font-weight:normal;"><em class="fc_90" style="color:#000 !important;font-size:13px;">쇼핑포인트 적립</em> '.number_format($gs['gpoint']).'원 ('.$rate.'%)</p>';
		$gpoint = display_point($gs['gpoint'])." <span class='fc_107'>(".$rate."%)</span>";
	}
}

?>