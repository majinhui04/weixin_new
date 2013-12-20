<?php
ini_set('display_errors', 'off');
$path = dirname(__FILE__);
include($path.'\db_sqlite.php');


$action_array = array(
	'image.list'=>'12',
	'image.update'=>'1',

	);

class Dao{
	
	function Dao($table){
		$this->table = $table;
		$this->_pagesize = 10;
		$this->_page = 1;
	}
	function _list($opts){
		$ret = json_decode('{"code":0,"msg":"success"}');

		$table = $this->$table;
		$array = array();
		$options = ' where 1=1 ';

		if(!empty($opts)){
			$page = $opts->_page?$opts->_page:$this->_page;
			$pagesize = $opts->_pagesize?$opts->_pagesize:$this->_pagesize;
			$options = $opts->options?$opts->options:$options;
		}else{
			$page = $this->_page;
			$pagesize = $this->_pagesize;
			
		}
			
		$page = intval($page);
		$page--;
		$pagesize = intval($pagesize);
		
		$index = $page*$pagesize;

		$limit = " limit $index,$pagesize";
		$sql = "select * from $table ".$options." order by id desc ".$limit;
		
		
		try{
			$actions = new Actions();
			$array = $actions->_list($sql);
			$ret->data = $array;
			$ret->total = count($array);

		}catch(Exception $e){
			return json_decode('{"code":500,"msg":"there are something wrong "}');
		}
			
		return $ret;

	}
	function _update($record){
	

	}
	function _delete($id){
		$table = $this->$table;

		$sql = "delete from  $table  where id = $id ";
  		try{
  			
  			$actions = new Actions();
			$actions->_delete($sql);
			return true;
		}
		catch(Exception $e){

			return false;
		}

	}
	function _create($record){
		
	}

}

class dao_image{
	var $table = 'imgae';
	function _list($options){
		$ret = json_decode('{"code":0,"msg":""}');
		$result = array();
		
		$id = $_GET["id"];
		$page = $_GET["_page"];
		$pagesize = $_GET["_pagesize"];

		if(!$page){
			$page = 0;
		}else{
			$page = intval($page);
			$page--;
		}
		if(!$pagesize){
			$pagesize = 10;
		}else{
			$pagesize = intval($pagesize);
		}
		$index = $page*$pagesize;

		$limit = " limit $index,$pagesize";
		$sql = 'select * from image order by id desc '.$limit;
		sqlite_logger($sql);
		
		$db = new db_sqlite();
		$conn = $db->connect_sqlite();
		$result = $db->query_sqlite($conn,$sql);
		//总数
		$count = $db->query_total($conn,'image');
		
		$db->close_sqlite($conn);
		$db = null;

		$ret->data = $result;
		$ret->total = $count;
		return $ret;

	}

	function _update($record){
		$count = 0;
		$picurl=$record->picurl;
	  	$mediaid=$record->mediaid ;
	  	$id=$record->id ;
	  	$notes=$record->notes ? $record->notes:'';
	  	$msgtype=$record->msgtype ?$record->msgtype:'';

	  	if($picurl and $mediaid and $id){
	  		try{
	  			$sql = "update image set picurl = '$picurl',mediaid = '$mediaid',msgtype = '$msgtype',notes='$notes' where id = $id ";
	  			sqlite_logger(' sql '.$sql);
		  		$db = new db_sqlite();
				$conn = $db->connect_sqlite();
				
				$db->exec_sqlite($conn,$sql);
				$db->commit_sqlite($conn);
				
				$db->close_sqlite($conn);
				$db = null;
			}
			catch(Exception $e){

				return false;
			}

	  	}

	  	return true;

	}
	function _delete($id){

		
  		try{
  			$sql = "delete from  image  where id = $id ";
  			sqlite_logger(' sql '.$sql);
	  		$db = new db_sqlite();
			$conn = $db->connect_sqlite();
			
			$db->exec_sqlite($conn,$sql);
			$db->commit_sqlite($conn);
			
			$db->close_sqlite($conn);
			$db = null;
			return true;
		}
		catch(Exception $e){

			return false;
		}

	  	

	  	return false;

	}
	function _create($record){
		$result = -1;
		
		$picurl = $record->picurl ;
	  	$mediaid = $record->mediaid ;
	  	$notes = $record->notes ;
	  	$msgtype = $record->msgtype;
		
		
		$sql = "insert into image(picurl,mediaid,msgtype,notes) values('$picurl','$mediaid','$msgtype','$notes')";

		try{
			$db = new db_sqlite();
			$conn = $db->connect_sqlite();
			$db->exec_sqlite($conn,$sql);
   			$db->commit_sqlite($conn);
   			$result = $db->query_maxid($conn,'image');
    		$db->close_sqlite($conn);

    		$db = null;
    		$result->data = $maxid;
		}catch(Exception $e){

			return $result;
		}

		return $result;
	}

}

