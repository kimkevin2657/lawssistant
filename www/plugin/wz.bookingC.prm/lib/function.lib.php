<?php

$weekstr = array('일', '월', '화', '수', '목', '금', '토');

class wz_calendar {

    public $sch_year = '';
    public $sch_month = '';
    public $total_day = '';
    public $year_prev = '';
    public $month_prev = '';
    public $year_next = '';
    public $month_next = '';
    public $today = '';
    public $sch_day = '';
    public $sch_month_mm = '';
    public $first_day = '';

    function __construct($sch_year='', $sch_month='', $sch_day='') {

        $sch_year   = preg_match("/^([0-9]{4})$/", $sch_year) ? (int)$sch_year : (int)substr(G5_TIME_YMD,0,4);
        $sch_month  = preg_match("/^([0-9]{1,2})$/", $sch_month) ? (int)$sch_month : (int)substr(G5_TIME_YMD,5,2);
        $sch_day    = preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $sch_day) ? $sch_day : '';

        // 지난달과 다음달을 보는 루틴
        $year_p = $sch_year - 1;
        $year_n = $sch_year + 1;

        if($sch_month == 1) {
            $year_prev	= $year_p;
            $year_next	= $sch_year;
            $month_prev	= 12;
            $month_next	= $sch_month + 1;
        }
        else if($sch_month == 12) {
            $year_prev	= $sch_year;
            $year_next	= $year_n;
            $month_prev	= $sch_month - 1;
            $month_next	= 1;
        }
        else if($sch_month != 1 && $sch_month != 12) {
            $year_prev	= $sch_year;
            $year_next	= $sch_year;
            $month_prev	= $sch_month - 1;
            $month_next	= $sch_month + 1;
        }

        $this->sch_year     = $sch_year;
        $this->sch_month    = $sch_month;
        $this->year_prev    = $year_prev;
        $this->year_next    = $year_next;
        $this->month_prev   = $month_prev;
        $this->month_next   = $month_next;
        $this->today        = G5_TIME_YMD;
        $this->sch_month_mm = sprintf("%02d", $sch_month);
        $this->first_day    = date('w', mktime(0, 0, 0, $this->sch_month, 1, $this->sch_year));

        $this->max_day($sch_year, $sch_month);
        $this->set_sch_day($sch_day);
    }

    function max_day($i_year, $i_month) {

        $day = 1;

        while(checkdate($i_month, $day, $i_year))
            $day++;

        $day--;

        $this->total_day = $day;
    }

    function set_sch_day($sch_day='') {

        if (isset($sch_day) && $sch_day)
            ;
        else
            $sch_day = $this->today;

        $this->sch_day = $sch_day;
    }

    function day_class($sch_day, $count) {

        if ($sch_day == $this->sch_day) {
            $class = 'danger';
        }
        elseif ($sch_day == $this->today) { // 오늘 표시
            $class = 'success';
        }
        else { // 오늘이 아니면...
            if ($count == 0) // 일요일
                $class = 'box-sun';
            elseif ($count == 6) // 토요일
                $class = 'box-sat';
            else // 평일
                $class = '';
        }



        return $class;
    }

    function day_class_sch($sch_day, $count) {

        if ($sch_day == $this->sch_day) { // 오늘 표시
            $class = 'success';
        }
        else { // 오늘이 아니면...
            if ($count == 0) // 일요일
                $class = 'box-sun';
            elseif ($count == 6) // 토요일
                $class = 'box-sat';
            else // 평일
                $class = '';
        }

        return $class;
    }

    function holiday_list($cp_ix='') { // 공휴일정보

        global $g5;

        $ho = array();

        $query = "select
                        hd_ix, hd_day, hd_subject, hd_loop_year
                    from {$g5['wzb_holiday_table']}
                    where (cp_ix = '{$cp_ix}' or cp_ix = 0)
                    and (hd_year = '".$this->sch_year."' and hd_month = '".$this->sch_month_mm."' or (hd_loop_year = 1 and hd_month = '".$this->sch_month_mm."')) ";
        $res = sql_query($query);
        while($row = sql_fetch_array($res)) {
            $ho[$row['hd_day']] = $row;
        }
        if ($res) sql_free_result($res);

        return $ho;
    }
}

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

