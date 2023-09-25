<?php
if(!defined('_MALLSET_')) exit;

Theme::get_theme_part(MS_THEME_PATH,'/aside_my.skin.php');


$linePoint = LinePoint::getList($page);
?>

<div id="con_lf">
    <h2 class="pg_tit">
        <span><?php echo $ms['title']; ?></span>
        <p class="pg_nav">HOME<i>&gt;</i>마이페이지<i>&gt;</i><?php echo $ms['title']; ?></p>
    </h2>

    <p class="pg_cnt">
        <em>총 <?php echo number_format($linePoint->total_count); ?>건</em>의 추천점수내역이 있습니다.
    </p>

    <div class="tbl_head02 tbl_wrap">
        <table>
            <colgroup>
                <col width="140">
                <col>
                <col width="100">
                <col width="100">
            </colgroup>
            <thead>
            <tr>
                <th scope="col">일시</th>
                <th scope="col">내용</th>
                <th scope="col">적립점수</th>
                <th scope="col">차감점수</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sum_point1 = $sum_point2 = $sum_point3 = 0;

            while( $line = $linePoint->next() ) :
                $point1 = $point2 = 0;
                if($line->current->lp_point > 0) {
                    $point1 = '+' .number_format($line->current->lp_point);
                    $sum_point1 += $line->current->lp_point;
                } else {
                    $point2 = number_format($line->current->lp_point);
                    $sum_point2 += $line->current->lp_point;
                }
                ?>
                <tr>
                    <td class="tac"><?php echo $line->current->lp_datetime; ?></td>
                    <td><?php echo $line->current->lp_content; ?></td>
                    <td class="td_num"><?php echo $point1; ?></td>
                    <td class="td_num"><?php echo $point2; ?></td>
                </tr>
                <?php
            endwhile;
            if($linePoint->total_count == 0)
                echo '<tr><td colspan="4" class="empty_table">자료가 없습니다.</td></tr>';
            else {
                if($sum_point1 > 0)
                    $sum_point1 = "+" . number_format($sum_point1);
                $sum_point2 = number_format($sum_point2);
            }
            ?>
            </tbody>
            <tfoot>
            <tr>
                <th scope="row" colspan="2">소계</th>
                <td class="td_num fc_red"><?php echo $sum_point1; ?></td>
                <td class="td_num fc_red"><?php echo $sum_point2; ?></td>
            </tr>
            <tr>
                <th scope="row" colspan="2">보유쇼핑포인트</th>
                <td class="td_num fc_red" colspan="2"><?php echo number_format($member['line_point']); ?></td>
            </tr>
            </tfoot>
        </table>
    </div>

    <?php
    echo get_paging($config['write_pages'], $page, $linePoint->total_page, $_SERVER['SCRIPT_NAME'].'?page=');
    ?>
</div>
