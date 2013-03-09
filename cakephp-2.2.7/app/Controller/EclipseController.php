<?php
App::uses('Sanitize', 'Utility');

class EclipseController extends AppController{

	#protected static $_messages = array(
	#	JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
		#JSON_ERROR_SYNTAX => 'Syntax error',
		#JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
      #   );



	public function recievePOST(){
		$array = array();
		if ($this->request->isPost()){
<<<<<<< HEAD
			# echo "its a post request!\n";
			$array = array();
			# recieve 
			$JSON = $this->request->input('json_decode', true);
			# open db here so we only have one instance open
			$con = mysql_connect("localhost:3306","root","root");
			foreach( $JSON as $obj ){
				# test prints
				# echo $obj['name'];
				# echo "\n";
				# echo $obj['version'];
				# cleaning the data and makes a string SQL-safe
				$nameObj = Sanitize::escape($obj['name']);
				$versionObj = Sanitize::escape($obj['version']);
				$array[] = $this->Eclipse->versioncheck($nameObj,$versionObj,$con);
				#$array[] = $sendJSON;
			}
			mysql_close($con);
======= 	
			$JSON = $this->request->input('json_decode',true);
			# cleaning the data and makes a string SQL-safe before sending to model
			foreach( $JSON as $obj ){
				$idObj = Sanitize::escape($obj['component']);
				$versionObj = Sanitize::escape($obj['version']);
			}
			$cleanedData = array();
			$cleanedData[] = array("component"=> $idObj,
									"version"=> $versionObj); 
			$array[] = $this->Eclipse->versionchecker($cleanedData);
>>>>>>> origin/mvcbranch
		}
		$this->set('sendJSON', $array);
		$this->autoLayout = false;
		$this->render('/Eclipse/SerializeJson/');
	}
}
?>
