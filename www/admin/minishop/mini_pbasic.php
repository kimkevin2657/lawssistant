<?php
if(!defined('_MALLSET_')) exit;

$frm_submit = '<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>';
?>

<form name="fbasicform" class="w964" method="post" action="./minishop/mini_pbasicupdate.php">
<input type="hidden" name="token" value="">

<h2>등급별 기본설정</h2>
<div class="tbl_head01">
	<table>
	<colgroup>
		<col class="w50">
		<col>
		<col class="w110">
        <col class="w110">
        <?php if( defined('USE_LINE_UP') && USE_LINE_UP ) : ?>
        <col class="w110">
        <?php endif; ?>
        <?php if( defined('USE_SHOPPING_PAY') && USE_SHOPPING_PAY ) : ?>
		<col class="w110">
        <col class="w110">
        <?php endif; ?>
        <col class="w110">
        <col class="w110">
        <col class="w110">
        <col class="w110">
		<col class="w150">
	</colgroup>
	<thead>
	<tr>
		<th scope="col" rowspan="2">레벨</th>
		<th scope="col" rowspan="2">레벨명</th>
		<th scope="col">가맹점개설비</th>
        <th scope="col">추천점수(롤업)</th>
        <?php if( defined('USE_LINE_UP') && USE_LINE_UP ) : ?>
        <th scope="col" rowspan="2">라인점수</th>
        <?php endif; ?>
        <?php if( defined('USE_SHOPPING_PAY') && USE_SHOPPING_PAY ) : ?>
        <th scope="col">지급쇼핑페이</th>
        <th scope="col" rowspan="2">쇼핑페이적립%</th>
        <?php endif; ?>
        <th scope="col">지급쇼핑포인트</th>
        <th scope="col">쇼핑포인트적립%</th>
        <th scope="col">영업수수료%</th>
		<th scope="col" rowspan="2">접속수수료(CPC)</th>
		<th scope="col" rowspan="2">자동레벨업(누적수익)</th>
	</tr>
    <tr>
        <th scope="col">관리비</th>
        <th scope="col">하위골드매칭시(롤업)</th>
        <?php if( defined('USE_SHOPPING_PAY') && USE_SHOPPING_PAY ) : ?>
        <th scope="col">추천쇼핑페이</th>
        <?php endif; ?>
        <th scope="col">추천쇼핑포인트</th>
        <th scope="col">무료호스팅개월</th>
        <th scope="col">관리수수료%</th>
    </tr>
	</thead>
	<tbody class="list">
	<?php
	$sql = " select * from shop_member_grade where gb_no between 2 and 6 order by gb_no desc ";
	$res = sql_query($sql);
	for($i=0; $row=sql_fetch_array($res); $i++) {
		$bg = 'bg'.($i%2);
	?>
	<tr class="<?php echo $bg; ?>">
		<td rowspan="2">
			<strong><?php echo $row['gb_no']; ?>레벨</strong>
			<input type="hidden" name="gb_no[<?php echo $i; ?>]" value="<?php echo $row['gb_no']; ?>">
			<input type="hidden" name="chk[]" value="<?php echo $i; ?>">
		</td>
		<td rowspan="2">
			<label for="gb_name<?php echo $i; ?>" class="sound_only">레벨명</label>
			<input type="text" name="gb_name[<?php echo $i; ?>]" readonly value="<?php echo $row['gb_name']; ?>" id="gb_name<?php echo $i; ?>" class="frm_input pb-name-cell">
		</td>
		<td>
			<label for="gb_anew_price<?php echo $i; ?>" class="sound_only">개설비</label>
			<input type="text" name="gb_anew_price[<?php echo $i; ?>]" value="<?php echo $row['gb_anew_price']; ?>" id="gb_anew_price<?php echo $i; ?>" class="frm_input w70 tar"> 원
		</td>

        <td>
            <label for="gb_line_point<?php echo $i; ?>" class="sound_only">추천점수</label>
            <input type="text" name="gb_line_point[<?php echo $i; ?>]" value="<?php echo $row['gb_line_point']; ?>" id="gb_line_point<?php echo $i; ?>" class="frm_input w20 tar"> 점(
            <input type="number" min="1" max="20" name="gb_line_point_rollup_level[<?php echo $i; ?>]" value="<?php echo $row['gb_line_point_rollup_level']?>" id="gb_line_point_rollup_level<?php echo $i; ?>" class="frm_input w40 tar">
            UP)
        </td>
        <?php if( defined('USE_LINE_UP') && USE_LINE_UP ) : ?>
        <td rowspan="2">
            <label for="gb_line_depth<?php echo $i; ?>" class="sound_only">라인점수</label>
            <?php echo Match::selectBoxLineDepth('gb_line_depth['.$i.']', $row['gb_line_depth'], array('class'=>'frm_input w40')); ?>UP 매칭시
        </td>
        <?php endif; ?>
        <?php if( defined('USE_SHOPPING_PAY') && USE_SHOPPING_PAY ) : ?>
        <td>
            <label for="gb_pf_sp_point<?php echo $i; ?>" class="sound_only">지급쇼핑페이</label>
            <input type="text" name="gb_pf_sp_point[<?php echo $i; ?>]" value="<?php echo $row['gb_pf_sp_point']; ?>" id="gb_pf_sp_point<?php echo $i; ?>" class="frm_input w70 tar"> P
        </td>
        <td rowspan="2">
			<label for="gb_pf_per_sp_point<?php echo $i; ?>" class="sound_only">쇼핑페이적립%</label>
            <input type="text" name="gb_pf_per_sp_point[<?php echo $i; ?>]" value="<?php echo $row['gb_pf_per_sp_point']; ?>" id="gb_pf_per_sp_point<?php echo $i; ?>" class="frm_input w40 tar">
            <span class="w40" style="display: inline-block">%</span>
		</td>
        <?php endif; ?>
        <td>
            <label for="gb_pf_point<?php echo $i; ?>" class="sound_only">지급쇼핑포인트</label>
            <input type="text" name="gb_pf_point[<?php echo $i; ?>]" value="<?php echo $row['gb_pf_point']; ?>" id="gb_pf_point<?php echo $i; ?>" class="frm_input w70 tar"> P
        </td>
        <td>
            <label for="gb_pf_per_point<?php echo $i; ?>" class="sound_only">쇼핑포인트적립%</label>
            <input type="text" name="gb_pf_per_point[<?php echo $i; ?>]" value="<?php echo $row['gb_pf_per_point']; ?>" id="gb_pf_per_point<?php echo $i; ?>" class="frm_input w40 tar">
            <span class="w40" style="display: inline-block">%</span>
        </td>
        <td>
            <label for="gb_pf_per_up_pay<?php echo $i; ?>" class="sound_only">추천수수료</label>
            <input type="text" name="gb_pf_per_up_pay[<?php echo $i; ?>]" value="<?php echo $row['gb_pf_per_up_pay']; ?>" id="gb_pf_per_up_pay<?php echo $i; ?>" class="frm_input w40 tar">
            <span class="w40" style="display: inline-block">%</span>
        </td>
        <td rowspan="2">
            <label for="gb_visit_pay<?php echo $i; ?>" class="sound_only">접속수수료</label>
            <input type="text" name="gb_visit_pay[<?php echo $i; ?>]" value="<?php echo $row['gb_visit_pay']; ?>" id="gb_visit_pay<?php echo $i; ?>" class="frm_input w70 tar"> 원
        </td>
		<td rowspan="2">
			<?php if($i==0) { ?>
			<span class="txt_false">최초 시작등급</span>
            <input type="hidden" name="gb_promotion[<?php echo $i; ?>]" value="<?php echo $row['gb_promotion']; ?>" id="gb_promotion<?php echo $i; ?>" class="frm_input w70 tar">
			<?php } else { ?>
			<label for="gb_promotion<?php echo $i; ?>" class="sound_only">누적수익</label>
			<input type="text" name="gb_promotion[<?php echo $i; ?>]" value="<?php echo $row['gb_promotion']; ?>" id="gb_promotion<?php echo $i; ?>" class="frm_input w70 tar"> 원 달성시
			<?php } ?>
		</td>
	</tr>
    <tr class="<?php echo $bg; ?>">
        <td>
            <label for="gb_term_price<?php echo $i; ?>" class="sound_only">관리비</label>
            <input type="text" name="gb_term_price[<?php echo $i; ?>]" value="<?php echo $row['gb_term_price']; ?>" id="gb_term_price<?php echo $i; ?>" class="frm_input w70 tar"> 원
        </td>
        <td>
            <label for="gb_line_point_gold_matched<?php echo $i; ?>" class="sound_only">하위골드매칭시</label>
            <input type="text" name="gb_line_point_gold_matched[<?php echo $i; ?>]" value="<?php echo $row['gb_line_point_gold_matched']; ?>" id="gb_line_point_gold_matched<?php echo $i; ?>" class="frm_input w20 tar"> 점(
            <input type="number" min="1" max="20" name="gb_line_point_gold_matched_rollup_level[<?php echo $i; ?>]" value="<?php echo $row['gb_line_point_gold_matched_rollup_level']?>" id="gb_line_point_gold_matched_rollup_level<?php echo $i; ?>" class="frm_input w40 tar">
            UP)
        </td>
        <?php if( defined('USE_SHOPPING_PAY') && USE_SHOPPING_PAY ) : ?>
        <td>
            <label for="gb_pf_up_sp_point<?php echo $i; ?>" class="sound_only">추천쇼핑페이</label>
            <input type="text" name="gb_pf_up_sp_point[<?php echo $i; ?>]" value="<?php echo $row['gb_pf_up_sp_point']; ?>" id="gb_pf_up_sp_point<?php echo $i; ?>" class="frm_input w70 tar"> P
        </td>
        <?php endif; ?>
        <td>
            <label for="gb_pf_up_point<?php echo $i; ?>" class="sound_only">추천쇼핑포인트</label>
            <input type="text" name="gb_pf_up_point[<?php echo $i; ?>]" value="<?php echo $row['gb_pf_up_point']; ?>" id="gb_pf_up_point<?php echo $i; ?>" class="frm_input w70 tar"> P
        </td>
        <td >
            <label for="gb_pf_hosting<?php echo $i; ?>" class="sound_only">무료호스팅개월</label>
            <input type="text" name="gb_pf_hosting[<?php echo $i; ?>]" value="<?php echo $row['gb_pf_hosting']; ?>" id="gb_pf_hosting<?php echo $i; ?>" class="frm_input w40 tar">
            <span class="w40" style="display: inline-block">개월</span>
        </td>
        <td>
            <label for="gb_pf_per_match_pay<?php echo $i; ?>" class="sound_only">관리수수료</label>
            <input type="text" name="gb_pf_per_match_pay[<?php echo $i; ?>]" value="<?php echo $row['gb_pf_per_match_pay']; ?>" id="gb_pf_per_match_pay<?php echo $i; ?>" class="frm_input w40 tar">
            <span class="w40" style="display: inline-block">%</span>
        </td>
    </tr>
	<?php } ?>
	</tbody>
	</table>
