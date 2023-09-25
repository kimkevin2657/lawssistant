  <?php

  /* 
  -----------------------------------------------------------------------------------
  알림톡 전송
  -----------------------------------------------------------------------------------
  버튼의 경우 템플릿에 버튼이 있을때만 버튼 파라메더를 입력하셔야 합니다.
  버튼이 없는 템플릿인 경우 버튼 파라메더를 제외하시기 바랍니다.
  */

  $_apiURL    =	'https://kakaoapi.aligo.in/akv10/alimtalk/send/';
  $_hostInfo  =	parse_url($_apiURL);
  $_port      =	(strtolower($_hostInfo['scheme']) == 'https') ? 443 : 80;
  $_variables =	array(
    'apikey'      => 'tcjpb8gb4vkkog5p63608lucnrnci97q', 
    'userid'      => 'ink6067', 
    'token'       => '1df8cbae319b2d6e34f7811681ef75d033db4e4c8a7d4e56ca8aaf6d223519ef0205bb7696681ccfe700f5d654316d9d9af295746f77310465433955ba8aa7b8qE2bT1tAOufK5qJpkRpay6KvZZxTxGxzLdc4Gi3D4f5hjXMyMV1F0AE0zHdVLJkY5SFfMkESOUUk1KJAePVpHw==', 
    'senderkey'   => 'ecea570a946a7b73377b06ffcffd07cfacd7f5b7', 
    'tpl_code'    => 'TE_4306',
    'sender'      => '1661-2550',
    'receiver_1'  => '010-8107-0824',
    'recvname_1'  => '첫번째 알림톡을 전송받을 사용자 명',
    'subject_1'   => '제목',
    'message_1'   => '안녕하세요. #{고객명}님!
    #{쇼핑몰명}
    #{쇼핑몰명}에 가입 해주셔서
    진심으로 감사드립니다.'
  );

  /*

  -----------------------------------------------------------------
  치환자 변수에 대한 처리
  -----------------------------------------------------------------

  등록된 템플릿이 "#{이름}님 안녕하세요?" 일경우
  실제 전송할 메세지 (message_x) 에 들어갈 메세지는
  "홍길동님 안녕하세요?" 입니다.

  카카오톡에서는 전문과 템플릿을 비교하여 치환자이외의 부분이 일치할 경우
  정상적인 메세지로 판단하여 발송처리 하는 관계로
  반드시 개행문자도 템플릿과 동일하게 작성하셔야 합니다.

  예제 : message_1 = "홍길동님 안녕하세요?"

  -----------------------------------------------------------------
  버튼타입이 WL일 경우 (웹링크)
  -----------------------------------------------------------------
  링크정보는 다음과 같으며 버튼도 치환변수를 사용할 수 있습니다.
  {"button":[{"name":"버튼명","linkType":"WL","linkP":"https://www.링크주소.com/?example=12345", "linkM": "https://www.링크주소.com/?example=12345"}]}

  -----------------------------------------------------------------
  버튼타입이 AL 일 경우 (앱링크)
  -----------------------------------------------------------------
  {"button":[{"name":"버튼명","linkType":"AL","linkI":"https://www.링크주소.com/?example=12345", "linkA": "https://www.링크주소.com/?example=12345"}]}

  -----------------------------------------------------------------
  버튼타입이 DS 일 경우 (배송조회)
  -----------------------------------------------------------------
  {"button":[{"name":"버튼명","linkType":"DS"}]}

  -----------------------------------------------------------------
  버튼타입이 BK 일 경우 (봇키워드)
  -----------------------------------------------------------------
  {"button":[{"name":"버튼명","linkType":"BK"}]}

  -----------------------------------------------------------------
  버튼타입이 MD 일 경우 (메세지 전달)
  -----------------------------------------------------------------
  {"button":[{"name":"버튼명","linkType":"MD"}]}

  -----------------------------------------------------------------
  버튼이 여러개 인경우 (WL + DS)
  -----------------------------------------------------------------
  {"button":[{"name":"버튼명","linkType":"WL","linkP":"https://www.링크주소.com/?example=12345", "linkM": "https://www.링크주소.com/?example=12345"}, {"name":"버튼명","linkType":"DS"}]}

  */

  $oCurl = curl_init();
  curl_setopt($oCurl, CURLOPT_PORT, $_port);
  curl_setopt($oCurl, CURLOPT_URL, $_apiURL);
  curl_setopt($oCurl, CURLOPT_POST, 1);
  curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($oCurl, CURLOPT_POSTFIELDS, http_build_query($_variables));
  curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);

  $ret = curl_exec($oCurl);
  $error_msg = curl_error($oCurl);
  curl_close($oCurl);

  // 리턴 JSON 문자열 확인
  print_r($ret . PHP_EOL);

  // JSON 문자열 배열 변환
  $retArr = json_decode($ret);

  // 결과값 출력
  print_r($retArr);

  /*
  code : 0 성공, 나머지 숫자는 에러
  message : 결과 메시지
  */