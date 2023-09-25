<?php
if(!defined('_MALLSET_')) exit;

$user_nm        = isset($_POST['user_nm'])      ? clean_xss_tags(trim($_POST['user_nm']))   : "";
$user_hp        = isset($_POST['user_hp'])      ? clean_xss_tags(preg_replace('/[^0-9]/', '', $_POST['user_hp']))   : "";
$user_no        = isset($_POST['user_no'])      ? clean_xss_tags(preg_replace('/[^0-9]/', '', $_POST['user_no']))   : "";

// 회원인 경우
$sql_common = " from {$g5['wzb_booking_table']} where cp_ix = '{$wzdc['cp_ix']}' ";

if ($is_member) {
    $sql_common .= " and mb_id = '{$member['id']}' ";
}
else if ($user_nm && $user_hp) { // 비회원인 경우 예약자명과 핸드폰번호가 넘어왔다면
   
    if ($user_no) { 
        $sql_common .= " and od_id = '{$user_no}' ";
    } 

    $query = " select od_id, bk_time, bk_ip {$sql_common} and bk_name = '$user_nm' and replace(bk_hp, '-', '') = '$user_hp' order by bk_ix desc ";
    $row = sql_fetch($query);
    if ($row['od_id']) {
        $uid = md5($row['od_id'].$row['bk_time'].$row['bk_ip']);
        set_session('ss_orderview_uid', $uid);
        goto_url(WZB_STATUS_URL.'&mode=orderdetail&od_id='.$row['od_id'].'&amp;uid='.$uid); // 비회원은 최근 예약만 조회가능.
    }
    else {
        alert('존재하지 않는 예약자 정보입니다.');
    }
}
else { // 그렇지 않다면 로그인으로 가기
    goto_url(WZB_STATUS_URL.'&mode=ordercheck');
}

$qstr .= '&amp;bo_table='.$bo_table.'&mode=orderlist';
if (isset($_REQUEST['page'])) { // 리스트 페이지
    $page = (int)$_REQUEST['page'];
    if ($page)
        $qstr .= '&amp;page=' . urlencode($page);
} else {
    $page = '';
}

// 객실정보
$query = " select count(*) as cnt {$sql_common} ";
$row = sql_fetch($query);
$total_count = $row['cnt'];
$rows = $config['cf_page_rows'];
$total_page  = ceil($total_count / $rows);  // 전체 페이지 계산
if ($page < 1) $page = 1; // 페이지가 없으면 첫 페이지 (1 페이지)
$from_record = ($page - 1) * $rows; // 시작 열을 구함

unset($arr_order);
$arr_order = array();
$query = "select * {$sql_common} order by bk_ix desc  ";

//echo $query;

$res = sql_query($query);
while($row = sql_fetch_array($res)) {
    $query2 = "select * from {$g5['wzb_booking_room_table']} where od_ix = '{$row['od_ix']}' "; // 객실정보
    $res2 = sql_query($query2);
    while($row2 = sql_fetch_array($res2)) { 
        $row['rm'][] = $row2;
    }
    $arr_order[] = $row;
}
$cnt_order = count($arr_order);
if ($res2) sql_free_result($res2);
if ($res) sql_free_result($res);

include_once(WZB_PLUGIN_PATH.'/navi_reserv.php');
?>

<style>
	#con_lf{width:1200px;}
</style>

