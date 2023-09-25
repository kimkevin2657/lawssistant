<?php
include_once('./_common.php');

$store_mb_id = $_GET['store_mb_id'];

$sql_common = " FROM g5_wzb3_room ";

$sql_search = " where store_mb_id = '{$store_mb_id}' ";

if($stx){
    $sql_search .= " and rm_subject like '%{$stx}%' ";
}

$sql = " select count(*) as cnt {$sql_common} {$sql_search} ";
$row = sql_fetch($sql);
$page = $_GET['page'];
$total_count    = $row['cnt'];

$rows = 10;
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = sql_query("select * {$sql_common} {$sql_search} limit {$from_record}, {$rows}");

?>
<style>
.sel_id{
    border: 1px solid #ccc;
    padding: 5px;
    background: #eee;
    text-decoration: none;
    cursor:pointer;
}
</style>
<div class="tbl_head01 tbl_wrap">
    <form name="fsearch" id="fsearch" class="local_sch02 local_sch" method="get">
    <input type="hidden" name="store_mb_id" value="<? echo $_GET['store_mb_id']; ?>">
    <div>
        <strong>검색어</strong>
        <input type="text" name="stx" id="stx" value="<?php echo $stx;?>" class="frm_input" style="width:170px;" maxlength="50" />
        <input type="submit" value="검색">
    </div>
    </form>
    <table>
    <thead>
    <tr>
        <th scope="col">디자이너명</th>
        <th scope="col">관리</th>
    </tr>
    </thead>
    <tbody>
    <?php
        for($i=0; $row = sql_fetch_array($sql); $i++){
    ?>
    <tr>
        <td><? echo $row['rm_subject']; ?></td>
        <td><span class="sel_id" rm_subject="<? echo $row['rm_subject']; ?>" rm_ix="<? echo $row['rm_ix']; ?>">선택</span></td>
    </tr>
    <? } ?>
    </tbody>
    </table>
</div>
<script>
    $(".sel_id").on("click", function(){
        rm_subject = $(this).attr("rm_subject");
        rm_ix = $(this).attr("rm_ix");
        $("#design_idx", opener.document).val(rm_ix);
        $("#rm_subject", opener.document).val(rm_subject);
        $(".design_name", opener.document).text(rm_subject);
	self.close();
    })
</script>
<?php echo get_paging(10, $page, $total_page, "{$_SERVER['SCRIPT_NAME']}?$qstr&amp;page="); ?>