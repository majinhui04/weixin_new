<?php
class db_sqlite{
	//private db_path = "";
	//打开数据库
	function connect_sqlite(){
		$dbPath = dirname(__FILE__).'/db/weixin.db';
		echo $dbPath;
		$dbConnet = 'sqlite:'.$dbPath;

		$conn =null;
		if(file_exists($dbPath)){
			try{
				$conn = new PDO($dbConnet);
				//$conn  = new PDO("sqlite:student.db");
				$conn->beginTransaction();
			}
			catch(PDOException $e){
				echo "Exception is".$e->getMessage();
			}

			return $conn;
		}else{
			echo "fail";
			//exit(header('location:http://www.baidu.com/'));
		}


	}

	function query_sqlite($conn,$sql){
		$result = array();
		try {
			$sth=$conn->prepare($sql);
			$sth->execute();
			$result=$sth->fetchAll();
		} catch (PDOException $e) {
			echo "Exception is".$e->getMessage();
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
			var_dump($result);
			return ($vec['0']['count(*)']);

		} catch (PDOException $e) {
			echo "Exception is".$e->getMessage();
		}
		return 0;
		return $result;
	}

	function exec_sqlite($conn,$sql){

		$count = 0;
		try {
			$conn->exec($sql);
			
			
		} catch (PDOException $e) {
			$conn->rollBack();
			echo "Exception is".$e->getMessage();
		}
		$conn->commit();
		return $count;
	}

	function commit_sqlite($conn){
		$conn->commit();
	}

	function close_sqlite($conn){
		$conn = null;
	}

}
?>