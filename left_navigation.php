<div class="col-md-3">
	<div class="grid support">
		<div class="grid-body">				
			<ul>
			<li>
				<?php if(isset($_SESSION["userid"])) { echo "Logged in : ".ucfirst($_SESSION["name"]); } ?>  
				</li>	
				<li><a href="logout.php">Logout</a></li>
			</ul>					
			<hr>					
			<ul>
			<li class="active"><a href="index.php?status=open">Browse<span class="pull-right"></span></a></li>
				<li><a href="index.php">Ticket Listing<span class="pull-right"><?php echo $ticket->getTicketCount(); ?></span></a></li>			
				<li><a href="index.php?status=open&userid=<?php echo $_SESSION["userid"]; ?>">Created by you<span class="pull-right"><?php echo $ticket->getUsersTicket(); ?></span></a></li>
				<li><a href="index.php?mentioned=<?php echo $_SESSION["email"]; ?>&userid=<?php echo $_SESSION["userid"]; ?>">Mentioning you<span class="pull-right"><?php echo $ticket->getMentionedTicket(); ?></span></a></li>
				
			</ul>			
			<hr>		
		</div>
	</div>
</div>