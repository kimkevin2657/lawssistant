<?php
if(!defined('_MALLSET_')) exit;

$od_id = $_GET['od_id'];
$od_id = preg_match("/^[0-9]+$/", $od_id) ? $od_id : '';

if (!$is_member) {
    if (get_session('ss_orderview_uid') != $_GET['uid'])
        alert("직접 링크로는 예약 조회가 불가합니다.\\n\\n예약확인 화면을 통하여 조회하시기 바랍니다.", WZB_STATUS_URL.'&mode=ordercheck');
}


if($is_member){
    $sql .= " and mb_id = '{$member['mb_id']}' ";
}

$sql = "select * from {$g5['wzb_booking_table']} where cp_ix = '{$wzdc['cp_ix']}' and od_id = '$od_id' ";
//echo $sql."<br><br>";
$bk = sql_fetch($sql);

//echo "bk_ix:".$bk['bk_ix']."<br><br>";

if (!$bk['od_id'] || (!$is_member && md5($bk['od_id'].$bk['bk_time'].$bk['bk_ip']) != get_session('ss_orderview_uid'))) {
    alert("조회하실 예약정보가 없습니다.", WZB_STATUS_URL);
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
$query = "select * from {$g5['wzb_booking_room_table']} where bk_ix = '{$bk['bk_ix']}' and cp_ix = '{$wzdc['cp_ix']}' order by bkr_ix asc ";

//echo "select * from {$g5['wzb_booking_room_table']} where bk_ix = '{$bk['bk_ix']}' and cp_ix = '{$wzdc['cp_ix']}' order by bkr_ix asc ";

$res = sql_query($query);
while($row = sql_fetch_array($res)) { 
    $arr_room[] = $row;
}
$cnt_room = count($arr_room);
if ($res) sql_free_result($res);

// 옵션선택정보
unset($arr_option);
$arr_option = array();
$query = "select * from {$g5['wzb_booking_option_table']} where bk_ix = '{$bk['bk_ix']}' and cp_ix = '{$wzdc['cp_ix']}' order by odo_ix asc ";
$res = sql_query($query);
while($row = sql_fetch_array($res)) { 
    $arr_option[] = $row;
}
$cnt_option = count($arr_option);
if ($res) sql_free_result($res);

$uid = md5($bk['od_id'].$bk['bk_time'].$bk['bk_ip']);
$action_url = https_url(G5_PLUGIN_DIR.'/wz.bookingC.prm/order.view.update.php', true);   

// LG 현금영수증 JS
if($bk['bk_pg'] == 'lg') {
    if($wzpconfig['pn_pg_test']) {
        echo '<script language="JavaScript" src="http://pgweb.uplus.co.kr:7085/WEB_SERVER/js/receipt_link.js"></script>'.PHP_EOL;
    } else {
        echo '<script language="JavaScript" src="http://pgweb.uplus.co.kr/WEB_SERVER/js/receipt_link.js"></script>'.PHP_EOL;
    }
}

include_once(WZB_PLUGIN_PATH.'/navi_reserv.php');
?>

<div class="alert alert-success alert-dismissible pay-bank-notice" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    
    <?php if ($bk['bk_status'] == '대기') { ?>
    <strong class="res-message-title">예약신청이 완료되었습니다.</strong>

    <?php if ($wzpconfig['pn_is_pay']) {?>
    <ul class="desc">
        <li><i class="fa fa-info-circle fa-lg"></i> <strong><?php echo date("Y년m월d일 H시", strtotime($bk['bk_time']." + ".$wzpconfig['pn_wating_time']." hours"));?>까지</strong> 입금을 완료하지 않을경우 자동취소 됩니다.</li>
        <li><i class="fa fa-info-circle fa-lg"></i> 인터넷 예약 특성상 입금시간이 지체되면 예약이 중복될수 있어 빠른입금 부탁드립니다.</li>
        <li><i class="fa fa-info-circle fa-lg"></i> 입금완료 후 미리 준비할 수 있도록 이용전 통화하시는것이 좋습니다.</li>
    </ul>
    <?php } ?>

    <?php } else if ($bk['bk_status'] == '취소') { ?>
    <strong class="res-message-title">예약이 취소되었습니다.</strong>

    <?php if ($wzpconfig['pn_is_pay']) {?>
    <ul class="desc">
        <li><i class="fa fa-info-circle fa-lg"></i> 환불수수료는 규정 및 이용안내 를 참고해주세요.</li>
    </ul>
    <?php } ?>

    <?php } else { ?>
    <strong class="res-message-title">예약이 완료되었습니다.</strong>
    <ul class="desc">
        <li><i class="fa fa-info-circle fa-lg"></i> 예약취소는 전화문의바랍니다.</li>

        <?php if ($wzpconfig['pn_is_pay']) {?>
        <li><i class="fa fa-info-circle fa-lg"></i> 환불수수료는 규정 및 이용안내 를 참고해주세요.</li>
        <?php } ?>

    </ul>
    <?php } ?>

</div>

<div class="panel panel-default">

	<div class="panel-heading"><strong><i class="fa fa-hotel fa-lg"></i> 객실정보</strong></div>
	<div class="table-responsive">
		<table class="table form-group form-group-sm table-bordered font-color-gray">
        <thead>
		<tr>
            <th scope="col">예약서비스</th>
            <th scope="col">이용일자</th>
            <th scope="col">예약시간</th>
            <th scope="col">인원</th>
            <?php if ($wzpconfig['pn_is_pay']) {?>
            <th scope="col">합계</th>
            <?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php
        $total_price = 0;
        if ($cnt_room > 0) { 
            $z = 0;
            foreach ($arr_room as $k => $v) {

                ?>
                <tr>
                    <td data-title="예약서비스"><?php echo $v['bkr_subject'];?></td>
                    <td data-title="이용일자"><?php echo wz_get_hangul_date_md($v['bkr_date']).'('.get_yoil($v['bkr_date']).')';?></td>
                    <td data-title="예약시간"><?php echo wz_get_hangul_time_hm($v['bkr_time']);?></td>
                    <td data-title="이용인원"><?php echo $v['bkr_cnt'];?></td>

                    <?php if ($wzpconfig['pn_is_pay']) {?>
                    <td data-title="합계"><?php echo number_format($v['bkr_price']);?></td>
                    <?php } ?>
                </tr>
                <?php 
                $total_price += $v['bkr_price'];
                $z++;
            }
        }
        ?>
        </tbody>
        
        <?php if ($wzpconfig['pn_is_pay']) {?>
        <thead>
        <tr>
            <th colspan="4">합계</th>
            <th><?php echo number_format($total_price);?></th>
        </tr> 
        </thead>
        <?php } ?>

        </table>
    </div>
</div>


<?php if ($cnt_option > 0) { ?>
<div class="panel panel-default">

    <div class="panel-heading"><strong><i class="fa fa-cart-plus fa-lg"></i> 이용옵션</strong></div>
	<div class="table-responsive">
        <table class="table form-group form-group-sm table-bordered font-color-gray">
        <caption></caption>
        <colgroup>
            <col>
            <col width="80px;">
            <col width="90px;">
        </colgroup>
        <thead>
        <tr>
            <th scope="col">옵션명</th>
            <th scope="col">수량</th>
            <?php if ($wzpconfig['pn_is_pay']) {?><th scope="col">금액</th><?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php 
        $total_option = 0;
        for ($z = 0; $z < $cnt_option; $z++) { 
            ?>
            <tr>
                <td><?php echo $arr_option[$z]['odo_name'].($arr_option[$z]['odo_memo'] ? ' ('.$arr_option[$z]['odo_memo'].')' : '');?></td>
                <td><?php echo $arr_option[$z]['odo_cnt'].$arr_option[$z]['odo_unit']?></td>
                <?php if ($wzpconfig['pn_is_pay']) {?><td><?php echo number_format($arr_option[$z]['odo_price']);?></td><?php } ?>
            </tr>
            <?php 
            $total_option    += $arr_option[$z]['odo_price'];
        } 
        ?>

        <?php if ($wzpconfig['pn_is_pay']) {?>
        <tr>
            <th colspan="2">합계</th>
            <th><?php echo number_format($total_option);?> 원</th>
        </tr>
        <?php } ?>

        </tbody>
        </table>
    </div>
</div>
<?php } ?>

<?php if ($wzpconfig['pn_is_pay']) {?>
<div class="row">

    <div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">예약금</h3>
            </div>
            <div class="panel-body">
                <?php echo number_format($bk['bk_reserv_price']);?> 원
                <span class="pull-right badge"><?php echo ($bk['bk_reserv_price'] <= ($bk['bk_price'] - $bk['bk_misu']) ? '결제완료' : '미결제');?></span>
            </div>
        </div>
    </div>

    <div class="col-sm-4">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">잔금</h3>
            </div>
            <div class="panel-body">
                <?php echo number_format($bk['bk_price'] - $bk['bk_reserv_price']);?> 원
                <div class="pull-right badge"><?php echo ($bk['bk_misu'] ? '미결제' : '결제완료');?></div>
            </div>
        </div>
    </div>
    
    <div class="col-sm-4">
        <div class="panel panel-success">
            <div class="panel-heading">
                <h3 class="panel-title">총이용금액</h3>
            </div>
            <div class="panel-body">
                <?php echo number_format($bk['bk_price']);?> 원 
                <div class="pull-right badge"><?php echo ($bk['bk_misu'] ? '미결제' : '결제완료');?></div>
            </div>
        </div>
    </div>

</div>
<?php } ?>

<div class="panel panel-default">

    <div class="panel-heading"><strong><i class="fa fa-cart-plus fa-lg"></i> 예약자 정보</strong></div>
	
    <div class="table-responsive">
        <table class="table form-group form-group-sm table-bordered font-color-gray">
        <caption></caption>
        <colgroup>
            <col width="80px;">
            <col>
        </colgroup>
        <tbody>
        
        <?php if ($wzpconfig['pn_is_pay']) {?>

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

        <?php } ?>

        <tr>
            <th scope="col">예약상태</th>
            <td>
                <?php echo $bk['bk_status'];?>
                <?php
                if ($bk['bk_status'] == '대기') { 

                    echo '<button type="button" class="btn btn-xs btn-danger" onclick="getCancel();">예약취소</button>';
                }
                ?>
            </td>
        </tr> 
        <tr>
            <th scope="col">예약번호</th>
            <td><?php echo $bk['od_id'];?></td>
        </tr>
        <tr>
            <th scope="col">예약일시</th>
            <td><?php echo $bk['bk_time'];?></td>
        </tr> 
        <tr>
            <th scope="col">예약자명</th>
            <td><?php echo $bk['bk_name'];?></td>
        </tr>
        <tr>
            <th scope="col">핸드폰</th>
            <td><?php echo $bk['bk_hp'];?></td>
        </tr>
        <tr>
            <th scope="col">이메일</th>
            <td><?php echo $bk['bk_email'];?></td>
        </tr>
        <tr>
            <th scope="col">요청사항</th>
            <td><?php echo conv_content($bk['bk_memo'],0);?></td>
        </tr>
        </tbody>
        </table>
    </div>

</div>

<script type="text/javascript">
<!--
    <?php if ($bk['bk_status'] == '대기') { 
    ?>
    function getCancel() {
        if (confirm("예약내역을 취소 하시겠습니까?")) {
            $.ajax({
                type: 'POST',
                url: '<?php echo $action_url?>',
                dataType: 'json',
                data: {'uid': '<?php echo $uid?>', 'od_id': '<?php echo $od_id?>', 'mode': 'cancel'},
                cache: false,
                async: false,
                success: function(json) {
                    if (json.rescd == '00') {
                        alert("취소되었습니다.");
                        location.reload();
                    }
                    else {
                        alert(json.restx);
                    }
                }
            });
        }
    }
    <?php } ?>
//-->
</script>
