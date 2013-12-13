<?php

include('db_splite.php');

$db = new db_splite();

$conn=$db->connect_sqplite();
$count = $db->query_count($conn,'select count(*) from about');
//$count = $db->exec_sqlite($conn,'delete from user where id=4');
echo "count:".$count.'<br>';
/*
$result = $db->exec_sqlite($conn,"insert into text(msgtype,content) values('lily','的发生的发生方法的沙发沙发上法律孙菲菲盯上了发生的放声大哭刘放生发发生的浪费')");
echo "result:".$result.'<br>';*/
$db->close_sqlite($conn);
/*$array = $db->query_splite($conn,'select * from user where id<3');

foreach ($array as $key => $value) {
	echo "id:".$value['id'].'  '.$value['name'].'<br>';
}
echo "---------<br>";

$db->exec_sqlite($conn,"insert into user(name,age,note) values('lily',21,'helow')");

$array = $db->query_splite($conn,'select * from user where id>3');

foreach ($array as $key => $value) {
	echo "id:".$value['id'].'  '.$value['name'].'<br>';
}*/
//var_dump($array);
$db->close_sqlite($conn);
xx();
function xx(){
	
	$db1 = new db_splite();
	var_dump($db1);
}
?>