<?php

class Eclipse extends Model {


	public function versionchecker(array $JSON){
		# echo "got to model";
		$database='';
		$hostname='';
		$username='';
		$password='';
		$link = mysql_connect($hostname, $username, $password)
									or die ("Database Connection Error");
		mysql_select_db($database, $link) or die ("Connection Error");
		#improve this for real db
		$results = array();
		foreach($JSON as $obj){
			$id = $obj['name'];
			$version = $obj['version'];
			#$query = sprintf("SELECT * FROM version WHERE id='%s'",
			#						mysql_real_escape_string($obj['name']));
			$entry = mysql_query("SELECT * FROM version");
			while($row = mysql_fetch_array($entry)){
 			if(strcmp($row['id'],$obj['name'])==0){
				if (strcmp($row['p2_version'],$obj['version'])==0){
					$results=array("component"=> $obj['name'],
									"current"=> true
									);
				}else{ 
					# not up to dat
					$results=array( "component"=> $obj['name'],
									"current"=> array(
													"repo" => $row['git_repo'],
			 					  					"commit" => $row['git_commit'],
								   					"branch" => $row['git_branch'],
								 					"LTS" => $row['LTS']
													)
								);
					}
				}
			}
		}
		mysql_close($link);
		return $results;
		}			
	}
?>