$action = null;
if($_GET['action']){
	$action = $_GET['action'];
}
if($_POST['action']){
	$action = $_POST['action'];
}

sqlite_logger( 'action:'.$action );
//echo "string action ".$action.'<br>';
if($action){
	
	switch ($action){
		case 'image.list':
		  	$image = new dao_image();
			$ret = $image->_list();
			
			//zhuan str
			echo json_encode($ret);
			exit();
			break;  
		case 'image.update':
			$image = new dao_image();
			$result = json_decode('{"code":-1,"msg":"好像出错了"}');

		  	$record = json_decode('{"_abc":1}');
		  	$record->picurl = $_POST['picurl'];
		  	$record->mediaid = $_POST['mediaid'];
		  	$record->id = $_POST['id'];
		  	$record->notes = $_POST['notes'];
		  	$record->msgtype = $_POST['msgtype'];

		  	$r = $image->_update($record);
		  	if($r){
		  		$result->code = 0;
		  	}
		  	sqlite_logger( json_encode($record) );

		  	echo json_encode($result);
		  	exit();
			break;
		case 'image.delete':
			$result = json_decode('{"code":-1,"msg":"好像出错了"}');
			$id = $_GET['id'];
			if($id){
				sqlite_logger("delete id $id");
				$image = new dao_image();
				$ret = $image->_delete($id);
				if($ret){
			  		$result->code = 0;
			  		
			  	}
			}
			echo json_encode($result);  	
			
			exit();
			break;  
		case 'image.create':
			$result = json_decode('{"code":-1,"msg":"创建图片信息出错了"}');
			
			$record = json_decode('{}');
		  	$picurl = $record->picurl = $_POST['picurl'];
		  	$mediaid = $record->mediaid = $_POST['mediaid'];
		  	$notes = $record->notes = $_POST['notes'];
		  	$msgtype = $record->msgtype = $_POST['msgtype'];


			if($picurl and $mediaid and $msgtype){
				$image = new dao_image();
				$ret = $image->_create($record);
				if($ret>-1){
			  		$result->code = 0;
			  		$result->data = $ret;
			  	}
			}
			echo json_encode($result);  	
			
			exit();
			break;  
		default:

			break;
	}
			
	
}else{
	echo "action:".$_GET['action'].'<br>';
	echo "action type :".gettype($_GET['action']).'<br>';
	echo "action type :".($_GET['action']==null).'<br>';
	echo "action type :".(!$_GET['action']).'<br>';
	echo "action type :".($action_array['image.list']).'<br>';
	echo 'please input action';
	test0();
	exit();
}
function test0(){
	$sql = "update image set picurl = 'http://mmbiz.qpic.cn/mmbiz/veD11cgRBcnQnxVshoP21fAF5pTMCicRdPuEozLVr88OX4Q3YFVaAicASdTOD4Fx5cw0s5ybALKMcbJHoT3QULbA/0',mediaid = 'gRwaHXoqXkoHnTFjdnKdPR3bkFrh64eo-Pk2BOg9_Ldy8Q0d_7S0gDX5YEwocggv',msgtype = 'joke',notes='11222' where id = 22";

	$db = new db_sqlite();
	$conn = $db->connect_sqlite();
	$result1 = $db->exec_sqlite($conn,$sql);
	$result2 = $db->commit_sqlite($conn);
	$db->close_sqlite($conn);
	echo '<br>'."result1:".$result1.'<br>';
	echo "result2:".$result2.'<br>';
	return true;
}
function test1(){
	$sql = "insert into about(author,notes) values('mjh','22')";

	$db = new db_sqlite();
	$conn = $db->connect_sqlite();
	$result1 = $db->exec_sqlite($conn,$sql);
	$result2 = $db->commit_sqlite($conn);
	$db->close_sqlite($conn);
	echo "$result1:".$result1.'<br>';
	echo "$result2:".$result2.'<br>';
	return true;
}
function test2(){
	
	$db = new db_sqlite();
	$conn = $db->connect_sqlite();
	$result = $db->query_maxid($conn,'about');

	$db->close_sqlite($conn);
	echo "$result:".$result.'<br>';
	
	return true;
}
function sqlite_logger($content){
    file_put_contents("sqlite_log.html",date('Y-m-d H:i:s ').$content.'<br>',FILE_APPEND);
}

?>