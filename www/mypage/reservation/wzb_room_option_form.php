<?php
if(!defined('_TUBEWEB_')) exit;

$rmo_ix = (int)$_GET['rmo_ix'];

if ($mode == 'edit') { 
    $sql = " select * from {$g5['wzb_room_option_table']} where rmo_ix = '$rmo_ix' ";

    $rmo = sql_fetch($sql);
    if (!$rmo['rmo_ix']) alert('등록된 자료가 없습니다.', './wzb_room_option_list.php');    
} 
else {
    if ($sch_cp_ix) { 
        $rmo['cp_ix'] = $sch_cp_ix;
    } 
}

//echo $rmo['design_idx'];

$qstr .= "&sch_cp_ix=".$sch_cp_ix."&sch_name=".$sch_name."&sch_unit=".$sch_unit."&sch_required=".$sch_required."&sch_use=".$sch_use;


$pg_title = '옵션정보 등록/수정';
include_once("./admin_head.sub.php");
?>
<div class="s_wrap">
<form method="post" name="frm" id="frm" action="./reservation/wzb_room_option_form_update.php?<?php echo $qstr;?>" onsubmit="return getAction(this);">
<input type="hidden" name="code" value="<?php echo $code ?>">
<input type="hidden" name="mode" value="<?php echo $mode ?>">
<input type="hidden" name="rmo_ix" value="<?php echo $rmo_ix ?>">
<input type="hidden" name="store_mb_id" value="<?php echo $member['id'] ?>">  
<h2 class="h2_frm">옵션상세정보</h2>

<div class="tbl_frm01 tbl_wrap">
    
    <table cellpadding="0" cellspacing="0" border="0">
    <colgroup>
        <col class="grid_4">
        <col>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>

    <input type="hidden" name="cp_ix" id="cp_ix" value="<?php echo $rmo['cp_ix'];?>" />
    <tr>
        <th>디자이너선택</th>
        <td colspan="3">
            <select name="store_mb_id">
                <?
                    $sql = sql_query("SELECT * FROM g5_wzb3_room where store_mb_id = '{$member['id']}' ");
                    while($row = sql_fetch_array($sql)){
                        $chkKey = $row['store_mb_id']."|".$row['rm_ix'];
                ?>
                <option value="<? echo $row['store_mb_id']; ?>|<? echo $row['rm_ix']; ?>|<? echo $row['rm_subject']; ?>" <? echo $row['rm_ix'] == $rmo['design_idx'] ? "selected":""; ?> ><? echo $row['rm_subject']; ?></option>
            <? } ?>
            </select>
        </td>
    </tr> 
    <tr>
        <th>옵션명</th>
        <td colspan="3">
            <input type="text" name="rmo_name" id="rmo_name" value="<?php echo $rmo['rmo_name'];?>" class="frm_input required" required style="width:260px;" maxlength="100" />
        </td>
    </tr> 
    <tr>
        <th>옵션한줄설명</th>
        <td colspan="3">
            <input type="text" name="rmo_memo" id="rmo_memo" value="<?php echo $rmo['rmo_memo'];?>" class="frm_input" style="width:460px;" maxlength="100" />
        </td>
    </tr> 
    <tr>
        <th>최대선택수량</th>
        <td>
            <input type="text" name="rmo_cnt" id="rmo_cnt" value="<?php echo $rmo['rmo_cnt'];?>" class="frm_input required number" maxlength="10" size="4" />
        </td>
        <th>단위</th>
        <td>
            <input type="text" name="rmo_unit" id="rmo_unit" value="<?php echo $rmo['rmo_unit'];?>" class="frm_input required number" maxlength="10" size="4" />
            (예: ea, 셋트)
        </td>
    </tr> 
    <tr>
        <th>금액</th>
        <td colspan="3">
            <input type="text" name="rmo_price" id="rmo_price" value="<?php echo $rmo['rmo_price'];?>" class="frm_input required number" style="width:90px;" maxlength="100" /> 원
        </td>
    </tr> 
    <tr>
        <th>필수여부</th>
        <td>
            <label><input type="checkbox" name="rmo_required" id="rmo_required" value="1" <?php echo $rmo['rmo_required'] ? 'checked=checked' : '';?> /> 필수</label>
        </td>
        <th>사용여부</th>
        <td>
            <label><input type="checkbox" name="rmo_use" id="rmo_use" value="1" <?php echo $rmo['rmo_use'] ? 'checked=checked' : '';?> /> 사용</label>
        </td>
    </tr> 
    <tr>
        <th>순서</th>
        <td colspan="3">
            <input type="text" name="rmo_sort" id="rmo_sort" value="<?php echo $rmo['rmo_sort'];?>" class="frm_input required" style="width:30px;" maxlength="5" />
        </td>
    </tr> 
    </tbody>
    </table>

</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="저장하기" class="btn_submit" accesskey="s">
    <a href="./rpage.php?code=wzb_room_option_list<?php echo $qstr;?>">목록</a>
</div>

</form>
</div>
<script type="text/javascript">
<!--
    function getAction(f) {
        return true;
    }
//-->
</script>
<?php
include_once("./admin_tail.sub.php");
?>