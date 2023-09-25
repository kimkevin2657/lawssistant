<?php
$sub_menu = '790400';
include_once('./_common.php');

$bk_ix = (int)$_GET['bk_ix'];

$sql = " select * from {$g5['wzb_booking_table']} where bk_ix = '$bk_ix' ";
$bk = sql_fetch($sql);
if (!$bk['bk_ix']) alert('등록된 자료가 없습니다.', './wzb_booking_list.php');

$bk_hp1 = $bk_hp2 = $bk_hp3 = '';
if ($bk['bk_hp']) { 
    $bk_hp1 = substr(str_replace('-', '', $bk['bk_hp']), 0, 3);
    $bk_hp2 = substr(str_replace('-', '', $bk['bk_hp']), 3, 4);
    $bk_hp3 = substr(str_replace('-', '', $bk['bk_hp']), 7);
} 

$is_done = true;
if ($bk['bk_status'] != '완료') {
    $is_done = false;
}

$disp_bank = true;
$app_no_subj = '';
$disp_bank = true;
$disp_receipt = false;
if($bk['bk_payment'] == '신용카드' || $bk['bk_payment'] == 'KAKAOPAY') {
    $app_no_subj = '승인번호';
    $app_no = $bk['bk_app_no'];
    $disp_bank = false;
    $disp_receipt = true;
} else if($bk['bk_payment'] == '간편결제') {
    $app_no_subj = '승인번호';
    $app_no = $bk['bk_app_no'];
    $disp_bank = false;
    switch($bk['bk_pg']) {
        case 'kcp':
            $easy_pay_name = 'PAYCO';
            break;
        default:
            break;
    }
} else if($bk['bk_payment'] == '휴대폰') {
    $app_no_subj = '휴대폰번호';
    $app_no = $bk['bk_bank_account'];
    $disp_bank = false;
    $disp_receipt = true;
} else if($bk['bk_payment'] == '가상계좌' || $bk['bk_payment'] == '계좌이체') {
    $app_no_subj = '거래번호';
    $app_no = $bk['bk_tno'];
}


// 객실예약정보
unset($arr_room);
$arr_room = array();
$query = "select * from {$g5['wzb_booking_room_table']} where bk_ix = '{$bk['bk_ix']}' order by bkr_ix asc ";
$res = sql_query($query);
while($row = sql_fetch_array($res)) { 
    $arr_room[] = $row;
}
$cnt_room = count($arr_room);
if ($res) sql_free_result($res);

// 옵션선택정보
unset($arr_option);
$arr_option = array();
$query = "select * from {$g5['wzb_booking_option_table']} where bk_ix = '{$bk['bk_ix']}' order by odo_ix asc ";
$res = sql_query($query);
while($row = sql_fetch_array($res)) { 
    $arr_option[] = $row;
}
$cnt_option = count($arr_option);
if ($res) sql_free_result($res);


$qstr .= "&sch_cp_ix=".$sch_cp_ix."&sch_room=".$sch_room."&sch_frdate1=".$sch_frdate1."&sch_todate1=".$sch_todate1."&sch_frdate2=".$sch_frdate2."&sch_todate2=".$sch_todate2."&sch_status=".$sch_status."&sch_payment=".$sch_payment;

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

$g5['title'] = '예약정보 상세보기';
include_once(MS_ADMIN_PATH."/admin_topmenu.php");
include_once(MS_ADMIN_PATH.'/admin.head.php');
?>


    
<h2 class="h2_frm">예약정보</h2>

