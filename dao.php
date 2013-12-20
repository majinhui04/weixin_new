<?php
ini_set('display_errors', 'off');
$path = dirname(__FILE__);
include($path.'\db_sqlite.php');





/*image*/
class Dao_image extends Dao{
	
	function Dao_image(){
		
		$this->table = 'image';
		$this->_pagesize = 10;
	}
	function getListParams(){
		$params = json_decode('{}');
	  	$params->_page = $_GET['_page'];
	  	$params->_pagesize = $_GET['_pagesize'];
	  	$params->msgtype = $_GET['msgtype'];

	  	return $params;
	}
	function getUpdateParams(){
		$record = json_decode('{}');

		$picurl = getRequest('picurl');
		$mediaid = getRequest('mediaid');
		$msgtype = getRequest('msgtype');
		$notes = getRequest('notes');
		$id = getRequest('id');

	  	$record->picurl =  $picurl;
	  	$record->mediaid = $mediaid;
	  	$record->id = $id;
	  	$record->notes = $notes;
	  	$record->msgtype = $msgtype;
	  	
	  	if( empty($id) or empty($mediaid) or empty($picurl) ){
	  		$record = json_decode('{"code":500}');
	  		$record->msg = 'update 参数不完整';
	  	}
	  	return $record;
	}
	function getDeleteParams(){
		$record = json_decode('{}');
	  	$id = $_GET['id'];
	  	$record->id = $id;
	  	
	  	if( empty($id) ){
	  		$record = json_decode('{"code":500}');
	  		$record->msg = 'id miss';
	  	}
	  	return $record;
	}
	function getCreateParams(){
		$record = json_decode('{}');

		
		$picurl = getRequest('picurl');
		$mediaid = getRequest('mediaid');
		$msgtype = getRequest('msgtype');
		$notes = getRequest('notes');
		

	  	$record->picurl =  $picurl;
	  	$record->mediaid = $mediaid;
	  	$record->notes = $notes;
	  	$record->msgtype = $msgtype;


	  	if( empty($mediaid) or empty($picurl) ){
	  		$record = json_decode('{"code":500}');
	  		$record->msg = 'create 参数不完整';
	  	}
	  		
	  	return $record;
	}
	

}

/*text*/
class Dao_text extends Dao{
	
	function Dao_text(){
		
		$this->table = 'text';
		$this->_pagesize = 10;
	}
	function getListParams(){
		$params = json_decode('{}');
	  	$params->_page = $_GET['_page'];
	  	$params->_pagesize = $_GET['_pagesize'];
	  	$params->msgtype = $_GET['msgtype'];

	  	return $params;
	}
	function getUpdateParams(){
		$record = json_decode('{}');
		
		$id = getRequest('id');
		$content = getRequest('content');
		$msgtype = getRequest('msgtype');
		$notes = getRequest('notes');
		 
	  	$record->content =  $content?$content:'';
	  	$record->id = $id;
	  	$record->notes = $notes?$notes:'';
	  	$record->msgtype = $msgtype?$msgtype:'';
	  	
	  	if( empty($id) or empty($content)  ){
	  		$record = json_decode('{"code":500}');
	  		$record->msg = 'update 参数不完整';
	  	}
	  	return $record;
	}
	function getDeleteParams(){
		$record = json_decode('{}');
	  	$record->id = $_GET['id'];
	  	
	  	if( empty($record->id) ){
	  		$record = json_decode('{"code":500}');
	  		$record->msg = 'id miss';
	  	}
	  	return $record;
	}
	function getCreateParams(){
		$record = json_decode('{}');

		$content = getRequest('content');
		$msgtype = getRequest('msgtype');
		$notes = getRequest('notes');
		
	  	$record->content =  $content?$content:'';
	  	$record->notes = $notes?$notes:'';
	  	$record->msgtype = $msgtype?$msgtype:'';
	  	if( empty($content) ){
	  		$record = json_decode('{"code":500}');
	  		$record->msg = 'create 参数不完整';
	  	}
	  		
	  	return $record;
	}
	
}

/*msgtype*/
class Dao_msgtype extends Dao{
	
