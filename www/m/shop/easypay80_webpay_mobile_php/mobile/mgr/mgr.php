<!DOCTYPE html>
<html style="height: 100%;">
<head>  
<meta name="robots" content="noindex, nofollow">
<meta http-equiv="content-type" content="text/html; charset=euc-kr">
<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, target-densitydpi=medium-dpi" />
<title>EasyPay 8.0 webpay mobile</title>
<link rel="stylesheet" type="text/css" href="../css/easypay.css" />
<link rel="stylesheet" type="text/css" href="../css/board.css" />
<script language="javascript" src="../js/default.js" type="text/javascript"></script>
<script type="text/javascript">
<?
     /*
     * 파라미터 체크 메소드
     */
    function getNullToSpace($param) 
    {
        return ($param == null) ? "" : $param.trim();
    }
 

    $req_ip     = $_SERVER['REMOTE_ADDR'];                      // 변경요청자 IP
    $mall_id    = getNullToSpace($_POST["sp_mall_id"]);         // 가맹점 ID
?>

    /* 파라미터 초기값 Setting */
    function f_init()
    {           
        var frm_mgr = document.frm_mgr;
         
        frm_mgr.sp_mall_id.value     = "T0001997";              //가맹점 ID
        frm_mgr.mgr_amt.value        = "51004";                 //취소금액  

    }

    function f_submit() {
        
        var frm_mgr = document.frm_mgr;
        
        var bRetVal = false;

        /*  가맹점정보 확인 */
        if( !frm_mgr.sp_mall_id.value ) {
            alert("가맹점 아이디를 입력하세요!!");
            frm_mgr.sp_mall_id.focus();
            return;
        }
        
        /*  변경정보 확인 */
        if( !frm_mgr.org_cno.value ) {
            alert("PG거래번호를 입력하세요.");
            frm_mgr.org_cno.focus();
            return;
        }
        
         /*  환불(60),부분환불(62)일 경우 체크 */
        if( frm_mgr.mgr_txtype.value == "60" || frm_mgr.mgr_txtype.value == "62") {

            if(frm_mgr.mgr_subtype.value == "RF01"){
                alert("환불요청을 입력하세요!!");
                frm_mgr.mgr_subtype.focus();
                return;
            }

            if(!frm_mgr.mgr_amt.value){
                alert("환불금액을 입력하세요!!");
                frm_mgr.mgr_amt.focus();
                return;
            }

            if(!frm_mgr.mgr_bank_cd.value){
                alert("환불은행코드를 입력하세요!!");
                frm_mgr.mgr_bank_cd.focus();
                return;
            }

            if(!frm_mgr.mgr_account.value){
                alert("환불계좌번호를 입력하세요!!");
                frm_mgr.mgr_account.focus();
                return;
            }

            if(!frm_mgr.mgr_depositor.value){
                alert("환불예금주명를 입력하세요!!");
                frm_mgr.mgr_depositor.focus();
                return;
            }
        }

        /*  부분매입취소(31), 승인부분취소(32)일 경우 체크 */
        if( frm_mgr.mgr_txtype.value == "31" || frm_mgr.mgr_txtype.value == "32") {

            if(!frm_mgr.mgr_amt.value){
                alert("취소금액을 입력하세요!!");
                frm_mgr.mgr_amt.focus();
                return;
            }

        }

        frm_mgr.submit();
    }
</script>
</head>
<body id="container_skyblue" onload="f_init();">
<form name="frm_mgr" method="post" action="../easypay_request.php">  

<!-- [START] 변경요청 필드 -->     <!--  <table>내에도 일부 파라미터 존재합니다.-->
<input type="hidden"     name="sp_tr_cd"            id="sp_tr_cd"          value="00201000">      <!-- [필수]거래구분(수정불가) -->
<input type="hidden"     name="req_ip"              id="req_ip"            value="<?=$req_ip?>">  <!-- [필수]요청자 IP          -->
<!-- [END] 변경요청 필드  --> 
 

<div id="div_mall">
   <div class="contents1">
            <div class="con1">
                <p>
                    <img src='../img/common/logo.png' height="19" alt="Easypay">
                </p>
            </div>
            <div class="con1t1">
                <p>EP8.0 Webpay Mobile<br>변경 페이지</p>
            </div>
    </div>
    <div class="contents">
        <section class="section00 bg_skyblue">
                <fieldset>
                <legend>변경</legend>
                <br>
                <div class="roundTable">
                   <table width="100%" class="table_roundList" cellpadding="5">          
                     <!-- [START] 변경요청 필드 -->   
                     <tbody>
                            <tr>
                                <td colspan="2" align="center">변경(필수: *표시)</td>                            
                            </tr>  
                            <tr>
                                <td>가맹점 ID(*)</td>
                                <td><input type='text' name="sp_mall_id" id="sp_mall_id" style="width:180px;" value="<?=$mall_id?>"></td>
                            </tr>                                
                            <tr>       
                                <td>변경거래구분(*)</td>
                                <td>
                                    <select name="mgr_txtype" >   
                                        <option value="20" >매입</option> 
                                        <option value="31" >부분매입취소</option>    
                                        <option value="32" >카드부분취소</option>
                                        <option value="33" >계좌부분취소</option>                   
                                        <option value="40" selected>즉시취소</option>
                                        <option value="60" >환불</option>  
                                        <option value="62" >부분환불</option>  
                                    </select>
                                   </td>
                            </tr>
                                                        <tr>
                                <td>PG거래번호(*)</td>
                                <td><input type="text" name="org_cno" id="org_cno" style="width:180px;" ></td>
                            </tr>
                            <tr>                    
                                <td>변경사유</td>
                                <td><input type="text" name="mgr_msg" id="mgr_msg" style="width:180px;" ></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">전체환불/부분환불 시, 필수</td>                            
                            </tr>
                            <tr>       
                                <td>변경세부구분(*)</td>
                                <td>
                                    <select name="mgr_subtype" id="mgr_subtype">      
                                        <option value=""     selected>선택</option>  
                                        <option value="RF01" >환불요청</option>                
                                    </select>
                                </td>
                            </tr>  
                            <tr>
                                <td colspan="2" align="center">부분취소/부분환불 시, 필수</td>                            
                            </tr>
                            <tr>
                                <td>취소금액</td>
                                <td><input type="text" name="mgr_amt" id="mgr_amt" style="width:180px;" ></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">전체환불/부분환불 시, 필수</td>                            
                            </tr> 
                            <tr>
                                <td>은행코드</td>
                                <td ><input type="text" name="mgr_bank_cd" id="mgr_bank_cd" style="width:180px;" ></td>
                            </tr>
                            <tr>
                                <td>계좌번호</td>
                                <td><input type="text" name="mgr_account" id="mgr_account" style="width:180px;" ></td>
                            </tr>
                            <tr>
                                <td>예금주명</td>
                                <td><input type="text" name="mgr_depositor" id="mgr_depositor" style="width:180px;" ></td>
                            </tr>                      
                     </tbody>
                     <!-- [END] 변경요청 필드  --> 
                   </table>
                </div>
               <br>
           </fieldset>
           <div class="btnMidNext" align="center"><!-- //button guide에서 button 참고하여 작성 -->
              <a href="javascript:f_submit();" class="btnBox_blue"><span class="btnWhiteVlines">다음</span></a>
          </div>
       </section>
  </div>        
</form>
</body>
</html>