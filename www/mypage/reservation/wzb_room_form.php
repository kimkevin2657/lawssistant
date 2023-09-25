<?php
if(!defined('_TUBEWEB_')) exit;
//auth_check($auth[$sub_menu], "w");

$rm_ix = (int)$_GET['rm_ix'];
$sch_cp_ix = 1;

if ($sch_cp_ix) {
    $qstr .= "&sch_cp_ix=".$sch_cp_ix;
}
if ($sch_subject) {
    $qstr .= "&sch_subject=".$sch_subject;
}
unset($arr_img);
$arr_img = array();

if ($w == 'u') {
    $html_title = '이용정보 수정';

    $sql = " select * from {$g5['wzb_room_table']} where rm_ix = '$rm_ix' ";
    $rm = sql_fetch($sql);

    //echo $sql;

    if (!$rm['rm_ix']){
        alert('등록된 자료가 없습니다.', 'wzb_room_list.php');
    }

    $query = "select * from {$g5['wzb_room_photo_table']} where rm_ix = '{$rm_ix}' order by rmp_ix asc";
    $res = sql_query($query);
    while($row = sql_fetch_array($res)) {
        $arr_img[] = $row;
    }
    $cnt_img = count($arr_img);
    if ($res) sql_free_result($res);

    $sch_cp_ix = $rm['cp_ix'];

}
else {
    $html_title = '이용정보 입력';
	$rm['cp_ix'] = $sch_cp_ix;
}

// 시간정보
unset($arr_se);
$arr_se = array();
$cnt_se = 0;

$query = " select * from {$g5['wzb_room_time_table']} where rm_ix = '{$rm_ix}' order by rmt_time asc, rmt_ix desc ";
$res = sql_query($query);
while($row = sql_fetch_array($res)) { 
    $arr_se[] = $row;
} 
$cnt_se = count($arr_se);
if ($res) sql_free_result($res);


// 시간선택
$timesH = '';
for ($i=0;$i<=23;$i++) { 
    $tm = sprintf('%02d', $i);
    $timesH .= '<option value="'.$tm.'">'.$tm.'</option>';
} 
$timesM = '';
for ($i=0;$i<=59;$i++) { 
    $tm = sprintf('%02d', $i);
    $timesM .= '<option value="'.$tm.'">'.$tm.'</option>';
} 
$pg_title = "이용상세정보";
include_once("./admin_head.sub.php");
?>

