<?php
$sub_menu = '790420';
include_once('./_common.php');

include_once(TB_PLUGIN_PATH.'/wz.bookingC.prm/config.php');
include_once(TB_PLUGIN_PATH.'/wz.bookingC.prm/lib/function.lib.php');

if(!$sch_year){
$sch_year = date("Y");
$sch_month = date("m");
$sch_day = date("Y-m-d");
}

if (isset($_POST['sch_year']) && $_POST['sch_year'])
    $sch_year = (int)$_POST['sch_year'];

if (isset($_POST['sch_month']) && $_POST['sch_month'])
    $sch_month = (int)$_POST['sch_month'];

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
$arr_hd         = $wz_cal->holiday_list($sch_cp_ix); // 특정일정보

unset($arr_list);
$arr_order = array();
$query = "select rms.*, rm.rm_subject, bk.bk_name, bk.bk_hp, bk.od_id from {$g5['wzb_room_status_table']} as rms inner join {$g5['wzb_room_table']} as rm inner join {$g5['wzb_booking_table']} as bk on rms.rm_ix = rm.rm_ix and rms.bk_ix = bk.bk_ix where (rms_year = '$sch_year' and rms_month = '$sch_month_02d' or (rms_loop_year = 1 and rms_month = '$sch_month_02d')) and rms_status <> '취소' order by rms_time asc  ";
$res = sql_query($query);
while($row = sql_fetch_array($res)) {
    $arr_list[$row['rms_date']][] = $row;
}
$cnt_list = count($arr_list);
if ($res) sql_free_result($res);
?>

<div class="cal_navi">
    <a href="javascript:_wzSetCanlendar('<?php echo $year_prev?>','<?php echo $month_prev?>','<?php echo $sch_day?>');"><img src="./wz_bookingC_prm_admin/img/prev_chevron.png" /></a>&nbsp;
    <span class="title_red"><?php echo $sch_year?>년 <span><?php echo $sch_month_02d?>월</span>&nbsp;
    <a href="javascript:_wzSetCanlendar('<?php echo $year_next?>','<?php echo $month_next?>','<?php echo $sch_day?>');"><img src="./wz_bookingC_prm_admin/img/next_chevron.png" /></a>
</div>

<table border="0" cellpadding="0" cellspacing="0" class="caltable-adm">
<tbody>
<tr height="30">
    <th class="sunday">일</th>
    <th>월</th>
    <th>화</th>
    <th>수</th>
    <th>목</th>
    <th>금</th>
    <th class="saturday">토</th>
</tr>
<tr height="30" class="date">
    <?php
    $weekno = 0;

    //첫번째 주에서 빈칸을 1일전까지 빈칸을 삽입
    for ($i = 0; $i < $first_day; $i++) {
        echo '<td class="prev"></td>'.PHP_EOL;
        $weekno++;
    }

    for ($day = 1; $day <= $total_day; $day++) {

        $v02dd = sprintf("%02d", $day);
        $vmmdd = $sch_month_02d ."-". $v02dd;
        $vdate = $sch_year ."-". $vmmdd; // 표시 날짜.
        $bclss = $wz_cal->day_class($vdate, $weekno);

        $rm_html = '';
        $rm_cnts = 0;

        $hd_title   = '';
        if (isset($arr_hd[$v02dd])) { // 날짜제목이 있을경우
            $bclss    = 'box-sun';
            $hd_title = '<span class="sstx" style="color:red">'.$arr_hd[$v02dd]['hd_subject'].'</span>';
        }

        $ul_class = '';
        if ($vdate == G5_TIME_YMD) { // 오늘이라면..
            $ul_class = 'todays';
        }

        if ($cnt_list > 0) {
            if (is_array($arr_list[$vdate])) {
                foreach ($arr_list[$vdate] as $k => $v) {
                    $rm_html .= '<li>'.$v['rm_subject'].' '.$v['rms_time'].' '.$v['bk_name'].' ('.$v['rms_cnt'].'명)</li>'.PHP_EOL;
                }
            }
            $rm_html .= '  </ul>'.PHP_EOL;
        }

        echo '<td class="'. $bclss .'">'.PHP_EOL;
        echo '  <p class="titday"><span class="day">'.$day.'</span>'.$hd_title.'</p>'.PHP_EOL;
        echo $rm_html;
        echo '</td>'.PHP_EOL;

        if ($weekno==6) { // 토요일이 되면 줄바꾸기 위한 <tr>태그 삽입을 위한 식
            echo '</tr>'.PHP_EOL;
            if ($day != $total_day) {
                echo '<tr height="30" class="date">'.PHP_EOL;
            }
            $weekno = 0;
        }
        else {
            $weekno++;
        }
    }

    unset($arr_time);
    unset($arr_status);
    unset($arr_hd);

    // 선택한 월의 마지막날 이후의 빈테이블 삽입
    if ($weekno != 0) {
        for ($i=$day; $total_day <= $day && $weekno <= 6;$i++) {
            echo '<td class="mini next"></td>'.PHP_EOL;
            if ($weekno == 6)
                echo '</tr>'.PHP_EOL;
            $weekno++;
        }
    }
    ?>
</tbody>
</table>