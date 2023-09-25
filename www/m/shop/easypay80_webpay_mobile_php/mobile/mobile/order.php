<!--�ֹ�������-->
<!--�޴��� '���������� �ۼ�' ������û/�������� �Ķ���� ����.-->

<!DOCTYPE html>
<html style="height: 100%;">
<head>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="content-type" content="text/html; charset=euc-kr">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, target-densitydpi=medium-dpi" />
<title>EasyPay 8.0 webpay mobile</title>
<link rel="stylesheet" type="text/css" href="../css/easypay.css" />
<link rel="stylesheet" type="text/css" href="../css/board.css" />

<!-- Test -->
<script language="javascript" src="http://testsp.easypay.co.kr/webpay/EasypayCard_Web.js"></script>
<!-- Real -->
<!-- script language="javascript" src="https://sp.easypay.co.kr/webpay/EasypayCard_Web.js"></script-->

<script type="text/javascript">
    /* �Ķ���� �ʱⰪ Setting */
    function f_init()
    {
        var frm_pay = document.frm_pay;

        //������ �ֹ���ȣ ����
        var today = new Date();
        var year  = today.getFullYear();
        var month = today.getMonth() + 1;
        var date  = today.getDate();
        var time  = today.getTime();

        if(parseInt(month) < 10)
        {
            month = "0" + month;
        }

        if(parseInt(date) < 10)
        {
            date = "0" + date;
        }

        /*--����--*/
        frm_pay.sp_mall_id.value        = "T0001997";                              //������ ID
        frm_pay.sp_mall_nm.value        = "��������8.0 �����";                    //��������
        frm_pay.sp_order_no.value       = "ORDER_" + year + month + date + time;   //������ �ֹ���ȣ
                                                                                   //��������(select)
        frm_pay.sp_currency.value       = "00";                                    //��ȭ�ڵ� : 00-��
        frm_pay.sp_product_nm.value     = "�׽�Ʈ��ǰ";                            //��ǰ��
        frm_pay.sp_product_amt.value    = "51004";                                 //��ǰ�ݾ�
                                                                                   //������ return_url(������ Ÿ�� ���� ��, �б�)
        frm_pay.sp_lang_flag.value      = "KOR"                                    //���: KOR / ENG
        frm_pay.sp_charset.value        = "EUC-KR"                                 //������ Charset: EUC-KR(default) / UTF-8
        frm_pay.sp_user_id.value        = "psj1988";                               //������ �� ID
        frm_pay.sp_memb_user_no.value   = "15123485756";                           //������ �� �Ϸù�ȣ
        frm_pay.sp_user_nm.value        = "ȫ�浿";                                //������ ����
        frm_pay.sp_user_mail.value      = "kildong@kicc.co.kr";                    //������ �� �̸���
        frm_pay.sp_user_phone1.value    = "0221471111";                            //������ �� ��ȣ1
        frm_pay.sp_user_phone2.value    = "01012345679";                           //������ �� ��ȣ2
        frm_pay.sp_user_addr.value      = "����� ��õ�� ���굿";                  //������ �� �ּ�
        frm_pay.sp_product_type.value   = "0";                                     //��ǰ�������� : 0-�ǹ�, 1-����
        frm_pay.sp_product_expr.value   = "20201231";                              //���񽺱Ⱓ : YYYYMMDD
        frm_pay.sp_app_scheme.value     = "";                                      //������ app scheme : �����app���� ���񽺽� �ʼ�

        /*--�ſ�ī��--*/
        frm_pay.sp_usedcard_code.value  = "";                                      //��밡���� ī�� LIST
        frm_pay.sp_quota.value          = "";                                      //�Һΰ���
        frm_pay.sp_os_cert_flag.value   = "2";                                     //�ؿܾȽ�Ŭ�� ��뿩��
                                                                                   //������ ����(Y/N) (select)
        frm_pay.sp_noinst_term.value    = "029-02:03";                             //�����ڱⰣ
                                                                                   //ī�������Ʈ ��뿩��(select)
        frm_pay.sp_point_card.value     = "029-40";                                //����Ʈī�� LIST
                                                                                   //�����ڵ�(select)
                                                                                   //���� ��ī�� ���(select)

    }

    /* ����â ȣ��, ���� ��û */
    function f_cert()
    {
        var frm_pay = document.frm_pay;

        /*  �ֹ����� Ȯ�� */
        if( !frm_pay.sp_order_no.value )
        {
            alert("�������ֹ���ȣ�� �Է��ϼ���!!");
            frm_pay.sp_order_no.focus();
            return;
        }

        if( !frm_pay.sp_product_amt.value )
        {
            alert("��ǰ�ݾ��� �Է��ϼ���!!");
            frm_pay.sp_product_amt.focus();
            return;
        }
        /* UTF-8 ��밡������ ��� EP_charset �� ���� �ʼ� */
        if( frm_pay.sp_charset.value == "UTF-8" )
        {
            // �ѱ��� ���� ���� ��� encoding �ʼ�.
            frm_pay.sp_mall_nm.value      = encodeURIComponent( frm_pay.sp_mall_nm.value );
            frm_pay.sp_product_nm.value   = encodeURIComponent( frm_pay.sp_product_nm.value );
            frm_pay.sp_user_nm.value      = encodeURIComponent( frm_pay.sp_user_nm.value );
            frm_pay.sp_user_addr.value    = encodeURIComponent( frm_pay.sp_user_addr.value );
        }

            frm_pay.sp_return_url.value = "http://thanqmall.com/shop/easypay80_webpay_mobile_php/mobile/mobile/order_res_submit.php";
            easypay_card_webpay(frm_pay,"./order_req.php","_self","0","0","submit",30);


    }

   /* ���� ��û */
    function f_submit()
    {
        var frm_pay = document.frm_pay;

        // ����("0000") �� �� ���ο�û�������� �̵�.
        if( frm_pay.sp_res_cd.value == "0000" )
        {
            if( frm_pay.sp_charset.value == "UTF-8" )
            {
                // ������û �� ���ڵ� �� ���� ���ο�û �� ���ڵ� ó���ؾ���.
                frm_pay.sp_mall_nm.value      = decodeURIComponent( frm_pay.sp_mall_nm.value );
                frm_pay.sp_product_nm.value   = decodeURIComponent( frm_pay.sp_product_nm.value );
                frm_pay.sp_user_nm.value      = decodeURIComponent( frm_pay.sp_user_nm.value );
                frm_pay.sp_user_addr.value    = decodeURIComponent( frm_pay.sp_user_addr.value );
            }

            frm_pay.target = "_self";
            frm_pay.action = "../easypay_request.php";
            frm_pay.submit();
        }
    }

