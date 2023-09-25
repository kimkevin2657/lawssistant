<?php
if (!defined("_MALLSET_")) exit; // 개별 페이지 접근 불가

## 직원의 담당구분
$MB_SECTION = array(
    "0"=>"담당종류1",
    "1"=>"담당종류2",
    "2"=>"담당종류3",
    "3"=>"담당종류4",
    "4"=>"담당종류5"
);

## 결재 상태값
$APP_STATE = array(
    "0" => "미결재",
    "1" => "승인",
    "2" => "반려",
    "3" => "보류",
    "99" => "취소"
);

function br() {
    echo "<br/>";
}

Class Frm_Search {

	var $option_text;
	var $option_value;
	var $temp1;
	var $temp2;


	public function Month($A,$B,$fd,$td) {
		$aa = str_replace('.','',$A);
		$bb = str_replace('.','',$B);
		$total  = "<input type=\"text\" name='".$aa."' value='".$fd."' id=\"{$aa}\" class=\"{$aa} width80\" placeholder=\"검색시작일\" />";
		$total .= " ~ ";
		$total .= "<input type=\"text\" name=\"{$bb}\" value='".$td."' id=\"{$bb}\" class=\"{$bb} width80\" placeholder=\"검색종료일\" />";
		$total .= "&nbsp;<button type=\"button\" class='sch_M' onclick=\"SetToDays('".$A."', '".$B."'); \">오늘</button>&nbsp;";
		$total .= "<button type=\"button\" class='sch_M' onclick=\"SetPrevMonthDays('".$A."', '".$B."'); \">전월</button>&nbsp;";
		$total .= "<button type=\"button\" class='sch_M' onclick=\"SetCurrentMonthDays('".$A."', '".$B."');  \">당월</button>&nbsp;";
		$total .= "<button type=\"button\" class='sch_M' onclick=\"SetWeek_befor('".$A."', '".$B."'); \">지난주</button>&nbsp;";
		$total .= "<button type=\"button\" class='sch_M' onclick=\"SetWeek('".$A."', '".$B."'); \">이번주</button>";
		return $total;
	}

	function var_mode($m, $arr) {

		// 배열오류이면...
		if(!is_array($arr)) {
			$arr = array ("msg"=>"배열오류");
		 }
        
		$text = implode("|", $arr);
		$value = implode("|", array_keys($arr));
        
		switch($m) {
			case "A" : // key, value
				$t = $text;
				$v = $value;
			break;
			case "B" : // value, value
				$t = $text;
				$v = $text;
			break;
			default : // key, value
				$t = $text;
				$v = $value;
			break;
		}
		$this->option_text = $t;
		$this->option_value = $v;
	}

	// 검색박스 검색조건 셀렉트 박스 생성 (모드, 배열, name, id, class, val)
	function Select($s_title='', $s_name, $s_id='', $s_class='', $s_val, $required='') {

		if($s_title) {
			$data1 = explode("|", $s_title."|".$this->option_text);
			$data2 = explode("|", "|".$this->option_value);
		} else {
			$data1 = explode("|", $this->option_text);
			$data2 = explode("|", $this->option_value);
		}

		$this->temp1 = $data1;
		$this->temp2 = $data2;

		for($i=0; $i < count($data1); $i++){ $dataA[$i] = trim($data1[$i]); }
		for($i=0; $i < count($data2); $i++){ $dataB[$i] = trim($data2[$i]); }
        $opt = "";
        $opt .="<option value='' ".$selected.">== 선택 ==</option>";
        for($i=0; $i < count($data2); $i++){
			$selected = ( $s_val == $dataB[$i] )? "selected":"";
			$opt .="<option value='".$dataB[$i]."' ".$selected.">".$dataA[$i]."</option>";
        }
        
        if($s_id) {
            $id = "id='{$s_id}'";
        }

		$rst = "<select name='".$s_name."' {$id} class='".$s_class."' ".$required.">";
		$rst .= $opt;
		$rst .= "</select>";
		return $rst;
	}

	function radio($s_title='', $s_name, $s_id='', $s_class='', $s_val) {

		$data1 = explode("|", $this->option_text);
		$data2 = explode("|", $this->option_value);
        
		$data1 = array_values($data1);
		$data2 = array_values($data2);

        $this->temp1 = $data1;
		$this->temp2 = $data2;

	  	for($i=0; $i < count($data1); $i++){
              $dataA[$i]=$data1[$i];
        }

  		for($i=0; $i < count($data2); $i++){
              $dataB[$i]=$data2[$i];
        }

		$result = "";
		for($i=0; $i < count($data2); $i++){
			$checked = ($s_val == $dataB[$i]) ? "checked" : "";
			$result .= "<input type='radio' name='$s_name' class='$s_class' id='{$s_id}[$i]' value='$dataB[$i]' $checked />";
			$result .= "<label for='{$s_id}[$i]'> $dataA[$i]</label>&nbsp;&nbsp;";
		}
		return $result;
	}

	function checkbox($s_name, $s_id, $s_class, $s_val){
		
		$data1 = explode("|", $this->option_text);
		$data2 = explode("|", $this->option_value);		
		
		for($i=0; $i < count($data1); $i++) {
			$dataA[$i] = $data1[$i];
		}

		for($i=0; $i < count($data2); $i++) {
			$dataB[$i] = $data2[$i];
		}

		$check = explode(",", $s_val);
		$result ="<ul>";
		$j=0;
	
		for($i=0; $i < count($data2); $i++) {
			if($dataB[$i] == $check[$j]) {
				$checked = "checked";
				$j++;
			} else {
				$checked="";
			}

			$result .= "<li><input type='checkbox' value='{$dataB[$i]}' name='{$s_name}[]' id='{$s_id}_{$i}' $checked class='{$s_class}'/>";
			$result .= "<label for='{$s_name}_{$i}'> {$dataA[$i]}</label></li>";
		}
		$result .="</ul>";

		return $result;
	}

}