<div class="panel panel-default">

	<div class="panel-heading"><strong><i class="fa fa-hotel fa-lg"></i> 예약객실안내</strong></div>
	<div class="table-responsive">
		<table class="table form-group form-group-sm table-bordered font-color-gray">
        <thead>
		<tr>
            <th scope="col">예약정보</th> 
            <th scope="col">예약자명</th>
            <?php if ($wzpconfig['pn_is_pay']) {?><th scope="col">이용요금</th><?php } ?>
            <th scope="col">예약상태</th>
            <?php if ($wzpconfig['pn_is_pay']) {?><th scope="col">결제방식</th><?php } ?>
        </tr>
        </thead>
        <tbody>
        <?php 
        if ($cnt_order > 0) { 
            for ($z = 0; $z < $cnt_order; $z++) { 

            $uid = md5($arr_order[$z]['od_id'].$arr_order[$z]['bk_time'].$arr_order[$z]['bk_ip']);
            $cnt_room = count($arr_order[$z]['rm']);
            ?>
            <tr>
                <td data-title="예약정보">
                    <a href="javascript:;" onclick="show_page('<?php echo $uid;?>', '<?php echo $arr_order[$z]['od_id'];?>');" class="linker" title="예약번호 <?php echo $arr_order[$z]['od_id'];?> 의 상세정보 확인">
                    <?php echo $arr_order[$z]['bk_subject'];?> <i class="fa fa-search-plus"></i>
                    </a>
                    <? if($member['grade'] != 1){ ?>
                        <? if($arr_order[$z]['bk_status'] == "완료"){ ?>
                            <p class="padt3"><a href="<?php echo MS_SHOP_URL; ?>/orderreview2.php?bk_ix=<?php echo $arr_order[$z]['bk_ix']; ?>&booking_id=<?php echo $arr_order[$z]['store_mb_id']; ?>" onclick="win_open(this, 'winorderreview', '650', '530','yes');return false;" class="btn_ssmall bx-white">리뷰 작성</a></p>
                        <? } ?>
                    <? }else{ ?>
                        <p class="padt3"><a href="<?php echo MS_SHOP_URL; ?>/orderreview2.php?bk_ix=<?php echo $arr_order[$z]['bk_ix']; ?>&booking_id=<?php echo $arr_order[$z]['store_mb_id']; ?>" onclick="win_open(this, 'winorderreview', '650', '530','yes');return false;" class="btn_ssmall bx-white">리뷰 작성</a></p>
                    <? } ?>
                </td>
                <td data-title="예약자명"><?php echo $arr_order[$z]['bk_name'];?></td>
                <?php if ($wzpconfig['pn_is_pay']) {?><td data-title="이용요금" style="text-align:right"><?php echo number_format($arr_order[$z]['bk_price']);?> 원</td><?php } ?>
                <td data-title="예약상태"><?php echo $arr_order[$z]['bk_status'];?></td>
                <?php if ($wzpconfig['pn_is_pay']) {?><td data-title="결제방식"><?php echo $arr_order[$z]['bk_payment'];?></td><?php } ?>
            </tr>
            <?php 
            }
        } 
        else {
            ?>
            <tr>
                <td colspan="7" style="text-align:center">
                    예약내역이 존재하지 않습니다.
                </td>
            </tr>
            <?php 
        }
        ?>
        </tbody>
        </table>

    </div>
</div>

<?php 
echo wz_get_paging(G5_IS_MOBILE ? $config['cf_mobile_pages'] : $config['cf_write_pages'], $page, $total_page, '?'.$qstr.'&amp;page=');    
?>

<div class="panel panel-default">
    <div class="panel-heading"><strong><i class="fa fa-file-text-o fa-lg"></i> 이용규정안내</strong></div>
    <div class="panel-body">
    
        <?php if ($wzpconfig['pn_con_info']) { ?>
        <div class="bs-callout bs-callout-info">
            <h4>기본예약안내</h4>
            <?php echo $wzpconfig['pn_con_info'];?>
        </div>
        <?php } ?>

        <?php if ($wzpconfig['pn_con_checkinout']) { ?>
        <div class="bs-callout bs-callout-warning">
            <h4>입/퇴실 안내</h4>
            <?php echo $wzpconfig['pn_con_checkinout'];?>
        </div>
        <?php } ?>

        <?php if ($wzpconfig['pn_con_refund'] && $wzpconfig['pn_is_pay']) { ?>
        <div class="bs-callout bs-callout-warning">
            <h4>환불규정</h4>
            <?php echo $wzpconfig['pn_con_refund'];?>
        </div>
        <?php } ?>

    </div>

</div>



<script type="text/javascript">
<!--
    function show_page(uid, od_id) {
        $.ajax({
            type: 'POST',
            url: '<?php echo WZB_PLUGIN_URL?>/order.session.php',
            dataType: 'json',
            data: {'uid': uid, 'od_id': od_id},
            cache: false,
            async: false,
            success: function(json) {
                location.href = '<?php echo WZB_STATUS_HTTPS_URL?>&mode=orderdetail&uid='+uid+'&od_id='+od_id;
            }
        });
    }
//-->
</script>