<?php
$sub_menu = '790530';
include_once('./_common.php');

$sql_common = " from {$g5['wzb_room_option_table']} ";

$sql_search = " where (1) ";

$is_sch = false; // 검색여부

$sch_cp_ix = 1; // 단독형 고정
if ($sch_name) {
    $sql_search .= " and rmo_name like '%".$sch_name."%' ";
    $qstr .= "&sch_name=".$sch_name;
    $is_sch = true;
}

if ($sch_unit) {
    $sql_search .= " and rmo_unit = '".$sch_unit."' ";
    $qstr .= "&sch_unit=".$sch_unit;
    $is_sch = true;
}

if ($sch_store) {
    $sql_search .= " and store_mb_id like '%".$sch_store."%' ";
    $qstr .= "&sch_store=".$sch_store;
    $is_sch = true;
}

if ($sch_design) {
    $sql_search .= " and design_name like '%".$sch_design."%' ";
    $qstr .= "&sch_design=".$sch_design;
    $is_sch = true;
}

if ($sch_required != '') {
    $sql_search .= " and rmo_required = '".$sch_required."' ";
    $qstr .= "&sch_required=".$sch_required;
    $is_sch = true;
}

if ($sch_use != '') {
    $sql_search .= " and rmo_use = '".$sch_use."' ";
    $qstr .= "&sch_use=".$sch_use;
    $is_sch = true;
}

if ($sch_cp_ix) {
    $sql_search .= " and cp_ix = '".$sch_cp_ix."' ";
    $qstr .= "&sch_cp_ix=".$sch_cp_ix;
    $is_sch = true;
}

if (!$sst) {
    $sst = "rmo_sort asc, rmo_ix";
    $sod = "desc";
}

$sql_order = " order by {$sst} {$sod} ";

$sql = " select count(*) as cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = '40';
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

unset($arr_option);
$arr_option = array();
$query = "select * {$sql_common} {$sql_search} {$sql_order} limit {$from_record}, {$rows} ";
$res = sql_query($query);
while($row = sql_fetch_array($res)) {
    $query2 = "select cp_title from {$g5['wzb_corp_table']} where cp_ix = '{$row['cp_ix']}' "; // 업체정보
    $row2 = sql_fetch($query2);
    $row['cp_title'] = $row2['cp_title'];
    $arr_option[] = $row;
}
$cnt_option = count($arr_option);
if ($res) sql_free_result($res);

$g5['title'] = '옵션정보 목록보기';
include_once (MS_ADMIN_PATH.'/admin.head.php');

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'" class="ov_listall">전체목록</a>';
?>

<div class="local_ov01 local_ov">
    <?php echo $listall; ?>
    전체 <?php echo number_format($total_count); ?>건
</div>

<form name="fsearch" id="fsearch" class="local_sch02 local_sch" method="get">

<input type="hidden" name="sch_cp_ix" id="sch_cp_ix" value="<?php echo $sch_cp_ix;?>" />
<input type="hidden" name="code" id="code" value="wzb_room_option_list" />
<div>
    <strong>옵션명</strong>
    <input type="text" name="sch_name" id="sch_name" value="<?php echo $sch_name;?>" class="frm_input" style="width:170px;" maxlength="30" />
</div>
<div>
    <strong>옵션단위</strong>
    <input type="text" name="sch_unit" id="sch_unit" value="<?php echo $sch_unit;?>" class="frm_input" style="width:70px;" maxlength="10" />
</div>
<div>
    <strong>가맹점 아이디</strong>
    <input type="text" name="sch_store" id="sch_store" value="<?php echo $sch_store;?>" class="frm_input" style="width:170px;" maxlength="30" />
</div>
<div>
    <strong>디자이너명</strong>
    <input type="text" name="sch_design" id="sch_design" value="<?php echo $sch_design;?>" class="frm_input" style="width:170px;" maxlength="30" />