function doc_affected_rows($link=null)
{
    global $tb;
    if(!$link)
        $link = $tb['connect_db'];
    if(function_exists('mysqli_affected_rows') && G5_MYSQLI_USE)
        return mysqli_affected_rows($link);
    else
        return mysql_affected_rows($link);
}

function db_check(){
	global $write_table, $is_admin, $bo_table;
	
	// approval_office 한개의 테이블만 검사
	$row = sql_fetch("check table {$write_table}_sub");
    if($row['Msg_type'] == 'Error') {
        
        echo "<div style='width:700px; margin:0px auto; padding:50px; line-height:150%; border:2px solid #555; border-radius:5px; background:#efefef;'>";
        
        if(!$is_admin) {
            echo "관리자 계정으로 로그인후 이용하시기 바랍니다.<br/>";
            echo "<a href='".G5_URL."/bbs/login.php'>로그인 화면으로..</a>";
            die();
        }

		echo "지출결의서에 필요한 DB TABLE이 설치되지 않았습니다.<br/>";
		echo "추가로 아래의 테이블이 생성되어야 합니다.";
		echo "<ul style='padding:15px;margin-left:20px;font-weight:bold;'>";
		echo "<li style='list-style:decimal;'>{$write_table}_sub (문서별 상세 내역 정보)</li>";
        echo "<li style='list-style:decimal;'>{$write_table}_member (직원현황)</li>";
        echo "<li style='list-style:decimal;'>{$write_table}_log (전자결재 로그)</li>";
        echo "<li style='list-style:decimal;'>{$write_table}_line (개인별 결재선 저장)</li>";
		echo "</ul>";
        echo "<a href='?bo_table={$bo_table}&pg=install' style='color:blue;margin-left:20px;font-weight:bold;font-size:13px;'>여기를 클릭하면 설치가 진행됩니다.</a>";
        echo "</div>";
        die();
    }
}

// SELECT BOX의 OPTION 만들기.
function xoption_str($data1,$data2,$option_name=''){
	$data1=explode("|",$data1);
	$data2=explode("|",$data2);
	for($i=0; $i < count($data1); $i++){$dataA[$i]=trim($data1[$i]);}
    for($i=0; $i < count($data2); $i++){$dataB[$i]=trim($data2[$i]);}
    $result = "";
	for($i=0; $i < count($data2); $i++){
		$selected=($option_name==$dataB[$i])? "selected":"";
		$result .="<option value='$dataB[$i]' $selected>$dataA[$i]</option>\n\t\t\t";
	}
	return($result);
}

// radio 버튼 만들기
function xradio($data1, $data2, $value, $name){
	$data1=explode("|",$data1);
	$data2=explode("|",$data2);
	for($i=0; $i < count($data1); $i++){$dataA[$i]=$data1[$i];}
    for($i=0; $i < count($data2); $i++){$dataB[$i]=$data2[$i];}
    $result = "";
	for($i=0; $i < count($data2); $i++){
		$checked=($value==$dataB[$i])? "checked":"";
        $result .="<label for='${name}[$i]' class='xradio'><input type='radio' name='$name' id='${name}[$i]' class='${name}' value='$dataB[$i]' $checked>$dataA[$i]</label>";
    }
   
	return($result);
}


