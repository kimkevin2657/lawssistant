<!--���������-->
<!--�޴��� '���������� �ۼ�' �������� �Ķ���� ����.-->

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
	 * �Ķ���� üũ �޼ҵ�
	 */
	function getNullToSpace($param) 
	{
	    return ($param == null) ? "" : $param.trim();
	}
?>
<?

    $res_cd           = getNullToSpace($_POST["res_cd"]);              //����ڵ�             
    $res_msg          = getNullToSpace($_POST["res_msg"]);             //����޽���           
    $cno              = getNullToSpace($_POST["cno"]);                 //PG�ŷ���ȣ           
    $amount           = getNullToSpace($_POST["amount"]);              //�� �����ݾ�          
    $order_no         = getNullToSpace($_POST["order_no"]);            //�ֹ���ȣ             
    $auth_no          = getNullToSpace($_POST["auth_no"]);             //���ι�ȣ             
    $tran_date        = getNullToSpace($_POST["tran_date"]);           //�����Ͻ�             
    $escrow_yn        = getNullToSpace($_POST["escrow_yn"]);           //����ũ�� �������    
    $complex_yn       = getNullToSpace($_POST["complex_yn"]);          //���հ��� ����        
    $stat_cd          = getNullToSpace($_POST["stat_cd"]);             //�����ڵ�             
    $stat_msg         = getNullToSpace($_POST["stat_msg"]);            //���¸޽���           
    $pay_type         = getNullToSpace($_POST["pay_type"]);            //��������           
    $card_no          = getNullToSpace($_POST["card_no"]);             //ī���ȣ             
    $issuer_cd        = getNullToSpace($_POST["issuer_cd"]);           //�߱޻��ڵ�           
    $issuer_nm        = getNullToSpace($_POST["issuer_nm"]);           //�߱޻��             
    $acquirer_cd      = getNullToSpace($_POST["acquirer_cd"]);         //���Ի��ڵ�           
    $acquirer_nm      = getNullToSpace($_POST["acquirer_nm"]);         //���Ի��             
    $install_period   = getNullToSpace($_POST["install_period"]);      //�Һΰ���             
    $noint            = getNullToSpace($_POST["noint"]);               //�����ڿ���           
    $part_cancel_yn   = getNullToSpace($_POST["part_cancel_yn"]);      //�κ���� ���ɿ���    
    $card_gubun       = getNullToSpace($_POST["card_gubun"]);          //�ſ�ī�� ����        
    $card_biz_gubun   = getNullToSpace($_POST["card_biz_gubun"]);      //�ſ�ī�� ����  
    $cpon_flag        = getNullToSpace($_POST["cpon_flag"]);           //���� �������     
    $bank_cd          = getNullToSpace($_POST["bank_cd"]);             //�����ڵ�             
    $bank_nm          = getNullToSpace($_POST["bank_nm"]);             //�����               
    $account_no       = getNullToSpace($_POST["account_no"]);          //���¹�ȣ             
    $deposit_nm       = getNullToSpace($_POST["deposit_nm"]);          //�Ա��ڸ�             
    $expire_date      = getNullToSpace($_POST["expire_date"]);         //���»�븸����       
    $cash_res_cd      = getNullToSpace($_POST["cash_res_cd"]);         //���ݿ����� ����ڵ�  
    $cash_res_msg     = getNullToSpace($_POST["cash_res_msg"]);        //���ݿ����� ����޼���
    $cash_auth_no     = getNullToSpace($_POST["cash_auth_no"]);        //���ݿ����� ���ι�ȣ  
    $cash_tran_date   = getNullToSpace($_POST["cash_tran_date"]);      //���ݿ����� �����Ͻ�  
    $cash_issue_type  = getNullToSpace($_POST["cash_issue_type"]);     //���ݿ����� ����뵵   
    $cash_auth_type   = getNullToSpace($_POST["cash_auth_type"]);      //��������             
    $cash_auth_value  = getNullToSpace($_POST["cash_auth_value"]);     //���ݿ����� ������ȣ
    $auth_id          = getNullToSpace($_POST["auth_id"]);             //PhoneID              
    $billid           = getNullToSpace($_POST["billid"]);              //������ȣ             
    $mobile_no        = getNullToSpace($_POST["mobile_no"]);           //�޴�����ȣ           
    $mob_ansim_yn     = getNullToSpace($_POST["mob_ansim_yn"]);        //�Ƚɰ��� �������             
    $cp_cd            = getNullToSpace($_POST["cp_cd"]);               //����Ʈ��/������ 
    $rem_amt          = getNullToSpace($_POST["rem_amt"]);             //�ܾ�     
    $bk_pay_yn        = getNullToSpace($_POST["bk_pay_yn"]);           //��ٱ��� ��������   
    $canc_acq_date    = getNullToSpace($_POST["canc_acq_date"]);       //��������Ͻ�        
    $canc_date        = getNullToSpace($_POST["canc_date"]);           //����Ͻ�         
    $refund_date      = getNullToSpace($_POST["refund_date"]);         //ȯ�ҿ����Ͻ�    

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
                <p>EP8.0 Webpay Mobile<br>��� ������</p>
            </div>
    </div>
    <div class="contents">
        <section class="section00 bg_skyblue">
            <fieldset>
                <legend>�ֹ�</legend>
                <br>
                <div class="roundTable">
                    <table width="100%" class="table_roundList" cellpadding="5">          
                        <!-- ##########  ������û �Ķ���� ########## -->   
                        <tbody>
                            <tr>
                                <td colspan="2">����ڵ�</td>
                                <td class="r">[<?=$res_cd ?>]</td>
                            </tr>
                            <tr>
                                <td colspan="2">����޼���</td>
                                <td class="r"><?=$res_msg ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">PG�ŷ���ȣ</td>
                                <td class="r"><?=$cno ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">�� �����ݾ�</td>
                                <td class="r"><?=$amount ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">�ֹ���ȣ</td>
                                <td class="r"><?=$order_no ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">���ι�ȣ</td>
                                <td class="r"><?=$auth_no ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">�����Ͻ�</td>
                                <td class="r"><?=$tran_date ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">����ũ�ο���</td>
                                <td class="r"><?=$escrow_yn ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">���հ�������</td>
                                <td class="r"><?=$complex_yn ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">�����ڵ�</td>
                                <td class="r"><?=$stat_cd ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">���¸޽���</td>
                                <td class="r"><?=$stat_msg ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">��������</td>
                                <td class="r"><?=$pay_type ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">ī���ȣ</td>
                                <td class="r"><?=$card_no ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">�߱޻�</td>
                                <td class="r">[<?=$issuer_cd ?>] <?=$issuer_nm ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">���Ի�</td>
                                <td class="r">[<?=$acquirer_cd ?>] <?=$acquirer_nm ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">�Һΰ���</td>
                                <td class="r"><?=$install_period ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">�����ڿ���</td>
                                <td class="r"><?=$noint ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">�κ���� ���ɿ���</td>
                                <td class="r"><?=$part_cancel_yn ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">�ſ�ī������</td>
                                <td class="r"><?=$card_gubun ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">�ſ�ī�屸��</td>
                                <td class="r"><?=$card_biz_gubun ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">���� �������</td>
                                <td class="r"><?=$cpon_flag ?></td>
                            </tr>   
                             <tr>
                                <td colspan="2">�����ڵ�</td>
                                <td class="r"><?=$bank_cd ?></td>
                            </tr>  
                             <tr>
                                <td colspan="2">�����</td>
                                <td class="r"><?=$bank_nm ?></td>
                            </tr>  
                             <tr>
                                <td colspan="2">���¹�ȣ</td>
                                <td class="r"><?=$account_no ?></td>
                            </tr>  
                             <tr>
                                <td colspan="2">�Ա��ڸ�</td>
                                <td class="r"><?=$deposit_nm ?></td>
                            </tr>  
                             <tr>
                                <td colspan="2">���»�븸����</td>
                                <td class="r"><?=$expire_date ?></td>
                            </tr>  
                             <tr>
                                <td colspan="2">���ݿ����� ����ڵ�</td>
                                <td class="r"><?=$cash_res_cd ?></td>
                            </tr>  
                             <tr>
                                <td colspan="2">���ݿ����� ����޼���</td>
                                <td class="r"><?=$cash_res_msg ?></td>
                            </tr>      
                             <tr>
                                <td colspan="2">���ݿ����� ���ι�ȣ</td>
                                <td class="r"><?=$cash_auth_no ?></td>
                            </tr> 
                             <tr>
                                <td colspan="2">���ݿ����� �����Ͻ�</td>
                                <td class="r"><?=$cash_tran_date ?></td>
                            </tr> 
                             <tr>
                                <td colspan="2">���ݿ����� ����뵵</td>
                                <td class="r"><?=$cash_issue_type ?></td>
                            </tr> 
                             <tr>
                                <td colspan="2">���ݿ����� ��������</td>
                                <td class="r"><?=$cash_auth_type ?></td>
                            </tr> 
                             <tr>
                                <td colspan="2">���ݿ����� ������ȣ</td>
                                <td class="r"><?=$cash_auth_value ?></td>
                            </tr> 
                            <tr>
                                <td colspan="2">�޴��� PhoneID</td>
                                <td class="r"><?=$auth_id ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">�޴��� ������ȣ</td>
                                <td class="r"><?=$billid ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">�޴�����ȣ</td>
                                <td class="r"><?=$mobile_no ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">�Ƚɰ��� �������</td>
                                <td class="r"><?=$mob_ansim_yn ?></td>
                            </tr>
                            <tr>
                                <td colspan="2">����Ʈ��/������</td>
                                <td class="r"><?=$cp_cd ?></td>
                            </tr>       
                            <tr>
                                <td colspan="2">�ܾ�</td>
                                <td class="r"><?=$rem_amt ?></td>
                            </tr>  
                            <tr>
                                <td colspan="2">��ٱ��� ��������</td>
                                <td class="r"><?=$bk_pay_yn ?></td>
                            </tr>                               
                            <tr>
                                <td colspan="2">��������Ͻ�</td>
                                <td class="r"><?=$canc_acq_date ?></td>
                            </tr>                 
                            <tr>
                                <td colspan="2">����Ͻ�</td>
                                <td class="r"><?=$canc_date ?></td>
                            </tr>    
                            <tr>
                                <td colspan="2">ȯ�ҿ����Ͻ�</td>
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