<?php
include_once('./_common.php');

$g5['title'] = '환경설정';

$db_reload = false;

// 기본정보 생성
if(!sql_query(" DESCRIBE wpot_config ", false)) {
    sql_query(" CREATE TABLE wpot_config (
                    `cf_ix` INT(11) NOT NULL AUTO_INCREMENT,
                    `cf_bank_info` TEXT NOT NULL,
                    `cf_con_notice` TEXT NOT NULL,
                    `cf_con_refund` TEXT NOT NULL,
                    `cf_bank_use` TINYINT(4) NOT NULL DEFAULT '0',
                    `cf_pg_service` VARCHAR(20) NOT NULL,
                    `cf_pg_dbank_use` TINYINT(4) NOT NULL DEFAULT '0',
                    `cf_pg_vbank_use` TINYINT(4) NOT NULL DEFAULT '0',
                    `cf_pg_hp_use` TINYINT(4) NOT NULL DEFAULT '0',
                    `cf_pg_card_use` TINYINT(4) NOT NULL DEFAULT '0',
                    `cf_pg_test` TINYINT(4) NOT NULL DEFAULT '1',
                    `cf_pg_mid` VARCHAR(100) NOT NULL,
                    `cf_pg_site_key` VARCHAR(255) NOT NULL,
                    `cf_pg_sign_key` VARCHAR(255) NOT NULL,
                    `cf_point_term` INT(11) NOT NULL DEFAULT '0' COMMENT '충전유효기간',
                    `cps_sms_receive` VARCHAR(255) NOT NULL COMMENT '관리자수신번호',
                    `cps_sms1_con_user` TEXT NOT NULL COMMENT '충전대기(충전자)',
                    `cps_sms2_con_user` TEXT NOT NULL COMMENT '충전완료(충전자)',
                    `cps_sms3_con_user` TEXT NOT NULL COMMENT '충전취소(충전자)',
                    `cps_sms1_use_user` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '충전대기사용여부(충전자)',
                    `cps_sms2_use_user` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '충전완료사용여부(충전자)',
                    `cps_sms3_use_user` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '충전취소사용여부(충전자)',
                    `cps_sms1_con_adm` TEXT NOT NULL COMMENT '충전대기(관리자)',
                    `cps_sms2_con_adm` TEXT NOT NULL COMMENT '충전완료(관리자)',
                    `cps_sms3_con_adm` TEXT NOT NULL COMMENT '충전취소(관리자)',
                    `cps_sms1_use_adm` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '충전대기사용여부(관리자)',
                    `cps_sms2_use_adm` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '충전완료사용여부(관리자)',
                    `cps_sms3_use_adm` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '충전취소사용여부(관리자)',
                    PRIMARY KEY (`cf_ix`)
                )
                COMMENT='기본정보'
                ENGINE=MyISAM DEFAULT CHARSET=utf8;", true);

    sql_query(" INSERT INTO wpot_config
                    SET `cf_bank_info`          = '신한은행 333-333-33333 홍길동\r\n신한은행 555-555-55555 홍길동',
                        `cf_con_notice`         = '관리자화면에서 작성한 공지사항글이 등록됩니다. 에디터로 등록됩니다. 사진등록가능',
                        `cf_con_refund`         = '관리자화면에서 에디터로 수정가능',
                        `cf_bank_use`           = 1,
                        `cps_sms1_con_user`     = '{아이디}님의 충전신청이 완료되었습니다.\r\r{입금계좌정보}',
                        `cps_sms2_con_user`     = '{아이디}님의 충전이 완료되었습니다.\r\r충전포인트:{충전포인트}\r결제금액:{결제금액}',
                        `cps_sms3_con_user`     = '{아이디}님의 충전이 취소되었습니다.\r\r충전포인트:{충전포인트}\r결제금액:{결제금액}',
                        `cps_sms1_con_adm`      = '{아이디}님의 충전신청이 완료되었습니다.\r\r충전포인트:{충전포인트}',
                        `cps_sms2_con_adm`      = '{아이디}님의 충전이 완료되었습니다.\r\r충전포인트:{충전포인트}\r결제금액:{결제금액}',
                        `cps_sms3_con_adm`      = '{아이디}님의 충전이 취소되었습니다.\r\r충전포인트:{충전포인트}\r결제금액:{결제금액}'
            ", true);
    $db_reload = true;
}

// 기본정보 포인트 생성
if(!sql_query(" DESCRIBE wpot_config_point ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `wpot_config_point` (
                    `cfp_ix` INT(11) NOT NULL AUTO_INCREMENT,
                    `cfp_price` INT(11) NOT NULL DEFAULT '0',
                    `cfp_point` INT(11) NOT NULL DEFAULT '0',
                    PRIMARY KEY (`cfp_ix`)
                )
                COMMENT='기본정보포인트'
                ENGINE=MyISAM  DEFAULT CHARSET=utf8;", true);

    sql_query(" INSERT INTO wpot_config_point (cfp_price, cfp_point) VALUES(1100,1000), (2200,2000);", true);

    $db_reload = true;
}

// 충전정보 생성
if(!sql_query(" DESCRIBE wpot_order ", false)) {
    sql_query(" CREATE TABLE IF NOT EXISTS `wpot_order` (
                    `bo_table` VARCHAR(20) NOT NULL COMMENT '게시판코드',
                    `od_id` BIGINT(20) NOT NULL DEFAULT '0',
                    `mb_id` VARCHAR(20) NOT NULL,
                    `bk_subject` VARCHAR(100) NOT NULL,
                    `bk_hp` VARCHAR(20) NOT NULL,
                    `bk_email` VARCHAR(100) NOT NULL,
                    `bk_payment` VARCHAR(10) NOT NULL,
                    `bk_deposit_name` VARCHAR(20) NOT NULL,
                    `bk_bank_account` VARCHAR(255) NOT NULL,
                    `bk_price` INT(11) NOT NULL DEFAULT '0' COMMENT '결제할금액',
                    `bk_charge_point` INT(11) NOT NULL DEFAULT '0' COMMENT '충전할포인트',
                    `bk_chargepoint_term` INT(11) NOT NULL DEFAULT '0' COMMENT '충전포인트유효기간',
                    `bk_pg_price` INT(11) NOT NULL DEFAULT '0' COMMENT 'PG사결제요청금액',
                    `bk_receipt_price` INT(11) NOT NULL DEFAULT '0' COMMENT '결제완료금액',
                    `bk_pg_cancel` INT(11) NOT NULL DEFAULT '0' COMMENT 'PG사승인취소완료금액',
                    `bk_receipt_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                    `bk_mobile` TINYINT(4) NOT NULL DEFAULT '0',
                    `bk_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                    `bk_ip` VARCHAR(20) NOT NULL,
                    `bk_status` ENUM('대기','완료','취소') NOT NULL DEFAULT '대기',
                    `bk_log` VARCHAR(255) NOT NULL,
                    `bk_pg` VARCHAR(20) NOT NULL,
                    `bk_tno` VARCHAR(255) NOT NULL,
                    `bk_app_no` VARCHAR(100) NOT NULL,
                    `bk_cancel_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                    `bk_cancel_ip` VARCHAR(20) NOT NULL,
                    `bk_cancel_pos` VARCHAR(10) NOT NULL,
                    `bk_is_charge` TINYINT(4) NOT NULL DEFAULT '0' COMMENT '충전여부',
                    PRIMARY KEY (`od_id`),
                    INDEX `mb_id` (`mb_id`),
                    INDEX `bo_table` (`bo_table`)
                    )
                COMMENT='충전정보'
                ENGINE=MyISAM  DEFAULT CHARSET=utf8;", true);
    $db_reload = true;
}

// 충전정보임시 생성
if(!sql_query(" DESCRIBE wpot_order_data ", false)) {
        sql_query(" CREATE TABLE IF NOT EXISTS `wpot_order_data` (
                        `od_id` BIGINT(20) UNSIGNED NOT NULL,
                        `mb_id` VARCHAR(20) NOT NULL DEFAULT '',
                        `dt_pg` VARCHAR(255) NOT NULL DEFAULT '',
                        `dt_data` TEXT NOT NULL,
                        `dt_time` DATETIME NOT NULL DEFAULT '0000-00-00 00:00:00',
                        INDEX `od_id` (`od_id`)
                )
                COMMENT='충전정보임시'
                ENGINE=MyISAM  DEFAULT CHARSET=utf8;", true);
    $db_reload = true;
}

// 충전방식설정
$query = "show columns from `wpot_config` like 'cf_point_pay_type' ";
$res = sql_fetch($query);
if (empty($res)) {
    sql_query(" ALTER TABLE `wpot_config`
                    ADD `cf_point_pay_type` tinyint(4) DEFAULT '1' COMMENT '포인트결제방식',
                    ADD `cf_point_pay_ratio` float DEFAULT '0' COMMENT '직접입력포인트율'
                    ; ", true);
    $db_reload = true;
}

if ($db_reload) {
    alert("DB를 갱신합니다.", './config.php');
}

// 포인트정보
unset($arr_pt);
$arr_pt = wz_point_list();
$cnt_pt = count($arr_pt);

include_once (MS_ADMIN_PATH.'/admin.head.php');
include_once(MS_EDITOR_LIB);
?>

<form name="frm" id="frm" action="./config_update.php" method="post" enctype="multipart/form-data" onsubmit="return getAction(document.forms.frm);">
<input type="hidden" name="code" id="code" value="<?php echo $code;?>" />
<h2 class="h2_frm">포인트설정</h2>

<div class="tbl_frm01 tbl_wrap">

    <div class="local_desc01 local_desc" style="width:600px;">
        <label><input type="radio" name="cf_point_pay_type" id="cf_point_pay_type1" value="1" <?php echo ($wzcnf['cf_point_pay_type'] == '1' ? 'checked' : '');?> /> 포인트선택</label>&nbsp;
        <label><input type="radio" name="cf_point_pay_type" id="cf_point_pay_type2" value="2" <?php echo ($wzcnf['cf_point_pay_type'] == '2' ? 'checked' : '');?> /> 포인트선택 + 직접입력</label>&nbsp;
        <label><input type="radio" name="cf_point_pay_type" id="cf_point_pay_type3" value="3" <?php echo ($wzcnf['cf_point_pay_type'] == '3' ? 'checked' : '');?> /> 직접입력</label>
    </div>

    <table cellspacing="0" border="1" class="tbl_type" style="width:600px;" id="wrap-tbl-point">
    <caption></caption>
    <colgroup>
        <col width="40%"/>
        <col width="40%"/>
        <col width="20%"/>
    </colgroup>
    <thead>
    <tr>
        <th scope="row" class="center">포인트</th>
        <th scope="row" class="center">결제금액</th>
        <th scope="row" class="center"><a href="#none" class="btn_frmline add-tr">추가</a></th>
    </tr>
    </thead>
    <tbody>

    <tr id="point-custom">
        <td class="center">
            결제할 포인트 직접입력
        </td>
        <td class="center" colspan="2">
            결제금액에 <?php echo WPOT_POINT_TEXT;?>당 <input type="text" name="cf_point_pay_ratio" value="<?php echo $wzcnf['cf_point_pay_ratio'];?>" required class="required frm_input number" maxlength="3" size="3" /> % 추가 적용
        </td>
    </tr>

    <?php
    if ($cnt_pt > 0) {
        foreach ($arr_pt as $k => $v) {
        ?>
        <tr class="point-choice">
            <td class="center">
                <input type="hidden" name="cfp_ix[]" value="<?php echo $v['cfp_ix'];?>" />
                <input type="text" name="cfp_point[]" value="<?php echo $v['cfp_point'];?>" required class="required frm_input number" maxlength="20" /> <?php echo WPOT_POINT_TEXT;?>
            </td>
            <td class="center">
                <input type="text" name="cfp_price[]" value="<?php echo $v['cfp_price'];?>" required class="required frm_input number" maxlength="20" /> 원
            </td>
            <td class="center"><a href="#none" class="btn_frmline del-tr" data-cfp-ix="<?php echo $v['cfp_ix'];?>">삭제</a></td>
        </tr>
        <?php
        }
    }
    ?>
    </tbody>
    </table>
</div>

<div class="btn_fixed_top" style="margin-left: 20px;">
    <input type="submit" value="수정" class="btn_submi btn btn_01" accesskey="s">
</div>

<h2 class="h2_frm">환경설정</h2>
<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption>환경설정</caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">공지</th>
        <td>
            <?php echo editor_html('cf_con_notice', get_text($wzcnf['cf_con_notice'], 0)); ?>
        </td>
    </tr>
    <tr>
        <th scope="row">환불규정</th>
        <td>
            <?php echo editor_html('cf_con_refund', get_text($wzcnf['cf_con_refund'], 0)); ?>
        </td>
    </tr>
    </tbody>
    </table>
</div>
<div class="btn_fixed_top" style="margin-left: 20px;">
    <input type="submit" value="수정" class="btn_submi btn btn_01" accesskey="s">
</div>
<h2 class="h2_frm">결제설정</h2>
<div class="tbl_frm01 tbl_wrap">
    <table>
    <caption>결제설정</caption>
    <colgroup>
        <col class="grid_4">
        <col>
    </colgroup>
    <tbody>
    <tr>
        <th scope="row">무통장입금사용</th>
        <td>
            <?php echo help("예약시 무통장으로 입금을 가능하게 할것인지를 설정합니다.\n사용할 경우 은행계좌번호를 반드시 입력하여 주십시오.", 50); ?>
            <select id="cf_bank_use" name="cf_bank_use">
                <option value="0" <?php echo get_selected($wzcnf['cf_bank_use'], 0); ?>>사용안함</option>
                <option value="1" <?php echo get_selected($wzcnf['cf_bank_use'], 1); ?>>사용</option>
            </select>
        </td>
    </tr>
    <tr>
        <th scope="row">입금계좌정보</th>
        <td>
            <div style="margin:5px 0">엔터로 구분 등록해주세요.</div>
            <textarea name="cf_bank_info" id="cf_bank_info" style="height:60px;"><?php echo $wzcnf['cf_bank_info']; ?></textarea>
        </td>
    </tr>

    <?php
    @include_once(WPOT_PLUGIN_PATH.'/gender/pg.setting.1.php');
    ?>

    </tbody>
    </table>
</div>

<div class="btn_fixed_top" style="margin-left: 20px;">
    <input type="submit" value="수정" class="btn_submi btn btn_01" accesskey="s">
</div>

<h2 class="h2_frm">문자정보 (결제자발송용)</h2>

<div class="tbl_frm01 tbl_wrap">

    <div class="sim-bx auto">
        <div class="bx-hd" style="width:140px;">
            <p>결제대기 (결제자)&nbsp;&nbsp;<label><input type="checkbox" name="cps_sms1_use_user" id="cps_sms1_use_user" value="1" <?php echo $wzcnf['cps_sms1_use_user'] ? 'checked=checked' : '';?> /> 사용</label></p>
        </div>
        <div class="bx-ft">
            <p class="important">
                <textarea cols="16" rows="6" id="cps_sms1_con_user" name="cps_sms1_con_user" wrap="virtual" onkeyup="byte_check('cps_sms1_con_user', 'byte1', 'byte1_max');" class="sms-con"><?php echo $wzcnf['cps_sms1_con_user'];?></textarea>
            </p>
            <p class="important">
                <span id="byte1">0</span> / <span id="byte1_max"><?php echo ($config['cf_sms_type'] == 'LMS' ? 90 : 80); ?></span> byte
            </p>
        </div>
    </div>

    <div class="blank_box"></div>

    <div class="sim-bx auto">
        <div class="bx-hd" style="width:140px;">
            <p>결제완료 (결제자)&nbsp;&nbsp;<label><input type="checkbox" name="cps_sms2_use_user" id="cps_sms2_use_user" value="1" <?php echo $wzcnf['cps_sms2_use_user'] ? 'checked=checked' : '';?> /> 사용</label></p>
        </div>
        <div class="bx-ft">
            <p class="important">
                <textarea cols="16" rows="6" id="cps_sms2_con_user" name="cps_sms2_con_user" wrap="virtual" onkeyup="byte_check('cps_sms2_con_user', 'byte2', 'byte2_max');" class="sms-con"><?php echo $wzcnf['cps_sms2_con_user'];?></textarea>
            </p>
            <p class="important">
                <span id="byte2">0</span> / <span id="byte2_max"><?php echo ($config['cf_sms_type'] == 'LMS' ? 90 : 80); ?></span> byte
            </p>
        </div>
    </div>

    <div class="blank_box"></div>

    <div class="sim-bx auto">
        <div class="bx-hd" style="width:140px;">
            <p>결제취소 (결제자)&nbsp;&nbsp;<label><input type="checkbox" name="cps_sms3_use_user" id="cps_sms3_use_user" value="1" <?php echo $wzcnf['cps_sms3_use_user'] ? 'checked=checked' : '';?> /> 사용</label></p>
        </div>
        <div class="bx-ft">
            <p class="important">
                <textarea cols="16" rows="6" id="cps_sms3_con_user" name="cps_sms3_con_user" wrap="virtual" onkeyup="byte_check('cps_sms3_con_user', 'byte3', 'byte3_max');" class="sms-con"><?php echo $wzcnf['cps_sms3_con_user'];?></textarea>
            </p>
            <p class="important">
                <span id="byte3">0</span> / <span id="byte3_max"><?php echo ($config['cf_sms_type'] == 'LMS' ? 90 : 80); ?></span> byte
            </p>
        </div>
    </div>

</div>

<hr class="section-division"></hr>

<h2 class="h2_frm">문자정보 (관리자발송용)</h2>

<div class="tbl_frm01 tbl_wrap">

    <div style="margin:5px 0">
        <table cellpadding=0 cellspacing=0 border=0 style="width:476px;">
            <caption></caption>
            <tbody>
            <tr>
                <th width="30%" scope="col">관리자 수신번호</th>
                <td width="70%">
                    <?php echo help('여러개의 번호일경우 컴마 , 단위로 입력해주세요. <br />(예: 0102222222,0103333333,0104444444)');?>
                    <input type="text" name="cps_sms_receive" id="cps_sms_receive" value="<?php echo $wzcnf['cps_sms_receive'];?>" class="frm_input" style="width:100%;" maxlength="170" />
                </td>
            </tr>
            </tbody>
        </table>
    </div>

    <div class="sim-bx auto">
        <div class="bx-hd" style="width:140px;">
            <p>결제대기 (관리자)&nbsp;&nbsp;<label><input type="checkbox" name="cps_sms1_use_adm" id="cps_sms1_use_adm" value="1" <?php echo $wzcnf['cps_sms1_use_adm'] ? 'checked=checked' : '';?> /> 사용</label></p>
        </div>
        <div class="bx-ft">
            <p class="important">
                <textarea cols="16" rows="6" id="cps_sms1_con_adm" name="cps_sms1_con_adm" wrap="virtual" onkeyup="byte_check('cps_sms1_con_adm', 'byte4', 'byte4_max');" class="sms-con"><?php echo $wzcnf['cps_sms1_con_adm'];?></textarea>
            </p>
            <p class="important">
                <span id="byte4">0</span> / <span id="byte4_max"><?php echo ($config['cf_sms_type'] == 'LMS' ? 90 : 80); ?></span> byte
            </p>
        </div>
    </div>

    <div class="blank_box"></div>

    <div class="sim-bx auto">
        <div class="bx-hd" style="width:140px;">
            <p>결제완료 (관리자)&nbsp;&nbsp;<label><input type="checkbox" name="cps_sms2_use_adm" id="cps_sms2_use_adm" value="1" <?php echo $wzcnf['cps_sms2_use_adm'] ? 'checked=checked' : '';?> /> 사용</label></p>
        </div>
        <div class="bx-ft">
            <p class="important">
                <textarea cols="16" rows="6" id="cps_sms2_con_adm" name="cps_sms2_con_adm" wrap="virtual" onkeyup="byte_check('cps_sms2_con_adm', 'byte5', 'byte5_max');" class="sms-con"><?php echo $wzcnf['cps_sms2_con_adm'];?></textarea>
            </p>
            <p class="important">
                <span id="byte5">0</span> / <span id="byte5_max"><?php echo ($config['cf_sms_type'] == 'LMS' ? 90 : 80); ?></span> byte
            </p>
        </div>
    </div>

    <div class="blank_box"></div>

    <div class="sim-bx auto">
        <div class="bx-hd" style="width:140px;">
            <p>결제취소 (관리자)&nbsp;&nbsp;<label><input type="checkbox" name="cps_sms3_use_adm" id="cps_sms3_use_adm" value="1" <?php echo $wzcnf['cps_sms3_use_adm'] ? 'checked=checked' : '';?> /> 사용</label></p>
        </div>
        <div class="bx-ft">
            <p class="important">
                <textarea cols="16" rows="6" id="cps_sms3_con_adm" name="cps_sms3_con_adm" wrap="virtual" onkeyup="byte_check('cps_sms3_con_adm', 'byte6', 'byte6_max');" class="sms-con"><?php echo $wzcnf['cps_sms3_con_adm'];?></textarea>
            </p>
            <p class="important">
                <span id="byte6">0</span> / <span id="byte6_max"><?php echo ($config['cf_sms_type'] == 'LMS' ? 90 : 80); ?></span> byte
            </p>
        </div>
    </div>

    <hr class="section-division"></hr>

    <div class="helpguide">
        <div class="help_section">
        <table cellspacing="0" border="1" summary="안내 도움말 영역">
        <caption>안내 도움말 영역 표</caption>
        <colgroup>
            <col style="width:88px">
            <col>
            <col style="width:88px">
            <col>
        </colgroup>
        <tbody>
        <tr>
            <th scope="row"><div class="inner"><p class="h_tx h_tx1">안내</p></div></th>
            <td colspan="3">
                <div class="inner">
                    <ul class="faq_lst">
                        <li>문자내용에 아이디를 포함하실 경우 내용에 {아이디} 을 입력해주세요.</li>
                        <li>문자내용에 충전금액을 포함하실 경우 내용에 {결제금액} 을 입력해주세요.</li>
                        <li>문자내용에 입금계좌정보를 포함하실 경우 내용에 {입금계좌정보} 를 입력해주세요.</li>
                        <li>사용에 체크되어 있지 않으면 발송되지 않습니다.</li>
                    </ul>
                </div>
            </td>
        </tr>
        </tbody>
        </table>
        </div>
    </div>

</div>

<div class="btn_fixed_top" style="margin-left: 20px;">
    <input type="submit" value="수정" class="btn_submi btn btn_01" accesskey="s">
</div>

</form>

<script type="text/javascript">
<!--
jQuery(document).ready(function () {
    $(document).on("click", "input[name=cf_is_pay]", function() {
        var cf_is_pay = $(":input:radio[name=cf_is_pay]:checked").val();
        if (cf_is_pay == '0') {
            $('#pay-input-yes').hide();
            $('#pay-input-no').show();
            $('#bx_cf_result_state').show();
        }
        else {
            $('#pay-input-yes').show();
            $('#pay-input-no').hide();
            $('#bx_cf_result_state').hide();
        }
    });
    $(document).on('click', '#wrap-tbl-point .add-tr', function() {
        $('#wrap-tbl-point .empty').remove();
        tbl_tr_add_point();
    });
    $(document).on('click', '#wrap-tbl-point .del-tr', function() {
        var cfp_ix = $(this).attr('data-cfp-ix');
        if (cfp_ix) {
            $('#frm').prepend('<input type="hidden" name="cfp_ix_del[]" value="'+cfp_ix+'">');
        }

        $(this).closest('tr').remove();
        var tr_cnt = $('#wrap-tbl-point tbody tr').length;
        if (tr_cnt == 0) {
            $('#wrap-tbl-point').append('<tr class="empty"><td colspan="3">추가버튼을 클릭하여 포인트정보를 등록해주세요.</td></tr>');
        }
    });
    // 포인트결제방식 선택
    $(document).on('change', ":input:radio[name='cf_point_pay_type']", function() {
        point_pay_type();
    });
    point_pay_type();
});

function point_pay_type() {

    var cf_point_pay_type = $(":input:radio[name=cf_point_pay_type]:checked").val();
    if (cf_point_pay_type == '1') {
        $('#point-custom').hide();
        $('.point-choice').show();
        $('#wrap-tbl-point .add-tr').show();
    }
    else if (cf_point_pay_type == '2') {
        $('#point-custom').show();
        $('.point-choice').show();
        $('#wrap-tbl-point .add-tr').show();
    }
    else {
        $('#point-custom').show();
        $('.point-choice').hide();
        $('#wrap-tbl-point .add-tr').hide();
    }
}

function getAction(f) {

    <?php echo get_editor_js('cf_con_notice'); ?>
    <?php echo get_editor_js('cf_con_refund'); ?>

    if ((f.cps_sms1_use_adm.checked || f.cps_sms2_use_adm.checked || f.cps_sms2_use_adm.checked) && !f.cps_sms_receive.value)
    {
        alert("관리지 수신번호가 입력되어야 합니다.");
        f.cps_sms_receive.focus();
        return false;
    }

    return true;
}

function byte_check(wr_message, sms_bytes, sms_max_bytes)
{
    var conts = document.getElementById(wr_message);
    var bytes = document.getElementById(sms_bytes);
    var max_bytes = document.getElementById(sms_max_bytes);

    var i = 0;
    var cnt = 0;
    var exceed = 0;
    var ch = '';

    for (i=0; i<conts.value.length; i++)
    {
        ch = conts.value.charAt(i);
        if (escape(ch).length > 4) {
            cnt += 2;
        } else {
            cnt += 1;
        }
    }

    bytes.innerHTML = cnt;

    <?php if($config['cf_sms_type'] == 'LMS') { ?>
    if(cnt > 90)
        max_bytes.innerHTML = 1500;
    else
        max_bytes.innerHTML = 90;

    if (cnt > 1500)
    {
        exceed = cnt - 1500;
        alert('메시지 내용은 1500바이트를 넘을수 없습니다.\n\n작성하신 메세지 내용은 '+ exceed +'byte가 초과되었습니다.\n\n초과된 부분은 자동으로 삭제됩니다.');
        var tcnt = 0;
        var xcnt = 0;
        var tmp = conts.value;
        for (i=0; i<tmp.length; i++)
        {
            ch = tmp.charAt(i);
            if (escape(ch).length > 4) {
                tcnt += 2;
            } else {
                tcnt += 1;
            }

            if (tcnt > 1500) {
                tmp = tmp.substring(0,i);
                break;
            } else {
                xcnt = tcnt;
            }
        }
        conts.value = tmp;
        bytes.innerHTML = xcnt;
        return;
    }
    <?php } else { ?>
    if (cnt > 80)
    {
        exceed = cnt - 80;
        alert('메시지 내용은 80바이트를 넘을수 없습니다.\n\n작성하신 메세지 내용은 '+ exceed +'byte가 초과되었습니다.\n\n초과된 부분은 자동으로 삭제됩니다.');
        var tcnt = 0;
        var xcnt = 0;
        var tmp = conts.value;
        for (i=0; i<tmp.length; i++)
        {
            ch = tmp.charAt(i);
            if (escape(ch).length > 4) {
                tcnt += 2;
            } else {
                tcnt += 1;
            }

            if (tcnt > 80) {
                tmp = tmp.substring(0,i);
                break;
            } else {
                xcnt = tcnt;
            }
        }
        conts.value = tmp;
        bytes.innerHTML = xcnt;
        return;
    }
    <?php } ?>
}

function tbl_tr_add_point() {

    var tbl_tr_html = '';
        tbl_tr_html += '<tr class="point-choice">';
        tbl_tr_html += '    <td class="center">';
        tbl_tr_html += '        <input type="hidden" name="cfp_ix[]" value="0" />';
        tbl_tr_html += '        <input type="text" name="cfp_point[]" value="" required class="required frm_input number" maxlength="20" /> <?php echo WPOT_POINT_TEXT;?>';
        tbl_tr_html += '    </td>';
        tbl_tr_html += '    <td class="center">';
        tbl_tr_html += '        <input type="text" name="cfp_price[]" value="" required class="required frm_input number" maxlength="20" /> 원';
        tbl_tr_html += '    </td>';
        tbl_tr_html += '    <td class="center"><a href="#none" class="btn_frmline del-tr">삭제</a></td>';
        tbl_tr_html += '</tr>';

    $('#wrap-tbl-point').append(tbl_tr_html);
}

byte_check('cps_sms1_con_user', 'byte1', 'byte1_max');
byte_check('cps_sms2_con_user', 'byte2', 'byte2_max');
byte_check('cps_sms3_con_user', 'byte3', 'byte3_max');
byte_check('cps_sms1_con_adm', 'byte4', 'byte4_max');
byte_check('cps_sms2_con_adm', 'byte5', 'byte5_max');
byte_check('cps_sms3_con_adm', 'byte6', 'byte6_max');

//-->
</script>


<?php
include_once (G5_ADMIN_PATH.'/admin.tail.php');
?>