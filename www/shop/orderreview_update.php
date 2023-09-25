<?php
include_once("./_common.php");

if(!$is_member) {
    alert("로그인 후 작성 가능합니다.");
}

if($_POST["token"] && get_session("ss_token") == $_POST["token"]) {
	// 맞으면 세션을 지워 다시 입력폼을 통해서 들어오도록 한다.
	set_session("ss_token", "");
} else {
	alert("잘못된 접근 입니다.");
	exit;
}

$od = get_order($_POST['od_id']);
if(!$od['od_id']) { alert_close("주문정보가 없습니다."); }

$rd = get_review($_POST['od_id']);
if($rd['od_id']) { alert_close("해당 주문건에 등록된 리뷰가 있습니다."); }

$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));
function check_file_ext($filename, $allow_ext) {
  $ext = get_file_ext($filename);
  $allow_ext = explode(";", $allow_ext);
  $sw_allow_ext = false;
  for ($i=0; $i<count($allow_ext); $i++) {
    if ($ext == $allow_ext[$i]) { // 허용하는 확장자라면
      $sw_allow_ext = true;
      break;
    }else{
  	  $sw_allow_ext = false;
    }
 }
 return $sw_allow_ext;
}

function get_file_ext($filename) {
  $type = explode(".", $filename);
  $ext = strtolower($type[count($type)-1]);
  return $ext;
}

$gs_id = trim(strip_tags($_POST['gs_id']));
$seller_id = trim(strip_tags($_POST['seller_id']));
$score = trim(strip_tags($_POST['score']));

if(substr_count($_POST['memo'], "&#") > 50) {
    alert("내용에 올바르지 않은 코드가 다수 포함되어 있습니다.");
}

if(!get_magic_quotes_gpc()) {
	$memo = addslashes($_POST['memo']);
  //$memo = $_POST['memo'];
}
$upload = array();

if ($_FILES['photo_file_1']['error'] == 0){
  $tmp_file  = $_FILES['photo_file_1']['tmp_name'];
  $filesize  = $_FILES['photo_file_1']['size'];
  $filename  = $_FILES['photo_file_1']['name'];
  $filename  = get_safe_filename($filename);

  $loadck = check_file_ext($filename,'jpg;JPG;gif;GIF;png;PNG');

  if (is_uploaded_file($tmp_file) && $loadck) {

    // 프로그램 원래 파일명
    $upload['source'] = $filename;
    $upload['filesize'] = $filesize;

    // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
    $filename = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

    shuffle($chars_array);
    $shuffle = implode('', $chars_array);

    $upload['file'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

    $dest_file = MS_DATA_PATH.'/review/'.$upload['file'];

    // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
    $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['photo_file_1']['error']);

    // 올라간 파일의 퍼미션을 변경합니다.
    chmod($dest_file, "777");
    $imgup1_db = '/data/review/'.$upload['file'];
  } else {
    alert_close("업로드되지 않았거나, 이미지파일이 아닙니다.");
  }
}

if ($_FILES['photo_file_2']['error'] == 0){
  $tmp_file  = $_FILES['photo_file_2']['tmp_name'];
  $filesize  = $_FILES['photo_file_2']['size'];
  $filename  = $_FILES['photo_file_2']['name'];
  $filename  = get_safe_filename($filename);

  $loadck = check_file_ext($filename,'jpg;JPG;gif;GIF;png;PNG');

  if (is_uploaded_file($tmp_file) && $loadck) {

    // 프로그램 원래 파일명
    $upload['source'] = $filename;
    $upload['filesize'] = $filesize;

    // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
    $filename = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

    shuffle($chars_array);
    $shuffle = implode('', $chars_array);

    $upload['file'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

    $dest_file = MS_DATA_PATH.'/review/'.$upload['file'];

    // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
    $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['photo_file_2']['error']);

    // 올라간 파일의 퍼미션을 변경합니다.
    chmod($dest_file, "777");
    $imgup2_db = '/data/review/'.$upload['file'];
  } else {
    alert_close("업로드되지 않았거나, 이미지파일이 아닙니다.");
  }
}

