<!--결과페이지-->
<!--메뉴얼 '승인페이지 작성' 승인응답 파라미터 포함.-->

<!DOCTYPE html>
<html style="height: 100%;">
<head>
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="content-type" content="text/html; charset=euc-kr">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, target-densitydpi=medium-dpi" />
<title>EasyPay 8.0 webpay mobile</title>
<link rel="stylesheet" type="text/css" href="./css/easypay.css" />
<link rel="stylesheet" type="text/css" href="./css/board.css" />
</head>
<script type="text/javascript">
<?
	/*
	 * 파라미터 체크 메소드
	 */
	function getNullToSpace($param) 
	{
	    return ($param == null) ? "" : $param.trim();
	}
?>
<?

    $res_cd           = getNullToSpace($_POST["res_cd"]);              //결과코드             
    $res_msg          = getNullToSpace($_POST["res_msg"]);             //결과메시지           
    $cno              = getNullToSpace($_POST["cno"]);                 //PG거래번호           
    $amount           = getNullToSpace($_POST["amount"]);              //총 결제금액          
    $order_no         = getNullToSpace($_POST["order_no"]);            //주문번호             
    $auth_no          = getNullToSpace($_POST["auth_no"]);             //승인번호             
    $tran_date        = getNullToSpace($_POST["tran_date"]);           //승인일시             
    $escrow_yn        = getNullToSpace($_POST["escrow_yn"]);           //에스크로 사용유무    
    $complex_yn       = getNullToSpace($_POST["complex_yn"]);          //복합결제 유무        
    $stat_cd          = getNullToSpace($_POST["stat_cd"]);             //상태코드             
    $stat_msg         = getNullToSpace($_POST["stat_msg"]);            //상태메시지           
    $pay_type         = getNullToSpace($_POST["pay_type"]);            //결제수단           
    $card_no          = getNullToSpace($_POST["card_no"]);             //카드번호             
    $issuer_cd        = getNullToSpace($_POST["issuer_cd"]);           //발급사코드           
    $issuer_nm        = getNullToSpace($_POST["issuer_nm"]);           //발급사명             
    $acquirer_cd      = getNullToSpace($_POST["acquirer_cd"]);         //매입사코드           
    $acquirer_nm      = getNullToSpace($_POST["acquirer_nm"]);         //매입사명             
    $install_period   = getNullToSpace($_POST["install_period"]);      //할부개월             
    $noint            = getNullToSpace($_POST["noint"]);               //무이자여부           
    $part_cancel_yn   = getNullToSpace($_POST["part_cancel_yn"]);      //부분취소 가능여부    
    $card_gubun       = getNullToSpace($_POST["card_gubun"]);          //신용카드 종류        
    $card_biz_gubun   = getNullToSpace($_POST["card_biz_gubun"]);      //신용카드 구분  
    $cpon_flag        = getNullToSpace($_POST["cpon_flag"]);           //쿠폰 사용유무     
    $bank_cd          = getNullToSpace($_POST["bank_cd"]);             //은행코드             
    $bank_nm          = getNullToSpace($_POST["bank_nm"]);             //은행명               
    $account_no       = getNullToSpace($_POST["account_no"]);          //계좌번호             
    $deposit_nm       = getNullToSpace($_POST["deposit_nm"]);          //입금자명             
    $expire_date      = getNullToSpace($_POST["expire_date"]);         //계좌사용만료일       
    $cash_res_cd      = getNullToSpace($_POST["cash_res_cd"]);         //현금영수증 결과코드  
    $cash_res_msg     = getNullToSpace($_POST["cash_res_msg"]);        //현금영수증 결과메세지
    $cash_auth_no     = getNullToSpace($_POST["cash_auth_no"]);        //현금영수증 승인번호  
    $cash_tran_date   = getNullToSpace($_POST["cash_tran_date"]);      //현금영수증 승인일시  
    $cash_issue_type  = getNullToSpace($_POST["cash_issue_type"]);     //현금영수증 발행용도   
    $cash_auth_type   = getNullToSpace($_POST["cash_auth_type"]);      //인증구분             
    $cash_auth_value  = getNullToSpace($_POST["cash_auth_value"]);     //현금영수증 인증번호
    $auth_id          = getNullToSpace($_POST["auth_id"]);             //PhoneID              
    $billid           = getNullToSpace($_POST["billid"]);              //인증번호             
    $mobile_no        = getNullToSpace($_POST["mobile_no"]);           //휴대폰번호           
    $mob_ansim_yn     = getNullToSpace($_POST["mob_ansim_yn"]);        //안심결제 사용유무             
    $cp_cd            = getNullToSpace($_POST["cp_cd"]);               //포인트사/쿠폰사 
    $rem_amt          = getNullToSpace($_POST["rem_amt"]);             //잔액     
    $bk_pay_yn        = getNullToSpace($_POST["bk_pay_yn"]);           //장바구니 결제여부   
    $canc_acq_date    = getNullToSpace($_POST["canc_acq_date"]);       //매입취소일시        
    $canc_date        = getNullToSpace($_POST["canc_date"]);           //취소일시         
    $refund_date      = getNullToSpace($_POST["refund_date"]);         //환불예정일시    

