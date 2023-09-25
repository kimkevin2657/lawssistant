<?php
$sub_menu = '790500';
include_once('./_common.php');

include_once(MS_PLUGIN_PATH.'/wz.bookingC.prm/config.php');
include_once(MS_PLUGIN_PATH.'/wz.bookingC.prm/lib/function.lib.php');

if(!$sch_year){
$sch_year = date("Y");
$sch_month = date("m");
$sch_day = date("Y-m-d");
}


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

// 펜션정보
unset($arr_cp);
$arr_cp = wz_corp_list();
$cnt_cp = count($arr_cp);

$g5['title'] = '공휴일관리';
include_once (MS_ADMIN_PATH.'/admin.head.php');

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';
?>

<div class="local_desc01 local_desc">
    <p>
        공휴일 정보를 개별 관리합니다.<br />
        매년에 체크하시면 해당정보가 동일한 날짜에 매년 적용됩니다.<br />
        휴지통 모양 아이콘을 클릭하시면 초기화(삭제) 됩니다.
    </p>
</div>

<div class="tbl_head01 tbl_wrap">

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

            // 특정일정보
            $hd_class = $hd_ix = $hd_subject = $hd_loop_year = '';
            if (isset($arr_hd[$v02dd])) {
                
                $hd_ix          = (int)$arr_hd[$v02dd]['hd_ix'];
                $hd_subject     = trim($arr_hd[$v02dd]['hd_subject']);
                $hd_loop_year   = (int)$arr_hd[$v02dd]['hd_loop_year'];
                $hd_class       = ' holi';
            }

            echo '<td class="'.$bclss.$hd_class.'">'.PHP_EOL;
            echo '  <p class="titday">'.$day.'</p>'.PHP_EOL;
            echo '  <ul class="rmprice">'.PHP_EOL;
            echo '  <li>'.PHP_EOL;
            echo '  <input type="hidden" name="hd_date" id="hd_date_'.$day.'" value="'.$vdate.'" />'.PHP_EOL; 
            echo '  <input type="text" name="hd_subject" id="hd_subject_'.$day.'" value="'.$hd_subject.'" class="frm_input" style="width:60px;" maxlength="20" />'.PHP_EOL;
            echo '  <label class="hd_lp"><input type="checkbox" name="hd_loop_year" id="hd_loop_year_'.$day.'" value="1" '.($hd_loop_year ? 'checked=checked' : '').' />매년</label>'.PHP_EOL;
            if ($hd_ix) { 
                echo '<a href="#none" class="hd_del" data-hd-ix="'.$hd_ix.'"><i class="fa fa-trash fa-2"></i></a>'.PHP_EOL; 
            } 
            echo '  </li>'.PHP_EOL;
            echo '  </ul>'.PHP_EOL; 
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
            location.href = "<?php echo $_SERVER['SCRIPT_NAME']?>?code=wzb_holiday_list&sch_cp_ix=<?php echo $sch_cp_ix?>&sch_year="+sch_year+"&sch_month="+sch_month+"&sch_day="+sch_day;
        }
        jQuery(document).ready(function () {
            $(document).on("change", "input[name=hd_subject]", function() {
                var $this           = $(this);
                var hd_date         = $this.closest("li").find("input[name=hd_date]").val();
                var hd_subject      = $this.val();
                var hd_loop_year    = $this.closest("li").find("input:checkbox[name=hd_loop_year]").is(":checked") ? 1 : 0;
                getAction($this,  hd_date, hd_subject, hd_loop_year);
            });
            $(document).on("click", "input:checkbox[name=hd_loop_year]", function() {
                var $this           = $(this);
                var hd_date         = $this.closest("li").find("input[name=hd_date]").val();
                var hd_subject      = $this.closest("li").find("input[name=hd_subject]").val();
                var hd_loop_year    = $this.is(":checked") ? 1 : 0;
                getAction($this, hd_date, hd_subject, hd_loop_year);
            });
            $(document).on("click", ".hd_del", function() {
                var $this           = $(this);
                var hd_ix           = $this.attr("data-hd-ix");
                if (confirm("설정된 정보를 초기화 하시겠습니까?")) {
                    var url = "./wzb_holiday_delete.php";
                    var token = get_ajax_token();
                    var prm = { "sch_cp_ix": "<?php echo $sch_cp_ix?>", "hd_ix": hd_ix, "token": token};
                    $.post(url, prm, function(json, status) {
                            if(status == 'success') {
                                if (json.rescd == '00') {
                                    $this.closest("li").find("input[name=hd_subject]").val("");
                                    $this.closest("li").find("input:checkbox[name=hd_loop_year]").attr('checked',false);
                                    $this.remove();
                                } 
                            }
                            else {
                                alert("error!!"); return;
                            }
                        }, "json"
                    );
                }
            });
        });
        function getAction(_this, hd_date, hd_subject, hd_loop_year) {
            var url = "./wzb_holiday_update.php";
            var token = get_ajax_token();
            var prm = { "sch_cp_ix": "<?php echo $sch_cp_ix?>", "hd_date": hd_date, "hd_subject": hd_subject, "hd_loop_year": hd_loop_year, "token": token };
            $.post(url, prm, function(json, status) {
                    if(status == 'success') {
                        if (json.rescd == '00') {
                            if (json.resmo == 'new') {
                                _this.closest("li").find(".hd_lp").after('<a href="#none" class="hd_del" data-hd-ix="'+json.hd_ix+'"><i class="fa fa-trash fa-2"></i></a>');
                            }
                            else {
                                alert(json.restx);
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

</div>


<?php
include_once (MS_ADMIN_PATH.'/admin.tail.php');
?>

