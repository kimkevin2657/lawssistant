<?php
if(!defined('_MALLSET_')) exit;

function wz_calculate_room($parm) {


    global $g5, $wzpconfig, $wzdc, $member;
    $arr_room = array();

    wz_verification();

    $rm_date = preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $parm['sch_day']) ? $parm['sch_day'] : '';
    if (!$rm_date) {
        return false;
    }

    $rms_year   = substr($rm_date, 0, 4);
    $rms_month  = substr($rm_date, 5, 2);
    $rms_day    = substr($rm_date, 8);
    $rms_week   = date('w', strtotime($rm_date));

    // 예약가능최대일에 포함되는지.
    $expire_date = G5_TIME_YMD;
    if ($wzpconfig['pn_max_booking_expire']) {
        $expire_date = wz_get_addday(G5_TIME_YMD, $wzpconfig['pn_max_booking_expire']);
    }
    if ($rm_date > $expire_date) { // 예약가능 최대일을 넘긴경우 리턴처리.
        return false;
    }

    // 예약차단일에 해당되는지.
    $cp_term_day = '';
    if ($wzdc['cp_term_day']) {
        $cp_term_day = wz_get_addday(G5_TIME_YMD, $wzdc['cp_term_day']);
        if ($rm_date < $cp_term_day) {
            return false;
        }
    }

    $cnt_chk = 0;
    if (is_array($parm['rm_ix'])) {
   
        foreach ($parm['rm_ix'] as $k => $rm_ix) {

            $rmix    = (int)$rm_ix; // 객실키
            $rm_time = $parm['rm_time'][$k];
            $rm_cnt  = (int)$parm['rm_cnt'][$k];

            if ($rmix) {

                $query = "select * from {$g5['wzb_room_table']} where rm_ix = '$rmix' and rm_use = 1 ";
                $rm = sql_fetch($query);

                if ($member['grade'] < $rm['rm_level']) { // 권한확인
                    continue;
                }

                if (!$rm['rm_holiday_use']) { // 공휴일 예약허용이 아닐경우 해당일이 공휴일인지 확인
                    $query = "select
                                    hd_ix
                                from {$g5['wzb_holiday_table']}
                                where (cp_ix = '".$wzdc['cp_ix']."' or cp_ix = 0)
                                and (hd_date = '".$rm_date."' or (hd_loop_year = 1 and hd_month = '".$rms_month."' and hd_day = '".$rms_day."')) ";
                    $hd = sql_fetch($query);
                    if ($hd['hd_ix']) {
                        continue;
                    }
                }

                if (!$rm['rm_week'.$rms_week]) { // 예약가능한 요일이 아닐경우
                    continue;
                }

                $query = " select rmc_ix from {$g5['wzb_room_close_table']} where rm_ix = '".$rm_ix."' and rmc_date = '".$rm_date."' "; // 시설차단정보
                $rmc = sql_fetch($query);
                if ($rmc['rmc_ix']) {
                    continue;
                }

                if ($wzpconfig['pn_is_pay']) { // 결제기능을 사용할경우 요금계산

                    $prc = wz_calculate_price($rm_ix, $rm_date, $rm_time);
                    $rm['rmt_price']      = $prc['price'];
                    $rm['rmt_price_type'] = $prc['price_type'];
                }

                // 예약가능한 수량 체크
                $rmt_max_cnt = wz_check_time_room($rmix, $rm_date, $rm_time);
                $rm['rm_person_max'] = $rmt_max_cnt;
                $rm['rm_time']       = $rm_time;
                $rm['rm_person_cnt'] = $rm_cnt;
                $arr_room[]          = $rm;
            }
        }
    }

    return $arr_room;
}

// 선택한 옵션요금계산
function wz_calculate_option($parm) {

    global $g5;
    $arr_option = array();
    $cnt_opt = 0;
    if (is_array($parm['opt'])) {
        $cnt_opt = count($parm['opt']);
        for ($z = 0; $z < $cnt_opt; $z++) {
            $rmoix  = (int)$parm['rmo_ix'][$parm['opt'][$z]]; // 옵션키
            $rmocnt = (int)$parm['rmo_cnt'][$parm['opt'][$z]]; // 옵션선택수량

            if ($rmoix && $rmocnt) {
                $query = "select rmo_ix, rmo_name, rmo_memo, rmo_unit, rmo_price from {$g5['wzb_room_option_table']} where rmo_ix = '$rmoix' ";
                $rmo = sql_fetch($query);
                $rmo['cnt']     = $rmocnt;
                $rmo['price']   = $rmo['rmo_price'] * $rmocnt;
                $arr_option[]   = $rmo;
            }
        }
    }

    return $arr_option;
}

function wz_verification() {

    $url = 'http://www.wetoz.kr/connect/inicheck.php';

    $posts = array();
    $posts['ps_domain'] = 'http://blingbeauty.shop';
    $posts['ps_pcode']  = 'wzyeyakuproc';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_VERBOSE, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $posts);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($curl, CURLOPT_TIMEOUT, 200);
    $result = curl_exec($curl);
    curl_close($curl);

    if (substr($result, 0, 5) == 'error') {
        die(substr($result, 6));
    }
}

/* eval(unserialize(gzinflate(base64_decode('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')))); */