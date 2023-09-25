<?php
if(!defined('_TUBEWEB_')) exit;

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

if ($sch_rm_ix) {
    $qstr .= "&sch_rm_ix=".$sch_rm_ix;
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
                    and rms_status <> '취소' 
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

    // 객실개별요금정보
	unset($arr_rmp);
	$arr_rmp = array();
	$query = "select 
	                rmp_ix, rm_ix, rmp_date, rmp_time, rmp_price, rmp_loop_year
	            from {$g5['wzb_room_extend_price_table']} 
	            where cp_ix = '{$sch_cp_ix}'
	            and rmp_year = '$sch_year' and rmp_month = '$sch_month_02d' or (rmp_loop_year = 1 and rmp_month = '$sch_month_02d') ";
	$res = sql_query($query);
	while($row = sql_fetch_array($res)) { 
	    $arr_rmp[$row['rmp_date']][$row['rmp_time']]['rmp_ix'] = $row['rmp_ix'];
	    $arr_rmp[$row['rmp_date']][$row['rmp_time']]['rmp_price'] = $row['rmp_price'];
	    $arr_rmp[$row['rmp_date']][$row['rmp_time']]['rmp_loop_year'] = $row['rmp_loop_year'];
	}
	$cnt_rmp = count($arr_rmp);
	if ($res) sql_free_result($res);

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

}

// 이용서비스정보
unset($arr_room);
$arr_room = wz_room_list($sch_cp_ix, false, 'sort', $member['id']);
$cnt_room = count($arr_room);

$pg_title = '개별요금관리';
include_once("./admin_head.sub.php");

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';
?>
<div class="s_wrap">
<form name="fsearch" id="fsearch" class="local_sch02 local_sch" method="get">
<input type="hidden" name="code" value="<?=$code?>" />
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
        시간대별 개별요금을 적용해야 할 경우 설정하며 설정된 요금이 최우선 적용됩니다.<br>
        노란색 표시는 요금을 직접 수동으로 설정한 금액입니다.<br>
        입력박스 옆 삭제버튼 을 클릭하시면 직접 설정한 금액이 취소됩니다.<br />
        매년에 체크하시면 해당정보가 동일한 날짜에 매년 적용됩니다.<br />
        휴지통 모양 아이콘을 클릭하시면 초기화 됩니다.
    </p>
</div>

