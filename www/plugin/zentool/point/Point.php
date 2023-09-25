<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2019-02-22
 * Time: 17:25
 */

class Point
{
    const API_TABLE = "@api";

    public static function get_point($mb_id)
    {
        $point = get_point_sum($mb_id);
        return [ApiResponse::KEY_RSLT_CODE => ApiResponse::SUCCESS, 'point' => $point];
    }

    public static function add_point($mb_id, $point, $content, $rel_table = '@api', $rel_action = '@client_id')
    {
        if( ! is_numeric($point) ) {
            return [ApiResponse::KEY_RSLT_CODE=>ApiResponse::INVALID_NUMBER, ApiResponse::KEY_RSLT_MESSAGE=>'Point 는 숫자여야 합니다.'];
        }
        $point = (int) $point;
        $tran_id = get_uniqid();
        insert_point($mb_id, $point, $content, $rel_table, $tran_id, $rel_action);
        $point = get_point_sum($mb_id);
        return [ApiResponse::KEY_RSLT_CODE=>ApiResponse::SUCCESS, 'tran_id'=>$tran_id, 'point'=>$point];
    }

    public static function use_point($mb_id, $point, $content, $rel_table = '@api', $rel_action = '@client_id')
    {
        if( ! is_numeric($point) ) {
            return [ApiResponse::KEY_RSLT_CODE=>ApiResponse::INVALID_NUMBER, ApiResponse::KEY_RSLT_MESSAGE=>'Point 는 숫자여야 합니다.'];
        }
        $point = (int) $point;
        $tran_id = get_uniqid();
        insert_point($mb_id, $point, $content, $rel_table, $tran_id, $rel_action);
        $point = get_point_sum($mb_id);
        return [ApiResponse::KEY_RSLT_CODE=>ApiResponse::SUCCESS, 'tran_id'=>$tran_id, 'point' => $point];
    }

    public static function rollback($mb_id, $rel_id, $rel_table = '@api', $rel_action = '@client_id' )
    {
        $plain_rel_id = $rel_id;
        if( strlen($plain_rel_id) == 0 ) {
            return [ApiResponse::KEY_RSLT_CODE=>ApiResponse::NOT_EXIST_TRAN_ID, ApiResponse::KEY_RSLT_MESSAGE=>'존재 하지 않는 Tran ID 입니다.'];
        }
        if( ! self::exist_rollback($mb_id, $rel_table, $rel_id, $rel_action) ) {
            return [ApiResponse::KEY_RSLT_CODE=>ApiResponse::NOT_EXIST_TRAN_ID, ApiResponse::KEY_RSLT_MESSAGE=>'존재 하지 않는 Tran ID 입니다.'];
        }
        delete_point($mb_id, $rel_table, $rel_id, $rel_action);
        $point = get_point_sum($mb_id);
        return [ApiResponse::KEY_RSLT_CODE=>ApiResponse::SUCCESS, 'tran_id'=>$rel_id, 'point'=>$point];
    }


    public static function exist_rollback($mb_id, $rel_table, $rel_id, $rel_action)
    {
        $plain_rel_id = $rel_id;
        $rslt = sql_fetch("SELECT * FROM shop_point WHERE mb_id = '{$mb_id}' AND po_rel_table = '{$rel_table}' AND po_rel_id = '{$plain_rel_id}' AND po_rel_action = '{$rel_action}'");
        return ( ! $rslt ) ? false : true;
    }
}