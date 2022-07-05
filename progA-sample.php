<!DOCTYPE html>
<html lang="ja">
  <head>
    <title>実装Aの結果</title>
  </head>
  <body>

<?php

if(isset($_REQUEST["tag"])){
    $tag = $_REQUEST["tag"];//入力タグ

    $tag_file = fopen("/home/pi/geotag/tag.csv", "r");//ファイルオープン
    $t = 0;
    
    while($tag_file && !feof($tag_file)){//ファイルポインタの終端まで
	    $tag_csv = fgets($tag_file);
      $tag_array = explode(',', $tag_csv);//csvをコンマで区切って配列を定義
	    if(isset($tag_array[1])){
		    if(strcmp(trim($tag_array[1]),$tag)==0){//配列の1列目と入力タグが一致するとき
          $tag_id[$t]=$tag_array[0];
			    ++$t;
	    	}
	    }
    }
   

    $geotag_file = fopen("/home/pi/geotag/geotag.csv", "r");
    $g = 0;

    while($geotag_file && !feof($geotag_file)){
	    $geotag_csv = fgets($geotag_file);
	    $geotag_array = explode(',', $geotag_csv);
	    if(isset($tag_id[$g])){
		    if(strcmp($tag_id[$g], trim($geotag_array[0]))==0){
			    $data[] = array(
				    "id"        => $geotag_array[0],
				    "time"      => $geotag_array[1],
				    "latitude"  => $geotag_array[2],
				    "longitude" => $geotag_array[3],
				    "url"       => $geotag_array[4]);//連想配列
				    ++$g;
		    }
	    }
    }
    //降順ソート
    foreach ((array) $data as $key => $value){
      $sort[$key] = $value['time'];
    }
    array_multisort($sort, SORT_DESC, $data);
    
    //出力
    for($g = 0; $g <= 100; $g++){
      echo '<div align="center">';
      echo '撮影時刻：'.$data[$g]["time"]."  ";
      echo '緯度：'.$data[$g]["latitude"]."  ";
      echo '経度：'.$data[$g]["longitude"];
      echo '<br>';
      $url = $data[$g]["url"];
      $img = file_get_contents($url);
      $enc_img = base64_encode($img);
      $imginfo = getimagesize('data:application/octet-stream;base64,' . $enc_img);
      echo '<img src="data:' . $imginfo['mime'] . ';base64,'.$enc_img.'">';
      echo nl2br("\n");
      echo '</div>';
      echo '<hr color="#191970"/>';
    }

}else{
    echo "[Program A] Please input a tag.";
}


?>
  </body>
</html>
