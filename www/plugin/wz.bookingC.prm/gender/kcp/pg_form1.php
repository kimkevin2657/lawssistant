<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
?>
<script type="text/javascript">
/****************************************************************/
/* m_Completepayment  설명                                      */
/****************************************************************/
/* 인증완료시 재귀 함수                                         */
/* 해당 함수명은 절대 변경하면 안됩니다.                        */
/* 해당 함수의 위치는 payplus.js 보다먼저 선언되어여 합니다.    */
/* Web 방식의 경우 리턴 값이 form 으로 넘어옴                   */
/* EXE 방식의 경우 리턴 값이 json 으로 넘어옴                   */
/****************************************************************/
function m_Completepayment( FormOrJson, closeEvent )
{
    var frm = document.wzfrm;

    /********************************************************************/
    /* FormOrJson은 가맹점 임의 활용 금지                               */
    /* frm 값에 FormOrJson 값이 설정 됨 frm 값으로 활용 하셔야 됩니다.  */
    /* FormOrJson 값을 활용 하시려면 기술지원팀으로 문의바랍니다.       */
    /********************************************************************/
    GetField( frm, FormOrJson );

    if( frm.res_cd.value == "0000" )
    {
        document.getElementById("display_pay_button").style.display = "none" ;
        document.getElementById("display_pay_process").style.display = "" ;
        
        frm.action = "<?php echo $action_url;?>";
        frm.submit();
    }
    else
    {
        alert( "[" + frm.res_cd.value + "] " + frm.res_msg.value );

        closeEvent();
    }
}
function pg_pay(f) {

    f.site_cd.value = f.def_site_cd.value;
    f.payco_direct.value = "";

    var payment = $(":input:radio[name=bk_payment]:checked").val();
    switch(payment)
    {
        case "계좌이체":
            f.pay_method.value   = "010000000000";
            break;
        case "가상계좌":
            f.pay_method.value   = "001000000000";
            break;
        case "휴대폰":
            f.pay_method.value   = "000010000000";
            break;
        case "신용카드":
            f.pay_method.value   = "100000000000";
            break;
        case "간편결제":
            f.site_cd.value      = "<?php echo $g_conf_site_cd?>";
            f.pay_method.value   = "100000000000";
            f.payco_direct.value = "Y";
            break;
        default:
            f.pay_method.value   = "무통장";
            break;
    }
	
	f.good_mny.value  = f.reserv_price.value;
    f.buyr_name.value = f.bk_name.value;
    f.buyr_mail.value = f.bk_email.value;
    f.buyr_tel1.value = f.bk_hp.value;

    if(f.pay_method.value != "무통장") {
        <?php if (!$is_mobile_pay) {?>
        return jsf_pay( f );
        <?php } else { ?>
        return jsf_pay_mobile( f );
        <?php } ?>
    } else {
        return true;
    }

}
</script>

<script src="<?php echo $g_conf_js_url; ?>"></script>
<script>
/* Payplus Plug-in 실행 */
function jsf_pay( form )
{
    try
    {
        KCP_Pay_Execute( form );
    }
    catch (e)
    {
        /* IE 에서 결제 정상종료시 throw로 스크립트 종료 */
    }
}
function jsf_pay_mobile( form )
{
    var sm = document.sm_form;

    sm.buyr_name.value = form.bk_name.value;
    sm.buyr_mail.value = form.bk_email.value;
    sm.rcvr_tel1.value = form.buyr_tel1.value;
    sm.payco_direct.value = form.payco_direct.value;
    sm.good_mny.value  = form.reserv_price.value;

    var payment = $(":input:radio[name=bk_payment]:checked").val();
    sm.settle_method.value  = payment;

    // 주문 정보 임시저장
    var order_data = $(form).serialize();
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

}
</script>

