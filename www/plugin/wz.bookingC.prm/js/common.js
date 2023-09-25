function wz_get_hangul_date(data) {

    var format = /[12][0-9]{3}-[0-9]{2}-[0-9]{2}/;
    if(data.search(format) == -1)
        return data;
        
    var _year = data.substr(0,4);
    var _month= data.substr(5,2);
    var _day = data.substr(8,2);
    
    return _year+'년 '+_month+'월 '+_day+'일';
}

$(function() {

	// ajax 윈도우
	$('.wz-ajax-win').magnificPopup({
		type: 'ajax', // inline
		overflowY: 'scroll',
	}); 
	// iframe 윈도우
	$('.wz-iframe-win').magnificPopup({
		type: 'iframe',
		overflowY: 'scroll',
	});
	// modal 윈도우
	$('.wz-modal-win').magnificPopup({
		type: 'ajax', // inline
		preloader: false,
		overflowY: 'scroll',
		modal: true
	});

});