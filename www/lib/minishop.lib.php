<?php
if(!defined('_MALLSET_')) exit;

/* * ***********************************************************************
 * *
 * *  가맹점 관련 함수 모음
 * *
 * *********************************************************************** */

// 가맹점인가?
function is_minishop($mb_id)
{
    if(!$mb_id) return '';

	$mb = get_member($mb_id, 'grade');
	$sql = "select state from shop_minishop where mb_id = '$mb_id'";
	$pt = sql_fetch($sql);

    if(in_array($mb['grade'], array(2,3,4,5,6)) && $pt['state']) {
		return true;
	} else {
		return false;
	}
}

// 가맹점 정보를 리턴
function get_minishop($mb_id, $fileds='*')
{
	return sql_fetch("select $fileds from shop_minishop where mb_id = TRIM('$mb_id')");
}

// 가맹점 정산요청 정보를 리턴
function get_minishop_payrun($index_no, $fileds='*')
{
	return sql_fetch("select $fileds from shop_minishop_payrun where index_no = TRIM('$index_no')");
}
// 가맹점 정산요청 정보를 리턴
function get_minishop_shopping_payrun($index_no, $fileds='*')
{
    return sql_fetch("select $fileds from shop_minishop_shopping_payrun where index_no = TRIM('$index_no')");
}
// 가맹점 관리비연장 정보를 리턴
function get_minishop_term($index_no, $fileds='*')
{
	return sql_fetch("select $fileds from shop_minishop_term where index_no = TRIM('$index_no')");
}

// 가맹점 계좌출력
function print_minishop_bank($mb_id)
{
	$row = get_minishop($mb_id, 'bank_name, bank_account, bank_holder');

	$info = array();
	$info[] = get_text($row['bank_name']); // 은행명
	$info[] = get_text($row['bank_account']); // 계좌번호
	$info[] = get_text($row['bank_holder']); // 예금주명

	if($info[0] && $info[1] && $info[2])
		$bank_str = implode(' ', $info);
	else
		$bank_str = '미등록';

	return $bank_str;
}

function print_minishop_pay_bank($mb_id)
{
    $row = get_minishop($mb_id, 'pay_bank_name, pay_bank_account, pay_bank_holder');

    $info = array();
    $info[] = get_text($row['pay_bank_name']); // 은행명
    $info[] = get_text($row['pay_bank_account']); // 계좌번호
    $info[] = get_text($row['pay_bank_holder']); // 예금주명

    if($info[0] && $info[1] && $info[2])
        $bank_str = implode(' ', $info);
    else
        $bank_str = '미등록';

    return $bank_str;
}

// 가맹점 계좌출력
function print_minishop_bank2($bank_name, $bank_account, $bank_holder)
{
	$info = array();
	$info[] = get_text($bank_name); // 은행명
	$info[] = get_text($bank_account); // 계좌번호
	$info[] = get_text($bank_holder); // 예금주명

	if($info[0] && $info[1] && $info[2])
		$bank_str = implode(' ', $info);
	else
		$bank_str = '미등록';

	return $bank_str;
}

// 가맹점 등급별 설정값
function get_minishop_basic($gb_no, $fields='*')
{
	$sql = " select $fields from shop_member_grade where gb_no = '$gb_no' ";
	return sql_fetch($sql);
}

// 만료일 기간연장
function get_term_date($term='1')
{
	global $config;

	// 관리비를 사용중인가?
	if($config['pf_expire_use']) {
		$term_date = date("Y-m-d", strtotime("+{$term} month", time()));
	} else {
		$term_date = '9999-12-31';
	}

	return $term_date;
}

// 실제 도메인만 추출
function get_basedomain($url)
{
	global $config;

	// 모든 공백을 제거
	$basedomain = preg_replace("/\s+/", "", $config['pf_basedomain']);

	$value = strtolower(trim($url));
	if(preg_match('/^(?:(?:[0-9a-z_]+):\/\/)?((?:[0-9a-z_\d\-]{2,}\.)+[0-9a-z_]{2,})(?::\d{1,5})?(?:\/[^\?]*)?(?:\?.+)?$/i', $value)) {
		preg_match('/([0-9a-z_\d\-]+(?:\.(?:'.$basedomain.')){1,2})(?::\d{1,5})?(?:\/[^\?]*)?(?:\?.+)?$/i', $value, $matches);
		$host = (!$matches[1]) ? $value : $matches[1];
	}

	return $host;
}

