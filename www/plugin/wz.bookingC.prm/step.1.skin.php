<?php
if(!defined('_MALLSET_')) exit;

$wzp_default_today = wz_get_addday(MS_TIME_YMD, $wzdc['cp_term_day']);
$sch_day = preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $_GET['sch_day']) ? $_GET['sch_day'] : $wzp_default_today;

if(!$sch_year){
$sch_year = date("Y");
$sch_month = date("m");
$sch_day = date("Y-m-d");
}

$id = $_GET['id'];
$rm_ix = $_GET['rm_ix'];

include_once(WZB_PLUGIN_PATH.'/navi_reserv.php');
include_once(WZB_PLUGIN_PATH.'/inc.popup.php'); // 팝업창
?>
<style>
	#con_lf{width:1200px;}
	.pg_tit {display:none;}
</style>
<div class="row">
    <div class="col-md-6">

        <div id="wrap-calendar" class="wrap-calendar"></div>

        <div class="panel panel-default hidden-xs hidden-sm">
            <?php
            ob_start();
            ?>
            <!-- List group -->
            <ul class="list-group">
                <li class="list-group-item"><i class="fa fa-check-circle-o" aria-hidden="true"></i> 달력에서 원하시는 예약일을 선택하시면 이용가능한 정보가 출력됩니다.</li>
                <li class="list-group-item"><i class="fa fa-check-circle-o" aria-hidden="true"></i> 예약 전 반드시 주의사항을 숙지하시기 바랍니다.</li>
            </ul>

            <div class="panel-body">
                <p><?php echo $wzpconfig['pn_con_notice'];?></p>
            </div>

            <?php
            $con_notice = ob_get_contents();
            ob_end_flush();
            ?>
        </div>

    </div>
    <div class="col-md-6">

        <div class="pager lead">
            <div class="">예약일 : <span id="select-date-text"><?php echo date('Y년 m월 d일', strtotime(str_replace('-', '', $sch_day)));?></span></div>
        </div>

        <form method="post" name="wzfrm"    id="wzfrm" action="<?php echo WZB_STATUS_URL;?>" onsubmit="return getNext(document.forms.wzfrm);">
        <input type="hidden" name="mode"    id="mode"       value="step2" />
        <input type="hidden" name="sch_day" id="sch_day"    value="" />
        <input type="hidden" name="store_mb_id" id="store_mb_id"    value="<? echo $id; ?>" />
        <input type="hidden" name="rm_ix" id="rm_ix"    value="<? echo $rm_ix; ?>" />
        <div id="hidden-parms"></div>

        <div class="row">

            <div class="col-md-12">

                <div id="room-select-list"></div>

            </div>

        </div>

        <div class="row">

            <div class="col-md-12 btn-group-justified" role="group">
                <div class="btn-group" role="group">
                    <button type="submit" class="btn btn-lg btn-success">다음단계 <i class="fa fa-chevron-right fa-sm"></i></button>
                </div>
            </div>

        </div>

        </form>

        <div class="clearfix" style="height:15px;"></div>

        <div class="panel panel-default hidden-md hidden-lg">
            <?php echo $con_notice;?>
        </div>


    </div>
</div>

<script type="text/javascript">
<!--
    var sch_day = '<?php echo $sch_day?>';
    var arr_rm_ix = new Array('<?php echo $rm_ix?>');

    jQuery(document).ready(function () {

        _wzSetCanlendar('', '');

        // 날짜클릭
        $(document).on('click', '.tbl-canlendar .wz-ajax-html', function(e) {
            e.preventDefault();
            $('.tbl-canlendar td').removeClass('danger');
            sch_day = $(this).attr('data-date');
            $(this).parent().addClass('danger');
            $('#select-date-text').text(wz_get_hangul_date(sch_day));
            getRoomList();
            $('#hidden-parms').html('');
        });

        // 시간선택
        $(document).on('click', '.cal_rm_list', function(e) {

            var f       = document.forms.wzfrm;
            var rm_ix   = $(this).attr('data-rm-ix');
            var rmt_ix  = $(this).attr('data-rmt-ix');
            var rm_time = $(this).attr('data-time');       
            var rm_idex = parseInt($(this).attr('data-index'));

            if ($(this).hasClass('closed')) {
                e.preventDefault();
                return;
            }
            else {

                f.sch_day.value = sch_day;
                
                var rm_exist = false;
                var rmt_cnt = 1;
                $("input[name='rmt_ix[]']").each(
                    function(){
                        if (this.value == rmt_ix) {
                            rm_exist = true;
                        }
                        rmt_cnt++;
                    }
                );
                
                if (rm_exist) { // 선택 취소처리.
                    $('.rm_selected_'+rm_idex).remove();
                    $(this).removeClass('active');
                }
                else {
                    $('#hidden-parms').append('<input type="hidden" name="rm_ix[]" class="rm_selected_'+rm_idex+'" value="'+rm_ix+'" />');
                    $('#hidden-parms').append('<input type="hidden" name="rmt_ix[]" class="rm_selected_'+rm_idex+'" value="'+rmt_ix+'" />');
                    $('#hidden-parms').append('<input type="hidden" name="rm_time[]" class="rm_selected_'+rm_idex+'" value="'+rm_time+'" />');
                    $(this).addClass('active');
                }
            }

        });

        // 레벨제한이 있는것을 클릭했을경우
        $(document).on('click', '.bx-times .limit-level', function(e) {
            e.preventDefault();
            var lname = $(this).attr('data-level');
            alert("["+lname+"]등급 회원만 예약이 가능한 서비스 입니다.");
        });

        getRoomList();

    });

    // 달력랜더링
    function _wzSetCanlendar(sch_year, sch_month) {
        $.ajax({
            type : 'get' ,
            async : true ,
            url : '<?php echo WZB_PLUGIN_URL?>/ajax.calendar.php',
            dataType : 'html' ,
            timeout : 30000 ,
            cache : false ,
            data: {'cp_code': cp_code, 'sch_year': sch_year, 'sch_month': sch_month, 'sch_day': sch_day, 'sch_type': 'check', 'id':'<? echo $id; ?>', 'rm_ix':'<? echo $rm_ix; ?>'} ,
            success : function(response, status, request) {
                $('#wrap-calendar').html(response);
            }
        });
    }

    // 이용정보랜더링
    function getRoomList() {
        $.ajax({
            type : 'post' ,
            async : true ,
            url : '<?php echo WZB_PLUGIN_URL?>/step.1.skin.room.php' ,
            dataType : 'html' ,
            data: {'cp_code': cp_code, 'sch_day': sch_day, 'arr_rm_ix': arr_rm_ix, 'id':'<? echo $id; ?>', 'rm_ix':'<? echo $rm_ix; ?>'} ,
            beforeSend : function() {
                $('#room-select-list').html('<div class="text-center" style="padding:20px 0"><img src="<?php echo WZB_PLUGIN_URL?>/img/loading.gif" /></div>');
            } ,
            success : function(response, status, request) {
                $('#room-select-list').html(response);
            }
        });
    }
    function getNext(f) {
        
        var rmt_cnt = $("input[name='rmt_ix[]']").length;
        
        if (rmt_cnt < 1) {
            alert("시간을 선택해주세요.");
            return false;
        }
    }

//-->
</script>

