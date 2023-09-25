<?php
if(!defined('_MALLSET_')) exit;
$pg_title = "기본정보 관리";
include_once("./admin_head.sub.php");
?>

<form name="fregform" method="post" action="./minishop_info_update.php">
<input type="hidden" name="token" value="">

<h2>기본정보</h2>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col class="w180">
		<col>
		<col class="w180">
		<col>
	</colgroup>
    <tbody>
	<tr>
		<th scope="row">미니샵 주소</th>
		<td colspan="3"><a href="<?php echo $admin_shop_url; ?>" target="_blank" class="sitecode"><?php echo $admin_shop_url; ?></a></td>
	</tr>
	<tr>
		<th scope="row">회원명 (아이디)</th>
		<td><?php echo $member['name']; ?> (<?php echo $member['id']; ?>)</td>
		<th scope="row">미니샵 신청일</th>
		<td><?php echo $minishop['reg_time']; ?></td>
	</tr>
	<tr>
		<th scope="row">회원레벨</th>
		<td><?php echo get_grade($member['grade']); ?></td>
		<th scope="row">승인여부</th>
		<td><?php echo ($minishop['state'])?"승인완료":"승인대기"; ?></td>
	</tr>
	</tbody>
	</table>
</div>

<h2>스킨 설정</h2>
<div class="tbl_frm01">
	<table class="tablef">
	<colgroup>
		<col class="w180">
		<col>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="theme">PC 쇼핑몰스킨</label></th>
		<td>
			<?php echo get_theme_select('theme', $member['theme']); ?>
		</td>
		<th scope="row"><label for="mobile_theme">모바일 쇼핑몰스킨</label></th>
		<td>
			<?php echo get_mobile_theme_select('mobile_theme', $member['mobile_theme']); ?>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<h2>입금받으실 수수료계좌정보</h2>
<div class="local_cmd01">
	<p>※ 아래 계좌정보는 수수료 정산시 이용 됩니다. 정확히 입력해주세요.</p>
</div>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="bank_name">은행명</label></th>
		<td><input type="text" name="bank_name" value="<?php echo $minishop['bank_name']; ?>" id="bank_name" class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="bank_account">계좌번호</label></th>
		<td><input type="text" name="bank_account" value="<?php echo $minishop['bank_account']; ?>" id="bank_account" class="frm_input" size="30"></td>
	</tr>
	<tr>
		<th scope="row"><label for="bank_holder">예금주명</label></th>
		<td><input type="text" name="bank_holder" value="<?php echo $minishop['bank_holder']; ?>" id="bank_holder" class="frm_input" size="30"></td>
	</tr>
	</tbody>
	</table>
</div>
<?php if( defined('USE_SHOPPING_PAY_EXCHANGE') && USE_SHOPPING_PAY_EXCHANGE ) : ?>
<h2>환전받으실 페이정보</h2>
<div class="local_cmd01">
    <p>※ 아래 계좌정보는 수수료 정산시 이용 됩니다. 정확히 입력해주세요.</p>
</div>
<div class="tbl_frm01">
    <table>
        <colgroup>
            <col class="w180">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><label for="pay_bank_name">페이명</label></th>
            <td><input type="text" name="pay_bank_name" value="<?php echo $minishop['pay_bank_name']; ?>" id="pay_bank_name" class="frm_input" size="30"></td>
        </tr>
        <tr>
            <th scope="row"><label for="pay_bank_account">페이계좌번호</label></th>
            <td><input type="text" name="pay_bank_account" value="<?php echo $minishop['pay_bank_account']; ?>" id="pay_bank_account" class="frm_input" size="30"></td>
        </tr>
        <tr>
            <th scope="row"><label for="pay_bank_holder">페이계좌주명</label></th>
            <td><input type="text" name="pay_bank_holder" value="<?php echo $minishop['pay_bank_holder']; ?>" id="pay_bank_holder" class="frm_input" size="30"></td>
        </tr>
        </tbody>
    </table>
