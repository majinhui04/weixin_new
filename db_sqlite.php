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
		$result = 0;
		try {
			$sth=$conn->prepare($sql);
			$sth->execute();
			$vec=$sth->fetchAll();
			$result = $vec['0'];
			return ($vec['0']['count(*)']);

		} catch (PDOException $e) {
			echo "Exception is".$e->getMessage();
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
	function query_maxid($conn,$table){
		$maxid = 0;
		$sql = 'select max(id) as maxid from '.$table;
		try {
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

function error_logger($content){
    file_put_contents("error_log.html",date('Y-m-d H:i:s ').$content.'<br>',FILE_APPEND);
}
?>