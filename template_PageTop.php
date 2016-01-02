<?php
include_once("php_includes/check_login_status.php");
$current_link = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
// AJAX CALLS THIS LOGIN CODE TO EXECUTE

$sql = "SELECT * FROM users WHERE username='$log_username' LIMIT 1";
$user_query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($user_query);
$name1 = "";
$firstName1 = "";
$lastName1 = "";
$userlevel1 = "";
$avatar1 = "";
$country1 = "";
$city1 = "";
$bio1 = "";
$joindate1 = "";
$lastsession1 = "";
$picStyle1 = "";
// Fetch the user row from the query above
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
	$profile_id1 = $row["id"];
	$name1 = $row["name"];
	$city1 = $row["city"];
	$country1 = $row["country"];
	$bio1 = $row["Bio"];
	$userlevel1 = $row["userlevel"];
	$avatar1 = $row["avatar"];
	$signup1 = $row["signup"];
	$lastlogin1 = $row["lastlogin"];
	$joindate1 = strftime("%b %d, %Y", strtotime($signup1));
	$lastsession1 = strftime("%b %d, %Y", strtotime($lastlogin1));
}

$pieces1 = explode(" ", $name1);
$firstName1 = $pieces1[0];

if ($bio1 == "") {
	$bio1 = "$firstName1 has not written a bio yet.";
}

if($avatar1 != "") {
	$picStyle1 = '<span class="profile_p tiny-square inline-block" style="'.$avatar1.'"></span>';
} else {
	$picStyle1 = '<span class="profile_p tiny-square inline-block"></span>';
}


$login_signup = '<input type="submit" value="Log In" class="med-btn transparent-blue-btn login-launch" />
	<input type="submit" value="Sign Up" class="med-btn transparent-green-btn signup-launch" />';

if ($log_username != "") {
	// $login_signup = '<i class="fa fa-bell-o fa-2x media-icon relative icon-notif med-margin-right profile-icon"></i><a href="new_post.php" class=""><i class="fa fa-plus fa-2x relative med-margin-right profile-icon" style="top: -2px;"></i></a>'.$picStyle1;
	$login_signup = '<a href="new_post.php" class=""><i class="fa fa-plus fa-2x relative med-margin-right profile-icon" style="top: -2px;"></i></a>'.$picStyle1;
}


