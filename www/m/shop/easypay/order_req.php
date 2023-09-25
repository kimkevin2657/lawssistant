<?php include_once('./_common.php'); ?><!--인증요청 페이지-->
<!--메뉴얼 '인증페이지 작성' 인증요청 파라미터 포함.-->
<!DOCTYPE html>
<html style="height: 100%;">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="robots" content="noindex, nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script type="text/javascript">
    window.onload = function()
    {
        document.frm.submit();
    }
</script>
<title>EasyPay 8.0 webpay mobile</title>
</head>
<body>
    <!-- KICC 서버로 인증요청 -->
    <?php if( $default['de_card_test'] === '1' ) : ?>
    <form name="frm" method="post" action="http://testsp.easypay.co.kr/ep8/MainAction.do"> <!-- 테스트 -->
    <?php else : ?>
    <form name="frm" method="post" action="https://sp.easypay.co.kr/ep8/MainAction.do"> <!-- 운영 -->
    <?php endif; ?>


        <!--공통-->
        <input type="hidden" id="sp_mall_id"           name="sp_mall_id"           value="<?=$_POST["EP_mall_id"]           ?>" /> <!--[필수]가맹점ID -->
        <input type="hidden" id="sp_mall_nm"           name="sp_mall_nm"           value="<?=$_POST["EP_mall_nm"]           ?>" /> <!--[선택]가맹점명 -->
        <input type="hidden" id="sp_order_no"          name="sp_order_no"          value="<?=$_POST["EP_order_no"]          ?>" /> <!--[필수]가맹점 주문번호(인증응답) -->
        <input type="hidden" id="sp_pay_type"          name="sp_pay_type"          value="<?=$_POST["EP_pay_type"]          ?>" /> <!--[필수]결제수단 -->
        <input type="hidden" id="sp_cert_type"         name="sp_cert_type"         value="<?=$_POST["EP_cert_type"]          ?>" /> <!--[선택]인증타입 -->
        <input type="hidden" id="sp_currency"          name="sp_currency"          value="<?=$_POST["EP_currency"]          ?>" /> <!--[필수]통화코드(수정불가) -->
        <input type="hidden" id="sp_product_nm"        name="sp_product_nm"        value="<?=$_POST["EP_product_nm"]        ?>" /> <!--[필수]상품명 -->
        <input type="hidden" id="sp_product_amt"       name="sp_product_amt"       value="<?=$_POST["EP_product_amt"]       ?>" /> <!--[필수]상품금액 m-->
        <input type="hidden" id="sp_return_url"        name="sp_return_url"        value="<?=$_POST["EP_return_url"]        ?>" /> <!--[필수]가맹점 return URL -->
        <input type="hidden" id="sp_lang_flag"         name="sp_lang_flag"         value="<?=$_POST["EP_lang_flag"]         ?>" /> <!--[선택]언어 -->
        <input type="hidden" id="sp_charset"           name="sp_charset"           value="<?=$_POST["EP_charset"]           ?>" /> <!--[선택]가맹점 charset -->
        <input type="hidden" id="sp_user_id"           name="sp_user_id"           value="<?=$_POST["EP_user_id"]           ?>" /> <!--[선택]가맹점 고객ID -->
        <input type="hidden" id="sp_memb_user_no"      name="sp_memb_user_no"      value="<?=$_POST["EP_memb_user_no"]      ?>" /> <!--[선택]가맹점 고객일련번호 -->
        <input type="hidden" id="sp_user_nm"           name="sp_user_nm"           value="<?=$_POST["EP_user_nm"]           ?>" /> <!--[선택]가맹점 고객명 -->
        <input type="hidden" id="sp_user_mail"         name="sp_user_mail"         value="<?=$_POST["EP_user_mail"]         ?>" /> <!--[선택]가맹점 고객 E-mail -->
        <input type="hidden" id="sp_user_phone1"       name="sp_user_phone1"       value="<?=$_POST["EP_user_phone1"]       ?>" /> <!--[선택]가맹점 고객 연락처1 -->
        <input type="hidden" id="sp_user_phone2"       name="sp_user_phone2"       value="<?=$_POST["EP_user_phone2"]       ?>" /> <!--[선택]가맹점 고객 연락처2 -->
        <input type="hidden" id="sp_user_addr"         name="sp_user_addr"         value="<?=$_POST["EP_user_addr"]         ?>" /> <!--[선택]가맹점 고객 주소 -->
        <input type="hidden" id="sp_user_define1"      name="sp_user_define1"      value="<?=$_POST["EP_user_define1"]      ?>" /> <!--[선택]가맹점 필드1 -->
        <input type="hidden" id="sp_user_define2"      name="sp_user_define2"      value="<?=$_POST["EP_user_define2"]      ?>" /> <!--[선택]가맹점 필드2 -->
        <input type="hidden" id="sp_user_define3"      name="sp_user_define3"      value="<?=$_POST["EP_user_define3"]      ?>" /> <!--[선택]가맹점 필드3 -->
        <input type="hidden" id="sp_user_define4"      name="sp_user_define4"      value="<?=$_POST["EP_user_define4"]      ?>" /> <!--[선택]가맹점 필드4 -->
        <input type="hidden" id="sp_user_define5"      name="sp_user_define5"      value="<?=$_POST["EP_user_define5"]      ?>" /> <!--[선택]가맹점 필드5 -->
        <input type="hidden" id="sp_user_define6"      name="sp_user_define6"      value="<?=$_POST["EP_user_define6"]      ?>" /> <!--[선택]가맹점 필드6 -->
        <input type="hidden" id="sp_mobilereserved1"   name="sp_mobilereserved1"   value="<?=$_POST["EP_mobilereserved1"]   ?>" /> <!--[선택]가맹점 여유필드1        -->
        <input type="hidden" id="sp_mobilereserved2"   name="sp_mobilereserved2"   value="<?=$_POST["EP_mobilereserved2"]   ?>" /> <!--[선택]가맹점 여유필드2        -->
        <input type="hidden" id="sp_reserved1"         name="sp_reserved1"         value="<?=$_POST["EP_reserved1"]         ?>" /> <!--[선택]가맹점 여유필드1        -->
        <input type="hidden" id="sp_reserved2"         name="sp_reserved2"         value="<?=$_POST["EP_reserved2"]         ?>" /> <!--[선택]가맹점 여유필드2        -->
        <input type="hidden" id="sp_reserved3"         name="sp_reserved3"         value="<?=$_POST["EP_reserved3"]         ?>" /> <!--[선택]가맹점 여유필드3        -->
        <input type="hidden" id="sp_reserved4"         name="sp_reserved4"         value="<?=$_POST["EP_reserved4"]         ?>" /> <!--[선택]가맹점 여유필드4        -->
        <input type="hidden" id="sp_product_type"      name="sp_product_type"      value="<?=$_POST["EP_product_type"]      ?>" /> <!--[선택]상품정보구분 -->
        <input type="hidden" id="sp_product_expr"      name="sp_product_expr"      value="<?=$_POST["EP_product_expr"]      ?>" /> <!--[선택]서비스 기간 -->
        <input type="hidden" id="sp_app_scheme"        name="sp_app_scheme"        value="<?=$_POST["EP_app_scheme"]        ?>" /> <!--[선택]가맹점 APP scheme -->
        <input type="hidden" id="sp_window_type"       name="sp_window_type"       value="<?=$_POST["EP_window_type"]       ?>" /> <!--[선택]윈도우타입 -->
        <input type="hidden" id="sp_disp_cash_yn"      name="sp_disp_cash_yn"      value="<?=$_POST["EP_disp_cash_yn"]      ?>" /> <!--[선택]현금영수증 화면표시여부(Y/N)-->

        <!--신용카드-->
        <input type="hidden" id="sp_usedcard_code"     name="sp_usedcard_code"     value="<?=$_POST["EP_usedcard_code"]     ?>" /> <!--[선택]사용가능카드 LIST -->
        <input type="hidden" id="sp_quota"             name="sp_quota"             value="<?=$_POST["EP_quota"]             ?>" /> <!--[선택]할부개월 -->
        <input type="hidden" id="sp_os_cert_flag"      name="sp_os_cert_flag"      value="<?=$_POST["EP_os_cert_flag"]      ?>" /> <!--[선택]해외안심클릭 사용여부-->
        <input type="hidden" id="sp_noinst_flag"       name="sp_noinst_flag"       value="<?=$_POST["EP_noinst_flag"]       ?>" /> <!--[선택]무이자 여부(Y/N) -->
        <input type="hidden" id="sp_noinst_term"       name="sp_noinst_term"       value="<?=$_POST["EP_noinst_term"]       ?>" /> <!--[선택]무이자 기간 -->
        <input type="hidden" id="sp_set_point_card_yn" name="sp_set_point_card_yn" value="<?=$_POST["EP_set_point_card_yn"] ?>" /> <!--[선택]카드사포인트 사용여부(Y/N) -->
        <input type="hidden" id="sp_point_card"        name="sp_point_card"        value="<?=$_POST["EP_point_card"]        ?>" /> <!--[선택]포인트카드 LIST(카드코드-더할 할부개월) -->
        <input type="hidden" id="sp_join_cd"           name="sp_join_cd"           value="<?=$_POST["EP_join_cd"]           ?>" /> <!--[선택]조인코드 -->
        <input type="hidden" id="sp_kmotion_useyn"     name="sp_kmotion_useyn"     value="<?=$_POST["EP_kmotion_useyn"]     ?>" /> <!--[선택]국민앱카드 사용유무 -->

        <!--가상계좌-->
        <input type="hidden" id="sp_vacct_bank"       name="sp_vacct_bank"         value="<?=$_POST["EP_vacct_bank"]        ?>" /> <!--[선택]가상계좌 사용가능한 은행 LIST -->
        <input type="hidden" id="sp_vacct_end_date"   name="sp_vacct_end_date"     value="<?=$_POST["EP_vacct_end_date"]    ?>" /> <!--[선택]입금 만료 날짜 -->
        <input type="hidden" id="sp_vacct_end_time"   name="sp_vacct_end_time"     value="<?=$_POST["EP_vacct_end_time"]    ?>" /> <!--[선택]입금 만료 시간 -->

        <!--선불카드-->
        <input type="hidden" id="sp_prepaid_cp"       name="sp_prepaid_cp"         value="<?=$_POST["EP_prepaid_cp"]        ?>" /> <!--[선택]선불카드 CP -->

     </form>
</body>
</html>
