<?php
include_once("./_common.php");

if(!$index){
        alert("삭제할 데이터가 없습니다.");
}else{
	$sql = " delete from shop_cart where index_no='$index' and ct_select='0' and ct_direct='{$member['id']}' ";
    sql_query($sql);
}
   goto_url(MS_MSHOP_URL."/cart.php");

?>