// 이름 + 직급 표시하기
function mb_name ($mb_id) {

	global $write_table;
    
    $sql = "select
				*
			from
				{$write_table}_member
			where
                mb_id = '{$mb_id}' ";
    //echo $sql;
    $row = sql_fetch($sql, true);
    
    if($row['mb_name']) {
        $rst = $row['mb_name']." ".$row['mb_position'];
    } else {
        $rst = false;
    }

	return $rst;
}

// 이름 + 직급표시, 
function mb_myinfo ($mb_id) {

	global $write_table;
    
    $sql = "select
				*
			from
                {$write_table}_member
			where
                mb_id = '{$mb_id}' ";
                
    $row = sql_fetch($sql, true);
    
    $row['username'] = $row['mb_name']." ".$row['mb_position'];
    return $row;
}



// 결재진행현황 (ID, 결재상태|결재시간)
function approval($id, $z) {

	global $member, $APP_STATE;
    
    if($id) {
		$kind	= explode("|", $z); // 현재결재상태
		switch($kind[0]) {
			case "0":
					if($member['id']==$id) {
						$status = "<span class='txt_red'>{$APP_STATE[0]}</span><br/>";
					} else {
						$status = "<span class='txt_gray'>{$APP_STATE[0]}</span><br/>";
					}
				break;
			case "1":
				$status = "<span class='txt_blue'>{$APP_STATE[1]}</span><br/>";
				break;
			case "2":
				$status = "<span class='txt_red'>{$APP_STATE[2]}</span><br/>";
				break;
			case "3":
				$status = "<span class='txt_red'>{$APP_STATE[3]}</span><br/>";
				break;
        }
	} else {
		$status = "<span style='color:#cccccc;'>미지정</span>";
    }
    
    return $status;
}

// 결재현황 (결재자아이디, 결재상태, 전결재상태, 다음결재상태, 결재순번)
function approval2($id, $z, $x, $y, $seq) {

	global $member, $APP_STATE;
    
    $x		= explode("|", $x); // 이전 결재자의 승인상태
    $y      = explode("|", $y); // 다음 결재자의 승인상태
	$kind	= explode("|", $z); // 현재결재상태

    $app_btn = "";
    $status = "";

    switch($kind[0]) {

		case "0": // 결재상태 - 대기
			if($id) { // 결재자 지정되었을 경우에만..., 이전 결제자의 승인이 되었을 경우
				if($member['id'] == $id && $x[0] == "1") {
                    $status .= "<span class='td_hand txt_red'>{$APP_STATE[0]}</span><br/>";
                    $app_btn .= "<li><a href='#' onclick=\"app_pop('1', '{$seq}');\"  class='btn_app btn'>문서승인</a></li>";
				} else {
					$status .= "<span class='app_btn txt_gray'>{$APP_STATE[0]}</span><br/>";
                }
			}
            break;
            
		case "1": // 결재상태 - 승인
            $status .= "<span class='txt_blue'>{$APP_STATE[1]}</span>";

            // 현재 결재상태가 "승인"이고, 다음 결재상태가 대기중일때 
            if($y[0]==0) {
                $app_btn .= "<li><a href='#' onclick=\"app_pop('99', '{$seq}');\" class='btn_app btn'>승인취소</a></li>";
                $app_btn .= "<li><a href='#' onclick=\"app_pop('2', '{$seq}');\" class='btn_app btn'>문서반려</a></li>";
                $app_btn .= "<li><a href='#' onclick=\"app_pop('3', '{$seq}');\" class='btn_app btn'>문서보류</a></li>";
            }
            
            $status .= "<br/><span class='txt_gray'>".$kind[1]."</span>";
            break;
            
        case "2": // 결재상태 - 반려
			$status .= "<span class='app_btn txt_blue'>{$APP_STATE[2]}</span><br/>";
            $status .= "<span class='txt_gray'>".$kind[1]."</span>";
            
            // 현재 결재상태가 "승인"이고, 다음 결재상태가 대기중일때 
            if($y[0]==0) {
                $app_btn .= "<li><a href='#' onclick=\"app_pop('1', '{$seq}');\"  class='btn_app btn'>문서승인</a></li>";
                $app_btn .= "<li><a href='#' onclick=\"app_pop('99', '{$seq}');\" class='btn_app btn'>승인취소</a></li>";
                $app_btn .= "<li><a href='#' onclick=\"app_pop('3', '{$seq}');\" class='btn_app btn'>문서보류</a></li>";
            }
            break;
            
        case "3": // 결재상태 - 보류
			$status .= "<span class='app_btn txt_blue'>{$APP_STATE[3]}</span><br/>";
            $status .= "<span class='txt_gray'>".$kind[1]."</span>";
            
            // 현재 결재상태가 "승인"이고, 다음 결재상태가 대기중일때 
            if($y[0]==0) {
                $app_btn .= "<li><a href='#' onclick=\"app_pop('1', '{$seq}');\"  class='btn_app btn'>문서승인</a></li>";
                $app_btn .= "<li><a href='#' onclick=\"app_pop('99', '{$seq}');\" class='btn_app btn'>승인취소</a></li>";
                $app_btn .= "<li><a href='#' onclick=\"app_pop('2', '{$seq}');\" class='btn_app btn'>문서반려</a></li>";
            }
			break;
    }

    $response['status'] = $status;
    $response['app_btn'] = $app_btn;
    
    return $response;
}

