<?php include_once('./_commmon.php'); ?>
<?php if( $default['de_card_test']) : ?>
<script type="text/javascript" charset="EUC-KR" src="http://testpg.easypay.co.kr/webpay/EasypayCard_Web.js"></script>
<?php else : ?>
<script type="text/javascript" charset="EUC-KR" src="https://pg.easypay.co.kr/webpay/EasypayCard_Web.js"></script>
<?php endif; ?>
<script>

    function f_submit()
    {


        $.ajax({
            url: tb_url+"/shop/easypay/request.php",
            type: "POST",
            data: $('#forderform').serialize(),
            dataType: "json",
            async: false,
            cache: false,
            success: function(data) {
                console.log( data );
                if( data.error == '') {

                    var frm_pay = document.forderform;

                    frm_pay.target = "_self";
                    frm_pay.action = "<?php echo $order_action_url; ?>";
                    frm_pay.submit();

                    document.forderform.submit();
                } else {
                    alert(data.error);
                }
            },
            error: function(data) {
                console.log(data);
            }

        });
    }
</script>
