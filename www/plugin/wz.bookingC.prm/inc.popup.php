<?php
if(!defined('_MALLSET_')) exit;

// 팝업레이어
unset($arr_popup);
$arr_popup = array();
$query = "  select * from {$g5['wzb_corp_popup_table']} where cp_ix = '{$wzdc['cp_ix']}' and '".G5_TIME_YMDHIS."' between nw_begin_time and nw_end_time
                and nw_device IN ( 'both', '".(is_mobile() ? 'mobile' : 'pc')."' ) 
            order by nw_id asc ";
$res = sql_query($query);
while($row = sql_fetch_array($res)) { 
    $arr_popup[] = $row;
}
$cnt_popup = count($arr_popup);
if ($res) sql_free_result($res);

if ($cnt_popup > 0) { 
    foreach ($arr_popup as $key => $row) {

        // 이미 체크 되었다면 Continue
        if ($_COOKIE["wzb_pops_{$row['nw_id']}"])
            continue;
        ?>
        <div id="wzb_pops_<?php echo $row['nw_id'] ?>" class="wzb_pops" style="top:<?php echo $row['nw_top']?>px;left:<?php echo $row['nw_left']?>px">
            <div class="wzb_pops_con" style="width:<?php echo $row['nw_width'] ?>px;height:<?php echo $row['nw_height'] ?>px">
                <?php echo conv_content($row['nw_content'], 1); ?>
            </div>
            <div class="wzb_pops_footer">
                <button class="wzb_pops_reject wzb_pops_<?php echo $row['nw_id']; ?> <?php echo $row['nw_disable_hours']; ?>"><strong><?php echo $row['nw_disable_hours']; ?></strong>시간 동안 다시 열람하지 않습니다.</button>
                <button class="wzb_pops_close wzb_pops_<?php echo $row['nw_id']; ?>">닫기</button>
            </div>
        </div>
        <?php
    }
    ?>
    <script>
    $(function() {
        $(document).on('click', '.wzb_pops_reject', function() {
            var id = $(this).attr('class').split(' ');
            var ck_name = id[1];
            var exp_time = parseInt(id[2]);
            $('#'+id[1]).css('display', 'none');
            set_cookie(ck_name, 1, exp_time, g5_cookie_domain);
        });
        $(document).on('click', '.wzb_pops_close', function() {
            var idb = $(this).attr('class').split(' ');
            $('#'+idb[1]).css('display','none');
        });
    });
    </script>
    <?php
}
?>