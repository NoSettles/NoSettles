<?php
include_once("php_includes/check_login_status.php");
// If user is already logged in, header that weenis away
if($log_username != ""){
	header("location: User.php?u=".$_SESSION["username"]);
  exit();
}
?><?php
// AJAX CALLS THIS CODE TO EXECUTE
if(isset($_POST["e"])){
	$e = mysqli_real_escape_string($db_conx, $_POST['e']);
	$sql = "SELECT id, username FROM users WHERE email='$e' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
	if($numrows > 0){
		while($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
			$id = $row["id"];
			$u = $row["username"];
		}
		$emailcut = substr($e, 0, 4);
		$randNum = rand(10000,99999);
		$tempPass = "$emailcut$randNum";
		$hashTempPass = md5($tempPass);
		$sql = "UPDATE useroptions SET temp_pass='$hashTempPass' WHERE username='$u' LIMIT 1";
	    $query = mysqli_query($db_conx, $sql);
		$to = "$e";
		$from = "auto_responder@nosettles.com";
		$headers ="From: $from\n";
		$headers .= "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1 \n";
		$subject ="yoursite Temporary Password";
		$msg = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>NoSettles</title><style>a{text-decoration: none;color: #3EA7FD;}a:hover{color: #0073c0;text-decoration: underline;}h2{display: inline-block;margin-left: 20px;margin-top: 10px;color: #fff;}body{background-color: #EFEFEF;}</style></head><body style="margin:0px; background-color: #eee; font-family:Tahoma, Geneva, sans-serif;"><div style="padding:10px; background:#0073C0; font-size:24px; color:#CCC; height:60px;"><a href="http://www.nosettles.com"><img src="http://www.nosettles.com/style/logo.png" width="60" height="50" alt="NoSettles" style="border:none; float:left;"></a><h2>Hello '.$u.'</h2></div><p>This is an automated message from NoSettles. If you did NOT recently initiate the Forgot Password process, please disregard this email.</p><p>You indicated that you forgot your login password. We can generate a temporary password for you to log in with, then once logged in you can change your password to anything you like.</p><p>After you click the link below your password to login will be:<br /><b>'.$tempPass.'</b></p><p><a href="http://www.nosettles.com/forgot_pass.php?u='.$u.'&p='.$hashTempPass.'">Click here now to apply the temporary password shown above to your account</a></p><p>If you do not click the link in this email, no changes will be made to your account. In order to set your login password to the temporary password you must click the link above.</p></body></html>';
		if(mail($to,$subject,$msg,$headers)) {
			echo "success";
			exit();
		} else {
			echo "email_send_failed";
			exit();
		}
    } else {
        echo "no_exist";
    }
    exit();
}
?><?php
// EMAIL LINK CLICK CALLS THIS CODE TO EXECUTE
if(isset($_GET['u']) && isset($_GET['p'])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
	$temppasshash = preg_replace('#[^a-z0-9]#i', '', $_GET['p']);
	if(strlen($temppasshash) < 10){
		exit();
	}
	$sql = "SELECT id FROM useroptions WHERE username='$u' AND temp_pass='$temppasshash' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$numrows = mysqli_num_rows($query);
	if($numrows == 0){
		header("location: message.php?msg=There is no match for that username with that temporary password in the system. We cannot proceed.");
  	exit();
	} else {
		$row = mysqli_fetch_row($query);
		$id = $row[0];
		$sql = "UPDATE users SET password='$temppasshash' WHERE id='$id' AND username='$u' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
		$sql = "UPDATE useroptions SET temp_pass='' WHERE username='$u' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    header("location: index.php");
    exit();
  }
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>NoSettles | Forgot Password</title>
<meta name="description" content="Forgot your password? We'll fix it in no time.">
<?php include_once("head.php"); ?>

<script type="text/javascript">
function forgotpass(){
	var e = _("email").value;
	if(e == ""){
		_("status").innerHTML = "Please type in your email address";
	} else {
		var ajax = ajaxObj("POST", "forgot_pass.php");
        ajax.onreadystatechange = function() {
	        if(ajaxReturn(ajax) == true) {
				var response = ajax.responseText;
				if(response == "success"){
					$('.border-lightgray').html('<h1 style="color: #1AB188;" class="center-text med-margin-top"><i class="fa fa-check-circle-o fa-3x"></i></h1><h2 class="font-gray center-text">Awesome! We Have successfully sent you your temporary password. Please check your email.</h2>');
				} else if (response == "no_exist"){
					_("fgp-status").innerHTML = "Sorry that email address is not in our system";
				} else if(response == "email_send_failed"){
					_("fgp-status").innerHTML = "Mail function failed to execute";
				} else {
					_("fgp-status").innerHTML = "An unknown error occurred";
				}
	        }
        }
        ajax.send("e="+e);
	}
}
</script>
</head>

<!-- <input id="email" type="text" class="textBox textBox1 fptxt" placeholder="Email Adress" onfocus="_('status').innerHTML='';" maxlength="88"> -->
<!-- <button id="forgotpassbtn" class="logIn fpBttn" onclick="forgotpass()">Generate Temporary Log In Password</button> -->


<body>
	<?php include_once("template_PageTop.php"); ?>


	<main>
		<div class="parallax">
			<div class="pageMiddle">
				<h1 class="flex-box-between margin-none small-margin-top">Forgot Password?<span>Don't worry, we'll fix it.</span></h1>
        <hr>

				<div class="border-lightgray">

          <h2 class="center-text gray margin-none small-margin-top"><i class="fa fa-lock fa-2x"></i></h2>
          <h1 class="center-text gray margin-none"></i>Email Password</h1>

          <form class="fancy-form half-width" onsubmit="return false;">

            <div class="fancy-text-form fancy-login-form">
              <input type="email"  id="email" class="transparent-blue-box med-long-textbox password-required" />
              <label>Email</lable>
            </div>

            <div class="fancy-text-form">
              <input type="submit" id="forgotpassbtn" value="Continue" class="med-long-btn transparent-blue-btn submit-password" onclick="forgotpass()" /><br>
              <span id="fgp-status" class="display-block small-margin-bottom" style="color: #f00; font-weight: bold;"></span><br>
							<a href="contact.php" class="transparent-blue-btn med-btn display-none">Contact Us</a>

            </div>
          </form>
        </div>

        <div class="dummy-space large-margin-top">

        </div>


			</div>
		</div>
	</main>


  <?php include_once("template_PageBottom.php"); ?>

</body>
</html>
