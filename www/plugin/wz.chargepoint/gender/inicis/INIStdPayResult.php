<?php
if (!defined('_GNUBOARD_')) exit; // 개별 페이지 접근 불가

$g5['title'] = 'KG 이니시스 결제';
include_once(G5_PATH.'/head.sub.php');

$exclude = array();

echo '<form name="forderform" method="post" action="'.$order_action_url.'" autocomplete="off">'.PHP_EOL;

foreach($data as $key=>$value) {
    if(!empty($exclude) && in_array($key, $exclude))
        continue;

    if(is_array($value)) {
        foreach($value as $k=>$v) {
            echo '<input type="hidden" name="'.$key.'['.$k.']" value="'.$v.'">'.PHP_EOL;
        }
    } else {
        echo '<input type="hidden" name="'.$key.'" value="'.$value.'">'.PHP_EOL;
    }
}

echo '</form>'.PHP_EOL;
?>

<div id="pay_working">
    <span style="display:block; text-align:center;margin-top:120px"><img src="<?php echo WPOT_PLUGIN_URL; ?>/gender/inicis/img/loading.gif" alt=""></span>
    <span style="display:block; text-align:center;margin-top:10px; font-size:14px">주문완료 중입니다. 잠시만 기다려 주십시오.</span>
</div>

<script type="text/javascript">
function setPAYResult() {
    setTimeout( function() {
        document.forderform.submit();
    }, 300);
}
window.onload = function() {
    setPAYResult();
}
</script>