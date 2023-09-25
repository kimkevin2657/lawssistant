<?php
include_once('./_common.php');
include_once('./config.php');
include_once('./lib/function.lib.php');

$sch_type = preg_match("/^(direct|check)$/i", $sch_type) ? $sch_type : 'direct';

if (isset($_GET['sch_year']) && $_GET['sch_year'])
    $sch_year = (int)$_GET['sch_year'];

if (isset($_GET['sch_month']) && $_GET['sch_month'])
    $sch_month = (int)$_GET['sch_month'];


if(!$sch_year){
$sch_year = date("Y");
$sch_month = date("m");
$sch_day = date("Y-m-d");
}

// 기본 예약날짜
$wzp_default_today = wz_get_addday(MS_TIME_YMD, $wzdc['cp_term_day']);

$sch_day = preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $_GET['sch_day']) ? $_GET['sch_day'] : $wzp_default_today;
if (isset($sch_year) && $sch_year && isset($sch_month) && $sch_month) {

}
else { // 실시간예약 처음화면에서 넘어왔을경우.
    $sch_year   = $sch_day ? substr($sch_day, 0, 4) : $sch_year;
    $sch_month  = $sch_day ? substr($sch_day, 5, 2) : $sch_month;
}

$wz_cal = new wz_calendar($sch_year, $sch_month, $sch_day);
$total_day      = $wz_cal->total_day;
$year_prev      = $wz_cal->year_prev;
$month_prev     = $wz_cal->month_prev;
$year_next      = $wz_cal->year_next;
$month_next     = $wz_cal->month_next;
$today          = $wz_cal->today;
$sch_day        = $wz_cal->sch_day;
$sch_month_02d  = $wz_cal->sch_month_mm;
$first_day      = $wz_cal->first_day;
$sch_year       = $wz_cal->sch_year;
$sch_month      = $wz_cal->sch_month;
$arr_hd         = $wz_cal->holiday_list($wzdc['cp_ix']); // 공휴일정보

// 이용상태정보
unset($arr_status);
$arr_status = array();
$query = "  select
                rm_ix, rms_date, ifnull(sum(rms_cnt), 1) as rms_cnt,
                rms_day, rms_time
            from {$g5['wzb_room_status_table']}
                where cp_ix = '{$wzdc['cp_ix']}'
                and (rms_year = '$sch_year' and rms_month = '$sch_month_02d' or (rms_loop_year = 1 and rms_month = '$sch_month_02d'))
                and rms_status <> '취소'
            group by rm_ix, rms_date, rms_time
            ";
			//echo $query;
$res = sql_query($query);
while($row = sql_fetch_array($res)) {
    $arr_status[$row['rm_ix']][$row['rms_date']][$row['rms_time']]['rms_cnt'] = $row['rms_cnt'];
}
$cnt_status = count($arr_status);
if ($res) sql_free_result($res);

// 시설차단정보
unset($arr_close);
$arr_close = array();
$query = " select rm_ix, rmc_date from {$g5['wzb_room_close_table']} where cp_ix = '{$wzdc['cp_ix']}' and rmc_year = '$sch_year' and rmc_month = '$sch_month_02d' ";
//echo $query;
$res = sql_query($query);
while($row = sql_fetch_array($res)) {
    $arr_close[$row['rm_ix']][] = $row['rmc_date'];
}
if ($res) sql_free_result($res);

// 이용정보
unset($arr_room);
$arr_room = array();

$id = $_GET['id'];
$rm_ix = $_GET['rm_ix'];

//echo "id".$id;

if($id != "" && $rm_ix != ""){
    $sql_add = " and store_mb_id = '{$id}' and rm_ix = '{$rm_ix}' ";
}

$query = "select * from {$g5['wzb_room_table']} where cp_ix = '{$wzdc['cp_ix']}' and rm_use = 1 {$sql_add} order by rm_sort asc, rm_ix desc ";

//echo $query;
$res = sql_query($query);
while($row = sql_fetch_array($res)) {

    // 시간정보
    $query2 = " select * from {$g5['wzb_room_time_table']} where rm_ix = '".$row['rm_ix']."' order by rmt_time asc, rmt_ix desc ";

    //echo $query2;

    $res2 = sql_query($query2);
    while($row2 = sql_fetch_array($res2)) {
        $row['times'][] = $row2;
    }

    $arr_room[] = $row;
}
$cnt_room = count($arr_room);
if ($res) sql_free_result($res);

if ($sch_day < $wzp_default_today)
    $cnt_room = 0;

// 최대예약가능일.
$day_expire = wz_get_addday(MS_TIME_YMD, $wzpconfig['pn_max_booking_expire']);

//예약차단일설정.
$cp_term_day = '';
if ($wzdc['cp_term_day']) {
    $cp_term_day = wz_get_addday(MS_TIME_YMD, $wzdc['cp_term_day']);
}
?>

<nav>
<ul class="pager">
    <li><a href="javascript:_wzSetCanlendar('<?php echo $year_prev?>','<?php echo $month_prev?>');"><span class="hidden-xs">이전달</span><i class="fa fa-chevron-left visible-xs-inline" aria-hidden="true"></i></a></li>
    <li><strong class="lead ym-title"><span class="text-number"><?php echo $sch_year?></span><span class="text-hangul hidden-xs">년</span><span class="text-hangul visible-xs-inline">.</span> <span class="text-number"><?php echo $sch_month_02d?></span><span class="text-hangul hidden-xs">월</span></strong></li>
    <li><a href="javascript:_wzSetCanlendar('<?php echo $year_next?>','<?php echo $month_next?>');"><span class="hidden-xs">다음달</span><i class="fa fa-chevron-right visible-xs-inline" aria-hidden="true"></i></a></li>
