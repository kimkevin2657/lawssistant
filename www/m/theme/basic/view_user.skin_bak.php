<?php
if(!defined("_MALLSET_")) exit; // ���� ������ ���� �Ұ�
?>

<h2 class="pop_title">
	<?php echo $ms['title']; ?> <span class="fc_red">(<?php echo number_format($total_count); ?>)</span>
	<a href="javascript:cl_list();" class="btn_small bx-white">��ü��ǰ����</a>
</h2>

<div id="sit_review">
	<table class="tbl_review">
	<colgroup>
		<col width="80px">
		<col>
	</colgroup>
	<tbody>
	<tr>
		<td class="image"><?php echo get_it_image($gs_id, $gs['simg1'], 80, 80); ?></td>
		<td class="gname">
			<?php echo get_text($gs['gname']); ?>
			<p class="bold mart5"><?php echo mobile_price($gs_id); ?></p>
		</td>
	</tr>
	</tbody>
	</table>

	<?php
	echo "<ul>\n";
	for($i=0; $row=sql_fetch_array($result); $i++)
	{
		$len = strlen($row['writer_s']);
		$str = substr($row['writer_s'],0,3);
		$tmp_name  = $str.str_repeat("*",$len - 3);
		$tmp_date  = date("Y-m-d", $row['wdate']);
		$tmp_score = $gw_star[$row['score']];

		$hash = md5($row['index_no'].$row['wdate'].$row['writer_s']);

		echo "<li class=\"lst\">\n";
		echo "<span class=\"lst_post\">{$row['memo']}</span>\n";
		echo "<span class=\"lst_h\"><span class=\"fc_255\">{$tmp_score}</span>\n";
		echo "<span class=\"fc_999\"> / {$tmp_name} / {$tmp_date}";

		if(is_admin() || ($member['id'] == $row['writer_s'])) {
			echo "<a href=\"javascript:window.open('".MS_MSHOP_URL."/orderreview.php?gs_id=$row[gs_id]&me_id=$row[index_no]&w=u');\" class=\"marl10 tu fc_blk\">����</a>\n";			
			echo "<a href=\"".MS_MSHOP_URL."/orderreview_update.php?gs_id=$row[gs_id]&me_id=$row[index_no]&w=d&hash=$hash&p=1\" class=\"marl5 tu fc_blk itemqa_delete\">����</a>\n";
		}
		echo "</span>\n";
		echo "</li>\n";
	}

	if($i == 0) {
		echo "<li class=\"empty_list\">�ڷᰡ �����ϴ�.</li>\n";
	}

	echo "</ul>\n";

	get_paging($config['mobile_pages'], $page, $total_page, $_SERVER['SCRIPT_NAME'].'?'.$q1.'&page=');
	?>
	<div class="btn_confirm">
		<a href="javascript:window.open('<?php echo MS_MSHOP_URL; ?>/orderreview.php?gs_id=<?php echo $gs_id; ?>');" class="btn_medium">�����ı⾲��</a>
		<a href="javascript:window.close();" class="btn_medium bx-white">â�ݱ�</a>
	</div>
</div>

<script>
function cl_list(){
	opener.location.href = tb_mobile_shop_url+'/list.php?ca_id=<?php echo $ca[gcate]; ?>';
	window.close();
}

// ����
$(function(){
    $(".itemqa_delete").click(function(){
        return confirm("���� ���� �Ͻðڽ��ϱ�?\n\n�����Ŀ��� �ǵ����� �����ϴ�.");
    });
});
</script>
