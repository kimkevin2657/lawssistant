<?php
if(!defined('_MALLSET_')) exit;

class wz_mail {

    private $od = array();

    function __construct($bk_ix) {
        
        global $g5;

        $bk_ix = (int)$bk_ix;
        $query = "select * from {$g5['wzb_booking_table']} where bk_ix = '{$bk_ix}' ";
        $this->od = sql_fetch($query);

    }

    function wz_send() { 
        
        global $config, $g5, $wzpconfig;
        $send_cnt = 0;

        if (!$this->od['bk_email']) 
            return false;

        // 객실예약정보
        unset($arr_room);
        $arr_room = array();
        $query = "select * from {$g5['wzb_booking_room_table']} where bk_ix = '".$this->od['bk_ix']."' order by bkr_ix asc ";
        $res = sql_query($query);
        while($row = sql_fetch_array($res)) { 
            $arr_room[] = $row;
        }
        $cnt_room = count($arr_room);
        if ($res) sql_free_result($res);

        // 옵션선택정보
        unset($arr_option);
        $arr_option = array();
        $query = "select * from {$g5['wzb_booking_option_table']} where bk_ix = '".$this->od['bk_ix']."' order by odo_ix asc ";
        $res = sql_query($query);
        while($row = sql_fetch_array($res)) { 
            $arr_option[] = $row;
        }
        $cnt_option = count($arr_option);
        if ($res) sql_free_result($res);
        
        $subject = $content = '';
        
        switch ($this->od['bk_status']) {
            case '대기':
                $subject = '{예약자명}님의 예약신청정보 입니다.';
            break;
            case '완료':
                $subject = '{예약자명}님의 예약완료정보 입니다.';
            break;
            case '취소':
                $subject = '{예약자명}님의 예약취소정보 입니다.';
            break;
        }

        $subject         = str_replace('{예약자명}', $this->od['bk_name'], $subject);
        $od_id           = $this->od['od_id'];
        $bk_reserv_price = $this->od['bk_reserv_price'];
        $bk_price        = $this->od['bk_price'];
        $bk_misu         = $this->od['bk_misu'];
        $bk_status       = $this->od['bk_status'];
        $bk_wating       = date("Y년m월d일 H시", strtotime($this->od['bk_time']." + ".$wzpconfig['pn_wating_time']." hours"));

        ob_start();
        include_once(G5_PLUGIN_PATH.'/wz.bookingC.prm/mail/send_mail1.inc.php');
        $content = ob_get_contents();
        ob_end_clean();

        $content = str_replace('{예약자명}', $this->od['bk_name'], $content);
        $content = str_replace('{핸드폰}', $this->od['bk_hp'], $content);
        $content = str_replace('{이메일주소}', $this->od['bk_email'], $content);
        $content = str_replace('{요청사항}', $this->od['bk_memo'], $content);
        $content = str_replace('{환불규정}', $wzpconfig['pn_con_refund'], $content);

        $result = mailer($config['cf_title'], $config['cf_admin_email'], $this->od['bk_email'], $subject, $content, 1);
        $result = mailer($config['cf_title'], $config['cf_admin_email'], $config['cf_admin_email'], $subject, $content, 1);
        
        return $result;
    }
}