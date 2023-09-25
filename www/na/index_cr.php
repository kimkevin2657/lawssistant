<?php
header("Content-Type: text/html; charset=UTF-8");
define('TB_IS_ADMIN', true);
include_once('./_common.php');
$url = 'http://mallset.store/index_bak.php'; //접속할 url 입력
$header_data = array("User-Agent: Mozilla/5.0 (Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko");
$ch = curl_init(); //curl 사용 전 초기화 필수(curl handle)
curl_setopt($ch, CURLOPT_URL, $url); //URL 지정하기
curl_setopt($ch, CURLOPT_POST, 0); //0이 default 값이며 POST 통신을 위해 1로 설정해야 함
curl_setopt($ch, CURLOPT_HEADER, true);//헤더 정보를 보내도록 함(*필수)
curl_setopt($ch, CURLOPT_HTTPHEADER, $header_data); //header 지정하기
curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1); //이 옵션이 0으로 지정되면 curl_exec의 결과값을 브라우저에 바로 보여줌. 이 값을 1로 하면 결과값을 return하게 되어 변수에 저장 가능
$res = curl_exec ($ch);
curl_close($ch);
$dir = $_SERVER['DOCUMENT_ROOT'];
$herder = explode("<!-- } 전체카테고리 시작 -->", $res);
$herder2 = explode("<!-- 사이트 끝 } -->", $herder[1]);
//$herder2 = explode("<!-- } 새로카테고리 끝 -->", $herder[1]);
$quick = "<?php
		if(!is_mobile()) { // 모바일접속이 아닐때만 노출
			Theme::get_theme_part(MS_THEME_PATH,'/quick.skin.php'); // 퀵메뉴
		}

		if(!defined('_INDEX_')) { // index가 아니면 실행
			echo '<div class=\"cont_inner\">'.PHP_EOL;
		}
		?>";
$mian = explode("<!-- 퀵메뉴 좌측날개 시작 { -->", $herder2[0]);
$mian2 = explode("<!-- } 우측 퀵메뉴 끝 -->", $mian[1]);
$main = $mian[0]."\n".$quick."\n".$mian2[1];
$myfile = fopen($dir."/category.php", "w") or die("Unable to open file!");
$txt = $main;
fwrite($myfile, $txt);
fclose($myfile);
//echo $herder2[0];
echo "완료 되었습니다. ";
echo "<br>";
echo "해당페이지는 쇼핑몰의 첫페이지를 미리 읽어들여서 데이터베이스의 부담을 줄이고 사이트의 속도를 증가시켜주기 위한 메뉴 입니다.  ";
echo "<br>";
echo "메인페이지 디자인 작업시에는 index.php가 아닌 index_bak.php를 이용하신 뒤 작업이 완료되면 해당 메뉴를 클릭하여  ";
echo "<br>";
echo "디자인을 갱신하여 주시면 됩니다.  또한 head.skin.php 부분 디자인 작업시에는 headcr.skin.php의 중복내용을 함께 적용해 주셔야 합니다.  ";
echo "<br>";
echo "head.skin.php의 <!-- } 전체카테고리 시작 --> 주석과 tail.sub.php 파일의 <!-- 사이트 끝 } --> 주석은 지우시면 안됩니다.  ";
echo "<br>";
echo "크롤링을 하기 위한 방향 표식 입니다.  ";
echo "<br>";
echo "감사합니다.    ";
?>