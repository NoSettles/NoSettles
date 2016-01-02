<?php
  include_once("php_includes/check_login_status.php");

  if(isset($_POST['name']) && isset($_POST['email']) && isset($_POST['subject']) && isset($_POST['inquiry'])) {
    $name = htmlentities($_POST['name']);
		$name = mysqli_real_escape_string($db_conx, $name);;
  	$email = mysqli_real_escape_string($db_conx, $_POST['email']);
    $subject = htmlentities($_POST['subject']);
		$subject = mysqli_real_escape_string($db_conx, $subject);;
    $inquiry = htmlentities($_POST['inquiry']);
		$inquiry = mysqli_real_escape_string($db_conx, $inquiry);;

    if($name != "" && $email != "" && $subject != "" && $inquiry != "") {

      $sql1 = "INSERT INTO support (name, email, subject, inquiry, date_sent)
			        VALUES('$name','$email','$subject','$inquiry',now())";
			$query = mysqli_query($db_conx, $sql1) or die(mysqli_error($db_conx));

      $to = "$email";
			$from = "auto_responder@nosettles.com";
			$subject = "$subject";
			$message = 'This is a copy of your message .
      '.$inquiry.'
      Please Do Not Respond To This Email!';
			$headers = "From: $from\n";
			mail($to, $subject, $message, $headers);

      echo $name;
      exit();

    } else {
      echo "Fail";
    }
  }


?>
<!DOCTYPE html>
<html>
<head>
  <!--/////////////////////////Meta tags/////////////////////////////-->
  <meta charset="UTF-8">
  <title>NoSettles | Contact Us</title>
  <meta name="description" content="Let us know anything that you have in mind!">
  <?php include_once("head.php"); ?>

  <script type="text/javascript">
    $(function() {
      $('.contact-required').keyup(function() {
          $('#contact-status').html(' ')
      });

      $('#contact-btn').on('click', function() {
        var $contact_name = $('#contact-name').val();
        var $contact_email = $('#contact-email').val();
        var $contact_subject = $('#contact-subject').val();
        var $contact_text = $('#contact-text').val();
        var $contact_status = $('#contact-status');

        if ($contact_name == "" || $contact_email == "" || $contact_subject == "" || $contact_text == "") {
          $contact_status.html('Please fill in all of the form.');

        }
        // else if (!$contact_name.match(/\s/g)){
    		// 	$contact_status.html('Please write your full name.');
    		// 	$('#contact-name').addClass('error');
        //
    		// }
         else if( !isValidEmailAddress($contact_email) ) {
    			$contact_status.html('Please enter a valid email.');
    			$('#contact-email').addClass('error');

    	 	}
        // else if($contact_text.length > 750) {
    		// 	$contact_status.html('Please make sure your message is shorter than 650 characters.');
    		// 	$('#contact-text').addClass('error');
        //
        // } else if($contact_text.length < 50) {
    		// 	$contact_status.html('Please give us more information about your inquiry.');
    		// 	$('#contact-text').addClass('error');
        //
        // }
        else {
          $.post('contact.php', {
            name: $contact_name,
            email: $contact_email,
            subject: $contact_subject,
            inquiry: $contact_text
          }, function(data) {
            if(data != "Fail") {
              $('#contact_message').html('<h1 style="color: #1AB188;" class="center-text"><i class="fa fa-check-circle-o fa-3x"></i></h1><h2 class="font-gray center-text">Awesome! We Have successfully received your message and we will be with you soon. Thank you '+data+'!</h2>');
            } else {
              $contact_status.html('Sorry, something went wrong, please email us the problem below. Thank you!');
            }
          });

        }
      });



    });

  </script>

</head>
<body data-pageName="contact">
	<?php include_once("template_PageTop.php"); ?>


  <main>
    <div class="parallax">
      <div class="pageMiddle">
        <h1 class="center-text">Get In Touch With Us!</h1>
        <hr>

        <div class="med-full-container">
          <h3 class="center-text">We'd love to hear from you.</h3>

          <div class="flex-box-between">

            <div>
              <form id="contact_message" class="" onsubmit="return false;">

                <div class="fancy-text-form fancy-login-form">
                  <input type="text" id="contact-name" class="transparent-blue-box med-long-textbox contact-required" />
                  <label>Your Awesome Name</lable>
                </div>

                <div class="fancy-text-form fancy-login-form">
                  <input type="email" id="contact-email" class="transparent-blue-box med-long-textbox contact-required" />
                  <label>Your Email</lable>
                </div>

                <div class="fancy-text-form fancy-login-form">
                  <input type="text"  id="contact-subject" class="transparent-blue-box med-long-textbox contact-required" />
                  <label>Subject</lable>
                </div>

                <div class="fancy-text-form">
                  <textarea name="Answer" id="contact-text" class="transparent-blue-textarea med-long-textbox contact-required" placeholder="How can we help you?"></textarea>
                </div>

                <div class="fancy-text-form">
                  <input type="submit" id="contact-btn" value="Submit" class="med-long-btn transparent-blue-btn submit-contact" onClick="" /><br>
                  <span id="contact-status" style="color: #f00; font-weight: bold;"></span>
                </div>
              </form>
            </div>

            <div class=" med-half-container small-margin-top small-padding">
              <h3 class="margin-none font-gray">FAQ</h3>

              <p class="font-gray"><strong>What is NoSettles?</strong> NoSettles is a Search Engine that organizes the worlds most popular educational websites by user ratings.</p>

              <p class="font-gray"><strong>Can I rate?</strong> Anybody on this website is able to rate any source if they are already logged in and have already "Enrolled" into the source.</p>

              <p class="font-gray"><strong>Can I add a new source?</strong> We will be more than happy if you'd like to add a new source. To do that simply log in and then clikc on the "plus" sign in the header area.</p>

              <p class="font-gray"><strong>How do I rate?</strong> If you look closely there's 5 stars on every post, you can choose to rate the site by rating from 1-5 stars.</p>

              <p class="font-gray"><strong>Are all the sources free?</strong> The price of each source should be written on the post. Most of the sources on the site are free.</p>

            </div>

        </div>

      </div>

      <hr>

      <h2 class="center-text">Want to email us instead?</h2>

      <div style="padding-left: 15px;" class="small-margin-top">

        <h4 class="margin-none">Great, we'll answer you as soon as we can!</h4>
        <div class="med-full-container ">
          <p>We will be more than happy to try and help you with any questions that you might have.</p>

          <p>Advertise with us: <a href="mailto:advertisement@nosettles.com">advertisement@nosettles.com</a></p>

          <p>Business inquiries: <a href="mailto:business@nosettles.com">business@nosettles.com</a></p>

          <p>Feedback for our site: <a href="mailto:feedback@nosettles.com">feedback@nosettles.com</a></p>

          <p>General inquiries: <a href="mailto:contact@nosettles.com">contact@nosettles.com</a></p>

          <p>Legal inquiries: <a href="mailto:legal@nosettles.com">legal@nosettles.com</a></p>
        </div>
      </div>

      </div>
    </div>
  </main>

  <?php include_once("template_PageBottom.php"); ?>

</body>

</html>
