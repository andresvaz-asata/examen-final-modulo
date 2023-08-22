<?php
class User {	
   
	private $userTable = 'ticket_user';	
	private $conn;
	
	public function __construct($db){
        $this->conn = $db;
    }
	
	public function login(){
		if($this->email && $this->password) {			
			$sqlQuery = "
				SELECT * FROM ".$this->userTable." 
				WHERE email = ? AND password = ?";			
			$stmt = $this->conn->prepare($sqlQuery);
			$password = md5($this->password);
			$stmt->bind_param("ss", $this->email, $password);	
			$stmt->execute();
			$result = $stmt->get_result();
			if($result->num_rows > 0){
				$user = $result->fetch_assoc();
				$_SESSION["userid"] = $user['userid'];
				$_SESSION["role"] = $user['role'];
				$_SESSION["name"] = $user['name'];
				$_SESSION["email"] = $user['email'];				
				return 1;		
			} else {
				return 0;		
			}			
		} else {
			return 0;
		}
	}
	
	public function isAdmin (){
		if($_SESSION["role"] == 'admin') {
			return 1;
		} else {
			return 0;
		}
	}
	
	public function loggedIn (){
		if(!empty($_SESSION["userid"])) {
			return 1;
		} else {
			return 0;
		}
	}
}
?>