// 등록되어 있는 부서 리스트
function get_teamlist() {
    
    global $write_table;
    
    $sql = "select
                distinct(mb_kind)
            from
                {$write_table}_member
            where
                (1)
            order by
                mb_kind asc ";

    //echo "team_sql:".$sql; 

    $result2 = sql_query($sql, true);
    $rst = array();
    $rst["전체공개"] = "전체 공개";
    while($rs=sql_fetch_array($result2)) {
        $rst[$rs['mb_kind']] = $rs['mb_kind'];
    }    
    return $rst;
}

function get_memInfo($id_no) {
    
    global $write_table;

    ## 직원정보 자료 수정
	$sql = "select
                *
            from
                {$write_table}_member
            where
                id_no = '{$id_no}' ";
    $rs = sql_fetch($sql);

    return $rs;
}

function get_memberlist() {

    global $write_table, $member;

    // 직원 리스트
    $sql = "select
                mb_id,
                mb_name,
                mb_position
            from
                {$write_table}_member
            where
                mb_id != '{$member['id']}'
            order by
                mb_name asc";
    //echo $sql;
    $result = sql_query($sql, true);
    
    $rst = array();    
    $rst["ALL"] = "전체 공개";
    while($row=sql_fetch_array($result)) {
        $rst[$row['mb_id']] = $row['mb_name']." ".$row['mb_position'];
    }

    return $rst;
}


// 지출내역 불러오기
function get_subdata($wr_id) {

    global $write_table;
    
    $sql = "select
                *
            from
                {$write_table}_sub
            where
                wr_id = '{$wr_id}'
            order by
                id_no asc";
    $result = sql_query($sql);
    
    $arr = array();
    while($row=sql_fetch_array($result)) {
        $arr[] = array(
            "id_no"     => $row['id_no'],            
            "bill_no"	=> $row['bill_no'],
            "doc_sub"	=> $row['doc_sub'],
            "doc_standard" => $row['doc_standard'],
            "doc_cnt"	=> $row['doc_cnt'],
            "doc_unit"	=> $row['doc_unit'],
            "doc_cost"	=> $row['doc_cost'],
            "doc_etc"	=> $row['doc_etc']
        );
    }

    return $arr;
}

