<?php
class RequestController extends AppController{
	
	protected static $_messages = array(
		JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
		JSON_ERROR_SYNTAX => 'Syntax error',
		JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
         );
	
	public $components = array('RequestHandler');

	public function recievePOST(){

		if ($this->RequestHandler->requestedWith('json')) {

		#if ($this->request->isPost()){
			# echo "its a post request!\n";
			$array = array();
			# recieve 
			$decodedData = json_decode($this->data);
			#$JSON = $this->request->input('json_decode', true);
			# open db here so we only have one instance open
			#$con = mysql_connect("db457838872.db.1and1.com","dbo457838872","eclipse");
		#	foreach( $JSON as $obj ){
				# test prints
				# echo $obj['name'];
				# echo "\n";
				# echo $obj['version'];
				$array[] = $this->Request->response();
				#$obj['name'],$obj['version'],$con);
				#$array[] = $sendJSON;
			#}
		#	mysql_close($con);
			$this->RequestHandler->setContent('json', 'application/json' );
		#}

		// $this->set('sendJSON', $array);

		}
		$this->RequestHandler->renderAs('json', $array);
		// $this->render('/Eclipse/SerializeJson/');
	}

	
}
?>


