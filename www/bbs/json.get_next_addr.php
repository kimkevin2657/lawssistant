<?
	include "./_common.php";

	$res = array();
	if($sido != ''){
		$sql = "select distinct(gugun) as gugun from area where sido = '".$sido."'";
		$rs = sql_query($sql);
		while($row = sql_fetch_array($rs))
			$res['option'][] = '<option value="'.$row['gugun'].'">'.$row['gugun'].'</option>'.PHP_EOL;
	}

	echo json_encode($res);
?>