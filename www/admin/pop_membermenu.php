<?php
/**
 * Created by PhpStorm.
 * User: bjkim
 * Date: 2019-02-16
 * Time: 15:18
 */
?>
<li><a href="./pop_memberform.php?mb_id=<?php echo $mb_id; ?>">회원정보수정</a></li>
<?php if(is_seller($mb_id)) { ?>
<li><a href="./pop_sellerform.php?mb_id=<?php echo $mb_id; ?>">공급사정보수정</a></li>
<li><a href="./pop_sellerorder.php?mb_id=<?php echo $mb_id; ?>">공급사판매내역</a></li>
<?php } ?>
<li><a href="./pop_memberorder.php?mb_id=<?php echo $mb_id; ?>">주문내역</a></li>
<li><a href="./pop_memberpoint.php?mb_id=<?php echo $mb_id; ?>">쇼핑포인트내역</a></li>
<?php if(is_minishop($mb_id)) { ?>
<li><a href="./pop_memberpay.php?mb_id=<?php echo $mb_id; ?>">가맹점수수료내역</a></li>
<li><a href="./pop_memberlinepoint.php?mb_id=<?php echo $mb_id; ?>">가맹점 점수내역</a></li>
    <?php if( defined('USE_SHOPPING_PAY') && USE_SHOPPING_PAY ) : ?>
<li><a href="./pop_membersppoint.php?mb_id=<?php echo $mb_id; ?>">쇼핑페이내역</a></li>
    <?php endif; ?>
<?php } ?>
