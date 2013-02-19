
<?php

class EclipseModel extends Model {


	public function versioncheck($JSON){
		$results = array();
		foreach( $json_obj as $obj ){
			#get entry from the 'eclipse' database that matches the json id
			$entry = this->find('first',array('conditions'=> array(eclipse.id => $obj->id)));
			if ($entry[1] == $obj->version){
				$results[]=array("component"=> $obj->id,
								"current"=> true
								);
			}else{
				$results[]=array("component"=> $obj->id,
								# nested array for info for updating component
								# 2,3,4 are placeholders for now
								"current"=> array("repo" => $entry[2],
												  "commit" => $entry[3],
												  "branch" => $entry[4]

												)
								);

			}
			return $results;
		}
?>
