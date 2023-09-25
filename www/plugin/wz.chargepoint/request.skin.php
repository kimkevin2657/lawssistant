<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

// 결제번호 생성.
$od_id      = substr(date('YmdHis',G5_SERVER_TIME),2).rand(1000,9999);
set_session('ss_order_id', $od_id);
$action_url = https_url(MS_PLUGIN_DIR.'/wz.chargepoint/request.update.php', true);
$goods      = 'Oh!포인트';
$token      = md5(uniqid(rand(), true)); set_session('WPOT_token', $token);

// 포인트정보
unset($arr_pt);
$arr_pt = wz_point_list();
$cnt_pt = count($arr_pt);

// 모바일 주문인지
$is_mobile_pay = is_mobile();

// PG 결제를 위한 코드
if ($wzcnf['cf_pg_service']) {
    @include_once(WPOT_PLUGIN_PATH.'/gender/'.$wzcnf['cf_pg_service'].'/config.php');
    @include_once(WPOT_PLUGIN_PATH.'/gender/'.$wzcnf['cf_pg_service'].'/pg_form1.php');
}
?>

<?php include_once(WPOT_PLUGIN_PATH.'/navi_reserv.php'); ?>

<?php if ($wzcnf['cf_con_notice']) { ?>
<div style="margin:10px 0 15px;"><?php echo $wzcnf['cf_con_notice'];?></div>
<?php } ?>

<form method="post" name="wzfrm" id="wzfrm" autocomplete="off">
<input type="hidden" name="bo_table" id="bo_table" value="<?php echo $bo_table;?>" />
<input type="hidden" name="od_id" id="od_id" value="<?php echo $od_id;?>" />
<input type="hidden" name="org_bk_price" id="org_bk_price" value="" />
<input type="hidden" name="token" id="token" value="<?php echo $token;?>" />
<input type="hidden" name="bk_price" id="bk_price" value="0" />

<?php if ($wzcnf['cf_point_pay_type'] == '1' || $wzcnf['cf_point_pay_type'] == '2') {?>
<div class="panel panel-default">
    <div class="panel-heading"><strong><i class="fa fa-money fa-lg"></i> 충전하실 Oh!포인트 선택</strong></div>
    <ul id="select-points">
        <?php
        if ($cnt_pt > 0) {
            foreach ($arr_pt as $k => $v) {
                $selected = '';
                echo '<li><label class="style-chk"><input type="radio" name="cfp_ix" value="'.$v['cfp_ix'].'" data-point="'.$v['cfp_point'].'" data-price="'.$v['cfp_price'].'" /><span class="chk"></span> '.number_format($v['cfp_point']).' '.WPOT_POINT_TEXT.' ('.number_format($v['cfp_price']).'원)</label></li>'.PHP_EOL;
            }
        }
        if ($wzcnf['cf_point_pay_type'] == '2') {
            ?>
                <li class="form-group-sm form-inline"><label class="style-chk"><input type="radio" name="cfp_ix" value="" data-point="0" data-price="0" /><span class="chk"></span> 직접입력&nbsp;&nbsp;</label>
                <input type="text" name="bk_charge_point" id="bk_charge_point" class="form-control" size="15" value="" disabled style="display:inline-block;width:auto;" />
                <span class="label-text">&nbsp;(<span id="charge-point-txt">0</span>원)</span></li>
            <?php
        }
        ?>
    </ul>
</div>
<?php } ?>