// 가맹점 PG결제 정보를 default 변수에 담는다.
function set_minishop_value($mb_id)
{
	global $default;

	if(!is_minishop($mb_id))
		return $default;

	$pt = get_minishop($mb_id);

	$default['de_bank_use'] = $pt['de_bank_use']; // 무통장입금
	$default['de_card_use'] = $pt['de_card_use']; // 신용카드
	$default['de_iche_use'] = $pt['de_iche_use']; // 계좌이체
	$default['de_vbank_use'] = $pt['de_vbank_use']; // 가상계좌
	$default['de_hp_use'] = $pt['de_hp_use']; // 휴대폰
	$default['de_card_test'] = $pt['de_card_test']; // 결제 테스트
	$default['de_pg_service'] = $pt['de_pg_service']; // 결제대행사
	$default['de_tax_flag_use'] = $pt['de_tax_flag_use']; // 복합과세 결제
	$default['de_taxsave_use'] = $pt['de_taxsave_use']; // 현금영수증 발급사용
	$default['de_card_noint_use'] = $pt['de_card_noint_use']; // 신용카드 무이자할부사용
	$default['de_easy_pay_use'] = $pt['de_easy_pay_use']; // PG사 간편결제 버튼사용
	$default['de_escrow_use'] = $pt['de_escrow_use']; // Escrow 사용여부
	$default['de_kcp_mid'] = $pt['de_kcp_mid']; // NHN KCP SITE CODE
	$default['de_kcp_site_key'] = $pt['de_kcp_site_key']; // NHN KCP SITE KEY
	$default['de_lg_mid'] = $pt['de_lg_mid']; // LG유플러스 상점아이디
	$default['de_lg_mert_key'] = $pt['de_lg_mert_key']; // LG유플러스 MertKey
	$default['de_inicis_mid'] = $pt['de_inicis_mid']; // KG이니시스 상점아이디
	$default['de_inicis_admin_key']	= $pt['de_inicis_admin_key']; // KG이니시스 키패스워드
	$default['de_inicis_sign_key'] = $pt['de_inicis_sign_key']; // KG이니시스 웹결제 사인키
	$default['de_samsung_pay_use'] = $pt['de_samsung_pay_use']; // KG이니시스 삼성페이 버튼
	$default['de_bank_account'] = $pt['de_bank_account']; // 무통장입금계좌
	$default['de_kakaopay_mid'] = $pt['de_kakaopay_mid']; // 카카오페이 상점MID
	$default['de_kakaopay_key'] = $pt['de_kakaopay_key']; // 카카오페이 상점키
	$default['de_kakaopay_enckey'] = $pt['de_kakaopay_enckey']; // 카카오페이 EncKey
	$default['de_kakaopay_hashkey'] = $pt['de_kakaopay_hashkey']; // 카카오페이 HashKey
	$default['de_kakaopay_cancelpwd'] = $pt['de_kakaopay_cancelpwd']; // 카카오페이 결제취소 P/W
	$default['de_naverpay_mid'] = $pt['de_naverpay_mid']; // 네이버페이 가맹점 아이디
	$default['de_naverpay_cert_key'] = $pt['de_naverpay_cert_key']; // 네이버페이 가맹점 인증키
	$default['de_naverpay_button_key'] = $pt['de_naverpay_button_key']; // 네이버페이 버튼 인증키
	$default['de_naverpay_test'] = $pt['de_naverpay_test']; // 네이버페이 결제테스트 아이디
	$default['de_naverpay_mb_id'] = $pt['de_naverpay_mb_id']; // 네이버페이 결제테스트 아이디
	$default['de_naverpay_sendcost'] = $pt['de_naverpay_sendcost']; // 네이버페이 추가배송비 안내

	return $default;
}


/* *
 * 가맹점 라인쇼핑포인트 관련 함수 모음
 *
 */
function insert_line_point_rollup($rollup_level,$mb_id, $pay, $content='', $rel_table='', $rel_id='', $rel_action='', $referer='', $agent=''){
    insert_line_point($mb_id, $pay, $content, $rel_table, $rel_id, $rel_action, $referer, $agent);
    for($i = 1 ; $i < $rollup_level; $i++){
        $mb = get_member($mb_id, 'pt_id');
        $mb_id = $mb['pt_id'];
        insert_line_point($mb_id, $pay, $content, $rel_table, $rel_id, $rel_action, $referer, $agent);
    }
}
function get_minishop_type($biz_type){
    return sql_fetch("select * from shop_minishop_type where biz_type = '{$biz_type}'");
}
function insert_line_point_matched_rollup($rollup_level,$mb_id, $pay, $content='', $rel_table='', $rel_id='', $rel_action='', $referer='', $agent=''){
    insert_line_point($mb_id, $pay, $content, $rel_table, $rel_id, $rel_action, $referer, $agent);
    for($i = 1 ; $i < $rollup_level; $i++){
        $mb = get_member($mb_id, 'pt_id');
        $mb_id = $mb['pt_id'];
        if($pt = get_member($mb_id, 'grade')) {
            $pt_grade = Member::get_grade($pt['grade']);
            $pay = $pt_grade['gb_line_point_gold_matched'];
        }
        insert_line_point($mb_id, $pay, $content, $rel_table, $rel_id, $rel_action, $referer, $agent);
    }
}
// 수수료 부여
function insert_line_point($mb_id, $pay, $content='', $rel_table='', $rel_id='', $rel_action='', $referer='', $agent='')
{
    // 수수료가 없거나 승인된 가맹점이 아니라면 업데이트 할 필요 없음
    if($pay == 0 || !is_minishop($mb_id)) { return 0; }

    // 이미 등록된 내역이라면 건너뜀
    if($rel_table || $rel_id || $rel_action)
    {
        $sql = " select count(*) as cnt
				   from shop_minishop_line_point
				  where mb_id = '$mb_id'
					and lp_rel_table = '$rel_table'
					and lp_rel_id = '$rel_id'
					and lp_rel_action = '$rel_action' ";
        $row = sql_fetch($sql);
        if($row['cnt'])
            return -1;
    }

    $pt_line_point = get_line_point_sum($mb_id); // 회원수수료
    $lp_balance = $pt_line_point + $pay; // 잔액

    $sql = " insert into shop_minishop_line_point
				set mb_id = '$mb_id'
				  , lp_datetime = '".MS_TIME_YMDHIS."'
				  , lp_content = '".addslashes($content)."'
				  , lp_point = '$pay'
				  , lp_use_point = '0'
				  , lp_balance = '$lp_balance'
				  , lp_rel_table = '$rel_table'
				  , lp_rel_id = '$rel_id'
				  , lp_rel_action = '$rel_action'
				  , lp_referer = '$referer'
				  , lp_agent = '$agent' ";
    sql_query($sql);

    // 수수료를 사용한 경우 수수료 내역에 사용금액 기록
    if($pay < 0) {
        insert_use_line_point($mb_id, $pay);
    }

    // 수수료 UPDATE
    $sql = " update shop_member set line_point = '$lp_balance' where id = '$mb_id' ";
    sql_query($sql);

    Match::promotion($mb_id);

    // 누적수수료에 따른 자동 레벨업
    check_promotion($mb_id);

    return 1;
}