if(isset($_POST["login_email"])){
	// CONNECT TO THE DATABASE
	// GATHER THE POSTED DATA INTO LOCAL VARIABLES AND SANITIZE
	$e = mysqli_real_escape_string($db_conx, $_POST['login_email']);
	$p = md5($_POST['login_password']);
	// GET USER IP ADDRESS
    $ip = preg_replace('#[^0-9.]#', '', getenv('REMOTE_ADDR'));
	// FORM DATA ERROR HANDLING
	if($e == "" || $p == ""){
		echo "login_failed";
        exit();
	} else {
	// END FORM DATA ERROR HANDLING
		sleep(0.5);
		$sql = "SELECT id, username, password FROM users WHERE email='$e' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $row = mysqli_fetch_row($query);
		$db_id = $row[0];
		$db_username = $row[1];
    $db_pass_str = $row[2];

		if($p != $db_pass_str) {
			echo "login_failed";
	    exit();

		} elseif ($db_username == "") {
			echo "login_failed";
			exit();

		} else {
			// CREATE THEIR SESSIONS AND COOKIES
			$_SESSION['userid'] = $db_id;
			$_SESSION['username'] = $db_username;
			$_SESSION['password'] = $db_pass_str;
			setcookie("id", $db_id, strtotime( '+30 days' ), "/", "", "", TRUE);
			setcookie("user", $db_username, strtotime( '+30 days' ), "/", "", "", TRUE);
    		setcookie("pass", $db_pass_str, strtotime( '+30 days' ), "/", "", "", TRUE);
			// UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
			$sql = "UPDATE users SET ip='$ip', lastlogin=now(), allow='0' WHERE username='$db_username' LIMIT 1";
      $query = mysqli_query($db_conx, $sql);
			echo $db_username;
		  exit();
		}
	}

	exit();
}
?><?php
	// Ajax calls this REGISTRATION code to execute
	if(isset($_POST["u"])){
		// CONNECT TO THE DATABASE
		include_once("php_includes/db_conx.php");
		// GATHER THE POSTED DATA INTO LOCAL VARIABLES
		$name = htmlentities($_POST['u']);
		$name = mysqli_real_escape_string($db_conx, $name);;
		$u = preg_replace('#[^a-z0-9]#i', '', $name);

		$e = mysqli_real_escape_string($db_conx, $_POST['e']);
		$p = $_POST['p'];
		// $g = preg_replace('#[^a-z]#', '', $_POST['g']);
		// $c = preg_replace('#[^a-z ]#i', '', $_POST['c']);
		$i = $_POST['i'];
		// DUPLICATE DATA CHECKS FOR USERNAME AND EMAIL
		//--------------------------------------------
		$sql = "SELECT id FROM users WHERE code='$i' LIMIT 1";
	    $query = mysqli_query($db_conx, $sql);
		$i_check = mysqli_num_rows($query);
		// -------------------------------------------
		$sql = "SELECT id FROM users WHERE email='$e' LIMIT 1";
	    $query = mysqli_query($db_conx, $sql);
		$e_check = mysqli_num_rows($query);
		// -------------------------------------------
		$sql = "SELECT id FROM users WHERE username LIKE '$u%'";
	    $query = mysqli_query($db_conx, $sql);
		$u_check = mysqli_num_rows($query);
		// FORM DATA ERROR HANDLING
		if($u == "" || $e == "" || $p == "" ){
			echo "Please fill in the entire form.";
	        exit();
		} else if ($e_check > 0){
	        echo "The email address you have entered is already in use in the system.";
	        exit();
		}  else if (is_numeric($u[0])) {
	        echo 'Please enter a valid name.';
	        exit();
	    } else if(strlen($u) < 5) {
				echo 'Please enter a valid name.';
				exit();
			} else {
		// END FORM DATA ERROR HANDLING
		    // Begin Insertion of data into the database
			// Hash the password and apply your own mysterious unique salt
			$p_hash = md5($p);
			$code = md5($e);
			$user = preg_replace('#[^a-z0-9]#i', '', $name);

			$country = '';
			$city = '';


			if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
		    $user_ip = $_SERVER['HTTP_CLIENT_IP'];
			} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		    $user_ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			} else {
		    $user_ip = $_SERVER['REMOTE_ADDR'];
			}
			$user_ip = '24.86.100.34';

			$geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
			$city = $geo["geoplugin_city"];
			$country = $geo["geoplugin_countryName"];

			if($u_check > 0) {
				$u_number = $u_check + 1;
				$user = $u.$u_number;
			}
			// Add user info into the database table for the main site table
			$sql = "INSERT INTO users (name, username, email, password, city, country, ip, signup, lastlogin, notescheck, code, invited_code)
			        VALUES('$name','$user','$e','$p_hash','$city','$country','$user_ip',now(),now(),now(),'$code','$i')";
			$query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
			$uid = mysqli_insert_id($db_conx);
			// Establish their row in the useroptions table
			$sql = "INSERT INTO useroptions (id, username, background) VALUES ('$uid','$user','original')";
			$query = mysqli_query($db_conx, $sql);
			// Email the user their activation link
			// $to = "$e";
			// $from = "auto_responder@nosettles.com";
			// $subject = 'NoSettles Account Activation';
			// $message = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>NoSettles</title><style>a{text-decoration: none;color: #3EA7FD;}a:hover{color: #0073c0;text-decoration: underline;}h5{display: inline-block;margin-left: 20px;margin-top: 10px;color: #fff;}body{background-color: #EFEFEF;}</style></head><body style="margin:0px; background-color: #eee; font-family:Tahoma, Geneva, sans-serif;"> <div style="padding:10px; background:#0073C0; font-size:24px; color:#CCC;"><a href="https://www.nosettles.com"><img src="https://www.nosettles.com/style/logo.png" width="60" height="50" alt="NoSettles" style="border:none; float:left;"></a> <h5>NoSettles Activation</h5></div><div style="padding:24px; font-size:17px;">Hello '.$name.',<br /><br />Click the link below to activate your account when ready:<br /><br /><a href="https://www.nosettles.com/activation.php?id='.$uid.'&u='.$user.'&e='.$e.'&p='.$p_hash.'">Click here to activate your account now</a><br /><br />After successful activation you can login using your::<br />* E-mail Address: <b>'.$e.'</b></div></body></html>';
			// $headers = "From: $from\n";
	    //     $headers .= "MIME-Version: 1.0\n";
	    //     $headers .= "Content-type: text/html; charset=iso-8859-1\n";
			// mail($to, $subject, $message, $headers);

			$inviter = "";
			$sql = "SELECT username FROM users WHERE code='$i' LIMIT 1";
	    	$query = mysqli_query($db_conx, $sql);
			while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
				$inviter = $row['username'];
			}

			// if ($inviter != "") {
			// 	$avatar_notif = '<img src="style/DPP.png" class="CPP" alt="Profile Pic" />';
			// 	$app = "Invite";
		  // 		$note = '<a href="user.php?u='.$user.'" style="color: #09F;"><img src="style/invited.png" alt="Donation" class="icon_donation" />'.$avatar_notif.'<img src="style/invited.png" alt="Donation" class="icon_donation flip" /><li class="Notification">Your friend '.$name.' just joined NoSettles. Check out '.$name.'\'s profile!</li></a>';
			// 	mysqli_query($db_conx, "INSERT INTO notifications(username, initiator, app, note, date_time)
			//              VALUES('$inviter','$user','$app','$note',now())") or die(mysqli_error($db_conx));
			// }

			$sql = "SELECT id, username FROM users WHERE email='$e' LIMIT 1";
			$query = mysqli_query($db_conx, $sql);
			$row = mysqli_fetch_row($query);
			$db_id = $row[0];

			$_SESSION['userid'] = $db_id;
			$_SESSION['username'] = $user;
			$_SESSION['password'] = $p_hash;
			setcookie("id", $db_id, strtotime( '+30 days' ), "/", "", "", TRUE);
			setcookie("user", $user, strtotime( '+30 days' ), "/", "", "", TRUE);
			setcookie("pass", $p_hash, strtotime( '+30 days' ), "/", "", "", TRUE);
			// UPDATE THEIR "IP" AND "LASTLOGIN" FIELDS
			$sql = "UPDATE users SET ip='$user_ip', lastlogin=now() WHERE username='$user' LIMIT 1";
			$query = mysqli_query($db_conx, $sql);
			echo "signup_success|$user";
			exit();
		}
	}

	$feedbacker = '';
	if(isset($_POST['subject']) && isset($_POST['text'])) {
    $subject = htmlentities($_POST['subject']);
		$subject = mysqli_real_escape_string($db_conx, $subject);;
    $text = htmlentities($_POST['text']);
		$text = mysqli_real_escape_string($db_conx, $text);;

		if(!isset($_SESSION['username'])) {
			$feedbacker = '';
		} else {
			$feedbacker = $log_username;
		}

    if($subject != "" && $text != "") {

      $sql1 = "INSERT INTO feedback (username, subject, feedback, postdate)
			        VALUES('$feedbacker','$subject','$text',now())";
			$query = mysqli_query($db_conx, $sql1) or die(mysqli_error($db_conx));

			$to = "nosettles@gmail.com";
      $from = "generalarvin@gmail.com";
			$subject = "$subject";
			$message = 'This is a copy of a feedback from a user: <br />
      '.$subject.' <br />
			'.$text.'<br />
      Please Do Not Respond To This Email!';
			$headers = "From: $from\n";
			mail($to, $subject, $message, $headers);

      echo 'success';
      exit();

    } else {
      echo "Fail";
			exit();
    }
  }
