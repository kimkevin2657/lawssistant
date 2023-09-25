<?php
include_once('./_common.php');
set_time_limit(0);
ini_set('memory_limit', '640M');
//require_once(G5_PLUGIN_PATH.'/tcpdf/examples/tcpdf_include.php');
require_once(G5_PLUGIN_PATH.'/tcpdf/config/tcpdf_config.php');
require_once(G5_PLUGIN_PATH.'/tcpdf/tcpdf.php');

//$pdf = new TCPDF('P-가로인쇄, L-세로인쇄', PDF_UNIT, 'A4', true, 'UTF-8', false);

class MYPDF extends TCPDF {

    //Page header
    public function Header() {
        // Logo
        //$image_file = K_PATH_IMAGES.'logo_example.jpg';
        //$this->Image($image_file, 10, 10, 15, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        //$this->SetFont('helvetica', 'B', 20);
        // Title
        //$this->Cell(0, 15, '<< TCPDF Example 003 >>', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-20);
        // Set font
        //$this->SetFont('helvetica', 'I', 8);
        $this->SetFont('nanumbarungothicyethangul', '', 9); // 글꼴 설정.
        // Page number
        $this->Cell(0, 8, '(주)ㅇㅇㅇ http://www.yourdomain.com : 아직 세상은 살만 하다!!', 'T', false, 'L', 0, '', 0, false, 'T', 'M');
        $this->Cell(22, 8, '페이지 : '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), '0', false, 'R', 0, '', 0, false, 'T', 'M');
        
    }
}


//$pdf = new TCPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
$pdf = new MYPDF('P', PDF_UNIT, 'A4', true, 'UTF-8', false);
//$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);




// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('http://www.bookmoa.kr');
$pdf->SetTitle('http://www.bookmoa.kr');
$pdf->SetSubject('http://www.bookmoa.kr');
$pdf->SetKeywords('http://www.bookmoa.kr');


// set default header data
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.'038', PDF_HEADER_STRING);
//$name1 = "(주)북모아 - 인쇄, 제작, 출판까지 ( http://www.bookmoa.kr )";
//$name2 = "북모아 인쇄 타이틀";

//$pdf->SetFont('nanumbarungothicyethangul', '', 6);
//$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, $name1, $name2);
//$pdf->SetHeaderData('', 0, $name1, '');

// set header and footer fonts
//$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
//$pdf->setHeaderFont(Array('nanumbarungothicyethangul', '', '8'));
//$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
$pdf->setFooterFont(Array('nanumbarungothicyethangul', '', '8'));

// set default monospaced font
//$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 25, PDF_MARGIN_RIGHT);
//$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetHeaderMargin(0);
$pdf->SetFooterMargin(20);

$pdf->setPrintHeader(false); // without Header

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, 20); // 페이지나누기 영역설정 (하단)

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/kor.php')) {
	require_once(dirname(__FILE__).'/lang/kor.php');
	$pdf->setLanguageArray($l);
}

// DB 시작 ===============
if($idx) {
    $row = estimate_content($idx); // 견적서 주요 내용

    // 견적서 세부 내역
    $list = estimate_sub_list($idx); 
    $list_count = count($list);

    // 카테고리(구분)
    $category = estimateCategory(); 
    
    // 배송지
    if($row['uid'] > 0) {
        $deliver = workingOrderDeliver($row['uid']);
        $deliver_count = count($deliver);
    }
}
// DB 종료 ===============

$pdf->AddPage();
$pdf->SetFillColor(238,238,238); // 배경 음영색 (회색)


$pdf->SetFont('nanumbarungothicyethangul', '', 15); // 글꼴 설정.
$pdf->MultiCell(60, 10, "견          적          서", 'B', 'C', 0, 1, 75, 20, true, 0, false, true, 10, 'M');


$pdf->SetFont('nanumbarungothicyethangul', '', 9); // 글꼴 설정.
$pdf->Ln(10);

$pdf->setCellHeightRatio(3.00); // line-height
$pdf->MultiCell(8, 32, '공 급 자', 'LTB', 'C', 1, 0, 98, '', true, 0, false, true, 35, 'M');