</div>
<div class="local_cmd02">
	<i class="fa fa-exclamation-circle fs11 fc_084"></i> <strong>가맹점 등급안내</strong> : 6 ~ 4번 순으로 숫자가 작을수록 레벨이 높습니다.<br>
    <i class="fa fa-exclamation-circle fs11 fc_084"></i> <strong>가맹점개설비</strong> : 부가세를 포함한 금액입니다.(가맹상품 구매로 대체 됩니다.)<br>
    <i class="fa fa-exclamation-circle fs11 fc_084"></i> <strong>쇼핑페이적립%</strong> : 실결제한 금액에 대한 비율입니다.<br>
    <i class="fa fa-exclamation-circle fs11 fc_084"></i> <strong>영업수수료%</strong> : 부가세를 제외한 가맹점개설비에 대한 비율입니다.<br>
    <i class="fa fa-exclamation-circle fs11 fc_084"></i> <strong>관리수수료%</strong> : 부가세를 제외한 매칭된 가맹점개설비에 대한 비율입니다.<br>
    <?php if( defined('USE_LINE_UP') && USE_LINE_UP ) : ?>
    <i class="fa fa-exclamation-circle fs11 fc_red"></i>
    <strong>라인점수 란</strong> : 설정은 된 UP 단계까지 매칭시 매칭 점수 합산 점수가 부여 됩니다.
	<button type="button" id="del_minishop_basic_row" class="btn_small red">설정초기화</button>
    <?php endif; ?>