</script>
</head>
<body id="container_skyblue" onload="f_init();">
<form name="frm_pay" method="post" >

<!-- [START] ������û �ʵ� -->     <!--  <table>������ �Ϻ� �Ķ���� �����մϴ�.-->

<!--����-->
<input type="hidden" id="sp_mall_nm"           name="sp_mall_nm"           value="" />               <!--[����]�������� -->
<input type="hidden" id="sp_order_no"          name="sp_order_no"          value="" />               <!--[�ʼ�]������ �ֹ���ȣ(��������) -->
<input type="hidden" id="sp_currency"          name="sp_currency"          value="" />               <!--[�ʼ�]��ȭ�ڵ�(�����Ұ�) -->
<input type="hidden" id="sp_return_url"        name="sp_return_url"        value="" />               <!--[�ʼ�]������ return URL -->
<input type="hidden" id="sp_lang_flag"         name="sp_lang_flag"         value="" />               <!--[����]��� -->
<input type="hidden" id="sp_charset"           name="sp_charset"           value="" />               <!--[����]������ charset -->
<input type="hidden" id="sp_user_id"           name="sp_user_id"           value="" />               <!--[����]������ ��ID -->
<input type="hidden" id="sp_memb_user_no"      name="sp_memb_user_no"      value="" />               <!--[����]������ ���Ϸù�ȣ -->
<input type="hidden" id="sp_user_nm"           name="sp_user_nm"           value="" />               <!--[����]������ ���� -->
<input type="hidden" id="sp_user_mail"         name="sp_user_mail"         value="" />               <!--[����]������ �� E-mail -->
<input type="hidden" id="sp_user_phone1"       name="sp_user_phone1"       value="" />               <!--[����]������ �� ����ó1 -->
<input type="hidden" id="sp_user_phone2"       name="sp_user_phone2"       value="" />               <!--[����]������ �� ����ó2 -->
<input type="hidden" id="sp_user_addr"         name="sp_user_addr"         value="" />               <!--[����]������ �� �ּ� -->
<input type="hidden" id="sp_user_define1"      name="sp_user_define1"      value="" />               <!--[����]������ �ʵ�1 -->
<input type="hidden" id="sp_user_define2"      name="sp_user_define2"      value="" />               <!--[����]������ �ʵ�2 -->
<input type="hidden" id="sp_user_define3"      name="sp_user_define3"      value="" />               <!--[����]������ �ʵ�3 -->
<input type="hidden" id="sp_user_define4"      name="sp_user_define4"      value="" />               <!--[����]������ �ʵ�4 -->
<input type="hidden" id="sp_user_define5"      name="sp_user_define5"      value="" />               <!--[����]������ �ʵ�5 -->
<input type="hidden" id="sp_user_define6"      name="sp_user_define6"      value="" />               <!--[����]������ �ʵ�6 -->
<input type="hidden" id="sp_product_type"      name="sp_product_type"      value="" />               <!--[����]��ǰ�������� -->
<input type="hidden" id="sp_product_expr"      name="sp_product_expr"      value="" />               <!--[����]���� �Ⱓ -->
<input type="hidden" id="sp_app_scheme"        name="sp_app_scheme"        value="" />               <!--[����]������ APP scheme -->

