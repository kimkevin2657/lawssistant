<!--������û ������-->
<!--�޴��� '���������� �ۼ�' ������û �Ķ���� ����.-->

<!DOCTYPE html>
<html style="height: 100%;">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=Edge" />
<meta name="robots" content="noindex, nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=euc-kr" />
<script type="text/javascript">
    window.onload = function()
    {
        document.frm.submit();
    }
</script>
<title>EasyPay 8.0 webpay mobile</title>
</head>
<body>
    <!-- KICC ������ ������û -->

    <!-- TEST -->
    <!--form name="frm" method="post" action="http://testsp.easypay.co.kr/ep8/MainAction.do" -->
    <!-- REAL -->
    <form name="frm" method="post" action="https://sp.easypay.co.kr/ep8/MainAction.do" >


        <!--����-->
        <input type="hidden" id="sp_mall_id"           name="sp_mall_id"           value="<?=$_POST["sp_mall_id"]           ?>" /> <!--[�ʼ�]������ID -->
        <input type="hidden" id="sp_mall_nm"           name="sp_mall_nm"           value="<?=$_POST["sp_mall_nm"]           ?>" /> <!--[����]�������� -->
        <input type="hidden" id="sp_order_no"          name="sp_order_no"          value="<?=$_POST["sp_order_no"]          ?>" /> <!--[�ʼ�]������ �ֹ���ȣ(��������) -->
        <input type="hidden" id="sp_pay_type"          name="sp_pay_type"          value="<?=$_POST["sp_pay_type"]          ?>" /> <!--[�ʼ�]�������� -->
        <input type="hidden" id="sp_cert_type"         name="sp_cert_type"         value="<?=$_POST["sp_cert_type"]         ?>" /> <!--[����]����Ÿ�� -->
        <input type="hidden" id="sp_currency"          name="sp_currency"          value="<?=$_POST["sp_currency"]          ?>" /> <!--[�ʼ�]��ȭ�ڵ�(�����Ұ�) -->
        <input type="hidden" id="sp_product_nm"        name="sp_product_nm"        value="<?=$_POST["sp_product_nm"]        ?>" /> <!--[�ʼ�]��ǰ�� -->
        <input type="hidden" id="sp_product_amt"       name="sp_product_amt"       value="<?=$_POST["sp_product_amt"]       ?>" /> <!--[�ʼ�]��ǰ�ݾ� m-->
        <input type="hidden" id="sp_return_url"        name="sp_return_url"        value="<?=$_POST["sp_return_url"]        ?>" /> <!--[�ʼ�]������ return URL -->
        <input type="hidden" id="sp_lang_flag"         name="sp_lang_flag"         value="<?=$_POST["sp_lang_flag"]         ?>" /> <!--[����]��� -->
        <input type="hidden" id="sp_charset"           name="sp_charset"           value="<?=$_POST["sp_charset"]           ?>" /> <!--[����]������ charset -->
        <input type="hidden" id="sp_user_id"           name="sp_user_id"           value="<?=$_POST["sp_user_id"]           ?>" /> <!--[����]������ ��ID -->
        <input type="hidden" id="sp_memb_user_no"      name="sp_memb_user_no"      value="<?=$_POST["sp_memb_user_no"]      ?>" /> <!--[����]������ ���Ϸù�ȣ -->
        <input type="hidden" id="sp_user_nm"           name="sp_user_nm"           value="<?=$_POST["sp_user_nm"]           ?>" /> <!--[����]������ ���� -->
        <input type="hidden" id="sp_user_mail"         name="sp_user_mail"         value="<?=$_POST["sp_user_mail"]         ?>" /> <!--[����]������ �� E-mail -->
        <input type="hidden" id="sp_user_phone1"       name="sp_user_phone1"       value="<?=$_POST["sp_user_phone1"]       ?>" /> <!--[����]������ �� ����ó1 -->
        <input type="hidden" id="sp_user_phone2"       name="sp_user_phone2"       value="<?=$_POST["sp_user_phone2"]       ?>" /> <!--[����]������ �� ����ó2 -->
        <input type="hidden" id="sp_user_addr"         name="sp_user_addr"         value="<?=$_POST["sp_user_addr"]         ?>" /> <!--[����]������ �� �ּ� -->
        <input type="hidden" id="sp_user_define1"      name="sp_user_define1"      value="<?=$_POST["sp_user_define1"]      ?>" /> <!--[����]������ �ʵ�1 -->
        <input type="hidden" id="sp_user_define2"      name="sp_user_define2"      value="<?=$_POST["sp_user_define2"]      ?>" /> <!--[����]������ �ʵ�2 -->
        <input type="hidden" id="sp_user_define3"      name="sp_user_define3"      value="<?=$_POST["sp_user_define3"]      ?>" /> <!--[����]������ �ʵ�3 -->
        <input type="hidden" id="sp_user_define4"      name="sp_user_define4"      value="<?=$_POST["sp_user_define4"]      ?>" /> <!--[����]������ �ʵ�4 -->
        <input type="hidden" id="sp_user_define5"      name="sp_user_define5"      value="<?=$_POST["sp_user_define5"]      ?>" /> <!--[����]������ �ʵ�5 -->
        <input type="hidden" id="sp_user_define6"      name="sp_user_define6"      value="<?=$_POST["sp_user_define6"]      ?>" /> <!--[����]������ �ʵ�6 -->
        <input type="hidden" id="sp_mobilereserved1"   name="sp_mobilereserved1"   value="<?=$_POST["sp_mobilereserved1"]   ?>" /> <!--[����]������ �����ʵ�1        -->
        <input type="hidden" id="sp_mobilereserved2"   name="sp_mobilereserved2"   value="<?=$_POST["sp_mobilereserved2"]   ?>" /> <!--[����]������ �����ʵ�2        -->
        <input type="hidden" id="sp_reserved1"         name="sp_reserved1"         value="<?=$_POST["sp_reserved1"]         ?>" /> <!--[����]������ �����ʵ�1        -->
        <input type="hidden" id="sp_reserved2"         name="sp_reserved2"         value="<?=$_POST["sp_reserved2"]         ?>" /> <!--[����]������ �����ʵ�2        -->
        <input type="hidden" id="sp_reserved3"         name="sp_reserved3"         value="<?=$_POST["sp_reserved3"]         ?>" /> <!--[����]������ �����ʵ�3        -->
        <input type="hidden" id="sp_reserved4"         name="sp_reserved4"         value="<?=$_POST["sp_reserved4"]         ?>" /> <!--[����]������ �����ʵ�4        -->
        <input type="hidden" id="sp_product_type"      name="sp_product_type"      value="<?=$_POST["sp_product_type"]      ?>" /> <!--[����]��ǰ�������� -->
        <input type="hidden" id="sp_product_expr"      name="sp_product_expr"      value="<?=$_POST["sp_product_expr"]      ?>" /> <!--[����]���� �Ⱓ -->
        <input type="hidden" id="sp_app_scheme"        name="sp_app_scheme"        value="<?=$_POST["sp_app_scheme"]        ?>" /> <!--[����]������ APP scheme -->
        <input type="hidden" id="sp_window_type"       name="sp_window_type"       value="<?=$_POST["sp_window_type"]       ?>" /> <!--[����]������Ÿ�� -->
        <input type="hidden" id="sp_disp_cash_yn"      name="sp_disp_cash_yn"      value="<?=$_POST["sp_disp_cash_yn"]      ?>" /> <!--[����]���ݿ����� ȭ��ǥ�ÿ���(Y/N)-->

        <!--�ſ�ī��-->
        <input type="hidden" id="sp_usedcard_code"     name="sp_usedcard_code"     value="<?=$_POST["sp_usedcard_code"]     ?>" /> <!--[����]��밡��ī�� LIST -->
        <input type="hidden" id="sp_quota"             name="sp_quota"             value="<?=$_POST["sp_quota"]             ?>" /> <!--[����]�Һΰ��� -->
        <input type="hidden" id="sp_os_cert_flag"      name="sp_os_cert_flag"      value="<?=$_POST["sp_os_cert_flag"]      ?>" /> <!--[����]�ؿܾȽ�Ŭ�� ��뿩��-->
        <input type="hidden" id="sp_noinst_flag"       name="sp_noinst_flag"       value="<?=$_POST["sp_noinst_flag"]       ?>" /> <!--[����]������ ����(Y/N) -->
        <input type="hidden" id="sp_noinst_term"       name="sp_noinst_term"       value="<?=$_POST["sp_noinst_term"]       ?>" /> <!--[����]������ �Ⱓ -->
        <input type="hidden" id="sp_set_point_card_yn" name="sp_set_point_card_yn" value="<?=$_POST["sp_set_point_card_yn"] ?>" /> <!--[����]ī�������Ʈ ��뿩��(Y/N) -->
        <input type="hidden" id="sp_point_card"        name="sp_point_card"        value="<?=$_POST["sp_point_card"]        ?>" /> <!--[����]����Ʈī�� LIST(ī���ڵ�-���� �Һΰ���) -->
        <input type="hidden" id="sp_join_cd"           name="sp_join_cd"           value="<?=$_POST["sp_join_cd"]           ?>" /> <!--[����]�����ڵ� -->
        <input type="hidden" id="sp_kmotion_useyn"     name="sp_kmotion_useyn"     value="<?=$_POST["sp_kmotion_useyn"]     ?>" /> <!--[����]���ξ�ī�� ������� -->

        <!--�������-->
        <input type="hidden" id="sp_vacct_bank"       name="sp_vacct_bank"         value="<?=$_POST["sp_vacct_bank"]        ?>" /> <!--[����]������� ��밡���� ���� LIST -->
        <input type="hidden" id="sp_vacct_end_date"   name="sp_vacct_end_date"     value="<?=$_POST["sp_vacct_end_date"]    ?>" /> <!--[����]�Ա� ���� ��¥ -->
        <input type="hidden" id="sp_vacct_end_time"   name="sp_vacct_end_time"     value="<?=$_POST["sp_vacct_end_time"]    ?>" /> <!--[����]�Ա� ���� �ð� -->

        <!--����ī��-->
        <input type="hidden" id="sp_prepaid_cp"       name="sp_prepaid_cp"         value="<?=$_POST["sp_prepaid_cp"]        ?>" /> <!--[����]����ī�� CP -->

     </form>
</body>
</html>
