<?php
include_once("./_common.php");

check_demo();

check_admin_token();

if(!count($_POST['chk'])) {
    alert();
}
for($i=0; $i<count($_POST['chk']); $i++)
{
    // 실제 번호를 넘김
    $k = $chk[$i];

	$sql = " update shop_member_grade 
			    set gb_name = '{$_POST['gb_name'][$k]}'
				  , gb_anew_price = '{$_POST['gb_anew_price'][$k]}'
				  , gb_term_price = '{$_POST['gb_term_price'][$k]}'
				  , gb_line_point = '{$_POST['gb_line_point'][$k]}'
				  , gb_line_point_rollup_level = '{$_POST['gb_line_point_rollup_level'][$k]}'
				  , gb_line_depth = '{$_POST['gb_line_depth'][$k]}'
				  , gb_visit_pay = '{$_POST['gb_visit_pay'][$k]}'
				  , gb_promotion = '{$_POST['gb_promotion'][$k]}'
				  , gb_line_point_gold_matched = '{$_POST['gb_line_point_gold_matched'][$k]}'
				  , gb_line_point_gold_matched_rollup_level = '{$_POST['gb_line_point_gold_matched_rollup_level'][$k]}'
				  , gb_pf_hosting = '{$_POST['gb_pf_hosting'][$k]}'
				  , gb_pf_sp_point = '{$_POST['gb_pf_sp_point'][$k]}'
				  , gb_pf_up_sp_point = '{$_POST['gb_pf_up_sp_point'][$k]}'
				  , gb_pf_per_sp_point = '{$_POST['gb_pf_per_sp_point'][$k]}'
				  , gb_pf_point = '{$_POST['gb_pf_point'][$k]}'
				  , gb_pf_up_point = '{$_POST['gb_pf_up_point'][$k]}'
				  , gb_pf_per_point = '{$_POST['gb_pf_per_point'][$k]}'
				  , gb_pf_per_up_pay = '{$_POST['gb_pf_per_up_pay'][$k]}'
				  , gb_pf_per_match_pay = '{$_POST['gb_pf_per_match_pay'][$k]}'
			  where gb_no = '{$_POST['gb_no'][$k]}' ";
	sql_query($sql);
}


for($k=0; $k<count($_POST['biz_no']); $k++)
{
    $sql = " update shop_minishop_type 
			    set biz_anew_price = '{$_POST['biz_anew_price'][$k]}',
			        use_minishop_pay= {$_POST['use_minishop_pay'][$k]},
			        use_share_bonus= {$_POST['use_share_bonus'][$k]},
			        use_point_bonus= {$_POST['use_point_bonus'][$k]}
			  where biz_no = '{$_POST['biz_no'][$k]}' ";
    sql_query($sql);
}

$pf_sale_benefit_2 = is_array($pf_sale_benefit_2)?implode(chr(30), $pf_sale_benefit_2) : '';
$pf_sale_benefit_3 = is_array($pf_sale_benefit_3)?implode(chr(30), $pf_sale_benefit_3) : '';
$pf_sale_benefit_4 = is_array($pf_sale_benefit_4)?implode(chr(30), $pf_sale_benefit_4) : '';
$pf_sale_benefit_5 = is_array($pf_sale_benefit_5)?implode(chr(30), $pf_sale_benefit_5) : '';
$pf_sale_benefit_6 = is_array($pf_sale_benefit_6)?implode(chr(30), $pf_sale_benefit_6) : '';

$pf_anew_benefit_2 = is_array($pf_anew_benefit_2)?implode(chr(30), $pf_anew_benefit_2) : '';
$pf_anew_benefit_3 = is_array($pf_anew_benefit_3)?implode(chr(30), $pf_anew_benefit_3) : '';
$pf_anew_benefit_4 = is_array($pf_anew_benefit_4)?implode(chr(30), $pf_anew_benefit_4) : '';
$pf_anew_benefit_5 = is_array($pf_anew_benefit_5)?implode(chr(30), $pf_anew_benefit_5) : '';
$pf_anew_benefit_6 = is_array($pf_anew_benefit_6)?implode(chr(30), $pf_anew_benefit_6) : '';


$pf_anew_match_pay_2 = is_array($pf_anew_match_pay_2)?implode(chr(30), $pf_anew_match_pay_2) : '';
$pf_anew_match_pay_3 = is_array($pf_anew_match_pay_3)?implode(chr(30), $pf_anew_match_pay_3) : '';
$pf_anew_match_pay_4 = is_array($pf_anew_match_pay_4)?implode(chr(30), $pf_anew_match_pay_4) : '';
$pf_anew_match_pay_5 = is_array($pf_anew_match_pay_5)?implode(chr(30), $pf_anew_match_pay_5) : '';
$pf_anew_match_pay_6 = is_array($pf_anew_match_pay_6)?implode(chr(30), $pf_anew_match_pay_6) : '';

$sql = " update shop_config
			set pf_sale_benefit_dan = '{$pf_sale_benefit_dan}'
			  , pf_sale_benefit_type = '{$pf_sale_benefit_type}'
			  , pf_sale_benefit_2 = '{$pf_sale_benefit_2}'
			  , pf_sale_benefit_3 = '{$pf_sale_benefit_3}'
			  , pf_sale_benefit_4 = '{$pf_sale_benefit_4}'
			  , pf_sale_benefit_5 = '{$pf_sale_benefit_5}'
			  , pf_sale_benefit_6 = '{$pf_sale_benefit_6}'
			  , pf_anew_benefit_dan = '{$pf_anew_benefit_dan}'
			  , pf_anew_benefit_type = '{$pf_anew_benefit_type}'
			  , pf_anew_benefit_2 = '{$pf_anew_benefit_2}'
			  , pf_anew_benefit_3 = '{$pf_anew_benefit_3}'
			  , pf_anew_benefit_4 = '{$pf_anew_benefit_4}'
			  , pf_anew_benefit_5 = '{$pf_anew_benefit_5}'
			  , pf_anew_benefit_6 = '{$pf_anew_benefit_6}' ";

// 매칭수당 설정 업데이트
$sql .= "    
              , pf_anew_match_use = '${pf_anew_match_use}'
              , pf_anew_match_per = '${pf_anew_match_per}'
              , pf_anew_match_pay = '${pf_anew_match_pay}'
              , pf_anew_match_type= '${pf_anew_match_type}'
        ";

$sql .= "
              , pf_anew_match_pay_2 = '${pf_anew_match_pay_2}'
			  , pf_anew_match_pay_3 = '${pf_anew_match_pay_3}'
			  , pf_anew_match_pay_4 = '${pf_anew_match_pay_4}'
			  , pf_anew_match_pay_5 = '${pf_anew_match_pay_5}'
			  , pf_anew_match_pay_6 = '${pf_anew_match_pay_6}' 
        ";

sql_query($sql);

goto_url(MS_ADMIN_URL.'/minishop.php?code=pbasic');
?>