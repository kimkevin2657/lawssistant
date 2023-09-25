<?php
include_once("./_common_mypage.php");

if(TB_IS_MOBILE) {
    goto_url(TB_MSHOP_URL.'/mypage.php');
}

if(!$is_member) {
    goto_url(TB_BBS_URL.'/login.php?url='.$urlencode);
}

$tb['title'] = "마이페이지";
include_once("./_head_mypage.php");

$sql_common = " from shop_coupon_log ";
$sql_search = " where mb_id = '{$member['id']}' ";

// 사용가능한 쿠폰
$sql_search.= " and mb_use='0' and ( ";
$sql_search.= " (cp_inv_type='0' and (cp_inv_edate = '9999999999' or cp_inv_edate > curdate())) ";
$sql_search.= " or (cp_inv_type='1' and date_add(`cp_wdate`, interval `cp_inv_day` day) > now()) ";
$sql_search.= " ) ";

$sql = " select count(*) as cnt $sql_common $sql_search ";
$row = sql_fetch($sql);
$cp_count = $row['cnt'];


$pg_title = "마이페이지";
$pg_navi  = "가맹점 관리";

if( isset($code ) ) {
    switch( $code ) {
        case 'partner_info' :
            $pg_title = '기본정보 관리';
            $pg_sub   =  $pg_title;
            break;
        case 'partner_orgchart' :
            $pg_title = '조직도 회원조회';
            $pg_sub   =  $pg_title;
            break;
    }
}

$tb['title'] = $pg_title;

if( !empty( $pg_sub ) )
    $pg_sub = '<i>&gt;</i>' . $pg_sub;


