<?php
if(!defined('_TUBEWEB_')) exit;

Theme::get_theme_part(TB_THEME_PATH,'/aside_my.skin.php');


$linePoint = LinePoint::getList($page);
?>

<div id="con_lf">
	<h2 class="pg_tit">
		<span><?php echo $tb['title']; ?></span>
		<p class="pg_nav">HOME<i>&gt;</i>마이페이지<i>&gt;</i><?php echo $tb['title']; ?></p>
	</h2>

	<p class="pg_cnt">
		<em>총 <?php echo number_format($linePoint->total_count); ?>건</em>의 점수내역이 있습니다.
	</p>

	<div class="tbl_head02 tbl_wrap">
		<table>
		<colgroup>
			<col width="140">
			<col>
			<col width="100">
		</colgroup>
		<thead>
		<tr>
			<th scope="col">일시</th>
			<th scope="col">라인ID</th>
			<th scope="col">점수</th>
		</tr>
		</thead>
		<tbody>
		<?php
		while( $line = $linePoint->next() ) :
		?>
		<tr>
			<td class="tac"><?php echo $line->regDate(); ?></td>
			<td><?php echo $line->displayLineId(); ?></td>
			<td class="td_num"><?php echo $line->point(); ?></td>
		</tr>
		<?php
	    endwhile;

		if($linePoint->total_count == 0)
			echo '<tr><td colspan="3" class="empty_table">자료가 없습니다.</td></tr>';
		?>
		</tbody>
		<tfoot>
		<tr>
			<th scope="row" colspan="2">총점수</th>
			<td class="td_num fc_red" colspan="2"><?php echo number_format($member['total_line_cnt']); ?></td>
		</tr>
		</tfoot>
		</table>
	</div>
	<?php
	echo get_paging($config['write_pages'], $page, $linePoint->total_page, $_SERVER['SCRIPT_NAME'].'?page=');
	?>
</div>