</div>
<div>
    <strong>필수여부</strong>
    <input type="radio" name="sch_required" value="" id="sch_required1" <?php echo ($sch_required == "" ? "checked=checked" : "");?>>
    <label for="sch_required1">전체</label>
    <input type="radio" name="sch_required" value="1" id="sch_required2" <?php echo ($sch_required == "1" ? "checked=checked" : "");?>>
    <label for="sch_required2">필수</label>
    <input type="radio" name="sch_required" value="0" id="sch_required3" <?php echo ($sch_required == "0" ? "checked=checked" : "");?>>
    <label for="sch_required3">필수아님</label>
</div>
<div class="sch_last">
    <strong>사용여부</strong>
    <input type="radio" name="sch_use" value="" id="sch_use1" <?php echo ($sch_use == "" ? "checked=checked" : "");?>>
    <label for="sch_use1">전체</label>
    <input type="radio" name="sch_use" value="1" id="sch_use2" <?php echo ($sch_use == "1" ? "checked=checked" : "");?>>
    <label for="sch_use2">사용</label>
    <input type="radio" name="sch_use" value="0" id="sch_use3" <?php echo ($sch_use == "0" ? "checked=checked" : "");?>>
    <label for="sch_use3">사용안함</label>
    <input type="submit" value="검색" class="btn_submit">
</div>
</form>

<div class="btn_add01 btn_add btn_fixed_top">
    <a href="./wzb_room_option_form.php?code=wzb_room_option_list<?php echo $qstr;?>" id="coupon_add">옵션 등록</a>
</div>

<form name="frm">
<input type="hidden" name="sort1" value="<?php echo $sort1; ?>">
<input type="hidden" name="sort2" value="<?php echo $sort2; ?>">
<input type="hidden" name="sel_field" value="<?php echo $sel_field; ?>">
<input type="hidden" name="search" value="<?php echo $search; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">

<div class="tbl_head01 tbl_wrap">
    <table cellpadding="0" cellspacing="0" border="0">
    <thead> 
    <tr>
        <th scope="col">가맹점아이디</th> 
	<th scope="col">디자이너명</th> 
        <th scope="col">옵션명</th> 
        <th scope="col">옵션선택수량</th>
        <th scope="col">옵션단위</th>
        <th scope="col">금액</th>
        <th scope="col">필수여부</th>
        <th scope="col">사용여부</th>
        <th scope="col">순서</th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
    if ($cnt_option > 0) {
        for ($z = 0; $z < $cnt_option; $z++) { 
            ?>
            <tr>
                <td><?php echo $arr_option[$z]['store_mb_id'];?></td>
                <td><?php echo $arr_option[$z]['design_name'];?></td>
                <td>
                    <?php echo $arr_option[$z]['rmo_name'] .($arr_option[$z]['rmo_memo'] ? ' ('.$arr_option[$z]['rmo_memo'].')' : '');?>
                </td>
                <td class="td_alignc"><span class="sm number"><?php echo $arr_option[$z]['rmo_cnt'];?></span></td>
                <td class="td_alignc"><?php echo $arr_option[$z]['rmo_unit'];?></td>
                <td class="td_alignc"><span class="sm number"><?php echo number_format($arr_option[$z]['rmo_price']);?></span></td>
                <td class="td_alignc"><?php echo $arr_option[$z]['rmo_required'] ? '필수' : '필수아님';?></td>
                <td class="td_alignc"><?php echo $arr_option[$z]['rmo_use'] ? '사용' : '사용안함';?></td>
                <td class="td_alignc"><?php echo $arr_option[$z]['rmo_sort']?></td>
                <td class="td_mngsmall">
                    <a href="./wzb_room_option_form.php?code=wzb_room_option_list&mode=edit&rmo_ix=<?php echo $arr_option[$z]['rmo_ix'].$qstr;?>">수정</a>
                    <a href="./wzb_room_option_form_update.php?mode=del&rmo_ix=<?php echo $arr_option[$z]['rmo_ix'].$qstr;?>" onclick="return delete_confirm(this);">삭제</a>
                </td>
            </tr> 
            <?php
        }
    }
    else {
        ?>
        <tr>
            <td colspan="8" class="td_alignc">데이터가 존재하지 않습니다.</td>
        </tr> 
        <?php
    }
    ?>

    </tbody>
    </table>
</div>

</form>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<?php
include_once (MS_ADMIN_PATH.'/admin.tail.php');
?>