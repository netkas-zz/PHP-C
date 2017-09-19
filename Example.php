<?PHP
	class CScript{
		private $Object;
		public function __construct($Object){
			$this->Object = $Object;
		}
		public function Execute(){
			$this->Object->endSession(100, array('all'=>'good'));
		}
	}
?>