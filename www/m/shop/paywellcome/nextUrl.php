<html> 
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<meta name="viewport" content="user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0, width=device-width">
<title>웰컴PG Mobile Sample Page</title>
</head>
<body>
<?php
header('Content-Type: text/html; charset=utf-8');

$P_STATUS 	= $_REQUEST['P_STATUS'];			
$P_TID 		= $_REQUEST['P_TID'];				
$P_REQ_URL 	= $_REQUEST['P_REQ_URL'];			
$P_NOTI 	= $_REQUEST['P_NOTI'];

$P_RMESG1 	= $_REQUEST['P_RMESG1'];			
$P_MID 		= substr($P_TID,10,10);


echo "STATUS==>" . $P_STATUS ."<br>";
if($P_STATUS !='00'){
	echo "P_RMESG1==>" . iconv("EUC-KR","UTF-8",$P_RMESG1) ."<br>";
}
?>

<br><br>
** 인증결과 수신 / 승인요청, 승인결과 표시 샘플 ** <br>
<?php
	if($P_STATUS =='00')
	{
?>

		############ !! 중 요 !! ##############<br>
			아래는 예제 입니다.<br>
			실제 개발 시에는 Http-Socket Back 단 요청으로 보내야 합니다.<br>
			<p></p>
			--------------<br>
			호출  URL : return 받은 P_REQ_URL(only https)<br>
			<?php echo $P_REQ_URL?><br>
			
			필수 파라미터 : P_MID, P_TID<br>
			--------------<br>
		###################################<br>
		 
		인증에 성공하였습니다. <br>
		아래 버튼을 클릭해 승인을 진행하시기 바랍니다.<br> <p></p>
		
		<form id="payForm" method="post" action="<?php echo $P_REQ_URL?>">
			<input type="hidden" name="P_MID" value="<?php echo $P_MID ?>" />
			<input type="hidden" name="P_TID" value="<?php echo $P_TID ?>" />
			<input type="submit" value="승인 요청하기">
		</form>
<?php
	} 
	else {
	?>
		인증에 실패하였습니다.<br>
		실패 사유 : <?echo $P_RMESG1 ?>
<?php 
	}
?>

</body>
</html>