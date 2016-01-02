<?php
	include_once("php_includes/check_login_status.php");


  // $sql = "SELECT allow FROM users WHERE username='$log_username' LIMIT 1";
  // $query = mysqli_query($db_conx, $sql);
  // $row = mysqli_fetch_row($query);
  // $allow = $row[0];
	//
  // if($allow == 0) {
  //   header('location: /');
  //   exit();
  // } else {
  //   $passwordPage = "";
  // }
	if(isset($_COOKIE["allow"])){
		$allow['allow'] = preg_replace('#[^0-9]#', '', $_COOKIE['allow']);
		$allow = implode($allow);

		if($allow == 1) {
			setcookie("allow", $allow, time()+3600);
		} else {
			header('location: password.php');
		}

	} else {
		header('location: password.php');
	}


	if(isset($_COOKIE["id"]) && isset($_COOKIE["user"]) && isset($_COOKIE["pass"])){
		$id['userid'] = preg_replace('#[^0-9]#', '', $_COOKIE['id']);
	  $u['username'] = preg_replace('#[^a-z0-9]#i', '', $_COOKIE['user']);
	  $p['password'] = preg_replace('#[^a-z0-9]#i', '', $_COOKIE['pass']);

		$id = implode($id);
		$u = implode($u);
		$p = implode($p);

		$sql = "SELECT ip FROM users WHERE id='$id' AND username='$u' AND password='$p' LIMIT 1";
	  $query = mysqli_query($db_conx, $sql);
	  $numrows = mysqli_num_rows($query);
		if($numrows > 0){
			setcookie("id", $id, time()+3600*24*30);
			setcookie("user", $u, time()+3600*24*30);
			setcookie("pass", $p, time()+3600*24*30);
		} else {
			header('location: /');
			// echo $id;
		}

	} else {
		header('location: /');
		// echo 'second';
	}

// If user is logged in, header them away
if(isset($_SESSION["username"])){
} else {
	header("location: /");
	exit();
}
?><?php
include_once("php_includes/check_login_status.php");
// Initialize any variables that the page might echo
$u = "";
$userlevel = "";
$avatar = "";
$country = "";
$joindate = "";
$lastsession = "";
// Make sure the _GET username is set, and sanitize it
if(isset($_GET["u"])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
}
// Select the member from the users table
$sql = "SELECT * FROM users WHERE username='$log_username' LIMIT 1";
$user_query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($user_query);
if($numrows < 1){
	echo "That user does not exist or is not yet activated, press back";
    exit();
}
// Check to see if the viewer is the account owner
$isOwner = "no";
if($u == $log_username && $user_ok == true){
	$isOwner = "yes";
}
// Fetch the user row from the query above
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
	$profile_id = $row["id"];
	$country = $row["country"];
	$email = $row['email'];
	$name = $row["name"];
	$userlevel = $row["userlevel"];
	$avatar = $row["avatar"];
	$signup = $row["signup"];
	$lastlogin = $row["lastlogin"];
	$joindate = strftime("%b %d, %Y", strtotime($signup));
	$lastsession = strftime("%b %d, %Y", strtotime($lastlogin));
}
?><?php

$sql = "SELECT * FROM users WHERE username='$log_username' AND activated='1' LIMIT 1";
$log_user_query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($user_query);
while ($row = mysqli_fetch_array($log_user_query, MYSQLI_ASSOC)) {
	$log_avatar = $row["avatar"];
}
$SPP = "";
$pp2 = "";
$pp3 = "";

