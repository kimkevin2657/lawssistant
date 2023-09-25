<?php
include_once("./_common.php");
//include_once(G5_PATH."/head.sub.php");

if(!$member['mb_id']) {
    die("[1] 정상적인 접근이 아닙니다.");
}

if(!$bo_table) {
    die("[2] 정상적인 접근이 아닙니다.");
}

include_once($board_skin_path."/skin.function.php");

$sql = "delete from
            {$write_table}_sub
        where
            id_no = '{$id}'";
sql_query($sql, true);
$cnt = doc_affected_rows();
echo json_encode($cnt);
?>
