<?php
class Ticket {	
   
	private $ticketsTable = 'tickets';	
	private $ticketReplyTable = 'ticket_replies';
	private $departmentTable = 'department';
	private $userTable = 'ticket_user';
	private $conn;
	
	public function __construct($db){
        $this->conn = $db;
		
    }
	
	public function insert(){
		
		if($this->subject && $this->message) {

			$stmt = $this->conn->prepare("
			INSERT INTO ".$this->ticketsTable."(`title`, `message`, `userid`, `department_id`)
			VALUES(?,?,?,?)");
		
			$this->subject = htmlspecialchars(strip_tags($this->subject));
			$this->message = htmlspecialchars(strip_tags($this->message));
			$this->department = htmlspecialchars(strip_tags($this->department));			
			
			$stmt->bind_param("ssii", $this->subject, $this->message, $_SESSION["userid"], $this->department);
			
			if($stmt->execute()){
				return true;
			}		
		}
	}

	public function getTicket(){		
		$sqlWhere = '';		

		
		$status = 'open';
		$order = ' ORDER BY id DESC';
		if(!empty($this->status) && $this->status == 'closed') {
			$status = 'closed';
		} elseif(!empty($this->order) && $this->order == 'oldest') {
			$order = ' ORDER BY id ASC';
		} 
		
		if(!empty($this->mentioned) && $this->mentioned) {
			$sqlWhere .= " AND ticket.mentioned like '%".$this->mentioned."%'";
		} else if(!empty($this->userId)) {
			$sqlWhere = " AND ticket.userid = '".$this->userId."'";
		}		

		
		$sqlQuery = "
			SELECT ticket.id, ticket.title, ticket.message, ticket.userid, ticket.mentioned, ticket.created, ticket.status, user.name
			FROM ".$this->ticketsTable." ticket
			LEFT JOIN ".$this->userTable." user ON user.userid = ticket.userid
			LEFT JOIN ".$this->departmentTable." department ON department.id = ticket.department_id
			WHERE ticket.status = '".$status."' $sqlWhere $order";	
			
		$stmt = $this->conn->prepare($sqlQuery);			
		$stmt->execute();
		$result = $stmt->get_result();
		return $result;
	}	
	
	
	public function getTicketDetail(){			
		if($_SESSION["userid"] && $this->ticket_id) {				
			$sqlQuery = "
				SELECT ticket.id, ticket.title, ticket.message, ticket.userid, ticket.mentioned, ticket.created, ticket.status, user.name, reply.comments, reply.created AS reply_date
				FROM ".$this->ticketsTable." ticket
				LEFT JOIN ".$this->ticketReplyTable." reply ON ticket.id = reply.ticket_id
				LEFT JOIN ".$this->userTable." user ON user.userid = ticket.userid				
				WHERE ticket.id = '".$this->ticket_id."'";	
				
			$stmt = $this->conn->prepare($sqlQuery);			
			$stmt->execute();
			$result = $stmt->get_result();
			return $result;
		}
	}
	

	function getTicketCountWithStatus ($status) {		
		$sqlWhere = '';
			
		$stmt = $this->conn->prepare("SELECT count(*) AS total
		FROM ".$this->ticketsTable." 
		WHERE status = ? $sqlWhere");		
		$stmt->bind_param("s", $status);
		$stmt->execute();			
		$result = $stmt->get_result();	
		$reply = $result->fetch_assoc();
		return $reply['total'];
		
	}
	
	
	public function getReplyCount() {
		if($this->id) {
			$stmt = $this->conn->prepare("SELECT count(*) AS total
			FROM ".$this->ticketReplyTable." 
			WHERE id = ?");		
			$stmt->bind_param("i", $this->id);
			$stmt->execute();			
			$result = $stmt->get_result();	
			$reply = $result->fetch_assoc();
			return $reply['total'];
		}
	}
	
	
	function saveTicketReply() {
		
		if($_SESSION["userid"] && $this->ticketId && $this->replyMessage) {
			
			$stmt = $this->conn->prepare("
			INSERT INTO ".$this->ticketReplyTable."(`ticket_id`, `comments`, `created_by`)
			VALUES(?,?,?)");
		
			$this->replyMessage = htmlspecialchars(strip_tags($this->replyMessage));
			$this->ticketId = htmlspecialchars(strip_tags($this->ticketId));
						
			$stmt->bind_param("iss", $this->ticketId, $this->replyMessage, $_SESSION["userid"]);
			
			if($stmt->execute()){
				return true;
			}		
		}	
		
	}
	
	
	function openTicket() {		
		if($_SESSION["userid"] && $this->ticketId) {
			
			$stmt = $this->conn->prepare("
			UPDATE ".$this->ticketsTable." 
			SET status = 'open' 
			WHERE id = ?");	
			
			$this->ticketId = htmlspecialchars(strip_tags($this->ticketId));
						
			$stmt->bind_param("i", $this->ticketId);
			
			if($stmt->execute()){
				return true;
			}		
		}		
	}
	
	function closeTicket() {		
		if($_SESSION["userid"] && $this->ticketId) {
			
			$stmt = $this->conn->prepare("
			UPDATE ".$this->ticketsTable." 
			SET status = 'closed' 
			WHERE id = ?");	
			
			$this->ticketId = htmlspecialchars(strip_tags($this->ticketId));
						
			$stmt->bind_param("i", $this->ticketId);
			
			if($stmt->execute()){
				return true;
			}		
		}		
	}
	
	function mentionUser() {		
		if($_SESSION["userid"] && $this->mentionUser) {
			
			$stmt = $this->conn->prepare("
			UPDATE ".$this->ticketsTable." 
			SET mentioned = CONCAT(mentioned,',$this->mentionUser')
			WHERE id = ?");	
			
			$this->mentionTicketId = htmlspecialchars(strip_tags($this->mentionTicketId));
						
			$stmt->bind_param("i", $this->mentionTicketId);
			
			if($stmt->execute()){
				return true;
			}		
		}		
	}
	
	function removeMentionEmail() {		
		if($_SESSION["userid"] && $this->mentionTicketId && $this->mentionEmail) {
			
			$stmt = $this->conn->prepare("
			UPDATE ".$this->ticketsTable." 
			SET mentioned = REPLACE(mentioned, '".$this->mentionEmail."', '')
			WHERE id = ?");	
			
			$this->mentionTicketId = htmlspecialchars(strip_tags($this->mentionTicketId));
						
			$stmt->bind_param("i", $this->mentionTicketId);
			
			if($stmt->execute()){
				return true;
			}		
		}		
	}
	
	function getMentionUser() {
		if($this->ticket_id) {
			$sqlQuery = "
				SELECT mentioned 
				FROM ".$this->ticketsTable." 
				WHERE id = ?";			
			$stmt = $this->conn->prepare($sqlQuery);
			$this->ticket_id = htmlspecialchars(strip_tags($this->ticket_id));
			$stmt->bind_param("i", $this->ticket_id);
			$stmt->execute();
			$result = $stmt->get_result();
			return $result;
		}
	}
	
	function getTicketCount() {
		$sqlQuery = "
		SELECT * FROM ".$this->ticketsTable." 
		WHERE status = 'open'";			
		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->num_rows;
	}
	
	function getUsersTicket() {
		$sqlQuery = "
		SELECT * FROM ".$this->ticketsTable." 
		WHERE status = 'open' AND userid = ?";			
		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->bind_param("i", $_SESSION["userid"]);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->num_rows;
	}
	
	function getMentionedTicket() {
		$sqlQuery = "
		SELECT * FROM ".$this->ticketsTable." 
		WHERE mentioned like '%".$_SESSION["email"]."%'";			
		$stmt = $this->conn->prepare($sqlQuery);
		$stmt->execute();
		$result = $stmt->get_result();
		return $result->num_rows;
	}
	
	/*************** department list ************/
	
	function departmentList(){		
		$stmt = $this->conn->prepare("SELECT id, department, status 
		FROM ".$this->departmentTable);				
		$stmt->execute();			
		$result = $stmt->get_result();		
		return $result;	
	}	
	
	function timeElapsedString($datetime, $full = false) {
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);

		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;

		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}

		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
}
?>