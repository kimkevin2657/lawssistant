<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

if (!$is_mobile_pay) {
    add_javascript('<script language="javascript" type="text/javascript" src="'.$stdpay_js_url.'" charset="UTF-8"></script>', 10);
}
?>
<script type="text/javascript">

function pg_pay(f) {

    <?php if (!$is_mobile_pay) { // 데스크탑 환경일경우 ?>

        var payment = $(":input:radio[name=bk_payment]:checked").val();
        switch(payment)
        {
            case "계좌이체":
                f.gopaymethod.value = "DirectBank";
                break;
            case "가상계좌":
                f.gopaymethod.value = "VBank";
                break;
            case "휴대폰":
                f.gopaymethod.value = "HPP";
                break;
            case "신용카드":
                f.gopaymethod.value = "Card";
                f.acceptmethod.value = f.acceptmethod.value.replace(":useescrow", "");
                break;
            case "간편결제":
                f.gopaymethod.value = "Kpay";
                break;
            default:
                f.gopaymethod.value = "무통장";
                break;
        }

        f.good_mny.value  = f.bk_price.value;

        f.price.value       = f.good_mny.value;
        //f.buyername.value   = f.bk_name.value;
        f.buyeremail.value  = f.bk_email.value;
        f.buyertel.value    = f.bk_hp.value;

        if(f.gopaymethod.value != "무통장") {

            // 주문 정보 임시저장
            var order_data = $(f).serialize();
            var save_result = "";
            $.ajax({
                type: "POST",
                data: order_data,
                url: "<?php echo WPOT_PLUGIN_URL?>/gender/pg.pay_data.php",
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

            if(!make_signature(f))
                return false;

            INIStdPay.pay(f.id);

        } else {
            return true;
        }

    <?php } else { ?>

        var f = document.sm_form;
        var pf = document.wzfrm;

        var paymethod = "";
        var width = 330;
        var height = 480;
        var xpos = (screen.width - width) / 2;
        var ypos = (screen.width - height) / 2;
        var position = "top=" + ypos + ",left=" + xpos;
        var features = position + ", width=320, height=440";
        var p_reserved = f.DEF_RESERVED.value;
        f.P_RESERVED.value = p_reserved;

        var settle_method = $(":input:radio[name=bk_payment]:checked").val();

        switch(settle_method) {
            case "계좌이체":
                paymethod = "bank";
                break;
            case "가상계좌":
                paymethod = "vbank";
                break;
            case "휴대폰":
                paymethod = "mobile";
                break;
            case "신용카드":
                paymethod = "wcard";
                f.P_RESERVED.value = f.P_RESERVED.value.replace("&useescrow=Y", "");
                break;
            case "간편결제":
                paymethod = "wcard";
                f.P_RESERVED.value = p_reserved+"&d_kpay=Y&d_kpay_app=Y";
                break;
            case "삼성페이":
                paymethod = "wcard";
                f.P_RESERVED.value = f.P_RESERVED.value.replace("&useescrow=Y", "")+"&d_samsungpay=Y";
                //f.DEF_RESERVED.value = f.DEF_RESERVED.value.replace("&useescrow=Y", "");
                f.P_SKIP_TERMS.value = "Y"; //약관을 skip 해야 제대로 실행됨
                break;
        }
        pf.good_mny.value = pf.bk_price.value;
        f.good_mny.value = pf.good_mny.value;
        f.P_AMT.value = f.good_mny.value;
        //f.P_UNAME.value = pf.bk_name.value;
        f.P_MOBILE.value = pf.bk_hp.value;
        f.P_EMAIL.value = pf.bk_email.value;
        f.P_RETURN_URL.value = "<?php echo $return_url.$od_id; ?>";
        f.action = "https://mobile.inicis.com/smart/" + paymethod + "/";

        // 주문 정보 임시저장
        var order_data = $(pf).serialize();
        var save_result = "";
        $.ajax({
            type: "POST",
            data: order_data,
            url: "<?php echo WPOT_PLUGIN_URL?>/gender/pg.pay_data.php",
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

        f.submit();

    <?php } ?>

}

function make_signature(frm)
{
    // 데이터 암호화 처리
    var result = true;
    $.ajax({
        url: "<?php echo WPOT_PLUGIN_URL?>/gender/inicis/makesignature.php",
        type: "POST",
        data: {
            bo_table : g5_bo_table,
            price : frm.good_mny.value
        },
        dataType: "json",
        async: false,
        cache: false,
        success: function(data) {
            if(data.error == "") {
                frm.timestamp.value = data.timestamp;
                frm.signature.value = data.sign;
                frm.mKey.value = data.mKey;
            } else {
                alert(data.error);
                result = false;
            }
        }
    });

    return result;
}

function openPopup(){
    var win = window.open('', 'win', 'width=1, height=1, scrollbars=yes, resizable=yes');

    if (win == null || typeof(win) == "undefined" || (win == null && win.outerWidth == 0) || (win != null && win.outerHeight == 0) || win.test == "undefined") {
        alert("팝업 차단 기능이 설정되어있습니다\n\n차단 기능을 해제(팝업허용) 한 후 다시 이용해 주십시오.\n\n만약 팝업 차단 기능을 해제하지 않으면\n정상적인 예약이 이루어지지 않습니다.");
        if (win) {
            win.close();
        }
        return;
    }
    else if (win) {
        if (win.innerWidth === 0) {
            alert("팝업 차단 기능이 설정되어있습니다\n\n차단 기능을 해제(팝업허용) 한 후 다시 이용해 주십시오.\n\n만약 팝업 차단 기능을 해제하지 않으면\n정상적인 예약이 이루어지지 않습니다.");
        }
    }
    else {
        return;
    }
    if (win) { // 팝업창이 떠있다면 close();
        win.close();
    }
}

window.onload = function() { // 페이지 로딩 후 즉시 함수 실행(window.onload)
    //openPopup();
}
</script>


<?php if ($is_mobile_pay) { // 모바일 환경일경우 ?>

    <form name="sm_form" method="POST" action="" accept-charset="euc-kr">
    <input type="hidden" name="P_OID"        value="<?php echo $od_id; ?>">
    <input type="hidden" name="P_GOODS"      value="<?php echo $goods; ?>">
    <input type="hidden" name="P_AMT"        value="">
    <input type="hidden" name="P_UNAME"      value="">
    <input type="hidden" name="P_MOBILE"     value="">
    <input type="hidden" name="P_EMAIL"      value="">
    <input type="hidden" name="P_MID"        value="<?php echo $wzcnf['cf_pg_mid']; ?>">
    <input type="hidden" name="P_NEXT_URL"   value="<?php echo $next_url; ?>">
    <input type="hidden" name="P_NOTI_URL"   value="<?php echo $noti_url; ?>">
    <input type="hidden" name="P_RETURN_URL" value="">
    <input type="hidden" name="P_HPP_METHOD" value="2">
    <input type="hidden" name="P_RESERVED"   value="bank_receipt=N&twotrs_isp=Y&block_isp=Y<?php echo $useescrow; ?>">
    <input type="hidden" name="DEF_RESERVED" value="bank_receipt=N&twotrs_isp=Y&block_isp=Y<?php echo $useescrow; ?>">
    <input type="hidden" name="P_NOTI"       value="<?php echo $od_id; ?>">
    <input type="hidden" name="P_QUOTABASE"  value="01:02:03:04:05:06:07:08:09:10:11:12"> <!-- 할부기간 설정 01은 일시불 -->
    <input type="hidden" name="P_SKIP_TERMS"      value="">

    <input type="hidden" name="good_mny"     value="" >

    </form>

<?php } ?>