</div>

<script>
$(function() {
	// 입력필드초기화
	$(document).on("click", "#del_minishop_basic_row", function() {
		$("input[name^=gb_anew_price]").val(0);
		$("input[name^=gb_term_price]").val(0);
		$("input[name^=gb_visit_pay]").val(0);
        $("input[name^=gb_line_point]").val(0);
        $("input[name^=gb_line_depth]").val(0);
		$("input[name^=gb_promotion]").val(0);
	});

	// 레벨명입력시 하위테이블 자동노출
	$(document).on("keyup", ".pb-name-cell", function() {
		var grade, seq;
		var $el_name = $("input[name^=gb_name]");
		var $el_no = $("input[name^=gb_no]");
		$el_name.each(function(index) {
			grade = $.trim($(this).val());
			seq = $.trim($el_no.eq(index).val());
			$(".grade_fld_"+seq).empty().text(grade);
		});
	});
});
</script>

<?php echo $frm_submit; ?>


<h2 class="dpn">타법인회원 전환 가맹점개설비</h2>
<div class="tbl_frm01 dpn">

<div class="sub_frm01">
    <table class="tablef">
        <colgroup>
            <col class="w120">
            <col class="w120">
            <col class="w120">
            <col class="w120">
            <col class="w120">
            <col class="w120">
            <col class="w120">
            <col>
        </colgroup>
        <thead>
        <tr class="tr_alignc">
            <th scope="col">타법인회원유형</th>
            <th scope="col">가맹점개설비</th>
            <th scope="col">정회원</th>
            <th scope="col">VIP회원</th>
            <th scope="col">영업수수료(관리)</th>
            <th scope="col">유지보너스</th>
            <th scope="col">가맹점수보너스</th>
            <th scope="col"></th>
        </tr>
        </thead>
        <tbody class="shop_minishop_type">
        <?php

        $rslt = sql_query("select * from shop_minishop_type order by biz_no");
        while($row = sql_fetch_array($rslt)){
            ?>
            <tr class="tr_alignc">
                <td class="bg3 tal">
                    <input type="hidden" name="biz_no[]" value="<?php echo $row['biz_no'];?>">
                    <?php echo $row['biz_type_name'] ?>
                </td>
                <td class="tr_alignc">
                    <label for="biz_anew_price<?php  echo $row['biz_type'];  ?>" class="sound_only"><?php echo $row['biz_type_name'] ?>가맹점개설비</label>
                    <input type="text" name="biz_anew_price[]"  value="<?php echo $row['biz_anew_price']; ?>" id="biz_anew_price<?php  echo $row['biz_type'];  ?>" class="frm_input w80 tar"> 원
                </td>
                <td><strong><?php echo get_grade($row['biz_grade_5_to']); ?></strong></td>
                <td><strong><?php echo get_grade($row['biz_grade_4_to']); ?></strong></td>
                <td><input type="hidden" name="use_minishop_pay[]" id="use_minishop_pay_<?php echo $row['biz_no']; ?>"
                           value="<?php echo $row['use_minishop_pay']; ?>">
                    <input type="checkbox" name="user_minishop_pay_checker" class="use-checker"
                           id="use_minishop_pay_checker_<?php echo $row['biz_no']; ?>"
                           <?php echo $row['use_minishop_pay'] == 1 ? ' checked' : ''; ?>>
                    <label for="use_minishop_pay_checker_<?php echo $row['biz_no']; ?>">추천수수료(매칭)</label></td>
                <td><input type="hidden" name="use_share_bonus[]" id="use_share_bonus_<?php echo $row['biz_no']; ?>"
                           value="<?php echo $row['use_share_bonus']; ?>">
                    <input type="checkbox" name="use_share_bonus_checker" class="use-checker"
                           id="use_share_bonus_checker_<?php echo $row['biz_no']; ?>"
                        <?php echo $row['use_share_bonus'] == 1 ? ' checked' : ''; ?>>
                    <label for="use_minishop_pay_checker_<?php echo $row['biz_no']; ?>">유지보너스</label></td>
                <td><input type="hidden" name="use_point_bonus[]" id="use_point_bonus<?php echo $row['biz_no']; ?>"
                           value="<?php echo $row['use_point_bonus']; ?>">
                    <input type="checkbox" name="use_point_bonus_checker" class="use-checker"
                           id="use_point_bonus_checker_<?php echo $row['biz_no']; ?>"
                        <?php echo $row['use_point_bonus'] == 1 ? ' checked' : ''; ?>>
                    <label for="use_point_bonus_checker_<?php echo $row['biz_no']; ?>">가맹점수보너스</label></td>
                <td></td>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
    <script>
        (function($){
            $(function(){
                $('.use-checker').on('click', function(){
                    $(this).closest('td').find('[type=hidden]').val($(this).prop('checked') ? 1 : 0);
                });
            });
        }(jQuery));
    </script>
</div>
</div>
    <div class="local_cmd02 dpn" >
        <i class="fa fa-exclamation-circle fs11 fc_084"></i>
        <strong>가맹점개설비</strong> : 타법인회원 가입시 가맹점 개설비 입니다. 다른 설정은 등급별 기본설정을 따릅니다.<br>
    </div>

    <div class="dpn">
        <?php echo $frm_submit; ?>
    </div>


    <h2>판매수수료 인센티브(가맹상품 제외 적용)</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">적립조건</th>
		<td>   
			<label for="pf_sale_benefit_type" class="sound_only">적립유형</label>
			<select name="pf_sale_benefit_type" id="pf_sale_benefit_type">
				<option value="0"<?php if($config['pf_sale_benefit_type'] == "0") echo ' selected="selected"';?>>[대상]이 판매시 수수료를 설정비율(%)로</option>
				<option value="1"<?php if($config['pf_sale_benefit_type'] == "1") echo ' selected="selected"';?>>[대상]이 판매시 수수료를 설정금액(원)으로</option>
			</select>	
			<label for="pf_sale_benefit_dan" class="sound_only">적립단계</label>
			<input type="text" name="pf_sale_benefit_dan" value="<?php echo $config['pf_sale_benefit_dan']; ?>" id="pf_sale_benefit_dan" class="frm_input" size="5"> UP 까지 적립
		</td>
	</tr>	
	<tr>	
		<th scope="row">인센티브</th>
		<td>                
			<div class="sub_frm01">
				<table class="tablef">
				<colgroup>
					<col class="w70">
				</colgroup>
				<thead>
				<tr class="tr_alignc">
					<th scope="col">대상</th>
					<?php for($g=6; $g>1; $g--) { ?>
					<th scope="col" class="grade_fld_<?php echo $g; ?>"><?php echo get_grade($g); ?></th>	
					<?php } ?>
				</tr>
				</thead>
				<tbody class="pf_sale_benefit_fld">
				<?php
				$sale_benefit = array();
				for($g=6; $g>3; $g--) {
					$sale_benefit[$g] = explode(chr(30), $config['pf_sale_benefit_'.$g]);
				}

				for($i=0; $i<(int)$config['pf_sale_benefit_dan']; $i++) {
				?>
				<tr class="tr_alignc">
					<td class="bg3"><?php echo ($i+1); ?>UP</td>
					<?php 
					for($g=6; $g>1; $g--) {
						$amount = (float) trim($sale_benefit[$g][$i]);
					?>
					<td>                
						<label for="pf_sale_benefit_<?php echo $g; ?>_<?php echo $i; ?>" class="sound_only"><?php echo ($i+1); ?>UP <?php echo $g; ?>레벨 인센티브</label>
						<input type="text" name="pf_sale_benefit_<?php echo $g; ?>[<?php echo $i; ?>]" value="<?php echo $amount; ?>" id="pf_sale_benefit_<?php echo $g; ?>_<?php echo $i; ?>" class="frm_input" size="8"> 
					</td>
					<?php } ?>
				</tr>	
				<?php 
				}
				if($i==0) {
					echo '<tr class="tr_alignc"><td colspan="6" class="empty_table">추가 인센티브 설정값이 없습니다.</td></tr>';
				}
				?>
				</tbody>
				</table>
			</td>
		</td>
	</tr>
	</tbody>
	</table>
</div>
<div class="local_cmd02">
	<i class="fa fa-exclamation-circle fs11 fc_084"></i>
	<strong>1UP 이란?</strong> : 고객이 상품구매시 판매수수료를 받는 직가맹점이 {1UP} 이며, 직가맹점의 추천인부터 {2UP} {3UP}....{좌동} 식으로 적립됩니다.
	<button type="button" id="del_sale_benefit_row" class="btn_small red">설정초기화</button>
</div>
<script>
$(function(){
	// 입력필드초기화
	$(document).on("click", "#del_sale_benefit_row", function() {
		$(".pf_sale_benefit_fld input[name^=pf_sale_benefit]").val(0);
	});

	$(document).on("keyup", "#pf_sale_benefit_dan", function() {
		var dan = $(this).val().replace(/[^0-9]/g,"");	
		$.post(
			tb_admin_url+"/minishop/ajax.sale_benefit.php",
			{ "dan": dan },
			function(data) {
				$(".pf_sale_benefit_fld").empty().html(data);
			}
		);
	});
});
</script>
    <?php echo $frm_submit; ?>


<h2>후원수수료 인센티브(가맹상품 적용)</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w140">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">적립조건</th>
		<td> 
			<label for="pf_anew_benefit_type" class="sound_only">적립유형</label>
			<select name="pf_anew_benefit_type" id="pf_anew_benefit_type">
				<option value="0"<?php if($config['pf_anew_benefit_type'] == "0") echo ' selected="selected"';?>>[대상]을 유치시 수수료를 설정비율(%)로</option>
				<option value="1"<?php if($config['pf_anew_benefit_type'] == "1") echo ' selected="selected"';?>>[대상]을 유치시 수수료를 설정금액(원)으로</option>
			</select>	
			<label for="pf_anew_benefit_dan" class="sound_only">적립단계</label>
			<input type="text" name="pf_anew_benefit_dan" value="<?php echo $config['pf_anew_benefit_dan']; ?>" id="pf_anew_benefit_dan" class="frm_input" size="5"> UP 까지 적립
		</td>
	</tr>	
	<tr>
		<th scope="row">인센티브</th>
		<td>                
			<div class="sub_frm01">
				<table class="tablef">
				<colgroup>
					<col class="w70">
				</colgroup>
				<thead>
				<tr class="tr_alignc">
					<th scope="col">대상</th>
					<?php for($g=6; $g>1; $g--) { ?>
					<th scope="col" class="grade_fld_<?php echo $g; ?>"><?php echo get_grade($g); ?></th>	
					<?php } ?>
				</tr>
				</thead>
				<tbody class="pf_anew_benefit_fld">
				<?php
				$anew_benefit = array();
				for($g=6; $g>1; $g--) {
					$anew_benefit[$g] = explode(chr(30), $config['pf_anew_benefit_'.$g]);
				}

				for($i=0; $i<(int)$config['pf_anew_benefit_dan']; $i++) {
				?>
				<tr class="tr_alignc">
					<td class="bg3"><?php echo ($i+1); ?>UP</td>
					<?php 
					for($g=6; $g>1; $g--) {
						$amount = (float)trim($anew_benefit[$g][$i]);
					?>
					<td>                
						<label for="pf_anew_benefit_<?php echo $g; ?>_<?php echo $i; ?>" class="sound_only"><?php echo ($i+1); ?>UP <?php echo $g; ?>레벨 인센티브</label>
						<input type="text" name="pf_anew_benefit_<?php echo $g; ?>[<?php echo $i; ?>]" value="<?php echo $amount; ?>" id="pf_anew_benefit_<?php echo $g; ?>_<?php echo $i; ?>" class="frm_input" size="8"> 
					</td>
					<?php } ?>
				</tr>	
				<?php 
				}
				if($i==0) {
					echo '<tr class="tr_alignc"><td colspan="6" class="empty_table">추가 인센티브 설정값이 없습니다.</td></tr>';
				}
				?>
				</tbody>
				</table>
            </div>
			</td>
		</td>
	</tr>	
	</tbody>
	</table>
</div>
<div class="local_cmd02 lh6">
	<i class="fa fa-exclamation-circle fs11 fc_084"></i>
	<strong>1UP 이란?</strong> : 가맹점 신규개설시 후원수수료를 받는 직가맹점이 {1UP} 이며, 직가맹점의 추천인부터 {2UP} {3UP}....{좌동} 식으로 적립됩니다.<br>
	<i class="fa fa-exclamation-circle fs11 fc_red"></i>
	<strong>주의하세요</strong> : 후원수수료는 별도 허가된 법적 라이센스없이 절대 사용해서는 안됩니다. 반드시 법적자문을 받으신 후 사용하셔야 합니다.
	<button type="button" id="del_anew_benefit_row" class="btn_small red">설정초기화</button>
</div>

<script>
$(function(){
	// 입력필드초기화
	$(document).on("click", "#del_anew_benefit_row", function() {
		$(".pf_anew_benefit_fld input[name^=pf_anew_benefit]").val(0);
	});

	$(document).on("keyup", "#pf_anew_benefit_dan", function() {
		var dan = $(this).val().replace(/[^0-9]/g,"");	
		$.post(
			tb_admin_url+"/minishop/ajax.anew_benefit.php",
			{ "dan": dan },
			function(data) {
				$(".pf_anew_benefit_fld").empty().html(data);
			}
		);
	});
});
</script>


<?php if( !(defined('USE_ANEWMATCH') && USE_ANEWMATCH) ) {  ?><div class="dpn"><?php } ?>

    <h2>매칭수당 인센티브(가맹상품 적용)</h2>
    <div class="tbl_frm01">
        <table>
            <colgroup>
                <col class="w140">
                <col>
            </colgroup>
            <tbo>
            <tr>
                <th scope="row">적립조건</th>
                <td>
                    <input type="hidden" id="pf_anew_match_use" name="pf_anew_match_use" value="<?php echo $config['pf_anew_match_use']; ?>">
                    <label for="pf_anew_match_use_checker">사용</label>
                    <input type="checkbox" id="pf_anew_match_use_checker" name="pf_anew_match_use_checker"
                        <?php if($config['pf_anew_match_use']) echo ' checked="checked"'; ?>
                    ></td>
                <td id="pf_anew_match_setting" class="match-use <?php if(!$config['pf_anew_match_use']) echo 'dpn'; ?>">
                    <label for="pf_anew_match_type" class="sound_only">적립유형</label>
                    1UP을 <input type="text" name="pf_anew_match_per" value="<?php echo $config['pf_anew_match_per']; ?>" id="pf_anew_match_per" class="frm_input tar" data-format="number" size="2">명 유치시 수수료를
                    <select name="pf_anew_match_type" id="pf_anew_match_type">
                        <option value="0"<?php if($config['pf_anew_match_type'] == "0") echo ' selected="selected"';?>>설정비율(%)</option>
                        <option value="1"<?php if($config['pf_anew_match_type'] == "1") echo ' selected="selected"';?>>설정금액(원)</option>
                    </select>
                    로 <input type="hidden" name="pf_anew_match_pay" value="<?php echo $config['pf_anew_match_pay']; ?>" id="pf_anew_match_pay" class="frm_input tar" size="8" data-format="number">적립
                </td>
            </tr>
            <tr class="match-use <?php if(!$config['pf_anew_match_use']) echo 'dpn'; ?>">
                <th scope="row">인센티브</th>
                <td colspan="2">
                    <div class="sub_frm01">
                        <table class="tablef">
                            <colgroup>
                                <col class="w70">
                            </colgroup>
                            <thead>
                            <tr class="tr_alignc">
                                <th scope="col">대상</th>
                                <?php for($g=6; $g>1; $g--) { ?>
                                    <th scope="col" class="grade_fld_<?php echo $g; ?>"><?php echo get_grade($g); ?></th>
                                <?php } ?>
                            </tr>
                            </thead>
                            <tbody class="pf_anew_match_pay_fld">
                            <?php
                            $anew_match_pay = array();
                            for($g=6; $g>1; $g--) {
                                $anew_match_pay[$g] = $config['pf_anew_match_pay_'.$g];
                            }

                            ?>
                            <tr class="tr_alignc">
                                <td class="bg3">1UP</td>
                                <?php
                                $i = 0;
                                for($g=6; $g>1; $g--) {
                                    $amount = (float)trim($anew_match_pay[$g]);
                                    ?>
                                    <td>
                                        <label for="pf_anew_match_pay_<?php echo $g; ?>_<?php echo $i; ?>" class="sound_only"><?php echo ($i+1); ?>UP <?php echo $g; ?>레벨 인센티브</label>
                                        <input type="text" name="pf_anew_match_pay_<?php echo $g; ?>[<?php echo $i; ?>]" value="<?php echo $amount; ?>" id="pf_anew_match_pay_<?php echo $g; ?>_<?php echo $i; ?>" class="frm_input" size="8">
                                    </td>
                                <?php }$i++; ?>
                            </tr>
                            <?php

                            if($i==0) {
                                echo '<tr class="tr_alignc"><td colspan="6" class="empty_table">추가 인센티브 설정값이 없습니다.</td></tr>';
                            }
                            ?>
                            </tbody>
                        </table>
                </td>
                </td>
            </tr>
            </tbo>
        </table>
    </div>
    <div class="local_cmd02 lh6">
        <i class="fa fa-exclamation-circle fs11 fc_084"></i>
        <strong>1UP 이란?</strong> : 가맹점 신규개설시 후원수수료를 받는 직가맹점이 {1UP} 이며, 직가맹점의 추천인부터 {2UP} {3UP}....{좌동} 식으로 적립됩니다.<br>
        <i class="fa fa-exclamation-circle fs11 fc_red"></i>
        <strong>주의하세요</strong> : 매칭수당은 별도 허가된 법적 라이센스없이 절대 사용해서는 안됩니다. 반드시 법적자문을 받으신 후 사용하셔야 합니다. 다른 등급이 매칭시 낮은 등급의 인센티브가 지급됩니다.
    </div>
<?php if( !(defined('USE_ANEWMATCH') && USE_ANEWMATCH) ) {  ?></div><?php } ?>
    <script>
        $(function(){

            $("#pf_anew_match_use_checker").on("click",  function(){
                $('#pf_anew_match_use').val( $(this).is(':checked') ? 1 : 0 );
                if( $(this).is(':checked') ) {
                    $('.match-use').removeClass('dpn');
                } else {
                    $('.match-use').addClass('dpn');
                }

            });

            $(document).on("keyup", "[data-format=number]", function() {
                $(this).val( $(this).val().replace(/[^0-9]/g,"") );
            });
        });
    </script>

   
    <?php echo $frm_submit; ?>
    <h2>가맹점수보너스</h2>
    <div class="tbl_frm01">
        <div class="sub_frm01">
            <?php
            $sql = "select a.*, b.job_title, b.benefit, b.benefit_type from shop_minishop_bonus a, shop_minishop_bonus_title b where a.job_no = b.job_no order by a.up_point asc";
            $rslt= sql_query($sql);
            $rows = array();
            while($row = sql_fetch_array($rslt)){
                array_push($rows, (object) $row);
            }
            ?>
            <table class="tablef">
                <colgroup>
                    <col class="w70">
                </colgroup>
                <thead>
                <tr class="tr_alignc">
                    <th>누적가맹점수</th>
                    <?php
                    foreach($rows as $row ) {
                        ?><th class="tac"><?php echo number_format($row->up_point,0, '.',','); ?>점</th><?php
                    } ?>
                </tr>
                </thead>
                <tbody class="pf_bonus_fld">

                <tr class="tr_alignc">
                    <td>[명함] 직급</td>
                    <?php
                    foreach($rows as $row ) {
                        ?><td class="tac"><?php echo $row->job_title; ?></td><?php
                    } ?>
                </tr>
                <tr class="tr_alignc">
                    <td>권리소득</td>
                    <?php
                    foreach($rows as $row ) {
                        ?><td class="tac"><?php if( $row->benefit > 0 ) echo '매월 '.($row->benefit_type == 'sale' ? '쇼핑몰 수익' :' 가맹매출').' '.$row->benefit.'%'; ?></td><?php
                    } ?>
                </tr>
                <tr class="tr_alignc">
                     <td>지급보너스(만원)</td>
                    <?php
                    foreach($rows as $row ) {
                        ?><td class="tar"><?php echo number_format($row->bonus_pay / 10000); ?>만원</td><?php
                    } ?>
                </tr>
                <tr class="tr_alignc">
                    <td>누적보너스(만원)</td>
                    <?php
                    $total = 0;
                    foreach($rows as $row ) {
                        $total += $row->bonus_pay;
                        ?><td class="tar"><?php echo number_format($total / 10000); ?>만원</td><?php
                    } ?>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    <h2>권리소득</h2>
    <div class="tbl_frm01">
        <div class="sub_frm01">
            <?php
            $sql = "select * from shop_minishop_bonus_title where benefit > 0 order by job_no asc";
            $rslt= sql_query($sql);
            $rows = array();
            while($row = sql_fetch_array($rslt)){
                array_push($rows, (object) $row);
            }
            ?>
            <table class="tablef">
                <colgroup>
                    <col class="w70">
                </colgroup>
                <thead>
                <tr class="tr_alignc">
                    <th>직급</th>
                    <?php
                    foreach($rows as $row ) {
                        ?><th class="tac"><?php echo $row->job_title; ?></th><?php
                    } ?>
                </tr>
                </thead>
                <tbody class="pf_bonus_fld">

                <tr class="tr_alignc">
                    <td>권리소득</td>
                    <?php
                    foreach($rows as $row ) {
                        ?><td class="tac"><?php if( $row->benefit > 0 ) echo '매월 '.($row->benefit_type == 'sale' ? '쇼핑몰 수익' :' 가맹매출').' '.$row->benefit.'%'; ?></td><?php
                    } ?>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
    
</form>
