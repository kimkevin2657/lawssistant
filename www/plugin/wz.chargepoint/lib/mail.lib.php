<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

class wz_mail {

    private $od = array();

    function __construct($od_id) {

        global $g5;

        $od_id = preg_replace('/[^0-9]/i', '', $od_id);
        $query = "select * from {$g5['wpot_order_table']} where od_id = '".$od_id."' ";
        $this->od = sql_fetch($query);

    }

    function wz_send() {

        global $config, $g5, $wzcnf;
        $send_cnt = 0;

        if (!$this->od['bk_email'])
            return false;

        $subject = $content = '';

        switch ($this->od['bk_status']) {
            case '대기':
                $subject = '{아이디}님의 충전신청정보 입니다.';
            break;
            case '완료':
                $subject = '{아이디}님의 충전완료정보 입니다.';
            break;
            case '취소':
                $subject = '{아이디}님의 충전취소정보 입니다.';
            break;
        }

        $subject         = str_replace('{아이디}', $this->od['mb_id'], $subject);
        $od_id           = $this->od['od_id'];
        $bk_price        = $this->od['bk_price'];
        $bk_status       = $this->od['bk_status'];

        ob_start();
        include_once(WPOT_PLUGIN_PATH.'/mail/send_mail1.inc.php');
        $content = ob_get_contents();
        ob_end_clean();

        $content = str_replace('{결제방법}', $this->od['bk_payment'], $content);
        $content = str_replace('{충전포인트}', number_format($this->od['bk_charge_point']).' '.WPOT_POINT_TEXT, $content);
        $content = str_replace('{결제금액}', number_format($this->od['bk_price']).' 원', $content);
        $content = str_replace('{결제상태}', $this->od['bk_status'], $content);
        $content = str_replace('{아이디}', $this->od['mb_id'], $content);
        $content = str_replace('{핸드폰}', $this->od['bk_hp'], $content);
        $content = str_replace('{이메일주소}', $this->od['bk_email'], $content);
        $content = str_replace('{환불규정}', $wzcnf['cf_con_refund'], $content);

        $result = mailer($config['cf_title'], $config['cf_admin_email'], $this->od['bk_email'], $subject, $content, 1);
        $result = mailer($config['cf_title'], $config['cf_admin_email'], $config['cf_admin_email'], $subject, $content, 1);

        return $result;
    }
}