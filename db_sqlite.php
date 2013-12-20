<?php
date_default_timezone_set('PRC');
class db_sqlite{
	//private db_path = "";
	//打开数据库
	function connect_sqlite(){
		$dbPath = dirname(__FILE__).'\db\weixin.db';
		
		$dbConnet = 'sqlite:'.$dbPath;

		$conn =null;
		if(file_exists($dbPath)){
			try{
				$conn = new PDO($dbConnet);
				//$conn  = new PDO("sqlite:student.db");
				$conn->beginTransaction();
			}
			catch(PDOException $e){
				error_logger("connect database fail .Exception is".$e->getMessage());
				//echo "Exception is".$e->getMessage();
			}

			return $conn;
		}else{
			error_logger('database path is wrong');
			//exit(header('location:http://www.baidu.com/'));
		}


	}

	function query_sqlite($conn,$sql){
		$result = array();
		try {
			$sth=$conn->prepare($sql);
			$sth->execute();
			$result=$sth->fetchAll(PDO::FETCH_ASSOC);
		} catch (PDOException $e) {
			error_logger("sql:".$sql." Exception is".$e->getMessage());
			//echo "Exception is".$e->getMessage();
		}

		return $result;
	}

	function query_count($conn,$sql){
		$result = null;
		try {
			$sth=$conn->prepare($sql);
			$sth->execute();
			$vec=$sth->fetchAll();
			$result = $vec['0'];
			return ($vec['0']['count(*)']);

		} catch (PDOException $e) {
			error_logger("sql:".$sql." Exception is".$e->getMessage());
			//echo "Exception is".$e->getMessage();
		}
		
		return $result;
	}
	//获取某张表的总记录数
	function query_total($conn,$table){
		$result = 0;
		$sql = 'select count(*) as c from '.$table;
		try {
			$sth=$conn->prepare($sql);
			$sth->execute();
			$vec=$sth->fetchAll();
			//$result = $vec['0'];
			return ($vec['0']['c']);

		} catch (PDOException $e) {
			error_logger("sql:".$sql." Exception is".$e->getMessage());
			//echo "Exception is".$e->getMessage();
		}
		
		return $result;
	}
	//获取最大的id
	function query_maxid($conn,$table=''){
		$maxid = 0;
		
		try {
			$sql = 'select max(id) as maxid from '.$table;
			$sth=$conn->prepare($sql);
			$sth->execute();
			$vec=$sth->fetchAll();
			$maxid = $vec['0']['maxid'];
			return $maxid;

		} catch (PDOException $e) {
			error_logger("sql:".$sql." Exception is".$e->getMessage());
			//echo "Exception is".$e->getMessage();
		}
		
		return $maxid;
	}

	function exec_sqlite($conn,$sql){
		$count = 0;
		try {
			$conn->exec($sql);
			
			
		} catch (PDOException $e) {
			$conn->rollBack();
			error_logger("sql:".$sql." Exception is".$e->getMessage());
			//echo "Exception is".$e->getMessage();
		}
		// $conn->commit();
		return $count;
	}

	function commit_sqlite($conn){
		$conn->commit();
	}

	function close_sqlite($conn){
		$conn = null;
	}



}

