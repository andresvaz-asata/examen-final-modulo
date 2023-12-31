<?php
include_once 'config/Database.php';
include_once 'class/User.php';


$database = new Database();
$db = $database->getConnection();

$user = new User($db);

if($user->loggedIn()) {	
	header("Location: index.php");	
}

$loginMessage = '';
if(!empty($_POST["login"]) && !empty($_POST["email"]) && !empty($_POST["password"])) {	
	$user->email = $_POST["email"];
	$user->password = $_POST["password"];	
	if($user->login()) {
		header("Location: index.php");	
	} else {
		$loginMessage = 'Invalid login! Please try again.';
	}
} else if(!empty($_POST["login"])) {
	$loginMessage = 'Fill all fields.';
}
include('inc/header.php');
?>
<title>coderszine.com : Demo Ticketing System with PHP and MySQL</title>
<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>			
<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
<link href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">  
<link href="css/style.css" rel="stylesheet">
<div class="container">

<div class="container-fluid">
		<h2>Ticketing System with PHP and MySQL</h2>			
        <div class="col-md-6">                    
		<div class="panel panel-info">
			
			<div style="padding-top:30px" class="panel-body" >
				
				<?php if (!empty($loginMessage) && $loginMessage !='') { ?>
					<div id="login-alert" class="alert alert-danger col-sm-12"><?php echo $loginMessage; ?></div>                            
				<?php } ?>
				
				<form id="loginform" class="form-horizontal" role="form" method="POST" action="">                                    
					<div style="margin-bottom: 25px" class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
						<input type="text" class="form-control" id="email" name="email" value="<?php if(!empty($_POST["email"])) { echo $_POST["email"]; } ?>" placeholder="email" style="background:white;" required>                                     
					</div>                                
					<div style="margin-bottom: 25px" class="input-group">
						<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
						<input type="password" class="form-control" id="password" name="password" value="<?php if(!empty($_POST["password"])) { echo $_POST["password"]; } ?>" placeholder="password" required>
					</div>						
					
					<div style="margin-top:10px" class="form-group">                          
						<input type="submit" name="login" value="Login" class="btn btn-info">	
					</div>	
					
					
					<p>
						<h3>User Login</h3>
						<strong>Email: </strong>adam@coderszine.com<br>
						<strong>Password:</strong> 123<br><br>		
						
						<h3>User Login</h3>
						<strong>Email: </strong>smith@coderszine.com<br>
						<strong>Password:</strong> 123<br><br>									
					</p>					
				</form>   
				<div style="margin-top:10px" class="form-group">                          
					<a href="register.html" class="btn btn-info">Registrar cliente</a>
				</div>	
			</div>                     
		</div>  
	</div>       
    </div>  