// 예약가능한 수량 계산
function wz_check_time_room($rm_ix, $rm_date, $rm_time) {

    global $g5, $wzpconfig, $wzdc;

    $rm_ix   = (int)$rm_ix;
    $rm_date = preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $rm_date) ? $rm_date : '';
    $rm_time = preg_match("/([0-9]{2}):([0-9]{2})/", $rm_time) ? $rm_time : '';
    if (!$rm_ix || !$rm_date || !$rm_time) {
        return 0;
    }

    $rms_year   = substr($rm_date, 0, 4);
    $rms_month  = substr($rm_date, 5, 2);
    $rms_day    = substr($rm_date, 8);
    $rms_week   = date('w', strtotime($rm_date));

    $query = "select * from {$g5['wzb_room_table']} where rm_ix = '$rm_ix' and rm_use = 1 ";
    $rm = sql_fetch($query);

    if (!$rm['rm_holiday_use']) { // 공휴일 예약허용일경우 해당일이 공휴일인지 확인
        $query = "select
                        hd_ix
                    from {$g5['wzb_holiday_table']}
                    where (cp_ix = '".$wzdc['cp_ix']."' or cp_ix = 0)
                    and (hd_date = '".$rm_date."' or (hd_loop_year = 1 and hd_month = '".$rms_month."' and hd_day = '".$rms_day."')) ";
        $hd = sql_fetch($query);
        if ($hd['hd_ix']) {
            return 0;
        }
    }

    if (!$rm['rm_week'.$rms_week]) { // 예약가능한 요일이 아닐경우
        return 0;
    }

    // 예약가능한 최대수량
    $rmt_max_cnt = 0;
    $query = " select rmt_max_cnt from {$g5['wzb_room_time_table']} where rm_ix = '".$rm_ix."' and rmt_time = '".$rm_time."' ";
    $row = sql_fetch($query);
    if ($row['rmt_max_cnt']) {
        $rmt_max_cnt = $row['rmt_max_cnt'];
    }
    else {
        return 0;
    }

    // 잔여예약수량 확인
    // 2019-01-07 : group by rm_ix 추가
    $query = " select ifnull(sum(rms_cnt), 0) as rms_cnt from {$g5['wzb_room_status_table']} where rm_ix = '".$rm_ix."' and rms_date = '".$rm_date."' and rms_time = '".$rm_time."' and rms_status <> '취소' group by rm_ix ";
    $row = sql_fetch($query);
    $rmt_max_cnt = $rmt_max_cnt - $row['rms_cnt'];

    // 만약 한타임당 한팀만 받는경우 아래에서 처리
    /*
    if ($row['rms_cnt'] > 0) {

    }
    */

    return $rmt_max_cnt;
}

// 요금계산
function wz_calculate_price($rm_ix = 0, $rm_date = '', $rm_time = '') {

    global $g5;

    $rm_ix   = (int)$rm_ix;
    $rm_date = preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $rm_date) ? $rm_date : '';
    $rm_time = preg_match("/([0-9]{2}):([0-9]{2})/", $rm_time) ? $rm_time : '';
    if (!$rm_ix || !$rm_date || !$rm_time) {
        return false;
    }

    $rms_month  = substr($rm_date, 5, 2);
    $rms_day    = substr($rm_date, 8);

    $return = array();

    $query = " select rmt_price, rmt_price_type from {$g5['wzb_room_time_table']} where rm_ix = '".$rm_ix."' and rmt_time = '".$rm_time."' ";
    $row = sql_fetch($query);
    $return['price']      = $row['rmt_price'];
    $return['price_type'] = $row['rmt_price_type'];

    // 시설 최우선적용정보
    $query = "  select rmp_ix, rmp_price from {$g5['wzb_room_extend_price_table']}
                where rm_ix = '".$rm_ix."'
                and ((rmp_date = '".$rm_date."' and rmp_time = '".$rm_time."') or (rmp_loop_year = 1 and rmp_month = '".$rms_month."' and rmp_day = '".$rms_day."' and rmp_time = '".$rm_time."')) ";
    $rmp = sql_fetch($query);
    if ($rmp['rmp_ix']) { // 시설요금을 강제로 적용했을경우 최우선 적용
        $return['price']  = $rmp['rmp_price'];
    }

    return $return;
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

