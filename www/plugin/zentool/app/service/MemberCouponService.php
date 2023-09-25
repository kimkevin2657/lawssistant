<?php


namespace App\service;


class MemberCouponService
{
    public static function get_balance($mb_id)
    {
        $row = sql_fetch("select count(1) cnt from shop_coupon_log where mb_id = '{$mb_id}'  and mb_use='0' and ( (cp_inv_type='0' and (cp_inv_edate = '9999999999' or cp_inv_edate > curdate())) or (cp_inv_type='1' and date_add(`cp_wdate`, interval `cp_inv_day` day) > now()) )  ");
        return $row['cnt'];
    }

    public static function pub_able_coupons($mb_id = '')
    {
        $result = sql_query("select * from shop_coupon where cp_type = 6");
        while($row = sql_fetch_array($result)) {
            ?>
            <button type="button" class="btn_small grey marl10 holder--coupon-pub" data-mb-id="<?php echo $mb_id; ?>" data-cp-id="<?php echo $row['cp_id']; ?>">쿠폰발행</button>
            <?php echo $row['cp_subject']; ?>
            <?php
        }
    }

    public static function pub_script()
    {
        ?>
        <script>
            (function($){
                $(document).ready(function () {
                    $('.holder--coupon-pub').on('click', function(){
                        if(!confirm('쿠폰 발행 하시겠습니까?')) return;
                        var token = get_ajax_token();
                        if(!token) {
                            alert("토큰 정보가 올바르지 않습니다.");
                            return false;
                        }
                        var cp_id = $(this).data('cpId');
                        var mb_id = $(this).data('mbId');

                        $.ajax({
                            type: "POST",
                            url: tb_admin_url+"/ajax.coupon.php",
                            data: { mb_id: mb_id, cp_id: cp_id },
                            cache: false,
                            async: false,
                            dataType: "json",
                            success: function(data) {
                                if(data.error) {
                                    alert(data.error);
                                    return false;
                                }

                                document.location.reload();
                            }
                        });
                    });
                });
            }(jQuery));
        </script>
        <?php
    }

    public static function pub_coupon($cp_id, $mb_id)
    {
        $mb = get_member($mb_id);
        $cp = sql_fetch("select * from shop_coupon where cp_id = '{$cp_id}'");
        insert_used_coupon($mb['id'], $mb['name'], $cp);
    }
}
