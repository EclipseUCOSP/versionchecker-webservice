
<?php

class EclipseMode extends Model {


	public function query($JSON=null){
		if (!$JSON) {
      		throw new NotFoundException(__('Invalid post'));
    	}
    	$results = array();
    	$results = $results + "{";
    	foreach ($JSON as $key->$id){
    		$query = "SELECT * from eclipse WHERE id = $id";
    		$result = mysql_query($query, eclipse);
    		# 1 is just position of query info for version, will need to be updated
    		if ($result[1] == $id->$p2_version){
    			$results = $results + "{
    									id=>$id->$id,
    									data=>true
    									}";
    		}
    		else{
    			$results = $results + "{
    									id=>$id->$id,
    									 date=>[$result[2], $result[3]],
    									 $result[4],$result[5]]
    									}";
    		}
    	}
    	$results = $results + "}";
	}
	$newJSON = json_encode($result);
	return $newJSON;
}

?>