Theme::get_theme_part(TB_THEME_PATH,'/aside_my.skin.php');
?>
    <style>

        /*본문*/
        #content {padding-bottom:100px;}
        #content:after {display:block;visibility:hidden;clear:both;content:"";}
        #content .graph {position:relative;margin:0;height:8px;background:#f1f1f1;border:1px solid #e9e9e9;}
        #content .graph.w80p {width:80% !important;display:inline-block;}
        #content .graph .bar {position:absolute;left:-1px;top:-1px;height:8px;border:1px solid #3962cd;background:#6b8eef;font-size:0;}
        #content .graph .bar2 {position:absolute;left:-1px;top:-1px;height:8px;border:1px solid #ba39cd;background:#de68f0;font-size:0;}
        .breadcrumb {padding:0 0 0 25px;color:#000;line-height:34px;border-bottom:1px solid #e9e9e9;text-align:left;}
        .breadcrumb span {font-weight:400;}
        .breadcrumb .ionicons {margin:0 7px 1px;vertical-align:middle;}

        /*헤딩*/
        #content h1 {margin:0 0 20px;padding-bottom:15px;border-bottom:1px solid #888;font-size:20px;line-height:1em;letter-spacing:-1px;}
        #content h2 {margin:30px 0 0;position:relative;font-size:16px;font-weight:600;letter-spacing:-1px;line-height:1em;padding:0 0 10px 10px;}
        #content h2:before {display:inline-block;position:absolute;left:0;top:0;width:4px;height:16px;background:#1a4e99;content:'';}

        /*하단*/
        #ft {clear:both;width:100%;min-width:1210px;font-family:"Century Gothic", sans-serif;text-align:center;margin:0;padding:20px 0;color:#999;border-top:1px solid #ccc;}

        /*메인*/
        #main_wrap {padding:0 30px 50px;overflow:hidden;}
        #main_wrap h2 {margin:30px 0 0;position:relative;font-size:16px;font-weight:600;letter-spacing:-1px;line-height:1em;padding:0 0 10px 10px;}
        #main_wrap h2:before {display:inline-block;position:absolute;left:0;top:0;width:4px;height:16px;background:#1a4e99;content:'';}
        #main_wrap .btn_small {float:right;margin-bottom:5px !important;}

        /*폼 테이블*/
        .tbl_frm01 {border-top:1px solid #888;}
        .tbl_frm01 table {width:100%;}
        .tbl_frm01 th,
        .tbl_frm01 td {padding:8px 14px;height:23px;border-bottom:1px solid #e4e5e7;text-align:left;vertical-align:middle;}
        .tbl_frm01 th {font-weight:600;}
        .tbl_frm01 td em {color:#7d7d7d;}
        .tbl_frm01 td label {margin-right:7px;}
        .tbl_frm01 tr.thover {background:#e4e5e7 !important;}

        .tbl_frm02 {}
        .tbl_frm02 table {width:100%;}
        .tbl_frm02 th,.tbl_frm02 td {padding:8px 14px;height:22px;border:1px solid #e4e5e7;text-align:left;vertical-align:middle;}
        .tbl_frm02 th {font-weight:600;}

        /*내부 폼 테이블*/
        .sub_frm01{margin:0 !important;padding:0 !important;}
        .sub_frm01 table{width:100%;border:1px solid #ececec !important;table-layout:fixed;}
        .sub_frm01 th,.sub_frm01 td{padding:5px !important;border-top:1px solid #ececec;border-right:1px solid #ececec;}
        .sub_frm01 th{font-weight:600;}
        .sub_frm01 td{border-left:1px solid #ececec;}

        .sub_frm02{margin:0 !important;padding:0 !important;}
        .sub_frm02 table{width:100%;border:none !important;table-layout:fixed;}
        .sub_frm02 th,.sub_frm02 td{padding:0 !important;border:none !important;}
        .sub_frm02 th{font-weight:600;}

        /*테이블*/
        .sidx_head01 {}
        .sidx_head01 table {width:100%;border:1px solid #ddd;table-layout:fixed;}
        .sidx_head01 th,
        .sidx_head01 td {border:1px solid #ddd;padding:10px;vertical-align:middle;}
        .sidx_head01 th {font-weight:600;letter-spacing:-0.05em;background:#f8f8f8;}
        .sidx_head01 tbody td {line-height:1.4em;word-break:break-all;}

        .sidx_head02 {}
        .sidx_head02 table {width:100%;border:1px solid #ddd;table-layout:fixed;}
        .sidx_head02 th,
        .sidx_head02 td {border:1px solid #ddd;padding:5px;vertical-align:middle;text-align:center !important;}
        .sidx_head02 th {font-weight:600;letter-spacing:-0.05em;background:#f8f8f8;}
        .sidx_head02 tbody td {line-height:1.4em;word-break:break-all;}

        /*thead 한 줄 테이블*/
        .tbl_head01 {border-top:1px solid #aeaeae;border-bottom:1px solid #e4e5e7;}
        .tbl_head01 table {width:100%;}
        .tbl_head01 thead th {border-top:0 !important;text-align:center;}
        .tbl_head01 thead tr.rows th {border-top:1px solid #e4e5e7 !important;}
        .tbl_head01 thead tr.grid th {padding:8px 0 !important;line-height:1.4em !important;}
        .tbl_head01 th {padding:8px 10px;line-height:1em;font-weight:600;background:#f1f1f1;}
        .tbl_head01 th a {text-decoration:underline !important;}
        .tbl_head01 tr:not(.rows) th:first-child,
        .tbl_head01 tr:not(.rows) td:first-child {border-left:0 !important;}
        .tbl_head01 th,
        .tbl_head01 td {border-left:1px solid #e4e5e7;text-align:center;vertical-align:middle;}
        .tbl_head01 td {height:22px;padding:8px 8px;line-height:1.6em;border-top:1px solid #e4e5e7;}
        .tbl_head01 td a {font-weight:600;}
        .tbl_head01 td a:focus,
        .tbl_head01 td a:hover,
        .tbl_head01 td a:active {text-decoration:underline;}
        .tbl_head01 td.url a {text-decoration:underline !important;font-weight:normal !important;}
        .tbl_head01 td label {margin-right:10px;}
        .tbl_head01 tr.grid td {height:33px !important;line-height:1.4em !important;}
        .tbl_head01 tbody.list tr:hover {background:#ececec !important;}
        .tbl_head01 tfoot {background:#f1f1f1 !important;}
        .tbl_head01 tfoot th,
        .tbl_head01 tfoot td {border-top:1px solid #d6d6d6 !important;}
        .tbl_head01 .frm_input {width:100%;}
        .tbl_head01 .frm_refer {display:block;padding-top:5px;color:#547eec;}

        .tbl_head02{border-top:1px solid #aeaeae;border-bottom:1px solid #e4e5e7;}
        .tbl_head02 table{width:100%;}
        .tbl_head02 thead th{border-top:0 !important;text-align:center;}
        .tbl_head02 thead tr.rows th{border-top:1px solid #e4e5e7 !important;}
        .tbl_head02 thead tr.grid th{padding:8px 0 !important;line-height:1.4em !important;}
        .tbl_head02 th{padding:8px 10px;line-height:1em;font-weight:600;background:#f1f1f1;}
        .tbl_head02 th a{text-decoration:underline !important;}
        .tbl_head02 tr:not(.rows) th:first-child,
        .tbl_head02 tr:not(.rows) td:first-child{border-left:0 !important;}
        .tbl_head02 th,.tbl_head02 td{border-left:1px solid #e4e5e7;font-size:11px;text-align:center;vertical-align:middle;}
        .tbl_head02 td{height:38px;padding:0 8px;line-height:1.6em;border-top:1px solid #e4e5e7;}
        .tbl_head02 td a{font-weight:600;}
        .tbl_head02 td a:focus,.tbl_head02 td a:hover,.tbl_head02 td a:active{text-decoration:underline;}
        .tbl_head02 td.url a{text-decoration:underline !important;font-weight:normal !important;}
        .tbl_head02 td label{margin-right:10px;}
        .tbl_head02 tr.grid td{height:33px !important;line-height:1.4em !important;}
        .tbl_head02 tbody.list tr:hover{background:#ececec !important;}
        .tbl_head02 tfoot{background:#f1f1f1 !important;}
        .tbl_head02 tfoot th,.tbl_head02 tfoot td{border-top:1px solid #d6d6d6 !important;}
        .tbl_head02 .frm_input{width:100%;}
        .tbl_head02 .frm_refer{display:block;padding-top:5px;color:#547eec;}

        /*버튼*/
        .btn_confirm {margin-top:20px;text-align:center;}
        .btn_confirm .btn_small.fa-caret-up {font-size:17px;padding:1px 8px 3px;}
        .btn_confirm .btn_small.fa-caret-down {font-size:17px;padding:2px 8px;}
        .btn_confirm a,.btn_confirm input,.btn_confirm button{margin:0 1.5px;}
        .btn_confirm02 {margin-top:8px;margin-bottom:0;text-align:center;}
        .btn_confirm02 a,.btn_confirm02 input,.btn_confirm02 button{margin:0 1.5px;}
        .btn_confirm03 {margin-top:8px;margin-bottom:8px;text-align:center;}
        .btn_confirm03 a,.btn_confirm03 input,.btn_confirm03 button{margin:0 1.5px;}

        /*공통*/
        .local_frm01,.local_frm02 {overflow:hidden;margin:0;padding:7px 10px;background:#fcfcfc;}
        .local_frm01:after,.local_frm02:after {display:block;visibility:hidden;clear:both;content:"";}
        .local_frm01 {border-top:1px solid #ececec;}
        .local_frm02 {border-bottom:1px solid #e4e5e7;}
        .local_frm01 .stxt {display:inline-block;margin:4px 7px 0 0;}
        .s_wrap .ms_type1 li,
        .s_wrap .ms_type2 li {margin-right:20px;float:left;}
        .guidebox {border:1px solid #ddd;padding:10px 12px;background-color:#f8f8f8;}
        .or_totalbox {border:5px solid #eee;}
        .or_totalbox li {width:25%;font-size:13px;padding:15px 0;text-align:center;border-left:1px solid #eee;float:left;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
        .or_totalbox li:first-child {border-left:0 !important;}
        .or_totalbox li b {font-size:15px;}
        .or_totalbox.w33p li {width:33.33%;}

        /*페이징*/
        .pg_wrap {margin:0;padding:20px 0 0;text-align:center;}
        .pg_wrap span,.pg_wrap strong,.pg_wrap a {display:inline-block;text-decoration:none;}
        .pg {}
        .pg a:focus,.pg a:hover {text-decoration:none;border:1px solid #333;}
        .pg_page,.pg_current {padding:0 8px;min-width:9px;height:25px;color:#353e44;font-weight:normal;line-height:25px;vertical-align:middle;border:1px solid #c8c8c8;}
        .pg_page {background-color:#fbfbfb;text-decoration:none;}
        .pg_current {background-color:#333;color:#fff;border:1px solid #333 !important;}
        .pg_start,.pg_prev,.pg_next,.pg_end {width:27px;height:27px;overflow:hidden;padding:0 !important;font-size:0 !important;text-indent:-9000px;border:none !important;background:url('/img/sprite_pg.png') no-repeat;vertical-align:top;}
        .pg_prev {margin-right:4px;}
        .pg_next {margin-left:4px;}
        .pg a.pg_start {background-position:-116px 0;}
        .pg a.pg_prev {background-position:-145px 0;}
        .pg a.pg_next {background-position:-174px 0;}
        .pg a.pg_end {background-position:-203px 0;}
        .pg span.pg_start {background-position:0 0;}
        .pg span.pg_prev {background-position:-29px 0;}
        .pg span.pg_next {background-position:-58px 0;}
        .pg span.pg_end {background-position:-87px 0;}

        /*ckeditor 단축키*/
        .cke_sc {margin:0 0 5px;text-align:right}
        .btn_cke_sc {display:inline-block;padding:0 10px;line-height:23px;border:1px solid #ccc !important;background:#fafafa !important;color:#000 !important;text-decoration:none !important;line-height:1.9em;vertical-align:middle}
        .cke_sc_def {margin:5px 0 5px;padding:10px;border:1px solid #ccc;background:#f7f7f7;text-align:center}
        .cke_sc_def dl {margin:0 0 5px;text-align:left;zoom:1}
        .cke_sc_def dl:after {display:block;visibility:hidden;clear:both;content:""}
        .cke_sc_def dt,.cke_sc_def dd {float:left;margin:0;padding:5px 0;border-bottom:1px solid #e9e9e9}
        .cke_sc_def dt {width:20%;font-weight:bold}
        .cke_sc_def dd {width:30%}

        /*카테고리 관리*/
        .sho_cate_bx {border:1px solid #ddd;}
        .sho_cate_bx li {padding:7px 15px;text-align:left;border-top:1px dotted #ccc;}
        .sho_cate_bx li:first-child {border-top:0 !important;}
        .sho_cate_bx .cate2_bx {margin:0;}
        .sho_cate_bx .cate2_bx > dt {padding:1px 0 1px 25px;}
        .sho_cate_bx .cate3_bx {margin:0;}
        .sho_cate_bx .cate3_bx > dd {padding:1px 0 1px 0;margin-left:50px;}
        .sho_cate_bx .cate4_bx {margin:0;}
        .sho_cate_bx .cate4_bx > dd {padding:1px 0 1px 0;margin-left:25px;}
        .sho_cate_bx .cate5_bx {margin:0;}
        .sho_cate_bx .cate5_bx > dd {padding:1px 0 1px 0;margin-left:25px;}
        .sho_cate_bx body {background-color:#fff !important;}

        /*간단수정 페이지*/
        #ppg_wrap {margin:30px;}
        #ppg_wrap h2 {margin:31px 0 7px;font-size:15px;line-height:1em;letter-spacing:-1px;}
        #ppg_wrap .ms_type1 li {padding:1px 5px;float:left;}
        #ppg_wrap .ms_type2 {padding-top:5px;margin-top:7px;border-top:1px dotted #ccc;}
        #ppg_wrap .ms_type2 li {padding:1px 5px;float:left;}

        /*쿠폰관리(온라인)*/
        .chk_opli {margin:5px 0 0;max-height:204px;overflow-y:auto;}
        .chk_opli li {height:38px;padding:7px 30px 5px 55px;border-top:1px dotted #ccc;position:relative;}
        .chk_opli .pr_img {width:40px;height:40px;position:absolute;left:5px;top:5px;}
        .chk_opli .bt_del {position:absolute;right:5px;top:15px;}

        /*주문관리*/
        #opt_4 {padding:0 0 0 10px;display:inline-block;vertical-align:middle;}

        /*디자인관리*/
        .dg_img_ic{position:relative;margin-left:10px;display:inline-block;cursor:pointer;}
        .dg_img_ic .skin_img{border:1px solid #111;background-color:#fff;padding:5px;position:absolute;left:-700px;top:-14px;display:none;}
        .dg_img_ic:hover .skin_img{display:block;}

        /*SMS*/
        .scf_sms_img{position:relative;margin:5px 0 15px;width:163px;height:191px;background:url("/admin/img/sms_back.gif") no-repeat 0 0;text-align:center;}
        .scf_sms_img textarea {position:absolute;top:55px;left:24px;margin:0;width:115px;height:85px;border:0;background:transparent;font-size:0.95em;overflow:hidden;}
        .scf_sms_byte {width:100%;text-align:center;position:absolute;bottom:24px;left:0;}
        .scf_sms_wrap {margin:6px 20px 15px 0;padding:10px;height:168px;border:1px solid #ddd;background-color:#fafafa;}
        .scf_sms_wrap .tit {font-size:15px;font-weight:600;padding:0 0 10px;}
        .scf_sms_wrap p {padding:7px 0;border-bottom:1px dotted #ddd;}

        /*문자전송*/
        .psms_tit {font-size:16px;font-weight:600;border-bottom:1px solid #ccc;padding:10px;}
        .psms_wrap {padding:10px;}

        /*새창*/
        .newp_tit {font-size:18px;font-weight:600;padding:15px 20px;border-bottom:1px solid #ccc;}
        .newp_wrap {padding:20px;}
        .new_win_lnb {height:100%;min-height:600px;border-right:1px solid #333;}
        .new_win_lnb .lnb_tit {height:42px;padding:14px;background-color:#333;}
        .new_win_lnb .lnb_tit p {font-size:13px;padding:3px 0;color:#fff;letter-spacing:0;}
        .new_win_lnb ul {margin:10px 0 0;}
        .new_win_lnb li a {padding:5px 15px;text-decoration:none !important;display:block;}
        .new_win_lnb li:hover a {color:#111;font-weight:600;background-color:#f5f5f5;}
        .new_win_body {padding:20px 20px 40px 20px;}
        .new_win_body h2 {height:50px;font-size:23px;line-height:1.4em;margin:0 0 20px 0;border-bottom:1px solid #888;position:relative;}
        .new_win_body h2 .btn_wrap {position:absolute;bottom:22px;right:0;}
        .new_win_body h3 {margin:31px 0 7px;font-size:15px;line-height:1em;letter-spacing:-1px;}

        .half_bx {width:49%;margin-left:2%;float:left;}
        .half_bx:first-child {margin-left:0 !important;}

        /*외부서비스 사이트코드*/
        .sitecode {display:inline-block;font:bold 15px 'Verdana';vertical-align:middle;}

        /*페이지 진열*/
        .op_list, .op_list li{list-style:none;padding:0;margin:0;}
        .op_list li{min-height:23px;line-height:1.7em;float:left;}

        /*기타*/
        .td_price {text-align:right !important;font-weight:600;}
        .td_label label {margin-right:7px;}
        .bo_label label {margin:0 !important;line-height:1.5em !important;}
        .bo_label label span {margin-left:5px !important;color:#197fe0 !important;}
        .od_chk {padding-bottom:10px;}
        .banner_or_img {margin:5px 0 0;}
        .th_bg {background-color:#f7f8e0 !important;}
        .th_bg2 {background-color:#fff700 !important;}
        .txt_true {color:#e8180c !important;}
        .txt_false {color:#ccc !important;}
        .txt_succeed {color:#40b300 !important;}
        .txt_fail {color:#ce4242 !important;}
        .fsitem{font-family:"돋움";font-size:11px;}
        .fsitem span{letter-spacing:0 !important;}
        .tr_alignc th,
        .tr_alignc td{text-align:center !important;}
        .txt_active{color:#5d910b !important;}
        .txt_expired{color:#ccc !important;}
        .bg0{background-color:#ffffff !important;}
        .bg1{background-color:#fcfceb !important;}

        /*로딩바*/
        #ajax-loading{position:fixed;top:0;left:0;width:100%;height:100%;z-index:9000;text-align:center;display:none;}
        #ajax-loading img{position:absolute;top:50%;left:50%;width:120px;height:120px;margin:-60px 0 0 -60px;}

        /*상단으로*/
        #anc_header{z-index:20;position:fixed;right:70px;bottom:30px;display:none;}
        #anc_header a{width:80px;display:block;text-align:center;font:11px/100% Arial, Helvetica, sans-serif;text-transform:uppercase;text-decoration:none;color:#bbb;-webkit-transition:1s;-moz-transition:1s;transition:1s;}
        #anc_header a:hover{color:#000;}
        #anc_header span{width:80px;height:80px;display:block;margin-bottom:7px;background:#ddd url('/img/up-arrow.png') no-repeat center center;-webkit-border-radius:15px;-moz-border-radius:15px;border-radius:15px;-webkit-transition:1s;-moz-transition:1s;transition:1s;}
        #anc_header a:hover span{background-color:#777;}

        /*가격비교 사이트*/
        .price_engine{margin:0;padding:0;border:1px solid #eaeaea;background:#fafafa;}
        .price_engine a{text-decoration:underline;}
        .price_engine strong{color:#ec0e03;}
        .price_engine dt a,
        .price_engine ul li{padding-left:20px;}
        .price_engine dt a,
        .price_engine li{line-height:1.8em;}
        .price_engine dt a{font-weight:600;}
        .price_engine p,
        .price_engine ol{padding:10px 20px;}
        .price_engine dd{margin:0 0 10px 15px;}

        /*페이지 내 실행*/
        .local_cmd{}
        .local_cmd01{margin:0;padding:10px;border-top:1px solid #e4e5e7;background:#fff;}
        .local_cmd01 .cmd_tit{font-weight:600;}
        .local_cmd02{margin:0;padding:10px;border-bottom:1px solid #efefef;background:#fff;position:relative;}
        .local_cmd02 button{position:absolute;top:5px;right:0;}

        .sod_opt {margin:0;border-bottom:0;}
        .sod_opt ul {margin:0;padding:0;list-style:none;}
        .sod_opt li.ty {padding:0;color:#7d62c3;letter-spacing:0;}
        .sod_opt li.ny {padding:0;color:#888888;letter-spacing:0;}

        /*공통박스*/
        .compare_wrap{margin:0 0 10px;zoom:1;}
        .compare_wrap:after{display:block;visibility:hidden;clear:both;content:"";}
        .compare_wrap section{margin:0;padding:2%;background:#f8f8f8;}
        .compare_wrap h3{margin:0 0 20px;text-align:center;}
        .compare_wrap .tbl_frm{margin:0;}
        .compare_wrap .frm_input{background-color:#fff !important;}
        .compare_wrap .btn_confirm{padding:10px 0 0;}
        .compare_left{float:left;width:45%;}
        .compare_right{float:right;width:45%;}

        /*관련 상품 입력/수정*/
        .srel section ul{margin:0;padding:10px;list-style:none;}
        .srel section li{padding:5px 0;border-bottom:1px solid #e9e9e9;zoom:1;}
        .srel section li:after{display:block;visibility:hidden;clear:both;content:'';}
        .srel .srel_list, .srel .srel_sel{height:auto !important;height:200px;max-height:200px;border:1px solid #ced9de;background:#f6f6f6;overflow-y:scroll;}
        .srel .list_item{float:left;width:80%;}
        .srel .list_item img{float:left;margin:0 10px 0 0;}
        .srel .list_item_btn{float:right;}
        .srel .srel_sel{border:1px solid #ced9de;background:#fcfff2;}
        .srel .srel_list p, .srel .srel_sel p{padding:10px 0;text-align:center;}
        .srel .compare_left ul{margin:0;list-style:none;}
        .srel .srel_noneimg li{padding:7px 0;}
        .srel .srel_noneimg button{top:0;right:0;}
        .srel .srel_pad{display:block;height:30px;}
        .srel .srel_pad button{position:static;}

        /*쿠폰관리*/
        #scp_list_find{margin:10px 20px;padding:10px;border:1px solid #e9e9e9;background:#f7f7f7;}
        #scp_list_find label{font-weight:600;}

        /*폼 테이블*/
        .tbl_wrap{margin:0 20px 20px;padding:0;}

        /*새창 기본 스타일*/
        .new_win{min-width:320px;}
        .new_win h1{margin-bottom:20px;padding:0 20px;min-width:320px;height:60px;border-top:2px solid #484848;border-bottom:1px solid #e9e9e9;background:#fff;font-size:1.2em;line-height:5em;}
        .new_win h2{margin:0 20px 10px;font-size:14px;line-height:1em;letter-spacing:-1px;}
        .new_win em{font-style:normal;vertical-align:middle;color:#547eec !important;line-height:22px !important;}
        .new_win .sit_copy{margin:10px 20px 20px;background:#fff;}
        .new_win .sit_copy label{display:inline-block;margin:0 10px 0 0;font-weight:600;}
        .new_win_desc{margin:0 20px;}

        /*안내박스*/
        .information{margin-top:50px;padding:20px 0;background:#fef7f8;border-left:#f0868e 3px solid;}
        .information h4{background:url('/img/icon_guide.jpg') no-repeat 12px 2px;padding-left:39px;font-size:12px;line-height:1.4em;font-weight:800;}
        .information .content{padding-left:23px;}
        .information .hd{font-size:12px;line-height:1.8em;font-weight:800;padding-top:10px;}
        .information .desc01,.information .desc02{color:#666;font-size:12px;line-height:1.6em;}
        .information .desc02{padding-top:10px;}
        .information em{color:#547eec;}

        /*페이지 내 안내문*/
        .local_ov{margin:0;padding:10px;border-top:1px solid #aeaeae;}
        .local_ov a,.local_ov a strong{color:#547eec;}
        .local_ov .ov_listall{display:inline-block;margin:0 5px 0 0;padding:0 10px 0 0;border-right:1px solid #ccc;}
        .local_ov .ov_a{display:inline-block;margin:0 0 0 5px;padding:0 0 0 10px;border-left:1px solid #ccc;color:#ff3061 !important;}
        .local_ov2{margin:0;padding:10px;border-bottom:1px solid #efefef;background:#fcfcfc;}

        .local_desc{}
        .local_desc ol, .local_desc ul{margin:0;padding:0 0 10px 20px;}
        .local_desc li{margin:0 0 5px;}

        .local_desc01{margin-bottom:10px;padding:10px 20px;border:1px solid #f2f2f2;background:#f9f9f9;}
        .local_desc01 strong{color:#ec0e03;}
        .local_desc01 a{text-decoration:underline;}

        .local_desc02{margin-bottom:10px;} /*주로 온라인 서식 관련 안내 내용에 사용*/
        .local_desc02 p{padding:0;line-height:1.6em;word-break:break-all;}
        .local_desc02 a{text-decoration:underline;}

        .local_desc03{margin-bottom:10px;padding:10px 20px;border:1px solid #e9e9e9;background:#f9f9f9;}
        .local_desc03 strong{color:#ec0e03;}
        .local_desc03 p{padding:0;line-height:1.6em;word-break:break-all;}
        .local_desc03 a{text-decoration:underline;}

        .local_desc04{margin:0 20px 10px;}
        .local_desc04 p{padding:0;line-height:1.8em;}

        #scf_sms_pre dl:after {display:block;visibility:hidden;clear:both;content:"";}
        #scf_sms_pre dt {clear:both;float:left;padding:5px 0;width:100px;}
        #scf_sms_pre dd {padding:5px 0;overflow:hidden;}

        .colorpicker {width:356px;height:176px;overflow:hidden;position:absolute;background:url('/admin/img/colorpicker_background.png');font-family:Arial, Helvetica, sans-serif;display:none;}
        .colorpicker_color {width:150px;height:150px;left:14px;top:13px;position:absolute;background:#f00;overflow:hidden;cursor:crosshair;}
        .colorpicker_color div {position:absolute;top:0;left:0;width:150px;height:150px;background:url('/admin/img/colorpicker_overlay.png');}
        .colorpicker_color div div {position:absolute;top:0;left:0;width:11px;height:11px;overflow:hidden;background:url('/admin/img/colorpicker_select.gif');margin:-5px 0 0 -5px;}
        .colorpicker_hue {position:absolute;top:13px;left:171px;width:35px;height:150px;cursor:n-resize;}
        .colorpicker_hue div {position:absolute;width:35px;height:9px;overflow:hidden;background:url('/admin/img/colorpicker_indic.gif') left top;margin:-4px 0 0 0;left:0px;}
        .colorpicker_new_color {position:absolute;width:60px;height:30px;left:213px;top:13px;background:#f00;}
        .colorpicker_current_color {position:absolute;width:60px;height:30px;left:283px;top:13px;background:#f00;}
        .colorpicker input {background-color:transparent;border:1px solid transparent;position:absolute;font-size:10px;font-family:Arial, Helvetica, sans-serif;color:#898989;top:4px;right:11px;text-align:right;margin:0;padding:0;height:11px;}
        .colorpicker_hex {position:absolute;width:72px;height:22px;background:url('/admin/img/colorpicker_hex.png') top;left:212px;top:142px;}
        .colorpicker_hex input {right:6px;}
        .colorpicker_field {height:22px;width:62px;background-position:top;position:absolute;}
        .colorpicker_field span {position:absolute;width:12px;height:22px;overflow:hidden;top:0;right:0;cursor:n-resize;}
        .colorpicker_rgb_r {background-image:url('/admin/img/colorpicker_rgb_r.png');top:52px;left:212px;}
        .colorpicker_rgb_g {background-image:url('/admin/img/colorpicker_rgb_g.png');top:82px;left:212px;}
        .colorpicker_rgb_b {background-image:url('/admin/img/colorpicker_rgb_b.png');top:112px;left:212px;}
        .colorpicker_hsb_h {background-image:url('/admin/img/colorpicker_hsb_h.png');top:52px;left:282px;}
        .colorpicker_hsb_s {background-image:url('/admin/img/colorpicker_hsb_s.png');top:82px;left:282px;}
        .colorpicker_hsb_b {background-image:url('/admin/img/colorpicker_hsb_b.png');top:112px;left:282px;}
        .colorpicker_submit {position:absolute;width:22px;height:22px;background:url('/admin/img/colorpicker_submit.png') top;left:322px;top:142px;overflow:hidden;}
        .colorpicker_focus {background-position:center;}
        .colorpicker_hex.colorpicker_focus {background-position:bottom;}
        .colorpicker_submit.colorpicker_focus {background-position:bottom;}
        .colorpicker_slider {background-position:bottom;}

        /*주문배송*/
        .order_vbx {width:calc(100% - 2px);margin-bottom:25px;border:1px solid #ddd;overflow:hidden;display:table;}
        .order_vbx dl {padding:15px 10px 10px;border-left:1px solid #ddd;display:table-cell;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;}
        .order_vbx dl:first-child {border-left:0 !important;}
        .order_vbx dt {font-size:15px;font-weight:600;margin-bottom:15px;text-align:center;}
        .order_vbx dt span {font-size:11px;font-weight:normal;margin-left:5px;color:#e94c1c;}
        .order_vbx dd {font-size:15px;position:relative;}
        .order_vbx dd p {height:17px;color:#222;font-weight:800;padding:15px 0;}
        .order_vbx dd .ddtit {height:13px;font-size:11px;font-weight:600;padding:10px 0;background-color:#eee;}
        .order_vbx .od_bx1 {width:290px;}
        .order_vbx .od_bx1 dd {width:calc(37% - 2px);margin:0 1px;background-color:#f8f8f8;text-align:center;float:left;}
        .order_vbx .od_bx1 dd.total {width:calc(63% - 2px);}
        .order_vbx .od_bx1 dd.total {font-size:13px;}
        .order_vbx .od_bx2 {width:418px;}
        .order_vbx .od_bx2 dd {width:calc(20% - 2px);margin:0 1px;background-color:#f8f8f8;text-align:center;float:left;}
        .order_vbx .od_bx3 {width:290px;}
        .order_vbx .od_bx3 dd {width:calc(25% - 2px);margin:0 1px;background-color:#f8f8f8;text-align:center;float:left;}

        /*목록 바로가기*/
        .anchor{margin:0 0 10px 0;height:29px;background:url('/admin/img/tab_menu.gif') repeat-x 0 100%;}
        .anchor:after{display:block;visibility:hidden;clear:both;content:"";}
        .anchor li{margin:0;padding:0;list-style:none;}
        .anchor li,.anchor li a{background:url('/admin/img/bg_tab2_off.gif') no-repeat;}
        .anchor li{float:left;margin-right:-2px;line-height:24px;}
        .anchor li a{display:inline-block;padding:2px 16px 2px;background-position: 100% 0;font-weight:600;color:#666;text-decoration:none !important;}
        .anchor li a:hover{color:#000;}
        .anchor li.active,.anchor ul li.active a{background-image:url('/admin/img/bg_tab2_on.gif');}
        .anchor li.active a{color:#3376b8;}

        /*배너관리*/
        .sbn_img{text-align:center;}
        .sbn_image{display:none;text-align:left;padding-bottom:10px !important;}

        /*가맹점관련*/
        #partner_list thead th{padding:6px 10px !important;}
        #partner_list tfoot td{font-weight:600;background:#f1f1f1 !important;}

        /*주문내역*/
        #sodr_list thead th{padding:6px 10px !important;}
        #sodr_list td{height:22px;padding:5px 8px;line-height:1.4em !important;}
        #sodr_list td a{font-weight:normal !important;}
        #sodr_list .td_chk {padding:5px 0 !important;}
        #sodr_list .td_img {padding:5px 0 5px 8px !important;}
        #sodr_list .td_imgline {padding:5px 0 !important;border-left:none !important;}
        #sodr_list .td_itname {border-left:none !important;text-align:left !important;}
        #sodr_list .list_point {display:block;text-align:center;}
        #sodr_list .list_escrow {display:block;text-align:center;color:#80bc0d;}
        #sodr_list .list_test {display:block;text-align:center;color:#ec0e03;}
        #sodr_list .list_baesong {display:block;text-align:center;color:#ec0e03;font-weight:600;}
        #sodr_list .list_canceltype {display:block;text-align:center;color:#ec0e03;font-weight:600;}
        #sodr_list .list_mb_id {display:block;text-align:center;color:#999999;}
        #sodr_list .list_cancel {display:block;text-align:right;color:#ec0e03;}

        #sod_ws_tot{margin:10px 0 0;padding:0 10px;border:1px solid #e2e2e2;background-color:#f9f9f9;zoom:1;}
        #sod_ws_tot:after{display:block;visibility:hidden;clear:both;content:"";}
        #sod_ws_tot dt,
        #sod_ws_tot dd{float:left;font-weight:600;padding:10px 0;border-top:1px solid #e2e2e2;}
        #sod_ws_tot dt{padding-left:2%;width:48%;}
        #sod_ws_tot dd{padding-right:2%;width:48%;margin:0;text-align:right;}
        #sod_ws_tot .ws_price{background-color:#8f908c;color:#fff;border-top:0 !important;}

        /*주문내역출력 (새창)*/
        .new_win .sodr_print_pop_list table{width:100%;}
        #sodr_print_pop h2{padding:15px 0;margin:0;color:#ff3600;text-align:right;}
        #sodr_print_pop h3{margin:0 0 10px;font-size:1em;}
        .sodr_print_pop_list{margin:0 20px 10px;padding:0 0 10px;}
        .sodr_print_pop_list .sodr_print_pop_same{padding:10px 0;margin:0 0 10px;border:1px solid #e9e9e9;background:#f7f7f7;text-align:center;}
        .sodr_print_pop_list dl{margin:0 0 15px;padding:0;zoom:1;}
        .sodr_print_pop_list dl:after{display:block;visibility:hidden;clear:both;content:"";}
        .sodr_print_pop_list dt{float:left;padding:7px 0 6px;width:100px;border-bottom:1px solid #ddd;}
        .sodr_print_pop_list dd{padding:7px 0 6px;border-bottom:1px solid #ddd;overflow:hidden;}
        #sodr_print_pop_total{padding:20px 0;text-align:center;}
        #sodr_print_pop_total span{display:block;margin:0 0 10px;font-size:1.3em;}
        #sodr_print_pop_total strong{color:#ff3600;}

        /*주문내역 수정*/
        .sodr_nonpay{color:#ff6600;}
        strong.sodr_nonpay{display:block;padding:5px 0;text-align:right;}
        .sodr_sppay{color:#1f9bff;}
        #anc_sodr_orderer #addr1, #anc_sodr_orderer #addr2, #anc_sodr_orderer #addr3{margin:5px 0 0;}
        #anc_sodr_taker #b_addr1, #anc_sodr_taker #b_addr2, #anc_sodr_taker #b_addr3{margin:5px 0 0;}
        #anc_sodr_orderer #addr_jibeon, #anc_sodr_taker #b_addr_jibeon{display:block;margin:5px 0 0;}
        .od_test_caution{font-weight:600;color:#ff0000;margin:10px 0;font-size:1.167em;background:#ffe3e3;padding:10px 20px;}

        #anc_sodr_list{margin:0 20px;}
        #anc_sodr_pay{margin:30px 20px 0;}
        #anc_sodr_memo{margin:30px 20px 0;}
        #anc_sodr_addr{margin:30px 20px 50px;}

        #sodr_qty_log{}
        #sodr_qty_log h3{margin:20px 0 10px;}
        #sodr_qty_log div{padding:10px 20px;height:auto !important;height:200px;max-height:200px;border:1px solid #f2f2f2;background:#f9f9f9;line-height:1.6em;overflow-y:scroll;}

    </style>
<!-- 마이페이지 시작 { -->
    <?php include_once( "./{$code}.php");?>
    </div>
<?php
include_once("./_tail_mypage.php");
?>