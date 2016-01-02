<?php
  include_once("php_includes/check_login_status.php");

  if($log_username == "") {
    header('location: /');
  }

  // $sql = "SELECT allow FROM users WHERE username='$log_username' LIMIT 1";
  // $query = mysqli_query($db_conx, $sql);
  // $row = mysqli_fetch_row($query);
  // $allow = $row[0];
  //
  // if($allow == 1) {
  //   header('location: settings.php');
  //   exit();
  // } else {
  //   $passwordPage = "";
  // }

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
		}
  }

  if(isset($_COOKIE["allow"])){
		$allow['allow'] = preg_replace('#[^0-9]#', '', $_COOKIE['allow']);
		$allow = implode($allow);

		if($allow == 1) {
			setcookie("allow", $allow, time()+3600);
      header('location: setting.php');
		}

	}

?><?php
  if(isset($_POST['password']) && $_POST['password'] != "") {

    $password = md5($_POST['password']);

    $sql = "SELECT password FROM users WHERE username='$log_username' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $row = mysqli_fetch_row($query);
    $password_check = $row[0];

    if($password === $password_check) {
			setcookie("id", $log_id, time()+3600*24*30);
			setcookie("user", $log_username, time()+3600*24*30);
			setcookie("pass", $password, time()+3600*24*30);
			setcookie("allow", 1, time()+3600);
      echo 'success';
      exit();

    } else if($password !== $password_check) {
      echo 'Fail';
      exit();

    }
  }

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Please Enter Your Password</title>
  <meta name="description" content="Please Enter Password To Prove Identity.">
	<?php include_once("head.php"); ?>
</head>
<body>
  <?php include_once("template_PageTop.php"); ?>

  <main>
    <div class="parallax">
      <div class="pageMiddle">
        <h1 class="flex-box-between margin-none small-margin-top">Please Enter password<span>It's just to prove your identity</span></h1>
        <hr>

        <div class="border-lightgray">

          <h2 class="center-text gray margin-none small-margin-top"><i class="fa fa-lock fa-2x"></i></h2>
          <h1 class="center-text gray margin-none"></i>Password Required</h1>

          <form class="fancy-form half-width" onsubmit="return false;">

            <div class="fancy-text-form fancy-login-form" data-tooltip="Enter the password that you use to log in to your account.">
              <input type="password"  id="password" class="transparent-blue-box med-long-textbox password-required" />
              <label>Password</lable>
            </div>

            <div class="fancy-text-form">
              <input type="submit" id="password-btn" value="Continue" class="med-long-btn transparent-blue-btn submit-password" onClick="" /><br>
              <span id="password-status" class="display-block small-margin-bottom" style="color: #f00; font-weight: bold;"></span><br>
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
<script type="text/javascript">
  $(function() {
    $('.password-required').keyup(function() {
        $('#password-status').html(' ');
    });

    $('#password-btn').click(function() {
      var password_check = $('#password').val();

      if(password_check == "") {
        $('#password').addClass('error');
        $('#password-status').html('Please Enter Your Password.');
      } else {
        $.post('password.php', {
          password: password_check
        }, function(data) {
          if(data == "Fail") {
            $('#password-status').html('Sorry, Wrong Password.');
          } else if (data == "success") {
            window.location = "Setting.php";
          } else {
            $('#password-status').html('Oops, something went wrong. Please contact us and let us know! Thank you.');
            $('.display-none').fadeIn(400);
          }
        });
      }

    });

  });


</script>

</html>
