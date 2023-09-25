<?php
// 한달의 총 날짜 계산 함수
function wz_max_day($i_month, $i_year) {
    $day = 1;
    while(checkdate($i_month, $day, $i_year)) {
        $day++;
    }
    $day--;
    return $day;
}

// 날짜구하기
function wz_get_addday($day, $add) {
    $day    = preg_replace('/[^0-9]/', '', $day);
    $y      = substr( $day, 0, 4 );
    $m      = substr( $day, 4, 2 );
    $d      = (int)substr( $day, 6, 2 );

    if ($add >= 0) {
        return date("Y-m-d", mktime(0,0,0, $m, ($d+$add), $y));
    }
    else {
        if ($d > $add) {
            return date("Y-m-d", mktime(0,0,0, $m, ($d+$add), $y));
        }
        else {
            return date("Y-m-d", mktime(0,0,0, $m, ($d-$add), $y));
        }
    }
}

 //날짜 사이의 일수를 구한다.
function wz_date_between($date1, $date2) {
    $retval = intval((strtotime($date2) - strtotime($date1)) / 86400);
    return $retval;
}

// 이메일주소 유효성검사
function wz_get_email_address($emails) {
    preg_match("/[0-9a-z._-]+@[a-z0-9._-]{4,}/i", $emails, $matches);
    return $matches[0];
}

// 한글날짜로 리턴
function wz_get_hangul_date($date) {
    $date = str_replace('-', '', $date);
    return preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\1년\\2월\\3일", $date);
}

// 한글날짜로 리턴
function wz_get_hangul_date_md($date) {
    $date = str_replace('-', '', $date);
    return preg_replace("/([0-9]{4})([0-9]{2})([0-9]{2})/", "\\2/\\3", $date);
}

// 한글시간으로 리턴
function wz_get_hangul_time_hm($time) {
    return preg_replace("/([0-9]{2}):([0-9]{2})/", "\\1시\\2분", $time);
}

// 날짜항목을 경과된 시간으로 표시
function wz_convert_time_last($last_time) {

    $ntime  = strtotime(G5_TIME_YMDHIS) - strtotime($last_time);
    $days   = floor($ntime / 86400);
    $time   = $ntime - ($days * 86400);
    $hours  = floor($time / 3600);
    $time   = $time - ($hours * 3600);
    $min    = floor($time / 60);
    $sec    = $time - ($min * 60);

    if ($days == 0 && $hours == 0 && $min == 0)
        return $sec.'초';
    elseif ($days == 0 && $hours == 0)
        return $min.'분';
    elseif ($days == 0)
        return $hours.'시간';
    else
        return $days.'일';
}

function wz_get_paging($write_pages, $cur_page, $total_page, $url, $add="")
{
    $url = preg_replace('#&amp;page=[0-9]*#', '', $url) . '&amp;page=';

    $str = '';
    if ($cur_page > 1) {
        $str .= '<li><a href="'.$url.'1'.$add.'" aria-label="맨앞으로이동"><span aria-hidden="true"><i class="fa fa-angle-double-left"></i></span></a></li>'.PHP_EOL;
    }

    $start_page = ( ( (int)( ($cur_page - 1 ) / $write_pages ) ) * $write_pages ) + 1;
    $end_page = $start_page + $write_pages - 1;

    if ($end_page >= $total_page) $end_page = $total_page;

    if ($start_page > 1) $str .= '<li><a href="'.$url.($start_page-1).$add.'" aria-label="이전 '.$cur_page.'페이지"><span aria-hidden="true"><i class="fa fa-angle-left"></i></span></a></li>'.PHP_EOL;

    if ($total_page > 1) {
        for ($k=$start_page;$k<=$end_page;$k++) {
            if ($cur_page != $k)
                $str .= '<li><a href="'.$url.$k.$add.'">'.$k.'<span class="sr-only">페이지</span></a></li>'.PHP_EOL;
            else
                $str .= '<li class="active"><a href="#none"><span class="sr-only">열린</span>'.$k.'<span class="sr-only">페이지</span></a></li>'.PHP_EOL;
        }
    }

    if ($total_page > $end_page) $str .= '<li><a href="'.$url.($end_page+1).$add.'" aria-label="다음 '.$cur_page.'페이지"><span aria-hidden="true"><i class="fa fa-angle-right"></i></span></a></li>'.PHP_EOL;

    if ($cur_page < $total_page) {
        $str .= '<li><a href="'.$url.$total_page.$add.'" aria-label="맨뒤로이동"><span aria-hidden="true"><i class="fa fa-angle-double-right"></i></span></a></li>'.PHP_EOL;
    }

    if ($str)
        return '<nav class="text-center"><ul class="pagination">'.$str.'</ul></nav>';
    else
        return '';
}

// 관리자 화면 *********************************

// 시간이 비어 있는지 검사
function wz_is_null_time($datetime)
{
    // 공란 0 : - 제거
    $datetime = preg_replace("/[ 0:-]/", "", $datetime);
    if ($datetime == "")
        return true;
    else
        return false;
}

