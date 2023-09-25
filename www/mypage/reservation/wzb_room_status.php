<?php
if(!defined('_TUBEWEB_')) exit;

if(!$sch_year){
$sch_year = date("Y");
$sch_month = date("m");
$sch_day = date("Y-m-d");
}

$pg_title = '이용상태관리';

$is_sch = false; // 검색여부

$sch_cp_ix = 1; // 단독형 고정
if ($sch_cp_ix) {
    $qstr .= "&sch_cp_ix=".$sch_cp_ix;
    $is_sch = true;
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

// 이용서비스정보
unset($arr_room);
$arr_room = wz_room_list($sch_cp_ix, false, 'sort', $member['id']);
$cnt_room = count($arr_room);

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

include_once("./admin_head.sub.php");

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';
?>
<div class="s_wrap">
<div class="local_desc01 local_desc">
    <p>
        날짜별로 이용서비스의 예약을 차단할경우 설정합니다.<br>
        시설버튼을 클릭하시면 차단됩니다.
    </p>
</div>

<div class="tbl_head01 tbl_wrap">

    <?php if ($sch_cp_ix) {?>

        <div class="cal_navi">
            <a href="javascript:getCalender('<?php echo $year_prev?>','<?php echo $month_prev?>','<?php echo $sch_day?>');"><img src="./reservation/img/prev_chevron.png" /></a>&nbsp;
            <span class="title_red"><?php echo $sch_year?>년 <span><?php echo $sch_month_02d?>월</span>&nbsp;
            <a href="javascript:getCalender('<?php echo $year_next?>','<?php echo $month_next?>','<?php echo $sch_day?>');"><img src="./reservation/img/next_chevron.png" /></a>       
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
        <tr height="30" class="date status">
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

                if ($cnt_room > 0) { 
                    $rm_html .= '  <ul class="rmstatus">'.PHP_EOL;
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
                        $closed_tag = '';
                        if (isset($arr_close[$rm_ix]) && in_array($vdate, $arr_close[$rm_ix])) { 
                            $closed_tag = 'closed';
                        }
                        $rm_html .= '<li><a href="#none" class="btn-room '.$closed_tag.'" data-date="'.$vdate.'" data-rmix="'.$v['rm_ix'].'">'.$v['rm_subject'].'</a></li>'.PHP_EOL;
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
                        echo '<tr height="30" class="date status">'.PHP_EOL;
                    }
                    $weekno = 0;
                }
                else {
                    $weekno++;
                }
            }

            unset($arr_room);
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
                location.href = "<?php echo $_SERVER['SCRIPT_NAME']?>?code=wzb_room_status&sch_cp_ix=<?php echo $sch_cp_ix?>&sch_year="+sch_year+"&sch_month="+sch_month+"&sch_day="+sch_day;
            }
            jQuery(document).ready(function () {
                $(document).on("click", ".btn-room", function() {
                    var $this           = $(this);
                    var rm_ix           = $this.attr("data-rmix");
                    var rmc_date        = $this.attr("data-date");
                    
                    var msg             = "해당 서비스정보를 차단 하시겠습니까?";
                    if ($this.hasClass('closed') == true) {
                        msg             = "해당 서비스 차단정보를 해제 하시겠습니까?";
                    }

                    if (confirm(msg)) {
                        getAction($this, rm_ix, rmc_date);
                    }
                    
                });
            });
            function getAction(_this, rm_ix, rmc_date) {
                var url = "./reservation/wzb_room_status_update.php";
                var prm = { "sch_cp_ix": "<?php echo $sch_cp_ix?>", "rm_ix": rm_ix, "rmc_date": rmc_date};
                $.post(url, prm, function(json, status) {
                        if(status == 'success') {
                            if (json.rescd == '00') {
                                if (_this.hasClass('closed') == true) {
                                    _this.removeClass('closed');
                                }
                                else {
                                    _this.addClass('closed');
                                }
                            } 
                        }
                        else {
                            alert("error!!"); return;
                        }
                    }, "json"
                );
            }
        //-->
        </script>

    <?php } else { ?>
        
    <div style="padding:20px 0;text-align:center;font-weight:bold;">
        <p>
            업체를 선택해주세요.
        </p>
    </div>

    <?php } ?>

</div>
</div>

<?php
include_once("./admin_tail.sub.php");
//include_once (TB_ADMIN_PATH.'/admin.tail.php');
?>

