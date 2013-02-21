
<?php

class Eclipse extends Model {


	public function versioncheck($id,$version,$con){
		# echo "got to model";
		mysql_select_db("eclipsedb", $con) or die ("Connection Error");
		#improve this for real db
		$entry = mysql_query("SELECT * FROM version");
		while($row = mysql_fetch_array($entry)){	
 			if(strcmp($row['id'],$id)==0){
 				#testing prints ignore for now
 				#echo $id;
 				#echo "\n";
				#echo $row['id'];
				#up to date
				if (strcmp($row['p2_version'],$version)==0){
					$results=array("component"=> $id,
									"current"=> true
									);
				}else{ 
				# not up to dat
					$results=array("component"=> $id,
									"current"=> array("repo" => $row['git_repo'],
							 		 			 "commit" => $row['git_commit'],												  		"branch" => $row['git_branch']
													)
									);
					}
				}
			}
			return $results;
		}
	}
?>
