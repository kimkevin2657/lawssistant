<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2019-02-22
 * Time: 17:23
 */

include_once('_common.php');

if( count($_POST) ) {

    $request = new ApiRequest($_POST);

    echo json_encode($request->do_action());
} else {
    include("test.php");
}

