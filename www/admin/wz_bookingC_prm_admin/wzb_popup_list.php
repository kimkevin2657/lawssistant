<?php
$sub_menu = '790710';
include_once('./_common.php');


$sql_common = " from {$g5['wzb_corp_popup_table']} ";

$sql_search = " where (1) ";

$is_sch = false; // 검색여부

$sch_cp_ix = 1; // 단독형 고정
if ($sch_cp_ix) {
    $sql_search .= " and cp_ix = '".$sch_cp_ix."' ";
    $qstr .= "&sch_cp_ix=".$sch_cp_ix;
    $is_sch = true;
}

// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt " . $sql_common ." ". $sql_search;
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$sql = "select * {$sql_common} {$sql_search} order by nw_id desc ";
$result = sql_query($sql);

$g5['title'] = '팝업창관리 관리';
include_once (MS_ADMIN_PATH.'/admin.head.php');
?>

<div class="local_ov01 local_ov">전체 <?php echo $total_count; ?>건</div>

<div class="btn_add01 btn_add">
    <a href="./wzb_popup_form.php?code=wzb_popup_list<?php echo $qstr;?>">새창관리추가</a>
</div>

<div class="tbl_head01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th scope="col">번호</th>
        <th scope="col">제목</th>
        <th scope="col">접속기기</th>
        <th scope="col">시작일시</th>
        <th scope="col">종료일시</th>
        <th scope="col">시간</th>
        <th scope="col">Left</th>
        <th scope="col">Top</th>
        <th scope="col">Width</th>
        <th scope="col">Height</th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    for ($i=0; $row=sql_fetch_array($result); $i++) {
        $bg = 'bg'.($i%2);

        switch($row['nw_device']) {
            case 'pc':
                $nw_device = 'PC';
                break;
            case 'mobile':
                $nw_device = '모바일';
                break;
            default:
                $nw_device = '모두';
                break;
        }

        $query2 = "select cp_title from {$g5['wzb_corp_table']} where cp_ix = '{$row['cp_ix']}' "; // 업체정보
        $row2 = sql_fetch($query2);
        $row['cp_title'] = $row2['cp_title'];
    ?>
    <tr class="<?php echo $bg; ?>">
        <td class="td_num"><?php echo $row['nw_id']; ?></td>
        <td><?php echo $row['nw_subject']; ?></td>
        <td class="td_device"><?php echo $nw_device; ?></td>
        <td class="td_datetime"><?php echo substr($row['nw_begin_time'],2,14); ?></td>
        <td class="td_datetime"><?php echo substr($row['nw_end_time'],2,14); ?></td>
        <td class="td_num"><?php echo $row['nw_disable_hours']; ?>시간</td>
        <td class="td_num"><?php echo $row['nw_left']; ?>px</td>
        <td class="td_num"><?php echo $row['nw_top']; ?>px</td>
        <td class="td_num"><?php echo $row['nw_width']; ?>px</td>
        <td class="td_num"><?php echo $row['nw_height']; ?>px</td>
        <td class="td_mngsmall">
            <a href="./wzb_popup_form.php?code=wzb_popup_list&w=u&nw_id=<?php echo $row['nw_id']; ?>"><span class="sound_only"><?php echo $row['nw_subject']; ?> </span>수정</a>
            <a href="./wzb_popup_form_update.php?w=d&nw_id=<?php echo $row['nw_id']; ?>" onclick="return delete_confirm(this);"><span class="sound_only"><?php echo $row['nw_subject']; ?> </span>삭제</a>
        </td>
    </tr>
    <?php
    }

    if ($i == 0) {
        echo '<tr><td colspan="11" class="empty_table">자료가 한건도 없습니다.</td></tr>';
    }
    ?>
    </tbody>
    </table>
</div>


<?php
include_once (MS_ADMIN_PATH.'/admin.tail.php');
?>
