<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>

<script language="javascript" src="<?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']=='on') ? 'https' : 'http'; ?>://xpay.uplus.co.kr/xpay/js/xpay_crossplatform.js" type="text/javascript"></script>
<script type="text/javascript">

var LGD_window_type = "<?php echo $LGD_WINDOW_TYPE; ?>";

function launchCrossPlatform(frm) {
    $.ajax({
        url: "<?php echo WZB_PLUGIN_URL?>/gender/lg/xpay_request.php",
        type: "POST",
        data: $("#LGD_PAYREQUEST input").serialize(),
        dataType: "json",
        async: false,
        cache: false,
        success: function(data) {
            frm.LGD_HASHDATA.value = data.LGD_HASHDATA;

            lgdwin = openXpay(frm, '<?php echo $CST_PLATFORM; ?>', LGD_window_type, null, "", "");
        },
        error: function(data) {
            console.log(data);
        }
    });
}
/*
* FORM 명만  수정 가능
*/
function getFormObject() {
    return document.getElementById("wzfrm");
}

/*
 * 인증결과 처리
 */
function payment_return() {
    var fDoc;

    fDoc = lgdwin.contentWindow || lgdwin.contentDocument;

    if (fDoc.document.getElementById('LGD_RESPCODE').value == "0000") {
        document.getElementById("LGD_PAYKEY").value = fDoc.document.getElementById('LGD_PAYKEY').value;
        document.getElementById("wzfrm").target = "_self";
        document.getElementById("wzfrm").action = "<?php echo $action_url; ?>";
        document.getElementById("wzfrm").submit();
    } else {
        document.getElementById("wzfrm").target = "_self";
        document.getElementById("wzfrm").action = "<?php echo $action_url; ?>";
        alert("LGD_RESPCODE (결과코드) : " + fDoc.document.getElementById('LGD_RESPCODE').value + "\n" + "LGD_RESPMSG (결과메시지): " + fDoc.document.getElementById('LGD_RESPMSG').value);
        closeIframe();
    }
}


function pg_pay(f) {

    var payment = $(":input:radio[name=bk_payment]:checked").val();
    
    <?php if (!$is_mobile_pay) {?>
    
        f.LGD_EASYPAY_ONLY.value = "";
        if(typeof f.LGD_CUSTOM_USABLEPAY === "undefined") {
            var input = document.createElement("input");
            input.setAttribute("type", "hidden");
            input.setAttribute("name", "LGD_CUSTOM_USABLEPAY");
            input.setAttribute("value", "");
            f.LGD_EASYPAY_ONLY.parentNode.insertBefore(input, f.LGD_EASYPAY_ONLY);
        }

        switch(payment)
        {
            case "계좌이체":
                f.LGD_CUSTOM_FIRSTPAY.value = "SC0030";
                f.LGD_CUSTOM_USABLEPAY.value = "SC0030";
                break;
            case "가상계좌":
                f.LGD_CUSTOM_FIRSTPAY.value = "SC0040";
                f.LGD_CUSTOM_USABLEPAY.value = "SC0040";
                break;
            case "휴대폰":
                f.LGD_CUSTOM_FIRSTPAY.value = "SC0060";
                f.LGD_CUSTOM_USABLEPAY.value = "SC0060";
                break;
            case "신용카드":
                f.LGD_CUSTOM_FIRSTPAY.value = "SC0010";
                f.LGD_CUSTOM_USABLEPAY.value = "SC0010";
                break;
            case "간편결제":
                var elm = f.LGD_CUSTOM_USABLEPAY;
                if(elm.parentNode)
                    elm.parentNode.removeChild(elm);
                f.LGD_EASYPAY_ONLY.value = "PAYNOW";
                break;
            default:
                f.LGD_CUSTOM_FIRSTPAY.value = "무통장";
                break;
        }

        f.LGD_BUYER.value = f.bk_name.value;
        f.LGD_BUYEREMAIL.value = f.bk_email.value;
        f.LGD_BUYERPHONE.value = f.bk_hp.value;
        f.LGD_AMOUNT.value = f.reserv_price.value;

        if(f.LGD_CUSTOM_FIRSTPAY.value != "무통장") {
            launchCrossPlatform(f);
        } else {
            return true;
        }

    <?php } else { ?>

        var sm = document.sm_form;
        var pay_method = "";
        var easy_pay = "";
        switch(payment) {
            case "계좌이체":
                pay_method = "SC0030";
                break;
            case "가상계좌":
                pay_method = "SC0040";
                break;
            case "휴대폰":
                pay_method = "SC0060";
                break;
            case "신용카드":
                pay_method = "SC0010";
                break;
            case "간편결제":
                easy_pay = "PAYNOW";
                break;
        }
        sm.LGD_CUSTOM_FIRSTPAY.value = pay_method;
        sm.LGD_BUYER.value = f.bk_name.value;
        sm.LGD_BUYEREMAIL.value = f.bk_email.value;
        sm.LGD_BUYERPHONE.value = f.bk_hp.value;
        sm.LGD_AMOUNT.value = f.reserv_price.value;
        sm.LGD_EASYPAY_ONLY.value = easy_pay;
        
        // 주문 정보 임시저장
        var order_data = $(f).serialize();
        var save_result = "";
        $.ajax({
            type: "POST",
            data: order_data,
            url: "<?php echo WZB_PLUGIN_URL?>/gender/pg.pay_data.php",
            cache: false,
            async: false,
            success: function(data) {
                save_result = data;
            }
        });

        if(save_result) {
            alert(save_result);
            return false;
        }

        sm.submit();

    <?php } ?>

}
</script>

<form name="sm_form" method="POST" action="<?php echo WZB_PLUGIN_URL; ?>/gender/lg/pg_mobile_approval_form.php">
<input type="hidden" name="LGD_OID"                     id="LGD_OID"            value="<?php echo $od_id; ?>">                                   <!-- 주문번호 -->
<input type="hidden" name="LGD_BUYER"                   id="LGD_BUYER"          value="">                                  <!-- 구매자 -->
<input type="hidden" name="LGD_PRODUCTINFO"             id="LGD_PRODUCTINFO"    value="<?php echo $goods; ?>">             <!-- 상품정보 -->
<input type="hidden" name="LGD_AMOUNT"                  id="LGD_AMOUNT"         value="">                                  <!-- 결제금액 -->
<input type="hidden" name="LGD_CUSTOM_FIRSTPAY"         id="LGD_CUSTOM_FIRSTPAY" value="">                                 <!-- 결제수단 -->
<input type="hidden" name="LGD_BUYEREMAIL"              id="LGD_BUYEREMAIL"     value="">                                  <!-- 구매자 이메일 -->
<input type="hidden" name="LGD_TAXFREEAMOUNT"           id="LGD_TAXFREEAMOUNT"  value="">                                  <!-- 결제금액 중 면세금액 -->
<input type="hidden" name="LGD_BUYERID"                 id="LGD_BUYERID"        value="<?php echo $LGD_BUYERID; ?>">       <!-- 구매자ID -->
<input type="hidden" name="LGD_CASHRECEIPTYN"           id="LGD_CASHRECEIPTYN"  value="N">                                 <!-- 현금영수증 사용 설정 -->
<input type="hidden" name="LGD_BUYERPHONE"              id="LGD_BUYERPHONE"     value="">                                  <!-- 구매자 휴대폰번호 -->
<input type="hidden" name="LGD_EASYPAY_ONLY"            id="LGD_EASYPAY_ONLY"   value="">                                  <!-- 페이나우 결제 호출 -->

<input type="hidden" name="bo_table"      value="<?php echo $bo_table; ?>">

</form>