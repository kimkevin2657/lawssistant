<?php
if(!defined('_MALLSET_')) exit;
?>

<h2 class="pg_tit">
	<span><?php echo $ms['title']; ?></span>
	<p class="pg_nav">HOME<i>&gt;</i><?php echo $ms['title']; ?></p>
</h2> 

<?php 
echo get_view_thumbnail(conv_content($co["co_content"], 1, 0), 1000);
?>
