<?php
include_once('./_common.php');
include_once(MS_LIB_PATH.'/thumbnail.lib.php');
include_once('./config.php');
include_once('./lib/function.lib.php');

$id = $_POST['id'];
$rm_ix = $_POST['rm_ix'];


//echo $id;

if($id != "" && $rm_ix != ""){
    $sql_add = " and store_mb_id = '{$id}' and rm_ix = '{$rm_ix}' ";
}

if (isset($_POST['sch_day']) && $_POST['sch_day']) {
    $sch_day = preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $_POST['sch_day']) ? $_POST['sch_day'] : '';
}

if (!$sch_day) {
    die('예약하실 날짜를 선택해주세요.');
}

// 예약차단일에 해당되는지.
$cp_term_day = '';
if ($wzdc['cp_term_day']) {
    $cp_term_day = wz_get_addday(MS_TIME_YMD, $wzdc['cp_term_day']);
    if ($sch_day < $cp_term_day) {
        $sch_day = $cp_term_day;
    }
}

// 예약가능최대일에 포함되는지.
$expire_date = MS_TIME_YMD;
if ($wzpconfig['pn_max_booking_expire']) {
    $expire_date = wz_get_addday(MS_TIME_YMD, $wzpconfig['pn_max_booking_expire']);
}
if ($sch_day > $expire_date) { // 예약가능 최대일을 넘긴경우.
    $sch_day = $expire_date;
}

$file_save_dir  = '/wzb_room/';
$file_save_path = MS_DATA_PATH.$file_save_dir;
$rms_year       = substr($sch_day, 0, 4);
$rms_month      = substr($sch_day, 5, 2);
$rms_day        = substr($sch_day, 8);
$sch_week       = date('w', strtotime($sch_day));


// 시설정보
unset($arr_room);
$arr_room = array();
$query = "select * from {$g5['wzb_room_table']} where cp_ix = '{$wzdc['cp_ix']}' and rm_use = 1 {$sql_add} order by rm_sort asc, rm_ix desc ";

//echo $query;

$res = sql_query($query);
while($row = sql_fetch_array($res)) {

    if (!$row['rm_week'.$sch_week]) { // 예약가능한 요일이 아닐경우
        continue;
    }

    $row['is_close'] = false;
    $query2 = " select rmc_ix from {$g5['wzb_room_close_table']} where rm_ix = '{$row['rm_ix']}' and rmc_date = '$sch_day' "; // 시설차단정보
    $rmc = sql_fetch($query2);
    if ($rmc['rmc_ix']) {
        $row['is_close'] = true;
    }

    // 공휴일정보
    if (!$row['rm_holiday_use']) { // 공휴일 예약허용이 아닐경우 해당일이 공휴일인지 확인
        $query2 = "select
                        hd_ix
                    from {$g5['wzb_holiday_table']}
                    where (cp_ix = '".$wzdc['cp_ix']."' or cp_ix = 0)
                    and hd_date = '".$sch_day."' or (hd_loop_year = 1 and hd_month = '".$rms_month."' and hd_day = '".$rms_day."') ";
        $hd = sql_fetch($query2, true);
        if ($hd['hd_ix']) {
            continue;
        }
    }

    // 시설이미지
    $query2 = "select rmp_photo from {$g5['wzb_room_photo_table']} where rm_ix = '{$row['rm_ix']}' order by rmp_ix asc limit 1";
    $rmp = sql_fetch($query2);
    $bimg = $file_save_path.$rmp['rmp_photo'];
    if (file_exists($bimg) && $rmp['rmp_photo']) {
        $file_name_thumb = thumbnail($rmp['rmp_photo'], $file_save_path, $file_save_path, 80, 80, true, true);
        $row['img_src'] = MS_DATA_URL.$file_save_dir.$file_name_thumb;
    }

    // 시간정보
    $query2 = " select * from {$g5['wzb_room_time_table']} where rm_ix = '".$row['rm_ix']."' order by rmt_time asc, rmt_ix desc ";
    $res2 = sql_query($query2);
    while($row2 = sql_fetch_array($res2)) {
        $row['times'][] = $row2;
    }

    // 권한정보 확인
    $row['permit_level'] = ($member['grade'] >= $row['rm_level'] ? true : false);

    $arr_room[] = $row;
}
$cnt_room = count($arr_room);
if ($res) sql_free_result($res);

