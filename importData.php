<?php
$path = dirname(__FILE__);
include($path.'\db_sqlite.php');

$arr = readTxtFile('../joke_image.txt');

$db = new db_sqlite();
$conn = $db->connect_sqlite();
for ($i=0; $i < count($arr); $i++) { 
	$item = json_decode($arr[$i]);
	$picUrl = $item->picUrl;
	$mediaId = $item->mediaId;
	
	//echo $item->mediaId.'<br>';

	$sql = "insert into image(mediaid,picurl) values('".$mediaId."','".$picUrl."')";
	echo $sql.'<br>';	
	$result = $db->exec_sqlite($conn,$sql);
	echo $result.'<br>';	
		
}
$aa = $db->commit_sqlite($conn);
echo $aa.'<br>';	
/*$item = json_decode($arr[0]);
	$picUrl = $item->picUrl;
	$mediaId = $item->mediaId;
	
	

	$sql = "insert into image(mediaid,picurl) values('".$mediaId."','".$picUrl."')";
	echo $sql.'<br>';*/
	//$result = $db->exec_sqlite($conn,$sql);
$db->close_sqlite($conn);

function readTxtFile($file){
    $arr=array();
    $list = array();
    $fp=fopen($file,'r');

    while ($arr[] = fgets($fp)) {
   
    }
    fclose($fp);
    
    foreach ($arr as $key => $value) {
      if(strlen($value) >2){
        $list[] = $value;
      }
      //echo $key.'=>'.$value.',count:'.strlen($value).'<br>';
    }
    return $list;
}

?>