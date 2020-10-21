<?php

class User{
	private $USERID;
	private $USERNAME;
	private $USERKURS;
	private $ISADMIN;
	private $ISTRAINER;

	public function __construct($userid,$username,$userkurs,$isadmin,$istrainer) {
		$this->loginUser($userid,$username,$userkurs,$isadmin,$istrainer);
	}

	private function loginUser($userid,$username,$userkurs,$isadmin,$istrainer) {
		$this->USERID = $userid;
		$this->USERNAME = $username;
		$this->USERKURS = $userkurs;
		$this->ISADMIN = $isadmin;
		$this->ISTRAINER = $istrainer;
	}

	public function getUserID() {
		return $this->USERID;
	}

	public function getUserName() {
		return $this->USERNAME;
	}

	public function getUserKurs() {
		return $this->USERKURS;
	}

	public function getUserAdminStatus() {
		return $this->ISADMIN;
	}

	public function getUserTrainerStatus() {
		return $this->ISTRAINER;
	}
}


?>