// 시설전체이미지
unset($arr_img);
$arr_img = array();
$query = "select rmp.rm_ix, rmp.rmp_photo, rm.rm_subject from {$g5['wzb_room_photo_table']} as rmp inner join {$g5['wzb_room_table']} as rm on rmp.rm_ix = rm.rm_ix where rm_use = 1 order by rm_ix desc, rmp_ix asc";
$res = sql_query($query);
while($row = sql_fetch_array($res)) {
    $bimg = $file_save_path.$row['rmp_photo'];
    if (file_exists($bimg) && $row['rmp_photo']) {
        $row['img_src'] = MS_DATA_URL.$file_save_dir.$row['rmp_photo'];
        //echo MS_DATA_URL.$file_save_dir.$row['rmp_photo'];
    }
    $arr_img[$row['rm_ix']][] = $row;
}
$cnt_img = count($arr_img);
if ($res) sql_free_result($res);

$now_time = date('H:i', MS_SERVER_TIME); // 현재시간

$cnt_view = 0;
if ($cnt_room > 0) {
    foreach ($arr_room as $k => $v) {

    $rm_ix = $v['rm_ix'];

    ?>

    <?php 
    if ($v['img_src']) {

    ?>
    <div class="media-left">
        <a class="room-photo-frame" id="open-photo-<?php echo $rm_ix;?>" href="#none"><img src="<?php echo $v['img_src'];?>" class="room-photo-main"></a>
    </div>
    <?php } ?>

    <div class="media-body info">
        <h4 class="media-heading" id="top-aligned-media"><?php echo $v['rm_subject'];?>
        <?php if ($v['rm_link_url']) {?>
        <a href="<?php echo $v['rm_link_url'];?>" target="_blank" class="more">[더보기]</a>
        <?php } ?>
        </h4>
        <div class="desc"><?php echo conv_content($v['rm_desc'], '');?></div>
    </div>

    <ul class="bx-times">
        <?php
        $z = 1;
        foreach ($v['times'] as $k2 => $v2) {

            $max_cnt = wz_check_time_room($rm_ix, $sch_day, $v2['rmt_time']);

            $closed = false;
            if ($sch_day <= MS_TIME_YMD && $v2['rmt_time'] < $now_time) {
                $closed = true;
            }

            if (!$max_cnt || $v['is_close'])
                $closed = true;

            echo '<li>'.PHP_EOL;
            if ($closed) { // 마감된것
                echo '  <div class="btn-time closed"><span class="remain-cnt">('.$max_cnt.'/'.$v2['rmt_max_cnt'].')</span><span class="time">'.wz_get_hangul_time_hm($v2['rmt_time']).'</span></div>';
            }
            else if (!$v['permit_level']) { // 권한없음
                echo '  <a href="#none" class="btn-time closed limit-level" data-level="'.$v['rm_level'].'"><span class="time">'.wz_get_hangul_time_hm($v2['rmt_time']).'</span></a>';
            }
            else {
                echo '  <a href="#none" title="'. wz_get_hangul_date($sch_day) .' '. get_text($v['rm_subject']).' '.wz_get_hangul_time_hm($v2['rmt_time']).' 예약하기" class="btn-time cal_rm_list"  data-rmt-ix="'.$v2['rmt_ix'].'" data-rm-ix="'.$rm_ix.'" data-time="'.$v2['rmt_time'].'" data-index="'.$z.'" id="btn_time_'.$z.'"><span class="remain-cnt">('.$max_cnt.'/'.$v2['rmt_max_cnt'].')</span><span class="time">'.wz_get_hangul_time_hm($v2['rmt_time']).'</span></a>';
            }
            echo '</li>'.PHP_EOL;
            $z++;
        }
        ?>
    </ul>

    <?php
    $cnt_view++;
    }
}
else {
    ?>
    <div class="col-xs-12 text-center" style="margin-bottom:15px;">예약가능한 이용서비스가 존재하지 않습니다.</div>
    <?php
}
?>

<div id="room-select-form"></div>

<script type="text/javascript">
<!--
jQuery(document).ready(function () {

    <?php
    foreach ($arr_img as $k => $v) {
        ?>
        $('#open-photo-<?php echo $k?>').magnificPopup({
        items:
            [
            <?php
            foreach ($v as $k2 => $v2) {
                ?>
                {
                    src: '<?php echo $v2["img_src"]?>',
                    title: '<?php echo $v2["rm_subject"]?>'
                },
                <?php
            }
            ?>
            ],
            gallery: {
                enabled: true
            },
            type: 'image' // this is a default
        });
        <?php
    }
    ?>
});
//-->
</script>