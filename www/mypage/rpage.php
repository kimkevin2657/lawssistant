<?php
include_once("./_common.php");
include_once(MS_PLUGIN_PATH.'/wz.bookingC.prm/config.php');
include_once(MS_PLUGIN_PATH.'/wz.bookingC.prm/lib/function.lib.php');
//add_stylesheet('<link rel="stylesheet" href="'.MS_ADMIN_URL.'/css/admin.css">', 10);
add_stylesheet('<link rel="stylesheet" href="'.MS_ADMIN_URL.'/wz_bookingC_prm_admin/style.css">', 10);
add_stylesheet('<link rel="stylesheet" href="'.WZB_PLUGIN_URL.'/css/font-awesome.min.css">', 10);
add_stylesheet('<link rel="stylesheet" href="'.WZB_PLUGIN_URL.'/css/magnific-popup.css?v=170202">', 12);
add_javascript('<script type="text/javascript" src="'.WZB_PLUGIN_URL.'/js/jquery.magnific-popup.min.js"></script>', 12);
add_javascript('<script type="text/javascript" src="'.MS_ADMIN_URL.'/wz_bookingC_prm_admin/js/common.js"></script>', 12);

include_once("./admin_head.php");

?>
<style>
    /* 조직도 넓이 때문에 좁아짐 방지 */
    #snb{min-width:200px;}
    
/*예약관리 > 예약관리*/
#fsearch{padding:20px 0;}
#fsearch > div{padding:10px 0;}
#fsearch > div > strong{display:inline-block; width:120px;}
input{padding:5px 7px; border:1px solid #ddd;}
#fsearch button{margin:0 10px;}
#fsearch select, #fsearch .frm_input{height:30px;}
#fsearch > div > label{margin-right:10px;}

/*예약관리 > 이용관리*/
#frm .btn_add01{margin-bottom:15px;text-align:right;}
#frm .btn_add01 a#bo_add{font-weight:600; border: 1px solid #ccc; padding: 5px; background: #eee;text-decoration:none;}
#frm .btn_add01 a#bo_add:hover{background:#fff;}
#frm .tbl_head01 .bg0 td:nth-child(6){padding:0;}
#frm .tbl_head01 .bg0 td:nth-child(6) .tbl_into{margin:0;}
#frm .tbl_head01 .bg0 td:nth-child(6) .tbl_into th{border-top: none; border-right: none;}
#frm .tbl_head01 .bg0 td:nth-child(6) .tbl_into tbody td{border-bottom:none;}

/*예약관리 > 개별요금관리*/
.local_desc01 p{line-height:1.8em;}

/*예약관리 > 옵션관리*/
.btn_add01{margin-bottom:15px;text-align:right;}
.btn_add01 #coupon_add{font-weight:600; border: 1px solid #ccc; padding: 5px; background: #eee;text-decoration:none;}
.btn_add01 #coupon_add:hover{background:#fff;}
</style>
<div id="wrapper">
    <div id="snb">
        <?php
        //echo $admin_snb_file; 
        include_once($admin_snb_file);
        ?>
    </div>
    <div id="content">
        <?php
        include_once("./reservation/{$code}.php");
        ?>
    </div>
</div>
<?php
include_once("./admin_tail.php");
?>