?>


<script type="text/javascript">
// LOGIN STUFF HERE ONLY
	$(function() {
		// fade in the login page
		$('.login-launch').on('click', function() {
			$('.pop-up-background').fadeIn( 500 );
			$('.login-form').fadeIn( 500 );
		});

		// clear the errors
		$('.login-required').keyup(function() {
			$('#login-status').html('');
			$(this).removeClass('error');
		});

		// declare the math
		var one = Math.floor((Math.random() * 10) + 1);
		var two = Math.floor((Math.random() * 10) + 1);
		var total = one + two;
		$('#login-question').html("What's "+one+" Plus "+two+"?");

		// *log in button pushed*
		$('#loginbtn').on('click', function() {
			$(this).removeClass('transparent-blue-btn');
			$(this).addClass('gray-white-btn');
			// Get the values
			var $login_email = $('#login-email').val();
			var $login_password = $('#login-password').val();
			var $login_answer = $('#login-answer').val();

			// reset the math on call
			function resetMath() {
				one = Math.floor((Math.random() * 10) + 1);
				two = Math.floor((Math.random() * 10) + 1);
				total = one + two;
				$('#login-question').html("What's "+one+" Plus "+two+"?");
			}

			// validation
			if($login_email == "" || $login_password == "" || $login_answer == "") {
				$('#login-status').html('Please fill in the entire form.');
				$(this).addClass('transparent-blue-btn');
				$(this).removeClass('gray-white-btn');

			} else if($login_answer != total) {
				$('#login-status').html('Please answer the math equation correctly.');
				$('#login-answer').addClass('error');
				$(this).addClass('transparent-blue-btn');
				$(this).removeClass('gray-white-btn');
				resetMath();

			} else {
				$.post('template_PageTop.php', {
					login_email: $login_email,
					login_password: $login_password,
				}, function(data) {
					if(data == "") {
						$('#login-status').html('Sorry, something went wrong');
							$(this).addClass('transparent-blue-btn');
							$(this).removeClass('gray-white-btn');

					} else if (data == "login_failed") {
						$('#login-status').html('Sorry, your email and password do not match!');
							$(this).addClass('transparent-blue-btn');
							$(this).removeClass('gray-white-btn');

					} else {
						window.location = "<?php echo $current_link ?>";
					}
				});

			}
		});

	});


