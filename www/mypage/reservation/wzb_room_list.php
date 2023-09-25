<?php
$sub_menu = '790300';
include_once('./_common.php');
include_once(TB_LIB_PATH.'/thumbnail.lib.php');
//auth_check($auth[$sub_menu], "r");

$sql_common = " from {$g5['wzb_room_table']} ";

$sql_search = " where (1) ";

$is_sch = false; // 검색여부

$sch_cp_ix = 1; // 단독형 고정
if ($sch_cp_ix) {
    $sql_search .= " and cp_ix = '".$sch_cp_ix."' ";
    $qstr .= "&sch_cp_ix=".$sch_cp_ix;
    $is_sch = true;
}

if ($sch_subject) {
    $sql_search .= " and rm_subject like '%".$sch_subject."%' ";
    $qstr .= "&sch_subject=".$sch_subject;
    $is_sch = true;
}

$sql_search .= " and store_mb_id = '{$member['id']}' ";

$sql_order = " order by rm_sort asc , rm_ix desc ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = '40';
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$file_save_dir  = "/wzb_room/";
$file_save_path = TB_DATA_PATH.$file_save_dir;

unset($arr_room);
$arr_room = array();
$query = "select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";

//echo $query;

$res = sql_query($query);
while($row = sql_fetch_array($res)) { 
    
    // 업체정보
    $query2 = "select cp_title from {$g5['wzb_corp_table']} where cp_ix = '{$row['cp_ix']}' "; 
    $row2 = sql_fetch($query2);
    $row['cp_title'] = $row2['cp_title'];
    
    // 이용이미지
    $query2 = "select rmp_photo from {$g5['wzb_room_photo_table']} where rm_ix = '{$row['rm_ix']}' order by rmp_ix asc limit 1";
    $rmp = sql_fetch($query2);
    $bimg = $file_save_path.$rmp['rmp_photo'];
    //echo $bimg;
    if (file_exists($bimg) && $rmp['rmp_photo']) {

        $file_name_thumb = thumbnail($rmp['rmp_photo'], $file_save_path, $file_save_path, 100, 80, true, true);
        $row['img_src'] = TB_DATA_URL.$file_save_dir.$file_name_thumb;

    }

    $query2 = " select * from {$g5['wzb_room_time_table']} where rm_ix = '{$row['rm_ix']}' order by rmt_time asc, rmt_ix desc ";
    $res2 = sql_query($query2);
    while($row2 = sql_fetch_array($res2)) { 
        $row['time'][] = $row2;
    } 

    $arr_room[] = $row;

}
$cnt_room = count($arr_room);
if ($res) sql_free_result($res);

// 업체정보
unset($arr_cp);
$arr_cp = wz_corp_list();
$cnt_cp = count($arr_cp);

$g5['title'] = '이용정보 관리';
include_once (TB_ADMIN_PATH.'/admin.head.php');

$colspan = 8;
?>

<div class="local_ov01 local_ov">
    <?php echo $listall; ?>
    전체 <?php echo number_format($total_count); ?>건
</div>
<div class="s_wrap">
<form name="fsearch" id="fsearch" class="local_sch02 local_sch" method="get">
<input type="hidden" name="code" id="code" value="<?php echo $code;?>" />
<input type="hidden" name="sch_cp_ix" id="sch_cp_ix" value="<?php echo $sch_cp_ix;?>" />
<div class="sch_last">
    <strong>이용서비스명</strong>
    <input type="text" id="sch_subject" name="sch_subject" value="<?php echo $sch_subject;?>" class="frm_input" size="20" maxlength="20">
    <input type="submit" value="검색" class="btn_submit">
</div>
</form>


<form name="frm" id="frm" method="post" action="./wzb_room_list_update.php" onsubmit="return getAction(this);">
<input type="hidden" name="sst" value="<?php echo urlencode($sst) ?>">
<input type="hidden" name="sod" value="<?php echo $sod ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl ?>">
<input type="hidden" name="stx" value="<?php echo $stx ?>">
<input type="hidden" name="page" value="<?php echo $page ?>">

<div class="btn_add01 btn_add">
    <a href="./rpage.php?code=wzb_room_form<?php echo $qstr;?>" id="bo_add">이용정보 추가</a>
