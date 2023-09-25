<?php
include_once("./_common.php");

check_demo();

check_admin_token();

Shop::dpLabelUpdate($_POST);

echo json_encode(array('result'=>'success'));
