
<?php
class EclipseController extends AppController{
	
	protected static $_messages = array(
		JSON_ERROR_STATE_MISMATCH => 'Invalid or malformed JSON',
		JSON_ERROR_SYNTAX => 'Syntax error',
		JSON_ERROR_UTF8 => 'Malformed UTF-8 characters, possibly incorrectly encoded'
         );
	
	
	
	public function recievePOST($JSON){
		if ($this->request->isPost()){
			$JSON = $this->request->input('json_decode', true) ;
			throw new RuntimeException(static::$_messages[json_last_error()]);
			$sendJSON = $this->EclipseModel->versioncheck($JSON);
		}
		$this->RequestHandler->setContent('json', 'application/json');
		$this->set('sendJSON', $sendJSON);
		$this->render('/Eclipse/SerializeJson/');
		
	}
}
?>


