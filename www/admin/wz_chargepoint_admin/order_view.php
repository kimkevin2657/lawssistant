<?php
include_once('./_common.php');

//auth_check($auth[$sub_menu], "r");

$od_id = preg_replace('/[^0-9]/i', '', $_GET['od_id']);

$sql = " select * from {$g5['wpot_order_table']} where od_id = '$od_id' ";
$bk = sql_fetch($sql);
if (!$bk['od_id']) alert('등록된 자료가 없습니다.', './order_list.php');

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

$qstr .= "&sch_frdate1=".$sch_frdate1."&sch_todate1=".$sch_todate1."&sch_status=".$sch_status."&sch_payment=".$sch_payment;

$g5['title'] = '충전정보 상세보기';
include_once (G5_ADMIN_PATH.'/admin.head.php');

$mb = get_member($bk['mb_id']);
?>

<form method="post" name="frmpay" id="frmpay" action="./order_form_update.php?<?php echo $qstr;?>">
<input type="hidden" name="od_id" value="<?php echo $od_id ?>">

<h2 class="h2_frm">결제정보</h2>

<div class="tbl_frm01 tbl_wrap">

    <table cellpadding="0" cellspacing="0" border="0">
    <colgroup>
        <col width="130px">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th>결제번호</th>
        <td><?php echo $bk['od_id'];?></td>
    </tr>
    <tr>
        <th>회원</th>
        <td>
            <?php
            echo $mb['name'];
            /* $mb = get_member($bk['mb_id'], 'mb_id, mb_name, mb_nick, mb_email, mb_homepage');
            $mb_nick = get_sideview($mb['mb_id'], get_text($mb['mb_nick']), $mb['mb_email'], $mb['mb_homepage']);
            echo $mb_nick; */
            ?>
        </td>
    </tr>
    <tr>
        <th>입금자명</th>
        <td>
            <?php
            echo $bk['bk_deposit_name'];
            /* $mb = get_member($bk['mb_id'], 'mb_id, mb_name, mb_nick, mb_email, mb_homepage');
            $mb_nick = get_sideview($mb['mb_id'], get_text($mb['mb_nick']), $mb['mb_email'], $mb['mb_homepage']);
            echo $mb_nick; */
            ?>
        </td>
    </tr>
    <tr>
        <th>결제방법</th>
        <td><?php echo $bk['bk_payment'];?></td>
    </tr>
    <tr>
        <th>충전포인트</th>
        <td><?php echo number_format($bk['bk_charge_point']).' '.WPOT_POINT_TEXT;?></td>
    </tr>
    <tr>
        <th>결제금액</th>
        <td><?php echo number_format($bk['bk_price']);?> 원</td>
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
                    include_once(WPOT_PLUGIN_PATH.'/gender/kcp/config.php');
                    $hp_receipt_script = 'window.open(\''.$g_receipt_url_bill.'mcash_bill&tno='.$bk['bk_tno'].'&order_no='.$bk['od_id'].'&trade_mony='.$bk['bk_receipt_price'].'\', \'winreceipt\', \'width=500,height=690,scrollbars=yes,resizable=yes\');';
                }
                else if($bk['bk_pg'] == 'inicis') {
                    $hp_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid='.$bk['bk_tno'].'&noMethod=1\',\'receipt\',\'width=430,height=700\');';
                }
                else if($bk['bk_pg'] == 'lg') {
                    include_once(WPOT_PLUGIN_PATH.'/gender/lg/config.php');
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
                    include_once(WPOT_PLUGIN_PATH.'/gender/kcp/config.php');
                    $card_receipt_script = 'window.open(\''.$g_receipt_url_bill.'card_bill&tno='.$bk['bk_tno'].'&order_no='.$bk['od_id'].'&trade_mony='.$bk['bk_receipt_price'].'\', \'winreceipt\', \'width=470,height=815,scrollbars=yes,resizable=yes\');';
                }
                else if($bk['bk_pg'] == 'inicis') {
                    $card_receipt_script = 'window.open(\'https://iniweb.inicis.com/DefaultWebApp/mall/cr/cm/mCmReceipt_head.jsp?noTid='.$bk['bk_tno'].'&noMethod=1\',\'receipt\',\'width=430,height=700\');';
                }
                else if($bk['bk_pg'] == 'lg') {
                    include_once(WPOT_PLUGIN_PATH.'/gender/lg/config.php');
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

            <?php if ($bk['bk_payment'] == '신용카드' || $bk['bk_payment'] == '계좌이체' || $bk['bk_payment'] == '휴대폰') {?>
                <span class="vbar">&#124;</span>
                <?php if (!$bk['bk_pg_cancel']) {?>
                <label><input type="checkbox" name="bk_cancel" id="bk_cancel" value="1" /> 결제승인취소</label>
                <?php } else { ?>
                <label>PG결제 취소 완료</label>
                <?php } ?>
            <?php } ?>
        </td>
    </tr>
    <tr>
        <th scope="col">신청일시</th>
        <td><?php echo $bk['bk_time'];?></td>
    </tr>
    <tr>
        <th scope="col">핸드폰</th>
        <td><?php echo $bk['bk_hp'];?></td>
    </tr>
    <tr>
        <th scope="col">이메일</th>
        <td><?php echo $bk['bk_email'];?></td>
    </tr>

    </tbody>
    </table>

</div>

<div class="btn_confirm01 btn_confirm">
    <input type="submit" value="결제처리변경" class="btn_submit btn" accesskey="s">
    <a href="./point_list.php?code=order_list&<?php echo $qstr;?>" class="btn btn_02">목록</a>
</div>

</form>

<script type="text/javascript">
<!--
function getAction(f) {
    return true;
}
//-->
</script>

<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>