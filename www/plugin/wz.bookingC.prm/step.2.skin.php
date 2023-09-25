<?php
if(!defined('_MALLSET_')) exit;
include_once(MS_PLUGIN_PATH.'/wz.bookingC.prm/lib/core.lib.php');
include_once(MS_LIB_PATH.'/global.lib.php');

$store_mb_id = $_POST['store_mb_id'];
$rm_ix = $_POST['rm_ix'];

if (isset($_POST['sch_day']) && $_POST['sch_day']) {
    $sch_day = preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $_POST['sch_day']) ? $_POST['sch_day'] : "";
}

if (!$sch_day) {
    alert("잘못된 접근입니다.", WZB_STATUS_URL);
}

// 선택예약정보 검증
unset($arr_room);

$arr_room   = wz_calculate_room($_POST);

$bk_subject = $arr_room[0]['rm_subject'];
$cnt_room   = count($arr_room);
/* echo "<br>";
echo "az:"."<br>";

print_r($arr_room); */

// 옵션정보
unset($arr_room_option);
$arr_room_option = array();

if($member['grade'] != 1){
    $sql_add = " and store_mb_id = '{$store_mb_id}' ";
}

$query = "select * from {$g5['wzb_room_option_table']} where cp_ix = '{$wzdc['cp_ix']}' and rmo_use = 1 {$sql_add} order by rmo_sort asc, rmo_ix desc ";

//echo $query; 

$res = sql_query($query);

while($row = sql_fetch_array($res)) {
    $arr_room_option[] = $row;
}
$cnt_room_option = count($arr_room_option);

//print_r($cnt_room_option);
//print_r($arr_room);
if ($res) sql_free_result($res);

// 예약번호 생성.
$od_id      = $wzdc['cp_ix'].substr(date('YmdHis',MS_SERVER_TIME),2).rand(100,999);
set_session('ss_order_id', $od_id);
$action_url = https_url(MS_PLUGIN_DIR.'/wz.bookingC.prm/step.2.update.php', true);
$goods      = $bk_subject . ($cnt_room>1 ? ' 외'.($cnt_room-1).'건' : '');

// 모바일 주문인지
$is_mobile_pay = is_mobile();

// PG 결제를 위한 코드
if ($wzpconfig['pn_pg_service']) {
    @include_once(WZB_PLUGIN_PATH.'/gender/'.$wzpconfig['pn_pg_service'].'/config.php');
    @include_once(WZB_PLUGIN_PATH.'/gender/'.$wzpconfig['pn_pg_service'].'/pg_form1.php');
}

include_once(MS_PLUGIN_PATH.'/jquery-ui/datepicker.php');
include_once(WZB_PLUGIN_PATH.'/navi_reserv.php');

//echo WZB_PLUGIN_PATH.'/navi_reserv.php';

//echo WZB_PLUGIN_PATH.'/gender/'.$wzpconfig['pn_pg_service'].'/pg_form1.php';

//point
$po = sql_fetch("SELECT * FROM shop_point where mb_id = '{$member['id']}' order by po_id desc limit 1 ");
if($po['po_mb_point'] > 0){
    $mb_point = $po['po_mb_point'];
}else{
    $mb_point = 0;
}

?>

<style>
	#con_lf{width:1200px;}
</style>

<form method="post" name="wzfrm" id="wzfrm" autocomplete="off">
<input type="hidden" name="mode" id="mode" value="step3" />
<input type="hidden" name="sch_day" id="sch_day" value="<?php echo $sch_day;?>" />
<input type="hidden" name="bo_table" id="bo_table" value="<?php echo $bo_table;?>" />
<input type="hidden" name="od_id" id="od_id" value="<?php echo $od_id;?>" />
<input type="hidden" name="cp_code" id="cp_code" value="<?php echo $cp_code;?>" />
<input type="hidden" name="store_mb_id" id="store_mb_id" value="<?php echo $store_mb_id;?>" />
<input type="hidden" name="rm_ix" id="rm_ix" value="<?php echo $rm_ix;?>" />