$interests = "";
$sql = "SELECT * FROM interests WHERE username='$log_username' ORDER BY postdate DESC";
$log_user_query = mysqli_query($db_conx, $sql);
$numrows = mysqli_num_rows($user_query);
while ($row = mysqli_fetch_array($log_user_query, MYSQLI_ASSOC)) {
	$the_interest = $row["interest"];
	$id_interest = $row['id'];

	$interest2 = str_replace("+", "OplusO", $the_interest);
	$interest2 = str_replace(" ", "+", $interest2);

	$interests .= "<span class='cursor-pointer inline-block'><a href='searchResult.php?s=".$interest2."' data-interest='".$id_interest."' target='_blank' class='inline-block margin-none small-margin-bottom med-btn blue-white-btn interest'> ".$the_interest." </a><span class='delete-interest red-background font-white small-margin-right inline-block'><i class='fa fa-times-circle'></i></span></span>";
}
?><?php
$pic = "";
// Ajax calls this REGISTRATION code to execute
if(isset($_POST["name"]) && $_POST['password'] != ""){
	$password = md5($_POST['password']);
	$name = htmlentities($_POST['name']);
	$name = mysqli_real_escape_string($db_conx, $name);
	$city = htmlentities($_POST['city']);
	$city = mysqli_real_escape_string($db_conx, $city);;
	$country = htmlentities($_POST['country']);
	$country = mysqli_real_escape_string($db_conx, $country);;
	$bio = htmlentities($_POST['bio']);
	$bio = mysqli_real_escape_string($db_conx, $bio);;
	$email = mysqli_real_escape_string($db_conx, $_POST['email']);
	if(isset($_POST['pic'])) {
		$pic = $_POST['pic'];

	}
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$user_ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$user_ip = $_SERVER['REMOTE_ADDR'];
	}

	if ($name == "" || $city == "" || $country == "" || $bio == "" || $email == "" || $password == "") {
		echo 'Fail|Fields with * cannot be blank.';
		exit();

	} else {
		$sql = "UPDATE users SET ip='$user_ip', name='$name', city='$city', country='$country', Bio='$bio', email='$email', password='$password', avatar='$pic' WHERE username='$log_username' LIMIT 1";
		$query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
		$pieces = explode(" ", $name);
		$firstName = $pieces[0];
		echo "success|$firstName";
		exit();
	}
}

if(isset($_POST["name"]) && $_POST['password'] == ""){
	$name = htmlentities($_POST['name']);
	$name = mysqli_real_escape_string($db_conx, $name);
	$city = htmlentities($_POST['city']);
	$city = mysqli_real_escape_string($db_conx, $city);;
	$country = htmlentities($_POST['country']);
	$country = mysqli_real_escape_string($db_conx, $country);;
	$bio = htmlentities($_POST['bio']);
	$bio = mysqli_real_escape_string($db_conx, $bio);;
	$email = mysqli_real_escape_string($db_conx, $_POST['email']);
	if(isset($_POST['pic'])) {
		$pic = $_POST['pic'];

	}
	if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		$user_ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		$user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$user_ip = $_SERVER['REMOTE_ADDR'];
	}

	if ($name == "" || $city == "" || $country == "" || $bio == "" || $email == "") {
		echo 'Fail|Fields with * cannot be blank.';
		exit();

	} else {
		$sql = "UPDATE users SET ip='$user_ip', name='$name', city='$city', country='$country', Bio='$bio', email='$email', avatar='$pic' WHERE username='$log_username' LIMIT 1";
		$query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
		$pieces = explode(" ", $name);
		$firstName = $pieces[0];
		echo "success|$firstName";
		exit();

	}

}
?><?php
	if(isset($_POST['interest']) && $_POST['interest'] != '') {
		$interest = htmlentities($_POST['interest']);
		$interest = mysqli_real_escape_string($db_conx, $interest);

		if($interest == '') {
			echo "Nothing was typed.|false data.";
			exit();
		} else {
			$sql = "SELECT * FROM interests WHERE interest='$interest' AND username='$log_username'";
			$query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
			$interest_num = mysqli_num_rows($query);
			if($interest_num >= 1) {
				echo "You have already added this interest.|false data.";
				exit();
			} else {
				$sql = "INSERT INTO interests(username, interest, postdate)
						VALUES('$log_username','$interest',now())";
				$query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
				$id = mysqli_insert_id($db_conx);
				echo "success|".$id;
				exit();
			}
		}
	}