$(function () {
	$('.signup-required').keyup(function() {
		$('#status_register').html('');
	});

	$('#signupbtn').on('click', function() {
		var u = $("#u").val();
		var e = $("#e").val();
		var p1 = $("#p1").val();
		var p2 = $("#p2").val();
		var c = $("#c").val();
		var g = $("#g").val();
		var i = $("#i").val();
		if(u == '' || e == '' || p1 == '' ) {
			$('#status_register').css({'color' : '#f00'})
			$('#status_register').html('Please fill in all of the required fields.');
			$('.signup-required').each(function() {
	      var $this = $(this);

	      if ($this.val().length === 0) {
	        $this.addClass('error');
	      }
	    });
		} else if(p1.length < 6) {
			$('#status_register').css({'color' : '#f00'});
			$('#status_register').html('Please select a password with at least 6 characters.');
			$('#p1, #p2').addClass('error');
		} else if (!u.match(/\s/g)){
			$('#status_register').css({'color' : '#f00'});
			$('#status_register').html('Please write your full name.');
			$('#u').addClass('error');
		} else if( !isValidEmailAddress(e) ) {
			$('#status_register').css({'color' : '#f00'});
			$('#status_register').html('Please enter a valid email.');
			$('#e').addClass('error');
	 	} else {
			$.post('template_PageTop.php', {
				u: u,
				e: e,
				p: p1,
				i: i
			}, function(data) {
				var datArray = data.split("|");
				var stage = datArray[0];
				var user = datArray[1];

				if(stage == "signup_success") {
					window.location = "<?php echo $current_link ?>";

				} else {
					$('#status_register').html(data);
				}
			});

		}
	});

	$('.drop-down-parent').hover( function() {
		$(this).children('.drop-down-ul').slideToggle(300);

	});

	$('.parent-side-nav').click(function() {
		$('.child-side-nav').slideToggle(300);

	});

	$('.prevent-default').click(function() {
		event.preventDefault();

	});

	$('.profile_p.tiny-square').click(function() {
		$('.notif-menu').slideUp(200);
		$('.profile-menu').slideToggle(200).delay(550);
		profilePosition();
	});

	function profilePosition() {
		var profile_p = $('.login-signup');
		var position = profile_p.position();
		var position_left = position.left;
		if($(window).width() > 490) {
			var position_right = $('header').width() - position_left - 70;
			var position_top = position.top + 65;
		} else {
			var position_right = $('header').width() - position_left - 65;
			var position_top = position.top + 55;
		}

		$('.profile-menu').css({'right' : position_right , 'top' : position_top});
	}

	profilePosition();
	$(window).resize(profilePosition);


	$('.fa-bell-o').click(function() {
		$(this).toggleClass('active');
		$('.profile-menu').slideUp(200);
		$('.notif-menu').slideToggle(200).delay(550);
		notifPosition();
	});

	function notifPosition() {
		var profile_p = $('.login-signup');
		var position = profile_p.position();
		var position_left = position.left;
		if($(window).width() > 490) {
			var position_right = $('header').width() - position_left - 26;
			var position_top = position.top + 65;
		} else {
			var position_right = 5;
			var position_top = position.top + 55;
		}

		$('.notif-menu').css({'right' : position_right , 'top' : position_top});
	}

	notifPosition();
	$(window).resize(notifPosition);



	$('.signup-form-pop .notif-menu').mCustomScrollbar({
  	theme:"minimal-dark"
	});

	$('#feedback-btn').click( function() {
		var subject = $('#feedback-subject').val();
		var text = $('#feedback-textarea').val();

		if(subject == '' || text == '') {
			$('#feedback-status').html('Please fill in both of the boxes.');
		} else {
			$('#feedback-subject').val('');
			$('#feedback-textarea').val('');
			$.post('template_PageTop.php', {
				subject: subject,
				text: text
			}, function(data) {
				if(data == "success") {
          $('.feedback-pop').html('<span class="gray exit" style="font-weight: bold">X</span><h1 style="color: #1AB188;" class="center-text"><i class="fa fa-check-circle-o fa-3x"></i></h1><h2 class="font-gray center-text">Awesome! We Have successfully received your message. Your feedback will be exteremely helpful to us. Thank you!</h2>');
				} else {
					$('#feedback-status').html('Sorry, there\'s a problem in the system. Please contact us, so we can fix it! Thank you!');
					alert(data);
				}
			});
		}

	});
});
$(window).load(function() {
	$('.background-loader').fadeOut(500);
});
</script>
<!-- Loading stuff -->
<div class="background-loader">
  <div class="loader">
    <span class="spinner spinner1"></span>
    <span class="spinner spinner2"></span>
    <span class="spinner spinner3"></span>
    <br>
    <span class="loader-text">LOADING...</span>
  </div>
