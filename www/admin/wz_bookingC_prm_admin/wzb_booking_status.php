<?php
$sub_menu = '790410';
include_once('./_common.php');

$is_sch = false; // 검색여부

$sch_cp_ix = 1; // 단독형 고정
if ($sch_cp_ix) {
    $qstr .= "&sch_cp_ix=".$sch_cp_ix;
    $is_sch = true;
}

if ($sch_rm_ix) {
    $qstr .= "&sch_rm_ix=".$sch_rm_ix;
    $is_sch = true;
}

if(!$sch_year){
$sch_year = date("Y");
$sch_month = date("m");
$sch_day = date("Y-m-d");
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
$arr_hd         = $wz_cal->holiday_list($sch_cp_ix); // 특정일정보

if ($sch_cp_ix && $sch_rm_ix) {

    // 이용상태정보
    unset($arr_status);
    $arr_status = array();
    $query = "  select
                    rm_ix, rms_date, ifnull(sum(rms_cnt), 1) as rms_cnt,
                    rms_day, rms_time
                from {$g5['wzb_room_status_table']}
                    where rm_ix = '".$sch_rm_ix."'
                    and (rms_year = '$sch_year' and rms_month = '$sch_month_02d' or (rms_loop_year = 1 and rms_month = '$sch_month_02d'))
                    and rms_status <> '취소' and bk_ix <> 0
                group by rm_ix, rms_date, rms_time
                ";
    $res = sql_query($query);
    while($row = sql_fetch_array($res)) {
        $arr_status[$row['rms_date']][$row['rms_time']]['rms_cnt'] = $row['rms_cnt'];
    }
    $cnt_status = count($arr_status);
    if ($res) sql_free_result($res);

    $rm = sql_fetch(" select * from {$g5['wzb_room_table']} where rm_ix = '".$sch_rm_ix."' ");

    // 시간정보
    unset($arr_time);
    $arr_time = array();
    $query = "select * from {$g5['wzb_room_time_table']} where rm_ix = '".$sch_rm_ix."' order by rmt_time asc, rmt_ix desc ";
    $res = sql_query($query);
    while($row = sql_fetch_array($res)) {
        $arr_time[] = $row;
    }
    $cnt_time = count($arr_time);
    if ($res) sql_free_result($res);

}

// 시설차단정보
unset($arr_close);
$arr_close = array();
$query = " select rm_ix, rmc_date from {$g5['wzb_room_close_table']} where cp_ix = '{$wzdc['cp_ix']}' and rmc_year = '$sch_year' and rmc_month = '$sch_month_02d' ";
$res = sql_query($query);
while($row = sql_fetch_array($res)) {
    $arr_close[$row['rm_ix']][] = $row['rmc_date'];
}
$cnt_close = count($arr_close);
if ($res) sql_free_result($res);

// 이용서비스정보
unset($arr_room);
$arr_room = wz_room_list($sch_cp_ix, false, 'sort');
$cnt_room = count($arr_room);

$g5['title'] = '예약현황';

include_once (MS_ADMIN_PATH.'/admin.head.php');

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';
?>
<form name="fsearch" id="fsearch" class="local_sch02 local_sch" method="get">
<input type="hidden" name="code" value="<?php echo $code; ?>">
<div>
    <strong>이용서비스</strong>
    <select name="sch_rm_ix" id="sch_rm_ix" onchange="this.form.submit();">
        <option value="">선택</option>
        <?php
        if ($cnt_room > 0) {
            foreach ($arr_room as $k => $v) {
                $selected = '';
                if ($sch_rm_ix == $v['rm_ix'])
                    $selected = 'selected=selected';
                echo '<option value="'.$v['rm_ix'].'" '.$selected.'>'.$v['rm_subject'].'</option>';
            }
        }
        ?>
    </select>
</div>
</form>

<div class="local_desc01 local_desc">
    <p>
        달력으로 예약현황을 확인합니다.
    </p>
</div>

<div class="tbl_head01 tbl_wrap">

    <?php if ($sch_rm_ix) {?>

        <div class="cal_navi">
            <a href="javascript:getCalender('<?php echo $year_prev?>','<?php echo $month_prev?>','<?php echo $sch_day?>');"><img src="./img/prev_chevron.png" /></a>&nbsp;
            <span class="title_red"><?php echo $sch_year?>년 <span><?php echo $sch_month_02d?>월</span>&nbsp;
            <a href="javascript:getCalender('<?php echo $year_next?>','<?php echo $month_next?>','<?php echo $sch_day?>');"><img src="./img/next_chevron.png" /></a>
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

                // 관리자 > 이용상태관리
                $is_block = false;
                if (isset($arr_close[$sch_rm_ix]) && in_array($vdate, $arr_close[$sch_rm_ix])) {
                    $is_block = true;
                }

                $hd_title   = '';
                if (isset($arr_hd[$v02dd])) { // 날짜제목이 있을경우
                    $bclss    = 'box-sun';
                    $hd_title = '<span class="sstx" style="color:red">'.$arr_hd[$v02dd]['hd_subject'].'</span>';
                }

                if (!$rm['rm_holiday_use'] && $hd_title) { // 공휴일 예약허용이 아닐경우 해당일이 공휴일인지 확인
                    $is_block = true;
                }

                if ($cnt_time > 0 && !$is_block && $rm['rm_week'.$weekno]) {
                    $rm_html .= '  <ul class="rmstatus">'.PHP_EOL;
                    foreach ($arr_time as $k => $v) {

                        $rm_cnts    = $v['rmt_max_cnt']; // 등록된 시간대별 예약허용인원
                        $rms_cnt    = 0;
                        $rmt_time   = $v['rmt_time']; // 등록된 시간 hh:mm

                        if (isset($arr_status[$vdate][$rmt_time])) { // 예약시간이 존재할경우
                            $rms_cnt = $arr_status[$vdate][$rmt_time]['rms_cnt'];
                        }

                        $closed_tag = '';
                        if ($rm_cnts == $rms_cnt) {
                            $closed_tag = 'closed';
                        }

                        $rm_html .= '<li class="'.$closed_tag.'"><a href="./wzb_booking_list2.php?code=wzb_booking_list&sch_cp_ix='.$sch_cp_ix.'&sch_room='.urlencode($rm['rm_subject']).'&sch_frdate1='.$vdate.'&sch_todate1='.$vdate.'">'.$rmt_time.' ('.$rms_cnt .'/'. $rm_cnts.')</a></li>'.PHP_EOL;
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

        <script type="text/javascript">
        <!--
            function getCalender(sch_year, sch_month, sch_day) {
                location.href = "<?php echo $_SERVER['SCRIPT_NAME']?>?code=wzb_booking_status&sch_cp_ix=<?php echo $sch_cp_ix?>&sch_rm_ix=<?php echo $sch_rm_ix?>&sch_year="+sch_year+"&sch_month="+sch_month+"&sch_day="+sch_day;
            }
        //-->
        </script>

    <?php } else { ?>

    <div style="padding:20px 0;text-align:center;font-weight:bold;">
        <p>
            이용서비스를 선택해주세요.
        </p>
    </div>

    <?php } ?>

</div>