<style>
.tbl_type,.tbl_type th,.tbl_type td{border:0;}
.tbl_type{width:100%;border-top:1px solid #dcdcdc;border-bottom:1px solid #dcdcdc;border-collapse:collapse}
.tbl_type caption{display:none}
.tbl_type tfoot{background-color:#f5f7f9;font-weight:bold}
.tbl_type th{padding:7px 0 4px;border:1px solid #dcdcdc;background-color:#f5f7f9;color:#666;font-weight:bold;text-align:center;}
.tbl_type td{padding:6px 6px;border:1px solid #e5e5e5;color:#4c4c4c}
.tbl_type th.center, .tbl_type td.center {text-align:center}
.frm_input.number {text-align:right;padding-right:3px;}
#content h2{margin:0!important;}
</style>
<form name="frm" id="frm" action="./reservation/wzb_room_form_update.php" method="post" onsubmit="return getAction(this);" autocomplete="off" enctype="multipart/form-data">
<input type="hidden" name="w" value="<?php echo $w; ?>">
<input type="hidden" name="rm_ix" value="<?php echo $rm_ix; ?>">
<input type="hidden" name="sst" value="<?php echo $sst; ?>">
<input type="hidden" name="sod" value="<?php echo $sod; ?>">
<input type="hidden" name="sfl" value="<?php echo $sfl; ?>">
<input type="hidden" name="stx" value="<?php echo $stx; ?>">
<input type="hidden" name="page" value="<?php echo $page; ?>">
<input type="hidden" name="sch_cp_ix" value="<?php echo $sch_cp_ix; ?>">
<input type="hidden" name="sch_subject" value="<?php echo $sch_subject; ?>">
<input type="hidden" name="store_mb_id" value="<?php echo $member['id']; ?>">
<input type="hidden" name="rm_level" value="99">
    <section id="anc_spp_pay" class="cbox">
        
        <div style="height:20px;"></div>
        <h2>이용상세정보</h2>
        <div class="tbl_frm01 tbl_wrap">
            <table>
            <caption>이용상세정보</caption>
            <colgroup>
                <col class="grid_4">
                <col>
            </colgroup>
            <tbody>

            <input type="hidden" name="cp_ix" id="cp_ix" value="<?php echo $rm['cp_ix'];?>" />
            
            <tr>
                <th scope="row">이용명</th>
                <td>
                    <input type="text" name="rm_subject" id="rm_subject" value="<?php echo $rm['rm_subject'];?>" required class="required frm_input"  maxlength="100" size="30" />
                </td>
            </tr>
            <tr>
                <th scope="row">간단설명</th>
                <td>
                    <textarea name="rm_desc" id="rm_desc" rows="" cols=""><?php echo $rm['rm_desc'];?></textarea>
                </td>
            </tr>
            <!-- <tr>
                <th scope="row">예약가능권한</th>
                <td>
                    <?php echo help('권한을 1로 선택할경우 비회원도 예약이 가능합니다.') ?>
                    <?php echo get_member_level_select('rm_level', 1, 10, $rm['rm_level']) ?>
                </td>
            </tr> -->
            <tr>
                <th scope="row">링크URL</th>
                <td>
                    <input type="text" name="rm_link_url" id="rm_link_url" value="<?php echo $rm['rm_link_url'];?>" class="frm_input"  maxlength="120" size="80" />
                </td>
            </tr>
            <tr>
                <th scope="row">공휴일예약허용</th>
                <td>
                    <?php echo help('체크하시면 공휴일에도 예약이 가능합니다.') ?>
                    <label><input type="checkbox" name="rm_holiday_use" id="rm_holiday_use" value="1" <?php echo $rm['rm_holiday_use'] ? 'checked=checked' : '';?> /> 허용</label>
                </td>
            </tr>
            <tr>
                <th scope="row">예약가능요일</th>
                <td>
                    <?php echo help('아래 체크된 요일에만 예약이 가능하게 설정됩니다.') ?>
                    <input type="checkbox" name="rm_week0" value="1" <?php echo $rm['rm_week0']==1?"checked":""; ?> id="rm_week0">
                    <label for="rm_week0">일</label>&nbsp;&nbsp;
                    <input type="checkbox" name="rm_week1" value="1" <?php echo $rm['rm_week1']==1?"checked":""; ?> id="rm_week1">
                    <label for="rm_week1">월</label>&nbsp;&nbsp;
                    <input type="checkbox" name="rm_week2" value="1" <?php echo $rm['rm_week2']==1?"checked":""; ?> id="rm_week2">
                    <label for="rm_week2">화</label>&nbsp;&nbsp;
                    <input type="checkbox" name="rm_week3" value="1" <?php echo $rm['rm_week3']==1?"checked":""; ?> id="rm_week3">
                    <label for="rm_week3">수</label>&nbsp;&nbsp;
                    <input type="checkbox" name="rm_week4" value="1" <?php echo $rm['rm_week4']==1?"checked":""; ?> id="rm_week4">
                    <label for="rm_week4">목</label>&nbsp;&nbsp;
                    <input type="checkbox" name="rm_week5" value="1" <?php echo $rm['rm_week5']==1?"checked":""; ?> id="rm_week5">
                    <label for="rm_week5">금</label>&nbsp;&nbsp;
                    <input type="checkbox" name="rm_week6" value="1" <?php echo $rm['rm_week6']==1?"checked":""; ?> id="rm_week6">
                    <label for="rm_week6">토</label>
                </td>
            </tr>
            <tr>
                <th scope="row">순서</th>
                <td>
                    <input type="text" name="rm_sort" id="rm_sort" value="<?php echo $rm['rm_sort'];?>" required class="required frm_input"  maxlength="10" size="7" />
                </td>
            </tr>
            <tr>
                <th scope="row">이미지</th>
                <td>
                    <div class="tbl_frm01">
                    <table cellspacing="0" border="1" class="tbl_type" style="width:600px;" id="wrap-tbl-image">
                        <caption></caption>
                        <colgroup>
                            <col width="auto"/>
                            <col width="13%"/>
                        </colgroup>
                        <thead>
                        <tr>
                            <th scope="row" class="center">이미지파일</th>
                            <th scope="row" class="center"><a href="#none" class="btn_frmline add-tr">추가</a></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php
                        if ($cnt_img > 0) { 
                            for ($z = 0; $z < $cnt_img; $z++) { 

                            $rmp_image = "";
                            $bimg = G5_DATA_PATH."/wzb_room/".$arr_img[$z]['rmp_photo'];
                            if (file_exists($bimg) && $arr_img[$z]['rmp_photo']) {
                                $rmp_image = '<a href="'.G5_DATA_URL.'/wzb_room/'.$arr_img[$z]['rmp_photo'].'" target="_blank"><img src="'.G5_DATA_URL.'/wzb_room/'.$arr_img[$z]['rmp_photo'].'" height="30"></a>';
                            }
                            ?>
                            <tr>
                                <td>
                                    <?php echo $rmp_image;?>
                                    <?php echo $arr_img[$z]['rmp_photo_name'];?>
                                </td>
                                <td class="center"><a href="#none" class="btn_frmline del-tr" data-rmp-ix="<?php echo $arr_img[$z]['rmp_ix'];?>">삭제</a></td>
                            </tr>
                            <?php
                            }
                        }
                        else {
                            ?>
                            <tr class="empty">
                                <td colspan="2">추가버튼을 클릭하여 이미지파일을 등록해주세요.</td>
                            </tr>
                            <?php
                        }
                        ?>
                        </tbody>
                    </table>
                    </div>
                </td>
            </tr>
            <tr>
                <th scope="row">사용여부</th>
                <td>
                    <label><input type="checkbox" name="rm_use" id="rm_use" value="1" <?php echo $rm['rm_use'] ? 'checked=checked' : '';?> /> 사용</label>
                </td>
            </tr>
            </tbody>
            </table>
        </div>
        
        <div style="height:20px;"></div>
        <h2>이용시간정보</h2>

        <div class="tbl_frm01 tbl_wrap" style="width:700px;">
            
            <div class="local_desc" style="padding:10px;">
                <p>
                    환경설정에서 결제기능사용여부 가 체크되어있을경우 요금설정이 활성화 됩니다. <a href="./wzb_booking_list2.php?code=wzb_config" class="wz-label">환경설정</a><br />
                    요금을 인당으로 선택 할 경우 예약시 인원수별로 요금을 곱합니다. <br />
                    시간당으로 선택할경우 인원수에 관계없이 입력된 요금으로 예약됩니다.
                </p>
            </div>

            <div class="tbl_frm01">
            <table cellspacing="0" border="1" class="tbl_type" style="width:100%;" id="wrap-tbl-time">
                <caption></caption>
                <colgroup>
                    <col width="30%"/>
                    <?php if ($wzpconfig['pn_is_pay']) {?><col width="30%"/><?php } ?>
                    <col width="30%"/>
                    <col width="10%"/>
                </colgroup>
                <thead>
                <tr>
                    <th scope="row" class="center">시간</th>
                    <?php if ($wzpconfig['pn_is_pay']) {?><th scope="row" class="center">요금</th><?php } ?>
                    <th scope="row" class="center">예약허용인원</th>
                    <th scope="row" class="center"><a href="#none" class="btn_frmline add-tr">추가</a></th>
                </tr>
                </thead>
                <tbody>
                <?php
                if ($cnt_se > 0) { 
                    for ($z = 0; $z < $cnt_se; $z++) { 
                    
                    $arr_time = explode(':', $arr_se[$z]['rmt_time']);
                    $rmt_time_h = $arr_time[0];
                    $rmt_time_m = $arr_time[1];
                    ?>
                    <tr>
                        <td class="center">
                            <input type="hidden" name="rmt_ix[]" value="<?php echo $arr_se[$z]['rmt_ix'];?>" />
                            <select name="rmt_time_h[]" id="rmt_time_h_<?php echo $z;?>"><?php echo $timesH?></select> : <select name="rmt_time_m[]" id="rmt_time_m_<?php echo $z;?>"><?php echo $timesM?></select>
                            <script type="text/javascript">
                            <!--
                                $('#rmt_time_h_<?php echo $z;?>').val('<?php echo $rmt_time_h;?>');
                                $('#rmt_time_m_<?php echo $z;?>').val('<?php echo $rmt_time_m;?>');
                            //-->
                            </script>
                        </td>

                        <?php if ($wzpconfig['pn_is_pay']) {?>
                        <td class="center">
                            <select name="rmt_price_type[]">
                                <option value="인당" <?php echo $arr_se[$z]['rmt_price_type'] == '인당' ? 'selected=selected' : '';?>>인당</option>
                                <option value="시간당" <?php echo $arr_se[$z]['rmt_price_type'] == '시간당' ? 'selected=selected' : '';?>>시간당</option>
                            </select>
                            <input type="text" name="rmt_price[]" value="<?php echo $arr_se[$z]['rmt_price'];?>" required class="required frm_input" maxlength="20" />
                        </td>
                        <?php } ?>

                        <td class="center">
                            <input type="text" name="rmt_max_cnt[]" value="<?php echo $arr_se[$z]['rmt_max_cnt'];?>" required class="required frm_input" maxlength="20" size="5" />
                        </td>
                        <td class="center"><a href="#none" class="btn_frmline del-tr" data-rmt-ix="<?php echo $arr_se[$z]['rmt_ix'];?>">삭제</a></td>
                    </tr>
                    <?php
                    }
                }
                else {
                    ?>
                    <tr class="empty">
                        <td colspan="4">추가버튼을 클릭하여 시간정보를 등록해주세요.</td>
                    </tr>
                    <?php
                }
                ?>
                </tbody>
            </table>
            </div>

        </div>
    </section>

    <div class="btn_confirm01 btn_confirm">
        <input type="submit" value="확인" class="btn_submit" accesskey="s">
        <a href="./rpage.php?code=wzb_room_list&<?php echo $qstr; ?>">목록</a>
    </div>

</form>
<script type="text/javascript">
<!--
jQuery(document).ready(function () {
    $(document).on('click', '#wrap-tbl-image .add-tr', function() {
        $('#wrap-tbl-image .empty').remove();
        tbl_tr_add_image();
    });
    $(document).on('click', '#wrap-tbl-image .del-tr', function() {
        var rmp_ix = $(this).attr('data-rmp-ix');
        if (rmp_ix) {
            $('#frm').prepend('<input type="hidden" name="rmp_ix[]" value="'+rmp_ix+'">');
        }
        
        $(this).closest('tr').remove();
        var tr_cnt = $('#wrap-tbl-image tbody tr').length;
        if (tr_cnt == 0) {
            $('#wrap-tbl-image').append('<tr class="empty"><td colspan="2">추가버튼을 클릭하여 이미지파일을 등록해주세요.</td></tr>');
        }
    });
    $(document).on('click', '#wrap-tbl-time .add-tr', function() {
        $('#wrap-tbl-time .empty').remove();
        tbl_tr_add_time();
    });
    $(document).on('click', '#wrap-tbl-time .del-tr', function() {
        var rmt_ix = $(this).attr('data-rmt-ix');
        if (rmt_ix) {
            $('#frm').prepend('<input type="hidden" name="rmt_ix_del[]" value="'+rmt_ix+'">');
        }
        
        $(this).closest('tr').remove();
        var tr_cnt = $('#wrap-tbl-time tbody tr').length;
        if (tr_cnt == 0) {
            $('#wrap-tbl-time').append('<tr class="empty"><td colspan="4">추가버튼을 클릭하여 시간정보를 등록해주세요.</td></tr>');
        }
    });
});
function tbl_tr_add_image() {
    
    var tbl_tr_html = '';
        tbl_tr_html += '<tr>';
        tbl_tr_html += '    <td>';
        tbl_tr_html += '        <input type="file" name="rmp_photo[]" class="frm_input margin_full_input" />';
        tbl_tr_html += '    </td>';
        tbl_tr_html += '    <td class="center"><a href="#none" class="btn_frmline del-tr">삭제</a></td>';
        tbl_tr_html += '</tr>';

    $('#wrap-tbl-image').append(tbl_tr_html);
}
function tbl_tr_add_time() {
    
    var tbl_tr_html = '';
        tbl_tr_html += '<tr>';
        tbl_tr_html += '    <td class="center">';
        tbl_tr_html += '        <input type="hidden" name="rmt_ix[]" value="0" />';
        tbl_tr_html += '        <select name="rmt_time_h[]"><?php echo $timesH?></select> : <select name="rmt_time_m[]"><?php echo $timesM?></select>';
        tbl_tr_html += '    </td>';

        <?php if ($wzpconfig['pn_is_pay']) {?>
        tbl_tr_html += '    <td class="center">';
        tbl_tr_html += '        <select name="rmt_price_type[]">';
        tbl_tr_html += '            <option value="인당">1인당</option>';
        tbl_tr_html += '            <option value="시간당">시간당</option>';
        tbl_tr_html += '        </select>';
        tbl_tr_html += '        <input type="text" name="rmt_price[]" value="0" required class="required frm_input" maxlength="20" />';
        tbl_tr_html += '    </td>';
        <?php } ?>

        tbl_tr_html += '    <td class="center">';        
        tbl_tr_html += '        <input type="text" name="rmt_max_cnt[]" value="0" required class="required frm_input" maxlength="20" size="5" />';
        tbl_tr_html += '    </td>';
        tbl_tr_html += '    <td class="center"><a href="#none" class="btn_frmline del-tr">삭제</a></td>';
        tbl_tr_html += '</tr>';

    $('#wrap-tbl-time').append(tbl_tr_html);
}
function getAction(f) {

    if (f.rm_week0.checked == false && f.rm_week1.checked == false && f.rm_week2.checked == false && f.rm_week3.checked == false && f.rm_week4.checked == false && f.rm_week5.checked == false && f.rm_week6.checked == false) {
        if (!confirm("예약가능요일에 모두 체크가 되지 않을경우 예약이 되지 않습니다. 계속 진행하시겠습니까?")) {
            return false;
        }
    }

    var tr_cnt    = $('#wrap-tbl-time tbody tr').length;
    var empty_cnt = $('#wrap-tbl-time .empty').length;
    if ((tr_cnt - empty_cnt) < 1) {
        alert('추가버튼을 클릭하여 최소 한개이상의 시간정보를 등록해주세요');
        return false;
    }

    return true;
}
//-->
</script>
<?php
include_once("./admin_tail.sub.php");
?>