</div>
<?php endif; ?>
<h2>사업자정보</h2>
<div class="local_cmd01">
	<p>※ 아래 사업자정보는 쇼핑몰 하단에 노출되며 노출안함으로 설정시 본사 사업자정보가 노출 됩니다.</p>
</div>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row">쇼핑몰 사업자노출 여부</th>
		<td>
			<?php echo radio_checked('saupja_yes', $minishop['saupja_yes'], '1', '노출함'); ?>
			<?php echo radio_checked('saupja_yes', $minishop['saupja_yes'], '0', '노출안함'); ?>			
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="shop_name">쇼핑몰명</label></th>
		<td>
			<input type="text" name="shop_name" value="<?php echo $minishop['shop_name']; ?>" id="shop_name" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="shop_name_us">쇼핑몰 영문명</label></th>
		<td>
			<input type="text" name="shop_name_us" value="<?php echo $minishop['shop_name_us']; ?>" id="shop_name_us" class="frm_input" size="30">
		</td>
	</tr>
	<tr>
		<th scope="row">사업자유형</th>
		<td>
            <?php echo radio_checked('company_type', $minishop['company_type'], '3', '개인'); ?>
			<?php echo radio_checked('company_type', $minishop['company_type'], '0', '일반과세자'); ?>
			<?php echo radio_checked('company_type', $minishop['company_type'], '1', '간이과세자'); ?>
			<?php echo radio_checked('company_type', $minishop['company_type'], '2', '면세사업자'); ?>
		</td>
	</tr>
    <tr data-role="biz-nm">
        <th scope="row"><label for="company_name">회사명</label></th>
        <td>
            <input type="text" name="company_name" value="<?php echo $minishop['company_name']; ?>" id="company_name" class="frm_input" size="30">
            <em>세무서에 등록되어 있는 회사명 입력</em>
        </td>
    </tr>
    <tr data-role="biz-info" >
        <th scope="row"><label for="company_owner">대표자명</label></th>
        <td>
            <input type="text" name="company_owner" value="<?php echo $minishop['company_owner']; ?>" id="company_owner" class="frm_input" size="30">
            <em>예) 홍길동</em>
        </td>
    </tr>
    <tr data-role="biz-no">
        <th scope="row"><label for="company_saupja_no">사업자등록번호</label></th>
        <td>
            <input type="text" name="company_saupja_no" value="<?php echo $minishop['company_saupja_no']; ?>" id="company_saupja_no" class="frm_input" size="30">
            <em>예) 000-00-00000</em>
        </td>
    </tr>
    <tr data-role="biz-info" >
        <th scope="row"><label for="company_item">업태</label></th>
        <td>
            <input type="text" name="company_item" value="<?php echo $minishop['company_item']; ?>" id="company_item" class="frm_input" size="30">
            <em>예) 소매업</em>
        </td>
    </tr>
    <tr data-role="biz-info" >
        <th scope="row"><label for="company_service">종목</label></th>
        <td>
            <input type="text" name="company_service" value="<?php echo $minishop['company_service']; ?>" id="company_service" class="frm_input" size="30">
            <em>예) 전자상거래업</em>
        </td>
    </tr>
    <tr data-role="biz-info" >
        <th scope="row"><label for="company_zip">사업장우편번호</label></th>
        <td>
            <input type="text" name="company_zip" maxlength="5" value="<?php echo $minishop['company_zip']; ?>" id="company_zip" class="frm_input" size="8">
        </td>
    </tr>
    <tr data-role="biz-info" >
        <th scope="row"><label for="company_addr">사업장주소</label></th>
        <td>
            <input type="text" name="company_addr" value="<?php echo $minishop['company_addr']; ?>" id="company_addr" class="frm_input" size="60">
        </td>
    </tr>
	<tr>
		<th scope="row"><label for="tongsin_no">통신판매업신고번호</label></th>
		<td>
			<input type="text" name="tongsin_no" value="<?php echo $minishop['tongsin_no']; ?>" id="tongsin_no" class="frm_input" size="30">
			<em>예) <?php echo MS_TIME_YEAR.'-서울강남-0000호'; ?></em>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="company_tel">대표전화번호</label></th>
		<td>
			<input type="text" name="company_tel" value="<?php echo $minishop['company_tel']; ?>" id="company_tel" class="frm_input" size="30">
			<em>예) 1544-0000, 070-0000-0000</em>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="company_fax">팩스번호</label></th>
		<td>
			<input type="text" name="company_fax" value="<?php echo $minishop['company_fax']; ?>" id="company_fax" class="frm_input" size="30">
			<em>예) 02-0000-0000</em>
		</td>
	</tr>	
	<tr>
		<th scope="row"><label for="info_name">정보책임자 이름</label></th>
		<td>
			<input type="text" name="info_name" value="<?php echo $minishop['info_name']; ?>" id="info_name" class="frm_input" size="30">
			<em>예) 홍길동</em>
		</td>
	</tr>		
	<tr>
		<th scope="row"><label for="info_email">정보책임자 e-mail</label></th>
		<td>
			<input type="text" name="info_email" value="<?php echo $minishop['info_email']; ?>" id="info_email" class="email frm_input" size="30">
			<em>예) help@domain.com</em>
		</td>
	</tr>
	</tbody>
	</table>
