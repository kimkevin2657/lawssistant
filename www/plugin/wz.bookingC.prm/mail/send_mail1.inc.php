<!doctype html>
<html lang="ko">
<head>
<meta charset="utf-8">
<title><?php echo $subject; ?></title>
</head>

<?php
$cont_st_head = 'margin:0 auto 16px;width:94%;border:0;border-collapse:collapse;';
$cont_st = 'margin:0 auto 7px;width:94%;border:0;border-collapse:collapse;';
$caption_st = 'padding:0 0 5px;font-weight:bold';
$th_st = 'padding:5px;border-top:1px solid #e9e9e9;border-bottom:1px solid #e9e9e9;background:#f5f6fa;text-align:left';
$td_st = 'padding:5px;border-top:1px solid #e9e9e9;border-bottom:1px solid #e9e9e9;text-align:center;';
$empty_st = 'padding:30px;border-top:1px solid #e9e9e9;border-bottom:1px solid #e9e9e9;text-align:center';
$ft_a_st = 'display:block;padding:30px 0;background:#484848;color:#fff;text-align:center;text-decoration:none';
$td_head = 'padding:5px;font-size:14px;font-family:dotum,"돋움",Helvetica;color:#828282;line-height:23px;vertical-align:top;border-right:1px solid #d5d5d5;';
$td_con = 'padding:5px;font-size:14px;font-family:dotum,"돋움",Helvetica;color:#424240;line-height:23px;text-align:left;vertical-align:top';


$css_table = 'margin:0 auto 20px;width:94%;padding:0;clear:both;border-collapse:collapse;border-spacing:0;';
$css_th = 'vertical-align:middle;padding:7px 0;border:1px solid #d5d5d5;color:#666;text-align:center;';
$css_td = 'vertical-align:middle;padding:7px;border:1px solid #d5d5d5;color:#4c4c4c;';
$css_help_wrap = 'margin:0 auto 20px;width:94%;border:1px solid #dfdfdf;';
$css_help = 'padding:15px;';
?>

<body>

