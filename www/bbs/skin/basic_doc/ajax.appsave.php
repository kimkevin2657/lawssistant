<?php
include_once("../../../common.php");
include_once("/home/pulo/www/bbs/skin/basic_doc/skin.function.php");



$name = mb_name($member['id']);
if(!$name) {
    exit();
}

$approval = implode("|",$_POST);

$sql = "select
            count(*) as cnt
        from
            {$write_table}_line
        where
            mb_id = '{$member['id']}' and approval = '{$approval}' ";
$row = sql_fetch($sql, true);
if($row['cnt'] > 0) {
    echo json_encode(false);
    die();
}

$sql = "INSERT INTO
            {$write_table}_line
            (`id_no`, `mb_id`, `approval`)
        VALUES
            ('{$id_no}', '{$member['id']}', '{$approval}')
        ON DUPLICATE KEY UPDATE
            `approval` = '{$approval}' ";
sql_query($sql,true);
$id_no = sql_insert_id();
echo json_encode($id_no);
?>

