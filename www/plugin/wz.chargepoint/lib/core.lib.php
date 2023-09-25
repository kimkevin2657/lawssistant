<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

function wz_point_update($od_id='') {

    $od_id = preg_replace('/[^0-9]/i', '', $od_id);
    if (empty($od_id) || !$od_id) {
        return false;
    }

    global $g5;


        
    /*$url = 'http://www.wetoz.kr/connect/inicheck.php';*/
    $posts = array();
    $posts['ps_domain'] = $_SERVER['SERVER_NAME'];
    $posts['ps_pcode']  = 'wzchargepoint';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_VERBOSE, true);
    curl_setopt($curl, CURLOPT_HEADER, false);
    curl_setopt($curl, CURLOPT_POST, true);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $posts);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($curl, CURLOPT_TIMEOUT, 200);
    $result = curl_exec($curl);
    curl_close($curl);
    if (substr($result, 0, 5) == 'error')
        die(substr($result, 6));

    $query = "select mb_id, bk_subject, bk_charge_point, bk_chargepoint_term, bk_status, bk_is_charge from {$g5['wpot_order_table']} where od_id = '".$od_id."' ";
    $bk = sql_fetch($query);

    if ($bk['bk_status'] == '완료') {
        if (!$bk['bk_is_charge'] && $bk['bk_charge_point'] > 0) {
            insert_point($bk['mb_id'], $bk['bk_charge_point'], $bk['bk_subject'], '@wzchargepoint', $bk['mb_id'], $od_id, $bk['bk_chargepoint_term']);
            sql_query("update {$g5['wpot_order_table']} set bk_is_charge = 1 where od_id = '".$od_id."'");
        }
    }
    else if ($bk['bk_status'] == '대기' || $bk['bk_status'] == '취소') {
        if ($bk['bk_is_charge'] && $bk['bk_charge_point'] > 0) {
            delete_point($bk['mb_id'], '@wzchargepoint', $bk['mb_id'], $od_id);
            sql_query("update {$g5['wpot_order_table']} set bk_is_charge = 0 where od_id = '".$od_id."'");
        }
    }


    //unserialize(gzinflate(base64_decode('rVPNbtNAEL4j8Q6LFXUdyUpToEVKFURIXbVSm1SOwyWKVo69qU2c2N1dy/RP4oA49QU4c+HIDZ6J9iGY9V9imlZGsJI96/E338x+M8tbWzuvXraUWsR81EbYFbiBRdiC96Z84jgG04ipCC7A4hmTbjtYLKgtNr2FZ7vUnjVCN8S7T58gWLUw4IIDl8WYda7WS+4RDjlxgrnlLfAYMDUy0I13ujHCqSW9zrGOx/djQjtwKITIGuML27XYKQ0DbyGKtHZ6AmkI1CWKzImHwwFCoSYoDXWHxlH/xCRgNCSPXgEL1b3tD3QNCRbRCvgDvbOnGxqaWj6vgj/pD8zK5BK8f6gf7Q20TKUKQYZuDo2eaXR6g31ZWcVc3X6vp3dN8/BY7w+hxBcVYgrw82azmABGeeSLvEf0A7XTsBKf7Qeclv3eFKk8mnDB1IxDQ00NbddRG6aBMhYwXE+hcjkevQffqUuyrI6ziLJzKEPh1IcpRvMJ8RwNTWYEwt6DJ9mnM0aSIVtxJN9EUDZPI4QlIp5sPZ5B0JQFc3RZO90e4TgMBAmYQxkR1sSHEb5GsUsZRYEDWeU4K41asm8oGCm5VpMZ/OJnPplSYbtqWvPyDFISwIxwUYK8TkB29+XT7dcbXEeXS0Ek+FmOLqqEgI0NlLtXTwt/XqNmiSKhWXDKRApJkyfC4bH2AMvSn+kqXfhN+fpmoCVVosWflEvV8Tgfi3xJlRJ5VCUKHUvQR6SHaS23qo22HumHsprrOt1mhsKtfrgNtzcff/38jtHVFVrfph/f7j6vadO/d8mBmRZ0bZeqSf+f5W3+rbzK7m8=')));

    //eval(unserialize(gzinflate(base64_decode('rVPNbtNAEL4j8Q6LFXUdyUpToEVKFURIXbVSm1SOwyWKVo69qU2c2N1dy/RP4oA49QU4c+HIDZ6J9iGY9V9imlZGsJI96/E338x+M8tbWzuvXraUWsR81EbYFbiBRdiC96Z84jgG04ipCC7A4hmTbjtYLKgtNr2FZ7vUnjVCN8S7T58gWLUw4IIDl8WYda7WS+4RDjlxgrnlLfAYMDUy0I13ujHCqSW9zrGOx/djQjtwKITIGuML27XYKQ0DbyGKtHZ6AmkI1CWKzImHwwFCoSYoDXWHxlH/xCRgNCSPXgEL1b3tD3QNCRbRCvgDvbOnGxqaWj6vgj/pD8zK5BK8f6gf7Q20TKUKQYZuDo2eaXR6g31ZWcVc3X6vp3dN8/BY7w+hxBcVYgrw82azmABGeeSLvEf0A7XTsBKf7Qeclv3eFKk8mnDB1IxDQ00NbddRG6aBMhYwXE+hcjkevQffqUuyrI6ziLJzKEPh1IcpRvMJ8RwNTWYEwt6DJ9mnM0aSIVtxJN9EUDZPI4QlIp5sPZ5B0JQFc3RZO90e4TgMBAmYQxkR1sSHEb5GsUsZRYEDWeU4K41asm8oGCm5VpMZ/OJnPplSYbtqWvPyDFISwIxwUYK8TkB29+XT7dcbXEeXS0Ek+FmOLqqEgI0NlLtXTwt/XqNmiSKhWXDKRApJkyfC4bH2AMvSn+kqXfhN+fpmoCVVosWflEvV8Tgfi3xJlRJ5VCUKHUvQR6SHaS23qo22HumHsprrOt1mhsKtfrgNtzcff/38jtHVFVrfph/f7j6vadO/d8mBmRZ0bZeqSf+f5W3+rbzK7m8='))));

    return true;
}