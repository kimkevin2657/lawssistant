<?php
$sub_menu = '790710';
include_once('./_common.php');
include_once(G5_EDITOR_LIB);

$nw_id = preg_replace('/[^0-9]/', '', $nw_id);

$html_title = "팝업창관리";

if ($w == "u")
{
    $html_title .= " 수정";
    $sql = " select * from {$g5['wzb_corp_popup_table']} where nw_id = '$nw_id' ";
    $nw = sql_fetch($sql);
    if (!$nw['nw_id']) alert("등록된 자료가 없습니다.");
}
else
{
    if ($sch_cp_ix) { 
        $nw['cp_ix'] = $sch_cp_ix;
    } 
    $html_title .= " 입력";
    $nw['nw_device'] = 'both';
    $nw['nw_disable_hours'] = 24;
    $nw['nw_left']   = 10;
    $nw['nw_top']    = 10;
    $nw['nw_width']  = 450;
    $nw['nw_height'] = 500;
    $nw['nw_content_html'] = 2;
}

$qstr .= "&sch_cp_ix=".$sch_cp_ix;

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

$g5['title'] = $html_title;
include_once(TB_ADMIN_PATH."/admin_topmenu.php");
include_once (TB_ADMIN_PATH.'/admin.head.php');

?>
<div class="s_wrap">
<h1><?php echo $pg_title2; ?></h1>
<form name="frmnewwin" action="./wzb_popup_form_update.php" onsubmit="return frmnewwin_check(this);" method="post">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="nw_id" value="<?php echo $nw_id; ?>">
<input type="hidden" name="token" value="">

<div class="local_desc01 local_desc">
    <p>초기화면 접속 시 자동으로 뜰 팝업창관리를 설정합니다.</p>
</div>

<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption><?php echo $g5['title']; ?></caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    
    <input type="hidden" name="cp_ix" id="cp_ix" value="<?php echo $nw['cp_ix'];?>" />

    <tr>
        <th scope="row"><label for="nw_device">접속기기</label></th>
        <td>
            <?php echo help("팝업창관리가 표시될 접속기기를 설정합니다."); ?>
            <select name="nw_device" id="nw_device">
                <option value="both"<?php echo get_selected($nw['nw_device'], 'both', true); ?>>PC와 모바일</option>
                <option value="pc"<?php echo get_selected($nw['nw_device'], 'pc'); ?>>PC</option>
                <option value="mobile"<?php echo get_selected($nw['nw_device'], 'mobile'); ?>>모바일</option>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_disable_hours">시간<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <?php echo help("고객이 다시 보지 않음을 선택할 시 몇 시간동안 팝업창관리를 보여주지 않을지 설정합니다."); ?>
            <input type="text" name="nw_disable_hours" value="<?php echo $nw['nw_disable_hours']; ?>" id="nw_disable_hours" required class="frm_input required" size="5"> 시간
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_begin_time">시작일시<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_begin_time" value="<?php echo $nw['nw_begin_time']; ?>" id="nw_begin_time" required class="frm_input required" size="21" maxlength="19">
            <input type="checkbox" name="nw_begin_chk" value="<?php echo date("Y-m-d 00:00:00", G5_SERVER_TIME); ?>" id="nw_begin_chk" onclick="if (this.checked == true) this.form.nw_begin_time.value=this.form.nw_begin_chk.value; else this.form.nw_begin_time.value = this.form.nw_begin_time.defaultValue;">
            <label for="nw_begin_chk">시작일시를 오늘로</label>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_end_time">종료일시<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_end_time" value="<?php echo $nw['nw_end_time']; ?>" id="nw_end_time" required class="frm_input required" size="21" maxlength="19">
            <input type="checkbox" name="nw_end_chk" value="<?php echo date("Y-m-d 23:59:59", G5_SERVER_TIME+(60*60*24*7)); ?>" id="nw_end_chk" onclick="if (this.checked == true) this.form.nw_end_time.value=this.form.nw_end_chk.value; else this.form.nw_end_time.value = this.form.nw_end_time.defaultValue;">
            <label for="nw_end_chk">종료일시를 오늘로부터 7일 후로</label>
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_left">팝업창관리 좌측 위치<strong class="sound_only"> 필수</strong></label></th>
        <td>
           <input type="text" name="nw_left" value="<?php echo $nw['nw_left']; ?>" id="nw_left" required class="frm_input required" size="5"> px
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_top">팝업창관리 상단 위치<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_top" value="<?php echo $nw['nw_top']; ?>" id="nw_top" required class="frm_input required"  size="5"> px
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_width">팝업창관리 넓이<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_width" value="<?php echo $nw['nw_width'] ?>" id="nw_width" required class="frm_input required" size="5"> px
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_height">팝업창관리 높이<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_height" value="<?php echo $nw['nw_height'] ?>" id="nw_height" required class="frm_input required" size="5"> px
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_subject">팝업 제목<strong class="sound_only"> 필수</strong></label></th>
        <td>
            <input type="text" name="nw_subject" value="<?php echo stripslashes($nw['nw_subject']) ?>" id="nw_subject" required class="frm_input required" size="80">
        </td>
    </tr>
    <tr>
        <th scope="row"><label for="nw_content">내용</label></th>
        <td><?php echo editor_html('nw_content', get_text($nw['nw_content'], 0)); ?></td>
    </tr>
    </tbody>
    </table>
</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="확인" class="btn_submit" accesskey="s">
    <a href="./wzb_booking_list2.php?code=wzb_popup_list<?php echo $qstr; ?>">목록</a>
</div>
</form>
</div>
<script>
function frmnewwin_check(f)
{
    errmsg = "";
    errfld = "";

    <?php echo get_editor_js('nw_content'); ?>

    check_field(f.nw_subject, "제목을 입력하세요.");

    if (errmsg != "") {
        alert(errmsg);
        errfld.focus();
        return false;
    }
    return true;
}
</script>

<?php
include_once(TB_ADMIN_PATH."/wz_bookingC_prm_admin/admin_tail_config.php");
//include_once (TB_ADMIN_PATH.'/admin.tail.php');
?>
