<?php
class EclipseController extends AppController{

	#protected static $_messages = array(
	#	JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
		#JSON_ERROR_SYNTAX => 'Syntax error',
		#JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
      #   );



	public function recievePOST(){
		$array = array();
		if ($this->request->isPost()){
			$JSON = $this->request->input('json_decode',true);
			$array[] = $this->Eclipse->versionchecker($JSON);
		}
		$this->set('sendJSON', $array);
		$this->autoLayout = false;
		$this->render('/Eclipse/SerializeJson/');
	}
}
?>