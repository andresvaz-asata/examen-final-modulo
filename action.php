<?php
include_once 'config/Database.php';

include_once 'class/User.php';
include_once 'class/Ticket.php';

$database = new Database();
$db = $database->getConnection();

$ticket = new Ticket($db);

if(!empty($_POST['action']) && $_POST['action'] == 'createTicket') {
	$ticket->subject = $_POST["subject"];
	$ticket->department = $_POST["department"];
    $ticket->message = $_POST["message"];    
	$ticket->insert();
}

if(!empty($_POST['action']) && $_POST['action'] == 'replyTicket') {
	$ticket->ticketId = $_POST["ticketId"];
	$ticket->replyMessage = $_POST["message"];
	$ticket->saveTicketReply();
} 

if(!empty($_POST['action']) && $_POST['action'] == 'mentionUser') {
	$ticket->mentionTicketId = $_POST["mentionTicketId"];
	$ticket->mentionUser = $_POST["mentionUser"];
	$ticket->mentionUser();
}

if(!empty($_POST['action']) && $_POST['action'] == 'removeMentionEmail') {
	$ticket->mentionTicketId = $_POST["mentionTicketId"];
	$ticket->mentionEmail = $_POST["mentionEmail"];
	$ticket->removeMentionEmail();
}

if(!empty($_POST['action']) && $_POST['action'] == 'openTicket') {
	$ticket->ticketId = $_POST["ticketId"];
	$ticket->openTicket();
}

if(!empty($_POST['action']) && $_POST['action'] == 'closeTicket') {
	$ticket->ticketId = $_POST["ticketId"];
	$ticket->closeTicket();
}

?>