// 설정된 시간이 지나면 예약대기건은 자동으로 취소처리.
function wz_ready_order_cancel() {

    global $g5, $config, $default, $wzpconfig;

    include_once(G5_SMS5_PATH.'/sms5.lib.php');
    include_once(WZB_PLUGIN_PATH.'/lib/sms.lib.php');

    include_once(G5_LIB_PATH.'/mailer.lib.php');
    include_once(WZB_PLUGIN_PATH.'/lib/mail.lib.php');

    $query = "select bk_ix from {$g5['wzb_booking_table']} where bk_status = '대기' and date_add(bk_time, interval ".($wzpconfig['pn_wating_time'] ? $wzpconfig['pn_wating_time'] : 6)." hour) < now() ";
    $res = sql_query($query);
    while($row = sql_fetch_array($res)) {

        // 객실예약정보 변경
        $query = " update {$g5['wzb_booking_table']} set bk_status = '취소', bk_cancel_time = '".G5_TIME_YMDHIS."', bk_cancel_ip = '".$_SERVER['REMOTE_ADDR']."', bk_cancel_pos = 'auto' where bk_ix = '".$row['bk_ix']."' ";
        sql_query($query);

        // 객실상태정보 변경
        $query = "update {$g5['wzb_room_status_table']} set rms_status = '취소' where bk_ix = '".$row['bk_ix']."' ";
        sql_query($query);

        // 예약자에게 자동취소처리 내역 전송 (mail, sms)
        $wzsms = new wz_sms($row['bk_ix']);
        $wzsms->wz_send();

        $wzmail = new wz_mail($row['bk_ix']);
        $wzmail->wz_send();
    }
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

// 업체목록
function wz_corp_list($status=false, $sort='name') {

    global $g5;

    $sql_common = "from {$g5['wzb_corp_table']} where (1) ";
    if ($status) {
        $sql_common .= " and cp_status = '1' ";
    }

    $sql_order = "";
    if ($sort == 'name') {
        $sql_order .= " order by cp_title asc ";
    }

    $arr_db = array();
    $query = "select cp_ix, cp_code, cp_ix, cp_title, cp_status {$sql_common} {$sql_order} ";
    $res = sql_query($query);
    while($row = sql_fetch_array($res)) {
        $arr_db[] = $row;
    }
    if ($res) sql_free_result($res);

    return $arr_db;
}

// 이용서비스목록
function wz_room_list($cp_ix=0, $status=false, $sort='sort', $store_mb_id = '') {

    global $g5;

    $cp_ix = (int)$cp_ix;
    if (empty($cp_ix) || !$cp_ix) {
        return false;
    }

    $sql_common = "from {$g5['wzb_room_table']} where cp_ix = '".$cp_ix."' ";
    if ($status) {
        $sql_common .= " and rm_use = '1' ";
    }

    if($store_mb_id){
        $sql_search = " and store_mb_id = '{$store_mb_id}' ";
    }

    $sql_order = "";
    if ($sort == 'sort') {
        $sql_order .= " order by rm_sort asc, rm_ix desc ";
    }

    $arr_db = array();
    $query = "select * {$sql_common} {$sql_search} {$sql_order} ";
    $res = sql_query($query);
    while($row = sql_fetch_array($res)) {
        $arr_db[] = $row;
    }
    if ($res) sql_free_result($res);

    return $arr_db;
}

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