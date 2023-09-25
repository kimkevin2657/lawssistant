<?php
if(!defined('_MALLSET_')) exit;

$inc_level = 0;
$dwn_level = 0;
$mb_id     = encrypted_admin();
$tr_name   = encrypted_admin();

function mb_tree($mb_recommend, $level, $line_array, $pre_cnt=0)
{
	global $inc_level, $dwn_level, $mb_id, $tr_name;

	$sql = "select count(*) as cnt from shop_member where pt_id='$mb_recommend' ";
	$row = sql_fetch($sql);
	$cnt = $row['cnt'];

	$sql = "select name,id,grade from shop_member where id='$mb_recommend' ";
	$row = sql_fetch($sql);

//	$row['plain_id'] = $row['id'];

	$blank = "";
	for($i=0;$i<$level-1;$i++) {
		if($line_array[$i]==0)
			$blank .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
		else
			$blank .= "<img align='absbottom' src='".MS_ADMIN_URL."/img/line.gif'>&nbsp;";
	}

	if($level==0){
		;
	} else {
		if($pre_cnt==0) {
			if($cnt==0)
				$blank .= "<img align='absbottom' src='".MS_ADMIN_URL."/img/join1.gif'>&nbsp;";
			else
				$blank .= "<img id='$mb_recommend"."_img"."' align='absbottom' src='".MS_IMG_URL."/minus1.gif'>&nbsp;";
		} else {
			if($cnt==0)
				$blank .= "<img align='absbottom' src='".MS_ADMIN_URL."/img/join2.gif'>&nbsp;";
			else
				$blank .= "<img id='$mb_recommend"."_img"."' align='absbottom' src='".MS_ADMIN_URL."/img/minus2.gif'>&nbsp;";
		}
	}

	if($inc_level==1 && $level > 1){
		$tr_name = $mb_id;
		$inc_level=0;
	}
	if($dwn_level){
		$tr_name = $mb_recommend;
		$dwn_level = 0;
	}
	echo("<tr name='$tr_name'");
	if($level!=1){	echo(" style='display:block;'"); }
	echo("><td nowrap height='17'>$blank$row[mb_name] ");

	$r = sql_fetch("select * from shop_member where id='$mb_recommend' ");
	if($r['gender']=='M') { $img_name="man"; } else { $img_name="guir"; }

	$r['plain_id'] = $r['id'];

	$mb_grade = get_grade($r['grade']);

	$line = "&nbsp;&nbsp;<img src='".MS_IMG_URL."/sub/tree_line.gif' align='absmiddle'>&nbsp;&nbsp;";

	$t_cnt = sel_count("shop_member","where pt_id='$mb_recommend'");
	if($t_cnt > 0) { $t_color = 'blue'; } else { $t_color = 'red'; }
	if($r['grade']>1  || $mb_recommend ==encrypted_admin()){
		echo("<img src='".MS_ADMIN_URL."/img/$img_name.gif' width='15' height='15' align='absmiddle'>&nbsp;<a href=\"javascript:win_open('pop_memberform.php?mb_id=$r[id]','pop_member','1200','600','yes');\"><font color='$t_color'>($t_cnt)</font>&nbsp;{$r[name]}{$line}".$mb_recommend."{$line}<font color='ed8e06'>{$mb_grade}</font>{$line}휴대폰:<font color='#939393'>{$r[cellphone]}</font>{$line}가입:<font color='#939393'>".substr($r['reg_time'],0,10)."</font>{$line}로그인수:<font color='#939393'>{$r[login_sum]}</font></a>");
	} else {
		echo("<img src='".MS_ADMIN_URL."/img/$img_name.gif' width='15' height='15' align='absmiddle'>&nbsp;<a href=\"javascript:win_open('pop_memberform.php?mb_id=$r[id]','pop_member','1200','600','yes');\"><font color='cccccc'>{$r[name]}{$line}".$mb_recommend."{$line}{$mb_grade}{$line}휴대폰:{$r[cellphone]}{$line}가입:".substr($r['reg_time'],0,10)."{$line}로그인수:{$r[login_sum]}</font></a>");
	}

	echo("</td></tr>");

	if($cnt <= 0) return;

	$pre_cnt = $cnt;
	$inc_level = 1;

	$sql = "select id from shop_member where pt_id='$mb_recommend' order by index_no asc ";
	$result = sql_query($sql);
	while($row = sql_fetch_array($result)){
		if($inc_level==1)
			$mb_id = $mb_recommend;
			$pre_cnt--;

		if($pre_cnt==0)
			$line_array[$level]=0;
		else
			$line_array[$level]=1;

		mb_tree($row['id'],$level+1,$line_array,$pre_cnt);
	}

	$dwn_level=1;
}
?>

<table style="width:100%;border:1px solid #d5d5d5;">
<tr>
	<td style="padding:10px;">
		<div style="overflow-x:auto;">
			<table class="wfull">
			<?php
			$line_array=array();
			mb_tree(encrypted_admin(),0,$line_array);
			?>
			</table>
		</div>
	</td>
</tr>
</table>