// 사용수수료 입력
function insert_use_line_point($mb_id, $pay, $lp_id='')
{
    $pay1 = abs($pay);
    $sql = " select lp_id, lp_point, lp_use_point
			   from shop_minishop_line_point
			  where mb_id = '$mb_id'
				and lp_id <> '$lp_id'
				and lp_point > lp_use_point
			  order by lp_id asc ";
    $result = sql_query($sql);
    for($i=0; $row=sql_fetch_array($result); $i++) {
        $pay2 = $row['lp_point'];
        $pay3 = $row['lp_use_point'];

        if(($pay2 - $pay3) > $pay1) {
            $sql = " update shop_minishop_line_point
						set lp_use_point = lp_use_point + '$pay1'
					  where lp_id = '{$row['lp_id']}' ";
            sql_query($sql);
            break;
        } else {
            $pay4 = $pay2 - $pay3;
            $sql = " update shop_minishop_line_point
						set lp_use_point = lp_use_point + '$pay4'
					  where lp_id = '{$row['lp_id']}' ";
            sql_query($sql);
            $pay1 -= $pay4;
        }
    }



}

// 사용수수료 삭제
function delete_use_line_point($mb_id, $pay)
{
    $pay1 = abs($pay);
    $sql = " select lp_id, lp_use_point
			   from shop_minishop_line_point
			  where mb_id = '$mb_id'
				and lp_use_point > 0
			  order by lp_id desc ";
    $result = sql_query($sql);
    for($i=0; $row=sql_fetch_array($result); $i++) {
        $pay2 = $row['lp_use_point'];

        if($pay2 > $pay1) {
            $sql = " update shop_minishop_line_point
						set lp_use_point = lp_use_point - '$pay1'
					  where lp_id = '{$row['lp_id']}' ";
            sql_query($sql);
            break;
        } else {
            $sql = " update shop_minishop_line_point
						set lp_use_point = '0'
					  where lp_id = '{$row['lp_id']}' ";
            sql_query($sql);

            $pay1 -= $pay2;
        }
    }
}

// 수수료 삭제
function delete_line_point($mb_id, $rel_table, $rel_id, $rel_action)
{
    $result = false;
    if($rel_table || $rel_id || $rel_action)
    {
        // 수수료 내역정보
        $sql = " select *
				   from shop_minishop_line_point
				  where mb_id = '$mb_id'
					and lp_rel_table = '$rel_table'
					and lp_rel_id = '$rel_id'
					and lp_rel_action = '$rel_action' ";
        $row = sql_fetch($sql);

        if($row['lp_point'] < 0) {
            $mb_id = $row['mb_id'];
            $lp_point = abs($row['lp_point']);

            delete_use_line_point($mb_id, $lp_point);
        } else {
            if($row['lp_use_point'] > 0) {
                insert_use_line_point($row['mb_id'], $row['lp_use_point'], $row['lp_id']);
            }
        }

        $sql = " delete from shop_minishop_line_point
				  where mb_id = '$mb_id'
					and lp_rel_table = '$rel_table'
					and lp_rel_id = '$rel_id'
					and lp_rel_action = '$rel_action' ";
        $result = sql_query($sql, false);

        // lp_balance에 반영
        $sql = " update shop_minishop_line_point
					set lp_balance = lp_balance - '{$row['lp_point']}'
				  where mb_id = '$mb_id'
					and lp_id > '{$row['lp_id']}' ";
        sql_query($sql);

        // 수수료 내역의 합을 구하고
        $sum_line_point = get_line_point_sum($mb_id);

        // 수수료 UPDATE
        $sql = " update shop_member set line_point = '$sum_line_point' where id = '$mb_id' ";
        $result = sql_query($sql);
    }

    return $result;
}

// 수수료합
function get_line_point_sum($mb_id)
{
    $sql = " select sum(lp_point) as sum_line_point
			   from shop_minishop_line_point
			  where mb_id = '$mb_id' ";
    $row = sql_fetch($sql);

    return (int)$row['sum_line_point'];
}

// 유형별 수수료합
function get_line_point_status($mb_id, $rel_table, $select_add='')
{
    $sql = " select count(*) as cnt,
					sum(lp_point) as pay
			   from shop_minishop_line_point
			  where mb_id = '$mb_id'
				and lp_rel_table = '$rel_table'
				$select_add ";
    $row = sql_fetch($sql);

    $info = array();
    $info['cnt'] = (int)$row['cnt'];
    $info['pay'] = (int)$row['pay'];

    return $info;
}

// 수수료합 (총적립액, 총지급액)
function get_line_point_sheet($mb_id)
{
    $sql_where = " where mb_id = '$mb_id' ";

    $sql1 = " select sum(lp_point) as pay from shop_minishop_line_point {$sql_where} and lp_point > 0 ";
    $row1 = sql_fetch($sql1);

    $sql2 = " select sum(lp_point) as pay from shop_minishop_line_point {$sql_where} and lp_point < 0 ";
    $row2 = sql_fetch($sql2);

    $info = array();
    $info['pay'] = (int)$row1['pay'];
    $info['usepay'] = (int)$row2['pay'];

    return $info;
}

/* *
 * 가맹점 쇼핑페이 관련 함수 모음
 *
 */
