<?PHP
	class php_c_config{
		public $Accounts = False;
		public $SecretKey = NULL;
	}
	class php_c{

		private $Config;
		private $Security;
		private $Version;
		private $ErrorCodes;
		private $_DATA;

		public function __construct($Config){
			$this->Config = $Config;
			if($this->Config->Accounts == False){ $this->Security = "Unsecured"; }else{ $this->Security = "Secured"; }
			$this->Version = '1.0.0.0';
			$this->ErrorCodes = array(
				100 => 'Success',
				101 => 'Unauthorized',
				102 => 'Incompatible Client',
				103 => 'Internal Error',
				104 => 'Script Error',
				105 => 'No Response',
				106 => 'Security key is invalid',
				107 => 'Unauthorized! Missing \'user\'',
				108 => 'Unauthorized! Missing \'pswd\'',
				109 => 'Script not found',
				110 => 'Invalid/Null Data'
			);
			$this->setHeaders();
			if($this->Config->Accounts !== False){
				if($this->Config->SecretKey == NULL){
					$this->endSession(106);
				}elseif(strlen($this->Config->SecretKey) < 20){
					$this->endSession(106);
				}
			}
			
			if(count($_POST) > 0){
				$this->_DATA = $_POST;
			}elseif(count($_REQUEST) > 0){
				$this->_DATA = $_REQUEST;
			}else{
				$this->endSession(110);
			}
		}

		private function setHeaders(){
			header('Content-Type: application/json');
			header('Application-Version: ' . $this->Version);
			header('Application-Security: ' . $this->Security);
		}

		public function endSession($ResponseCode, $Data = array()){
			if(count($Data) > 0){
				die(json_encode(array(
					'status' => $ResponseCode,
					'message' => $this->ErrorCodes[$ResponseCode],
					'data' => $Data
				)));
			}else{
				die(json_encode(array(
					'status' => $ResponseCode,
					'message' => $this->ErrorCodes[$ResponseCode]
				)));
			}
			
		}

		private function createAuth($Username, $Password){
			return hash('sha256',  $Username . '/' . $this->Config->SecretKey . ':' . $Password);
		}

		public function initialize(){
			/*
				'arp' doesn't need Authorization to execute!
				This will be used by clients to determine
				if this is a valid php-c class and the valid
				version that is compatible with
			*/
			if(!empty($this->_DATA['arp'])){
				if($this->_DATA['arp'] == 'ping'){
					$this->endSession(100);
				}elseif($this->_DATA['arp'] == 'info'){
					$this->endSession(100, array('version'=>$this->Version, 'security'=>$this->Security));
				}
			}

			/*
				Verify if Auth is required, if so. Check if the AuthKey is valid
			*/
			if($this->Config->Accounts !== False){
				if(empty($this->_DATA['auth'])){
					$this->endSession(101);
				}
				if($this->_DATA['auth'] == 'getkey'){
					if(empty($this->_DATA['user'])){
						$this->endSession(107);
					}
					if(empty($this->_DATA['pswd'])){
						$this->endSession(108);
					}

					// Calculate AuthKey
					if(array_key_exists($this->_DATA['user'], $this->Config->Accounts)){
						$this->endSession(100, array('auth'=>$this->createAuth($this->_DATA['user'], $this->_DATA['pswd'])));
					}else{
						$this->endSession(101);
					}
				}else{
					if(empty($this->_DATA['user'])){
						$this->endSession(107);
					}
					if(array_key_exists($this->_DATA['user'], $this->Config->Accounts)){
						if($this->_DATA['auth'] !== $this->createAuth($this->_DATA['user'], $this->Config->Accounts[$this->_DATA['user']])){
							$this->endSession(101);
						}
					}
				}
			}
			$this->process();
			$this->endSession(105);
		}

		private function process(){
			if(!empty($this->_DATA['svrhook'])){

				switch($this->_DATA['svrhook']){

					case "time":
						$this->endSession(100, 
							array('time'=>date("h:i:sa"),'date'=>date("Y-m-d"))
						);
						break;

					case "hook":
						if(empty($this->_DATA['script'])){
							$this->endSession(109);
						}
						if(!file_exists($this->_DATA['script'] . '.php')){
							$this->endSession(109);
						}
						include($this->_DATA['script'] . '.php');
						$CScript = new CScript($this);
						$CScript->Execute();
						$this->endSession(105);
					default:
						$this->endSession(103);
						break;

				}

			}
		}
	}

?>