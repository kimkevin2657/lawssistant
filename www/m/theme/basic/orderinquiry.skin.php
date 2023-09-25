<?php
if(!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가
?>

<div id="sod_v">
	<div id="sod_fin_no">
		<strong>총 <?php echo number_format($total_count); ?>건</strong>의 주문내역이 있습니다.
	</div>

	<div id="sod_inquiry">			
		<?php
		for($i=0; $row=sql_fetch_array($result); $i++)
		{			
			echo '<li>'.PHP_EOL;
			
			$sql = " select * from shop_cart where od_id = '$row[od_id]' ";
			$sql.= " group by gs_id order by io_type asc, index_no asc ";
			$res = sql_query($sql);
			for($k=0; $ct=sql_fetch_array($res); $k++) {
				$rw = get_order($ct['od_no']);
				$gs = unserialize($rw['od_goods']);

				$href = MS_MSHOP_URL.'/view.php?gs_id='.$rw['gs_id'];
				
				$dlcomp = explode('|', trim($rw['delivery']));

        $rd = get_review($rw['od_id']);
				
				$delivery_str = '';
				if($dlcomp[0] && $rw['delivery_no']) {
					$delivery_str = get_text($dlcomp[0]).' '.get_text($rw['delivery_no']);
				}

				$uid = md5($rw['od_id'].$rw['od_time'].$rw['od_ip']);

				if($rw['dan'] == 5 && !$rd['od_id']) {
					$li_btn = '<a href="'.MS_MSHOP_URL.'/orderreview.php?gs_id='.$rw['gs_id'].'&od_id='.$rw['od_id'].'" onclick="win_open(this, \'winorderreview\');return false;" class="btn_ssmall bx-white" style="margin-top:5px;">구매후기</a>';
				}  else {
          $li_btn = '';
        }

				if($k == 0) {
		?>	        
            <div class="inquiry_idtime">
                <a href="<?php echo MS_MSHOP_URL; ?>/orderinquiryview.php?od_id=<?php echo $rw['od_id']; ?>&uid=<?php echo $uid; ?>" class="idtime_link"><?php echo $rw['od_id']; ?></a>
                <span class="idtime_time"><?php echo substr($rw['od_time'],2,8); ?></span>
            </div>
			<?php } ?>
			<div class="inquiry_info">
				<div class="inquiry_name">
					<a href="<?php echo $href; ?>"><?php echo get_text($gs['gname']); ?></a>
				</div>
				<div class="inquiry_price">
					<?php echo display_price($rw['use_price']); ?>
				</div>
				<div class="inquiry_inv">
					<span class="inv_status"><?php echo $gw_status[$rw['dan']]; ?></span>
            <?php if(in_array($rw['dan'], [3,4])) { ?>
              <?php echo get_delivery_inquiry($rw['delivery'], $rw['delivery_no'], 'btn_ssmall'); ?>
            <?php } ?>
            <?=$li_btn?>
					<span class="inv_inv"><?php echo $delivery_str; ?><?php echo $rw['delivery_no']; ?></span>
				</div>
			</div>
		
		<?php
			}
			echo '</li>'.PHP_EOL;
		}

        if($i == 0)
            echo '<li class="empty_list">주문 내역이 없습니다.</li>';
        ?>
    </ul>

	<?php 
	echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page='); 
	?>

	</div>
</div>