</div>
<!-- End of loading stuff -->
<!-- Pop up stuff -->
<div class="pop-up-background"></div>
<div class="signup-form-pop">
	<span class="gray exit" style="font-weight: bold">X</span>
  <h1 class="h-center gray">Let’s Make A Better World</h1>

  <form class="fancy-form" onsubmit="return false;">

    <div class="fancy-text-form">
      <input type="text" name="name" id="u" class="transparent-blue-box med-med-textbox signup-required" />
      <label>Your Awesome Name</lable>
    </div>

    <div class="fancy-text-form">
      <input type="email" name="Name" id="e" class="transparent-blue-box med-med-textbox signup-required" />
      <label>Your Awesome Email</lable>
    </div>


    <div class="fancy-text-form">
      <input type="password" name="password" id="p1" class="transparent-blue-box med-med-textbox signup-required" />
      <label>Your Secure Password</lable>
    </div>

    <!-- <div class="fancy-text-form" data-tooltip="If you do not have an invitation code, simply leave this box med-med-textbox blank.">
      <input type="text" id="i" name="invite" class="transparent-blue-box med-med-textbox" />
      <label>Invitation Code?</lable>
    </div> -->

    <div class="fancy-text-form">
      <input type="submit" id="signupbtn" value="Sign Up" class="med-box transparent-blue-btn submit-signup">
    </div>
  </form>
  <p>By clicking done, you agree to our <a class="underline" href="terms-of-service.php">Terms of use</a> and our <a class="underline" href="privacy-policy.php">Privacy Policy</a></p>
  <span id="status_register"></span>
</div>


