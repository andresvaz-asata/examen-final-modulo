<?php
include_once 'config/Database.php';

include_once 'class/User.php';
include_once 'class/Ticket.php';

$database = new Database();
$db = $database->getConnection();

$user = new User($db);
$ticket = new Ticket($db);

if(!$user->loggedIn()) {	
	header("Location: login.php");	
}

include('../header.php');
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>coderszine.com : Demo Ticketing System with PHP & MySQL</title>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>	
<link rel="stylesheet" href="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"> <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"></script> 
<script src="https://netdna.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script> 
<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">
<script src="js/ticket.js"></script>
<link href="css/style.css" rel="stylesheet">
<div class="container">
<section class="content">
	<div class="row">		
		<?php include('left_navigation.php'); ?>
		<!-- END NAV TICKET -->
		<!-- BEGIN TICKET -->
		<div class="col-md-9">
			<div class="grid support-content">
				 <div class="grid-body">
					 <h2>Ticket Details</h2>
					 
					 <hr>					
					
					Mentioned : 
					
					<?php
					if(!empty($_GET['ticket_id']) && $_GET['ticket_id']) {
						$ticket->ticket_id = $_GET['ticket_id'];
					}
					$mentionResult = $ticket->getMentionUser();
					while ($mention = $mentionResult->fetch_assoc()) {
						$mentionDetails = explode(",",$mention['mentioned']);
						$count = 1;
						$comma = '';
						foreach($mentionDetails AS $mentionEmail){
							if($mentionEmail) {
								echo $comma.$mentionEmail.'<a id="removeMentionEmail_'.$count.'" data-mention-email="'.$mentionEmail.'" data-ticket-id="'.$_GET['ticket_id'].'"><span class="glyphicon glyphicon-remove-circle"></span></a>';
								$comma = ', ';
								$count++;
							}
						}
					}
					?>
					<a class="btn btn-info btn-xs" id="mentionUser" data-ticket-id="<?php echo $_GET['ticket_id']; ?>">Add</a>
					 
					<div class="padding"></div>
					 
					<div class="row">						
						<div class="col-md-12">
							<ul class="list-group fa-padding">
								<?php									
								$ticketResult = $ticket->getTicketDetail();
								$replyCount = 0;
								while ($ticketDetails = $ticketResult->fetch_assoc()) {	
														
								?>									
								<?php if($replyCount == 0) { ?>
								<li class="list-group-item" id="ticketReplyDetails">
									<div class="row">
									
										<?php
										if($ticketDetails['status'] == 'closed') {
										?>
										<a class="btn btn-warning" title="Make Open" data-ticket-id="<?php echo $ticketDetails['id']; ?>" id="openTicket">Open Ticket</a>
										<?php } else { ?>
										<a class="btn btn-danger" title="Make Closed" data-ticket-id="<?php echo $ticketDetails['id']; ?>" id="closeTicket">Close Ticket</a>
										<?php } ?>
										
										<?php 
										if($ticketDetails['status'] == 'open') {
										?>										&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
										<a class="btn btn-default" data-ticket-id="<?php echo $ticketDetails['id']; ?>" id="ticketReplyButton" title="Reply to ticket">Reply</a>
										<?php } ?>
									</div>								
								</li>
								<li class="list-group-item">
									<div class="media">
										<div class="media-body">
											<div>
											<span class="number pull-right"># <?php echo $ticketDetails['id']; ?> </span>
											<span style="font-size:26px;padding-bottom:10px;"><?php echo $ticketDetails['title']; ?></span>
											
											<?php 
											if($ticketDetails['status'] == 'open') {
												echo "<span style='color:red;font-weight:bold;background-color:black;padding:4px;'>Open</span>";
											} else if($ticketDetails['status'] == 'closed') {
												echo "<span style='color:green;font-weight:bold;background-color:black;padding:4px;'>Closed</span>"; 
											}										
											
											?>											
											</span>
											</div>
											<p class="info">Replied by <a href="#"><?php echo $ticketDetails['name']; ?></a> <?php echo $ticket->timeElapsedString($ticketDetails['created']); ?> <i class="fa fa-comments"></i> 					
											
											</p>
											
											<p><?php echo $ticketDetails["message"]; ?></p>
										</div>
									</div>
								</li>
								<?php } ?>		
								<?php if($ticketDetails["comments"]) { ?>								
								<li class="list-group-item">
									<div class="media">
										<div class="media-body">										
											<p class="info">Replied by <a href="#"><?php echo $ticketDetails['name']; ?></a> <?php echo $ticket->timeElapsedString($ticketDetails['reply_date']); ?> <i class="fa fa-comments"></i> 					
											
											</p>
											
											<p><?php echo $ticketDetails["comments"]; ?></p>
										</div>
									</div>
								</li>
								<?php } ?>
								
								<?php
								$replyCount++;	
								}
								?>								
							</ul>						
						</div>						
					</div>
				</div>
			</div>
			
			
			<div class="modal fade" id="ticketReplyModal" tabindex="-1" role="dialog" aria-labelledby="ticketReply" aria-hidden="true">
				<div class="modal-wrapper">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header bg-blue">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h4 class="modal-title"><i class="fa fa-pencil"></i> Reply to ticket</h4>
							</div>
							<form id="replyForm" method="post">
								<div class="modal-body">								
									<div class="form-group">
										<textarea name="message" class="form-control" placeholder="Please detail your issue or question" style="height: 120px;"></textarea>
									</div>											
								</div>
								<div class="modal-footer">
									<input name="ticketId" id="ticketId" type="hidden" value="">
									<input name="action" type="hidden" value="replyTicket">
									<button type="submit" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
									<button type="submit" id="save" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> Reply</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
			
			
			<div class="modal fade" id="mentionModal" tabindex="-1" role="dialog" aria-labelledby="ticketReply" aria-hidden="true">
				<div class="modal-wrapper">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header bg-blue">
								<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
								<h4 class="modal-title"><i class="fa fa-pencil"></i> Mention user</h4>
							</div>
							<form id="mentionForm" method="post">
								<div class="modal-body">								
									<div class="form-group">
										<input type="email" name="mentionUser" class="form-control" placeholder="Enter email"></textarea>
									</div>											
								</div>
								<div class="modal-footer">
									<input name="mentionTicketId" id="mentionTicketId" type="hidden" value="">
									<input name="action" type="hidden" value="mentionUser">
									<button type="submit" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
									<button type="submit" id="save" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> Add</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>
					
					
		</div>		
	</div>
</section>
</div>