<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$od_id = preg_replace('/[^0-9]/i', '', $_GET['od_id']);

$query = "select * from {$g5['wpot_order_table']} where od_id = '".$od_id."' and mb_id = '".$member['id']."' ";

//echo $query;

$bk = sql_fetch($query);
if (!$bk['od_id']) {
    alert("조회하실 충전정보가 없습니다.", WPOT_STATUS_URL);
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

// LG 현금영수증 JS
if($bk['bk_pg'] == 'lg') {
    if($wzpconfig['pn_pg_test']) {
        echo '<script language="JavaScript" src="http://pgweb.uplus.co.kr:7085/WEB_SERVER/js/receipt_link.js"></script>'.PHP_EOL;
    } else {
        echo '<script language="JavaScript" src="http://pgweb.uplus.co.kr/WEB_SERVER/js/receipt_link.js"></script>'.PHP_EOL;
    }
}
?>

<div class="alert alert-success alert-dismissible pay-bank-notice" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>

    <?php 
        if ($bk['bk_status'] == '대기') { 

            $subject_1 = 'Oh!포인트 충전신청 안내';
            $message_1 = $member['name'].'님!
            Oh!포인트가 충전신청이 완료되었습니다.
            
            결제금액 : '.$bk_price.'
            
            입금계좌 : '.$bk_bank_account.'
            
            이용해주셔서 감사합니다.';
        
            aligo_sms('TE_4908', $bk_hp, $member['name'], $subject_1, $message_1);

    ?>
    <strong class="res-message-title">충전신청이 완료되었습니다. 입금 확인 후 충전처리됩니다.</strong>
    <ul class="desc">
        <li><i class="fa fa-info-circle fa-lg"></i> 입금은 입금자명과 일치해야 충전처리가 가능합니다.</li>
    </ul>

    <?php 
        } else if ($bk['bk_status'] == '취소') { 

            $mp = (int)$member['point']+(int)$bk_charge_point;
            $subject_1 = 'Oh!포인트 취소안내';
            $message_1 = $member['name'].'님!
            Oh!포인트가 취소 되었습니다.
            
            충전포인트 : '.$bk_charge_point.'
            결제금액 : '.$bk_price.'
            
            이용해주셔서 감사합니다.';
        
            aligo_sms('TE_4909', $bk_hp, $member['name'], $subject_1, $message_1);

    ?>
    <strong class="res-message-title">충전이 취소처리 되었습니다.</strong>
    <ul class="desc">
        <li><i class="fa fa-info-circle fa-lg"></i> 환불수수료는 환불규정을 참고해주세요.</li>
    </ul>

    <?php 
        } else {

            $mp = (int)$member['point']+(int)$bk_charge_point;
            $subject_1 = 'Oh!포인트 충전완료 안내';
            $message_1 = $member['name'].'님!
            Oh!포인트가 충전되었습니다.
            
            결제금액 : '.$bk_price.'
            충전포인트 : '.$bk_charge_point.'
            누적포인트 : '.$mp.'
            
            이용해주셔서 감사합니다.';
        
            aligo_sms('TE_4910', $bk_hp, $member['name'], $subject_1, $message_1);

    ?>
    <strong class="res-message-title">충전이 완료되었습니다.</strong>
    <ul class="desc">
        <li><i class="fa fa-info-circle fa-lg"></i> 충전취소는 고객센터로 문의바랍니다.</li>
        <li><i class="fa fa-info-circle fa-lg"></i> 환불수수료는 환불규정을 참고해주세요.</li>
    </ul>
    <?php } ?>

</div>

<div class="panel panel-default">

    <div class="panel-heading"><strong><i class="fa fa-credit-card fa-lg"></i> 결제 정보</strong></div>

    <div class="table-responsive">
        <table class="table form-group form-group-sm table-bordered font-color-gray">
        <caption></caption>
        <colgroup>
            <col width="80px;">
            <col>
        </colgroup>
        <tbody>

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
                if ($bk['bk_deposit_name']) {
                    echo ' 입금자명 : '.get_text($bk['bk_deposit_name']);
                }
                if ($bk['bk_bank_account']) {
                    echo ' 입금계좌 : '.get_text($bk['bk_bank_account']);
                }
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
            <th scope="col">결제상태</th>
            <td><?php echo $bk['bk_status'];?></td>
        </tr>
        <tr>
            <th scope="col">결제번호</th>
            <td><?php echo $bk['od_id'];?></td>
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

</div>