<div class="panel panel-default">

	<div class="panel-heading"><strong><i class="fa fa-calculator fa-lg"></i> 이용서비스정보</strong></div>
	<div class="table-responsive">
		<table class="table form-group form-group-sm table-bordered font-color-gray">
        <thead>
		<tr>
            <th scope="col">예약서비스</th>
            <th scope="col">이용일자</th>
            <th scope="col">예약시간</th>
            <th scope="col">인원</th>
			<!--
            <?php if ($wzpconfig['pn_is_pay']) {?>
            <th scope="col">요금</th>
            <th scope="col">합계</th>
            <?php } ?>
			-->
        </tr>
        </thead>
        <tbody>
        <?php
        $total_price = 0;

        if ($cnt_room > 0) {
            $z = 0;

            foreach ($arr_room as $k => $v) {

                ?>
                <input type="hidden" name="rm_ix[]"     value="<?php echo $v['rm_ix'];?>" />
                <input type="hidden" name="rm_time[]" id="rm_time_<?php echo $z;?>" value="<?php echo $v['rm_time'];?>" />

                <tr>
                    <td data-title="예약서비스"><?php echo $v['rm_subject'];?></td>
                    <td data-title="이용일자"><?php echo wz_get_hangul_date_md($sch_day).'('.get_yoil($sch_day).')';?></td>
                    <td data-title="예약시간"><?php echo wz_get_hangul_time_hm($v['rm_time']);?></td>
				   <td data-title="이용인원">
                        <select name="rm_cnt[]" id="rm_cnt_<?php echo $z;?>" data-price="<?php echo $v['rmt_price'];?>" data-price-type="<?php echo $v['rmt_price_type'];?>" class="form-control" style="width:60px;">
                            <?php
                            for ($i = 1; $i <= $v['rm_person_max']; $i++) {
                                echo '<option value="'.$i.'">'.$i.'</option>';
                            }
                            ?>
                        </select>
                    </td>
<!--
                    <?php if ($wzpconfig['pn_is_pay']) {?>
                    <td data-title="요금"><?php echo $v['rmt_price_type'];?> <?php echo number_format($v['rmt_price']);?></td>
                    <td data-title="합계"><span id="time-total-<?php echo $z;?>"><?php echo number_format($v['rmt_price']);?></span></td>
                    <?php } ?>
-->
                </tr>
				
                <?php
                $total_price += $v['rmt_price'];
                $z++;
            }
        }
        ?>
        </tbody>

<!--
        <?php if ($wzpconfig['pn_is_pay']) {?>
        <thead>
        <tr>
            <th colspan="5">합계</th>
            <th><span id="all-total"><?php echo number_format($total_price);?></span></th>
        </tr>
        </thead>
        <?php } ?>
-->

		</table>
	</div>
</div>

