<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ko" lang="ko">
<head>
<meta http-equiv="X-UA-Compatible" content="IE=10" />
<meta name="robots" content="noindex, nofollow" />
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<script>
    window.onload = function()
    {
        /* UTF-8 ��밡������ ��� �ѱ��� ���� ���� ��� decoding �ʼ� */
        var res_msg = urldecode( "<?=$_POST["EP_res_msg"] ?>" );

        if(window.opener != null)
        {

            window.opener.document.getElementById("EP_res_cd").value         = "<?=$_POST["EP_res_cd"] ?>";           // �����ڵ�
            window.opener.document.getElementById("EP_res_msg").value        = res_msg;                                             // ����޼���
            window.opener.document.getElementById("EP_tr_cd").value          = "<?=$_POST["EP_tr_cd"] ?>";            // ���� ��û����
            window.opener.document.getElementById("EP_ret_pay_type").value   = "<?=$_POST["EP_ret_pay_type"] ?>";     // ��������
            window.opener.document.getElementById("EP_ret_complex_yn").value = "<?=$_POST["EP_ret_complex_yn"] ?>";   // ���հ��� ���� (Y/N)
            window.opener.document.getElementById("EP_card_code").value      = "<?=$_POST["EP_card_code"] ?>";        // ī���ڵ� (ISP:KVPī���ڵ� MPI:ī���ڵ�)
            window.opener.document.getElementById("EP_eci_code").value       = "<?=$_POST["EP_eci_code"] ?>";         // MPI�� ��� ECI�ڵ�
            window.opener.document.getElementById("EP_card_req_type").value  = "<?=$_POST["EP_card_req_type"] ?>";    // �ŷ����� (0:�Ϲ�, 1:ISP, 2:MPI, 3:UPOP)
            window.opener.document.getElementById("EP_save_useyn").value     = "<?=$_POST["EP_save_useyn"] ?>";       // ī��� ���̺� ���� (Y/N)
            window.opener.document.getElementById("EP_trace_no").value       = "<?=$_POST["EP_trace_no"] ?>";         // ������ȣ
            window.opener.document.getElementById("EP_sessionkey").value     = "<?=$_POST["EP_sessionkey"] ?>";       // ����Ű
            window.opener.document.getElementById("EP_encrypt_data").value   = "<?=$_POST["EP_encrypt_data"] ?>";     // ��ȣȭ����
            window.opener.document.getElementById("EP_spay_cp").value        = "<?=$_POST["EP_spay_cp"] ?>";          // ������� CP �ڵ�
            window.opener.document.getElementById("EP_card_prefix").value    = "<?=$_POST["EP_card_prefix"] ?>";      // �ſ�ī��prefix
            window.opener.document.getElementById("EP_card_no_7").value      = "<?=$_POST["EP_card_no_7"] ?>";        // �ſ�ī�� �� 7�ڸ�


            if( "<?=$_POST["EP_res_cd"] ?>" == "0000" )
            {
                window.opener.f_submit();
            }
            else
            {
                alert( "<?=$_POST["EP_res_cd"] ?> : " + res_msg );
            }

            self.close();
        }
        else
        {

            window.parent.document.getElementById("EP_res_cd").value         = "<?=$_POST["EP_res_cd"] ?>";           // �����ڵ�
            window.parent.document.getElementById("EP_res_msg").value        = res_msg;                                             // ����޼���
            window.parent.document.getElementById("EP_tr_cd").value          = "<?=$_POST["EP_tr_cd"] ?>";            // ���� ��û����
            window.parent.document.getElementById("EP_ret_pay_type").value   = "<?=$_POST["EP_ret_pay_type"] ?>";     // ��������
            window.parent.document.getElementById("EP_ret_complex_yn").value = "<?=$_POST["EP_ret_complex_yn"] ?>";   // ���հ��� ���� (Y/N)
            window.parent.document.getElementById("EP_card_code").value      = "<?=$_POST["EP_card_code"] ?>";        // ī���ڵ� (ISP:KVPī���ڵ� MPI:ī���ڵ�)
            window.parent.document.getElementById("EP_eci_code").value       = "<?=$_POST["EP_eci_code"] ?>";         // MPI�� ��� ECI�ڵ�
            window.parent.document.getElementById("EP_card_req_type").value  = "<?=$_POST["EP_card_req_type"] ?>";    // �ŷ����� (0:�Ϲ�, 1:ISP, 2:MPI, 3:UPOP)
            window.parent.document.getElementById("EP_save_useyn").value     = "<?=$_POST["EP_save_useyn"] ?>";       // ī��� ���̺� ���� (Y/N)
            window.parent.document.getElementById("EP_trace_no").value       = "<?=$_POST["EP_trace_no"] ?>";         // ������ȣ
            window.parent.document.getElementById("EP_sessionkey").value     = "<?=$_POST["EP_sessionkey"] ?>";       // ����Ű
            window.parent.document.getElementById("EP_encrypt_data").value   = "<?=$_POST["EP_encrypt_data"] ?>";     // ��ȣȭ����
            window.parent.document.getElementById("EP_spay_cp").value        = "<?=$_POST["EP_spay_cp"] ?>";          // ������� CP �ڵ�
            window.parent.document.getElementById("EP_card_prefix").value    = "<?=$_POST["EP_card_prefix"] ?>";      // �ſ�ī��prefix
            window.parent.document.getElementById("EP_card_no_7").value      = "<?=$_POST["EP_card_no_7"] ?>";        // �ſ�ī�� �� 7�ڸ�


            if( "<?=$_POST["EP_res_cd"] ?>" == "0000" )
            {
                window.parent.f_submit();
            }
            else
            {
                alert( "<?=$_POST["EP_res_cd"] ?> : " + res_msg );
            }

            window.parent.kicc_popup_close();

        }
    }

     function urldecode( str )
    {
        // ���� ������ + �� ó���ϱ� ���� +('%20') �� �������� ġȯ
        return decodeURIComponent((str + '').replace(/\+/g, '%20'));
    }

</script>
<title>webpay ������ test page</title>
</head>
<body>
</body>
</html>
