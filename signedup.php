<?php
  include_once("php_includes/check_login_status.php");
  include_once("classes/develop_php_library.php");

  if(isset($_GET['c'])) {
    $code = preg_replace('#[^a-z0-9]#i', '', $_GET['c']);
    $sql = "SELECT email FROM users WHERE code='$code' LIMIT 1";
    $query = mysqli_query($db_conx, $sql);
    $statusnumrows = mysqli_num_rows($query);
  	if($statusnumrows > 0){
    	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $email = $row['email'];
      }

    } else {
      header('location: /');
    }


  } else {
    header('location: /');
  }
?>
<!DOCTYPE html>
<html>
<head>

  <meta charset="UTF-8">
  <title>Please Check Your Email Account</title>
  <meta name="description" content="A vision for making the world a better place for everyone.">
	<?php include_once("head.php"); ?>

</head>
<body>
  <?php include_once("template_PageTop.php"); ?>

  <main>
    <div class="parallax">
      <div class="pageMiddle">
        <h1 class="center-text">Email Activation</h1>
        <hr>

        <div class="med-margin-top">
          <h1 style="color: #1AB188;" class="center-text"><i class="fa fa-envelope-o fa-3x"></i></h1>
          <h2 class="font-gray center-text">Alright! We have sent an email to <?php echo $email; ?> for confirmation.</h2>
        </div>
        <hr>

        <div class="dummy-space">

        </div>


        <div class="">
          <h2 class="font-gray center-text">To resend the confirmation email to <?php echo $email; ?>, please click on the button below.</h2>
          <input type="submit" id="resendMail" value="Resend Email" class="med-long-btn transparent-blue-btn submit-contact center"  />
        </div>
        <hr>


      </div>
    </div>
  </main>

  <?php include_once("template_PageBottom.php"); ?>
</body>
</html>
