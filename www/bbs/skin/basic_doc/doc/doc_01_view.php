<?php
include_once("_common.php");
include_once("/home/pulo/www/bbs/skin/basic_doc/skin.function.php");

// 지출내역 불러오기
$list = get_subdata($wr_id);
?>
<div id="doc_view">
    <table>
        <colgroup>
            <col width="6%" />
            <col width="*" />
            <col width="13%" />
            <col width="20%" />
        </colgroup>
        <thead>
            <tr>
                <th>순번</th>
                <th>적요</th>
                <th>금액</th>
                <th>비고</th>
            </tr>
        </thead>
        <tbody class="work_item">
            <?php 
            $sum = 0;
            for($i=0; $i < count($list); $i++) { ?>
            <tr>
                <th><?php echo $i+1; ?></th>
                <td class="td_left"><?php echo $list[$i]['doc_sub']; ?></td>
                <td class="td_right"><?php echo number_format($list[$i]['doc_cost']); ?></td>
                <td><?php echo $list[$i]['doc_etc']; ?></td>
            </tr>
            <?php
            $sum += $list[$i]['doc_cost'];
            } ?>
        </tbody>
        <tfoot>
            <tr>
                <th colspan="2">합계</th>
                <th class="td_right"><?php echo number_format($sum); ?></th>
                <th></th>
            </tr>
        </tfoot>
    </table>
</div>