<div style="margin:30px auto;width:600px;border:10px solid #f7f7f7">
    <div style="border:1px solid #dedede">
        <h1 style="margin:0 0 20px;padding:30px 30px 20px;background:#f7f7f7;color:#555;font-size:1.4em">
            <?php echo $config['cf_title'];?>
        </h1>

        <p style="text-align:center;padding:10px 10px 40px;font-size:1.2em">
            <?php echo $subject;?>
        </p>

        <div style="<?php echo $cont_st_head; ?>">
            <strong>예약번호 <?php echo $od_id; ?></strong><br>
            본 메일은 <?php echo G5_TIME_YMDHIS; ?> (<?php echo get_yoil(G5_TIME_YMDHIS); ?>)을 기준으로 작성되었습니다.
        </div>
        
        <div style="<?php echo $cont_st; ?>">- 예약자정보</div>

        <table cellpadding="0" cellspacing="0" style="margin:0 auto 20px;width:94%;padding:0;"> 
        <colgroup>
            <col width="140px;"/>
            <col/>
        </colgroup>
        <tbody>
        <tr><td height="2" style="background:#424240" colspan="2"></td></tr>
        <tr>
            <td style="<?php echo $td_head;?>">
                예약자명
            </td>
            <td style="<?php echo $td_con;?>">
                {예약자명}						
            </td>
        </tr>
        <tr><td height="1" style="background:#d5d5d5;" colspan="2"></td></tr>
        <tr>
            <td style="<?php echo $td_head;?>">
                핸드폰
            </td>
            <td style="<?php echo $td_con;?>">
                {핸드폰}
            </td>
        </tr>	
        <tr><td height="1" style="background:#d5d5d5;" colspan="2"></td></tr>
        <tr>
            <td style="<?php echo $td_head;?>">
                이메일주소
            </td>
            <td style="<?php echo $td_con;?>">
                {이메일주소}
            </td>
        </tr>	
        <tr><td height="1" style="background:#d5d5d5;" colspan="2"></td></tr>
        <tr>
            <td style="<?php echo $td_head;?>">
                요청사항
            </td>
            <td style="<?php echo $td_con;?>">
                {요청사항}
            </td>
        </tr>	
        <tr><td height="1" style="background:#d5d5d5;" colspan="2"></td></tr>
        </tbody>
        </table>

        <p style="<?php echo $cont_st; ?>">- 이용서비스정보</p>

        <?php 
        $total_price = $total_room = $total_person = 0;
        if ($cnt_room > 0) { 
            for ($z = 0; $z < $cnt_room; $z++) { 
            ?>

            <table cellpadding="0" cellspacing="0" style="<?php echo $css_table;?>"> 
            <colgroup>
                <col width="140px;"/>
                <col/>
                <col width="140px;"/>
                <col/>
            </colgroup>
            <tbody>
            <tr><td height="2" style="background:#424240" colspan="4"></td></tr>
            <tr>
                <td style="<?php echo $css_th;?>">
                    예약서비스
                </td>
                <td style="<?php echo $css_td;?>" colspan="3">
                    <?php echo $arr_room[$z]['bkr_subject'];?>				
                </td>
            </tr>
            <tr>
                <td style="<?php echo $css_th;?>">
                    이용일자/시간
                </td>
                <td style="<?php echo $css_td;?>">
                    <?php echo wz_get_hangul_date_md($arr_room[$z]['bkr_date']).'('.get_yoil($arr_room[$z]['bkr_date']).') / '.wz_get_hangul_time_hm($arr_room[$z]['bkr_time']);?>
                </td>
                <td style="<?php echo $css_th;?>">
                    예약인원
                </td>
                <td style="<?php echo $css_td;?>">
                    <?php echo $arr_room[$z]['bkr_cnt'];?>
                </td>
            </tr>
            <?php if ($wzpconfig['pn_is_pay']) {?>
            <tr>
                <td style="<?php echo $css_th;?>">
                    합계
                </td>
                <td style="<?php echo $css_td;?>" colspan="3">
                    <?php echo number_format($arr_room[$z]['bkr_price']);?> 원
                </td>
            </tr>	
            <?php } ?>
            </tbody>
            </table>

            <?php 
            $total_room     += $arr_room[$z]['bkr_price'];
            $total_person   += $arr_room[$z]['bkr_price_person'];
            }
        } 
        ?>

        <?php if ($cnt_option > 0) { ?>
        <div style="height:10px;"></div>
        <p style="<?php echo $cont_st; ?>">- 옵션예약정보</p>
        
        <table cellpadding="0" cellspacing="0" style="<?php echo $css_table;?>"> 
            <colgroup>
                <col>
                <col width="108px;">
                <?php if ($wzpconfig['pn_is_pay']) {?><col width="100px;"><?php } ?>
            </colgroup>
            <thead>
            <tr><td height="2" style="background:#424240" colspan="3"></td></tr>
            <tr>
                <th scope="col" style="<?php echo $css_th;?>">옵션명</th>
                <th scope="col" style="<?php echo $css_th;?>">수량</th>
                <?php if ($wzpconfig['pn_is_pay']) {?><th scope="col" style="<?php echo $css_th;?>">금액</th><?php } ?>
            </tr>
            </thead>
            <tbody>
            <?php 
            $total_option = 0;
            for ($z = 0; $z < $cnt_option; $z++) { 
                ?>
                <tr>
                    <td style="<?php echo $css_td; ?>"><?php echo $arr_option[$z]['odo_name'].($arr_option[$z]['odo_memo'] ? ' ('.$arr_option[$z]['odo_memo'].')' : '');?></td>
                    <td style="<?php echo $css_td; ?>"><?php echo $arr_option[$z]['odo_cnt'].$arr_option[$z]['odo_unit']?></td>
                    <?php if ($wzpconfig['pn_is_pay']) {?><td style="<?php echo $css_td; ?>"><?php echo number_format($arr_option[$z]['odo_price']);?></td><?php } ?>
                </tr>
                <?php 
                $total_option    += $arr_option[$z]['odo_price'];
            } 
            ?>
            </tbody>
        </table>
        <?php } ?>
        
        <?php if ($wzpconfig['pn_is_pay']) {?>
        <div style="height:10px;"></div>
        <p style="<?php echo $cont_st; ?>">- 결제정보</p>
        <table cellpadding="0" cellspacing="0" style="<?php echo $css_table;?>"> 
            <colgroup>
                <col width="33%"/>
                <col width="33%"/>
                <col width="34%"/>
            </colgroup>
            <thead>
            <tr><td height="2" style="background:#424240" colspan="3"></td></tr>
            <tr>
                <th scope="col" style="<?php echo $css_th;?>">예약금</th>
                <th scope="col" style="<?php echo $css_th;?>">잔금</th>
                <th scope="col" style="<?php echo $css_th;?>">총이용금액</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="<?php echo $css_td; ?>"><?php echo number_format($bk_reserv_price);?> 원 (<?php echo ($bk_reserv_price <= ($bk_price - $bk_misu) ? '결제완료' : '미결제');?>)</td>
                <td style="<?php echo $css_td; ?>"><?php echo number_format($bk_price - $bk_reserv_price);?> 원 (<?php echo ($bk_misu ? '미결제' : '결제완료');?>)</td>
                <td style="<?php echo $css_td; ?>"><strong><?php echo number_format($bk_price);?> 원 (<?php echo ($bk_misu ? '미결제' : '결제완료');?>)</strong></td>
            </tr>
            </tbody>
        </table>
        <?php } ?>
        
        <div style="height:10px;"></div>

        <?php if ($wzpconfig['pn_con_refund'] && $wzpconfig['pn_is_pay']) { ?>
        <p style="<?php echo $cont_st; ?>">- 환불규정</p>
        <div style="<?php echo $css_help_wrap;?>">
            <div style="<?php echo $css_help;?>">
            {환불규정}
            </div>
        </div>
        <?php } ?>

        <?php if ($bk_status == '대기' && $wzpconfig['pn_con_refund'] && $wzpconfig['pn_is_pay']) {?>
        <div style="<?php echo $css_help_wrap;?>">
            <div style="<?php echo $css_help;?>">
            입금기한은 <?php echo $bk_wating;?> 까지 입니다.<br />
            예약접수에 감사드립니다.
            </div>
        </div>
        <?php } ?>

        <p style="<?php echo $cont_st; ?>"> 본 메일은 발신전용 메일입니다. 문의가 있으시면 고객센터로 문의주세요.</p>
        <a href="<?php echo G5_URL; ?>" target="_blank" style="<?php echo $ft_a_st; ?>">예약상세내역 확인</a>

    </div>
</div>

</body>
</html>