// 결재선 및 참조 아이디 리스트
function check_id($arr) {

    global $is_admin, $member;
    
    $response = new stdClass();

    // 참조자 
    $wr_1 = json_decode($arr['wr_1'], true);
    $wr_1_count = ($wr_1 && count($wr_1) > 0) ? count($wr_1) : 0;
    $names = '';

    for($i=0; $i < $wr_1_count; $i++) {
        $response->name[]   = $wr_1[$i]['name'];
        $response->id[]     = $wr_1[$i]['id'];
        $response->check[]  = $wr_1[$i]['id'];
        $names .= ", ".$wr_1[$i]['name'];
    }
    $response->reperrer = substr($names, 1);

    // 참조 부서
    $wr_11 = json_decode($arr['wr_11'], true);
    $wr_11_count = ($wr_11 && count($wr_11) > 0) ? count($wr_11) : 0;
    $names = '';    
    for($i=0; $i < $wr_11_count; $i++) {
        $response->check[]   = $wr_11[$i];
    }

    // 등록자, 결재선, 참조자만 열람 가능
    $response->check[] = $arr['wr_2']; // 결재1
    $response->check[] = $arr['wr_4']; // 결재2
    $response->check[] = $arr['wr_6']; // 결재3
    $response->check[] = $arr['wr_8']; // 결재4
    $response->check[] = $arr['mb_id']; // 문서작성자
    
    
    // 참조자가 전체공개인지 체크
    $check = array_search("ALL", $response->check);
    $result = (!$check && $check !== 0) ? false : true ;

    if($result == false) {
        // 참조자 체크
        $check = array_search($member['id'], $response->check);
        $result = (!$check && $check !== 0) ? false : true ;
    }
    
    if($result == false) {
        // 참조부서가 전체공개인지 체크
        $check = array_search("ALL", $response->check);
        $result = (!$check && $check !== 0) ? false : true ;
    }

    if($result == false) {
        // 참조부서 체크
        $check = array_search($member['mb_kind'], $response->check);
        $result = (!$check && $check !== 0) ? false : true ;
    } 

    
    return $result;
}

// 결재선 및 참조 아이디 체크
function checkViewDoc($array) {
    
    global $is_admin;

    if(check_id($array)==false && !$is_admin) {
        alert("문서를 열람할 권한이 없습니다.");
    }

    //$arr = check_id($array);
    //$mb_check = array_search($mb_id, $arr->check);
    // if(!$mb_check && $mb_check !== 0 && !$is_admin) {
    //     alert("문서를 열람할 권한이 없습니다.");
    // }
}

// 결재 변경 로그
function get_log($wr_id) {
    global $write_table, $member;
    $sql = "SELECT 
            *
        FROM
            {$write_table}_log
        WHERE
            `wr_id` = '{$wr_id}'
        ORDER BY
            `datetime` asc ";
    $result = sql_query($sql);
    $total_count = sql_num_rows($result);

    $response = new stdClass();
    while($row=sql_fetch_array($result)) {
        $response->rows[] = array(
            "id_no" => $row['id_no'],
            "name" => mb_name($row['mb_id']),
            "current" => $row['current'],
            "memo" => $row['memo'],
            "datetime" => $row['datetime']
        );
    }
    $response->total_count = $total_count;

    return $response;
}

function get_btns($view){

    global $member;

    $edit_time = 3600; // 결재상태가 ㅅ결재승인상태를 변경할 수 있는 시간 ( 1 = 1초 )
    
    // 자신의 아이디로 몇번째 결재자인지 찾기. (상/하단 버튼 생성 - 승인/취소/반려/보류 )
    $app_list = array($view['wr_2'], $view['wr_4'], $view['wr_6'], $view['wr_8']);
    $app_key = array_search($member['id'], $app_list);
   
    switch($app_key) {
        case "0" : // 작성자
                if($app_key!==false) {
                    $app_rst = approval2($view['wr_2'], $view['wr_3'], "1|0", $view['wr_5'], '1');
                }
            break;
        case "1" : // 결재 1
            $ti = explode("|", $view['wr_5']);

            if($ti[0]==1) {

                $write_time = strtotime($ti[1]); // 최종 승인 시간
                $current_time = time(); // 현재 시간
                $gap_time = $current_time - $write_time; // 시간차이

                //$app_rst = approval2($view['wr_4'], $view['wr_5'], $view['wr_3'], $view['wr_7'], '2');
                
                if($gap_time <= $edit_time) {

                    //          결재현황 (결재자아이디,        결재상태,     전결재상태,   다음결재상태, 결재순번)
                    $app_rst = approval2($view['wr_4'], $view['wr_5'], $view['wr_3'], $view['wr_7'], '2');
                }

            } else {
                $app_rst = approval2($view['wr_4'], $view['wr_5'], $view['wr_3'], $view['wr_7'], '2');

            }

            break;
        case "2" : // 결재 2
            $ti = explode("|", $view['wr_7']);
            
            if($ti[0]==1) {
                $write_time = strtotime($ti[1]); // 최종 승인 시간
                $current_time = time(); // 현재 시간
                $gap_time = $current_time - $write_time; // 시간차이
                
                if($gap_time <= $edit_time) {
                    $app_rst = approval2($view['wr_6'], $view['wr_7'], $view['wr_5'], $view['wr_9'], '3');
                }
            } else {
                $app_rst = approval2($view['wr_6'], $view['wr_7'], $view['wr_5'], $view['wr_9'], '3');
            }
            break;


        case "3" : // 결재 3
           
            $ti = explode("|", $view['wr_9']);
            
            // 마지막 결재단계에서는 승인후 지정된 시간을 초과하면 결재상태를 변경 못하게 합니다.
            // 지정된 시간이 초과하면 결재상태 변경하는 버튼을 출력하지 않습니다.
            if($ti[0]==1) {

                $write_time = strtotime($ti[1]); // 최종 승인 시간
                $current_time = time(); // 현재 시간
                $gap_time = $current_time - $write_time; // 시간차이
                
                if($gap_time <= $edit_time) {
                    $app_rst = approval2($view['wr_8'], $view['wr_9'], $view['wr_7'], '', '4');
                }

            } else {
                
                // "승인"상태가 아니면 항상 결재 상태 변경 버튼을 출력한다.
                $app_rst = approval2($view['wr_8'], $view['wr_9'], $view['wr_7'], '', '4');

            }
            break;
    }

    return $app_rst;
}

