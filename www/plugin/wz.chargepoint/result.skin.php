<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

?>

<?php include_once(WPOT_PLUGIN_PATH.'/navi_reserv.php'); ?>
<?php include_once(WPOT_PLUGIN_PATH.'/order.info.skin.php'); ?>

<div class="btn-group-justified" role="group">
    <div class="btn-group" role="group">
        <button type="button" class="btn btn-success" onclick="location.href='<?php echo WPOT_STATUS_URL;?>';"><i class="fa fa-home fa-sm"></i> 첫화면으로</button>
    </div>
</div>