// 수수료 부여
function insert_shopping_pay($mb_id, $pay, $content='', $rel_table='', $rel_id='', $rel_action='', $referer='', $agent='')
{
    // 수수료가 없거나 승인된 가맹점이 아니라면 업데이트 할 필요 없음
    if($pay == 0 || !is_minishop($mb_id)) { return 0; }

    // 이미 등록된 내역이라면 건너뜀
    if($rel_table || $rel_id || $rel_action)
    {
        $sql = " select count(*) as cnt
				   from shop_minishop_shopping_pay
				  where mb_id = '$mb_id'
					and sp_rel_table = '$rel_table'
					and sp_rel_id = '$rel_id'
					and sp_rel_action = '$rel_action' ";
        $row = sql_fetch($sql);
        if($row['cnt'])
            return -1;
    }

    $pt_shopping_pay = get_shopping_pay_sum($mb_id); // 회원수수료
    $sp_balance = $pt_shopping_pay + $pay; // 잔액

    $sql = " insert into shop_minishop_shopping_pay
				set mb_id = '$mb_id'
				  , sp_datetime = '".MS_TIME_YMDHIS."'
				  , sp_content = '".addslashes($content)."'
				  , sp_price = '$pay'
				  , sp_use_price = '0'
				  , sp_balance = '$sp_balance'
				  , sp_rel_table = '$rel_table'
				  , sp_rel_id = '$rel_id'
				  , sp_rel_action = '$rel_action'
				  , sp_referer = '$referer'
				  , sp_agent = '$agent' ";
    sql_query($sql);

    // 수수료를 사용한 경우 수수료 내역에 사용금액 기록
    if($pay < 0) {
        insert_use_shopping_pay($mb_id, $pay);
    }

    // 수수료 UPDATE
    //$sql = " update shop_member set sp_point = '$sp_balance' where id = '$mb_id' ";
	$sql = " update shop_member set point = '$sp_balance' where id = '$mb_id' ";
    sql_query($sql);

    // 누적수수료에 따른 자동 레벨업
    check_promotion($mb_id);

    return 1;
}

// 사용수수료 입력
function insert_use_shopping_pay($mb_id, $pay, $sp_id='')
{
    $pay1 = abs($pay);
    $sql = " select sp_id, sp_price, sp_use_price
			   from shop_minishop_shopping_pay
			  where mb_id = '$mb_id'
				and sp_id <> '$sp_id'
				and sp_price > sp_use_price
			  order by sp_id asc ";
    $result = sql_query($sql);
    for($i=0; $row=sql_fetch_array($result); $i++) {
        $pay2 = $row['sp_price'];
        $pay3 = $row['sp_use_price'];

        if(($pay2 - $pay3) > $pay1) {
            $sql = " update shop_minishop_shopping_pay
						set sp_use_price = sp_use_price + '$pay1'
					  where sp_id = '{$row['sp_id']}' ";
            sql_query($sql);
            break;
        } else {
            $pay4 = $pay2 - $pay3;
            $sql = " update shop_minishop_shopping_pay
						set sp_use_price = sp_use_price + '$pay4'
					  where sp_id = '{$row['sp_id']}' ";
            sql_query($sql);
            $pay1 -= $pay4;
        }
    }
}

// 사용수수료 삭제
function delete_use_shopping_pay($mb_id, $pay)
{
    $pay1 = abs($pay);
    $sql = " select sp_id, sp_use_price
			   from shop_minishop_shopping_pay
			  where mb_id = '$mb_id'
				and sp_use_price > 0
			  order by sp_id desc ";
    $result = sql_query($sql);
    for($i=0; $row=sql_fetch_array($result); $i++) {
        $pay2 = $row['sp_use_price'];

        if($pay2 > $pay1) {
            $sql = " update shop_minishop_shopping_pay
						set sp_use_price = sp_use_price - '$pay1'
					  where sp_id = '{$row['sp_id']}' ";
            sql_query($sql);
            break;
        } else {
            $sql = " update shop_minishop_shopping_pay
						set sp_use_price = '0'
					  where sp_id = '{$row['sp_id']}' ";
            sql_query($sql);

            $pay1 -= $pay2;
        }
    }
}

// 수수료 삭제
function delete_shopping_pay($mb_id, $rel_table, $rel_id, $rel_action)
{
    $result = false;
    if($rel_table || $rel_id || $rel_action)
    {
        // 수수료 내역정보
        $sql = " select *
				   from shop_minishop_shopping_pay
				  where mb_id = '$mb_id'
					and sp_rel_table = '$rel_table'
					and sp_rel_id = '$rel_id'
					and sp_rel_action = '$rel_action' ";
        $row = sql_fetch($sql);

        if($row['sp_price'] < 0) {
            $mb_id = $row['mb_id'];
            $sp_price = abs($row['sp_price']);

            delete_use_shopping_pay($mb_id, $sp_price);
        } else {
            if($row['sp_use_price'] > 0) {
                insert_use_shopping_pay($row['mb_id'], $row['sp_use_price'], $row['sp_id']);
            }
        }

        $sql = " delete from shop_minishop_shopping_pay
				  where mb_id = '$mb_id'
					and sp_rel_table = '$rel_table'
					and sp_rel_id = '$rel_id'
					and sp_rel_action = '$rel_action' ";
        $result = sql_query($sql, false);

        // sp_balance에 반영
        $sql = " update shop_minishop_shopping_pay
					set sp_balance = sp_balance - '{$row['sp_price']}'
				  where mb_id = '$mb_id'
					and sp_id > '{$row['sp_id']}' ";
        sql_query($sql);

        // 수수료 내역의 합을 구하고
        $sum_shopping_pay = get_shopping_pay_sum($mb_id);

        // 수수료 UPDATE
        //$sql = " update shop_member set sp_point = '$sum_shopping_pay' where id = '$mb_id' ";
		$sql = " update shop_member set point = '$sum_shopping_pay' where id = '$mb_id' ";
        $result = sql_query($sql);
    }

    return $result;
}

// 수수료합
function get_shopping_pay_sum($mb_id)
{
    $sql = " select sum(sp_price) as sum_shopping_pay
			   from shop_minishop_shopping_pay
			  where mb_id = '$mb_id' ";
    $row = sql_fetch($sql);

    return (int)$row['sum_shopping_pay'];
}

