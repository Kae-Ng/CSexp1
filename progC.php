<!DOCTYPE html>
<html lang="ja">
	<head>
		<title>実装Cの結果</title>
	</head>
	<body>
<?php
//実装Ｃをこのファイルに実装してください。


//以下はダミーコードです。
if(isset($_REQUEST["tag"])){
	# 初期設定
	$mysqli = new mysqli('localhost', 'shizutaro', 'password', 'CSexp1DB');

	# mysqlとの接続
	if ($mysqli->connect_error) {
  		echo $mysqli->connect_error;
  		exit();
	} else {
  		$mysqli->set_charset("utf8");
	}

	// 検索タグを取得
	$tag = htmlspecialchars($_REQUEST["tag"], ENT_QUOTES, 'UTF-8');

	# keywordクエリの中身が何もなかった場合終了
	if (!isset($tag) || empty($tag)) {
		exit();
  	}

	// 検索をして、検索結果を表示
	$query = "SELECT * FROM geotag, tag WHERE geotag.id=tag.id and tag.tag='".$tag."' ORDER BY geotag.time DESC LIMIT 100";
	
	if($stmt = $mysqli->prepare($query)){
		$stmt->execute();
		$stmt->bind_result($id, $time, $latitude, $longitude, $url, $id2, $tag);
		echo "<div class='pic_box'>";
		while($stmt->fetch()){
			echo "<figure class='image'><img src='".$url."'></figure>";
			echo "<p class='text'>撮影時刻: ".$time."<br>";
			echo "緯度:  ".$latitude." 経度: ".$longitude."<br>";
			echo "URL:".$url."</p>";
			echo "<hr align='left' width='500' size='3' >";
		}
		echo "</div>";
		$stmt->close();
	}
	$mysqli->close();
}else{
	echo "[Program C] Please input a tag.";
}
?>
	</body>
</html>