<?php
define('_PURENESS_', true);
include_once("./_common.php");

$mb = get_member($mb_id);
if($mb['id']) {
    echo json_encode(array(
        'result'=>true,
        'id'=>$mb['id'],
        'name'=>$mb['name'],
        'grade'=>$mb['grade'],
        'grade_name'=>get_grade($mb['grade'])
    ));
} else {
    echo json_encode(array('result'=>false, 'msg'=>'존재하지 않는 아이디 입니다.'));
}
