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

include('inc/header.php');
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
						<h2>Tickets</h2>
						
						<hr>
						
						<div class="btn-group">
							<a href="index.php?status=open">
								<button type="button" id="open" class="btn btn-default active"><?php echo $ticket->getTicketCountWithStatus('open'); ?> Open</button>
							</a>						
						</div>
						
						<div class="btn-group">
							<a href="index.php?status=closed">
								<button type="button" id="closed" class="btn btn-default"><?php echo $ticket->getTicketCountWithStatus('closed'); ?> Closed</button>
							</a>
						</div>
						
						<div class="btn-group">
							<a href="index.php?order=newest">
							<button type="button" id="newest" class="btn btn-default">Newest</button>
						</a>
					</div>
					
					<div class="btn-group">
						<a href="index.php?order=oldest">
							<button type="button" id="oldest" class="btn btn-default">Oldest</button>
						</a>
					</div>
					<div class="btn-group">
					<a href="./api tickets/mapatickets.php">
				
							<button type="button" id="oldest" class="btn btn-default">
							Mapa de tickets
							</button>
						</a>
					</div>
					
					
					
					<button type="button" class="btn btn-success pull-right" data-toggle="modal" data-target="#newIssue">Create Ticket</button>
					
					<div class="modal fade" id="newIssue" tabindex="-1" role="dialog" aria-labelledby="newIssue" aria-hidden="true">
						<div class="modal-wrapper">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header bg-blue">
										<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
										<h4 class="modal-title"><i class="fa fa-pencil"></i> Create New Ticket</h4>
									</div>
									<form id="ticketForm" method="post">
										<div class="modal-body">
											<div class="form-group">
												<input name="subject" type="text" class="form-control" placeholder="Subject">
											</div>	
											<div class="form-group">
												<select style="height:34px;" class="form-control" id="department" name="department">
													<option value=''>--Select Department--</option>
													<?php 
													$result = $ticket->departmentList();
													while ($department = $result->fetch_assoc()) { 	
													?>
														<option value="<?php echo $department['id']; ?>"><?php echo ucfirst($department['department']); ?></option>							
													<?php } ?>
												</select>
											</div>	
											<div class="form-group">
												<textarea name="message" class="form-control" placeholder="Please detail your issue or question" style="height: 120px;"></textarea>
											</div>											
										</div>
										<div class="modal-footer">
											<input name="action" type="hidden" value="createTicket">
											<button type="submit" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
											<button type="submit" class="btn btn-primary pull-right"><i class="fa fa-pencil"></i> Create</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
					<!-- END NEW TICKET -->
					
					 
					<div class="padding"></div>
					
					<div class="row">						
						<div class="col-md-12">
							<ul class="list-group fa-padding">
								<?php
								if(isset($_GET['userid']) && !empty($_GET['userid'])) {
									$ticket->userId = $_GET['userid'];
								}
								
								if(isset($_GET['status']) && !empty($_GET['status'])) {
									$ticket->status = $_GET['status'];
								} else if(isset($_GET['order']) && !empty($_GET['order'])) {
									$ticket->order = $_GET['order'];
								} else if(isset($_GET['mentioned']) && !empty($_GET['mentioned'])) {
									$ticket->mentioned = $_GET['mentioned'];
								}		
								
								$ticketResult = $ticket->getTicket();
								while ($ticketDetails = $ticketResult->fetch_assoc()) {
									$ticket->id = $ticketDetails["id"];
								?>
								<li class="list-group-item">
									<div class="media">
										<i class="fa fa-code pull-left"></i>
										<div class="media-body">
											<a href="ticket.php?ticket_id=<?php echo $ticketDetails["id"]; ?>"><strong><?php echo $ticketDetails['title']; ?></strong> <span class="number pull-right"># <?php echo $ticketDetails['id']; ?> </span></a>
											<p class="info">Opened by <a href="#"><?php echo $ticketDetails['name']; ?></a> <?php echo $ticket->timeElapsedString($ticketDetails['created']); ?> <i class="fa fa-comments"></i> <a href="#"><?php echo $ticket->getReplyCount(); ?> Reply</a></p>
										</div>
									</div>
								</li>
								<?php
								}
								?>								
							</ul>
						</div>						
					</div>
				</div>
			</div>
		</div>		
	</div>
</section>
</div>