<div class="tbl_head01 tbl_wrap">

    <?php if ($sch_rm_ix) {?>

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
                    $j = 0;
                    $rm_html .= '  <ul class="rmstatus">'.PHP_EOL;
                    foreach ($arr_time as $k => $v) {
                        
                        $rmt_time   = $v['rmt_time']; // 등록된 시간 hh:mm
                        $price      = $v['rmt_price']; // 기본요금

                        $rmp_class  = $rmp_loop_year = $rmp_ix = '';

                        if (isset($arr_rmp[$vdate][$rmt_time])) {
                            $rmp_ix         = (int)$arr_rmp[$vdate][$rmt_time]['rmp_ix'];
                            $price          = (int)$arr_rmp[$vdate][$rmt_time]['rmp_price'];
                            $rmp_loop_year  = (int)$arr_rmp[$vdate][$rmt_time]['rmp_loop_year'];
                            $rmp_class      = 'customp';
                        }

                        $rm_html .= '<li class="'.$rmp_class.'">'.PHP_EOL;
                        $rm_html .= '  <input type="hidden" name="rm_ix" id="rm_ix_'.$j.'_'.$vdate.'" value="'.$rm['rm_ix'].'" />'.PHP_EOL; 
                        $rm_html .= '  <input type="hidden" name="rmp_date" id="rmp_date_'.$j.'_'.$vdate.'" value="'.$vdate.'" />'.PHP_EOL; 
                        $rm_html .= '  <input type="hidden" name="rmp_time" id="rmp_time_'.$j.'_'.$vdate.'" value="'.$rmt_time.'" />'.PHP_EOL; 
                        $rm_html .= '  <div class="el_wrap">';
                        $rm_html .= '  <strong>'.$rmt_time.'</strong>&nbsp;<input type="text" name="rmp_price" id="rmp_price_'.$j.'_'.$vdate.'" value="'.$price.'" class="frm_input number amt" style="width:55px;" maxlength="10" /> <label class="rmp_lp"><input type="checkbox" name="rmp_loop_year" id="rmp_loop_year_'.$j.'_'.$vdate.'" value="1" '.($rmp_loop_year ? 'checked=checked' : '').' /> 매년</label>'.PHP_EOL; 

                        if ($rmp_ix) { 
                            $rm_html .= '<a href="#none" class="rmp_del" data-rmp-ix="'.$rmp_ix.'" title="초기화"><i class="fa fa-trash fa-2"></i></a>'.PHP_EOL;  
                        } 
                        
                        $rm_html .= '  </div>'.PHP_EOL;
                        $rm_html .= '</li>'.PHP_EOL;

                        $j++;

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
                location.href = "<?php echo $_SERVER['SCRIPT_NAME']?>?code=wzb_price_list&sch_cp_ix=<?php echo $sch_cp_ix?>&sch_rm_ix=<?php echo $sch_rm_ix?>&sch_year="+sch_year+"&sch_month="+sch_month+"&sch_day="+sch_day;
            }
            jQuery(document).ready(function () {
                $(document).on("change", "input[name=rmp_price]", function() {
                    var $this           = $(this);
                    var rm_ix           = $this.closest("li").find("input[name=rm_ix]").val();
                    var rmp_date        = $this.closest("li").find("input[name=rmp_date]").val();
                    var rmp_time        = $this.closest("li").find("input[name=rmp_time]").val();
                    var rmp_price       = $this.val();
                    var rmp_loop_year   = $this.closest("li").find("input:checkbox[name=rmp_loop_year]").is(":checked") ? 1 : 0;
                    getAction($this, rm_ix, rmp_date, rmp_price, rmp_loop_year, rmp_time);
                });
                $(document).on("click", "input:checkbox[name=rmp_loop_year]", function() {
                    var $this           = $(this);
                    var rm_ix           = $this.closest("li").find("input[name=rm_ix]").val();
                    var rmp_date        = $this.closest("li").find("input[name=rmp_date]").val();
                    var rmp_time        = $this.closest("li").find("input[name=rmp_time]").val();
                    var rmp_price       = $this.closest("li").find("input[name=rmp_price]").val();
                    var rmp_loop_year   = $this.is(":checked") ? 1 : 0;
                    getAction($this, rm_ix, rmp_date, rmp_price, rmp_loop_year, rmp_time);
                });
                $(document).on("click", ".rmp_del", function() {
                    var $this           = $(this);
                    var rmp_ix          = $this.attr("data-rmp-ix");

                    if (confirm("설정된 정보를 초기화 하시겠습니까?")) {
                        var url = "./wzb_price_delete.php";
                        var token = get_ajax_token();
                        var prm = { "sch_cp_ix": "<?php echo $sch_cp_ix?>", "rmp_ix": rmp_ix, "token": token};
                        $.post(url, prm, function(json, status) {
                                if(status == 'success') {
                                    if (json.rescd == '00') {
                                        $this.closest("li").find("input[name=rmp_price]").val(json.rmp_price);
                                        $this.closest("li").find("input:checkbox[name=rmp_loop_year]").attr('checked',false);
                                        $this.closest("li").removeClass("customp");
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
            function getAction(_this, rm_ix, rmp_date, rmp_price, rmp_loop_year, rmp_time) {
                var url = "./reservation/wzb_price_update.php";
                var token = get_ajax_token();
                var prm = { "sch_cp_ix": "<?php echo $sch_cp_ix?>", "rm_ix": rm_ix, "rmp_date": rmp_date, "rmp_time": rmp_time, "rmp_price": rmp_price, "rmp_loop_year": rmp_loop_year, "token": token };
                $.post(url, prm, function(json, status) {
                        if(status == 'success') {
                            if (json.rescd == '00') {
                                if (json.resmo == 'new') {
                                    _this.closest("li").addClass("customp");
                                    _this.closest("li").find(".rmp_lp").after('<a href="#none" class="rmp_del" data-rmp-ix="'+json.rmp_ix+'"><i class="fa fa-trash fa-2"></i></a>');
                                }
                                else {
                                    alert(json.restx);
                                }
                            } 
                            else {
                                alert(json.restx);
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
            이용서비스를 선택해주세요.
        </p>
    </div>

    <?php } ?>

</div>
</div>

<?php
include_once("./admin_tail.sub.php");
//include_once (TB_ADMIN_PATH.'/admin.tail.php');
?>