<!--�ſ�ī��-->
<input type="hidden" id="sp_usedcard_code"     name="sp_usedcard_code"     value="" />               <!--[����]��밡��ī�� LIST -->
<input type="hidden" id="sp_quota"             name="sp_quota"             value="" />               <!--[����]�Һΰ��� -->
<input type="hidden" id="sp_os_cert_flag"      name="sp_os_cert_flag"      value="" />               <!--[����]�ؿܾȽ�Ŭ�� ��뿩��-->
<input type="hidden" id="sp_noinst_flag"       name="sp_noinst_flag"       value="" />               <!--[����]������ ����(Y/N)-->
<input type="hidden" id="sp_noinst_term"       name="sp_noinst_term"       value="" />               <!--[����]������ �Ⱓ -->
<input type="hidden" id="sp_set_point_card_yn" name="sp_set_point_card_yn" value="" />               <!--[����]ī�������Ʈ ��뿩��(Y/N)-->
<input type="hidden" id="sp_point_card"        name="sp_point_card"        value="" />               <!--[����]����Ʈī�� LIST(ī���ڵ�-���� �Һΰ���) -->
<input type="hidden" id="sp_join_cd"           name="sp_join_cd"           value="" />               <!--[����]�����ڵ� -->

<!--�������-->
<input type="hidden" id="sp_vacct_bank"       name="sp_vacct_bank"         value="" />               <!--[����]������� ��밡���� ���� LIST -->
<input type="hidden" id="sp_vacct_end_date"   name="sp_vacct_end_date"     value="" />               <!--[����]�Ա� ���� ��¥ -->
<input type="hidden" id="sp_vacct_end_time"   name="sp_vacct_end_time"     value="" />               <!--[����]�Ա� ���� �ð� -->

<!--����ī��-->
<input type="hidden" id="sp_prepaid_cp"       name="sp_prepaid_cp"         value="" />               <!--[����]����ī�� CP -->

<!-- [END] ������û �ʵ�  -->

<!-- [START] �������� �ʵ� -->

<!--����-->
<input type="hidden" id="sp_res_cd"              name="sp_res_cd"                value="" />         <!-- [�ʼ�]�����ڵ�        -->
<input type="hidden" id="sp_res_msg"             name="sp_res_msg"               value="" />         <!-- [�ʼ�]����޽���      -->
<input type="hidden" id="sp_tr_cd"               name="sp_tr_cd"                 value="" />         <!-- [�ʼ�]����â ��û���� -->
<input type="hidden" id="sp_ret_pay_type"        name="sp_ret_pay_type"          value="" />         <!-- [�ʼ�]��������        -->
<input type="hidden" id="sp_trace_no"            name="sp_trace_no"              value="" />         <!-- [����]������ȣ        -->
<!-- ������ �ֹ���ȣ ������û �ʵ忡 ����.                                                                [�ʼ�]������ �ֹ���ȣ -->
<input type="hidden" id="sp_sessionkey"          name="sp_sessionkey"            value="" />         <!-- [�ʼ�]����Ű          -->
<input type="hidden" id="sp_encrypt_data"        name="sp_encrypt_data"          value="" />         <!-- [�ʼ�]��ȣȭ����      -->
<!-- ������ ID  ������û �ʵ忡 ����.                                                                     [�ʼ�]������ ID       -->
<input type="hidden" id="sp_mobilereserved1"     name="sp_mobilereserved1"       value="" />         <!-- [����]�����ʵ�        -->
<input type="hidden" id="sp_mobilereserved2"     name="sp_mobilereserved2"       value="" />         <!-- [����]�����ʵ�        -->
<input type="hidden" id="sp_reserved1"           name="sp_reserved1"             value="" />         <!-- [����]�����ʵ�        -->
<input type="hidden" id="sp_reserved2"           name="sp_reserved2"             value="" />         <!-- [����]�����ʵ�        -->
<input type="hidden" id="sp_reserved3"           name="sp_reserved3"             value="" />         <!-- [����]�����ʵ�        -->
<input type="hidden" id="sp_reserved4"           name="sp_reserved4"             value="" />         <!-- [����]�����ʵ�        -->