<div class="login-form">
	<span class="gray exit" style="font-weight: bold">X</span>
  <h1 class="h-center gray">Welcome Back!</h1>

  <form class="login-fancy-form fancy-form" onsubmit="return false;">

    <div class="fancy-text-form fancy-login-form">
      <input type="email" name="login-email" id="login-email" class="transparent-blue-box med-med-textbox login-required" />
      <label>Your Email</lable>
    </div>

    <div class="fancy-text-form fancy-login-form">
      <input type="password"  name="login-password" id="login-password" class="transparent-blue-box med-med-textbox login-required" />
      <label>Your Secure Password</lable>
    </div>

    <div class="fancy-text-form fancy-login-form"  data-tooltip="This is to make sure that you are not a smart robot.">
      <input type="text" name="login-answer" id="login-answer" class="transparent-blue-box med-med-textbox login-required"/>
      <label id='login-question'></lable>
    </div>

    <div class="fancy-text-form">
      <input type="submit" id="loginbtn" value="Log In" class="med-box transparent-blue-btn submit-login" > <br>
			<span id="login-status" style="color: #f00; font-weight: bold;"></span>
    </div>
  </form>
	<hr>
	<a href="forgot_pass.php" class="underline center-table">Forgot Your Password?</a>
</div>

<div class="feedback-pop">
	<span class="gray exit" style="font-weight: bold">X</span>
  <h1 class="h-center gray">Your Feedback Really Matters To Us!</h1>
  <h3 class="h-center gray">With Your Feedback We Will Be Able To Improve Our Site So That It Will Be More Convenient For You.</h3>

  <form class="flex-box" onsubmit="return false;">
		<div class="flex-box-culumn">
			<div class="fancy-text-form fancy-login-form">
				<input type="text"  id="feedback-subject" class="feedback-small transparent-blue-box med-long-textbox feedback-required" />
				<label>Subject</lable>
			</div>

			<div class="fancy-text-form">
				<textarea name="Answer" id="feedback-textarea" class="feedback-small transparent-blue-textarea med-long-textbox feedback-required" placeholder="Your Feedback..."></textarea>
			</div>

			<div class="fancy-text-form">
				<input type="submit" id="feedback-btn" value="Submit" class="med-long-btn feedback-small transparent-blue-btn submit-feedback" /><br>
				<span id="feedback-status" style="color: #f00; font-weight: bold;"></span>
			</div>

		</div>
	</form>
</div>


<div class="fixed profile-menu important">
	<h3 class="center-text margin-none small-margin-top">User Navigation</h3>
	<div class="flex-box-between">
		<ul class="med-padding small-padding-top"> <strong>About <?php echo $firstName1; ?></strong>
			<a href="feed.php" class="underline"><li class="tiny-margin-top">Feed</li></a>
			<a href="user.php?u=<?php echo $log_username;?>" class="underline"><li class="tiny-margin-top">Profile</li></a>
			<a href="post.php?u=<?php echo $log_username;?>" class="underline"><li class="tiny-margin-top">Your Posts</li></a>
			<a href="setting.php" class="underline"><li class="tiny-margin-top">Settings</li></a>
		</ul>

	</div>
	<a class="bottom center-text small-padding small-margin-left underline" href="logout.php?link=<?php echo $current_link ?>">Log Out</a>
	<a class="bottom right center-text small-padding underline" href="new_post.php"><strong>+ New Source</strong></a>


</div>

<!-- END of Pop up suff -->