class Actions{
	function _count($sql){
		sqlite_logger(' count sql '.$sql);
		$result = null;
		try {
			$db = new db_sqlite();
			$conn = $db->connect_sqlite();
			$sth=$conn->prepare($sql);
			$sth->execute();
			$vec=$sth->fetchAll();
			$result = $vec['0']['count(*)'];
			$result = intval($result);
			//return ($vec['0']['count(*)']);

		} catch (PDOException $e) {
			error_logger("sql:".$sql." Exception is".$e->getMessage());
			//echo "Exception is".$e->getMessage();
		}
		
		return $result;
	}
	function _list($sql){
		sqlite_logger(' list sql '.$sql);
		$array = array();

		try{
			$db = new db_sqlite();
			$conn = $db->connect_sqlite();
			$array = $db->query_sqlite($conn,$sql);
			
			$db->close_sqlite($conn);
			$db = null;
			//sqlite_logger('ok list result '.var_dump($array));

		}catch(Exception $e){
			//sqlite_logger('fail list result '.var_dump($array));
			return null;

		}

		return $array;
	}
	function _update($sql){
		sqlite_logger(' update sql '.$sql);
		try{
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

		return true;
	}
	function _delete($sql){

		sqlite_logger(' delete sql '.$sql);

		try{
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

	  	return true;
	}

	function _create($sql,$table){
		sqlite_logger(' create sql '.$sql);

		try{
			$db = new db_sqlite();
			$conn = $db->connect_sqlite();
			$db->exec_sqlite($conn,$sql);
   			$db->commit_sqlite($conn);
   			$id = $db->query_maxid($conn,$table);
    		$db->close_sqlite($conn);

    		$db = null;
    		return $id;
    		
		}catch(Exception $e){

			return null;
		}
		
	}
}

class Dao{

	
	function Dao(){
		$this->table = '';
	}
	
	function getListParams(){
		
	}
	function getUpdateParams(){
		
	}
	function getDeleteParams(){
		
	}
	function getCreateParams(){
		
	}
	function ajax_list(){
		$ret = json_decode('{"code":0,"msg":"success"}');

		$params = $this->getListParams();

		$array = $this->_list($params);
		$count = $this->_count($params);
		if( gettype($array) == 'array' ){
			$ret->data = $array;
			$ret->total = $count;
			return json_encode($ret);
		}else{
			return '{"code":500,"msg":"there are something wrong "}';
		}
	}
	function ajax_update(){
		$record = $this->getUpdateParams();
		if($record->code){
			return json_encode($record);
		}
		$result = $this->_update($record);
		if($result){
			return '{"code":200,"msg":"success"}';
		}else{
			return '{"code":500,"msg":"update 好像出错了"}';
		}
	}
	function ajax_create(){
		$record = $this->getCreateParams();
		sqlite_logger(' ajax_create '.json_encode($record));

		if($record->code){
			return json_encode($record);
		}
		$id = $this->_create($record);
		if($id){
			return '{"code":200,"msg":"success","data":"'.$id.'"}';
		}else{
			return '{"code":500,"msg":"create 好像出错了"}';
		}
	}
	function ajax_delete(){
		$record = $this->getDeleteParams();
		if($record->code){
			return json_encode($record);
		}
		$id = $record->id;
		$result = $this->_delete($id);
		if($result){
			return '{"code":200,"msg":"success"}';
		}else{
			return '{"code":500,"msg":"delete 好像出错了"}';
		}
	}
	function _count($opts){
		$table = $this->table;
		$result = null;
		$condition = ' where 1=1 ';
		if( !empty($opts->msgtype) ){
			$msgtype = $opts->msgtype;
			$condition = $condition." and msgtype='$msgtype' ";
		}
	
		$sql = "select count(*) from $table ".$condition;
		
		try{
			$actions = new Actions();
			$result = $actions->_count($sql);
			//return $result;

		}catch(Exception $e){
			return null;
		}
			
		return $result;

	}
	function _list($opts){
	
		$table = $this->table;
	
		$array = null;
		$condition = ' where 1=1 ';
		if( !empty($opts->msgtype) ){
			$msgtype = $opts->msgtype;
			$condition = $condition." and msgtype='$msgtype' ";
		}
		
		$page = $opts->_page?$opts->_page:1;
		$pagesize = $opts->_pagesize?$opts->_pagesize:$this->_pagesize;
			
			
		$page = intval($page);
		$page--;
		$pagesize = intval($pagesize);
		
		$index = $page*$pagesize;

		$limit = " limit $index,$pagesize";
		$sql = "select * from $table ".$condition." order by id desc ".$limit;
		
		
		try{
			$actions = new Actions();
			$array = $actions->_list($sql);
			return $array;

		}catch(Exception $e){
			return null;
		}
			
		return $array;

	}

	function _update($record){
		$table = $this->table;
		
	  	$id=$record->id ;
	  	$array =array();
	  	
	  	foreach ($record as $key => $value) {
	  		if($key == 'id'){

	  		}else{
	  			if(gettype($value) == 'integer'){
		  			$array[] = "$key = $value";
		  		}else if(gettype($value) == 'string'){
		  			$array[] = "$key = '$value'";
		  		}
	  		}
		  		
	  			
	  	}
	  	$str = implode(',', $array);
	  	if($id and count($array)>0){
	  		$sql = "update $table set ".$str." where id = $id ";
	  		try{
	  			
	  			$actions = new Actions();
				$actions->_update($sql);
			}
			catch(Exception $e){

				return false;
			}

	  	}else{
	  		return false;
	  	}

	  	return true;

	}
	function _delete($id){
		$table = $this->table;

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
		$id = null;
		$table = $this->table;
		
	  	$arr_name =array();
	  	$arr_value =array();
	  	foreach ($record as $key => $value) {
	  		$arr_name[] = $key;
	  		if(gettype($value) == 'integer'){
	  			$arr_value[] = $value;
	  		}else if(gettype($value) == 'string'){
	  			$arr_value[] = "'$value'";
	  		}
	  			
	  	}
	  	$str_name = implode(',', $arr_name);
	  	$str_value = implode(',', $arr_value);
		
		$sql = "insert into $table($str_name) values($str_value)";

		try{
			$actions = new Actions();
			$id = $actions->_create($sql,$table);
		}catch(Exception $e){

			return null;
		}

		return $id;
	}

}
function error_logger($content){
    file_put_contents("error_log.html",date('Y-m-d H:i:s ').$content.'<br>',FILE_APPEND);
}
?>