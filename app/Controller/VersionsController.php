<?php
App::uses('Sanitize', 'Utility');

class VersionsController extends AppController{

	#protected static $_messages = array(
	#	JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
		#JSON_ERROR_SYNTAX => 'Syntax error',
		#JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
      #   );



	public function recievePOST(){
		if ($this->request->isPost()){
			#$array = array();
			$JSON = $this->request->input('json_decode',true);
			# Cleaning the data and makes a string SQL-safe before sending to versioncheck() function
			# Method 1: using Sanitize::escape() to only escape SQL injections
			// foreach( $JSON as $obj ){
			// 	# cleaning the data and making a SQL-safe string
			// 	$idObj = Sanitize::escape($obj['component']);
			// 	$versionObj = Sanitize::escape($obj['version']);
			// }

			// $cleanedData = array();
			// $cleanedData[] = array("component"=> $idObj,
			// 						"version"=> $versionObj);

			# Method 2: using Sanitize::clean() - It is a industrial-strength, multi-purpose cleaner, meant to be used on entire arrays
			$cleanedData = array();
			$cleanedData = Sanitize::clean($JSON, array('odd_spaces' => true,
															'dollar' => true,
															'carriage' => true,
															'unicode' => true,
															'escape' => true,
															'backslash' => true));

			$array = $this->versionchecker($cleanedData);
			$this->set('sendJSON', $array);
			$this->autoLayout = false;
			$this->render('/Eclipse/SerializeJson/');
		}else{
			
			$this->autoLayout = false;
			$this->render('/Eclipse/Instructions');
			}
		}
			
	#}


public function versionchecker($JSON){
		$results = array();
		foreach($JSON as $obj){
			$id = $obj['component'];
			# find all of the components in the database which match the id of the component
			$rows =  $this->Version->find('all', array('conditions' => array('Version.project' => $id)));
			$size = sizeof($rows);
			# set the default state to alternative
			$state = 'alternative';
			if ($size==1){
				# a state can only be available if a version for the component is given in the json
				if(array_key_exists('version', $obj)) {
					if (strcmp($obj['version'],$rows[0]['Version']['p2_version'])==0){
						$state = 'available';
					}
				}else{
					$state = 'available';
				}
 				$results[]=array("component"=> $id,
						       	"state" => $state,
								"version"=> $rows[0]['Version']['p2_version'],
								"repoinfo"=> array(
													"repo" => $rows[0]['Version']['git_repo'],
	 												"commit" => $rows[0]['Version']['git_commit'],
													"branch" => $rows[0]['Version']['git_branch']
													)	
										);	




			}elseif($size>1){
				$isAvailable = false;
				if(array_key_exists('version', $obj)) {
					foreach ($rows as $entry){
						if (strcmp($obj['version'],$entry['Version']['p2_version'])==0){
							$isAvailable = true;
							$results[]=array("component"=> $id,
						     			  	"state" => 'available',
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
				if(!$isAvailable){
					$latestVersion = $rows[0]['Version']['p2_version'];
 					foreach ($rows as $entry){
 						if ($entry['Version']['p2_version'] >= $latestVersion) {
							$latestVersion = $entry['Version']['p2_version'];
						}
 					}

 					$results[]=array("component"=> $id,
						       			"state" => 'alternative',
										"version"=> $latestVersion,
										"repoinfo"=> array(
															"repo" => $entry['Version']['git_repo'],
	 														"commit" => $entry['Version']['git_commit'],
															"branch" => $entry['Version']['git_branch']
													)	
										);	
 				}
			}
		}
		return $results;
		}


}

?>