<!-- Menu ONLY -->
<div class="menu">

	<a href="/" class="media-small1">
		<div class="logo-hover display-block">
			<img src="style/NoSettles.svg" class="center" onerror="this.onerror=null; this.src=\'image.png\'">
		</div>
	</a>

	<div class="center" style="width: 90%; margin-top: 25px;">
		<form class="searchMain" onsubmit="return false;">
			<input type="text" class="searchTermMain med-large-font" id="searchnav" placeholder="Start Learning" maxlength="50"/>
			<input class="searchMainButton" type="submit" />
		</form>
	</div>

	<div class="center">
    <ul class="nav-menu">
      <a href="/" class="home">
        <li class="home"><i class="fa fa-home small-margin-right fa-lg home"></i>Home</li>
      </a>
      <a href="about.php" class="about">
        <li class="about"><i class="fa fa-info small-margin-right fa-lg about"></i>About</li>
      </a>

      <!-- <a href="#" class="articles prevent-default">
        <li class="articles parent-side-nav"><i class="fa fa-file-text small-margin-right articles"></i>Articles <span class="triangle-down-small"></span></li>
      </a>

			<div class="nav-menu">

				<div class="child-side-nav">
					<a href="tips.php" class="tips">
		        <li class="tips"><i class="fa fa-file-text small-margin-right"></i>Tips &amp; Tricks </li>
		      </a>
				</div>

				<div class="child-side-nav  padding-none">
					<a href="fundraisingideas.php" class="fundraising_ideas padding-none">
		        <li class="fundraising_ideas"><i class="fa fa-file-text small-margin-right"></i>Fundraising Ideas </li>
		      </a>
				</div>

			</div> -->

			<!-- <a href="/" class="prevent-default search-slide">
				<li class="search-toggle"><i class="fa fa-search small-margin-right fa-lg  search-toggle"></i>Search</li>
			</a> -->

      <a href="contact.php" class="contact" style="border-bottom: 1px solid #999;">
        <li class="contact"><i class="fa fa-envelope-o small-margin-right contact"></i>Contact Us</li>
      </a>
    </ul>
	</div>

	<div class="bottom-nav-icon">
    <a href="https://www.facebook.com/NoSettles" target="_blank"><i class="fa fa-facebook fa-lg icon"></i></a>
    <a href="https://twitter.com/NoSettles" target="_blank"><i class="fa fa-twitter fa-lg icon"></i></a>
    <a href="https://nosettles.wordpress.com/" target="_blank"><i class="fa fa-rss fa-lg icon"></i></a>
	</div>

</div>
<!-- END OF MENU -->


<div class="menu-close menu-close-body"></div>

<header>


		<div>
			<div class="media-show">
				<i class="fa fa-bars fa-2x media-icon"></i>
			</div>
	    <a href="/" class="media-small1">
				<div class="logo-hover">
		    	<img src="style/NoSettles.svg" id="logo" onerror="this.onerror=null; this.src=\'image.png\'">
				</div>
	  	</a>
  	</div>


  	<div class="media-remove2">
	    <ul class="nav">
	      <a href="/">
	        <li class="home"><i class="fa fa-home fa-lg home navicon"></i>Home</li>
	      </a>

				<a href="about.php">
	        <li class="about"><i class="fa fa-info fa-lg about navicon"></i>About</li>
	      </a>

				<!-- <a href="#" class="prevent-default">
	        <li class="articles drop-down-parent"><i class="fa fa-file-text articles navicon "></i><span>Articles</span><span class="triangle-down-small"></span>
						<ul class="drop-down-ul">
	        		<a href="tips.php"><li class="drop-down-li tips">Tips &amp; Tricks</li></a>
							<a href="fundraisingideas.php"><li class="drop-down-li fundraising_ideas">Fundraising Ideas</li></a>
	        	</ul>
					</li>
	      </a> -->

				<a href="contact.php">
	        <li class="contact"><i class="fa fa-envelope-o contact navicon"></i>Contact Us</li>
	      </a>

				<a href="/" class="prevent-default search-slide">
					<li class="search-toggle"><i class="fa fa-search search-toggle navicon"></i>Search</li>
				</a>
	    </ul>
  	</div>


    <div class="login-signup relative">
			<?php echo $login_signup; ?>
    </div>
</header>
<div class="fixed full-width small-padding white-background search-slider medium-shadow display-none" style="z-index: 10;">
	<div class="pageMiddle">
		<div class="center" style="width: 90%; margin-top: 25px;">
			<form class="searchMain" onsubmit="return false;">
				<input type="text" class="searchTermMain med-large-font" id="search" placeholder="Start Learning" maxlength="50"/>
				<input class="searchMainButton" type="submit" />
			</form>
		</div>
	</div>
</div>

<div class="feedback-text cursor-pointer font-white fixed small-med-padding med-med-large-font">
	Feedback
</div>