$H = 8; //공급자정보 칸 높이
//$pdf->Image('../../../bmwork/estimate/stamp.png', 185, 45, 13, 13, 'PNG', '', '', false, 150, '', false, false, 0, false, false, false);
$pdf->setCellHeightRatio(1.00); // line-height
$pdf->MultiCell(19, $H, '등록번호', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, $H, 'M');
$pdf->SetFont('nanumbarungothicyethangul', 'B', 12); // 글꼴 설정.
$pdf->MultiCell(73, $H, '508-81-40669', 'LTRB', 'C', 0, 1, '', '', true, 0, false, true, $H, 'M');

$pdf->SetFont('nanumbarungothicyethangul', 'B', 9); // 글꼴 설정.
$pdf->MultiCell(19, $H, '상        호', 'LTB', 'C', 1, 0, 106, '', true, 0, false, true, $H, 'M');
$pdf->MultiCell(27, $H, '(주)북모아', 'LTRB', 'C', 0, 0, '', '', true, 0, false, true, $H, 'M');
$pdf->MultiCell(19, $H, '대        표', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, $H, 'M');
$pdf->MultiCell(27, $H, '김동명      (인)', 'LTRB', 'C', 0, 1, '', '', true, 0, false, true, $H, 'M');

$pdf->MultiCell(19, $H, '주        소', 'LTB', 'C', 1, 0, 106, '', true, 0, false, true, $H, 'M');
$pdf->MultiCell(73, $H, '서울 성동구 연무장5가길 25 SK V1 타워 706호', 'LTRB', 'C', 0, 1, '', '', true, 0, false, true, $H, 'M');

$pdf->MultiCell(19, $H, '업        태', 'LTB', 'C', 1, 0, 106, '', true, 0, false, true, $H, 'M');
$pdf->SetFont('nanumbarungothicyethangul', '', 7); // 글꼴 설정.
$pdf->MultiCell(27, $H, '제조, 도매, 소매, 서비스', 'LTRB', 'C', 0, 0, '', '', true, 0, false, true, $H, 'M');

$pdf->SetFont('nanumbarungothicyethangul', '', 9); // 글꼴 설정.
$pdf->MultiCell(19, $H, '종        목', 'LTB', 'C', 1, 0, '', '', true, 0, false, true, $H, 'M');
$pdf->SetFont('nanumbarungothicyethangul', '', 7); // 글꼴 설정.
$pdf->MultiCell(27, $H, '인쇄, 인쇄물무역업, 통신판매업, 편집, 디자인', 'LTRB', 'C', 0, 1, '', '', true, 0, false, true, $H, 'M');


$pdf->SetFont('nanumbarungothicyethangul', '', 10); // 글꼴 설정.
$NAME = $row['od_co']." (".$row['od_name'].")";
$pdf->MultiCell(75, 6, $NAME, 'B', 'L', 0, 0, 12, 42, true, 0, false, true, 6, 'M');
$pdf->MultiCell(75, 6, '귀하', '', 'L', 0, 0, 79, 42, true, 0, false, true, 6, 'M');

list($Y, $M, $D) = explode("-", $row['order_date']);
$YMD = $Y."년     ".$M."월     ".$D."일";
$pdf->MultiCell(75, 6, $YMD, 'B', 'L', 0, 0, 12, 49, true, 0, false, true, 6, 'M');

$basic = $row['size'].", ".$row['page']."페이지, ".$row['cnt']."부";
$pdf->MultiCell(75, 6, '아래와 같이 견적합니다.', 'B', 'L', 0, 0, 12, 56, true, 0, false, true, 6, 'M');
$pdf->MultiCell(75, 6, '기본사양 : '.$basic, 'B', 'L', 0, 0, 12, 63, true, 0, false, true, 6, 'M');
$pdf->MultiCell(186, 6, '견적제목 : '.$row['subject'], '', 'L', 0, 0, 12, 74, true, 0, false, true, 6, 'M');

$pdf->SetFont('nanumbarungothicyethangul', '', 12); // 글꼴 설정.
$TOTAL = number_format($row['total_price']);
$pdf->MultiCell(186, 7, '합 계 금 액 (원)  :  '.$TOTAL, 'T', 'L', 0, 0, 12, 80, true, 0, false, true, 7, 'M');
$pdf->SetFont('nanumbarungothicyethangul', '', 9); // 글꼴 설정.
$pdf->MultiCell(186, 7, '견적일로부터 30일간 유효', 'T', 'R', 0, 1, 12, 80, true, 0, false, true, 7, 'M');

