<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가

@sql_query("delete from {$write_table}_sub where wr_id = '{$wr_id}' "); // 문서상세내역 삭제
@sql_query("delete from {$write_table}_log where wr_id = '{$wr_id}' "); // 문서 로그 삭제
?>

