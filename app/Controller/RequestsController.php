<?php
App::uses('Sanitize', 'Utility');

class RequestsController extends AppController{
	
	// protected static $_messages = array(
	// 	JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
	// 	JSON_ERROR_SYNTAX => 'Syntax error',
	// 	JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
 //         );
	
	public $components = array('RequestHandler' => array(
        									'viewClassMap' => array(
            								'json' => 'ApiKit.MyJson')));

	public function recievePOST(){
		
		if ($this->RequestHandler->isPost()){
			

			$JSON = $this->request->input('json_decode',true);

			$this->RequestHandler->setContent('json', 'application/json' ); 

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

			$request = array();
			$request = $this->versionchecker($cleanedData);
			
			$this->RequestHandler->renderAs($this, 'json');
			$this->set(array('request' => $request, '_serialize' => 'request'));
		
		}else{
			
			$this->autoLayout = false;
			$this->render('/Eclipse/Instructions/');
			}
	}

	public function versionchecker($JSON){
		$results = array();
		foreach($JSON as $obj){
			$id = $obj['component'];
			# find all of the components in the database which match the id of the component
			$rows =  $this->Request->find('all', array('conditions' => array('Request.project' => $id)));
			$size = sizeof($rows);
			# set the default state to alternative
			$state = 'alternative';
			if ($size==1){
				# a state can only be available if a version for the component is given in the json
				if(array_key_exists('version', $obj)) {
					if (strcmp($obj['version'],$rows[0]['Request']['p2_version'])==0){
						$state = 'available';
					}
				}else{
					$state = 'available';
				}
 				$results[]=array("component"=> $id,
						       	"state" => $state,
								"version"=> $rows[0]['Request']['p2_version'],
								"repoinfo"=> array(
													"repo" => $rows[0]['Request']['git_repo'],
	 												"commit" => $rows[0]['Request']['git_commit'],
													"branch" => $rows[0]['Request']['git_branch']
													)	
										);	



			}elseif($size>1){
				if(array_key_exists('version', $obj)) {
					$isAvailable = false;
					foreach ($rows as $entry){
						if (strcmp($obj['version'],$entry['Request']['p2_version'])==0){
							$results[]=array("component"=> $id,
						     			  	"state" => 'available',
											"version"=> $entry['Request']['p2_version'],
											"repoinfo"=> array(
															"repo" => $entry['Request']['git_repo'],
	 														"commit" => $entry['Request']['git_commit'],
															"branch" => $entry['Request']['git_branch']
													)	
										);	
							$isAvailable = true;
						}
					}
					if (!$isAvailable){
						foreach ($rows as $entry){
							$results[]=array("component"=> $id,
						     			  	"state" => 'alternative',
											"version"=> $entry['Request']['p2_version'],
											"repoinfo"=> array(
															"repo" => $entry['Request']['git_repo'],
	 														"commit" => $entry['Request']['git_commit'],
															"branch" => $entry['Request']['git_branch']
													)	
										);	
						
						}
					}
				}else{
						$latestVersion = $rows[0]['Request']['p2_version'];
 						foreach ($rows as $entry){
 							if ($entry['Request']['p2_version'] >= $latestVersion) {
								$latestVersion = $entry['Request']['p2_version'];
							}
 						}

 						$results[]=array("component"=> $id,
						       			"state" => 'available',
										"version"=> $latestVersion,
										"repoinfo"=> array(
															"repo" => $entry['Request']['git_repo'],
	 														"commit" => $entry['Request']['git_commit'],
															"branch" => $entry['Request']['git_branch']
													)	
										);	
 					
 				}
			}
		}
		return $results;
	}

	
}
?>