<?php
/**
 * 조직도 신규
 */
if(!defined('_MALLSET_')) exit;

$inc_level = 0;
$dwn_level = 0;
$mb_id     = ( $member['id'] == $encrypted_admin ) ? $encrypted_admin : 'admin';
if( $_REQUEST['mb_id'] ) $mb_id = $_REQUEST['mb_id'];
else $mb_id = 'admin';
$tr_name   = encrypted_admin();
?>

<style>
    ul.top-seed {

    }
    ul.top-seed li {
        float:left;
    }
    ul.top-seed li a {
        display:inline-block;
        padding:5px;
        border:1px solid #369;
        margin:2px;
    }
    ul.top-seed li.current a {
        background:#369;
        color:#fff;
    }
    ul.top-seed:before{
        content: 'Top Seed';
        float:left;
        padding:5px;
        margin:1px;
        color:#369;
    }
    ul.top-seed:after{
        clear:both;
        content : ' ';
    }
</style>
<ul class="top-seed">
    <?php
    $result = sql_query("select id, name
     from shop_member where (id in ('".'a0000'."') or id  in (
     select a.id from shop_member a, (select pt_id, count(1) child_cnt from shop_member group by pt_id having count(1) > 0 ) b
 where a.id = b.pt_id and a.pt_id = '{$encrypted_admin}'
     )) 
    order by case when id = '".'a0000'."' then 0 else 1 end");//, '{$encrypted_admin}')");

    $result = sql_query("select id, name from shop_member where id in ('".'a0000'."')");
    while($mset = sql_fetch_array($result)){
        ?>
        <li class="<?php echo ($mb_id == $mset['id']) ? 'current' : '';?>">
            <a href="?code=orgchart&mb_id=<?php echo $mset['id']; ?>"><?php echo $mset['id']; ?>(<?php echo $mset['name'];?>)</a>
            <a href="/admin/pop_orgchart.php?mb_id=<?php echo $mset['id']; ?>" target="_blank">새창</a>
        </li>
        <?php
    }
    ?>
</ul>
<table style="width:100%;border:1px solid #d5d5d5;">
<tr>
	<td style="padding:10px;">
		<div style="overflow-x:auto;">
            <?php
            $orgChart = new Organization($mb_id);
            $orgChart->chart();
            ?>
		</div>
	</td>
</tr>
</table>