// 직원으로 등록되어 있는지 체크
function checkStaff($mb_id) {
    global $is_admin, $member;
    //$ck = $member['co_name'];
    if(!$member['name'] && !$is_admin) {
        alert('직원으로 등록되지 않은 사용자는 문서접근 권한이 없습니다.\n직원인 경우 [직원등록]후에 이용하시기 바랍니다.');
    }
}

// 열람가능한 문서인지 체크
function checkDoc($write, $mb_id) {
    global $is_admin;
   
    if($write['wr_id'] && $write['mb_id'] != $mb_id && !$is_admin) {
        $vc = explode("|", $write['wr_3']);
        if($vc[0] != 1) {
            alert('작성자가 본 문서를 승인하지 않아 열람할 수 없습니다.\n열람을 위해서는 작성자의 승인이 필요합니다.');
        }
    }    
}

// 결재 진행중인 문서인지 체크
function checkOngoing($wr_3){    
    // 결재 진행중인 문서인지 체크
    $a = explode("|", $wr_3);
    if($a[0] == 1) {
        alert('결재 진행중인 문서는 수정할 수 없습니다.');
    }   
}

function get_categoryList($mb_id = '' ) {
    
    global $write_table, $board;
    
    $sql_search = " and ( wr_4 = '{$mb_id}' or wr_6 = '{$mb_id}' or wr_8 = '{$mb_id}' ) ";

    // 카테고리
    $sql = "select
                ca_name,
                count(ca_name) as cnt
            from
                `{$write_table}`
            where
                wr_is_comment = 0
            {$sql_search}
            group by
                ca_name ";
    $result2 = sql_query($sql, true);

    //echo $sql; 

    $category = array();
    while($rs = sql_fetch_array($result2)) {
        $category[$rs['ca_name']] = $rs['cnt'];
    }
    $cate_list = array();
    $cate_list =  explode("|", $board['bo_category_list']);

    //$list = array();
    $btns = "";
    for($t=0; $t < count($cate_list); $t++) {
        $btns .= "<button name='button' class='cateBtns' data-id='{$cate_list[$t]}'>{$cate_list[$t]} (".number_format($category[$cate_list[$t]]).")</button>";
        // $list[] = array(
        //     "name" => $cate_list[$t],
        //     "cnt" => number_format($category[$cate_list[$t]])
        // );
    }
    return $btns;
}

function get_approvallsit() {

    global $write_table, $member;
    
    $sql = "select
                *
            from
                {$write_table}_line
            where
                mb_id = '{$member['id']}'
            order by
                id_no asc";
    
    //echo "sql:".$sql; 

    $result = sql_query($sql);
    
    $arr = array();
    while($row=sql_fetch_array($result)) {
        list($app1, $app2, $app3) = explode("|", $row['approval']);
        $arr[] = array(
            "id_no" => $row['id_no'],            
            "app1"	=> $app1,
            "app2"	=> $app2,
            "app3"	=> $app3
        );
    }

    //print_r($arr);

    return $arr;

}

function sql_affected_rows($link=null) {
    global $tb;
    if(!$link)
        $link = $tb['connect_db'];
    if(function_exists('mysqli_affected_rows') && G5_MYSQLI_USE)
        return mysqli_affected_rows($link);
    else
        return mysql_affected_rows($link);
}
?>
