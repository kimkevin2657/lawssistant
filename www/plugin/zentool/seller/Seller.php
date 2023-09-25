<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-12-28
 * Time: 03:23
 */

class Seller
{

    public static function payCalc(array $param)
    {
        global $tb;

        $order_idx = ( is_array($param['order_idx']) ) ? join(",", $param['order_idx']) : $param['order_idx'];

        $sql = "insert into shop_seller_cal
			   set mb_id = '{$param['mb_id']}'
			     , order_idx = '{$order_idx}'
				 , tot_price = '{$param['tot_price']}'
				 , tot_point = '{$param['tot_point']}'
				 , tot_coupon = '{$param['tot_coupon']}'
				 , tot_baesong = '{$param['tot_baesong']}'
				 , tot_supply = '{$param['tot_supply']}'
				 , tot_seller = '{$param['tot_seller']}'
				 , tot_minishop = '{$param['tot_minishop']}'
				 , tot_admin = '{$param['tot_admin']}'
				 , reg_time = '".MS_TIME_YMDHIS."' ";
        sql_query($sql, FALSE);

        $sql = "update shop_order
				set sellerpay_yes = '1'
			  where index_no IN ({$order_idx})
				and sellerpay_yes = '0' ";

        sql_query($sql, FALSE);

        $rslt = @mysqli_affected_rows($tb['connect_db']);

        if( $rslt ) {
            $rslt = JsonResult::SUCCESS;
            $data = $rslt .' 건이 정상적으로 정산 되었습니다.';
        } else {
            $rslt = JsonResult::FAIL;
            $data = '정산되지 않았습니다.';
        }
        return JsonResponse::response($rslt, $data);
    }

    public static function payCalcRollback(array $param)
    {
        global $tb;

        $order_idx = ( is_array($param['order_idx']) ) ? join(",", $param['order_idx']) : $param['order_idx'];

        $sql = "insert into shop_seller_cal
			   set mb_id = '{$param['mb_id']}'
			     , order_idx = '{$order_idx}'
				 , tot_price = '{$param['tot_price']}'
				 , tot_point = '{$param['tot_point']}'
				 , tot_coupon = '{$param['tot_coupon']}'
				 , tot_baesong = '{$param['tot_baesong']}'
				 , tot_supply = '{$param['tot_supply']}'
				 , tot_seller = '{$param['tot_seller']}' * -1
				 , tot_minishop = '{$param['tot_minishop']}'
				 , tot_admin = '{$param['tot_admin']}'
				 , reg_time = '".MS_TIME_YMDHIS."' ";
        sql_query($sql, FALSE);

        $sql = "update shop_order
				set sellerpay_yes = '0'
			  where index_no IN ({$order_idx})
				and sellerpay_yes = '1' ";

        sql_query($sql, FALSE);

        $rslt = @mysqli_affected_rows($tb['connect_db']);

        if( $rslt ) {
            $rslt = JsonResult::SUCCESS;
            $data = $rslt .' 건이 정상적으로 정산 취소 되었습니다.';
        } else {
            $rslt = JsonResult::FAIL;
            $data = '정산되지 않았습니다.';
        }
        return JsonResponse::response($rslt, $data);
    }
}