<!DOCTYPE html>
<html lang="ja">
	<head>
		<title>実装Bの結果</title>
	</head>
	<body>
    <?php
$link = mysqli_connect("localhost", "name", "pass", "dbname");

if (mysqli_connect_errno()) {
    printf("Connect failed: %s\n", mysqli_connect_error());
    exit();
}

$tag = $_REQUEST['tag'];
$query = "SELECT geotag.*, tag.tag FROM geotag, tag  IGNORE INDEX(i_tag) WHERE geotag.id = tag.id AND tag.tag like '$tag' ORDER BY time DESC LIMIT 100";
$result = mysqli_query($link, $query);

    while ($row = mysqli_fetch_assoc($result)) {
        echo '撮影時刻：'.$row["time"]." ";
        echo '緯度：'.$row["latitude"]." ";
        echo '経度：'.$row["longitude"];
        echo '<br>';
        $url = $row["url"];
        $img = file_get_contents($url);
        $enc_img = base64_encode($img);
        $imginfo = getimagesize('data:application/octet-stream;base64,' . $enc_img);
        echo '<img src="data:' . $imginfo['mime'] . ';base64,'.$enc_img.'">';
    }

    
mysqli_free_result($result);
mysqli_close($link);
?>
	</body>
</html>
