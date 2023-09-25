<?php
include_once("./_common.php");

check_demo();

check_admin_token();

$data = array(
    'pc_nm'=>$_POST['pc_nm'],
    'pc_cc_no'=>$_POST['pc_cc_no'],
    'pc_state'=>$_POST['pc_state']
);

try{
    if( empty($_POST['pc_no']) ) {
        if( sql_fetch("select * from shop_minishop_center where pc_nm = '{$data['pc_nm']}'")){
            return new Exception('존재 하는 센터 명 입니다.');
        }

        $data['reg_at'] = MS_TIME_YMDHIS;
        insert('shop_minishop_center', $data);
    } else {
        $data['upd_at'] = MS_TIME_YMDHIS;
        update('shop_minishop_center', $data, ' where pc_no = '.$_POST['pc_no']);
    }
    echo json_encode(array('result'=>'success'));
} catch(Exception $ex ){
    echo json_encode(array('result'=>'fail', 'message'=>$ex->getMessage()));
}
