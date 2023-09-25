<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2018-12-12
 * Time: 18:49
 */

include_once(__DIR__.'/_common.php');

if( true ) {

    $sql = "SELECT * FROM mig_id_list";
    $result = sql_query($sql);

    while( $row = sql_fetch_array($result) ) :

        $sql1 = "SELECT DISTINCT {$row['COLUMN_NAME']} as id FROM {$row['TABLE_NAME']} WHERE {$row['COLUMN_NAME']} <> '' AND {$row['COLUMN_NAME']} IS NOT NULL AND length({$row['COLUMN_NAME']}) < 21";
        $result1 = sql_query($sql1);

        while($row1 = sql_fetch_array($result1)):
            $mig_id = Mcrypt::zen_encrypt($row1['id']);
            $sql2 = "UPDATE {$row['TABLE_NAME']} SET {$row['COLUMN_NAME']} = '{$mig_id}' WHERE {$row['COLUMN_NAME']} = '{$row1['id']}' ";
            echo $sql2.PHP_EOL;
            sql_query($sql2);
        endwhile;

    endwhile;

}

// grp_id, grp_no
if( false ) {

    $sql = "SELECT id FROM shop_member";//WHERE grp_id = '' or grp_id IS NULL";
    $rst = sql_query($sql);
    while ($row = sql_fetch_array($rst)):
        echo Mcrypt::zen_decrypt($row['id']).PHP_EOL;
        Member::updateFamilyInfo($row['id']);
    endwhile;

}
