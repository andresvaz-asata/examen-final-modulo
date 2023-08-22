$(document).ready(function(){

	var urlPath = window.location.search;
	var tabId = urlPath.split('=').pop();    
	$('#open, #closed, #newest, #oldest').removeClass('active');	
	$('#'+tabId).addClass('active');
	
	$("#ticketForm").submit(function(event){	
		saveTicket();	
		return false;
	});
	
	$('#ticketReplyButton').click(function(){
		var ticketId = $(this).attr("data-ticket-id");		
		$('#ticketReplyModal').on("shown.bs.modal", function () {
			$('#ticketId').val(ticketId);
		}).modal({
			backdrop: 'static',
			keyboard: false
		});		
	});
	
	$("#ticketReplyModal").on('submit','#replyForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#replyForm')[0].reset();
				$('#ticketReplyModal').modal('hide');				
				$('#save').attr('disabled', false);	
				location.reload();
			}
		})
	});	
	
	$("#ticketReplyDetails").on('click','#openTicket', function(event){
		var ticketId = $(this).attr("data-ticket-id");
		var action = "openTicket";
		$.ajax({
			url:"action.php",
			method:"POST",
			data:{ticketId:ticketId, action:action},
			success:function(data) {					
				location.reload();
			}
		});
		
	});
	
	$("#ticketReplyDetails").on('click','#closeTicket', function(event){
		var ticketId = $(this).attr("data-ticket-id");
		var action = "closeTicket";
		$.ajax({
			url:"action.php",
			method:"POST",
			data:{ticketId:ticketId, action:action},
			success:function(data) {					
				location.reload();
			}
		});
	});
	
	$('#mentionUser').click(function(){
		var ticketId = $(this).attr("data-ticket-id");		
		$('#mentionModal').on("shown.bs.modal", function () {
			$('#mentionTicketId').val(ticketId);
		}).modal({
			backdrop: 'static',
			keyboard: false
		});		
	});
	
	
	$("#mentionModal").on('submit','#mentionForm', function(event){
		event.preventDefault();
		$('#save').attr('disabled','disabled');
		var formData = $(this).serialize();
		$.ajax({
			url:"action.php",
			method:"POST",
			data:formData,
			success:function(data){				
				$('#mentionForm')[0].reset();
				$('#mentionModal').modal('hide');				
				$('#save').attr('disabled', false);	
				location.reload();
			}
		})
	});	
	
	$('[id^=removeMentionEmail_]').click(function(e){		
		var mentionEmail = $(this).attr('data-mention-email');
		var ticketId = $(this).attr("data-ticket-id");	
		console.log(mentionEmail);
		var action = "removeMentionEmail";
		$.ajax({
			url:"action.php",
			method:"POST",
			data:{mentionTicketId:ticketId, mentionEmail : mentionEmail, action:action},
			success:function(data) {					
				location.reload();
			}
		});
		
	});
	
});


function saveTicket(){
	 $.ajax({
		type: "POST",
		url: "action.php",
		cache:false,
		data: $('form#ticketForm').serialize(),
		success: function(response){			
			$("#newIssue").modal('hide');
			location.reload();
		},
		error: function(){
			alert("Error");
		}
	});
}