<div class="tbl_head01 tbl_wrap">

    <table cellpadding="0" cellspacing="0" border="0">
    <colgroup>
        <col>
        <col width="100px">
        <col width="80px">
        <col width="70px">
        <col width="90px">
        <col width="70px">
    </colgroup>
    <thead> 
    <tr>
        <th scope="col">이용서비스명</th> 
        <th scope="col">이용일자</th>
        <th scope="col">예약시간</th>
        <th scope="col">인원</th>
        <th scope="col">이용요금</th>
        <th scope="col">삭제</th>
    </tr>
    </thead>
    <tbody>
    <?php 
    $total_price = $total_room = 0;
    if ($cnt_room > 0) { 
        for ($z = 0; $z < $cnt_room; $z++) { 
        ?>
        <tr>
            <td class="td_alignc"><?php echo $arr_room[$z]['bkr_subject'];?></td>
            <td class="td_alignc"><?php echo wz_get_hangul_date_md($arr_room[$z]['bkr_date']).'('.get_yoil($arr_room[$z]['bkr_date']).') ';?></td>
            <td class="td_alignc"><?php echo wz_get_hangul_time_hm($arr_room[$z]['bkr_time']);?></td>
            <td class="td_alignc"><?php echo $arr_room[$z]['bkr_cnt'];?></td>
            <td class="td_alignc"><?php echo number_format($arr_room[$z]['bkr_price']);?></td>
            <td class="td_alignc">
                <?php if (!$is_done) {?>
                <a href="./wzb_booking_form_update.php?mode=kd&amp;bkr_ix=<?php echo $arr_room[$z]['bkr_ix']; ?>&amp;<?php echo $qstr; ?>" onclick="return delete_confirm(this);">삭제</a>
                <?php } else { ?>
                -
                <?php } ?>
            </td>
        </tr>
        <?php 
        $total_room     += $arr_room[$z]['bkr_price'];
        }
    } 
    ?> 
    </tbody>
    <thead>
    <tr>
        <th scope="col" colspan="4"><span class="important">합계</span></th>
        <th><?php echo number_format($total_room);?></th>
        <th></th>
    </tr>  
    </thead>
    </table>
</div>

<?php if ($cnt_option > 0) { ?>
<h2 class="h2_frm">옵션예약정보</h2>

<div class="tbl_head01 tbl_wrap">
    
    <table cellpadding="0" cellspacing="0" border="0">
    <colgroup>
        <col>
        <col width="108px;">
        <col width="100px;">
    </colgroup>
    <thead>
    <tr>
        <th scope="col">옵션명</th>
        <th scope="col">수량</th>
        <th scope="col">금액</th>
    </tr>
    </thead>
    <tbody>
    <?php 
    $total_option = 0;
    for ($z = 0; $z < $cnt_option; $z++) { 
        ?>
        <tr>
            <td><?php echo $arr_option[$z]['odo_name'].($arr_option[$z]['odo_memo'] ? ' ('.$arr_option[$z]['odo_memo'].')' : '');?></td>
            <td class="td_alignc"><?php echo $arr_option[$z]['odo_cnt'].$arr_option[$z]['odo_unit']?></td>
            <td class="td_alignc"><?php echo number_format($arr_option[$z]['odo_price']);?></td>
        </tr>
        <?php 
        $total_option    += $arr_option[$z]['odo_price'];
    } 
    ?>
    </tbody>
    <thead>
    <tr>
        <th colspan="2"><span class="important">합계<span></th>
        <th><?php echo number_format($total_option);?> 원</th>
    </tr>
    </thead>
    </tbody>
    </table>

</div>
<?php } ?>

<div class="btn_confirm01 btn_confirm">
    <a href="./wzb_booking_list.php?<?php echo $qstr;?>">목록</a>
</div>

<form method="post" name="frmpay" id="frmpay" action="./wzb_booking_form_update.php?<?php echo $qstr;?>">
<input type="hidden" name="mode" value="pay">
<input type="hidden" name="bk_ix" value="<?php echo $bk_ix ?>">
<input type="hidden" name="bk_price" id="bk_price" value="<?php echo $bk['bk_price'];?>" />
<input type="hidden" name="bk_reserv_price" id="bk_reserv_price" value="<?php echo $bk['bk_reserv_price'];?>" />

<h2 class="h2_frm">결제정보</h2>

