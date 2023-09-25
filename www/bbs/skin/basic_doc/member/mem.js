$(function(){
	$(Grid.list).jqGrid({
		url : Grid.editUrl,
        datatype : "json",
		colNames:['번호', 'wr_id', '구분', '부서명', '회원아이디', '이름', '휴대전화', '일반전화', '팩스'],
		colModel : [
			{ name:'num',index:'num', width:50, align:'center', sortable: false, formatter:'integer'},
			{ name:'id_no',index:'id_no', width:40, align:'center', key:true, hidden:true },
			{ name:'mb_section',index:'mb_section', width:110, align:'center' },
			{ name:'mb_kind',index:'mb_kind', width:110, align:'center' },
			{ name:'mb_id',index:'mb_id', width:100, align:'center' },
			{ name:'mb_name',index:'mb_name', width:150, align:'center' },
			{ name:'mb_hp',index:'mb_hp', width:120, align:'center' },
			{ name:'mb_tel',index:'mb_tel', width:120, align:'center' },
			{ name:'mb_fax',index:'mb_fax', width:120, align:'center' },
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
			"ms" : $("#ms").val(),
			"mk" : $("#mk").val(),
			"sfl" : $("#sfl").val(),
			"stx" : $("#stx").val()
		},
		caption: "&nbsp;",
        ondblClickRow: function (rowId, iRow, iCol, e) {  // 더블클릭 이벤트
            memberadd(rowId);
			//ahref(rowId,'','');
			//ahref(rowId); // 셀 더블클릭시 글상세보기
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
		},
		
	});

	// 필수 : Grid 하단에 기능 버튼 추가.
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

	// 필수 : 검색 - 초기화버튼 클릭시
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
	
	// 필수 : 검색버튼 클릭시
	$('#btn_submit').click(function() {
		Searching(); // 검색(POST)
	});

	// 필수 : 검색 - 검색어 입력후 엔터키 입력시 검색 실행
	$("#stx").keypress(function (e) {
		if (e.which == 13){
			$("#btn_submit").trigger("click");
			return false;
		}
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
    
    $("#doc_list").click(function() {
		//alert('?bo_table='+Grid.bo_table);
		
        location.href = 'https://blingbeauty.shop/bbs/board.php?bo_table='+Grid.bo_table;
    });
});
// 필수함수 : 검색 - 검색박스 POST 실행 
function Searching(z) {
	$(Grid.list).jqGrid('clearGridData');
	$(Grid.list).jqGrid('setGridParam', { 
		mtype:"POST",
		postData : {
			"ms" : $("#ms").val(),
			"mk" : $("#mk").val(),
			"sfl" : $("#sfl").val(),
			"stx" : $("#stx").val()
		}
	});
	$(Grid.list).trigger("reloadGrid",
		[{current: false}] //// 새로고침시 현재 체크상태 유지(true) / 비유지(false)
	);
}; 

// 필수함수 : 글수정시 (페이지 이동)
function ahref(wr_id) {
	var string = $(Grid.form).serialize();
	location.href=g5_bbs_url+"/board.php?bo_table="+Grid.bo_table+"&id_mo="+wr_id+"&"+string;
}

// 필수함수 : 그리드영역 새로고침 : 팝업창 닫을때 새로고침할때 사용되는 함수.
function jqGridReload() {
    $(Grid.list).trigger("reloadGrid",
		[{current: true}] //// 새로고침시 현재 체크상태 유지(true) / 비유지(false)
	);
}

