<?php
if(!defined('_MALLSET_')) exit;

include_once(WZB_PLUGIN_PATH.'/navi_reserv.php');

$action_url = WZB_STATUS_HTTPS_URL;
?>

<div class="row">
<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <div class="panel-title">예약자정보를 입력해주세요.</div>
        </div>
        <div class="panel-body">
            <form class="form-horizontal form-group-sm" method="post" name="wzfrm" id="wzfrm" onsubmit="return getNext();">
            <input type="hidden" name="mode" id="mode" value="orderlist" />
            <input type="hidden" name="cp_code" id="cp_code" value="<?php echo $cp_code;?>" />
            
                <div class="form-group has-feedback">
                    <label for="user_nm" class="col-sm-3 control-label">예약자명</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="user_nm" id="user_nm" value="<?php echo $member['mb_name'];?>" maxlength="20" placeholder="예약자명">
                        <span class="fa fa-user form-control-feedback"></span>
                        <div id="helpblock_bk_name" class="help-block"><small class="text-dotum">실명으로 입력해주세요</small></div>
                    </div>
                    
                </div>

                <div class="form-group has-feedback">
                    <label for="user_hp" class="col-sm-3 control-label">핸드폰</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="user_hp" id="user_hp" maxlength="20" placeholder="핸드폰번호">
                        <span class="fa fa-phone form-control-feedback"></span>
                    </div>
                </div>

                <div class="form-group has-feedback">
                    <label for="user_hp" class="col-sm-3 control-label">예약번호</label>
                    <div class="col-sm-9">
                        <input type="text" class="form-control" name="user_no" id="user_no" maxlength="16" placeholder="예약번호">
                        <span class="fa fa-tag form-control-feedback"></span>
                        <div id="helpblock_bk_name" class="help-block"><small class="text-dotum">예약번호를 입력하지않을경우 가장 최근 예약정보가 출력됩니다.</small></div>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-sm-offset-3 col-sm-9">
                        <button type="submit" class="btn btn-success btn-block"><i class="fa fa-calendar-check-o fa-lg"></i> 예약정보확인</button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>
</div>

<script type="text/javascript">
<!--
    function getNext() { 
        var f = document.forms.wzfrm;

        if (!f.user_nm.value) {
            alert("예약자명을 입력해주세요.");
            f.user_nm.focus();
            return false;
        }
        if (!f.user_hp.value) {
            alert("핸드폰번호를 입력해주세요.");
            f.user_hp.focus();
            return false;
        }

        f.action = "<?php echo $action_url;?>";
        f.target = "_self";


    }
//-->
</script>


