
<?php

class EclipseModel extends Model {


	public function versioncheck($JSON){
		$results = array();
		$con = mysql_connect("localhost:3306","root","root");
		mysql_select_db("eclipsedb", $con) or die ("Connection Error");
		foreach( $json_obj as $obj ){
			#get entry from the 'eclipse' database that matches the json id
			#$entry = this->find('first',array('conditions'=> array(eclipse.id => $obj->id)));
			$entry = mysql_query("SELECT * FROM version WHERE id = $obj->id");
			if ($entry["p2_version"] = $obj->version){
				$results[]=array("component"=> $obj->id,
								"current"=> true
								);
			}else{
				$results[]=array("component"=> $obj->id,
								# nested array for info for updating component
								# 2,3,4 are placeholders for now
								"current"=> array("repo" => $entry["git_repo"],
												  "commit" => $entry["git_commit"],
												  "branch" => $entry["git_branch"]
												)
								);

			}
			return $results;
		}
?>
