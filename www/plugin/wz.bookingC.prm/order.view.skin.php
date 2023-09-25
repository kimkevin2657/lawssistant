<?php
if(!defined('_MALLSET_')) exit;

include_once(WZB_PLUGIN_PATH.'/navi_reserv.php');
?>

<?php include_once(WZB_PLUGIN_PATH.'/order.info.skin.php')?>  

<?php if ($is_member) {?>
<div class="btn-group-justified" role="group">
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-lg btn-success" onclick="location.href='<?php echo WZB_STATUS_URL;?>&mode=orderlist';"><i class="fa fa-list fa-sm"></i> 목록으로</button>
    </div>
</div>
<?php } ?>