<form name="sm_form" method="POST" action="<?php echo WZB_PLUGIN_URL; ?>/gender/kcp/pg_mobile_approval_form.php">
<input type="hidden" name="bo_table"      value="<?php echo $bo_table; ?>">
<input type="hidden" name="good_name"     value="<?php echo $goods; ?>">
<input type="hidden" name="good_mny"      value="<?php echo $reserv_price ?>" >
<input type="hidden" name="buyr_name"     value="">
<input type="hidden" name="buyr_tel1"     value="">
<input type="hidden" name="buyr_tel2"     value="">
<input type="hidden" name="buyr_mail"     value="">
<input type="hidden" name="ipgm_date"     value="<?php echo $ipgm_date; ?>">
<input type="hidden" name="settle_method" value="">
<input type="hidden" name="payco_direct"   value="">      <!-- PAYCO 결제창 호출 -->
<!-- 주문번호 -->
<input type="hidden" name="ordr_idxx" value="<?php echo $od_id; ?>">
<!-- 결제등록 키 -->
<input type="hidden" name="approval_key" id="approval">
<!-- 수취인이름 -->
<input type="hidden" name="rcvr_name" value="">
<!-- 수취인 연락처 -->
<input type="hidden" name="rcvr_tel1" value="">
<!-- 수취인 휴대폰 번호 -->
<input type="hidden" name="rcvr_tel2" value="">
<!-- 수취인 E-MAIL -->
<input type="hidden" name="rcvr_add1" value="">
<!-- 수취인 우편번호 -->
<input type="hidden" name="rcvr_add2" value="">
<!-- 수취인 주소 -->
<input type="hidden" name="rcvr_mail" value="">
<!-- 수취인 상세 주소 -->
<input type="hidden" name="rcvr_zipx" value="">
<!-- 장바구니 상품 개수 -->
<input type="hidden" name="bask_cntx" value="<?php echo (int)$cnt_room; ?>">
<!-- 장바구니 정보(상단 스크립트 참조) -->
<input type="hidden" name="good_info" value="">
<!-- 배송소요기간 -->
<input type="hidden" name="deli_term" value="03">
<!-- 기타 파라메터 추가 부분 - Start - -->
<input type="hidden" name="param_opt_1"  value=""/>
<input type="hidden" name="param_opt_2"  value=""/>
<input type="hidden" name="param_opt_3"  value=""/>
<input type="hidden" name="disp_tax_yn"  value="N">
<!-- 기타 파라메터 추가 부분 - End - -->
<!-- 화면 크기조정 부분 - Start - -->
<input type="hidden" name="tablet_size"  value="<?php echo $tablet_size; ?>"/>
<!-- 화면 크기조정 부분 - End - -->
<!--
    사용 카드 설정
    <input type="hidden" name='used_card'    value="CClg:ccDI">
    /*  무이자 옵션
            ※ 설정할부    (가맹점 관리자 페이지에 설정 된 무이자 설정을 따른다)                             - "" 로 설정
            ※ 일반할부    (KCP 이벤트 이외에 설정 된 모든 무이자 설정을 무시한다)                           - "N" 로 설정
            ※ 무이자 할부 (가맹점 관리자 페이지에 설정 된 무이자 이벤트 중 원하는 무이자 설정을 세팅한다)   - "Y" 로 설정
    <input type="hidden" name="kcp_noint"       value=""/> */

    /*  무이자 설정
            ※ 주의 1 : 할부는 결제금액이 50,000 원 이상일 경우에만 가능
            ※ 주의 2 : 무이자 설정값은 무이자 옵션이 Y일 경우에만 결제 창에 적용
            예) 전 카드 2,3,6개월 무이자(국민,비씨,엘지,삼성,신한,현대,롯데,외환) : ALL-02:03:04
            BC 2,3,6개월, 국민 3,6개월, 삼성 6,9개월 무이자 : CCBC-02:03:06,CCKM-03:06,CCSS-03:06:04
    <input type="hidden" name="kcp_noint_quota" value="CCBC-02:03:06,CCKM-03:06,CCSS-03:06:09"/> */
-->
<input type="hidden" name="kcp_noint"       value="">
<?php
if($default['de_tax_flag_use']) {
    /* KCP는 과세상품과 비과세상품을 동시에 판매하는 업체들의 결제관리에 대한 편의성을 제공해드리고자,
       복합과세 전용 사이트코드를 지원해 드리며 총 금액에 대해 복합과세 처리가 가능하도록 제공하고 있습니다

       복합과세 전용 사이트 코드로 계약하신 가맹점에만 해당이 됩니다

       상품별이 아니라 금액으로 구분하여 요청하셔야 합니다

       총결제 금액은 과세금액 + 부과세 + 비과세금액의 합과 같아야 합니다.
       (good_mny = comm_tax_mny + comm_vat_mny + comm_free_mny)

       복합과세는 order_approval_form.php 파일의 의해 적용됨
       아래 필드는 order_approval_form.php 파일로 전송하는 것
    */
?>
<input type="hidden" name="tax_flag"          value="TG03">     <!-- 변경불가    -->
<input type="hidden" name="comm_tax_mny"      value="">         <!-- 과세금액    -->
<input type="hidden" name="comm_vat_mny"      value="">         <!-- 부가세     -->
<input type="hidden" name="comm_free_mny"     value="">        <!-- 비과세 금액 -->
<?php
}
?>
</form>