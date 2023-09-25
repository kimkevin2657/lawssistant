<?
    include_once("./_common.php");

    $msg = "회원가입이 완료 되었습니다.";
	
	$subject_1 = '회원가입';
	$message_1 = '안녕하세요. '.$member['name'].'님!
	블링뷰티
	
	블링뷰티에 가입 해주셔서
	진심으로 감사드립니다.';

	aligo_sms('TE_4306', $member['cellphone'], $member['name'], $subject_1, $message_1);

?>