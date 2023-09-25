<!--주문페이지-->
<!--메뉴얼 '인증페이지 작성' 인증요청/인증응답 파라미터 포함.-->

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
    /* 파라미터 초기값 Setting */
    function f_init()
    {
        var frm_pay = document.frm_pay;

        //가맹점 주문번호 설정
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

        /*--공통--*/
        frm_pay.sp_mall_id.value        = "T0001997";                              //가맹점 ID
        frm_pay.sp_mall_nm.value        = "이지페이8.0 모바일";                    //가맹점명
        frm_pay.sp_order_no.value       = "ORDER_" + year + month + date + time;   //가맹점 주문번호
                                                                                   //결제수단(select)
        frm_pay.sp_currency.value       = "00";                                    //통화코드 : 00-원
        frm_pay.sp_product_nm.value     = "테스트상품";                            //상품명
        frm_pay.sp_product_amt.value    = "51004";                                 //상품금액
                                                                                   //가맹점 return_url(윈도우 타입 선택 시, 분기)
        frm_pay.sp_lang_flag.value      = "KOR"                                    //언어: KOR / ENG
        frm_pay.sp_charset.value        = "EUC-KR"                                 //가맹점 Charset: EUC-KR(default) / UTF-8
        frm_pay.sp_user_id.value        = "psj1988";                               //가맹점 고객 ID
        frm_pay.sp_memb_user_no.value   = "15123485756";                           //가맹점 고객 일련번호
        frm_pay.sp_user_nm.value        = "홍길동";                                //가맹점 고객명
        frm_pay.sp_user_mail.value      = "kildong@kicc.co.kr";                    //가맹점 고객 이메일
        frm_pay.sp_user_phone1.value    = "0221471111";                            //가맹점 고객 번호1
        frm_pay.sp_user_phone2.value    = "01012345679";                           //가맹점 고객 번호2
        frm_pay.sp_user_addr.value      = "서울시 금천구 가산동";                  //가맹점 고객 주소
        frm_pay.sp_product_type.value   = "0";                                     //상품정보구분 : 0-실물, 1-서비스
        frm_pay.sp_product_expr.value   = "20201231";                              //서비스기간 : YYYYMMDD
        frm_pay.sp_app_scheme.value     = "";                                      //가맹점 app scheme : 모바일app으로 서비스시 필수

        /*--신용카드--*/
        frm_pay.sp_usedcard_code.value  = "";                                      //사용가능한 카드 LIST
        frm_pay.sp_quota.value          = "";                                      //할부개월
        frm_pay.sp_os_cert_flag.value   = "2";                                     //해외안심클릭 사용여부
                                                                                   //무이자 여부(Y/N) (select)
        frm_pay.sp_noinst_term.value    = "029-02:03";                             //무이자기간
                                                                                   //카드사포인트 사용여부(select)
        frm_pay.sp_point_card.value     = "029-40";                                //포인트카드 LIST
                                                                                   //조인코드(select)
                                                                                   //국민 앱카드 사용(select)

    }

    /* 인증창 호출, 인증 요청 */
    function f_cert()
    {
        var frm_pay = document.frm_pay;

        /*  주문정보 확인 */
        if( !frm_pay.sp_order_no.value )
        {
            alert("가맹점주문번호를 입력하세요!!");
            frm_pay.sp_order_no.focus();
            return;
        }

        if( !frm_pay.sp_product_amt.value )
        {
            alert("상품금액을 입력하세요!!");
            frm_pay.sp_product_amt.focus();
            return;
        }
        /* UTF-8 사용가맹점의 경우 EP_charset 값 셋팅 필수 */
        if( frm_pay.sp_charset.value == "UTF-8" )
        {
            // 한글이 들어가는 값은 모두 encoding 필수.
            frm_pay.sp_mall_nm.value      = encodeURIComponent( frm_pay.sp_mall_nm.value );
            frm_pay.sp_product_nm.value   = encodeURIComponent( frm_pay.sp_product_nm.value );
            frm_pay.sp_user_nm.value      = encodeURIComponent( frm_pay.sp_user_nm.value );
            frm_pay.sp_user_addr.value    = encodeURIComponent( frm_pay.sp_user_addr.value );
        }

            frm_pay.sp_return_url.value = "http://thanqmall.com/shop/easypay80_webpay_mobile_php/mobile/mobile/order_res_submit.php";
            easypay_card_webpay(frm_pay,"./order_req.php","_self","0","0","submit",30);


    }

   /* 승인 요청 */
    function f_submit()
    {
        var frm_pay = document.frm_pay;

        // 정상("0000") 일 때 승인요청페이지로 이동.
        if( frm_pay.sp_res_cd.value == "0000" )
        {
            if( frm_pay.sp_charset.value == "UTF-8" )
            {
                // 인증요청 시 인코딩 한 값은 승인요청 시 디코딩 처리해야함.
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

<!-- [START] 인증요청 필드 -->     <!--  <table>내에도 일부 파라미터 존재합니다.-->

<!--공통-->
<input type="hidden" id="sp_mall_nm"           name="sp_mall_nm"           value="" />               <!--[선택]가맹점명 -->
<input type="hidden" id="sp_order_no"          name="sp_order_no"          value="" />               <!--[필수]가맹점 주문번호(인증응답) -->
<input type="hidden" id="sp_currency"          name="sp_currency"          value="" />               <!--[필수]통화코드(수정불가) -->
<input type="hidden" id="sp_return_url"        name="sp_return_url"        value="" />               <!--[필수]가맹점 return URL -->
<input type="hidden" id="sp_lang_flag"         name="sp_lang_flag"         value="" />               <!--[선택]언어 -->
<input type="hidden" id="sp_charset"           name="sp_charset"           value="" />               <!--[선택]가맹점 charset -->
<input type="hidden" id="sp_user_id"           name="sp_user_id"           value="" />               <!--[선택]가맹점 고객ID -->
<input type="hidden" id="sp_memb_user_no"      name="sp_memb_user_no"      value="" />               <!--[선택]가맹점 고객일련번호 -->
<input type="hidden" id="sp_user_nm"           name="sp_user_nm"           value="" />               <!--[선택]가맹점 고객명 -->
<input type="hidden" id="sp_user_mail"         name="sp_user_mail"         value="" />               <!--[선택]가맹점 고객 E-mail -->
<input type="hidden" id="sp_user_phone1"       name="sp_user_phone1"       value="" />               <!--[선택]가맹점 고객 연락처1 -->
<input type="hidden" id="sp_user_phone2"       name="sp_user_phone2"       value="" />               <!--[선택]가맹점 고객 연락처2 -->
<input type="hidden" id="sp_user_addr"         name="sp_user_addr"         value="" />               <!--[선택]가맹점 고객 주소 -->
<input type="hidden" id="sp_user_define1"      name="sp_user_define1"      value="" />               <!--[선택]가맹점 필드1 -->
<input type="hidden" id="sp_user_define2"      name="sp_user_define2"      value="" />               <!--[선택]가맹점 필드2 -->
<input type="hidden" id="sp_user_define3"      name="sp_user_define3"      value="" />               <!--[선택]가맹점 필드3 -->
<input type="hidden" id="sp_user_define4"      name="sp_user_define4"      value="" />               <!--[선택]가맹점 필드4 -->
<input type="hidden" id="sp_user_define5"      name="sp_user_define5"      value="" />               <!--[선택]가맹점 필드5 -->
<input type="hidden" id="sp_user_define6"      name="sp_user_define6"      value="" />               <!--[선택]가맹점 필드6 -->
<input type="hidden" id="sp_product_type"      name="sp_product_type"      value="" />               <!--[선택]상품정보구분 -->
<input type="hidden" id="sp_product_expr"      name="sp_product_expr"      value="" />               <!--[선택]서비스 기간 -->
<input type="hidden" id="sp_app_scheme"        name="sp_app_scheme"        value="" />               <!--[선택]가맹점 APP scheme -->

<!--신용카드-->
<input type="hidden" id="sp_usedcard_code"     name="sp_usedcard_code"     value="" />               <!--[선택]사용가능카드 LIST -->
<input type="hidden" id="sp_quota"             name="sp_quota"             value="" />               <!--[선택]할부개월 -->
<input type="hidden" id="sp_os_cert_flag"      name="sp_os_cert_flag"      value="" />               <!--[선택]해외안심클릭 사용여부-->
<input type="hidden" id="sp_noinst_flag"       name="sp_noinst_flag"       value="" />               <!--[선택]무이자 여부(Y/N)-->
<input type="hidden" id="sp_noinst_term"       name="sp_noinst_term"       value="" />               <!--[선택]무이자 기간 -->
<input type="hidden" id="sp_set_point_card_yn" name="sp_set_point_card_yn" value="" />               <!--[선택]카드사포인트 사용여부(Y/N)-->
<input type="hidden" id="sp_point_card"        name="sp_point_card"        value="" />               <!--[선택]포인트카드 LIST(카드코드-더할 할부개월) -->
<input type="hidden" id="sp_join_cd"           name="sp_join_cd"           value="" />               <!--[선택]조인코드 -->

<!--가상계좌-->
<input type="hidden" id="sp_vacct_bank"       name="sp_vacct_bank"         value="" />               <!--[선택]가상계좌 사용가능한 은행 LIST -->
<input type="hidden" id="sp_vacct_end_date"   name="sp_vacct_end_date"     value="" />               <!--[선택]입금 만료 날짜 -->
<input type="hidden" id="sp_vacct_end_time"   name="sp_vacct_end_time"     value="" />               <!--[선택]입금 만료 시간 -->

<!--선불카드-->
<input type="hidden" id="sp_prepaid_cp"       name="sp_prepaid_cp"         value="" />               <!--[선택]선불카드 CP -->

<!-- [END] 인증요청 필드  -->

<!-- [START] 인증응답 필드 -->

<!--공통-->
<input type="hidden" id="sp_res_cd"              name="sp_res_cd"                value="" />         <!-- [필수]응답코드        -->
<input type="hidden" id="sp_res_msg"             name="sp_res_msg"               value="" />         <!-- [필수]응답메시지      -->
<input type="hidden" id="sp_tr_cd"               name="sp_tr_cd"                 value="" />         <!-- [필수]결제창 요청구분 -->
<input type="hidden" id="sp_ret_pay_type"        name="sp_ret_pay_type"          value="" />         <!-- [필수]결제수단        -->
<input type="hidden" id="sp_trace_no"            name="sp_trace_no"              value="" />         <!-- [선택]추적번호        -->
<!-- 가맹점 주문번호 인증요청 필드에 존재.                                                                [필수]가맹점 주문번호 -->
<input type="hidden" id="sp_sessionkey"          name="sp_sessionkey"            value="" />         <!-- [필수]세션키          -->
<input type="hidden" id="sp_encrypt_data"        name="sp_encrypt_data"          value="" />         <!-- [필수]암호화전문      -->
<!-- 가맹점 ID  인증요청 필드에 존재.                                                                     [필수]가맹점 ID       -->
<input type="hidden" id="sp_mobilereserved1"     name="sp_mobilereserved1"       value="" />         <!-- [선택]여유필드        -->
<input type="hidden" id="sp_mobilereserved2"     name="sp_mobilereserved2"       value="" />         <!-- [선택]여유필드        -->
<input type="hidden" id="sp_reserved1"           name="sp_reserved1"             value="" />         <!-- [선택]여유필드        -->
<input type="hidden" id="sp_reserved2"           name="sp_reserved2"             value="" />         <!-- [선택]여유필드        -->
<input type="hidden" id="sp_reserved3"           name="sp_reserved3"             value="" />         <!-- [선택]여유필드        -->
<input type="hidden" id="sp_reserved4"           name="sp_reserved4"             value="" />         <!-- [선택]여유필드        -->

<!--신용카드-->
<input type="hidden" id="sp_card_code"            name="sp_card_code"            value="" />         <!-- [필수]카드코드               -->
<input type="hidden" id="sp_eci_code"             name="sp_eci_code"             value="" />         <!-- [선택]ECI코드(MPI인 경우)    -->
<input type="hidden" id="sp_card_req_type"        name="sp_card_req_type"        value="" />         <!-- [필수]거래구분               -->
<input type="hidden" id="sp_save_useyn"           name="sp_save_useyn"           value="" />         <!-- [선택]카드사 세이브 여부     -->
<input type="hidden" id="sp_card_prefix"          name="sp_card_prefix"          value="" />         <!-- [선택]신용카드 Prefix        -->
<input type="hidden" id="sp_card_no_7"            name="sp_card_no_7"            value="" />         <!-- [선택]신용카드번호 앞7자리   -->

<!--간편결제-->
<input type="hidden" id="sp_spay_cp"              name="sp_spay_cp"              value="" />          <!-- [선택]간편결제 CP코드 -->

<!--선불카드-->
<input type="hidden" id="sp_prepaid_cp"           name="sp_prepaid_cp"           value="" />          <!-- [선택]선불카드 CP코드 -->

<!-- [END] 인증응답 필드  -->

<div id="div_mall">
   <div class="contents1">
            <div class="con1">
                <p>
                    <img src='../img/common/logo.png' height="19" alt="Easypay">
                </p>
            </div>
            <div class="con1t1">
                <p>EP8.0 Webpay Mobile<br>주문 페이지</p>
            </div>
    </div>
    <div class="contents">
        <section class="section00 bg_skyblue">
            <fieldset>
                <legend>주문</legend>
                <br>
                <div class="roundTable">
                    <table width="100%" class="table_roundList" cellpadding="5">
                        <!-- [START] 인증요청 필드 -->
                        <tbody>
                            <tr>
                                <td colspan="2" align="center">일반(필수: *표시)</td>
                            </tr>
                            <!-- [START] 공통 -->
                             <tr>
                                <td>가맹점 ID(*)</td>
                                <td><input type='text' name="sp_mall_id" id="sp_mall_id" style="width:180px;" value=""></td> <!-- 가맹점 ID(*) -->
                            </tr>
                            <tr>
                                <td>결제수단(*)</td>
                                <td>
                                    <select name="sp_pay_type" id="sp_pay_type">
                                        <option value="11" selected>신용카드</option>
                                        <option value="21">계좌이체</option>
                                        <option value="22">가상계좌</option>
                                        <option value="31">휴대폰</option>
                                        <option value="50">선불결제</option>
                                        <option value="60">간편결제</option>
                                        <option value="81">배치인증</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>인증타입</td>
                                <td>
                                    <select name="sp_cert_type" id="sp_cert_type">
                                        <option value="" selected>일반</option>
                                        <option value="0">인증</option>
                                        <option value="1">비인증</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>상품명(*)</td>
                                <td><input type='text' name="sp_product_nm" id="sp_product_nm" style="width:180px;" value=""></td>
                            </tr>
                            <tr>
                                <td>상품금액(*)</td>
                                <td><input type='text' name="sp_product_amt"  id="sp_product_amt" style="width:180px;" value=""></td>
                            </tr>
                            <tr>
                                <td>윈도우타입</td>
                                <td>
                                    <select name="sp_window_type" id="sp_window_type">
                                        <option value="submit" selected>submit</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>현금영수증 화면표시여부</td>
                                <td>
                                    <select name="sp_disp_cash_yn" id="sp_disp_cash_yn">
                                        <option value="" selected>DB조회</option>
                                        <option value="N">미표시</option>
                                    </select>
                                </td>
                            </tr>
                            <!-- [END] 공통 -->
                            <tr>
                                <td>국민앱카드 사용유무</td>
                                <td>
                                    <select name="sp_kmotion_useyn" id="sp_kmotion_useyn">
                                        <option value="Y" >사용</option>
                                        <option value="N" >미사용</option>
                                        <option value="" selected>DB조회</option>
                                    </select>
                                </td>
                            </tr>
                    </tbody>
                    </table>
                    <!-- [END] 인증요청 필드  -->
                </div><br>
            </fieldset>
           <div class="btnMidNext" align="center"><!-- //button guide에서 button 참고하여 작성 -->
              <a href="javascript:f_cert();" class="btnBox_blue"><span class="btnWhiteVlines">다음</span></a>
          </div>
        </section>
    </div>
</div><br>
<footer class="center margin_b12">
  <p>
      <img src='../img/common/k-logo.gif' width="50" height="9" alt="kicc"> <span class="cop1">Copyrightⓒ 2016 KICC All right reserved</span>
  </p>
</footer>
</form>
</body>
</html>