</ul>
</nav>

<table class="table table-bordered tbl-canlendar <?php echo $sch_type == 'check' ? 'mini' : '';?>">
<caption>위토즈 실시간 예약플러그인 C형 <?php echo $WZB_STATUS_VER;?></caption>
<tr>
    <th class="sunday"><span class="text-red">Sun</span></th>
    <th>Mon</th>
    <th>Tue</th>
    <th>Wed</th>
    <th>Thu</th>
    <th>Fri</th>
    <th class="saturday"><span class="text-blue">Sat</span></th>
</tr>
</thead>
<tbody>
<tr>
    <?php
    // count는 <tr>태그를 넘기기위한 변수. 변수값이 7이되면 <tr>태그를 삽입한다.
    $weekno  = 0;

    //첫번째 주에서 빈칸을 1일전까지 빈칸을 삽입
    for ($i = 0; $i < $first_day; $i++) {
        echo '<td class="prev"></td>'.PHP_EOL;
        $weekno++;
    }

    for ($day = 1; $day <= $total_day; $day++) {

        $v02dd = sprintf('%02d', $day);
        $vmmdd = $sch_month_02d .'-'. $v02dd;
        $vdate = $sch_year .'-'. $vmmdd; // 표시 날짜.
        $bclss = $wz_cal->day_class($vdate, $weekno);

        // 당일 및 이전날짜 예약불가.
        $is_block = $is_before = $is_after = false;

        if ($vdate < $wzp_default_today) {
            $is_block = true;
    		$is_before = true;
        }

        if ($vdate > $day_expire) { // 예약기간에 포함되지 않는 이후일자.
            $is_block  = true;
            $is_after  = true;
        }

        if ($cp_term_day && $vdate < $cp_term_day) { // 예약일제한
            $is_block  = true;
            $is_before = true;
        }

        $dclss = '';
        if ($is_block) {
            $bclss .= ' imposs';
        }
        else {
            $dclss .= ' rnfo wz-ajax-html'; // 클릭하면 이용서비스를 선택하게 해주는 클래스.
            $bclss .= ' live';
        }

        $hd_title = '';
        if (isset($arr_hd[$v02dd])) { // 날짜제목이 있을경우
            $dclss .= ' box-sun';
            $hd_title = $arr_hd[$v02dd]['hd_subject']; // 공휴일제목
        }

        $is_live = false;
        $rm_cnts = 0; // 날짜내 예약가능수
        if ($cnt_room > 0 && !$is_block) {
            foreach ($arr_room as $k => $v) {
                $nw_status  = '';
                $nw_counts  = 0;
                $rm_ix      = $v['rm_ix'];

                // 요일제 허용여부
                if (!$v['rm_week'.$weekno]) {
                    continue;
                }

                // 공휴일 예약허용일경우 해당일이 공휴일인지 확인
                if (!$v['rm_holiday_use'] && $hd_title) {
                    continue;
                }

                // 관리자 > 이용상태관리
                if (isset($arr_close[$rm_ix]) && in_array($vdate, $arr_close[$rm_ix])) {
                    continue;
                }

                // 예약가능한 갯수 처리
                foreach ($v['times'] as $k2 => $v2) {
                    $rm_cnts    += $v2['rmt_max_cnt']; // 등록된 시간대별 예약허용인원
                    $rmt_time   = $v2['rmt_time']; // 등록된 시간 hh:mm

                    if (isset($arr_status[$rm_ix][$vdate][$rmt_time])) { // 예약시간이 존재할경우
                        $rms_cnt = $arr_status[$rm_ix][$vdate][$rmt_time]['rms_cnt'];
                        $rm_cnts -= $rms_cnt;
                    }
                }

                $is_live = true;
            }
        }

        if ($rm_cnts <= 0) { // 날짜에 예약이 모두 마감상태인경우.
            $bclss = 'imposs';
        }

        echo '<td class="'. $bclss .'">'.PHP_EOL;

        if ($rm_cnts > 0) { // 예약가능한 수량이 존재할경우
            echo '<a href="#none" class="text-center day '.$dclss.'" data-date="'.$vdate.'"><span class="day">'. $day.'</span>';
            echo '  <div class="m-remain-cnt">(잔여'.$rm_cnts.')</div>';
            echo '</a>'.PHP_EOL;
        }
        else {
            echo '<div class="text-center day">'. $day;
            if (!$is_before && !$is_after && $is_live) {
                echo '<div>마감</div>';
            }
            echo '</div>'.PHP_EOL;
        }

        echo '</td>'.PHP_EOL;

        if ($weekno==6) { // 토요일이 되면 줄바꾸기 위한 <tr>태그 삽입을 위한 식
            echo '</tr>'.PHP_EOL;
            if($day != $total_day) {
                echo '<tr height="30" class="date">'.PHP_EOL;
            }
            $weekno = 0;
        }
        else {
            $weekno++;
        }
    }

    unset($arr_status);
    unset($arr_room);

    // 선택한 월의 마지막날 이후의 빈테이블 삽입
    if ($weekno != 0) {
        for ($i=$day; $total_day <= $day && $weekno <= 6;$i++) {
            echo '<td class="next '.($weekno == 6 ? 'sat' : '').'"></td>'.PHP_EOL;
            if ($weekno == 6)
                echo '</tr>'.PHP_EOL;
            $weekno++;
        }
    }
    ?>
</tbody>
</table>