<?php
if(!defined('_MALLSET_')) exit;

include_once(WZB_PLUGIN_PATH.'/navi_reserv.php');
?>

<?php include_once(WZB_PLUGIN_PATH.'/order.info.skin.php')?>  

<style>
	#con_lf{width:1200px;}
</style>

<div class="btn-group-justified" role="group">
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-lg btn-success" onclick="location.href='<?php echo WZB_STATUS_URL;?>';"><i class="fa fa-home fa-sm"></i> 첫화면으로</button>
    </div>
</div>
