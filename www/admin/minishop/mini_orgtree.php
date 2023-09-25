<?php
/**
 * 조직도 신규
 */
if(!defined('_MALLSET_')) exit;

$inc_level = 0;
$dwn_level = 0;
$mb_id     = encrypted_admin();
$tr_name   = encrypted_admin();
?>

<table style="width:100%;border:1px solid #d5d5d5;">
<tr>
	<td style="padding:10px;">
		<div style="overflow-x:auto;">
            <?php
            $orgChart = new Organization($mb_id);
            $orgChart->tree();
            ?>
		</div>
	</td>
</tr>
</table>
