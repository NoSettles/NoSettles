<?php
	include_once("php_includes/check_login_status.php");
	include_once("classes/develop_php_library.php");

?><?php
	// example rating!!!!
	$tooltip = "";

	if(isset($_SESSION["username"])){
		$jvVar = "$('.stars')";
	} else {
		$jvVar = "$('.nonexistance')";
		$tooltip = "data-tooltip='Please log in or sign up to rate.'";
	}

?>
<script type="text/javascript">
$(function() {
  <?php echo $jvVar; ?>.hover(function() {
    sibling = $(this).siblings('.yellow-background');
    previousRating = sibling.attr('data-starRating');

    if ($(this).hasClass('star1')) {
      $(this).siblings('.yellow-background').css({'width': '20%'});

    } else if ($(this).hasClass('star2')) {
      $(this).siblings('.yellow-background').css({'width': '40%'});

    } else if ($(this).hasClass('star3')) {
      $(this).siblings('.yellow-background').css({'width': '60%'});

    } else if ($(this).hasClass('star4')) {
      $(this).siblings('.yellow-background').css({'width': '80%'});

    } else if ($(this).hasClass('star5')) {
      $(this).siblings('.yellow-background').css({'width': '100%'});

    }

    $('.stars').mouseleave(function() {
      sibling.css({'width': previousRating});
    });
  })

  $('.stars').click(function() {
    if ($(this).hasClass('star1')) {
      $(this).siblings('.yellow-background').attr('data-starRating', '20%');

    } else if ($(this).hasClass('star2')) {
      $(this).siblings('.yellow-background').attr('data-starRating', '40%');

    } else if ($(this).hasClass('star3')) {
      $(this).siblings('.yellow-background').attr('data-starRating', '60%');

    } else if ($(this).hasClass('star4')) {
      $(this).siblings('.yellow-background').attr('data-starRating', '80%');

    } else if ($(this).hasClass('star5')) {
      $(this).siblings('.yellow-background').attr('data-starRating', '100%');

    }

  });
})
</script>