</div>
    <script>
        (function($){
            $(function(){
                var $companyType = $('[name=company_type]');
                var $bizInfo     = $('[data-role=biz-info]');
                var $bizNo       = $('[data-role=biz-no]');
                var $bizNm       = $('[data-role=biz-nm]');
                $companyType.on('click', function(){
                    if( $(this).val() == '3' ) {
                        $bizInfo.hide();
                        $bizNo.find('label').text('주민등록번호');
                        $bizNo.find('em').text('예)700101-1200001');
                        $bizNm.find('label').text('이름');
                        $bizNm.find('em').text('정산 받을 실명을 입력하세요.');
                    } else {
                        $bizInfo.show();
                        $bizNo.find('label').text('사업자등록번호');
                        $bizNo.find('em').text('예) 000-00-00000');
                        $bizNm.find('label').text('회사명');
                        $bizNm.find('em').text('세무서에 등록되어 있는 회사명 입력');
                    }
                });

                $companyType.filter(':checked').trigger('click');

            });
        }(jQuery));
    </script>
<h2>CS 운영시간</h2>
<div class="tbl_frm01">
	<table>
	<colgroup>
		<col class="w180">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<th scope="row"><label for="company_hours">상담가능시간</label></th>
		<td>
			<input type="text" name="company_hours" value="<?php echo $minishop['company_hours']; ?>" id="company_hours" class="frm_input" size="60">
			<em>예) 오전9시~오후6시</em>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="company_lunch">점심시간</label></th>
		<td>
			<input type="text" name="company_lunch" value="<?php echo $minishop['company_lunch']; ?>" id="company_lunch" class="frm_input" size="60">
			<em>예) 오후12시~오후1시</em>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="company_close">휴무일</label></th>
		<td>
			<input type="text" name="company_close" value="<?php echo $minishop['company_close']; ?>" id="company_close" class="frm_input" size="60">
			<em>예) 토요일,공휴일 휴무</em>
		</td>
	</tr>
	<tr>
		<th scope="row"><label for="company_url">추가링크</label></th>
		<td>
			<input type="text" name="company_url" value="<?php echo $minishop['company_url']; ?>" id="company_url" class="frm_input" size="60">
			<em>예) http:// or https:// 꼭 붙여주세요.</em>
		</td>
	</tr>
	</tbody>
	</table>
</div>

<div class="btn_confirm">
	<input type="submit" value="저장" class="btn_large" accesskey="s">
</div>
</form>

<?php
include_once("./admin_tail.sub.php");
?>