<?php
if(!defined('_MALLSET_')) exit;

if($w == "u"){
    $sql_common = " from category_manage ";

    $w = $_GET['w'];
    $cm_idx = $_GET['cm_idx'];

    $sql_search = " where (1) ";

    $sql_search .= " and cm_idx = '{$cm_idx}' ";
    $row = sql_fetch("SELECT * {$sql_common} {$sql_search}");
    
}

include_once(MS_PLUGIN_PATH.'/jquery-ui/datepicker.php');
?>
<div class="holder--form-center">
    <form name="frm_center" id="frm_center" action="./minishop/mini_category_formupdate.php" method="POST">
    <input type="hidden" name="w" value="<? echo $w; ?>">
    <input type="hidden" name="cm_idx" value="<? echo $cm_idx; ?>">
        <div class="tbl_frm01">
            <table>
                <colgroup>
                    <col class="w100">
                    <col>
                </colgroup>
                <tbody>
                <tr>
                    <th><label for="category_name">분류명</label></th>
                    <td>
                    <input type="text" class="frm_input required" id="category_name" name="category_name" required="required" value="<? echo $row['category_name']; ?>">
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div style="margin-top:10px; text-align:center;">
            <input type="submit" value="저장">
            <a href="./minishop.php?code=category_manage">목록</a>
        </div>
    </form>
</div>