
<?php
App::uses('Sanitize', 'Utility');

class EclipseController extends AppController{
	
	protected static $_messages = array(
		JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
		JSON_ERROR_SYNTAX => 'Syntax error',
		JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
         );
	
	
	
	public function recievePOST(){
		$array = array();
		if ($this->request->isPost()){
			# echo "its a post request!\n";
			$array = array();
			# recieve 
			$JSON = $this->request->input('json_decode', true);
			# cleaning the data and makes a string SQL-safe
			$JSON = Sanitize::escape($JSON);
			# open db here so we only have one instance open
			$con = mysql_connect("localhost:3306","root","root");
			foreach( $JSON as $obj ){
				# test prints
				# echo $obj['name'];
				# echo "\n";
				# echo $obj['version'];
				$array[] = $this->Eclipse->versioncheck($obj['name'],$obj['version'],$con);
				#$array[] = $sendJSON;
			}
			mysql_close($con);
		}
		$this->set('sendJSON', $array);
		$this->autoLayout = false;
		$this->render('/Eclipse/SerializeJson/');
	}
}
?>