?>
</script>
</head>
<body id="container_skyblue">
<form name="frm_pay" method="post">  
<div id="div_mall">
   <div class="contents1">
            <div class="con1">
                <p>
                    <img src='./img/common/logo.png' height="19" alt="Easypay">
                </p>
            </div>
            <div class="con1t1">
                <p>EP8.0 Webpay Mobile<br>결과 페이지</p>
            </div>
    </div>
    <div class="contents">
        <section class="section00 bg_skyblue">
            <fieldset>
                <legend>주문</legend>
                <br>
                <div class="roundTable">
                    <table width="100%" class="table_roundList" cellpadding="5">          
                        <!-- ##########  인증요청 파라미터 ########## -->   
                        <tbody>
                            <tr>
                                <td colspan="2">결과코드</td>
                                <td class="r">[<?=$res_cd ?>]</td>
                            </tr>
                            <tr>
                                <td colspan="2">결과메세지</td>
                                <td class="r"><?=$res_msg ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">PG거래번호</td>
                                <td class="r"><?=$cno ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">총 결제금액</td>
                                <td class="r"><?=$amount ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">주문번호</td>
                                <td class="r"><?=$order_no ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">승인번호</td>
                                <td class="r"><?=$auth_no ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">승인일시</td>
                                <td class="r"><?=$tran_date ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">에스크로여부</td>
                                <td class="r"><?=$escrow_yn ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">복합결제여부</td>
                                <td class="r"><?=$complex_yn ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">상태코드</td>
                                <td class="r"><?=$stat_cd ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">상태메시지</td>
                                <td class="r"><?=$stat_msg ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">결제수단</td>
                                <td class="r"><?=$pay_type ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">카드번호</td>
                                <td class="r"><?=$card_no ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">발급사</td>
                                <td class="r">[<?=$issuer_cd ?>] <?=$issuer_nm ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">매입사</td>
                                <td class="r">[<?=$acquirer_cd ?>] <?=$acquirer_nm ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">할부개월</td>
                                <td class="r"><?=$install_period ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">무이자여부</td>
                                <td class="r"><?=$noint ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">부분취소 가능여부</td>
                                <td class="r"><?=$part_cancel_yn ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">신용카드종류</td>
                                <td class="r"><?=$card_gubun ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">신용카드구분</td>
                                <td class="r"><?=$card_biz_gubun ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">쿠폰 사용유무</td>
                                <td class="r"><?=$cpon_flag ?></td>
                            </tr>   
                             <tr>
                                <td colspan="2">은행코드</td>
                                <td class="r"><?=$bank_cd ?></td>
                            </tr>  
                             <tr>
                                <td colspan="2">은행명</td>
                                <td class="r"><?=$bank_nm ?></td>
                            </tr>  
                             <tr>
                                <td colspan="2">계좌번호</td>
                                <td class="r"><?=$account_no ?></td>
                            </tr>  
                             <tr>
                                <td colspan="2">입금자명</td>
                                <td class="r"><?=$deposit_nm ?></td>
                            </tr>  
                             <tr>
                                <td colspan="2">계좌사용만료일</td>
                                <td class="r"><?=$expire_date ?></td>
                            </tr>  
                             <tr>
                                <td colspan="2">현금영수증 결과코드</td>
                                <td class="r"><?=$cash_res_cd ?></td>
                            </tr>  
                             <tr>
                                <td colspan="2">현금영수증 결과메세지</td>
                                <td class="r"><?=$cash_res_msg ?></td>
                            </tr>      
                             <tr>
                                <td colspan="2">현금영수증 승인번호</td>
                                <td class="r"><?=$cash_auth_no ?></td>
                            </tr> 
                             <tr>
                                <td colspan="2">현금영수증 승인일시</td>
                                <td class="r"><?=$cash_tran_date ?></td>
                            </tr> 
                             <tr>
                                <td colspan="2">현금영수증 발행용도</td>
                                <td class="r"><?=$cash_issue_type ?></td>
                            </tr> 
                             <tr>
                                <td colspan="2">현금영수증 인증구분</td>
                                <td class="r"><?=$cash_auth_type ?></td>
                            </tr> 
                             <tr>
                                <td colspan="2">현금영수증 인증번호</td>
                                <td class="r"><?=$cash_auth_value ?></td>
                            </tr> 
                            <tr>
                                <td colspan="2">휴대폰 PhoneID</td>
                                <td class="r"><?=$auth_id ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">휴대폰 인증번호</td>
                                <td class="r"><?=$billid ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">휴대폰번호</td>
                                <td class="r"><?=$mobile_no ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">안심결제 사용유무</td>
                                <td class="r"><?=$mob_ansim_yn ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">포인트사/쿠폰사</td>
                                <td class="r"><?=$cp_cd ?></td>
                            </tr>       
                            <tr>
                                <td colspan="2">잔액</td>
                                <td class="r"><?=$rem_amt ?></td>
                            </tr>  
                            <tr>
                                <td colspan="2">장바구니 결제여부</td>
                                <td class="r"><?=$bk_pay_yn ?></td>
                            </tr>                               
                            <tr>
                                <td colspan="2">매입취소일시</td>
                                <td class="r"><?=$canc_acq_date ?></td>
                            </tr>                 
                            <tr>
                                <td colspan="2">취소일시</td>
                                <td class="r"><?=$canc_date ?></td>
                            </tr>    
                            <tr>
                                <td colspan="2">환불예정일시</td>
                                <td class="r"><?=$refund_date ?></td>
                            </tr>                              
                        </tbody>
                    </table>
                    <div class="btnMidNext" align="center">
                    </div>
                </div>
            </fieldset>
        </section>
    </div>
</div>
</body>
</html>