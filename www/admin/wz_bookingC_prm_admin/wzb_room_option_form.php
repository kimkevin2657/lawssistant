<?php
$sub_menu = '790530';
include_once('./_common.php');

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

$qstr .= "&sch_cp_ix=".$sch_cp_ix."&sch_name=".$sch_name."&sch_unit=".$sch_unit."&sch_required=".$sch_required."&sch_use=".$sch_use;

$pg_title = ADMIN_MENU11;
$pg_num = 11;
$snb_icon = "<i class=\"fa fa-cogs\"></i>";

if($member['id'] != encrypted_admin() && !$member['auth_'.$pg_num]) {
	alert("접근 권한이 없습니다.");
}

if($code == "wzb_booking_list")			$pg_title2 = ADMIN_MENU11_01;
if($code == "wzb_booking_status")				$pg_title2 = ADMIN_MENU11_02;
if($code == "wzb_booking_calendar")				$pg_title2 = ADMIN_MENU11_03;
if($code == "wzb_room_list")			$pg_title2 = ADMIN_MENU11_04;
if($code == "wzb_room_status")	$pg_title2 = ADMIN_MENU11_05;
if($code == "wzb_price_list")				$pg_title2 = ADMIN_MENU11_06;
if($code == "wzb_room_option_list")			$pg_title2 = ADMIN_MENU11_07;
if($code == "wzb_holiday_list")			$pg_title2 = ADMIN_MENU11_08;
if($code == "wzb_pay_list")				$pg_title2 = ADMIN_MENU11_09;
if($code == "wzb_popup_list")			$pg_title2 = ADMIN_MENU11_10;
if($code == "wzb_config")			$pg_title2 = ADMIN_MENU11_11;

$g5['title'] = '옵션정보 등록/수정';
include_once(MS_ADMIN_PATH."/admin_topmenu.php");
include_once (MS_ADMIN_PATH.'/admin.head.php');

$store2 = sql_fetch("SELECT * FROM shop_member where id = '{$rmo['store_mb_id']}' ");

?>
<style>
.search_id, .search_id2{
    border: 1px solid #ccc;
    padding: 5px;
    background: #eee;
    text-decoration: none;
    cursor:pointer;
}
</style>
<div class="s_wrap">
<h1><?php echo $pg_title2; ?></h1>
<form method="post" name="frm" id="frm" action="./wzb_room_option_form_update.php?<?php echo $qstr;?>" onsubmit="return getAction(this);">
<input type="hidden" name="code" value="<?php echo $code ?>">
<input type="hidden" name="mode" value="<?php echo $mode ?>">
<input type="hidden" name="rmo_ix" value="<?php echo $rmo_ix ?>">
    
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
        <th scope="row">가맹점선택</th>
        <td colspan="9">
            <span class="store_name"><? echo $store2['name']; ?></span>
            <span class="search_id">검색</span>
            <input type="hidden" name="store_mb_id" id="store_mb_id" value="<? echo $rm['store_mb_id']; ?>">
        </td>
    </tr>
    <tr>
        <th scope="row">디자이너선택</th>
        <td>
            <span class="design_name"><? echo $rmo['design_name']; ?></span>
            <span class="search_id2">검색</span>
            <input type="hidden" name="design_idx" id="design_idx" value="<? echo $rmo['design_idx']; ?>">
            <input type="hidden" name="rm_subject" id="rm_subject" value="<? echo $rmo['design_name']; ?>">
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
    <a href="./wzb_booking_list2.php?code=wzb_room_option_list<?php echo $qstr;?>">목록</a>
</div>

</form>
</div>
<script type="text/javascript">

$(".search_id").on("click", function(){
    window.open("./search_id.php","search_id", "width=600,height=500,left=300,top=200");
})

$(".search_id2").on("click", function(){
    store_mb_id = $("#store_mb_id").val();
    if(store_mb_id == ""){
        alert("가맹점을 선택하셔야합니다.");
        return false;
    }
    window.open("./search_id2.php?store_mb_id="+store_mb_id,"search_id", "width=600,height=500,left=300,top=200");
})

<!--
    function getAction(f) {
        return true;
    }
//-->
</script>
<?php
include_once(MS_ADMIN_PATH."/wz_bookingC_prm_admin/admin_tail_config.php");
?>
<!-- <?php
include_once (MS_ADMIN_PATH.'/admin.tail.php');
?> -->