// 유형별 수수료합
function get_shopping_pay_status($mb_id, $rel_table, $select_add='')
{
    $sql = " select count(*) as cnt,
					sum(sp_price) as pay
			   from shop_minishop_shopping_pay
			  where mb_id = '$mb_id'
				and sp_rel_table = '$rel_table'
				$select_add ";
    $row = sql_fetch($sql);

    $info = array();
    $info['cnt'] = (int)$row['cnt'];
    $info['pay'] = (int)$row['pay'];

    return $info;
}

// 수수료합 (총적립액, 총지급액)
function get_shopping_pay_sheet($mb_id)
{
    $sql_where = " where mb_id = '$mb_id' ";

    $sql1 = " select sum(sp_price) as pay from shop_minishop_shopping_pay {$sql_where} and sp_price > 0 ";
    $row1 = sql_fetch($sql1);

    $sql2 = " select sum(sp_price) as pay from shop_minishop_shopping_pay {$sql_where} and sp_price < 0 ";
    $row2 = sql_fetch($sql2);

    $info = array();
    $info['pay'] = (int)$row1['pay'];
    $info['usepay'] = (int)$row2['pay'];

    return $info;
}

/* * ***********************************************************************
 * *
 * *  가맹점 수수료관련 함수 모음
 * *
 * *********************************************************************** */

// 수수료 부여
function insert_pay($mb_id, $pay, $content='', $rel_table='', $rel_id='', $rel_action='', $referer='', $agent='', $delay_due_date=0)
{
	// 수수료가 없거나 승인된 가맹점이 아니라면 업데이트 할 필요 없음
	if($pay == 0 || !is_minishop($mb_id)) { return 0; }

	// 이미 등록된 내역이라면 건너뜀
	if($rel_table || $rel_id || $rel_action)
	{
		$sql = " select count(*) as cnt
				   from shop_minishop_pay
				  where mb_id = '$mb_id'
					and pp_rel_table = '$rel_table'
					and pp_rel_id = '$rel_id'
					and pp_rel_action = '$rel_action' ";
		$row = sql_fetch($sql);
		if($row['cnt'])
			return -1;
	}

	$pt_pay = get_pay_sum($mb_id); // 회원수수료
	$pp_balance = $pt_pay + $pay; // 잔액

	$sql = " insert into shop_minishop_pay
				set mb_id = '$mb_id'
				  , pp_datetime = '".MS_TIME_YMDHIS."'
				  , pp_content = '".addslashes($content)."'
				  , pp_pay = '$pay'
				  , pp_use_pay = '0'
				  , pp_balance = '$pp_balance'
				  , pp_rel_table = '$rel_table'
				  , pp_rel_id = '$rel_id'
				  , pp_rel_action = '$rel_action'
				  , pp_referer = '$referer'
				  , pp_agent = '$agent'
				  , pp_due_date = date_add('".MS_TIME_YMD." 00:00:00', INTERVAL {$delay_due_date} DAY)";
	sql_query($sql);

	// 수수료를 사용한 경우 수수료 내역에 사용금액 기록
	if($pay < 0) {
		insert_use_pay($mb_id, $pay);
	}

	// 수수료 UPDATE
	$sql = " update shop_member set pay = '$pp_balance' where id = '$mb_id' ";
	sql_query($sql);

	// 누적수수료에 따른 자동 레벨업
	check_promotion($mb_id);

	return 1;
}

// 사용수수료 입력
function insert_use_pay($mb_id, $pay, $pp_id='')
{
	$pay1 = abs($pay);
	$sql = " select pp_id, pp_pay, pp_use_pay
			   from shop_minishop_pay
			  where mb_id = '$mb_id'
				and pp_id <> '$pp_id'
				and pp_pay > pp_use_pay
			  order by pp_id asc ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$pay2 = $row['pp_pay'];
		$pay3 = $row['pp_use_pay'];

		if(($pay2 - $pay3) > $pay1) {
			$sql = " update shop_minishop_pay
						set pp_use_pay = pp_use_pay + '$pay1'
					  where pp_id = '{$row['pp_id']}' ";
			sql_query($sql);
			break;
		} else {
			$pay4 = $pay2 - $pay3;
			$sql = " update shop_minishop_pay
						set pp_use_pay = pp_use_pay + '$pay4'
					  where pp_id = '{$row['pp_id']}' ";
			sql_query($sql);
			$pay1 -= $pay4;
		}
	}
}

// 사용수수료 삭제
function delete_use_pay($mb_id, $pay)
{
	$pay1 = abs($pay);
	$sql = " select pp_id, pp_use_pay
			   from shop_minishop_pay
			  where mb_id = '$mb_id'
				and pp_use_pay > 0
			  order by pp_id desc ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		$pay2 = $row['pp_use_pay'];

		if($pay2 > $pay1) {
			$sql = " update shop_minishop_pay
						set pp_use_pay = pp_use_pay - '$pay1'
					  where pp_id = '{$row['pp_id']}' ";
			sql_query($sql);
			break;
		} else {
			$sql = " update shop_minishop_pay
						set pp_use_pay = '0'
					  where pp_id = '{$row['pp_id']}' ";
			sql_query($sql);

			$pay1 -= $pay2;
		}
	}
}

