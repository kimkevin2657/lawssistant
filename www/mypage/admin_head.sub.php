<?php
if(!defined('_MALLSET_')) exit;
?>
<?php if($is_mypage) : ?>
<div id="con_lf">
    <h2 class="pg_tit">
        <span><?php echo ( empty( $pg_title ) ? $ms['title'] : $pg_title ); ?></span>
        <p class="pg_nav">HOME<i>&gt;</i>마이페이지
        <?php if( !empty($pg_navi) ) : ?>
            <i>&gt;</i><?php echo $pg_navi; ?>
        <?php endif; ?>
        <?php if( !empty($pg_title) ) : ?>
        <i>&gt;</i><?php echo $pg_title; ?>
        <?php endif; ?>
        </p>
    </h2>

    <div id="content">
<?php else : ?>

<div class="breadcrumb">
	<span>HOME</span> <i class="ionicons ion-ios-arrow-right"></i>
	<?php echo $pg_navi; ?> <i class="ionicons ion-ios-arrow-right"></i>
	<?php echo $pg_title; ?>
</div>

<div class="s_wrap">
	<h1><?php echo $pg_title; ?></h1>

<?php endif; ?>
