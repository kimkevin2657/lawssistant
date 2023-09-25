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
     * �Ķ���� üũ �޼ҵ�
     */
    function getNullToSpace($param) 
    {
        return ($param == null) ? "" : $param.trim();
    }
 

    $req_ip     = $_SERVER['REMOTE_ADDR'];                      // �����û�� IP
    $mall_id    = getNullToSpace($_POST["sp_mall_id"]);         // ������ ID
?>

    /* �Ķ���� �ʱⰪ Setting */
    function f_init()
    {           
        var frm_mgr = document.frm_mgr;
         
        frm_mgr.sp_mall_id.value     = "T0001997";              //������ ID
        frm_mgr.mgr_amt.value        = "51004";                 //��ұݾ�  

    }

    function f_submit() {
        
        var frm_mgr = document.frm_mgr;
        
        var bRetVal = false;

        /*  ���������� Ȯ�� */
        if( !frm_mgr.sp_mall_id.value ) {
            alert("������ ���̵� �Է��ϼ���!!");
            frm_mgr.sp_mall_id.focus();
            return;
        }
        
        /*  �������� Ȯ�� */
        if( !frm_mgr.org_cno.value ) {
            alert("PG�ŷ���ȣ�� �Է��ϼ���.");
            frm_mgr.org_cno.focus();
            return;
        }
        
         /*  ȯ��(60),�κ�ȯ��(62)�� ��� üũ */
        if( frm_mgr.mgr_txtype.value == "60" || frm_mgr.mgr_txtype.value == "62") {

            if(frm_mgr.mgr_subtype.value == "RF01"){
                alert("ȯ�ҿ�û�� �Է��ϼ���!!");
                frm_mgr.mgr_subtype.focus();
                return;
            }

            if(!frm_mgr.mgr_amt.value){
                alert("ȯ�ұݾ��� �Է��ϼ���!!");
                frm_mgr.mgr_amt.focus();
                return;
            }

            if(!frm_mgr.mgr_bank_cd.value){
                alert("ȯ�������ڵ带 �Է��ϼ���!!");
                frm_mgr.mgr_bank_cd.focus();
                return;
            }

            if(!frm_mgr.mgr_account.value){
                alert("ȯ�Ұ��¹�ȣ�� �Է��ϼ���!!");
                frm_mgr.mgr_account.focus();
                return;
            }

            if(!frm_mgr.mgr_depositor.value){
                alert("ȯ�ҿ����ָ� �Է��ϼ���!!");
                frm_mgr.mgr_depositor.focus();
                return;
            }
        }

        /*  �κи������(31), ���κκ����(32)�� ��� üũ */
        if( frm_mgr.mgr_txtype.value == "31" || frm_mgr.mgr_txtype.value == "32") {

            if(!frm_mgr.mgr_amt.value){
                alert("��ұݾ��� �Է��ϼ���!!");
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

<!-- [START] �����û �ʵ� -->     <!--  <table>������ �Ϻ� �Ķ���� �����մϴ�.-->
<input type="hidden"     name="sp_tr_cd"            id="sp_tr_cd"          value="00201000">      <!-- [�ʼ�]�ŷ�����(�����Ұ�) -->
<input type="hidden"     name="req_ip"              id="req_ip"            value="<?=$req_ip?>">  <!-- [�ʼ�]��û�� IP          -->
<!-- [END] �����û �ʵ�  --> 
 

<div id="div_mall">
   <div class="contents1">
            <div class="con1">
                <p>
                    <img src='../img/common/logo.png' height="19" alt="Easypay">
                </p>
            </div>
            <div class="con1t1">
                <p>EP8.0 Webpay Mobile<br>���� ������</p>
            </div>
    </div>
    <div class="contents">
        <section class="section00 bg_skyblue">
                <fieldset>
                <legend>����</legend>
                <br>
                <div class="roundTable">
                   <table width="100%" class="table_roundList" cellpadding="5">          
                     <!-- [START] �����û �ʵ� -->   
                     <tbody>
                            <tr>
                                <td colspan="2" align="center">����(�ʼ�: *ǥ��)</td>                            
                            </tr>  
                            <tr>
                                <td>������ ID(*)</td>
                                <td><input type='text' name="sp_mall_id" id="sp_mall_id" style="width:180px;" value="<?=$mall_id?>"></td>
                            </tr>                                
                            <tr>       
                                <td>����ŷ�����(*)</td>
                                <td>
                                    <select name="mgr_txtype" >   
                                        <option value="20" >����</option> 
                                        <option value="31" >�κи������</option>    
                                        <option value="32" >ī��κ����</option>
                                        <option value="33" >���ºκ����</option>                   
                                        <option value="40" selected>������</option>
                                        <option value="60" >ȯ��</option>  
                                        <option value="62" >�κ�ȯ��</option>  
                                    </select>
                                   </td>
                            </tr>
                                                        <tr>
                                <td>PG�ŷ���ȣ(*)</td>
                                <td><input type="text" name="org_cno" id="org_cno" style="width:180px;" ></td>
                            </tr>
                            <tr>                    
                                <td>�������</td>
                                <td><input type="text" name="mgr_msg" id="mgr_msg" style="width:180px;" ></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">��üȯ��/�κ�ȯ�� ��, �ʼ�</td>                            
                            </tr>
                            <tr>       
                                <td>���漼�α���(*)</td>
                                <td>
                                    <select name="mgr_subtype" id="mgr_subtype">      
                                        <option value=""     selected>����</option>  
                                        <option value="RF01" >ȯ�ҿ�û</option>                
                                    </select>
                                </td>
                            </tr>  
                            <tr>
                                <td colspan="2" align="center">�κ����/�κ�ȯ�� ��, �ʼ�</td>                            
                            </tr>
                            <tr>
                                <td>��ұݾ�</td>
                                <td><input type="text" name="mgr_amt" id="mgr_amt" style="width:180px;" ></td>
                            </tr>
                            <tr>
                                <td colspan="2" align="center">��üȯ��/�κ�ȯ�� ��, �ʼ�</td>                            
                            </tr> 
                            <tr>
                                <td>�����ڵ�</td>
                                <td ><input type="text" name="mgr_bank_cd" id="mgr_bank_cd" style="width:180px;" ></td>
                            </tr>
                            <tr>
                                <td>���¹�ȣ</td>
                                <td><input type="text" name="mgr_account" id="mgr_account" style="width:180px;" ></td>
                            </tr>
                            <tr>
                                <td>�����ָ�</td>
                                <td><input type="text" name="mgr_depositor" id="mgr_depositor" style="width:180px;" ></td>
                            </tr>                      
                     </tbody>
                     <!-- [END] �����û �ʵ�  --> 
                   </table>
                </div>
               <br>
           </fieldset>
           <div class="btnMidNext" align="center"><!-- //button guide���� button �����Ͽ� �ۼ� -->
              <a href="javascript:f_submit();" class="btnBox_blue"><span class="btnWhiteVlines">����</span></a>
          </div>
       </section>
  </div>        
</form>
</body>
</html>