<?php if ($cnt_room_option) {?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><strong><i class="fa fa-cart-plus fa-lg"></i> 메뉴선택</strong></div>

            <div class="panel-body form-horizontal">

                <?php
                if ($cnt_room_option > 0) {
                    $z = 0;
                    foreach ($arr_room_option as $k => $v) {
                    ?>
                    <div class="form-group has-feedback form-group-sm">
                        <input type="hidden" name="opt[]" id="opt_<?php echo $z;?>" value="<?php echo $z;?>" />
                        <input type="hidden" name="rmo_ix[<?php echo $z;?>]" id="rmo_ix_<?php echo $z;?>" value="<?php echo $v['rmo_ix'];?>" class="cal_option_ix" />
                        <label class="col-sm-3 control-label" for="formGroupInputLarge"><?php echo $v['rmo_name'];?></label>
                        <div class="col-sm-9">
                            <div class="form-inline">
                                <div class="input-group ipprc">
                                    <select name="rmo_cnt[<?php echo $z;?>]" id="rmo_cnt_<?php echo $z;?>" class="form-control cal_option_cnt" <?php echo ($v['rmo_required'] ? 'required=required' : '');?> data-price="<?php echo $v['rmo_price'];?>" aria-describedby="helpblock_rmo_cnt" data-title="<?php echo $v['rmo_name'];?>" style="width:120px;">
                                        <?php
                                        for ($i=0;$i<=$v['rmo_cnt'];$i++) {
                                            echo '<option value="'.$i.'">'.$i.'</option>';
                                        }
                                        ?>
                                    </select>
                                    <div class="input-group-addon"><?php echo $v['rmo_unit'];?></div>
                                </div>
                                <div id="helpblock_rmo_cnt" class="help-block">
									<small class="text-dotum" style="margin-left:15px;"> <!-- (1<?php echo $v['rmo_unit'];?> 당 --> <?php echo number_format($v['rmo_price']);?> 원<!--) <?php echo $v['rmo_memo'];?>--></small>
								</div>
                            </div>
                        </div>
                    </div>
                    <?php
                    $z++;
                    }
                }
                ?>

                <?php if ($wzpconfig['pn_is_pay']) {?>
                <div class="form-group has-feedback form-group-sm">
                    <label class="col-sm-3 control-label" for="option_tot_price">합계</label>
                    <div class="col-sm-9">
                        <span class="form-control-static"><span id="option_tot_price">0</span> 원</span>
                    </div>
                </div>
                <?php } ?>

            </div>
        </div>
    </div>
</div>
<?php } ?>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading"><strong><i class="fa fa-user fa-lg"></i> 예약자정보</strong></div>

            <div class="panel-body form-horizontal">

                <div class="form-group form-group-sm">
                    <label class="col-sm-2 control-label" for="bk_name">예약자명</label>
                    <div class="col-sm-10">
                        <div class="form-inline">
                            <!--<div id="helpblock_bk_name" class="help-block"><small class="text-dotum">실명으로 입력해주세요</small></div>-->
                            <div class="input-group">
                                <input type="text" name="bk_name" value="<?php echo $member['name'];?>" id="bk_name" required class="form-control" maxlength="20" aria-describedby="helpblock_bk_name">
                                <span class="fa fa-check form-control-feedback"></span>
                            </div>

                        </div>
                    </div>
                </div>

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
                    <label class="col-sm-2 control-label" for="bk_email">이메일</label>
                    <div class="col-sm-10">
                        <div class="form-inline">
                            <div class="input-group">
                                <input type="email" name="bk_email" id="bk_email" value="<?php echo $member['email'];?>" required class="form-control email" size="35" maxlength="100">
                                <span class="fa fa-envelope form-control-feedback"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group has-feedback form-group-sm">
                    <label class="col-sm-2 control-label" for="bk_memo">요청사항(선택)</label>
                    <div class="col-sm-10">
                        <div class="form-inline">
                            <textarea name="bk_memo" id="bk_memo" class="form-control" cols="50" rows="5"></textarea>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<?php if ($wzpconfig['pn_is_pay']) {?>

<div class="panel panel-default">
	<div class="panel-heading"><strong><i class="fa fa-credit-card fa-lg"></i> 결제정보</strong></div>

    <div class="panel-body form-horizontal">
		<div class="form-group form-group-sm">
            <label class="col-xs-4 col-sm-2 control-label">총이용금액</label>
            <label class="col-xs-8 col-sm-2 control-label">
                <span id="pg-price-all"><?php echo number_format($total_price);?></span> 원
            </label>
        </div>
        <? if($mb_point > 0){ ?>
        <div class="form-group form-group-sm">
            <label class="col-xs-4 col-sm-2 control-label"><strong>포인트</strong></label>
            <label class="col-xs-8 col-sm-2 control-label">
                <strong><input type="text" name="use_point" class="form-control" id="use_point" value="" /></strong>
            </label>
            <div class="col-sm-7" style="    display: inline-block;">
                <label class="control-label text-muted font-12" style="margin-top: 7px;">(현재 포인트 : <? echo number_format($mb_point); ?>)</label>
            </div>
        </div>
        <? } ?>
        <div class="form-group form-group-sm">
            <label class="col-xs-4 col-sm-2 control-label"><strong>예약금</strong></label>
            <label class="col-xs-8 col-sm-2 control-label">
                <input type="hidden" name="reserv_price" id="reserv_price" value="" />
                <input type="hidden" name="org_bk_price" id="org_bk_price" value="" />
                <strong><span id="od_tot_price"><?php echo number_format($reserv_price);?></span> 원</strong>
            </label>
            <div class="col-sm-7">
                <label class="control-label text-muted font-12">(결제/입금이 완료되어야 최종 예약이 완료됩니다.)</label>
            </div>
        </div>
        <div class="form-group form-group-sm">
            <label class="col-xs-4 col-sm-2 control-label">결제방법</label>
            <div class="col-xs-8 col-sm-10 radio-line">
                <?php
                $is_bank_use = false;
                if ($wzpconfig['pn_bank_use']) {
                    $is_bank_use = true;
                    echo '<label><input type="radio" name="bk_payment" id="bk_payment_bank" class="payment_type" value="무통장" checked=checked /> 무통장입금</label>';
                }
                if ($wzpconfig['pn_pg_card_use']) {
                    echo '<label><input type="radio" name="bk_payment" id="bk_payment_card" class="payment_type" value="신용카드" /> 신용카드</label>';
                }
                if ($wzpconfig['pn_pg_vbank_use']) {
                    echo '<label><input type="radio" name="bk_payment" id="bk_payment_vbank" class="payment_type" value="가상계좌" /> 가상계좌</label>';
                }
//                if ($wzpconfig['pn_pg_dbank_use']) {
//                    echo '<label><input type="radio" name="bk_payment" id="bk_payment_dbank" class="payment_type" value="계좌이체" /> 계좌이체</label>';
//                }
                if ($wzpconfig['pn_pg_hp_use']) {
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
                    $str = explode("\n", trim($wzpconfig['pn_bank_info']));
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
                    <input type="text" name="bk_deposit_name" id="bk_deposit_name" class="form-control" value="<?php echo $member['mb_name'];?>" placeholder="입금자명" maxlength="20">
                </div>
            </div>

            <?php if ($is_admin) {?>
            <div class="form-group form-group-sm">
                <label class="col-xs-4 col-sm-2 control-label">예약상태</label>
                <div class="col-xs-8 col-sm-10 radio-line">
                    <label><input type="radio" name="adm_status" id="adm_status1" value="대기" checked /> 대기</label>
                    <label><input type="radio" name="adm_status" id="adm_status2" value="완료" /> 완료</label>
                </div>
            </div>
            <?php } ?>

        </div>
    </div>
</div>

<?php } ?>

<?php if (!$is_admin || $member['grade'] == 1) { ?>
<div class="panel panel-default">
    <div class="panel-heading"><strong><i class="fa fa-file-text-o fa-lg"></i> 이용규정안내</strong></div>
    <div class="panel-body">

        <?php if ($wzpconfig['pn_con_info']) { ?>
        <div class="bs-callout bs-callout-info">
            <h4>기본예약안내</h4>
            <?php echo $wzpconfig['pn_con_info'];?>
        </div>
        <?php } ?>

        <?php if ($wzpconfig['pn_con_checkinout']) { ?>
        <div class="bs-callout bs-callout-warning">
            <h4>이용 안내</h4>
            <?php echo $wzpconfig['pn_con_checkinout'];?>
        </div>
        <?php } ?>

        <?php if ($wzpconfig['pn_con_refund'] && $wzpconfig['pn_is_pay']) { ?>
        <div class="bs-callout bs-callout-warning">
            <h4>환불규정</h4>
            <?php echo $wzpconfig['pn_con_refund'];?>
        </div>
        <?php } ?>

    </div>
    <div class="panel-footer">
        <label><input type="checkbox" name="agree1" value="1" id="agree1" /> 상기의 내용을 숙지하고 예약 및 환불규정에 동의 합니다.</label>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><strong><i class="fa fa-file-text-o fa-lg"></i> 개인정보 활용 동의</strong></div>
    <div class="panel-body">

        귀하의 소중한 개인정보는 개인정보보호법의 관련 규정에 의하여 예약 및 조회 등 아래의 목적으로 수집 및 이용됩니다.
            <ul class="purpose">
            <li>1. 개인정보의 수집·이용 목적 - 예약, 조회를 위한 본인 확인 절차</li>
            <li>2. 개인정보 수집 항목 - 예약자명, 핸드폰, 이메일</li>
            <li>3. 개인정보의 보유 및 이용기간 - 이용자의 개인정보는 원칙적으로 개인정보의 처리목적이 달성되면 지체 없이 파기합니다.</li>
        </ul>

        예약을 위하여 수집된 개인정보는 ‘전자상거래 등에서의 소비자보호에 관한 법률’ 제6조에의거 정해진 기간동안 보유됩니다.<br />
        ※ 상기 내용은 고객님께 예약서비스를 제공하는데 필요한 최소한의 정보입니다.<br />
        ※ 상기 내용에 대하여 본인이 동의하지 않을 수 있으나, 그러할 경우 예약 서비스 제공에 차질이 발생할 수 있습니다.

    </div>
    <div class="panel-footer">
        <label><input type="checkbox" name="agree2" value="1" id="agree2" /> 개인정보 활용에 동의 합니다.</label>
    </div>
</div>
<?php } ?>

<div class="row">

    <div id="display_pay_button" class="col-md-12 btn-group-justified" role="group">
        <div class="btn-group" role="group">
            <button type="button" class="btn btn-lg btn-primary" onclick="location.href='<?php echo WZB_STATUS_URL;?>&mode=step1&sch_day=<?php echo $sch_day;?>';"><i class="fa fa-chevron-left fa-sm"></i> 이전단계</button>
        </div>
        <div class="btn-group" role="group">
            <button type="button" id="submit_next" data-loading-text="Loading..." autocomplete="off" class="btn btn-lg btn-success" onclick="getNext();"><i class="fa fa-check-circle-o fa-sm"></i> <?php echo ($is_admin ? '관리자로 ' : '');?>예약하기</button>
        </div>
    </div>

    <div id="display_pay_process" style="display:none;">
        결제가 진행중입니다...
    </div>

    <?php
    if ($wzpconfig['pn_pg_service']) {
        @include_once(WZB_PLUGIN_PATH.'/gender/'.$wzpconfig['pn_pg_service'].'/pg_form2.php');
    }
    ?>

</div>

</form>

<script type="text/javascript">
<!--
    
    var mb_point = <? echo $mb_point; ?>

    $(function() {

        // 인원선택
        $("select[name='rm_cnt[]']").on('change', function() {
            <?php if ($wzpconfig['pn_is_pay']) {?>calculate_order();<?php } ?>
        });

        $("input[name='use_point']").on('change', function() {
            <?php if ($wzpconfig['pn_is_pay']) {?>calculate_order();<?php } ?>
        });

        // 옵션 선택
        $(document).on('change', '.cal_option_cnt', function() {
            <?php if ($wzpconfig['pn_is_pay']) {?>calculate_order();<?php } ?>
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

        <?php if ($wzpconfig['pn_is_pay']) {?>calculate_order();<?php } ?>
    });

    function calculate_order() {

        var z = 0;
        var total_price = 0;

        if(mb_point > 0){
            mb_point2 = parseInt($("#use_point").val())
        }else{
            mb_point2 = mb_point;
        }

        if($("#use_point").val() > mb_point){
            alert("보유한 포인트보다 많습니다.");
            return false;
        }

        $("select[name='rm_cnt[]']").each(
            function(){
                var cnt = parseInt($(this).val());
                var price = parseInt($(this).attr('data-price'));
                var price_type = $(this).attr('data-price-type');

                if (price_type == '인당') {
                    price = price * cnt;
                }
                total_price += price;
                $('#time-total-'+z).html(number_format(price+""));
                z++;
            }
        )
        $('#all-total').html(number_format(total_price+""));

        var cnt_option = price_option = 0;
        i = 0;
        $('input[name="opt[]"]').each(
            function(){
                i = this.value;
                cnt_option   = parseInt($('#rmo_cnt_'+i).val()); // 선택갯수
                price_option += parseInt($('#rmo_cnt_'+i).attr('data-price')) * cnt_option;
            }
        );
        $('#option_tot_price').html(number_format(price_option+""));
        total_price += price_option;

        $('#pg-price-all').html(number_format(total_price+""));

        if($("#use_point").val() != ""){
            var total_price2 = total_price-mb_point2; //point 사용금액
        }else{
            var total_price2 = total_price; //point 사용금액
        }

        console.log(total_price, total_price2);

        var reserv_price = Math.round((total_price2 / 100) * <?php echo ($wzpconfig['pn_reserv_price_avg'] ? $wzpconfig['pn_reserv_price_avg'] : 100)?>);
        $('#reserv_price').val(reserv_price);
        $('#org_bk_price').val(reserv_price);
        $('#od_tot_price').html(number_format(reserv_price+""));

    }
    //console.log(mb_point);
    function getNext() {
        var f = document.forms.wzfrm;

        if(parseInt($("#use_point").val()) > 0){
            if(parseInt($("#use_point").val()) > mb_point){
                alert("보유한 포인트보다 많습니다.");
                return false;
            }
        }

        var rm_cnt = $("input[name='rm_ix[]']").length;
        if (rm_cnt < 1) {
            alert("예약정보가 존재하지 않습니다.");
            return;
        }
        if (!f.bk_name.value) {
            alert("예약자명을 입력해주세요.");
            f.bk_name.focus();
            return;
        }
        if (!f.bk_hp.value) {
            alert("핸드폰번호를 입력해주세요.");
            f.bk_hp.focus();
            return;
        }

        var payment = '무통장';

        <?php if ($wzpconfig['pn_is_pay']) {?>
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

        <?php } ?>

        <?php if (!$is_admin) { ?>
        if (f.agree1.checked == false) {
            alert("이용규정에 동의 후 예약이 가능합니다.");
            f.agree1.focus();
            return;
        }
        if (f.agree2.checked == false) {
            alert("개인정보 활용에 동의 후 예약이 가능합니다.");
            f.agree2.focus();
            return;
        }
        <?php } ?>

        if (payment == '무통장') {
            if (confirm("예약하시겠습니까?")) {
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
