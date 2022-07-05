<!DOCTYPE html>
<html lang="ja">
	<head>
		<title>実装Aの結果</title>
	</head>
	<body>
<?php
//実装Ａをこのファイルに実装してください。
//mhg95などで検索すると早い

if(isset($_REQUEST["tag"])){
	// tag.csvに対して検索をして、画像IDを取得する	
	$tag = htmlspecialchars($_REQUEST["tag"], ENT_QUOTES, 'UTF-8');
	$result = array();
	$tag_cmd = "grep -w ".$tag."/home/pi/geotag/geotag/tag.csv | grep -oP [0-9]{10}";
	exec($tag_cmd, $hit_id);
	
	// 取得した画像IDに対してgeotag.csvで検索をする
	foreach($hit_id as $id){
		$id_cmd = "grep -w ".$id." -m 1 /home/pi/geotag/geotag/geotag.csv";
		exec($id_cmd, $result);
	}

	// 取得したデータの数を保存(100以上の場合は100に固定)
	$count_result = count($result);
	if($count_result > 100){
		$count_result = 100;
	}
	
	// 時間順で並び替え
	$target_keys = array();
	$number = 0;
	foreach($result as $k){
		$array = explode(',', $k);
		$target_keys[$number] = $array[1];
		$number ++;
	}
	array_multisort($target_keys, SORT_DESC, $result);

	// 取得した画像データ配列をコンマで区切ってさらに配列に保存
	$separated_result = array();
	$number = 0;
	foreach($result as $k){
		$separated_result[$number] = (explode(',', $k));
		$number ++;
	}

	// 画像の表示
	echo "<div class='pic_box'>";
	for($i=0; $i<$count_result; $i++){
		echo "<figure class='image'><img src='".$separated_result[$i][4]."'></figure>";
		echo "<p class='text'>撮影時刻: ".$separated_result[$i][1]."<br>";
		echo "緯度:  ".$separated_result[$i][2]." 経度: ".$separated_result[$i][3]."<br>";
		echo "URL:".$separated_result[$i][4]."</p>";
		echo "<hr align='left' width='500' size='3' >";
	}
	echo "</div>";
	
}

?>
	</body>
</html>