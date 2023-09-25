<?php
if(!defined('_MALLSET_')) exit;

$pg_title = "마일리지 정산";
include_once("./admin_head.sub.php");

if(!$year) $year = MS_TIME_YEAR;
if(!$month) $month = MS_TIME_MONTH;

$sum = get_pay_sheet($member['id']); // 누적

// 가맹점 직접출금
if($config['pf_payment_type']) {
?>

<h2>정산내역</h2>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w50">
		<col class="w60">
		<col class="w130">
		<col class="w100">
		<col class="w100">
		<col class="w100">
		<col>
	</colgroup>
	<thead>
	<tr>
		<th scope="col">번호</th>
		<th scope="col">상태</th>
		<th scope="col">신청일시</th>
		<th scope="col" class="th_bg">출금요청</th>
		<th scope="col" class="th_bg">세금공제</th>
		<th scope="col" class="th_bg">실수령액</th>
		<th scope="col">정산처리 입금계좌</th>
	</tr>
	</thead>
	<?php
	$q1 = "code=$code$qstr";

	$sql_common = " from shop_minishop_payrun ";
	$sql_search = " where mb_id = '{$member['id']}' ";
	$sql_order  = " order by index_no desc";

	// 테이블의 전체 레코드수만 얻음
	$sql = " select count(*) as cnt $sql_common $sql_search ";
	$row = sql_fetch($sql);
	$total_count = $row['cnt'];

	$rows = 10;
	$total_page = ceil($total_count / $rows); // 전체 페이지 계산
	if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
	$from_record = ($page - 1) * $rows; // 시작 열을 구함
	$num = $total_count - (($page-1)*$rows);

	$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($i==0)
			echo '<tbody class="list">'.PHP_EOL;

		$bg = 'list'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td><?php echo $num--; ?></td>
		<td><?php echo $row['state']?'완료':'대기'; ?></td>
		<td><?php echo $row['reg_time']; ?></td>
		<td class="tar"><?php echo number_format($row['balance']); ?></td>
		<td class="tar fc_red"><?php echo number_format($row['paytax']); ?></td>
		<td class="tar fc_00f"><?php echo number_format($row['paynet']); ?></td>
		<td class="tal"><?php echo print_minishop_bank2($row['bank_name'], $row['bank_account'], $row['bank_holder']); ?></td>
	</tr>
	<?php
	}
	if($i==0)
		echo '<tbody><tr><td colspan="7" class="empty_list">자료가 없습니다.</td></tr>';
	?>
	</tbody>
	</table>
</div>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>

<h2>출금요청</h2>
    <?php if( true || minishop::canPayCalcStatus($member['id']) ) : ?>
<form name="fpayform" method="post" action="./minishop_paylist_update.php" onsubmit="return fpayform_check(this);" autocomplete="off">
<input type="hidden" name="token" value="">
    <input type="hidden" name="company_saupja_no" id="company_saupja_no" value="<?php echo $minishop['company_saupja_no']; ?>">