</div>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th width="40px" scope="col">
            <label for="chkall" class="sound_only">현재 페이지 전체</label>
            <input type="checkbox" name="chkall" value="1" id="chkall" onclick="check_all(this.form)">
        </th>
        <th width="120px" scope="col">이미지</th>
        <th width="auto" scope="col">이용서비스명</th>
        <th scope="col">공휴일예약허용</th>
        <th scope="col">예약가능요일</th>
        <th scope="col">이용시간정보</th>
        <th width="80px" scope="col">사용여부</th>
        <th width="80px" scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($cnt_room > 0) {
        for ($z = 0; $z < $cnt_room; $z++) { 

        $bg  = 'bg'.($z%2);

        $rm_week0 = $arr_room[$z]['rm_week0'] ? '일' : '<span class="closed">일</span>';
        $rm_week1 = $arr_room[$z]['rm_week1'] ? '월' : '<span class="closed">월</span>';
        $rm_week2 = $arr_room[$z]['rm_week2'] ? '화' : '<span class="closed">화</span>';
        $rm_week3 = $arr_room[$z]['rm_week3'] ? '수' : '<span class="closed">수</span>';
        $rm_week4 = $arr_room[$z]['rm_week4'] ? '목' : '<span class="closed">목</span>';
        $rm_week5 = $arr_room[$z]['rm_week5'] ? '금' : '<span class="closed">금</span>';
        $rm_week6 = $arr_room[$z]['rm_week6'] ? '토' : '<span class="closed">토</span>';
        
        unset($rm_weekend);
        $rm_weekend = array();
        if ($rm_week0) $rm_weekend[] = $rm_week0;
        if ($rm_week1) $rm_weekend[] = $rm_week1;
        if ($rm_week2) $rm_weekend[] = $rm_week2;
        if ($rm_week3) $rm_weekend[] = $rm_week3;
        if ($rm_week4) $rm_weekend[] = $rm_week4;
        if ($rm_week5) $rm_weekend[] = $rm_week5;
        if ($rm_week6) $rm_weekend[] = $rm_week6;
    ?>

    <tr class="<?php echo $bg; ?>">
        <td class="td_chk">
            <input type="hidden" name="rm_ix[<?php echo $z ?>]" value="<?php echo $arr_room[$z]['rm_ix'] ?>">
            <input type="checkbox" name="chk[]" value="<?php echo $z ?>" id="chk_<?php echo $z ?>">
        </td>
        <td class="td_alignc">
            <?php

            //print_r($arr_room[$z]['img_src']);
            
            if ($arr_room[$z]['img_src']) { 
                echo '<img src="'.$arr_room[$z]['img_src'].'" />';
            }
            ?>
        </td>
        <td class="td_alignc"><?php echo $arr_room[$z]['rm_subject']; ?></td>
        <td class="td_alignc"><?php echo $arr_room[$z]['rm_holiday_use'] ? '허용' : '불가'; ?></td>
        <td class="td_alignc"><span class="weeks"><?php echo implode(',', $rm_weekend);?></span></td>
        <td class="td_alignr">
            <table cellspacing="0" border="1" class="tbl_into">
            <tbody>
            <tr>
                <th scope="row" width="30%">시간</th>
                <th scope="row" width="40%">요금</th>
                <th scope="row" width="30%">예약허용인원</th>
            </tr>
            <?php
            if (isset($arr_room[$z]['time'])) { 
                foreach ($arr_room[$z]['time'] as $key => $row) {
                ?>
                <tr>
                    <td><?php echo $row['rmt_time'];?></td>
                    <td><?php echo $row['rmt_price_type'].' '.number_format($row['rmt_price']);?></td>
                    <td><?php echo number_format($row['rmt_max_cnt']);?></td>
                </tr>
                <?php
                }
            }
            ?>
            </tbody>
            </table>
        </td>
        <td class="td_alignc"><?php echo $arr_room[$z]['rm_use'] ? '사용' : '사용안함';?></td>
        <td class="td_mngsmall">
            <a href="./rpage.php?code=wzb_room_form&w=u&amp;rm_ix=<?php echo $arr_room[$z]['rm_ix']; ?>&amp;<?php echo $qstr; ?>">수정</a>&nbsp;
            <a href="./reservation/wzb_room_form_update.php?w=d&amp;rm_ix=<?php echo $arr_room[$z]['rm_ix']; ?>&amp;<?php echo $qstr; ?>" onclick="return delete_confirm(this);">삭제</a> 
        </td>
    </tr>

    <?php
        }
    }
    else {
        echo '<tr><td colspan="'.$colspan.'" class="empty_table">자료가 없습니다.</td></tr>';
    }
    ?>
    </tbody>
    </table>
</div>

<div class="btn_list01 btn_list">
    <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value">
</div>

</form>
</div>
<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<script type="text/javascript">
<!--
    function getAction(f)
    {
        if (!is_checked("chk[]")) {
            alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
            return false;
        }

        if(document.pressed == "선택삭제") {
            if(!confirm("선택한 이용정보를 삭제처리 하시겠습니까?")) {
                return false;
            }
        }

        return true;
    }
//-->
</script>

<?php
include_once (TB_ADMIN_PATH.'/admin.tail.php');
?>