	function Dao_msgtype(){
		
		$this->table = 'msgtype';
		$this->_pagesize = 10;
	}
	function getListParams(){
		$params = json_decode('{}');
	  	$params->_page = $_GET['_page'];
	  	$params->_pagesize = $_GET['_pagesize'];
	  

	  	return $params;
	}
	function getUpdateParams(){
		$record = json_decode('{}');
		
		$name = getRequest('name');
		$id = getRequest('id');
		
	  	$record->name =  $name?$name:'';
	  	$record->id = $id;
	  	
	  	if( empty($id) or empty($name)  ){
	  		$record = json_decode('{"code":500}');
	  		$record->msg = 'update 参数不完整';
	  	}
	  	return $record;
	}
	function getDeleteParams(){
		$record = json_decode('{}');
	  	$record->id = $_GET['id'];
	  	
	  	if( empty($record->id) ){
	  		$record = json_decode('{"code":500}');
	  		$record->msg = 'id miss';
	  	}
	  	return $record;
	}
	function getCreateParams(){
		$record = json_decode('{}');

		$name = getRequest('name');
 
	  	$record->name =  $name?$name:'';
	  	if( empty($name) ){
	  		$record = json_decode('{"code":500}');
	  		$record->msg = 'create 参数不完整';
	  	}
	  		
	  	return $record;
	}
	

}

/*about*/
class Dao_about extends Dao{
	
	function Dao_about(){
		
		$this->table = 'about';
		$this->_pagesize = 1;
	}
	function getListParams(){
		$params = json_decode('{}');
	  	$params->_page = $_GET['_page'];
	  	$params->_pagesize = $_GET['_pagesize'];
	  

	  	return $params;
	}
	function getUpdateParams(){
		$record = json_decode('{}');
		
		$author = getRequest('author');
		$reply = getRequest('reply');
		$id = getRequest('id');
		
	  	$record->author =  $author;
	  	$record->reply =  $reply;
	  	$record->id = $id;
	  	
	  	if( empty($id) or empty($author) or empty($reply) ){
	  		$record = json_decode('{"code":500}');
	  		$record->msg = 'update 参数不完整';
	  	}
	  	return $record;
	}
	function getDeleteParams(){
		$record = json_decode('{}');
	  	$record->id = $_GET['id'];
	  	
	  	if( empty($record->id) ){
	  		$record = json_decode('{"code":500}');
	  		$record->msg = 'id miss';
	  	}
	  	return $record;
	}
	function getCreateParams(){
		$record = json_decode('{}');

		$author = getRequest('author');
		$reply = getRequest('reply');
 
	  	$record->author =  $author;
	  	$record->reply =  $reply;
	  	if( empty($author) or empty($reply) ){
	  		$record = json_decode('{"code":500}');
	  		$record->msg = 'create 参数不完整';
	  	}
	  		
	  	return $record;
	}
	

}

/*keyword*/
class Dao_keyword extends Dao{
	
	function Dao_keyword(){
		
		$this->table = 'keyword';
		$this->_pagesize = 10;
	}
	function getListParams(){
		$params = json_decode('{}');
	  	$params->_page = $_GET['_page'];
	  	$params->_pagesize = $_GET['_pagesize'];
	  	$params->key = $_GET['key'];
	  

	  	return $params;
	}
	function getUpdateParams(){
		$record = json_decode('{}');
		
		$reply = getRequest('reply');
		$msgtypes = getRequest('msgtypes');
		$keys = getRequest('keys');
		$name = getRequest('name');
		$id = getRequest('id');
		
	  	$record->name =  $name;
	  	$record->reply =  $reply;
	  	$record->keys =  $keys;
	  	$record->msgtypes =  $msgtypes;
	  	$record->id = $id;
	  	
	  	if( empty($id) or empty($name) or empty($keys)  ){
	  		$record = json_decode('{"code":500}');
	  		$record->msg = 'update 参数不完整';
	  	}
	  	return $record;
	}
	function getDeleteParams(){
		$record = json_decode('{}');

		$id = $_GET['id'];
	  	$record->id = $id;
	  	
	  	if( empty($id) ){
	  		$record = json_decode('{"code":500}');
	  		$record->msg = 'id miss';
	  	}
	  	return $record;
	}
	function getCreateParams(){
		$record = json_decode('{}');

		$reply = getRequest('reply');
		$msgtypes = getRequest('msgtypes');
		$keys = getRequest('keys');
		$name = getRequest('name');
 
	  	$record->name =  $name;
	  	$record->reply =  $reply;
	  	$record->keys =  $keys;
	  	$record->msgtypes =  $msgtypes;
	  	if( empty($name) or empty($keys) or (empty($reply) and empty($msgtypes)) ){
	  		$record = json_decode('{"code":500}');
	  		$record->msg = 'create 参数不完整';
	  	}
	  		
	  	return $record;
	}
	

}