<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w120">
		<col>
	</colgroup>
        <tr>
            <th scope="row">사업자 정보</th>
            <td><?php if( empty($minishop['company_saupja_no']) ) { ?>
                    사업자 번호 또는 주민등록 번호를 등록하셔야 출금 신청 하실 수 있습니다. [ <a href="/mypage/page.php?code=minishop_info">기본정보관리</a> ]
                <?php } else { ?>
                    <?php echo $gw_company_type[$minishop['company_type']]; ?>
                    <?php echo $minishop['company_name']; ?>
                    <?php echo $minishop['company_saupja_no']; ?>
                <?php }  ?></td>
        </tr>
	<tr>
		<th scope="row">출금요청 금액</th>
		<td>
			<?php
			// 출금요청중인 금액이 있는가?
			$sql = "select SUM(balance) as pay_run
					  from shop_minishop_payrun
					 where mb_id = '{$member['id']}'
					   and state = '0'";
			$res = sql_fetch($sql);

			// 지급예정금액
			$pre = sql_fetch("select sum(pp_pay) pay from shop_minishop_pay where mb_id = '{$member['id']}' and pp_due_date > now()");

			$member_pay = (int)$member['pay']- (int)$res['pay_run'] - (int)$pre['pay']; // 잔액 - 출금신청중인 금액
			$max_price  = (int)((int)($member_pay / $config['pf_payment_unit']) * $config['pf_payment_unit']);
			?>
			<input type="text" name="reg_price" required numeric itemname="출금요청 금액" class="required frm_input" size="10"> 원
			<?php if($config['pf_payment_unit']) { // 출금요청 단위 ?>
			<span class="fc_00f">(<?php echo number_format($config['pf_payment_unit']); ?>원 단위로 입력하세요.)</span>
			<?php } ?>
			<p class="frm_info">
				출금가능 금액 <b><?php echo number_format($member_pay); ?></b>원 중 <b class="fc_red">최대 <?php echo number_format($max_price); ?></b>원 까지 출금 가능<?php if($config['pf_payment']) { ?><br>출금가능 금액이 <strong><?php echo number_format($config['pf_payment']); ?></strong>원 이상부터 출금 가능<?php } ?>
			</p>
		</td>
	</tr>
	<tr>
		<th scope="row">입금계좌 정보</th>
		<td>
			<?php echo print_minishop_bank($member['id']); ?>
			<input type="hidden" name="bank_name" value="<?php echo $minishop['bank_name']; ?>">
			<input type="hidden" name="bank_account" value="<?php echo $minishop['bank_account']; ?>">
			<input type="hidden" name="bank_holder" value="<?php echo $minishop['bank_holder']; ?>">
		</td>
	</tr>
	</table>
</div>
<div class="btn_confirm">
	<input type="submit" value="출금요청" class="btn_medium red">
</div>
</form>

<script>
function fpayform_check(f) {
	var temp_price	= parseInt(f.reg_price.value); // 출금요청 금액
	var max_price	= parseInt(<?php echo $max_price; ?>);  // 최대 출금가능액
	var min_price	= parseInt(<?php echo $config['pf_payment']; ?>);  // 최소 출금가능액
	var price_unit	= parseInt(<?php echo $config['pf_payment_unit']; ?>); // 출금요청 단위

    if( f.company_saupja_no.value == '' ){
        alert('사업자 번호 또는 주민등록 번호를 등록하셔야 출금 신청 하실 수 있습니다.');
        return false;
    }

    if(isNaN(temp_price)) {
		alert("금액을 숫자로 입력하세요.");
		f.reg_price.select();
		return false;
	}

	if(temp_price < 1) {
		alert("금액을 0 이상 입력하세요.");
		f.reg_price.select();
		return false;
	}

	if(max_price < min_price) {
		alert('출금가능 금액이 '+number_format(String(min_price))+'원 이상부터 신청 가능합니다.');
		return false;
	}

	if(temp_price > max_price) {
		alert(number_format(String(max_price)) + "원 이상 신청할 수 없습니다.");
		f.reg_price.select();
		return false;
	}

	if(parseInt(parseInt(temp_price / price_unit) * price_unit) != temp_price) {
		alert("금액을 "+number_format(String(price_unit))+"원 단위로 입력하세요.");
		f.reg_price.select();
		return false;
	}

	if(f.bank_name.value == "" || f.bank_account.value == "" || f.bank_holder.value == "") {
		alert('입금받으실 계좌정보를 기본정보관리에서 먼저 등록해주세요.');
		return false;
	}

	if(confirm("출금요청 하시겠습니까?") == false)
		return false;

	f.action = "./minishop_paylist_update.php";
    return true;
}
</script>
    <?php else : ?>
        <ol>
            <li class="mart10"><i class="fa fa-info-circle"></i> 주민등록번호 미등록자나 관리비 미입금 회원님은 정산요청을 하실 수 없습니다.</li>
        </ol>
    <?php endif; ?>

<?php } ?>