?><?php
	if(isset($_POST['interest_id']) && $_POST['interest_id'] != '') {
		$interest_id = htmlentities($_POST['interest_id']);
		$interest_id = mysqli_real_escape_string($db_conx, $interest_id);

		if($interest_id == '') {
			echo "Oops, looks like something is wrong. Please contact us and let us know. Thanks for understanding.";
			exit();
		} else {
			$sql = "DELETE FROM interests WHERE id='$interest_id'";
			$query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
			echo "success";
			exit();
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php echo $name; ?> Setting</title>
	<?php include_once("head.php"); ?>
<script>
function doUpload(id){
	var file = _(id).files[0];
	if(file.name === ""){
		return false;
	}
	if(file.type != "image/jpeg" && file.type != "image/gif" && file.type != "image/png"){
		alert("That file type is not supported.");
		return false;
	}
	var formdata = new FormData();
	formdata.append("PPic", file);
	var ajax = new XMLHttpRequest();
	ajax.upload.addEventListener("progress", progressHandler, false);
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.addEventListener("abort", abortHandler, false);
	ajax.open("POST", "php_parsers/photo_system.php");
	ajax.send(formdata);
}
function progressHandler(event) {
	// var percent = (event.loaded / event.total) * 100;
	// _("outer1").innerHTML = "<div id='inner'>"+percent+"%</div>";
	// _("inner").style.width = percent+'%';
}
function completeHandler(event){
	var action = "";
	var data = event.target.responseText;
	var datArray = data.split("|");
	if(datArray[0] == "upload_complete"){
		hasImage = datArray[1];
		Profile('send');
	} else if(datArray[1] == 'fail') {
		alert();
	} else {
		alert(data);
	}
}
function errorHandler(event){
	alert('errorHandler');
	_("SDP").style.display = "block";
}
function abortHandler(event){
	_("SDP").style.display = "block";
}
function triggerUpload(e,elem){
	e.preventDefault();
	_(elem).click();
}
function Profile(action) {
	var ajax = ajaxObj("POST", "php_parsers/Profile_system.php");
	ajax.onreadystatechange = function() {
		if(ajaxReturn(ajax) == true) {
			var datArray = ajax.responseText.split("|");
			if(datArray[0] == "yiss"){
				var PIC = datArray[1];
				$('.profile_p').css('background-image', 'url(Profile_P/'+PIC+')');
				hasImage = "";
			} else {
				alert(ajax.responseText);
			}
		}
	};
	ajax.send("action=send&image="+hasImage);
}

</script>
</head>
<body>
  <?php include_once("template_PageTop.php"); ?>

	<div class="parallax" style="padding-bottom: 0;">
		<div class="profile-background border-top-bottom border-gray large-padding padding-top-bottom" style="z-index: 9;">
			<div class="pageMiddle">
				<div class="flex-box-between profile-info profile-contain transparent-dark-background cursor-default">


						<div class="align-bottom relative profile-first">
							<span class='upload display-none'>
								<form id='image_SP' enctype='multipart/form-data' method='post'>
									<input accept="image/*" type="file" name="FileUpload" id="fu_SP" onchange="doUpload('fu_SP')"/>
								</form>
							</span>
							<span class="profile-main profile_p relative cursor-pointer med-animation" style="<?php echo $avatar; ?>" id='SDP' onclick="triggerUpload(event, 'fu_SP')" >
								<span class="bottom-full tiny-full-banner transparent-dark-background center-text"><i class="fa fa-camera"></i></span>
							</span>
							<h3 class="inline-block"><?php echo $name; ?></h3>
						</div>

						<div class="flex-box-between profile-second">

							<div class="">
								<label class="small--margin-bottom">Location:*</lable>
	              <input type="text"  id="setting-location" value="<?php echo $city1;?>, <?php echo $country1;?>" class="transparent-blue-box med-med-textbox contact-required font-white med-font setting-required" />


							</div>

							<div class=" small-padding">

							</div>

							<div class="small-margin-top"  data-tooltip="Write a simple and short bio about yourself. Let people get closer to you.">
								<label>Bio:*</label>
								<textarea name="Answer" id="setting-bio" class="transparent-blue-textarea transparent-blue-textarea1 med-full-textbox contact-required display-block transparent-background font-white med-font setting-required"><?php echo $bio1; ?></textarea>
							</div>

						</div>
					</div>
				</div>
		</div>

		<div class="parallax-user">

			<div class="pageMiddle smallmed-margin-top relative" style="z-index: 20;">
        <h1 class="flex-box-between margin-none small-margin-top">Settings Page<span>This is where you change stuff</span></h1>
        <hr>

				<div class="med-full-container small-margin-top setting-message">
					<div class="flex-box-between">

						<div class="max-width47">
							<div class="fancy-text-form fancy-login-form">
								<input type="text" id="setting-name" value="<?php echo $name; ?>" class="transparent-blue-box med-long-textbox setting-required focusFancy" />
								<label>Your Name*</lable>
							</div>

								<div class="fancy-text-form fancy-login-form">
									<input type="email" id="setting-email" value="<?php echo $email; ?>" class="transparent-blue-box med-long-textbox setting-required focusFancy" />
									<label>Your Brand New Email*</lable>
								</div>

								<div class="fancy-text-form fancy-login-form">
									<input type="password" id="setting-p1" class="transparent-blue-box med-long-textbox" />
									<label>Your New Password</lable>
								</div>


								<div class="fancy-text-form fancy-login-form">
									<input type="password"  id="setting-p2" class="transparent-blue-box med-long-textbox" />
									<label>Confirm Your New Password</lable>
								</div>

								<form class="small-med-margin-top small-padding-top" onsubmit="return false;">
									<span class="fancy-text-form fancy-login-form block-on-small">
										<input type="text" id="setting-interest" maxlength="20" class="transparent-blue-box med-long-textbox" />
										<label>Your Interests:</lable>
									</span>
									<input type="submit" value="Add" class="med-btn blue-white-btn small-margin submit-interest inline-block" /><br>
									<span class="interest-status font-red med-font med-padding-left"></span>
								</form>

								<label class="small-margin-top small-margin-bottom small-padding">Your current interests:</label>
								<div class="interests small-margin-bottom small-margin-top"><?php echo $interests ?></div>

						</div>

						<div class=" med-half-container small-margin-top small-padding">
							<h3 class="margin-none font-gray">NOTE</h3>

							<p class="font-gray">If you do not wish to change certain things, simply <strong>leave them as they are</strong>.</p>

							<p class="font-gray">If you leave this page without clicking on the "Save Changes Button", <strong>your changes will not be submitted</strong>.</p>

							<p class="font-gray">You are able to change your settings at any time.</p>

							<p class="font-gray">Your changes will be instant and they will take place immediately once you click on "Save Changes" button.</p>

							<p class="font-gray">By clicking on the "Cancel Changes" button, your changes will not take place, and you will be redirected to your profile page.</p>

							<p class="font-gray">Some of your settings will be set to default if you leave them blank. (<strong>Email &amp; Passwords fields do not have any default settings!</strong>)</p>
						</div>

					</div>
				</div>

			<span id="setting-status" style="color: #f00; font-weight: bold;"></span>
			<hr>
			<input type="submit" value="Save Changes" class="med-btn green-white-btn small-margin submit-setting" />
			<a href="user.php?u=<?php echo $log_username; ?>" class="med-btn gray-white-btn small-margin inline-block" > Cancel Changes </a>
			<a href="contact.php" class="med-btn blue-white-btn small-margin display-none" > Contact Us </a>

		</div>
	</div>

  <?php include_once("template_PageBottom.php"); ?>


</body>


<script type="text/javascript">
$(function() {
	$('#setting-interest').keyup(function() {
		$('.interest-status').html('');
	});

	$('.submit-interest').click(function() {
		var interest = $('#setting-interest').val();

		if(interest == '') {
			$('.interest-status').html('Please type something first.');

		} else if($.isNumeric(interest)) {
			$('.interest-status').html('Please type a valid interest. No numbers.');

		} else {
			$.post('Setting.php', {
				interest: interest

			}, function(data) {
				var datArray = data.split("|");
				var stage = datArray[0];
				var content = datArray[1];

				if(stage == "success") {
		      var interest2 = interest.split('+').join('OplusO');
		      var interest2 = interest2.split(' ').join('+');
					$('.interests').prepend("<span class='cursor-pointer inline-block'><a href='searchResult.php?s="+interest2+"' data-interest='"+content+"' target='_blank' class='inline-block margin-none small-margin-bottom med-btn blue-white-btn interest'> "+interest+" </a><span class='delete-interest red-background font-white small-margin-right inline-block'><i class='fa fa-times-circle'></i></span></span>");
					$('#setting-interest').val('');

				} else {
					$('.interest-status').html(stage);

				}

			});
		}


	});

	$('body').on('click', '.delete-interest', function() {
		var interest_id = $(this).siblings().attr('data-interest');
		var the_interest = $(this);

		if(!$.isNumeric(interest_id)) {
			$('.interest-status').html('Oops, looks like something is wrong. Please contact us and let us know. Thanks for understanding!');
		} else {
			$.post('Setting.php', {
				interest_id: interest_id

			}, function(data) {
				if(data == "success") {
					the_interest.parent().hide('slow');

				} else {
					$('.interest-status').html('Oops, looks like something is wrong. Please contact us and let us know. Thanks for understanding!');

				}

			});
		}

	});

	function profileSize() {
		var widthFul = $('.profile-contain').width();
		var width1 = $('.profile-first').width();
		var wWidth = $(window).width();
		var width2 = $('.profile-second').width() * 0.9;

		var flexWidth = widthFul - width1 - 50;

		if (wWidth > 640) {
			$('.profile-second').width(flexWidth);
			$('.transparent-blue-textarea1').css('width', flexWidth * 0.99);
			var profileHeight = $('.profile-background').height();
			$('.parallax-user').css('margin-top', profileHeight + 100);
		} else {
			$('.profile-second').css('width', 'auto');
			$('.transparent-blue-textarea1').css('min-width', 0);
			$('.transparent-blue-textarea1').css('width', widthFul * 0.98);
			var profileHeight = $('.profile-background').height();
			$('.parallax-user').css('margin-top', profileHeight + 100);
		}

		var headerHeight = $('header').height();
		$('.user-pop').css('margin-top', headerHeight + 10);
	}


	profileSize();
	$(window).resize(function() {
		profileSize();
	});


	$(window).scroll(function() {
		var wScroll = $(window).scrollTop();

		if(wScroll > $('.profile-background').height() + 100) {
			$('.profile-background').css('display', 'none');
		} else {
			$('.profile-background').css('display', 'block');
		}
	});


	$('.setting-required').focusout(function() {
			$('#setting-status').html(' ')
	});

	$('.submit-setting').click(function() {
		var $city = "";
		var country = "";
		var $location = $('#setting-location').val();
		var $bio = $('#setting-bio').val();
		var $name = $('#setting-name').val();
		var $email = $('#setting-email').val();
		var $locations = $location.split(",");
		$city = $locations[0];
		country = $locations[1];
		var $p1 = $('#setting-p1').val();
		var $p2 = $('#setting-p2').val();
		var $pic = $(".profile_p").attr("style");

		if($location == "" || $bio == "" || $email == "" || $name == "") {
			$('#setting-status').html('Fields with * cannot be blank.');

		} else if ($city == "" || country == "" || !$city  || !country ) {
			$('#setting-status').html('Please write your location in this form: "City, Country".');

		} else if( !isValidEmailAddress($email) ) {
			$('#setting-status').html('Please enter a valid email.');
			$('#setting-email').addClass('error');

	 	} else if (!$name.match(/\s/g)){
			$('#setting-status').html('Please write your full name.');
			$('#setting-name').addClass('error');

		} else if ($bio.length > 450){
			$('#setting-status').html('Please make sure your Bio is less than 450 characters. It will be more amazing!');
			$('#setting-bio').addClass('error');

		} else if($p1 !== $p2) {
			$('#setting-status').html('Your password fields do not match.');

		} else {
			$country = $.trim(country);

			$.post('Setting.php', {
				country:   $country,
				city:      $city,
				bio:       $bio,
				name:			 $name,
				email:     $email,
				password:  $p1,
				pic:			 $pic

			}, function(data) {
				var datArray = data.split("|");
				var stage = datArray[0];
				var newName = datArray[1];

				if(stage == "Fail") {
					$('#setting-status').html(newName);

				} else if (stage == "success") {
					$('.setting-message').html('<h1 style="color: #1AB188;" class="center-text"><i class="fa fa-check-circle-o fa-3x"></i></h1><h2 class="font-gray center-text">Awesome '+newName+'! Your profile have been successfully updated.</h2>');

				} else {
					$('#setting-status').html('Oops, something went wrong. Please contact us and let us know! Thank you.');
					$('.blue-white-btn').fadeIn(400);
				}

			});

		}

	});

});
</script>
</html>
