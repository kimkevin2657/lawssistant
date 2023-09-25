<div class="navi-wrap">
    <ul class="nav nav-tabs">
        <li role="presentation" class="<?php echo (substr($mode,0,4) == 'step' || $mode == '' ? 'active' : '');?>"><a href="<?php echo WZB_STATUS_URL;?>">실시간예약</a></li>
        <li role="presentation" class="<?php echo (substr($mode,0,5) == 'order' ? 'active' : '');?>"><a href="<?php echo WZB_STATUS_URL;?>&mode=orderlist">예약확인/취소</a></li>
    </ul>
    <p class="button-list">
        <?php if ($is_admin) {?>
        <a href="<?php echo G5_ADMIN_URL;?>/wz_bookingC_prm_admin/wzb_booking_list2.php?code=wzb_booking_list" class="navbar-link">관리자</a>
        <?php } ?>
    </p>
</div>

<div class="clearfix" style="height:10px;"></div>