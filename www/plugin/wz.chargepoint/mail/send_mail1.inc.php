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
            <strong>결제번호 <?php echo $od_id; ?></strong><br>
            본 메일은 <?php echo G5_TIME_YMDHIS; ?> (<?php echo get_yoil(G5_TIME_YMDHIS); ?>)을 기준으로 작성되었습니다.
        </div>

        <div style="<?php echo $cont_st; ?>">- 결제자정보</div>

        <table cellpadding="0" cellspacing="0" style="margin:0 auto 20px;width:94%;padding:0;">
        <colgroup>
            <col width="140px;"/>
            <col/>
        </colgroup>
        <tbody>
        <tr><td height="2" style="background:#424240" colspan="2"></td></tr>
        <tr>
            <td style="<?php echo $td_head;?>">
                결제방법
            </td>
            <td style="<?php echo $td_con;?>">
                {결제방법}
            </td>
        </tr>
        <tr><td height="1" style="background:#d5d5d5;" colspan="2"></td></tr>
        <tr>
            <td style="<?php echo $td_head;?>">
                충전포인트
            </td>
            <td style="<?php echo $td_con;?>">
                {충전포인트}
            </td>
        </tr>
        <tr><td height="1" style="background:#d5d5d5;" colspan="2"></td></tr>
        <tr>
            <td style="<?php echo $td_head;?>">
                결제금액
            </td>
            <td style="<?php echo $td_con;?>">
                {결제금액}
            </td>
        </tr>
        <tr><td height="1" style="background:#d5d5d5;" colspan="2"></td></tr>
        <tr>
            <td style="<?php echo $td_head;?>">
                결제상태
            </td>
            <td style="<?php echo $td_con;?>">
                {결제상태}
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
        </tbody>
        </table>

        <div style="height:10px;"></div>

        <?php if ($wzcnf['cf_con_refund']) { ?>
        <p style="<?php echo $cont_st; ?>">- 환불규정</p>
        <div style="<?php echo $css_help_wrap;?>">
            <div style="<?php echo $css_help;?>">
            {환불규정}
            </div>
        </div>
        <?php } ?>

        <p style="<?php echo $cont_st; ?>"> 본 메일은 발신전용 메일입니다. 문의가 있으시면 고객센터로 문의주세요.</p>
        <a href="<?php echo G5_URL; ?>" target="_blank" style="<?php echo $ft_a_st; ?>">충전상세내역 확인</a>

    </div>
</div>

</body>
</html>