<div class="panel panel-default">
    <div class="panel-heading"><strong><i class="fa fa-user fa-lg"></i> 결제자 정보</strong></div>
    <div class="panel-body form-horizontal">

        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="bk_hp">핸드폰</label>
            <div class="col-sm-10">
                <div class="form-inline">
                    <div class="input-group">
                        <input type="text" name="bk_hp" value="<?php echo str_replace('-', '', $member['cellphone']);?>" id="bk_hp" required class="form-control" maxlength="20">
                        <span class="fa fa-phone form-control-feedback"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-group form-group-sm">
            <label class="col-sm-2 control-label" for="bk_email">이메일(선택)</label>
            <div class="col-sm-10">
                <div class="form-inline">
                    <div class="input-group">
                        <input type="email" name="bk_email" id="bk_email" value="<?php echo $member['email'];?>" required class="form-control email" size="35" maxlength="100">
                        <span class="fa fa-envelope form-control-feedback"></span>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><strong><i class="fa fa-credit-card fa-lg"></i> 결제정보</strong></div>

    <div class="panel-body form-horizontal">

        <?php if ($wzcnf['cf_point_pay_type'] == '3') { ?>
        <div class="form-group form-group-sm">
            <label class="col-xs-4 col-sm-2 control-label" for="bk_charge_point">충전 Oh!포인트</label>
            <div class="col-xs-8 col-sm-2">
                <input type="text" name="bk_charge_point" id="bk_charge_point" class="form-control" value="" placeholder="충전 Oh!포인트 입력" maxlength="20">
            </div>
        </div>
        <?php } ?>

        <div class="form-group form-group-sm">
            <label class="col-xs-4 col-sm-2 control-label">총결제금액</label>
            <label class="col-xs-8 col-sm-2 control-label">
                <span id="pg-price-all"><?php echo number_format($total_price);?></span> 원
            </label>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-xs-4 col-sm-2 control-label">결제방법</label>
            <div class="col-xs-8 col-sm-10 radio-line">
                <?php
                $is_bank_use = false;
                if ($wzcnf['cf_bank_use']) {
                    $is_bank_use = true;
                    echo '<label><input type="radio" name="bk_payment" id="bk_payment_bank" class="payment_type" value="무통장" checked=checked /> 무통장입금</label>';
                }
                if ($wzcnf['cf_pg_card_use']) {
                    echo '<label><input type="radio" name="bk_payment" id="bk_payment_card" class="payment_type" value="신용카드" /> 신용카드</label>';
                }
                if ($wzcnf['cf_pg_vbank_use']) {
                    echo '<label><input type="radio" name="bk_payment" id="bk_payment_vbank" class="payment_type" value="가상계좌" /> 가상계좌</label>';
                }
                if ($wzcnf['cf_pg_dbank_use']) {
                    echo '<label><input type="radio" name="bk_payment" id="bk_payment_dbank" class="payment_type" value="계좌이체" /> 계좌이체</label>';
                }
                if ($wzcnf['cf_pg_hp_use']) {
                    echo '<label><input type="radio" name="bk_payment" id="bk_payment_hp" class="payment_type" value="휴대폰" /> 휴대폰</label>';
                }
                ?>
            </div>
        </div>

        <div id="bank_info_box" style="display:<?php echo ($is_bank_use ? '' : 'none');?>">
            <div class="form-group form-group-sm">
                <label class="col-xs-4 col-sm-2 control-label" for="od_bank_account">입금계좌</label>
                <div class="col-xs-8 col-sm-4">
                    <?php
                    $str = explode("\n", trim($wzcnf['cf_bank_info']));
                    $bank_account = '<select name="bk_bank_account" id="bk_bank_account" class="form-control">'.PHP_EOL;
                    if (count($str) > 1) {
                        $bank_account .= '<option value="">선택하십시오.</option>';
                    }
                    for ($i=0; $i<count($str); $i++) {
                        $str[$i] = trim($str[$i]);
                        $bank_account .= '<option value="'.$str[$i].'">'.$str[$i].'</option>'.PHP_EOL;
                    }
                    $bank_account .= '</select>'.PHP_EOL;

                    echo $bank_account;
                    ?>
                </div>
            </div>

            <div class="form-group form-group-sm">
                <label class="col-xs-4 col-sm-2 control-label" for="od_deposit_name">입금자명</label>
                <div class="col-xs-8 col-sm-2">
                    <input type="text" name="bk_deposit_name" id="bk_deposit_name" class="form-control" value="<?php echo $member['name'];?>" placeholder="입금자명" maxlength="20">
                </div>
            </div>

        </div>
    </div>
</div>

<?php if ($wzcnf['cf_con_refund']) { ?>
<div class="panel panel-default">
    <div class="panel-heading"><strong><i class="fa fa-file-text-o fa-lg"></i> 환불규정안내</strong></div>
    <div class="panel-body">
        <div class="bs-callout bs-callout-warning">
            <?php echo $wzcnf['cf_con_refund'];?>
        </div>
    </div>
    <div class="panel-footer">
        <label><input type="checkbox" name="agree1" value="1" id="agree1" /> 상기의 내용을 숙지하고 환불규정에 동의 합니다.</label>
    </div>
</div>
<?php } ?>

<div class="row">

    <div id="display_pay_button" class="col-md-12 btn-group-justified" role="group">
        <div class="btn-group" role="group">
            <!--button type="button" class="btn btn-primary" onclick="location.href='<?php echo WZB_STATUS_URL;?>&mode=step1&sch_day=<?php echo $sch_day;?>';"><i class="fa fa-chevron-left fa-sm"></i> 취소하기</button-->
            <button type="button" class="btn btn-primary" onclick="history.back()"><i class="fa fa-chevron-left fa-sm"></i> 취소하기</button>
        </div>
        <div class="btn-group" role="group">
            <button type="button" id="submit_next" data-loading-text="Loading..." autocomplete="off" class="btn btn-success" onclick="getNext();"><i class="fa fa-check-circle-o fa-sm"></i> 충전하기</button>
        </div>
    </div>

    <div id="display_pay_process" style="display:none;">
        결제가 진행중입니다...
    </div>

    <?php
    if ($wzcnf['cf_pg_service']) {
        @include_once(WPOT_PLUGIN_PATH.'/gender/'.$wzcnf['cf_pg_service'].'/pg_form2.php');
    }
    ?>