function wz_file_upload($upload_file_path, $upload_max_size, $fobj, $upload_permit_ext, $upload_name_type = 'edit' ) {

	$upload = array();

	$chars_array = array_merge(range(0,9), range('a','z'), range('A','Z'));

	for ($i=0; $i<count($_FILES[$fobj][name]); $i++) {

		$tmp_file  = $_FILES[$fobj][tmp_name][$i];
		$filesize  = $_FILES[$fobj][size][$i];
		$filename  = $_FILES[$fobj][name][$i];
		$filename  = preg_replace('/(\s|\<|\>|\=|\(|\))/', '_', $filename);

		// 서버에 설정된 값보다 큰파일을 업로드 한다면
		if ($filename) {
			if ($_FILES[$fobj][error][$i] != 0) {
				echo "<script type=text/javascript>
				<!--
					alert(' \'". $filename ."\'  파일이 정상적으로 업로드 되지 않았습니다.');
					history.go(-1);
				//-->
				</script>";
				exit;
			}
		}

		if (is_uploaded_file($tmp_file)) {

			if ($filesize > $upload_max_size) {
				echo "<script type=text/javascript>
				<!--
					alert(' \'". $filename ."\' 파일의 용량(". number_format($filesize) ." 바이트)이 설정(". number_format($upload_max_size) ." 바이트)된 값보다 크므로 업로드 하지 않습니다.');
					history.go(-1);
				//-->
				</script>";
				exit;
			}

			if ( $upload_permit_ext != "*" && !preg_match("/\.(". $upload_permit_ext .")$/i", $filename) ) {
				echo "<script type=text/javascript>
				<!--
					alert('허용된 파일이 아닙니다.');
					history.go(-1);
				//-->
				</script>";
				exit;
			}

			$upload[$i]["filename_org"] = $filename; // 프로그램 원래 파일명
			$upload[$i]["filesize"] = $filesize;

			// 아래의 문자열이 들어간 파일은 -x 를 붙여서 웹경로를 알더라도 실행을 하지 못하도록 함
			$filename = preg_replace("/\.(php|phtm|htm|cgi|pl|exe|jsp|asp|inc)/i", "$0-x", $filename);

			// 파일명 변경처리.
			if ($upload_name_type == "edit") {
				shuffle($chars_array);
				$shuffle = implode("", $chars_array); // 랜덤문자열생성
				$uploadName = abs(ip2long($_SERVER[REMOTE_ADDR])).'_'.substr($shuffle,0,8).'_'.str_replace('%', '', urlencode(str_replace(' ', '_', $filename)));
			}
			else {
				$uploadName = iconv('UTF-8', 'EUC-KR', $filename);
			}

			// 디렉토리 생성.
			@mkdir($upload_file_path, 0707);
			@chmod($upload_file_path, 0707);

			// 파일명 중복확인.
			$uploadNameTmp = $uploadName;
			while(file_exists($upload_file_path .DIRECTORY_SEPARATOR. $uploadName))
			{
				$a++;
				$ext = explode(".", $uploadNameTmp);
				$ext[0] = $ext[0] ."(". $a .")";
				$uploadName = join(".", $ext);
			}

			$upload[$i]["filename"] = $uploadName;
			$dest_file = $upload_file_path . $upload[$i]["filename"];


			// 업로드가 안된다면 에러메세지 출력하고 죽어버립니다.
			$error_code = move_uploaded_file($tmp_file, $dest_file) or die($_FILES[$fobj][error][$i]);

			// 올라간 파일의 퍼미션을 변경합니다.
			chmod($dest_file, 0606);

			// 출력을 위한 인코딩 변경 처리.
			if ($upload_name_type == "edit") {

			}
			else {
				$upload[$i]["filename"] = iconv('EUC-KR', 'UTF-8', $upload[$i]["filename"]);
			}


		}

	}

	return $upload;

}

// 구버전사용자를 위함.
if (!function_exists('check_admin_token')) {
    function check_admin_token() {

    }
}

// 포인트목록
function wz_point_list() {

    global $g5;

    $arr_db = array();
    $query = "select * from {$g5['wpot_config_point_table']} order by cfp_price asc ";
    $res = sql_query($query);
    while($row = sql_fetch_array($res)) {
        $arr_db[] = $row;
    }
    if ($res) sql_free_result($res);

    return $arr_db;
}

// 결제모듈경로를 얻는다
function wz_get_gender_dir()
{
    global $g5;

    $result_array = array();

    $dirname = WPOT_PLUGIN_PATH.'/gender/';
    if(!is_dir($dirname))
        return;

    $handle = opendir($dirname);
    while ($file = readdir($handle)) {
        if($file == '.'||$file == '..') continue;

        if (is_dir($dirname.$file)) $result_array[] = $file;
    }
    closedir($handle);
    sort($result_array);

    return $result_array;
}