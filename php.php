<?php
$action = 'image.list';
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


		);
echo $actionList[$action];
if(in_array($action, $actionList)){
	echo 11;
}else{
	echo "string";
}


?>