if ($_FILES['photo_file_3']['error'] == 0){
  $tmp_file  = $_FILES['photo_file_3']['tmp_name'];
  $filesize  = $_FILES['photo_file_3']['size'];
  $filename  = $_FILES['photo_file_3']['name'];
  $filename  = get_safe_filename($filename);

  $loadck = check_file_ext($filename,'jpg;JPG;gif;GIF;png;PNG');

  if (is_uploaded_file($tmp_file) && $loadck) {

    // 프로그램 원래 파일명
    $upload['source'] = $filename;
    $upload['filesize'] = $filesize;

    // 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
    $filename = preg_replace("/\.(php|pht|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

    shuffle($chars_array);
    $shuffle = implode('', $chars_array);

    $upload['file'] = abs(ip2long($_SERVER['REMOTE_ADDR'])).'_'.substr($shuffle,0,8).'_'.replace_filename($filename);

    $dest_file = MS_DATA_PATH.'/review/'.$upload['file'];

    // 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
    $error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES['photo_file_3']['error']);

    // 올라간 파일의 퍼미션을 변경합니다.
    chmod($dest_file, "777");
    $imgup3_db = '/data/review/'.$upload['file'];
  } else {
    alert_close("업로드되지 않았거나, 이미지파일이 아닙니다.");
  }
}

// 구매후기 포인트 적립
if(($imgup1_db || $imgup2_db || $imgup3_db) && $config['review_photo_yes']=="1") { 
  if($od['goods_price'] < $config['review_photo_pay1']) {
    if($config['review_photo_per1']) {
      $res_point = ceil(($od['goods_price'] * $config['review_photo_per1']) / 100);
    } else if($config['review_photo_won1']) {
      $res_point = $config['review_photo_won1'];
    }
  } else if($od['goods_price'] >= $config['review_photo_pay2']) {
    if($config['review_photo_per2']) {
      $res_point = ceil(($od['goods_price'] * $config['review_photo_per2']) / 100);
    } else if($config['review_photo_won2']) {
      $res_point = $config['review_photo_won2'];
    }
  }
  $po_content = "포토리뷰";
} else if($memo && $config['review_general_yes']=="1") {
  if($od['goods_price'] < $config['review_general_pay1']) {
    if($config['review_general_per1']) {
      $res_point = ceil(($od['goods_price'] * $config['review_general_per1']) / 100);
    } else if($config['review_general_won1']) {
      $res_point = $config['review_general_won1'];
    }
  } else if($od['goods_price'] >= $config['review_general_pay2']) {
    if($config['review_general_per2']) {
      $res_point = ceil(($od['goods_price'] * $config['review_general_per2']) / 100);
    } else if($config['review_general_won2']) {
      $res_point = $config['review_general_won2'];
    }
  }
  $po_content = "일반리뷰";
}

$sql = "insert into shop_goods_review 
		   set gs_id = '$gs_id', 
			   mb_id = '$member[id]',
         od_id = '$od_id',
			   memo = '$memo',
			   score = '$score',
			   reg_time = '".MS_TIME_YMDHIS."',
			   seller_id = '$seller_id',
         photo_file_1 = '".$imgup1_db."',
         photo_file_2 = '".$imgup2_db."',
         photo_file_3 = '".$imgup3_db."',
			   pt_id = '$pt_id' ";
sql_query($sql);

$wr_id = sql_insert_id();

if($wr_id && ($config['review_general_yes']=="1" || $config['review_photo_yes']=="1") && $res_point > 0) {
  insert_point($member[id], $res_point, $po_content, '@reivew', $member[id], $member['id'].'-'.uniqid(''), $od_id);
  // insert_review_point($mb_id, $gs_id, $od_id, $point)
  insert_review_point($member[id], $gs_id, $od_id, $res_point);
}

sql_query("update shop_goods set m_count = m_count+1 where index_no='$gs_id'");

alert_close("정상적으로 등록 되었습니다.");
?>