<?php
if(!defined('_MALLSET_')) exit;

//class wz_sms extends SMS5 {
class wz_sms {

    private $od = array();

    function __construct($bk_ix) {
        
        global $g5;

        $bk_ix = (int)$bk_ix;
        $query = "select bk_ix, bk_status, bk_name, bk_reserv_price, bk_bank_account, bk_hp, bk_subject from {$g5['wzb_booking_table']} where bk_ix = '{$bk_ix}' ";
        $this->od = sql_fetch($query);

    }

    function wz_send() { 
        
        global $config, $wzpconfig, $g5, $sms5;
        $send_cnt = 0;

        if (!$config['cf_sms_use']) { 
            return false;
        } 

        // 2017-09-01 : 아이코드 아이디패스워드가 정상적으로 등록이 된것인지 확인
        $userinfo = get_icode_userinfo($config['cf_icode_id'], $config['cf_icode_pw']);
        if ($userinfo['code'] != '0') { 
            return false;
        } 

        $send_number = str_replace('-', '', trim($sms5['cf_phone'])); // 발송번호(발신번호 등록되어 있어야함)
        
        $room_list = '';
        if ($config['cf_sms_type'] == 'LMS') {
            $arr_room_list = array();
            $result = sql_query("select * from {$g5['wzb_booking_room_table']} where bk_ix = '".$this->od['bk_ix']."'");
            while ($row = sql_fetch_array($result)) { 
                $arr_room_list[] = $row['bkr_subject'].' '.wz_get_hangul_date_md($row['bkr_date']).'('.get_yoil($row['bkr_date']).') '.wz_get_hangul_time_hm($row['bkr_time']).' '. $row['bkr_cnt'].'명';
            }
            $room_list = $this->od['bk_name']."님의 연락처:".$this->od['bk_hp']." \n\n";
            $room_list .= implode("\n\n", $arr_room_list);
        }

        $is_user = $is_adm = false;
        $lms_subject_user = $lms_subject_adm = '';
        $sms_content_user = $sms_content_adm = '';
        
        switch ($this->od['bk_status']) {
            case '대기':
                if ($wzpconfig['cps_sms1_use_user']) { 
                    $lms_subject_user = '{예약자명}님의 예약신청정보 입니다.';
                    $sms_content_user = $wzpconfig['cps_sms1_con_user'];    
                    $is_user = true;
                }
                if ($wzpconfig['cps_sms1_use_adm']) { 
                    $lms_subject_adm  = '{예약자명}님의 예약신청정보 입니다.';
                    $sms_content_adm  = $wzpconfig['cps_sms1_con_adm'];    
                    $is_adm = true;
                }
            break;
            case '완료':
                if ($wzpconfig['cps_sms2_use_user']) { 
                    $lms_subject_user = '{예약자명}님의 예약정보 입니다.';
                    $sms_content_user = $wzpconfig['cps_sms2_con_user'];    
                    $is_user = true;
                }
                if ($wzpconfig['cps_sms2_use_adm']) { 
                    $lms_subject_adm  = '{예약자명}님의 예약정보 입니다.';
                    $sms_content_adm  = $wzpconfig['cps_sms2_con_adm'];
                    $is_adm = true;
                }
            break;
            case '취소':
                if ($wzpconfig['cps_sms3_use_user']) { 
                    $lms_subject_user = '{예약자명}님의 예약취소정보 입니다.';
                    $sms_content_user = $wzpconfig['cps_sms3_con_user'];
                    $is_user = true;
                }
                if ($wzpconfig['cps_sms3_use_adm']) { 
                    $lms_subject_adm  = '{예약자명}님의 예약취소정보 입니다.';
                    $sms_content_adm = $wzpconfig['cps_sms3_con_adm'];
                    $is_adm = true;
                }
            break;
        }

        if ($config['cf_sms_type'] == 'LMS')
            $port_setting = get_icode_port_type($config['cf_icode_id'], $config['cf_icode_pw']);
        else
            $port_setting = $config['cf_icode_server_port'];

        if (!$port_setting)
            $is_user = $is_adm = false;

        $this->SMS_con($config['cf_icode_server_ip'], $config['cf_icode_id'], $config['cf_icode_pw'], $port_setting);

        // 예약자

        // 전화번호가 정상인지 확인
        $recv_number_user = get_hp($this->od['bk_hp'],0);
        if (!$recv_number_user) { 
            $is_user = false;
        }

        if ($is_user) { 
            
            $sms_content_user = str_replace('{예약자명}', $this->od['bk_name'], $sms_content_user);
            $sms_content_user = str_replace('{예약금}', number_format($this->od['bk_reserv_price']), $sms_content_user);
            $recv_number    = $recv_number_user; // 예약자 수신
            $sms_content    = $sms_content_user;
            $sms_content      = str_replace('{예약정보}', $room_list, $sms_content);
           
            if ($config['cf_sms_type'] == 'LMS') {
                
                $lms_subject_user = str_replace('{예약자명}', $this->od['bk_name'], $lms_subject_user); // LMS 제목

                unset($strDest);
                $strDest     = array();
                $strDest[]   = $recv_number;
                $strCallBack = $send_number;
                $strCaller   = trim($config['cf_title']);
                $strSubject  = $lms_subject_user;
                $strURL      = '';
                $strData     = $sms_content;
                $strDate     = '';
                $nCount      = 1;

                $this->Add($strDest, $strCallBack, $strCaller, $strSubject, $strURL, $strData, $strDate, $nCount);
                $this->send();
                $this->Init();
            }
            else {
                $sms_content = iconv_euckr($sms_content);
                $this->Add($recv_number, $send_number, '', $sms_content, '');  
            }

            $send_cnt++;
        }
       
        // 관리자
        
        $arr_number = array();
        $recv_number_adm = $wzpconfig['cps_sms_receive'];
        if (!$recv_number_adm) { 
            $is_adm = false;
        } 
        else {
            $arr_number_adm = explode(',', $recv_number_adm);
            foreach ($arr_number_adm as $key => $value) {
                $arr_number_adm[$key] = get_hp($value, 0);
            }
        }

        if ($is_adm) { 
            
            $sms_content_adm = str_replace('{예약자명}', $this->od['bk_name'], $sms_content_adm);
            $sms_content_adm = str_replace('{예약금}', number_format($this->od['bk_reserv_price']), $sms_content_adm);
            $sms_content    = $sms_content_adm;
            $sms_content     = str_replace('{예약정보}', $room_list, $sms_content);

            if ($config['cf_sms_type'] == 'LMS') {
                
                $lms_subject_adm = str_replace('{예약자명}', $this->od['bk_name'], $lms_subject_adm); // LMS 제목

                unset($strDest);
                $strDest     = array();
                $strDest     = $arr_number_adm;
                $strCallBack = $send_number;
                $strCaller   = trim($config['cf_title']);
                $strSubject  = $lms_subject_adm;
                $strURL      = '';
                $strData     = $sms_content;
                $strDate     = '';
                $nCount      = count($arr_number_adm);
                
                $this->Add($strDest, $strCallBack, $strCaller, $strSubject, $strURL, $strData, $strDate, $nCount);
                $this->send();
                $this->Init();
            }
            else {
                $sms_content = iconv_euckr($sms_content);

                foreach ($arr_number_adm as $key => $value) {
                    $this->Add($value, $send_number, '', $sms_content, '');  
                }
            }

            $send_cnt++;
        }

        if ($config['cf_sms_type'] != 'LMS') {
            $this->send();
            $this->Init();
        }
    } 
}