<!--�ſ�ī��-->
<input type="hidden" id="sp_card_code"            name="sp_card_code"            value="" />         <!-- [�ʼ�]ī���ڵ�               -->
<input type="hidden" id="sp_eci_code"             name="sp_eci_code"             value="" />         <!-- [����]ECI�ڵ�(MPI�� ���)    -->
<input type="hidden" id="sp_card_req_type"        name="sp_card_req_type"        value="" />         <!-- [�ʼ�]�ŷ�����               -->
<input type="hidden" id="sp_save_useyn"           name="sp_save_useyn"           value="" />         <!-- [����]ī��� ���̺� ����     -->
<input type="hidden" id="sp_card_prefix"          name="sp_card_prefix"          value="" />         <!-- [����]�ſ�ī�� Prefix        -->
<input type="hidden" id="sp_card_no_7"            name="sp_card_no_7"            value="" />         <!-- [����]�ſ�ī���ȣ ��7�ڸ�   -->

<!--�������-->
<input type="hidden" id="sp_spay_cp"              name="sp_spay_cp"              value="" />          <!-- [����]������� CP�ڵ� -->

<!--����ī��-->
<input type="hidden" id="sp_prepaid_cp"           name="sp_prepaid_cp"           value="" />          <!-- [����]����ī�� CP�ڵ� -->

<!-- [END] �������� �ʵ�  -->

<div id="div_mall">
   <div class="contents1">
            <div class="con1">
                <p>
                    <img src='../img/common/logo.png' height="19" alt="Easypay">
                </p>
            </div>
            <div class="con1t1">
                <p>EP8.0 Webpay Mobile<br>�ֹ� ������</p>
            </div>
    </div>
    <div class="contents">
        <section class="section00 bg_skyblue">
            <fieldset>
                <legend>�ֹ�</legend>
                <br>
                <div class="roundTable">
                    <table width="100%" class="table_roundList" cellpadding="5">
                        <!-- [START] ������û �ʵ� -->
                        <tbody>
                            <tr>
                                <td colspan="2" align="center">�Ϲ�(�ʼ�: *ǥ��)</td>
                            </tr>
                            <!-- [START] ���� -->
                             <tr>
                                <td>������ ID(*)</td>
                                <td><input type='text' name="sp_mall_id" id="sp_mall_id" style="width:180px;" value=""></td> <!-- ������ ID(*) -->
                            </tr>
                            <tr>
                                <td>��������(*)</td>
                                <td>
                                    <select name="sp_pay_type" id="sp_pay_type">
                                        <option value="11" selected>�ſ�ī��</option>
                                        <option value="21">������ü</option>
                                        <option value="22">�������</option>
                                        <option value="31">�޴���</option>
                                        <option value="50">���Ұ���</option>
                                        <option value="60">�������</option>
                                        <option value="81">��ġ����</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>����Ÿ��</td>
                                <td>
                                    <select name="sp_cert_type" id="sp_cert_type">
                                        <option value="" selected>�Ϲ�</option>
                                        <option value="0">����</option>
                                        <option value="1">������</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>��ǰ��(*)</td>
                                <td><input type='text' name="sp_product_nm" id="sp_product_nm" style="width:180px;" value=""></td>
                            </tr>
                            <tr>
                                <td>��ǰ�ݾ�(*)</td>
                                <td><input type='text' name="sp_product_amt"  id="sp_product_amt" style="width:180px;" value=""></td>
                            </tr>
                            <tr>
                                <td>������Ÿ��</td>
                                <td>
                                    <select name="sp_window_type" id="sp_window_type">
                                        <option value="submit" selected>submit</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>���ݿ����� ȭ��ǥ�ÿ���</td>
                                <td>
                                    <select name="sp_disp_cash_yn" id="sp_disp_cash_yn">
                                        <option value="" selected>DB��ȸ</option>
                                        <option value="N">��ǥ��</option>
                                    </select>
                                </td>
                            </tr>
                            <!-- [END] ���� -->
                            <tr>
                                <td>���ξ�ī�� �������</td>
                                <td>
                                    <select name="sp_kmotion_useyn" id="sp_kmotion_useyn">
                                        <option value="Y" >���</option>
                                        <option value="N" >�̻��</option>
                                        <option value="" selected>DB��ȸ</option>
                                    </select>
                                </td>
                            </tr>
                    </tbody>
                    </table>
                    <!-- [END] ������û �ʵ�  -->
                </div><br>
            </fieldset>
           <div class="btnMidNext" align="center"><!-- //button guide���� button �����Ͽ� �ۼ� -->
              <a href="javascript:f_cert();" class="btnBox_blue"><span class="btnWhiteVlines">����</span></a>
          </div>
        </section>
    </div>
</div><br>
<footer class="center margin_b12">
  <p>
      <img src='../img/common/k-logo.gif' width="50" height="9" alt="kicc"> <span class="cop1">Copyright�� 2016 KICC All right reserved</span>
  </p>
</footer>
</form>
</body>
</html>
