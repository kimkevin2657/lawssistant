<?php
if (!defined("_GNUBOARD_")) exit; // 개별 페이지 접근 불가
include_once($board_skin_path."/skin.function.php");
$app = explode("|", $write['wr_3']);
if($app[0] > 0) {
    alert('문서 삭제는 작성자의 결재상태가 [미결재] 상태일때만 가능합니다.');
    exit;
}
// 문서 삭제체크
?>