// 수수료 삭제
function delete_pay($mb_id, $rel_table, $rel_id, $rel_action)
{
	$result = false;
	if($rel_table || $rel_id || $rel_action)
	{
		// 수수료 내역정보
		$sql = " select *
				   from shop_minishop_pay
				  where mb_id = '$mb_id'
					and pp_rel_table = '$rel_table'
					and pp_rel_id = '$rel_id'
					and pp_rel_action = '$rel_action' ";
		$row = sql_fetch($sql);

		if($row['pp_pay'] < 0) {
			$mb_id = $row['mb_id'];
			$pp_pay = abs($row['pp_pay']);

			delete_use_pay($mb_id, $pp_pay);
		} else {
			if($row['pp_use_pay'] > 0) {
				insert_use_pay($row['mb_id'], $row['pp_use_pay'], $row['pp_id']);
			}
		}

		$sql = " delete from shop_minishop_pay
				  where mb_id = '$mb_id'
					and pp_rel_table = '$rel_table'
					and pp_rel_id = '$rel_id'
					and pp_rel_action = '$rel_action' ";
		$result = sql_query($sql, false);

		// pp_balance에 반영
		$sql = " update shop_minishop_pay
					set pp_balance = pp_balance - '{$row['pp_pay']}'
				  where mb_id = '$mb_id'
					and pp_id > '{$row['pp_id']}' ";
		sql_query($sql);

		// 수수료 내역의 합을 구하고
		$sum_pay = get_pay_sum($mb_id);

		// 수수료 UPDATE
		$sql = " update shop_member set pay = '$sum_pay' where id = '$mb_id' ";
		$result = sql_query($sql);
	}

	return $result;
}

// 수수료합
function get_pay_sum($mb_id)
{
	$sql = " select sum(pp_pay) as sum_pay
			   from shop_minishop_pay
			  where mb_id = '$mb_id' ";
	$row = sql_fetch($sql);

	return (int)$row['sum_pay'];
}

// 유형별 수수료합
function get_pay_status($mb_id, $rel_table, $select_add='')
{
	$sql = " select count(*) as cnt,
					sum(pp_pay) as pay
			   from shop_minishop_pay
			  where mb_id = '$mb_id'
			    and pp_due_date < now()
				and pp_rel_table = '$rel_table'
				$select_add ";
	$row = sql_fetch($sql);

	$info = array();
	$info['cnt'] = (int)$row['cnt'];
	$info['pay'] = (int)$row['pay'];

	return $info;
}

// 수수료합 (총적립액, 총지급액)
function get_pay_sheet($mb_id)
{
	$sql_where = " where mb_id = '$mb_id' ";

	$sql1 = " select sum(pp_pay) as pay from shop_minishop_pay {$sql_where} and pp_pay > 0 ";
	$row1 = sql_fetch($sql1);

	$sql2 = " select sum(pp_pay) as pay from shop_minishop_pay {$sql_where} and pp_pay < 0 ";
	$row2 = sql_fetch($sql2);

	$info = array();
	$info['pay'] = (int)$row1['pay'];
	$info['usepay'] = (int)$row2['pay'];

	return $info;
}

// 누적수수료에 따른 자동 레벨업
function check_promotion($mb_id)
{
	if(!is_minishop($mb_id))
		return;

	// 수수료 총적립액
	$info = get_pay_sheet($mb_id);
	$sum_pay = $info['pay'];
	if($sum_pay <= 0)
		return;

	$mb = get_member($mb_id, 'grade');

	// 최상위 레벨이면 리턴
	if($mb['grade'] == 2)
		return;

	$sql = " select gb_no, gb_promotion
			   from shop_member_grade
			  where gb_no between '2' and '6'
			  order by gb_no asc ";
	$result = sql_query($sql);
	for($i=0; $row=sql_fetch_array($result); $i++) {
		if($mb['grade'] == $row['gb_no'])
			break;

		if(!$row['gb_promotion'])
			continue;

		if($sum_pay >= $row['gb_promotion']) {
			$sql = " update shop_member set grade = '{$row['gb_no']}' where id = '$mb_id' ";
			sql_query($sql);
			break;
		}
	}
}

