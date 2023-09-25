<?php
if(!defined('_MALLSET_')) exit;

// 설정일이 지난 배송완료상품 구매확정
if($default['de_final_keep_term'] > 0) {
	$tmp_before_date = date("Y-m-d", MS_SERVER_TIME - ($default['de_final_keep_term'] * 86400));
	$sql = " update shop_order
				set user_ok = '1'
				  , user_date = '".MS_TIME_YMDHIS."'
			  where left(invoice_date,10) < '".$tmp_before_date."'
		        and user_ok = '0'
			    and dan = '5' ";

    $sql = " select od_no
               from shop_order
			  where left(invoice_date,10) < '".$tmp_before_date."'
		        and user_ok = '0'
			    and dan = '5' ";
	$rslt = sql_query($sql, FALSE);
	while($row = sql_fetch_array($rslt)){
	    change_status_final($row['od_no']);
    }
}

// 설정일이 지난 배송중인 주문내역 자동 배송완료
if($default['de_bae_keep_term'] > 0) {
	$tmp_before_date = date("Y-m-d", MS_SERVER_TIME - ($default['de_bae_keep_term'] * 86400));
	$sql = " select *
			   from shop_order
			  where left(od_time,10) < '$tmp_before_date'
				and dan = '4'
			  order by index_no ";
	$res = sql_query($sql);
	while($row=sql_fetch_array($res)) {
		change_order_status_5($row['od_no']);
	}
}
?>

<div id="ajax-loading"><img src="<?php echo MS_IMG_URL; ?>/ajax-loader.gif"></div>
<?php if(!defined('_NEWWIN_')) { // 팝업창은 실행하지 않는다 ?>
<div id="anc_header"><a href="#anc_hd"><span></span>TOP</a></div>
<?php } ?>

<script src="<?php echo MS_ADMIN_URL; ?>/js/admin.js?ver=<?php echo MS_JS_VER; ?>"></script>

<script src="<?php echo MS_JS_URL; ?>/wrest.js"></script>
</body>
</html>
<?php echo html_end(); // HTML 마지막 처리 함수 : 반드시 넣어주시기 바랍니다. ?>