<h2>수수료 리포트</h2>
<form name="fsearch" id="fsearch" method="get">
<input type="hidden" name='code' value="<?php echo $code; ?>">
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w120">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">기간검색</th>
		<td>
			<select name="year">
				<?php
				for($i=(MS_TIME_YEAR-3);$i<(MS_TIME_YEAR+1);$i++) {
					echo "<option value=\"{$i}\"".get_selected($year, $i).">{$i}년</option>\n";
				}
				?>
			</select>
			<select name="month">
				<?php
				for($i=1;$i<=12;$i++) {
					$k = sprintf('%02d',$i);
					echo "<option value=\"{$k}\"".get_selected($month, $k).">{$k}월</option>\n";
				}
				?>
			</select>
			<input type="submit" value="검색" class="btn_small">
		</td>
	</tr>
	</tbody>
	</table>
</div>
</form>

<div class="local_ov mart20">
	<strong class="ov_listall">수수료 누적현황</strong>
	<strong class="fc_107 marr10">총적립액 : <?php echo number_format($sum['pay']); ?>원,</strong>
	<strong class="fc_red marr10">총차감액 : <?php echo number_format($sum['usepay']); ?>원,</strong>
	<strong class="fc_00f">현재잔액 : <?php echo number_format($member['pay']); ?>원</strong>
    <div class="mart10"><i class="fa fa-info-circle"></i> 현재잔액은 정산예정일 도래전 금액 포함 이며 아래는 정산예정일 도래전 금액 미포함입니다.</div>
    <div class="mart10"><i class="fa fa-info-circle"></i> 주민등록번호 미등록자나 관리비 미입금 회원님은 정산대상에서 제외 됩니다.</div>
</div>
    <div class="tbl_head01">
        <table class="tablef">
            <colgroup>
                <col class="w120">
            </colgroup>
            <thead>
            <tr>
                <th scope="col">기간</th>
                <?php foreach($gw_ptype as $key=>$label) : ?>
                    <th scope="col" colspan="2"><?php echo $label; ?></th>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody class="list">
            <?php
            $g = 0;

            foreach($gw_ptype as $key=>$label) :
                $tot_cnt_key = "{$key}_count";
                $tot_price_key="{$key}_price";
                ${$tot_cnt_key}   = 0;
                ${$tot_price_key} =0;
            endforeach;

            for($i=1; $i<=31; $i++) {
                $day = sprintf('%02d', $i);

                $pp_date = preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1-\\2-\\3", $year.$month.$day);

                $select_add = " and left(pp_datetime,10) = '$pp_date' ";

                $atot = 0;
                foreach($gw_ptype as $key=>$label) :
                    ${$key} = get_pay_status($member['id'], $key, $select_add);
                    $atot += ${$key}['cnt'];
                endforeach;

                if($atot) { $th_cl = ' txt_true'; } else { $th_cl = ''; }

                $bg = 'list'.($g%2);
                ?>
                <tr class="<?php echo $bg; ?>">
                    <td class="bold<?php echo $th_cl; ?>"><?php echo $pp_date; ?></td>
                    <?php foreach($gw_ptype as $key=>$label) :
                        $tot_cnt_key = "{$key}_count";
                        $tot_price_key="{$key}_price";
                        ${$tot_cnt_key}  += ${$key}['cnt'];
                        ${$tot_price_key}+= ${$key}['pay'];
                        ?>
                        <td class="tar <?php echo ${$key}['cnt'] > 0 ? 'bold txt_true' : 'txt_false'; ?>"><?php echo ${$key}['cnt']; ?>건</td>
                        <td class="tar <?php echo ${$key}['cnt'] > 0 ? 'bold txt_true' : 'txt_false'; ?>"><?php echo number_format(${$key}['pay']); ?>원</td>
                    <?php endforeach; ?>
                </tr>
                <?php
                $g++;

                // 오늘날짜와 같다면 중지
                if($pp_date == MS_TIME_YMD)
                    break;
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <th scope="row">총합계</th>
                <?php foreach($gw_ptype as $key=>$label) :
                    $tot_cnt_key = "{$key}_count";
                    $tot_price_key="{$key}_price";
                    ?>
                    <td class="bold tar"><?php echo ${$tot_cnt_key}; ?>건</td>
                    <td class="bold tar"><?php echo number_format(${$tot_price_key}); ?>원</td>
                <?php endforeach; ?>
            </tr>
            </tfoot>
        </table>
    </div>

<?php
include_once("./admin_tail.sub.php");
?>