<div class="tbl_frm01 tbl_wrap">
    <div class="sim-bx">
        <div class="bx-hd">			
            <p>예약금</p>	
        </div>	
        <div class="bx-ft">	
            <p class="important"><?php echo number_format($bk['bk_reserv_price']);?> 원 (<?php echo ($bk['bk_reserv_price'] <= ($bk['bk_price'] - $bk['bk_misu']) ? '결제완료' : '미결제');?>)</p>		
        </div>
    </div>
    <div class="next_box"></div>
    <div class="sim-bx">
        <div class="bx-hd">			
            <p>잔금</p>	
        </div>	
        <div class="bx-ft">	
            <p class="important"><?php echo number_format($bk['bk_price'] - $bk['bk_reserv_price']);?> 원 (<?php echo ($bk['bk_misu'] ? '미결제' : '결제완료');?>)</p>
        </div>
    </div>
    <div class="next_box equal"></div>
    <div class="sim-bx">
        <div class="bx-hd">			
            <p>총이용금액</p>	
        </div>	
        <div class="bx-ft">	
            <p class="important"><?php echo number_format($bk['bk_price']);?> 원 (<?php echo ($bk['bk_misu'] ? '미결제' : '결제완료');?>)</p>		
        </div>
    </div>

    <hr class="section-division"></hr>

    <table cellpadding="0" cellspacing="0" border="0">
    <colgroup>
        <col width="130px">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th>결제방법</th>
        <td><?php echo $bk['bk_payment'];?></td>
    </tr> 
    <?php if($app_no_subj) { // 승인번호, 휴대폰번호, 거래번호?>
    <tr>
        <th><?php echo $app_no_subj; ?></th>
        <td>
            <?php echo $app_no; ?>
        </td>
    </tr>
    <?php } ?>

    <?php if($disp_bank) {?>
    <tr>
        <th>입금정보</th>
        <td>
            <?php
            echo ' 입금자명 : '.get_text($bk['bk_deposit_name']).' 입금계좌 : '.get_text($bk['bk_bank_account']);
            ?>
        </td>
    </tr>
    <?php } ?>

    <?php if($disp_receipt) {?>
    <tr>
        <th>영수증</th>
        <td>
            <?php
            if($bk['bk_payment'] == '휴대폰')
            {
                if($bk['bk_pg'] == 'kcp') {
                    include_once(WZB_PLUGIN_PATH.'/gender/kcp/config.php');
                    $hp_receipt_script = 'window.open(\''.$g_receipt_url_bill.'mcash_bill&tno='.$bk['bk_tno'].'&order_no='.$bk['od_id'].'&trade_mony='.$bk['bk_receipt_price'].'\', \'winreceipt\', \'width=500,height=690,scrollbars=yes,resizable=yes\');';
                }
                else if($bk['bk_pg'] == 'inicis') {
                    $hp_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid='.$bk['bk_tno'].'&noMethod=1\',\'receipt\',\'width=430,height=700\');';
                }
                else if($bk['bk_pg'] == 'lg') {
                    include_once(WZB_PLUGIN_PATH.'/gender/lg/config.php');
                    $LGD_TID      = $bk['bk_tno'];
                    $LGD_MERTKEY  = $wzpconfig['pn_pg_site_key'];
                    $LGD_HASHDATA = md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);

                    $hp_receipt_script = 'showReceiptByTID(\''.$LGD_MID.'\', \''.$LGD_TID.'\', \''.$LGD_HASHDATA.'\');';
                }
            ?>
            <a href="javascript:;" onclick="<?php echo $hp_receipt_script; ?>">영수증 출력</a>
            <?php
            }

            if($bk['bk_payment'] == '신용카드')
            {
                if($bk['bk_pg'] == 'kcp') {
                    include_once(WZB_PLUGIN_PATH.'/gender/kcp/config.php');
                    $card_receipt_script = 'window.open(\''.$g_receipt_url_bill.'card_bill&tno='.$bk['bk_tno'].'&order_no='.$bk['od_id'].'&trade_mony='.$bk['bk_receipt_price'].'\', \'winreceipt\', \'width=470,height=815,scrollbars=yes,resizable=yes\');';
                }
                else if($bk['bk_pg'] == 'inicis') {
                    $card_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid='.$bk['bk_tno'].'&noMethod=1\',\'receipt\',\'width=430,height=700\');';
                }
                else if($bk['bk_pg'] == 'lg') {
                    include_once(WZB_PLUGIN_PATH.'/gender/lg/config.php');
                    $LGD_TID      = $bk['bk_tno'];
                    $LGD_MERTKEY  = $wzpconfig['pn_pg_site_key'];
                    $LGD_HASHDATA = md5($LGD_MID.$LGD_TID.$LGD_MERTKEY);

                    $card_receipt_script = 'showReceiptByTID(\''.$LGD_MID.'\', \''.$LGD_TID.'\', \''.$LGD_HASHDATA.'\');';
                }
            ?>
            <a href="javascript:;" onclick="<?php echo $card_receipt_script; ?>">영수증 출력</a>
            <?php
            }

            if($bk['bk_payment'] == 'KAKAOPAY')
            {
                $card_receipt_script = 'window.open(\'https://mms.cnspay.co.kr/trans/retrieveIssueLoader.do?TID='.$bk['bk_tno'].'&type=0\', \'popupIssue\', \'toolbar=no,location=no,directories=no,status=yes,menubar=no,scrollbars=yes,resizable=yes,width=420,height=540\');';
            ?>
            <a href="javascript:;" onclick="<?php echo $card_receipt_script; ?>">영수증 출력</a>
            <?php
            }
            ?>
        </td>
    </tr>
    <?php } ?>
    <tr>
        <th>결제처리</th>
        <td>

            <?php if ($bk['bk_payment'] == '가상계좌' && $bk['bk_status'] == '취소' && $bk['bk_pg_price']) {?>
            <div style="text-align:left;color:red;padding:0px 0 6px"><i class="fa fa-warning fa-2"></i> 가상계좌는 자동환불처리가 되지 않으므로 반드시 예약자님께 직접 송금 해야합니다. (환불금액 : <?php echo number_format($bk['bk_pg_price']);?> 원)</div>
            <?php } ?>
            예약상태 : 
            <select name="bk_status" id="bk_status">
                <option value="대기" <?php echo ($bk['bk_status'] == '대기' ? 'selected=selected' : '');?>>대기</option>
                <option value="완료" <?php echo ($bk['bk_status'] == '완료' ? 'selected=selected' : '');?>>완료</option>
                <option value="취소" <?php echo ($bk['bk_status'] == '취소' ? 'selected=selected' : '');?>>취소</option>
            </select>

            <label><input type="checkbox" name="is_sms_send" id="is_sms_send" value="1" /> SMS전송</label>
            <span class="vbar">&#124;</span>

            <label><input type="checkbox" name="is_mail_send" id="is_mail_send" value="1" /> 메일전송</label>
            <span class="vbar">&#124;</span>
            
            <?php if ($bk['bk_payment'] == '신용카드' || $bk['bk_payment'] == '계좌이체' || $bk['bk_payment'] == '휴대폰') {?>
                <?php if (!$bk['bk_pg_cancel']) {?>
                <label><input type="checkbox" name="bk_cancel" id="bk_cancel" value="1" /> 결제승인취소</label>
                <?php } else { ?>
                <label>PG결제 취소 완료</label>
                <?php } ?>
                <span class="vbar">&#124;</span>
            <?php } ?>
            
            입금액 : <input type="text" name="bk_receipt_price" id="bk_receipt_price" value="<?php echo $bk['bk_receipt_price'];?>" class="frm_input" style="width:80px;" maxlength="15" onkeyup="_jsCalculate('receipt');" onblur="_jsCalculate('receipt');" /> 원
            <button type="button" onclick="javascript:input_receipt('예약');" class="btn-sm1">예약금 입금</button>
            <button type="button" onclick="javascript:input_receipt('모두');" class="btn-sm1">총이용금액 입금</button>
            <span class="vbar">&#124;</span>
            미수금 : <input type="text" name="bk_misu" id="bk_misu" value="<?php echo $bk['bk_misu'];?>" class="frm_input" style="width:80px;" maxlength="15" onkeyup="_jsCalculate('misu');" onblur="_jsCalculate('misu');" /> 원

        </td>
    </tr>
    <tr>
        <th>결제완료일시</th>
        <td>
            <input type="text" name="bk_receipt_time" value="<?php echo wz_is_null_time($bk['bk_receipt_time']) ? "" : $bk['bk_receipt_time']; ?>" id="bk_receipt_time" class="frm_input" maxlength="19">
            <input type="checkbox" name="od_bank_chk" id="od_bank_chk" value="<?php echo date("Y-m-d H:i:s", G5_SERVER_TIME); ?>" onclick="if (this.checked == true) this.form.bk_receipt_time.value=this.form.od_bank_chk.value; else this.form.bk_receipt_time.value = this.form.bk_receipt_time.defaultValue;">
            <label for="od_bank_chk">현재 시간으로 설정</label>
        </td>
    </tr>
    </tbody>
    </table>