// 후원수수료 지급
function insert_anew_pay($mb_id)
{
	global $config;

	// 새로운 가맹점 등록시

    // 신청자의 추천인을 담고
    $mb = get_member($mb_id, 'pt_id, up_id');

    // minishop hierarchy
    minishop::insertHierarchy($mb_id, $mb['pt_id']);
    // pt hierarchy
    minishop::insertHierarchyPt($mb_id, $mb['pt_id']);
    // up hierarchy
    minishop::insertHierarchyUp($mb_id, $mb['up_id']);

	// 후원수수료를 사용을 하지 않는다면 리턴
	if(!$config['pf_anew_use']) return;
	/** % 로 고정 */
    $config['pf_anew_benefit_type'] = 0;
    /** 후원 단계를 1로 고정 */
    $config['pf_anew_benefit_dan'] = 1;
	if(!$config['pf_anew_benefit_dan']) return;
	// 신청자가 가맹점이 아니면 리턴
	if(!is_minishop($mb_id)) return;
	// 신청자 정보
	$pt = get_minishop($mb_id, 'mb_id, anew_grade, receipt_price');

    /** @var  $per_up_pay % */
    $grade      = Member::get_grade($pt['anew_grade']);
    $per_up_pay = $grade['gb_pf_per_up_pay'];
    $line_point = $grade['gb_line_point'];
    $sp_point   = $grade['gb_pf_sp_point'];
    $sp_up_point= $grade['gb_pf_up_sp_point'];
    $gb_pf_point= $grade['gb_pf_point'];
    $gb_pf_up_point= $grade['gb_pf_up_point'];

    // 후원수수료를 적용할 단계가 없다면 리턴
	// 가맹점개설비가 없다면 리턴
    // 없어도 쇼핑 페이 지급을 위해서
	$reg_price = (int)$pt['receipt_price'];
	// if($reg_price == 0) return;
    $reg_price_without_tax = $reg_price / 11 * 10;

	// 신청자의 추천인
	$cur_pt_id = $mb['pt_id'];

	if( $gb_pf_point > 0 )
        insert_point($mb_id, $gb_pf_point, '가맹가입 지급 쇼핑포인트', 'member', $mb_id,'가입쇼핑포인트');
	if( defined('USE_SHOPPING_PAY') && USE_SHOPPING_PAY )
        insert_shopping_pay($mb_id, $sp_point, "가맹가입 지급페이", "member", $mb_id, "가입쇼핑페이", $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT']);

	// 추천인이 가맹점이 아니면 리턴
	if(!is_minishop($cur_pt_id)) return;

	// 추천인은 본인이 될 수 없음
	if($mb_id == $cur_pt_id) return;
	// 신청레벨에 따른 인센티브를 배열로 담는다

    // 다단 적용 하지 않음
	// $anew_benefit = explode(chr(30), $config['pf_anew_benefit_'.$pt['anew_grade']]);

	// 상위 추천인 찾아가기
    $pt_id     = $cur_pt_id;
    $up_id     = $mb['up_id'];

    if(empty($up_id)) $up_id = $pt_id;

    insert_line_point_rollup($grade['gb_line_point_rollup_level'], $pt_id, $line_point,$mb_id.'님 가맹점가입 축하', 'anew', $mb_id, '추천점수', $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT']);

	for($i=0; $i<$config['pf_anew_benefit_dan']; $i++)
	{
		// 추천인이 없거나 최고관리자라면 중지
		if(!$pt_id || $pt_id == encrypted_admin())
			break;

		// 적용할 인센티브가 없다면 건너뜀
		$benefit = $per_up_pay;
		if($benefit <= 0) continue;

		if($config['pf_anew_benefit_type'])
			$pt_pay = $benefit; // 설정금액(원)
		else
			$pt_pay = floor($reg_price_without_tax * $benefit / 100); // 설정비율(%)

        // up_id 에게 후원 수수료
        // 추천수수료 받을 수 있는 사용자 인가?
        $chk_ptype= get_minishop($up_id);
        $ptype = get_minishop_type($chk_ptype['from_biz_type']);

        if( ! ( $ptype && $ptype['use_minishop_pay'] == 0 )) {
            insert_pay($up_id, $pt_pay, $mb_id . '님 가맹점가입 축하', 'anew', $mb_id, '추천수수료', $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT'], 7);
        }
        if( $gb_pf_up_point > 0 )
            insert_point($up_id, $gb_pf_up_point, $mb_id.'님 가맹점가입 추천쇼핑포인트', 'anew', $mb_id,'추천쇼핑포인트');
        if( defined('USE_SHOPPING_PAY') && USE_SHOPPING_PAY )
            insert_shopping_pay($up_id, $sp_up_point, $mb_id."님 가맹점가입 추천쇼핑페이", "anew", $mb_id, "추천쇼핑페이", $_SERVER['HTTP_REFERER'], $_SERVER['HTTP_USER_AGENT']);

		// 단계별 상위 추천인을 담고 다시 배열로 돌린다
		$mb = get_member($pt_id, 'pt_id, up_id');
		$pt_id = $mb['pt_id'];
		$up_id = $mb['up_id'];
		if( empty($up_id) ) $up_id = $pt_id;
	}

	// 쇼핑 pay

	// 매칭수당 지급
    Match::matchUp($cur_pt_id, $mb_id, $reg_price, $pt['anew_grade']);
}

/**
 * @param $mb_id
 *
 * 다단 추천
 */
function insert_anew_pay_multiple_dan($mb_id)
{
    global $config;
    // 후원수수료를 사용을 하지 않는다면 리턴
    if(!$config['pf_anew_use']) return;
    // 후원수수료를 적용할 단계가 없다면 리턴
    if(!$config['pf_anew_benefit_dan']) return;
    // 신청자가 가맹점이 아니면 리턴
    if(!is_minishop($mb_id)) return;
    // 신청자 정보
    $pt = get_minishop($mb_id, 'mb_id, anew_grade, receipt_price');

    // 가맹점개설비가 없다면 리턴
    $reg_price = (int)$pt['receipt_price'];
    if($reg_price == 0) return;
    // 신청자의 추천인을 담고
    $mb = get_member($mb_id, 'pt_id, up_id');
    // 신청자의 추천인
    $cur_pt_id = $mb['pt_id'];

    // 추천인이 가맹점이 아니면 리턴
    if(!is_minishop($cur_pt_id)) return;
    // 추천인은 본인이 될 수 없음
    if($mb_id == $cur_pt_id) return;
    // 신청레벨에 따른 인센티브를 배열로 담는다
    $anew_benefit = explode(chr(30), $config['pf_anew_benefit_'.$pt['anew_grade']]);

    // 상위 추천인 찾아가기
    $pt_id     = $cur_pt_id;
    $up_id     = $mb['up_id'];

    if(empty($up_id)) $up_id = $pt_id;

    for($i=0; $i<$config['pf_anew_benefit_dan']; $i++)
    {
        // 추천인이 없거나 최고관리자라면 중지
        if(!$pt_id || $pt_id == encrypted_admin())
            break;

        // 적용할 인센티브가 없다면 건너뜀
        $benefit = (float)trim($anew_benefit[$i]);
        if($benefit <= 0) continue;

        if($config['pf_anew_benefit_type'])
            $pt_pay = $benefit; // 설정금액(원)
        else
            $pt_pay = floor($reg_price * $benefit / 100); // 설정비율(%)

        // up_id 에게 후원 수수료
        insert_pay($up_id, $pt_pay, $mb_id.'님 가맹점가입 축하', 'anew', $mb_id, '후원수수료');

        // 단계별 상위 추천인을 담고 다시 배열로 돌린다
        $mb = get_member($pt_id, 'pt_id, up_id');
        $pt_id = $mb['pt_id'];
        $up_id = $mb['up_id'];
        if( empty($up_id) ) $up_id = $pt_id;
    }

    // 쇼핑 pay

    // 매칭수당 지급
    Match::matchUp($cur_pt_id, $mb_id, $reg_price, $pt['anew_grade']);
}
// 접속수수료 지급
function insert_visit_pay($mb_id, $remote_addr, $referer, $user_agent)
{
	global $config;

	// 접속수수료를 사용을 하지 않는다면 리턴
	if(!$config['pf_visit_use']) return;

	// 가맹점이 아니면 리턴
	if(!is_minishop($mb_id)) return;

	// 가맹점 정보
	$mb = get_member($mb_id, 'grade');

	// 레벨에 따른 접속수수료 설정값
	$pb = get_minishop_basic($mb['grade']);

	// 접속수수료가 없다면 리턴
	$pay = (int)$pb['gb_visit_pay'];

	if($pay == 0) return;

	$ip = preg_replace("/([0-9]+).([0-9]+).([0-9]+).([0-9]+)/", MS_IP_DISPLAY, $remote_addr);

	insert_pay($mb_id, $pay, "접속자 ({$ip})", "visit", $remote_addr, MS_TIME_YMD, $referer, $user_agent);
}

// 판매수수료 지급
function insert_sale_pay($pt_id, $od, $gs)
{
	// 후원(롤업) 수당은 up_id 에게 지급 합니다.
    Order::insertSalePay($od, $gs);
    return;

    global $config;
	// 판매수수료를 사용을 하지 않는다면 리턴
	if(!$config['pf_sale_use']) return;

	// 가맹점상품이면 리턴
	if($gs['use_aff']) return;

	// 가맹점이 아니면 리턴
	if(!is_minishop($pt_id)) return;

	// 가맹점 정보
	$mb = get_member($pt_id, 'grade');

	$amount = 0;

	// 원가 계산
	if($config['pf_sale_flag']) {
		if($od['supply_price'] > 0) // 공급가
			$amount = $od['goods_price'] - $od['supply_price'];

		if($config['pf_sale_flag'] == 1)
			$amount = $amount - ($od['coupon_price'] + $od['use_point']); // 할인쿠폰 + 쇼핑포인트결제
	} else {
		$amount = $od['use_price'] - $od['baesong_price']; // 순수결제액 - 배송비
	}

	// 적용할 금액이 없다면 리턴
	if($amount < 1) return;

	if($gs['ppay_type']) { // 개별설정
		$sale_benefit_dan  = $gs['ppay_dan'];
		$sale_benefit_type = $gs['ppay_rate'];
		$sale_benefit	   = explode(chr(30), $gs['ppay_fee']);
	} else { // 공통설정
		$sale_benefit_dan  = $config['pf_sale_benefit_dan'];
		$sale_benefit_type = $config['pf_sale_benefit_type'];
		$sale_benefit	   = explode(chr(30), $config['pf_sale_benefit_'.$mb['grade']]);
	}

	// 판매수수료를 적용할 단계가 없다면 리턴
	if($sale_benefit_dan < 1) return;

	for($i=0; $i<$sale_benefit_dan; $i++)
	{
		// 추천인이 없거나 최고관리자라면 중지
		if(!$pt_id || $pt_id == encrypted_admin())
			break;

		// 적용할 인센티브가 없다면 건너뜀
		$benefit = (float)trim($sale_benefit[$i]);
		if($benefit <= 0) continue;

		if($sale_benefit_type)
			$pt_pay = (int)($benefit * $od['sum_qty']); // 설정금액(원)
		else
			$pt_pay = (int)($amount * $benefit / 100); // 설정비율(%)

		// 추천인 정보
		$mb = get_member($pt_id, 'pt_id, payment, payflag');

		// 개별 추가 판매수수료
		if($mb['payment']) {
			if($mb['payflag'])
				$pt_pay += (int)($mb['payment'] * $od['sum_qty']); // 설정금액(원)
			else
				$pt_pay += (int)($amount * $mb['payment'] / 100); // 설정비율(%)
		}

		// 적용할 수수료가 없다면 건너뜀
		if($pt_pay <= 0) continue;

		insert_pay($pt_id, $pt_pay, "주문번호 {$od['od_id']} ({$od['od_no']}) 배송완료", 'sale', $od['od_no'], $od['od_id']);

		// 상위 추천인을 담고 다시 배열로 돌린다
		$pt_id = $mb['pt_id'];

	} // for

}

// 판매수수료 예상가
function get_payment($gs_id)
{
	global $config, $member;

	// 판매수수료 노출여부 사용중이아니면 리턴
	if(!$config['pf_payment_yes']) return 0;

	// 가맹점이 아니면 리턴
	if(!is_minishop($member['id'])) return 0;

	$gs = get_goods($gs_id);

	// 가맹점상품이면 리턴
	if($gs['use_aff']) return 0;

	// 원가 계산
	if($config['pf_sale_flag'])
		$amount = $gs['goods_price'] - $gs['supply_price']; // 판매가 - 공급가
	else
		$amount = $gs['goods_price']; // 판매가

	// 적용할 금액이 없다면 리턴
	if($amount < 1) return 0;

	if($gs['ppay_type']) { // 개별설정
		$sale_benefit_type = $gs['ppay_rate'];
		$sale_benefit	   = explode(chr(30), $gs['ppay_fee']);
	} else { // 공통설정
		$sale_benefit_type = $config['pf_sale_benefit_type'];
		$sale_benefit      = explode(chr(30), $config['pf_sale_benefit_'.$member['grade']]);
	}

	$benefit = (float)trim($sale_benefit[0]);

	if($sale_benefit_type)
		$pt_pay = $benefit; // 설정금액(원)
	else
		$pt_pay = (int)($amount * $benefit / 100); // 설정비율(%)

	// 개별 추가 판매수수료
	if($member['payment']) {
		if($member['payflag'])
			$pt_pay += $member['payment']; // 설정금액(원)
		else
			$pt_pay += (int)($amount * $member['payment'] / 100); // 설정비율(%)
	}

	if($pt_pay < 0)
		$pt_pay = 0;

	return $pt_pay;
}
