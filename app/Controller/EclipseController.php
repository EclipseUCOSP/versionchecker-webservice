<?php
#App::uses('Sanitize', 'Utility');

class EclipseController extends AppController{

	#protected static $_messages = array(
	#	JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
		#JSON_ERROR_SYNTAX => 'Syntax error',
		#JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
      #   );


/*
THIS IS WHAT WE ARE WORKING ON FOR INPUT SANITIZATION, STILL HAVING AN ERROR
SO I INCLUDED THE WORKING VERSION ATT HE BOTTOM
	public function recievePOST(){
		$array = array();
		if ($this->request->isPost()){

			# echo "its a post request!\n";
			$array = array();
			# recieve 
			$JSON = $this->request->input('json_decode', true);
			# open db here so we only have one instance open
			$con = mysql_connect("localhost:3306","root","root");
			foreach( $JSON as $obj ){
				# cleaning the data and makes a string SQL-safe
				$nameObj = Sanitize::escape($obj['name']);
				$versionObj = Sanitize::escape($obj['version']);
				$array[] = $this->Eclipse->versioncheck($nameObj,$versionObj,$con);
				#$array[] = $sendJSON;
			}
			mysql_close($con);

			$JSON = $this->request->input('json_decode',true);
			# cleaning the data and makes a string SQL-safe before sending to model
			foreach( $JSON as $obj ){
				$idObj = Sanitize::clean($obj['component'], array('escape' => true));
				$versionObj = Sanitize::clean($obj['version'], array('escape' => true));
			}
			$cleanedData = array();
			$cleanedData[] = array("component"=> $idObj,
									"version"=> $versionObj); 
			$array[] = $this->Eclipse->versionchecker($cleanedData);

		}
		$this->set('sendJSON', $array);
		$this->autoLayout = false;
		$this->render('/Eclipse/SerializeJson/');
	}
	*/


	public function recievePOST(){
		$array = array();
		if ($this->request->isPost()){
			$JSON = $this->request->input('json_decode',true);
			$array[] = $this->Eclipse->versionchecker($JSON);
		}
		$this->set('sendJSON', $array);
		$this->autoLayout = false;
		$this->render('/Eclipse/SerializeJson/');
	}


}
?>
