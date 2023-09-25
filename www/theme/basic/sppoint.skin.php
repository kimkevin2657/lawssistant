<?php
if(!defined('_MALLSET_')) exit;

Theme::get_theme_part(MS_THEME_PATH,'/aside_my.skin.php');


$shoppingPay = ShoppingPay::getList($page);
?>

<div id="con_lf">
    <h2 class="pg_tit">
        <span><?php echo $ms['title']; ?></span>
        <p class="pg_nav">HOME<i>&gt;</i>마이페이지<i>&gt;</i><?php echo $ms['title']; ?></p>
    </h2>

    <p class="pg_cnt">
        <em>총 <?php echo number_format($linePoint->total_count); ?>건</em>의 쇼핑포인트내역이 있습니다.
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
                <th scope="col">지급쇼핑포인트</th>
                <th scope="col">사용쇼핑포인트</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $sum_point1 = $sum_point2 = $sum_point3 = 0;

            while( $pay = $shoppingPay->next() ) :
                $point1 = $point2 = 0;
                if($pay->current->sp_price > 0) {
                    $point1 = '+' .number_format($pay->current->sp_price);
                    $sum_point1 += $pay->current->sp_price;
                } else {
                    $point2 = number_format($pay->current->sp_price);
                    $sum_point2 += $pay->current->sp_price;
                }
                ?>
                <tr>
                    <td class="tac"><?php echo $pay->current->sp_datetime; ?></td>
                    <td><?php echo $pay->current->sp_content; ?></td>
                    <td class="td_num"><?php echo $point1; ?></td>
                    <td class="td_num"><?php echo $point2; ?></td>
                </tr>
                <?php
            endwhile;
            if($shoppingPay->total_count == 0)
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
                <td class="td_num fc_red" colspan="2"><?php echo number_format($member['sp_point']); ?></td>
            </tr>
            </tfoot>
        </table>
    </div>

    <?php
    echo get_paging($config['write_pages'], $page, $shoppingPay->total_page, $_SERVER['SCRIPT_NAME'].'?page=');
    ?>
</div>
