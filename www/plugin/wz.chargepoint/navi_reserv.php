<div class="navi-wrap">
    <ul class="nav nav-tabs">
        <li role="presentation" class="<?php echo (substr($mode,0,4) == 'step' || $mode == '' ? 'active' : '');?>"><a href="<?php echo WPOT_STATUS_URL;?>">Oh!포인트 충전</a></li>
        <li role="presentation" class="<?php echo (substr($mode,0,5) == 'order' || substr($mode,0,6) == 'result' ? 'active' : '');?>"><a href="<?php echo WPOT_STATUS_URL;?>&mode=orderlist">Oh!포인트 충전내역</a></li>
    </ul>
    <p class="button-list">
        <?php if ($is_admin) {?>
        <!-- a href="<?php echo G5_ADMIN_URL;?>/wz_chargepoint_admin/order_list.php" class="navbar-link">관리자</a-->
        <?php } ?>
    </p>
</div>

<div class="clearfix" style="height:10px;"></div>