</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="결제처리변경" class="btn_submit" accesskey="s">
    <a href="./wzb_booking_list2.php?code=<? echo $code; ?><?php echo $qstr;?>">목록</a>
</div>

</form>

<form method="post" name="frminfo" id="frminfo" action="./wzb_booking_form_update.php?<?php echo $qstr;?>" onsubmit="return getAction(this);">
<input type="hidden" name="mode" value="info">
<input type="hidden" name="bk_ix" value="<?php echo $bk_ix ?>">

<h2 class="h2_frm">예약자정보</h2>

<div class="tbl_frm01 tbl_wrap">

    <table cellpadding="0" cellspacing="0" border="0">
    <colgroup>
        <col width="100px">
        <col>
        <col width="100px">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th class="head">예약번호</th>
        <td class="head" colspan="3">
            <p><?php echo $bk['od_id'];?></p>
        </td>
    </tr>
    <tr>
        <th>예약일시</th>
        <td><?php echo $bk['bk_time'].' ('.$bk['bk_ip'].')';?></td>
        <th>접속화면</th>
        <td><?php echo $bk['bk_mobile'] ? '모바일' : '피씨';?></td>
    </tr> 
    <tr>
        <th>예약자명</th>
        <td>
            <input type="text" name="bk_name" id="bk_name" value="<?php echo $bk['bk_name'];?>" class="frm_input required" required style="width:80px;" maxlength="15" />
        </td>
        <th>핸드폰번호</th>
        <td>
            <input type="text" name="bk_hp" id="bk_hp" value="<?php echo $bk['bk_hp'];?>" class="frm_input required" required style="width:100px;" maxlength="20" />
        </td>
    </tr> 
    <tr>
        <th>이메일</th>
        <td colspan="3">
            <input type="text" name="bk_email" id="bk_email" value="<?php echo $bk['bk_email'];?>" class="frm_input" style="width:90%;" maxlength="120" />
        </td>
    </tr> 
    <tr>
        <th>메모</th>
        <td colspan="3">
            <textarea name="bk_memo" id="bk_memo" style="width:95%;height:100px;"><?php echo $bk['bk_memo'];?></textarea>
        </td>
    </tr> 
    </tbody>
    </table>

</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="예약자정보변경" class="btn_submit" accesskey="s">
    <a href="./wzb_booking_list2.php?code=<? echo $code; ?><?php echo $qstr;?>">목록</a>
</div>

</form>

<script type="text/javascript">
<!--
    function _jsCalculate(mode) {
        var f = document.frmpay;
        var bk_price = parseInt(f.bk_price.value);

        if (mode == 'receipt') {
            f.bk_misu.value = bk_price - parseInt(f.bk_receipt_price.value);
        }
        else {
            f.bk_receipt_price.value = bk_price - parseInt(f.bk_misu.value);
        }
    }
    function getAction(f) {
        return true;
    }
    function input_receipt(type) {
        if (type == '예약') {
            $('#bk_receipt_price').val($('#bk_reserv_price').val());
        }
        else {
            $('#bk_receipt_price').val($('#bk_price').val());
        }
        _jsCalculate('receipt');
    }
//-->
</script>

<?php
include_once(MS_ADMIN_PATH."/wz_bookingC_prm_admin/admin_tail_config.php");
//include_once (MS_ADMIN_PATH.'/admin.tail.php');
?>