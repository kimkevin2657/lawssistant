<?php
if(!defined('_MALLSET_')) exit;
if(!$_GET['no']) 
	alert("잘못된 정보입니다.");

	$lg = sql_fetch("select * from shop_ebook where no = '{$no}' ");
	$num = $lg['bpage'];

	$backbtn = "<a href='./design.php?code=ebook_list' class=\"btn_small\">이전페이지</a>";
?>

<style>
	#fpopuplist .fpul_h{border-top: 1px solid #aeaeae; float: left; width: 33%;height:32px;line-height:32px;background-color:#f1f1f1;}
	#fpopuplist .fpul_h div{float: left; width: 33%;text-align:center;border-left:1px solid #e4e5e7;}
	#fpopuplist .fpul_d{border-top: 1px solid #e4e5e7; float: left; width: 33%;}
	#fpopuplist .fpul_d div{float: left; width: 33%;height:150px;text-align:center;border-left: 1px solid #e4e5e7;line-height:150px;}
	#fpopuplist > div > div:nth-child(3n+1) div:first-child{border-left:none;}
	#fpopuplist .fpul_d div:first-child{width:15%;}
	#fpopuplist .fpul_d div:nth-child(2){width:60%;}
	#fpopuplist .fpul_d div:nth-child(3){width:24%;}

</style>
<?php echo $backbtn; ?>
<h2><?php echo $lg['title']; ?></h2>
<form name="fpopuplist" id="fpopuplist" method="post" action="./design/ebook_list_update.php" onsubmit="return fpopuplist_submit(this);">
	<input type="hidden" name="q1" value="<?php echo $q1; ?>">
	<input type="hidden" name="page" value="<?php echo $page; ?>">
	<div style="width:100%;">
		<div class="fpul_h">
			<div style="width:15%">페이지</div>
			<div style="width:60%">대표이미지</div>
			<div style="width:24%">관리</div>
		</div>
		<div class="fpul_h">
			<div style="width:15%">페이지</div>
			<div style="width:60%">대표이미지</div>
			<div style="width:24%">관리</div>
		</div>
		<div class="fpul_h">
			<div style="width:15%">페이지</div>
			<div style="width:60%">대표이미지</div>
			<div style="width:24%;border-right:none;">관리</div>
		</div>
		<?php
			for($i=0; $i<$lg['bpage']; $i++)	{
				$row = sql_fetch("select * from shop_ebook_view where no = '{$no}' and bpage = '{$num}' ");
				$img = "";
				if($row['img']){
					$img = "<img src='/data/ebook/{$row['img']}' width='100'>";
				}

				$s_upd = "<a href='./design.php?code=ebook_view_form&bpage=$num&no=$no' class=\"btn_small\">관리</a>";
				$d_upd = "<a href='./design.php?code=ebook_view_del&bpage=$num&no=$no' class=\"btn_small\" onclick=\"if(!confirm('삭제 하시겠습니까?')){return false;}\" >삭제</a>";

				$bg = 'list'.$i%2;
		?>
		<div class="<?php echo $bg; ?> fpul_d">
			<div><?php echo $num--; ?></div>
			<div>&nbsp;<?php echo $img; ?></div>
			<div><?php echo $s_upd; ?>&nbsp;<?php echo $d_upd; ?></div>
		</div>
		<?php }
		if($i==0)
				echo '<div>자료가 없습니다.</div>';
		?>
	</div>
<script>
function fpopuplist_submit(f)
{
    if(!is_checked("chk[]")) {
        alert(document.pressed+" 하실 항목을 하나 이상 선택하세요.");
        return false;
    }

    if(document.pressed == "선택삭제") {
        if(!confirm("선택한 자료를 정말 삭제하시겠습니까?")) {
            return false;
        }
    }

    return true;
}
</script>