</div>

</form>

<div class="clearfix" style="height:10px;"></div>

<script type="text/javascript">
<!--
    $(function() {

        // 옵션 선택
        $(document).on('change', ":input:radio[name='cfp_ix']", function() {
            var cfp_ix = $(this).val();
            if (cfp_ix) {
                $('#bk_charge_point').attr('disabled', true);
                choice_point();
            }
            else { // 직접입력
                $('#bk_charge_point').attr('disabled', false).focus();
                write_point();
            }
        });

        $('.payment_type').on('click', function() {
            var payment = $(':input:radio[name=bk_payment]:checked').val();
            if (payment == '무통장') {
                $('#bk_deposit_name').val( $('#bk_name').val() );
                $('#bank_info_box').show();
            }
            else {
                $('#bank_info_box').hide();
            }
        });

        // 직접입력
        $(document).on('propertychange change keyup paste input', '#bk_charge_point', function() {
            write_point();
        });
    });

    function choice_point() {

        var z = 0;
        var total_price = $(":input:radio[name='cfp_ix']:checked").attr('data-price');
        if (!total_price) {
            total_price = 0;
        }

        $('#bk_price').val(total_price);
        $('#org_bk_price').val(total_price);
        $('#pg-price-all').html(number_format(total_price+""));
    }

    function write_point() {

        var regex = /[^0-9]/g;
        var charge_point = $('#bk_charge_point').val();
            charge_point = parseInt(charge_point.replace(regex, ''));
        var total_price = 0;
        if (isNaN(charge_point)) {
            total_price = 0;
        }
        else {
            total_price = charge_point + Math.round( charge_point / 100 * <?php echo $wzcnf['cf_point_pay_ratio'];?> );
        }

        <?php if ($wzcnf['cf_point_pay_type'] == '2') {?>
            $('#charge-point-txt').html(number_format(total_price+""));
        <?php } ?>

        $('#bk_price').val(total_price);
        $('#org_bk_price').val(total_price);
        $('#pg-price-all').html(number_format(total_price+""));
    }

    function getNext() {

        var f = document.forms.wzfrm;

        <?php if ($wzcnf['cf_point_pay_type'] == '1' || $wzcnf['cf_point_pay_type'] == '2') {?>
            var cfp_ix = $(":input:radio[name=cfp_ix]:checked").val();
            var charge_point = $("#bk_charge_point").val();
            if (!cfp_ix && !charge_point) {
                alert("충전하실 Oh!포인트를 선택해주세요.");
                return;
            }
        <?php } else { ?>
            var charge_point = $("#bk_charge_point").val();
            if (!charge_point) {
                alert("충전하실 Oh!포인트를 입력해주세요.");
                f.bk_charge_point.focus();
                return;
            }
        <?php } ?>

        if (!f.bk_hp.value) {
            alert("핸드폰번호를 입력해주세요.");
            f.bk_hp.focus();
            return;
        }

        var payment = '무통장';

        var _bk_payment = f.bk_payment.value;
        if (_bk_payment == '무통장') {
            if (!f.bk_deposit_name.value) {
                alert("입금자명을 입력해주세요.");
                f.bk_deposit_name.focus();
                return;
            }
            var bk_bank_account = document.getElementById("bk_bank_account");
            if (bk_bank_account) {
                if (f.bk_bank_account.options[f.bk_bank_account.selectedIndex].value == '') {
                    alert("계좌번호를 선택해주세요.");
                    f.bk_bank_account.focus();
                    return;
                }
            }
        }

        payment = $(":input:radio[name=bk_payment]:checked").val();
        if (!payment) {
            alert("결제방식을 선택해주세요.");
            return;
        }

        <?php if ($wzcnf['cf_con_refund']) { ?>
        if (f.agree1.checked == false) {
            alert("환불규정에 동의 후 충전이 가능합니다.");
            f.agree1.focus();
            return;
        }
        <?php } ?>

        if (payment == '무통장') {
            if (confirm("충전하시겠습니까?")) {
                f.action = "<?php echo $action_url;?>";
                f.target = "_self";
                f.submit();
            }
        }
        else {
            pg_pay(f);
        }

    }
//-->
</script>
