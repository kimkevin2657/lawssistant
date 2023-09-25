<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>

<?php if (!$is_mobile_pay) { // 데스크탑 환경일경우 ?>
        

    <input type="hidden" name="good_mny"    value="<?php echo $reserv_price; ?>">

    <?php
    if($wzpconfig['pn_pg_tax_flag_use']) {

        if($wzpconfig['pn_pg_tax_free']) {
            $comm_tax_mny   = 0;
            $comm_vat_mny   = 0;
            $comm_free_mny  = $reserv_price;
        }
        else {
            $comm_tax_mny   = round($reserv_price / 1.1);
            $comm_vat_mny   = $reserv_price - $comm_tax_mny;
            $comm_free_mny  = 0;
        }
    ?>
    <input type="hidden" name="comm_tax_mny"	  value="<?php echo $comm_tax_mny; ?>">         <!-- 과세금액    -->
    <input type="hidden" name="comm_vat_mny"      value="<?php echo $comm_vat_mny; ?>">         <!-- 부가세	    -->
    <input type="hidden" name="comm_free_mny"     value="<?php echo $comm_free_mny; ?>">        <!-- 비과세 금액 -->
    <?php
    }
    ?>

    <input type="hidden" name="version"     value="1.0" >
    <input type="hidden" name="mid"         value="<?php echo $mid; ?>">
    <input type="hidden" name="oid"         value="<?php echo $od_id; ?>">
    <input type="hidden" name="goodname"    value="<?php echo $goods; ?>">
    <input type="hidden" name="price"       value="<?php echo $reserv_price; ?>">
    <input type="hidden" name="buyername"   value="">
    <input type="hidden" name="buyeremail"  value="">
    <input type="hidden" name="parentemail" value="">
    <input type="hidden" name="buyertel"    value="">
    <input type="hidden" name="recvname"    value="">
    <input type="hidden" name="recvtel"     value="">
    <input type="hidden" name="recvaddr"    value="">
    <input type="hidden" name="recvpostnum" value="">

    <!-- 기타설정 -->
    <input type="hidden" name="currency"    value="WON">

    <!-- 결제방법 -->
    <input type="hidden" name="gopaymethod" value="">

    <!--
    SKIN : 플러그인 스킨 칼라 변경 기능 - 6가지 칼라(ORIGINAL, GREEN, ORANGE, BLUE, KAKKI, GRAY)
    HPP : 컨텐츠 또는 실물 결제 여부에 따라 HPP(1)과 HPP(2)중 선택 적용(HPP(1):컨텐츠, HPP(2):실물).
    Card(0): 신용카드 지불시에 이니시스 대표 가맹점인 경우에 필수적으로 세팅 필요 ( 자체 가맹점인 경우에는 카드사의 계약에 따라 설정) - 자세한 내용은 메뉴얼  참조.
    OCB : OK CASH BAG 가맹점으로 신용카드 결제시에 OK CASH BAG 적립을 적용하시기 원하시면 "OCB" 세팅 필요 그 외에 경우에는 삭제해야 정상적인 결제 이루어짐.
    no_receipt : 은행계좌이체시 현금영수증 발행여부 체크박스 비활성화 (현금영수증 발급 계약이 되어 있어야 사용가능)
    -->
    <input type="hidden" name="acceptmethod" value="<?php echo $acceptmethod; ?>">

    <!--
    플러그인 좌측 상단 상점 로고 이미지 사용
    이미지의 크기 : 90 X 34 pixels
    플러그인 좌측 상단에 상점 로고 이미지를 사용하실 수 있으며,
    주석을 풀고 이미지가 있는 URL을 입력하시면 플러그인 상단 부분에 상점 이미지를 삽입할수 있습니다.
    -->
    <!--input type="hidden" name="ini_logoimage_url"  value="http://[사용할 이미지주소]"-->

    <!--
    좌측 결제메뉴 위치에 이미지 추가
    이미지의 크기 : 단일 결제 수단 - 91 X 148 pixels, 신용카드/ISP/계좌이체/가상계좌 - 91 X 96 pixels
    좌측 결제메뉴 위치에 미미지를 추가하시 위해서는 담당 영업대표에게 사용여부 계약을 하신 후
    주석을 풀고 이미지가 있는 URL을 입력하시면 플러그인 좌측 결제메뉴 부분에 이미지를 삽입할수 있습니다.
    -->
    <!--input type="hidden" name="ini_menuarea_url" value="http://[사용할 이미지주소]"-->

    <!--
    플러그인에 의해서 값이 채워지거나, 플러그인이 참조하는 필드들
    삭제/수정 불가
    -->
    <input type="hidden" name="timestamp"   value="">
    <input type="hidden" name="signature"   value="">
    <input type="hidden" name="returnUrl"   value="<?php echo $returnUrl; ?>">
    <input type="hidden" name="mKey"        value="">
    <input type="hidden" name="charset"     value="UTF-8">
    <input type="hidden" name="payViewType" value="overlay">
    <input type="hidden" name="closeUrl"    value="<?php echo $closeUrl; ?>">
    <input type="hidden" name="popupUrl"    value="<?php echo $popupUrl; ?>">
    <input type="hidden" name="nointerest"  value="<?php echo $cardNoInterestQuota; ?>">
    <input type="hidden" name="quotabase"   value="<?php echo $cardQuotaBase; ?>">

    <?php if($wzpconfig['pn_pg_tax_flag_use']) { ?>
    <input type="hidden" name="tax"         value="<?php echo $comm_vat_mny; ?>">
    <input type="hidden" name="taxfree"     value="<?php echo $comm_free_mny; ?>">
    <?php } ?>


<?php } else { // 모바일 환경일경우 ?>


    <input type="hidden" name="good_mny"          value="<?php echo $reserv_price ?>" >
    <input type="hidden" name="res_cd"            value="">                                     <!-- 결과 코드          -->

    <input type="hidden" name="P_HASH"            value="">
    <input type="hidden" name="P_TYPE"            value="">
    <input type="hidden" name="P_UNAME"           value="">
    <input type="hidden" name="P_AUTH_DT"         value="">
    <input type="hidden" name="P_AUTH_NO"         value="">
    <input type="hidden" name="P_HPP_CORP"        value="">
    <input type="hidden" name="P_APPL_NUM"        value="">
    <input type="hidden" name="P_VACT_NUM"        value="">
    <input type="hidden" name="P_VACT_NAME"       value="">
    <input type="hidden" name="P_VACT_BANK"       value="">
    <input type="hidden" name="P_CARD_ISSUER"     value="">

    <?php
    if($wzpconfig['pn_pg_tax_flag_use']) {

        if($wzpconfig['pn_pg_tax_free']) {
            $comm_tax_mny   = 0;
            $comm_vat_mny   = 0;
            $comm_free_mny  = $reserv_price;
        }
        else {
            $comm_tax_mny   = round($reserv_price / 1.1);
            $comm_vat_mny   = $reserv_price - $comm_tax_mny;
            $comm_free_mny  = 0;
        }
    ?>
    <input type="hidden" name="comm_tax_mny"      value="<?php echo $comm_tax_mny; ?>">         <!-- 과세금액    -->
    <input type="hidden" name="comm_vat_mny"      value="<?php echo $comm_vat_mny; ?>">         <!-- 부가세     -->
    <input type="hidden" name="comm_free_mny"     value="<?php echo $comm_free_mny; ?>">        <!-- 비과세 금액 -->
    <?php } ?>


<?php } ?>