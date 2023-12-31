<?php
################################################################################################
################################################################################################
################################################################################################
##################   디자이너 수정 방법                                         #####################
##################   index_bak.php 가 index.php라고 생각하시면 됩니다.            #####################
##################   해당페이지는 index_bak.php를 자동 복제되어 만들어진 페이지 입니다.  #####################
##################   디자인 작업이 끝난 후에는 관리자페이지 - 환경설정 - 메인페이지새로읽기  #####################
##################   를 클릭하시면 새로 변경한 디자인을 index.php에 적용 할 수 있습니다.  #####################
##################   디자인 결과물 확인은 브라우저에서 index_bak.php를 직접 호출하여 확인하시면 됩니다. ##########
################################################################################################
################################################################################################
################################################################################################
include_once('./common.php');

// 모바일접속인가?
if(MS_IS_MOBILE) {
	goto_url(MS_MURL);
}
//$r = new ReflectionClass('Theme');
//echo $r->getFileName();
define('_INDEX_', true);

// 인트로를 사용중인가?
if(!$is_member && $config['shop_intro_yes']) {
	Theme::get_theme_part(MS_THEME_PATH,'/intro.skin.php');
    return;
}
include_once(MS_PATH.'/headch.php'); // 크롤링된 페이지 입니다. 
?>