/* 中转站 */
class Station{
	function handle(){
		/*     */
		$actionList = array(
			'image.list'=>'Dao_image',
			'image.update'=>'Dao_image',
			'image.create'=>'Dao_image',
			'image.delete'=>'Dao_image',
			'image.get'=>'Dao_image',

			'text.list'=>'Dao_text',
			'text.update'=>'Dao_text',
			'text.create'=>'Dao_text',
			'text.delete'=>'Dao_text',
			'text.get'=>'Dao_text',

			'msgtype.list'=>'Dao_msgtype',
			'msgtype.update'=>'Dao_msgtype',
			'msgtype.create'=>'Dao_msgtype',
			'msgtype.delete'=>'Dao_msgtype',
			'msgtype.get'=>'Dao_msgtype',

			'about.list'=>'Dao_about',
			'about.update'=>'Dao_about',
			'about.create'=>'Dao_about',
			'about.delete'=>'Dao_about',
			'about.get'=>'Dao_about',

			'keyword.list'=>'Dao_keyword',
			'keyword.update'=>'Dao_keyword',
			'keyword.create'=>'Dao_keyword',
			'keyword.delete'=>'Dao_keyword',
			'keyword.get'=>'Dao_keyword',


		);
		
		$action = getRequest('action');

		sqlite_logger( 'action:'.$action );
		//echo "string action ".$action.'<br>';
		if( $action and $actionList[$action] ){
			$array = explode('.',$action);
			
			$_target = $array[0];
			$_action = $array[1];
			$_dao = $actionList[$action];
			$_fn = 'ajax_'.$_action;

			$dao = new $_dao();
			$ret = $dao->$_fn();
			echo $ret;
			exit();
			
			/*switch ($action){
				case 'image.list':
				  	$image = new Dao_image();

					$ret = $image->ajax_list();
					echo $ret;
					exit();
					break;  
				case 'image.update':
					$image = new Dao_image();                   

				  	$ret = $image->ajax_update();
				  	echo $ret;
				  	exit();
					break;
				case 'image.delete':
					$image = new Dao_image();
					$ret = $image->ajax_delete();
					echo $ret;  	
					
					exit();
					break;  
				case 'image.create':
					$image = new Dao_image();
					$ret = $image->ajax_create();
					echo $ret; 	
					
					exit();
					break;  
				case 'text.list':
				  	$text = new Dao_text();

					$ret = $text->ajax_list();
					echo $ret;
					exit();
					break; 
				case 'text.update':
					$text = new Dao_text();

				  	$ret = $text->ajax_update();
				  	echo $ret;
				  	exit();
					break;
				case 'text.delete':
					$text = new Dao_text();
					$ret = $text->ajax_delete();
					echo $ret;  	
					
					exit();
					break;  
				case 'text.create':
					$text = new Dao_text();
					$ret = $text->ajax_create();
					echo $ret; 	
					
					exit();
					break;

				case 'msgtype.list':
				  	$dao = new Dao_msgtype();

					$ret = $dao->ajax_list();
					echo $ret;
					exit();
					break; 
				case 'msgtype.update':
					$dao = new Dao_msgtype();

				  	$ret = $dao->ajax_update();
				  	echo $ret;
				  	exit();
					break;
				case 'msgtype.delete':
					$dao = new Dao_msgtype();
					$ret = $dao->ajax_delete();
					echo $ret;  	
					
					exit();
					break;  
				case 'msgtype.create':
					$dao = new Dao_msgtype();
					$ret = $dao->ajax_create();
					echo $ret; 	
					
					exit();
					break;      
				default:
					echo '{"code":404,"msg":"action未匹配 "}';
					break;
			}*/
					
			
		}else{
			echo '{"code":404,"msg":"miss action "}';
		}
	}
}
	
$station = new Station();
$station->handle();


function getRequest($name=''){
	$result = '';

	if($_POST[$name]){
		$result = $_POST[$name];
	}else if($_GET[$name]){
		$result = $_GET[$name];
	}

	return $result;
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