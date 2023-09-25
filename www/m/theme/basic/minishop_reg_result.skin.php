<?php
if(!defined('_MALLSET_')) exit;
?>

<div id="fminishop_result">
	<h3 class="anc_tit">입금받으실 계좌</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w80">
			<col>
		</colgroup>
		<tbody>
		<tr>
			<th scope="row">은행명</th>
			<td><?php echo $minishop['bank_name']; ?></td>
		</tr>
		<tr>
			<th scope="row">계좌번호</th>
			<td><?php echo $minishop['bank_account']; ?></td>
		</tr>
		<tr>
			<th scope="row">예금주명</th>
			<td><?php echo $minishop['bank_holder']; ?></td>
		</tr>
		</tbody>
		</table>
	</div>

	<h3 class="anc_tit">결제정보</h3>
	<div class="tbl_frm01 tbl_wrap">
		<table>
		<colgroup>
			<col class="w80">
			<col>
		</colgroup>
		<tr>
			<th scope="row">신청일시</th>
			<td><?php echo $minishop['reg_time']; ?></td>
		</tr>
		<tr>
			<th scope="row">결제방법</th>
			<td><?php echo ($minishop['pay_settle_case']=='1')?"무통장입금":"신용카드"; ?></td>
		</tr>
		<tr>
			<th scope="row">결제금액</th>
			<td>
				<?php 
				if($minishop['receipt_price'] > 0) 
					echo display_price($minishop['receipt_price']);
				else
					echo '무료';
				?>
			</td>
		</tr>
		<?php if($minishop['pay_settle_case']=='1') { ?>		
		<tr>
			<th scope="row">입금계좌</th>
			<td><?php echo $minishop['bank_acc']; ?></td>
		</tr>
		<tr>
			<th scope="row">입금자명</th>
			<td><?php echo $minishop['deposit_name']; ?></td>
		</tr>
		<?php } ?>
		<?php if($minishop['memo']) { ?>
		<tr>
			<th scope="row">전달사항</th>
			<td><?php echo conv_content($minishop['memo'], 0); ?></td>
		</tr>
		<?php } ?>
		</table>
	</div>

	<div class="btn_confirm">
		<a href="<?php echo MS_MURL; ?>" class="btn_medium wset">확인</a>
	</div>
</div>
