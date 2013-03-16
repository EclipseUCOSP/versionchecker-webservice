<?php
#App::uses('Sanitize', 'Utility');

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
			$array[] = $this->versionchecker($JSON);
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
		
		#database credentials
		#include('app/Config/dbinfo.php');
		#$link = mysql_connect($hostname, $username, $password)
		#							or die ("Database Connection Error");
		#mysql_select_db($database, $link) or die ("Connection Error");
		#remove db credentials from memory
	#	unset($database,$hostname,$username,$password);
		$results = array();
		foreach($JSON as $obj){
			$id = $obj['component'];
			$version = $obj['version'];
			$row =  $this->Version->find('first', array('conditions' => array('Version.id' => $id)));
			#while($row = mysql_fetch_array($entry)){
 			if (!$row){
 				#if(strcmp($entery['Version']['id'],$id)==0){
 					#part up to date
					#if (strcmp($row['p2_version'],$id)==0){
					#	$results=array("component"=> $obj['name'],
					#					"current"=> true
					#					);
					#}else{ 
						# not up to date
						#component is unavailable
						if (strcmp($row['Version']['state'],"unavailable")==0){
							$results[]=array( "component"=> $id,
											"state"=>"unavailable"
											);   
						}else{
							#component is available or has alternative source
							# LTS support not implemented
							$results[]=array("component"=> $id,
						       				"state" => $row['Version']['state'],
						       				"version"=> $row['Version']['p2_version'],
											"repoinfo"=> array(
														"repo" => $row['Version']['git_repo'],
	 													"commit" => $row['Version']['git_commit'],
														"branch" => $row['Version']['git_branch']
															)	
											);
					}
				#}
			}
		}
		#mysql_close($link);
		return $results;
		}


}
?>
