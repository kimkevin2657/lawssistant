<?php
if(!defined('_MALLSET_')) exit;


init_lotto2(); // 공준비
$gong = get_lotto2(); // 하나 뽑기

if($gong == $row['point1']){
	$deg = rand(0, 45)+1800;
}elseif($gong == $row['point2']){
	$deg = rand(46, 90)+1800;
}elseif($gong == $row['point3']){
	$deg = rand(91, 135)+1800;
}elseif($gong == $row['point4']){
	$deg = rand(136, 180)+1800;
}elseif($gong == $row['point5']){
	$deg = rand(181, 225)+1800;
}elseif($gong == $row['point6']){
	$deg = rand(226, 270)+1800;
}elseif($gong == $row['point7']){
	$deg = rand(270, 315)+1800;
}elseif($gong == $row['point8']){
	$deg = rand(315, 360)+1800;
}
// 한면에 deg 값은 0~60 까지이다. 6개의 항목이라면 360 즉 360도를 나타낸다. 30+720
// 40을 맞추고 싶다면 180~240 까지 이므로 임의의 약 190에 2바퀴를 추가로 돌린다 가정하면 360x2 720에 +190을 한 910을 입력해주면 된다.
//$deg = rand(180, 240)+720;
?>
<script type="text/javascript" src="/js/slideshow.min.js"></script>
<script type="text/javascript" src="/js/jquery.transit.min.js"></script>
<script type="text/javascript">//<![CDATA[ 
$(function(){
        window.WHEELOFFORTUNE = {

            cache: {},

            init: function () {
                console.log('controller init...');

                var _this = this;
                this.cache.wheel = $('.wheel');
                this.cache.wheelMarker = $('.marker');
                this.cache.wheelSpinBtn = $('.wheel');

                //mapping is backwards as wheel spins clockwise //1=win
//                this.cache.wheelMapping = [50,10,30,50,10,30].reverse();
                this.cache.wheelMapping = [<?=$row['point8'];?>,<?=$row['point7'];?>,<?=$row['point6'];?>,<?=$row['point5'];?>,<?=$row['point4'];?>,<?=$row['point3'];?>,<?=$row['point2'];?>,<?=$row['point1'];?>];

                this.cache.wheelSpinBtn.on('click', function (e) {
                    e.preventDefault();
                    if (!$(this).hasClass('disabled')) _this.spin();

                });

                //reset wheel
                this.resetSpin();

                //setup prize events
               // this.prizeEvents();
            },

            spin: function () {
                console.log('spinning wheel');

                var _this = this;


                // reset wheel
                this.resetSpin();

                //disable spin button while in progress
                this.cache.wheelSpinBtn.addClass('disabled');

                /*
                    Wheel has 10 sections.
                    Each section is 360/10 = 36deg.
                */
				/*
                var deg = 1500 + Math.round(Math.random() * 1500),
                    duration = 6000; //optimal 6 secs
				*/
				
				
				$.post(
					"/m/shop/wheel_per.php",
					{mode:"per"},
					function(data){
						var rulldeg = data;
						$('input[name=enc]').attr('value',rulldeg);
					}
				);

				if(!$('input[name=enc]').val()){
					var rulldeg = <?php echo $deg; ?>;
					$('input[name=enc]').attr('value',rulldeg);
				}
				var deg = $('input[name=enc]').val();
                    duration = 6000; //optimal 6 secs
				

                _this.cache.wheelPos = deg;

                //transition queuing
                //ff bug with easeOutBack
                this.cache.wheel.transition({
                    rotate: '0deg'
                }, 0)
                    .transition({
                    rotate: deg + 'deg'
                }, duration, 'easeOutCubic');

                //move marker
                _this.cache.wheelMarker.transition({
                    rotate: '0deg'
                }, 0, 'snap');

                //just before wheel finish
                setTimeout(function () {
                    //reset marker
                    _this.cache.wheelMarker.transition({
                        rotate: '0deg'
                    }, 300, 'easeOutQuad');
                }, duration - 500);

                //wheel finish
                setTimeout(function () {
                    // did it win??!?!?!
                    var spin = _this.cache.wheelPos,

//						degrees = spin % 360,
//                        percent = (degrees / 360) * 100,
//                        segment = Math.ceil((percent / 4)),  //divided by number of segments
//                        win = _this.cache.wheelMapping[segment - 1]; //zero based array

                        degrees = 360 - spin % 360,
                        percent = (degrees / 360) * 8,
                        segment = Math.floor(percent),  //divided by number of segments
                        win = _this.cache.wheelMapping[segment]; //zero based array


                    console.log('spin = ' + spin);
                    console.log('degrees = ' + degrees);
                    console.log('percent = ' + percent);
                    console.log('segment = ' + segment);
                    console.log('win = ' + win);

                    //display dialog with slight delay to realise win or not.
                    setTimeout(function () {
			$.post(
				"/m/shop/_process_wheel.php",
				{mode:"point",point:win},
				function(data){
					if(data == "OK"){
						alert('포인트 '+win+'점에 당첨되셨습니다!');
						self.location.reload();
					}
					else{
						alert("이미 참여 하였습니다.\r\n하루에 한번만 참여가능합니다.");
					}
				}
			);
			//window.open('http://form.jotformz.com/form/41336216871655?','_self',false);
                    }, 1500);

                    //re-enable wheel spin
                    _this.cache.wheelSpinBtn.removeClass('disabled');

                }, duration);

            },

            resetSpin: function () {
                this.cache.wheel.transition({
                    rotate: '0deg'
                }, 0);
                this.cache.wheelPos = 0;
            }

        }

        window.WHEELOFFORTUNE.init();
});//]]>  

</script>
<input type=hidden name="enc">


<div class="wrap_wheel">
     <!--div class="bg">
	<img src="<?php echo MS_MIMG_URL; ?>/wheel/bg3.png">
    </div-->
	<div class="ment2">		 
		<img src="<?php echo MS_MIMG_URL; ?>/wheel/wheel_ment.png" style="width:300px;">
	</div>
    
		<div class="wheel-wrap">
		<img class="wheel" src="<?php echo MS_MIMG_URL; ?>/wheel/img_wheel.png">
		<img class="marker" src="<?php echo MS_MIMG_URL; ?>/wheel/marker.png">
<div class="text-on-img">
  <div class="background-wrap">
    <div class="content">
      <span class="content1"><i class="fa fa-credit-card marr5"></i><?php echo $member['name']; ?>님의 포인트는 <a href="<?php echo MS_SHOP_URL; ?>/point.php"><?php echo display_point($member['point']); ?></a></span>
      <!--span class="content1"><i class="fa fa-credit-card marr5"></i> 현재 나의포인트는 3,000 P</span-->
      <span class="content1"><i class="fa fa-file-text-o fa-lg marr5"></i> 이벤트 참여방법</span>
      <span>*1일 1회만 참여가 가능합니다.</span>
      <span>*START 버튼을 클릭 하세요!.</span>
      <span>*룰렛 포인트가 적립됩니다.</span>
      <span>*포인트는 마이페이지에서 확인가능합니다.</span>
      
    </div>
  </div>
</div>
	</div>
</div> 
</div> 


