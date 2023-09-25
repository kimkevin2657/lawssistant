$(function(){
	$(Grid.list).jqGrid({
		url : Grid.editUrl,
        datatype : "json",
		colNames:['번호', 'wr_id', 'edit', '문서종류', '작성자명', '제목', '등록일시', '금액', '작성자', '결재1', '결재2', '결재3', '열람권한'],
		colModel : [
			{ name:'num',index:'num', width:50, align:'center', sortable: false, formatter:'integer'},
			{ name:'wr_id',index:'a.wr_id', key:true, hidden:true },
			{ name:'edit',index:'edit', key:false, hidden:true },
			{ name:'ca_name',index:'a.ca_name', width:80, align:'center' },
			{ name:'wr_name',index:'b.mb_name', width:80, align:'center' },
			{ name:'wr_subject',index:'a.wr_subject', width:210, align:'left' },
			{ name:'wr_datetime',index:'a.wr_datetime', width:140, align:'center' },
			{ name:'wr_10',index:'a.wr_10', width:80, align:'right', formatter:'integer' },
			{ name:'wr_3',index:'a.wr_3', width:55, align:'center' },
			{ name:'wr_5',index:'a.wr_5', width:55, align:'center' },
			{ name:'wr_7',index:'a.wr_7', width:55, align:'center' },
			{ name:'wr_9',index:'a.wr_9', width:55, align:'center' },
			{ name:'view',index:'view', width:55, align:'center', sortable: false },
		],
		loadui: 'enable',
		height: 450,
		autowidth:true,
		rowNum : 25, // 기본 페이지 줄수
		formatoptions: {decimalSeperator : ','}, // 숫자에 콤마 찍기
		rowList : [10,15,20,25,30,35,40,50,70,100],
		emptyrecords : "<span class='txt_red txt_bold'>[북모아] 검색된 자료가 없습니다.</span>",
		pager: Grid.pager, // 페이지정보 위치
		sortname: 'a.wr_id',  // 기본 정렬 
		sortorder: "desc", // 기본정렬순서
		viewrecords: true, // 우측 하단 레코드 정보.(페이지등/총갯수)
		shrinkToFit: false, // 데이타 가로 길이 제한 없앰
		gridview : true,		
		multiselect: true, // 체크박스 (다중선택)
		scroll:true,
		editurl:Grid.editUrl, // 선택삭제
		jsonReader: { repeatitems : false },
		mtype:'POST', // 전송방식 (검색시 또는 페이지 이동시
		postData: {
			"fd" : $("#fd").val(),
			"td" : $("#td").val(),
			"ca_name" : $("#ca_name").val(),			
			"sfl" : $("#sfl").val(),
			"stx" : $("#stx").val()
		},
		caption: "&nbsp;",
		ondblClickRow: function (rowId, iRow, iCol, e) {  // 더블클릭 이벤트
			//var cm = $(Grid.list).jqGrid('getGridParam','colModel');
			var vi = $(Grid.list).jqGrid('getRowData', rowId).view;
			var mode = vi.replace(/[^\uAC00-\uD7AF\u1100-\u11FF\u3130-\u318F]/gi,""); // 한글만 추출
			if(mode=="열람불가") {
				alert("열람 권한이 없습니다.");
				return false;
			}

			// 작성자 문서 승인 상태 체크
			var vi = $(Grid.list).jqGrid('getRowData', rowId).wr_3;
			var edit = $(Grid.list).jqGrid('getRowData', rowId).edit;
			var mode = vi.replace(/[^\uAC00-\uD7AF\u1100-\u11FF\u3130-\u318F]/gi,"");
			if(edit=="false" && mode=="미결재") {
				alert("작성자가 문서를 승인하지 않아 열람할 수 없습니다.");
				return false;
			}
			
			ahref(rowId,'','');
		},
		onCellSelect: function (rowId, columnIndex, cellcontent, e) { // 클릭(선택)
		   
			/*
			var cm = $(Grid.list).jqGrid('getGridParam','colModel');
			switch (cm[columnIndex].name) {
				case "st_uid" : //작업번호 클릭
					ahref(rowId,'','');
					break;
				case "st_section" : // 구분 클릭
					var st_uid = $(Grid.list).jqGrid('getRowData', rowId).st_uid;
					insure_edit(st_uid);
					break;
				case "car_no" :
					ahref(rowId,'','');
					break;	
				case "mb_name" :
					var mb_id = $(Grid.list).jqGrid('getRowData', rowId).mb_id; // 선택한자료의 회원아이디불러오기
					carinfo_select(mb_id, '');
					break;			
			}
			*/
		},
		loadComplete : function(data) {
			var hap = "<span class='hap_title'>합계금액 : </span><span class='hap'>"+data.hap+ "원</span>";
			$("#category_info .cate_right").html(hap);
			// 자료 불러오기 성공시 resetn 값과 records 값 체크
			if(data.resetn=="Y" && data.records>0 && data.page==1) {
				$("#excel, #print").show(); // 엑셀저장버튼 보여줌
			} else {
				$("#excel, #print").hide(); // 엑셀저장버튼 숨김
			}
			
			// if(data.records==0) { // 검색자료 0
			// 	popup_msg("검색된 자료가 없습니다");
			// }

			// 캡션 부분에 페이지 정보 넣기.
			if(data.records>0) {
				$(".ui-jqgrid-title").text("자료수 : "+number_format(data.records)+" 개 (현재 "+data.page+" 페이지 / 총 "+data.total+" 페이지)    상세보기 - 더블클릭").css("font-weight","normal");
			} else {
				$(".ui-jqgrid-title").text("검색된 자료가 없습니다.").css({"font-weight":"bold"});
			}

			// 카테고리 출력
			// var cate_btns = "";
			// var active = "";
			// $("#category_info .cate_left").empty();
			// $.each(data.category_list, function(idx, data) {
			// 	if(data.name == data.para.ca_name) { active = 'active'; }
			// 	cate_btns += "<button name='button' class='cateBtns "+active+"' data-id='"+data.name+"'>"+data.name+" ("+data.cnt+")</button>";
			// });
			// $("#category_info .cate_left").append(cate_btns);

		},
		
	});

	// Grid 하단에 기능 버튼 추가.
	$(Grid.list).jqGrid('navGrid',Grid.pager,
		{ edit:false, add:false, del:true, search:false, refresh:true },
		{}, // edit
		{}, // add
		{ 
			width:500, // 자료 삭제시 창 크기
			height:150
		}, // del
		{},// search
		{} //refresh

	);

	// 검색 - 초기화버튼 클릭시
	$("#reset").click(function() {
		$(Grid.search+" input, select")
			.not(':button, :submit, :reset, :hidden')
			.val('')
			.removeAttr('checked')
			.removeAttr('selected');
		$("#sfl option:eq(0)").attr("selected", "selected");
		Searching('reset');
		//popup_msg("초기화 완료");
		
	});
	
	// 검색버튼 클릭시
	$('#btn_submit').click(function() {
		Searching(); // 검색(POST)
	});

	// 검색 - 검색어 입력후 엔터키 입력시 검색 실행
	$("#stx").keypress(function (e) {
		if (e.which == 13){
			$("#btn_submit").trigger("click");
			return false;
		}
	});

	$(document).on("click", ".cateBtns", function() {
		var cate = $(this).attr("data-id");
		$("#ca_name").val(cate);
		$("#btn_submit").trigger("click");		
		$(".cateBtns").removeClass("active");
		$(this).addClass("active");
	});

	/* 달력 끝 */
	$("#fd, #td").datepicker({
		changeMonth: true,
		changeYear: true,
		dateFormat: "yy-mm-dd",
		showButtonPanel: true,
		yearRange: "c-10:c+5",
		minDate: "",
		maxDate: "",
		beforeShow: function() {
			setTimeout(function(){
				$('.ui-datepicker').css('z-index', 100);
			}, 0);
		}
	});
	/* 달력 끝 */

	$(".sch_M").click(function() {
		$(".sch_M").removeClass("active");
		$(this).addClass("active");
	});

	
});
// 검색 - 검색박스 POST 실행
function Searching(z) {
	$(Grid.list).jqGrid('clearGridData');
	$(Grid.list).jqGrid('setGridParam', { 
		mtype:"POST",
		postData : {
			"fd" : $("#fd").val(),
			"td" : $("#td").val(),
			"ca_name" : $("#ca_name").val(),			
			"sfl" : $("#sfl").val(),
			"stx" : $("#stx").val()
		}
	});
	$(Grid.list).trigger("reloadGrid",
		[{current: false}] //// 새로고침시 현재 체크상태 유지(true) / 비유지(false)
	);
}; 

// 글수정
function ahref(wr_id) {
	var string = $(Grid.form).serialize();
	//location.href=g5_bbs_url+"/board.php?bo_table="+Grid.bo_table+"&wr_id="+wr_id+"&"+string;
	//ocation.href=g5_bbs_url+"/board.php?bo_table="+Grid.bo_table+"&wr_id="+wr_id+"&"+string;
	$.ajax({
		type : 'POST',
		url : Grid.board_skin_url+"/ajax.pretty_url.php",
		data : {
			"bo_table" : Grid.bo_table,
			"wr_id" : wr_id,
			"qstr" : string
		},
		dataType: "json",
		async: false,
		cache: false,
		error : function(error) {
			//alert("Error!");
		},
		success : function(data) {
			if(data) {
				var url = data.replace(/&amp;/g, '&');			
				location.href = url;
			}
		},
		complete : function() {
			//alert("complete!");    
		}
	});
}

