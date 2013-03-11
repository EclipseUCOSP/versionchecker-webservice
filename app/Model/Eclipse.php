<?php

class Eclipse extends Model {


	public function versionchecker($JSON){
		
		#database credentials
		include('app/Config/dbinfo.php');
		$link = mysql_connect($hostname, $username, $password)
									or die ("Database Connection Error");
		mysql_select_db($database, $link) or die ("Connection Error");
		#remove db credentials from memory
		unset($database,$hostname,$username,$password);
		$results = array();
		foreach($JSON as $obj){
			$id = $obj['component'];
			$version = $obj['version'];
			$entry = mysql_query("SELECT * FROM version");
			while($row = mysql_fetch_array($entry)){
 				if(strcmp($row['id'],$id)==0){
 					#part up to date
					#if (strcmp($row['p2_version'],$id)==0){
					#	$results=array("component"=> $obj['name'],
					#					"current"=> true
					#					);
					#}else{ 
						# not up to date
						#component is unavailable
						if (strcmp($row['state'],"unavailable")==0){
							$results[]=array( "component"=> $id,
											"state"=>"unavailable"
											);   
						}else{
							#component is available or has alternative source
							# LTS support not implemented
							$results[]=array("component"=> $id,
						       				"state" => $row['state'],
						       				"version"=> $row['p2_version'],
											"repoinfo"=> array(
														"repo" => $row['git_repo'],
	 													"commit" => $row['git_commit'],
														"branch" => $row['git_branch']
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