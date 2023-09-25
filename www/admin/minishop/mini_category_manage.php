<?php
if(!defined('_MALLSET_')) exit;


$sql_common = " from category_manage ";
$sql_search = " where (1) ";
if($stx){
    $sql_search .= " and category_name like '%{$stx}%' ";
}


// 테이블의 전체 레코드수만 얻음
$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$total_count = $row['cnt'];

$rows = 30;
$total_page = ceil($total_count / $rows); // 전체 페이지 계산
if($page == "") { $page = 1; } // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

$sql = " select * $sql_common $sql_search $sql_order limit $from_record, $rows ";
$result = sql_query($sql);

include_once(MS_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>

<h2>기본검색</h2>
<form name="fsearch" id="fsearch" method="get">
    <input type="hidden" name="code" value="<?php echo $code; ?>">
    <div class="tbl_frm01">
        <table>
            <colgroup>
                <col class="w100">
                <col>
            </colgroup>
            <tbody>
            <tr>
                <th scope="row">검색어</th>
                <td>
                    <select name="sfl">
                        <?php echo option_selected('category_name', $sfl, '분류명'); ?>
                    </select>
                    <input type="text" name="stx" value="<?php echo $stx; ?>" class="frm_input" size="30">
                    <input type="submit" value="검색" class="btn_lsmall">
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</form>

<form name="fpcenter" id="fpcenter" method="post" action="./minishop/mini_category_list_update.php" onsubmit="return fpcenter_submit(this);">
    <input type="hidden" name="q1" value="<?php echo $q1; ?>">
    <input type="hidden" name="page" value="<?php echo $page; ?>">
    <div class="local_frm01" style="border-top:none;text-align:right;">
        <a href="./minishop.php?code=category_manage_form" style="padding: 5px;
    background: #333;
    color: white;">분류추가</a>
    </div>
    <div class="tbl_head02">
        <table id="minishop_list">
            <thead>
            <tr>
                <th scope="col"><input type="checkbox" name="chkall" value="1" onclick="check_all(this.form);"></th>
                <th scope="col">분류명</th>
                <th scope="col">등록일시</th>
                <th scope="col">관리</th>
            </tr>
            </thead>
            <?php
            for($i=0; $row=sql_fetch_array($result); $i++) {

                if($i==0)
                    echo '<tbody class="list">'.PHP_EOL;

                $bg = 'list'.($i%2);
                ?>
                <tr class="<?php echo $bg; ?>">
                    <td>
                        <input type="hidden" name="cm_idx[]" value="<? echo $row['cm_idx']; ?>">
                        <label for="chk_<?php echo $i; ?>" class="sound_only"><?php echo $row['id']; ?> 님</label>
                        <input type="checkbox" name="chk[]" value="<?php echo $i; ?>" id="chk_<?php echo $i; ?>">
                    </td>
                    <td><? echo $row['category_name']; ?></td>
                    <td><? echo $row['mk_datetime']; ?></td>
                    <td><a href="./minishop.php?code=category_manage_form&w=u&cm_idx=<? echo $row['cm_idx']; ?>">수정</a></td>
                </tr>
                <?php
            }
            if($i==0)
                echo '<tbody><tr><td colspan="3" class="empty_table">자료가 없습니다.</td></tr>';
            ?>
            </tbody>
        </table>
    </div>

    <div class="btn_fixed_top">
        <input type="submit" name="act_button" value="선택삭제" onclick="document.pressed=this.value" class="btn btn_02">
    </div>

</form>

<?php
echo get_paging($config['write_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
?>


<script>
    function fpcenter_submit(f)
    {
        if(!is_checked("chk[]")) {
            alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
            return false;
        }

        return true;
    }
</script>