$pdf->Ln(2);

$pdf->SetFont('nanumbarungothicyethangul', '', 8);
$pdf->setCellPaddings( $left = '0.7', $top = '1', $right = '0.7', $bottom = '1');
$tbl = <<<EOD
<style>
table { width:100%; border-spacing:0; border-collapse:collapse; }
th { border:0px solid #000000; }
td { border:0px solid #000000; vertical-align:middle; height:20px; line-height:20px;}

.td_01 { width:5%; }
.td_03 { width:15%; }
.td_04 { width:30%; }
.td_05 { width:10%; }
.td_06 { width:11%; }
.td_07 { width:11%; }
.td_08 { width:18%; }

</style>
<table>
	<thead>
		<tr style="text-align:center;line-height:22px;background-color:#eeeeee;">
			<th class="td_01">NO</th>
            <th class="td_03">항목</th>
			<th class="td_04">규격(제목)</th>
			<th class="td_05">수량</th>
			<th class="td_06">단가</th>
			<th class="td_07">공급가액</th>
			<th class="td_08">비고</th>
		</tr>
	</thead>
	<tbody>
EOD;

$num=0;
for($i=0; $i < $list_count; $i++) {

    $num = $i+1; // 번호
    
    $categorySub = estimateCategorySub($list[$i]['cate1']);

    //$cate1 = ($list[$i]['cate1'])? $category[$list[$i]['cate1']]:""; // 구분
    $cate2 = ($list[$i]['cate2'])? $categorySub[$list[$i]['cate2']]:""; // 항목
    $standard = ($list[$i]['standard'])? $list[$i]['standard']:""; // 규격
    $unit = ($list[$i]['unit'])? $list[$i]['unit']:""; // 수량
    $unit_price = ($list[$i]['unit_price'])? number_format($list[$i]['unit_price']):""; // 단가
    $supply_price = ($list[$i]['supply_price'])? number_format($list[$i]['supply_price']):""; // 공급가액
    $note = ($list[$i]['note'])? $list[$i]['note']:""; // 비고

	$tbl .= <<<EOD
		<tr align="center" nobr="true">
			<td class="td_01">$num</td>
			<td class="td_03">$cate2</td>
			<td class="td_04" align="left">$standard</td>
			<td class="td_05" align="right">$unit</td>
			<td class="td_06" align="right">$unit_price</td>
			<td class="td_07" align="right">$supply_price</td>
			<td class="td_08">$note</td>
		</tr>
EOD;
}

$tbl .= <<<EOD
    </tbody>
</table>
EOD;

$pdf->writeHTML($tbl, true, false, false, false, ''); // 표출력


$point = ($list_count <= 20)? 238:""; // 자료의 수가 20개 이하면 메모박스의 위치를 고정
if($list_count > 20 && $list_count < 26) {

}


//메모박스
//$pdf->MultiCell(104, 35, $row['memo'], 'LTRB', 'L', 0, 0, 12, $point, true, 0, false, true, 9, 'M');
$pdf->MultiCell(104, 36, $row['memo'], 1, '', 0, 0, 12, $point, true);
//$pdf->MultiCell(15, 5, 'MEMO', 'B', 'C', 0, 0, 14, '', true, 0, false, true, 5, 'M');
//$pdf->MultiCell(104, 35, $row['memo'], '', 'L', 0, 0, 17, '', true, 0, false, true, 5, 'M');


$pdf->SetFont('nanumbarungothicyethangul', '', 9); // 글꼴 설정.
$H = 6;
$pdf->MultiCell(24, $H, '공급합계', 'LTB', 'C', 1, 0, 118, '', true, 0, false, true, $H, 'M');
$pdf->MultiCell(56, $H, number_format($row['sum_supply'])." 원    ", 'LTRB', 'R', 0, 1, '', '', true, 0, false, true, $H, 'M');

$pdf->MultiCell(24, $H, '할인액', 'LTB', 'C', 1, 0, 118, '', true, 0, false, true, $H, 'M');
$pdf->MultiCell(56, $H, number_format($row['dc_price'])." 원    ", 'LTRB', 'R', 0, 1, '', '', true, 0, false, true, $H, 'M');

$pdf->MultiCell(24, $H, '합계', 'LTB', 'C', 1, 0, 118, '', true, 0, false, true, $H, 'M');
$pdf->MultiCell(56, $H, number_format($row['hap_price'])." 원    ", 'LTRB', 'R', 0, 1, '', '', true, 0, false, true, $H, 'M');

$pdf->MultiCell(24, $H, 'VAT', 'LTB', 'C', 1, 0, 118, '', true, 0, false, true, $H, 'M');
$pdf->MultiCell(56, $H, number_format($row['vat_price'])." 원    ", 'LTRB', 'R', 0, 1, '', '', true, 0, false, true, $H, 'M');

$pdf->MultiCell(24, $H, '단위절사', 'LTB', 'C', 1, 0, 118, '', true, 0, false, true, $H, 'M');
$pdf->MultiCell(56, $H, number_format($row['trim_price'])." 원    ", 'LTRB', 'R', 0, 1, '', '', true, 0, false, true, $H, 'M');

$pdf->MultiCell(24, $H, '전체합계', 'LTB', 'C', 1, 0, 118, '', true, 0, false, true, $H, 'M');
$pdf->MultiCell(56, $H, number_format($row['total_price'])." 원    ", 'LTRB', 'R', 0, 1, '', '', true, 0, false, true, $H, 'M');

// 배송지 시작 ==================================
if($deliver_count > 0) {
$pdf->AddPage();
$tbl = <<<EOD
    <style>
    table { width:100%; border-spacing:0; border-collapse:collapse; }
    th { border:0px solid #000000; }
    td { border:0px solid #000000; vertical-align:middle; height:20px; line-height:20px;}
    </style>
    
    <table>
        <tbody>
        <tr style="text-align:center;line-height:22px;background-color:#eeeeee;">
            <td style="width:12%;">배송지명</td>
            <td style="width:8%;">우편번호</td>
            <td style="width:26%;">기본주소</td>
            <td style="width:20%;">상세주소</td>
            <td style="width:23%;">참고주소</td>
            <td style="width:4%;">수량</td>
            <td style="width:*;text-align:right;">배송비</td>
        </tr>
EOD;

for($i=0; $i < count($deliver); $i++) {
    
    $t_addr = $deliver[$i]['t_addr'];
    $zip    = $deliver[$i]['zip'];   
    $addr1  = $deliver[$i]['addr1'];
    $addr2  = $deliver[$i]['addr2'];
    $addr3  = $deliver[$i]['addr3'];
    $cnt    = number_format($deliver[$i]['cnt']);
    $price  = number_format($deliver[$i]['price']);

$tbl .= <<<EOD
    <tr style="text-align:center;">
        <td style="width:12%;">$t_addr</td>
        <td style="width:8%;">$zip</td>
        <td style="width:26%;">$addr1</td>
        <td style="width:20%;">$addr2</td>
        <td style="width:23%;">$addr3</td>
        <td style="width:4%;">$cnt</td>
        <td style="width:*;text-align:right;">$price</td>
    </tr>
EOD;
}

$tbl .= <<<EOD
        </tbody>
    </table>
EOD;
$pdf->writeHTML($tbl, true, false, false, false, ''); // 표출력
} // end if

// 배송지 출력 끝 ==============================
    


if($mode=="email"){
    // 이메일발송이면 PDF 파일을 서버에 저장한다.
    $filename = G5_DATA_PATH.'/pdf/bookmoa_estimate_'.$idx.'.pdf';
    $pdf->Output($filename, 'F');
    
    if(file_exists($filename)) {
        $rst['filename'] = $filename;
        $rst['status'] = true;
    } else {
        $rst['status'] = false;
    }
    
    echo json_encode($rst);

} else {
    // 이메일 발송이 아니라면 인쇄페이지를 보여준다.
    $pdf->Output('bookmoa_estimate.pdf', 'I');
}


//$tbl = '메모리 사용량 : '.number_format(memory_get_usage()).'Byte ('.(memory_get_usage() / 1000).'KB)';
//$pdf->writeHTML($tbl, true, false, false, false, '');



//$pdf->Output(dirname(__FILE__).'/견적서_'.G5_TIME_YMDHIS.'.pdf', 'F'); 
//============================================================+
// END OF FILE
//============================================================+
