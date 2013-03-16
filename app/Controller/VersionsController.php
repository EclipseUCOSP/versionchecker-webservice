<?php
App::uses('Sanitize', 'Utility');

class VersionsController extends AppController{

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
		
		if ($this->request->isPost()){
			$array = array();
			$JSON = $this->request->input('json_decode',true);
			# cleaning the data and makes a string SQL-safe before sending to versioncheck() function
			foreach( $JSON as $obj ){
				# cleaning the data and making a SQL-safe string
				$idObj = Sanitize::escape($obj['component']);
				$versionObj = Sanitize::escape($obj['version']);
			}
			$cleanedData = array();
			$cleanedData[] = array("component"=> $idObj,
									"version"=> $versionObj);

			$array[] = $this->versionchecker($cleanedData);
			$this->set('sendJSON', $array);
			$this->autoLayout = false;
			$this->render('/Eclipse/SerializeJson/');
		}else{
			
			$this->autoLayout = false;
			$this->render('/Eclipse/Instruction');
			}
		}
			
	#}

	public function versionchecker($JSON){
		

		$results = array();
		foreach($JSON as $obj){
			$id = $obj['component'];
			$version = $obj['version'];
			# will try to implement this later, giving strange problems right now
			#$row =  $this->Version->find('first', array('conditions' => array('Version.id' => $id)));
			$rows =  $this->Version->find('all');
 			foreach($rows as $entry){
 				if(strcmp($entry['Version']['id'],$id)==0){
 					#part up to date
					#if (strcmp($row['p2_version'],$id)==0){
					#	$results=array("component"=> $obj['name'],
					#					"current"=> true
					#					);
					#}else{ 
						# not up to date
						#component is unavailable
						if (strcmp($entry['Version']['state'],"unavailable")==0){
							$results[]=array( "component"=> $id,
											"state"=>"unavailable"
											);   
						}else{
							#component is available or has alternative source
							# LTS support not implemented
							$results[]=array("component"=> $id,
						       				"state" => $entry['Version']['state'],
						       				"version"=> $entry['Version']['p2_version'],
											"repoinfo"=> array(
														"repo" => $entry['Version']['git_repo'],
	 													"commit" => $entry['Version']['git_commit'],
														"branch" => $entry['Version']['git_branch']
															)	
											);
					}
				}
			}
		}
		return $results;
		}


}
?>
