<?php
if(!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가


$linePoint = LinePoint::getList($page);
?>

<div id="point">
	<p id="sod_fin_no">
		총 <b class="fc_red"><?php echo number_format($linePoint->total_count); ?></b>건의 점수 내역이 있습니다.
	</p>

	<ul id="point_ul">
        <?php
        while( $line = $linePoint->next() ) :
        ?>
        <li>
            <div class="point_wrap01">
                <span class="point_date"><?php echo conv_date_format('y-m-d H시', $line->regDate()); ?></span>
                <span class="point_log"><?php echo $line->message(); ?></span>
            </div>
            <div class="point_wrap02">
                <span class="point_inout"><?php echo display_point($line->point(), '점'); ?></span>
            </div>
        </li>
        <?php
        endwhile;

        if($i == 0)
            echo '<li class="empty_list">자료가 없습니다.</li>';
        ?>
    </ul>

    <div id="point_sum">
        <div class="sum_row">
            <span class="sum_tit">촘점수</span>
            <b class="sum_val"><?php echo number_format($member['line_point']); ?></b>
        </div>
    </div>

    <?php 
	echo get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?page=');
	?>
</div>
