<?php
if(!defined('_MALLSET_')) exit;

$pg_title = "조직도 트리회원조회";
include_once("./admin_head.sub.php");
?>

<?php
$inc_level = 0;
$dwn_level = 0;
$mb_id     = $member['id'];
$tr_name   = $member['id'];

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

<?php
include_once("./admin_tail.sub.php");
?>
