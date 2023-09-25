<?php
$sub_menu = '790420';
include_once('./_common.php');


if(!$sch_year){
$sch_year = date("Y");
$sch_month = date("m");
$sch_day = date("Y-m-d");
}

$g5['title'] = '월별예약현황';
include_once (MS_ADMIN_PATH.'/admin.head.php');
?>

<style>
    .rmsrealtimes {}
    .rmsrealtimes li {border:1px solid #dfdfdf;margin:4px 0 0}
    .rmsrealtimes.todays li {font-weight:bold}
    #wrap-list {margin:10px 0}
</style>


<div class="local_desc01 local_desc">
    <p>
        달력으로 전체 예약현황을 확인합니다.
    </p>
</div>

<div class="tbl_head01 tbl_wrap">

    <div id="wrap-calendar"></div>
    <div id="wrap-list"></div>

    <script type="text/javascript">
    <!--
        var sch_day = '<?php echo $sch_day?>';

        jQuery(document).ready(function () {

            $(document).on('click', '.get-ajax-page .pg_page', function(e) {
                e.preventDefault();
                var url = $(this).attr('href');
                _wzSetList(url);
            });

            _wzSetCanlendar('', '');
            _wzSetList('./wz_bookingC_prm_admin/wzb_booking_calendar.list.php');
        });

        // 달력랜더링
        function _wzSetCanlendar(sch_year, sch_month) {
            $.ajax({
                type : 'post' ,
                async : true ,
                url : './wz_bookingC_prm_admin/wzb_booking_calendar.monthly.php',
                dataType : 'html' ,
                timeout : 30000 ,
                cache : false ,
                data: {'sch_year': sch_year, 'sch_month': sch_month, 'sch_day': sch_day} ,
                success : function(response, status, request) {
                    $('#wrap-calendar').html(response);
                }
            });
        }

        // 목록랜더링
        function _wzSetList(url) {
            $.ajax({
                type : 'get' ,
                async : true ,
                url : url,
                dataType : 'html' ,
                timeout : 30000 ,
                cache : false ,
                success : function(response, status, request) {
                    $('#wrap-list').html(response);
                }
            });
        }
    //-->
    </script>

</div>


<?php
include_once (MS_ADMIN_PATH.'/admin.tail.php');
?>

