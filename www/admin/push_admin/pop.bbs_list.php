<?php
$sub_menu = '400300';
include_once('./_common.php');

auth_check($auth[$sub_menu], "r");

$g5['title'] = '게시물선택';
include_once (G5_PATH.'/head.sub.php');

$sql_search = '';
$qstr .= "&bo_table=".$bo_table;

if($sfl != '' && $stx != ''){
	$sql_search .= " and {$sfl} like '%{$stx}%'";
}

$rows = 15;
if($page == '')
	$page = 1;

$row = sql_fetch("select count(*) as cnt from $write_table where 1 = 1 ".$sql_search);
$total_count = $row['cnt'];
$total_page = ceil($total_count / $rows);

$rs = sql_query("select * from $write_table where 1 = 1 ".$sql_search." order by wr_num, wr_reply limit ".(($page -1 ) * $rows).",".$rows);

$listall = '<a href="'.$_SERVER['SCRIPT_NAME'].'?bo_table='.$bo_table.'" class="ov_listall">전체목록</a>';
?>
<style>
    .btn{
        display:inline-block;
        margin-bottom:10px;
    }
</style>
<div  style="padding:30px;">
    <div class="local_ov">
        <?php echo $listall; ?>
        <span class="btn_ov01"><span class="ov_txt">총</span><span class="ov_num"> <?php echo $total_count; ?>건</span></span>
    </div>

    <form name="flist" class="local_sch01 local_sch">
    <input type="hidden" name="bo_table" value="<?php echo $bo_table; ?>">

    <label for="sfl" class="sound_only">검색대상</label>
    <select name="sfl" id="sfl">
        <option value="wr_subject" <?php echo get_selected($sfl, 'wr_subject'); ?>>제목</option>
        <option value="wr_content" <?php echo get_selected($sfl, 'wr_content'); ?>>내용</option>
    </select>

    <label for="stx" class="sound_only">검색어</label>
    <input type="text" name="stx" value="<?php echo $stx; ?>" id="stx" class="frm_input">
    <input type="submit" value="검색" class="btn_submit">

    </form>
</div>

<div class="adm_tbl_head01 tbl_wrap" style="padding:0 30px;">
    <table>
    <caption><?php echo $g5['title']; ?> 목록</caption>
    <thead>
    <tr>
        <th>제목</th>        
        <th>작성일</th>
        <th>조회수</th>
        <th width="100">비고</th>
    </tr>
    </thead>
    <tbody>
	<?
	    for($i = 0; $row = sql_fetch_array($rs); $i++){
            if($bo_table == "event01"){
                
                $pu_url = G5_URL."/theme/ykf/mobile/09/event_view.php?bo_table=".$bo_table."&wr_id=".$row['wr_id'];
                $pu_url2 = G5_URL."/theme/ykf_store/mobile/01/my_event_view.php?bo_table=".$bo_table."&wr_id=".$row['wr_id']."&type=all";

            }else if($bo_table == "event02"){

                $pu_url = G5_URL."/theme/ykf/mobile/09/notice_view.php?wr_id=".$row['wr_id'];
                $pu_url2 = G5_URL."/theme/ykf_store/mobile/01/my_event_view.php?bo_table=".$bo_table."&wr_id=".$row['wr_id']."&type=all";

            }else{

                $pu_url = G5_URL."/theme/ykf/mobile/09/notice_view.php?wr_id=".$row['wr_id'];
                $pu_url2 = G5_URL."/theme/ykf_store/mobile/09/notice_view.php?wr_id=".$row['wr_id'];

            }
	?>
    <tr>
        <td><a href="<?=G5_BBS_URL?>/board.php?bo_table=<?=$bo_table?>&wr_id=<?=$row['wr_id']?>" target="_blank"><?=$row['wr_subject']?></a></td>        
        <td class="td_date"><?=substr($row['wr_datetime'], 0, 10)?></td>
        <td class="td_num"><?=$row['wr_hit']?></td>
        <td class="td_num">
            <a href="#" pu_url="<? echo $pu_url; ?>" onclick="return false" class="user btn btn_03">사용자</a>
            <a href="#" pu_url="<? echo $pu_url2; ?>" onclick="return false" class="store btn btn_03">가맹점</a>
        </td>
    </tr>
	<? } ?>
	<? if($i == 0){ echo '<tr><td colspan="5" class="empty_table">자료가 없습니다.</td></tr>'; }?>
	</tbody>
	</table>
</div>

<?php echo get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>

<script>
$(document).on('click', '.user', function(){
	$("#pu_url", opener.document).val($(this).attr("pu_url"));
	window.close();
});

$(document).on('click', '.store', function(){
	$("#pu_url2", opener.document).val($(this).attr("pu_url"));
	window.close();
});
</script>

<?php
include_once (G5_PATH.'/tail.sub.php');
?>
