<?php
include_once("php_includes/check_login_status.php");
include_once("classes/develop_php_library.php");

?><?php

$tooltip = "";

if(isset($_SESSION["username"])){
	$jvVar = "$('.stars')";
} else {
	$jvVar = "$('.nonexistance')";
	$tooltip = "data-tooltip='Please log in or sign up to rate.'";
}

// Make sure the _GET username is set, and sanitize it
if(isset($_GET["id"])){
	$statusId = preg_replace('#[^a-z0-9]#i', '', $_GET["id"]);
} else {
	header('location: /');
}


?><?php
$statuslist = "";
$sql = "SELECT * FROM post WHERE id='$statusId' ORDER BY postdate DESC LIMIT 1";
$query = mysqli_query($db_conx, $sql);
$statusnumrows = mysqli_num_rows($query);
if($statusnumrows > 0){
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$postid = $row['id'];
		$author = $row["author"];
		$title = $row["title"];
		$title = nl2br($title);
		$title = str_replace("&amp;","&",$title);
		$title = stripslashes($title);
		$category = $row["category"];
		$web = $row["web"];
		$web = nl2br($web);
		$web = str_replace("&amp;","&",$web);
		$web = stripslashes($web);
		$blurb = $row["blurb"];
		$blurb = nl2br($blurb);
		$blurb = str_replace("&amp;","&",$blurb);
		$blurb = stripslashes($blurb);
		$price = $row["price"];
		$apple = $row["apple"];
		$android = $row["android"];
		$description = $row["description"];
		$description = nl2br($description);
		$description = str_replace("&amp;","&",$description);
		$description = stripslashes($description);
		$image = $row["image"];
		$rating = $row["rating"];
		$enrolls = $row["enrolls"];
		$postdate = $row["postdate"];

		$timeAgoObject = new convertToAgo;
		$convertedTime = ($timeAgoObject -> convert_datetime($postdate)); // Convert Date Time
		$when = ($timeAgoObject -> makeAgo($convertedTime));

		if ($image != 'na') {
			$photo = 'background-image: url(permUploads/'.$image.');';
		} else {
      $photo = "";
    }


    if($android != "") {
      $android = '<a href="'.$android.'">Available</a>';
    } else {
      $android = "<span class='font-gray' >Not available</span>";
    }
    if($apple != "") {
      $apple = '<a href="'.$apple.'">Available</a>';
    } else {
      $apple = "<span class='font-gray' >Not available</span>";
    }


	  if ($price != 0) {
			$price1 = number_format($price);
			$price = '$'.$price1;
		} else if($price == 0) {
			$price = '<span class="font-green">FREE</span>';
		}


		$sql = "SELECT * FROM users WHERE username='$author' LIMIT 1";
		$query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
		while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
			$author_avatar = $row['avatar'];
			$author_name = $row['name'];
		}
		$pieces = explode(" ", $author_name);
		$author_firstName = $pieces[0];


		$percentage = 0;
    if($rating != 0) {
			$total = $rating * 20;
			$percentage = round($total, 1, PHP_ROUND_HALF_UP);
		}
    if($rating == 0) {
      $response = '<span class="font-gray">Not Rated</span>';
    } else if ($rating <= 1) {
      $response = '<span class="font-darkred">Terrible</span>';
    } else if ($rating <= 2) {
      $response = '<span class="font-red">Bad</span>';
    } else if ($rating <= 3) {
      $response = '<span class="font-orange">Medium</span>';
    } else if ($rating <= 4) {
      $response = '<span class="font-lightgreen">Good</span>';
    } else if ($rating <= 4.5) {
      $response = '<span class="font-green">Very Good</span>';
    } else if ($rating <= 5) {
      $response = '<span class="font-blue">Excellent</span>';
    }

		$delete = "";
		if($author == $log_username) {
			$delete = '<br><span class="delete font-red underline cursor-pointer">Delete Post</span>';
		} else {
			$delete = "";
		}


		$reviews = "";

		$sql2 = "SELECT * FROM review WHERE postid='$statusId' LIMIT 999999999";
		$query2 = mysqli_query($db_conx, $sql2);
		$statusnumrows2 = mysqli_num_rows($query2);
		if($statusnumrows2 == 0){
				$reviews = "0 Reviews";

		} else if($statusnumrows2 == 1){
				$reviews = "1 Review";

		} else if($statusnumrows2 > 1){
				$reviews = "".$statusnumrows2." Reviews";
		}

		$comments = '';

		$comment_count = "No Comments";
		$sql = "SELECT * FROM comments WHERE postid='$statusId' ORDER BY postdate DESC LIMIT 999999999";
		$query = mysqli_query($db_conx, $sql);
		$statusnumrows = mysqli_num_rows($query);
		if($statusnumrows > 0){
			while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
				$commenter = $row['user'];
				$comment = $row['data'];
				$comment_date = $row['postdate'];
				$timeAgoObject = new convertToAgo;
				$convertedTime = ($timeAgoObject -> convert_datetime($comment_date)); // Convert Date Time
				$comment_date = ($timeAgoObject -> makeAgo($convertedTime));

				$sql2 = "SELECT * FROM users WHERE username='$commenter' LIMIT 1";
				$query2 = mysqli_query($db_conx, $sql2);
				$statusnumrows2 = mysqli_num_rows($query2);
				while ($row = mysqli_fetch_array($query2, MYSQLI_ASSOC)) {
					$comment_name = $row['name'];
					$comment_avatar = $row['avatar'];
				}

				$comments .= '<div class="med-padding"><div class="tiny-square profile_p inline-block" style="'.$comment_avatar.'"></div><a class="small-margin-left bold font-blue med-font" href="user.php?u='.$commenter.'">'.$comment_name.'</a><span class="small-font font-gray small-margin-left">'.$comment_date.'</span><br><div class="med-margin-left tiny-margin-top">'.$comment.'</div></div>';

			}
		} else {
			$comments = '<h2 class="center-text font-gray bold no-comment">No Comments Found.</h2>';
		}
		if($statusnumrows == 1) {
			$comment_count = '<span class="comments">'.$statusnumrows.' Comment</span>';
		} else if($statusnumrows == 0) {
			$comment_count = '<span class="comments">No Comments</span>';
		} else {
			$comment_count = '<span class="comments">'.$statusnumrows.' Comments</span>';
		}

	}
} else {
	header('loction: /');
}

?><?php
// Check to see if the viewer is the account owner
$isOwner = "no";
if($author == $log_username){
	$isOwner = "yes";
}


$pieces = explode(" ", $author);
$firstName = $pieces[0];

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php echo $title; ?></title>
	<?php include_once("head.php"); ?>
	<?php include_once("stars.php"); ?>

</head>
<body>

	<?php
		include_once("template_PageTop.php");
		include_once("rate.php");
	?>
	<!-- <div class="fixed user-pop">
		<h1 class="margin-none center-text"><i class="profile-main gray-background"></i></h1>
	</div> -->

	<div class="full transparent-dark-background back-pop display-none fixed important">
	</div>
	<div class="width-40 fixed center-center white-background med-padding pop-up more-important display-none">
		<h2 class="font-lightblack center-text narrow-font margin-none">You are about to delete this post.</h2>
		<hr class="full-hr">
		<h3 class="font-lightblack narrow-font center-text">Are you sure you want to delete the post "<?php echo $title ?>"?</h3>
		<p class="center-text">You will be removing all the data related to this post, including ratings, comments, enrolls, and all the data.</p>

		<input type="submit" value="Delete Post" class="med-btn green-white-btn small-margin-left med-margin-top float-right delete-ok" />
		<input type="submit" value="Cancel" class="med-btn gray-white-btn small-margin-right med-margin-top float-right delete-cancel" />
	</div>

	<main>
		<div class="parallax" style="padding-bottom: 0;">
      <div class="profile-background border-top-bottom border-gray large-padding padding-top-bottom" data-author="<?php echo $author ?>" data-statusid="<?php echo $statusId; ?>" style="<?php echo $photo; ?>">
				<div class="pageMiddle">
					<div class="profile-info profile-contain transparent-dark-background">

            <h1 class="center-text"><?php echo $title; ?></h1>
            <hr>
            <div class="flex-box">
              <div class="width--300 center-text">
                <h3>Enrolls: <?php echo $enrolls; ?></h3>
              </div>

              <div class="width--300 center-text">
                <h3>Category: <?php echo $category; ?></h3>
              </div>

              <div class="width--300 center-text">
                <h3>Rating: <?php echo $response; ?></h3>
              </div>

              <div class="width--300 center-text">
                <h3>Android App: <?php echo $android; ?></h3>
              </div>

              <div class="width--300 center-text">
                <h3>Android App: <?php echo $apple; ?></h3>
              </div>

              <div class="width--300 center-text">
                <h3>Price: <?php echo $price; ?></h3>
              </div>
            </div>

            <h3 class="center-text margin-bottom-none">Blurb:</h3>
            <p class="fill-text margin-top-none"><?php echo $blurb; ?></p>

					</div>
				</div>
			</div>
		</div>

    <div class="parallax second-parallax">
  		<div class="parallax-user">
  			<div class="pageMiddle small-margin-top relative" style="z-index: 20;">
          <div class="flex-box-between" >
            <div class="width-75 hidden-overflow">
              <h2 class="center-text"><?php echo $title; ?></h2>
              <hr>

              <?php echo $description; ?>

            </div>

            <div class="width-20 relative">
              <div class="follow-down border-lightgray flex-box-culumn med-padding small-margin-top">
                <div class="">
                  <a href="<?php echo $web; ?>" target="_blank" class="transparent-blue-btn med-btn med-med-large-font enroll" data-tooltip="Click to visit the website">Enroll Now</a><br><br>
                  <span class="font-blue underline cursor-pointer">Report Link</span>
									<?php echo $delete; ?>
                </div>
                <div class="relative star-size small-margin-top" <?php echo $tooltip; ?>>
                  <span class="yellow-background short-animation star-size absolute left top" data-starRating="<?php echo $percentage; ?>%" style="z-index: 20; width: <?php echo $percentage; ?>%;"></span>
                  <div class="star-rating relative" style="z-index: 21;" data-statusid="<?php echo $statusId; ?>"></div>
                  <span class="transparent absolute cursor-pointer star-size top stars star1" data-rate="1"></span>
                  <span class="transparent absolute cursor-pointer star-size top stars star2" data-rate="2"></span>
                  <span class="transparent absolute cursor-pointer star-size top stars star3" data-rate="3"></span>
                  <span class="transparent absolute cursor-pointer star-size top stars star4" data-rate="4"></span>
                  <span class="transparent absolute cursor-pointer star-size top stars star5" data-rate="5"></span>
                  <span class="small-font reviews"><?php echo $reviews ?></span>
                  <span class="med-font float-right font-blue final-rating"><?php echo $rating; ?></span>
                </div><br>
								<h4 class="font-gray margin-none small-margin-top narrow-font">Made by:</h4>
								<div class="small-padding">
									<div class="tiny-square profile_p inline-block" style="<?php echo $author_avatar; ?>"></div><span><a class="small-margin-left bold font-blue med-font" href="user.php?u=<?php echo $author;?>"><?php echo $author_firstName; ?></a></span>
								</div>

              </div>
            </div>
          </div>

          <hr>
          <div class="flex-box-culumn full-width">
            <textarea id="comment" class="transparent-blue-textarea full-width med-font small-padding comment" placeholder="Leave a comment"></textarea>
            <input type="submit" id="submitComment" value="Submit Comment" class="med-long-btn transparent-blue-btn full-width border-top submitComment" /><br>
            <span id="comment-status" class="comment-status font-red"></span>
          </div>

          <div class="comment-section flex-box-culumn med-margin">
            <label><?php echo $comment_count ?>:</label>
            <span id="new-comment"></span>

            <?php echo $comments; ?>

          </div>

  			</div>
  		</div>
    </div>

	</div>
	</main>

	<?php include_once("template_PageBottom.php"); ?>
</body>
</html>
<script type="text/javascript">
$(function() {
	function profileSize() {
		var widthFul = $('.profile-contain').width();
		var width1 = $('.profile-first').width();
		var wWidth = $(window).width();

		var flexWidth = widthFul - width1 - 50;

		if (wWidth > 640) {
			$('.profile-second').width(flexWidth);
			$('.full-width').css('width', flexWidth * 0.99);
		} else {
			$('.profile-second').css('width', 'auto');
			$('.full-width').css('min-width', 0);
			$('.full-width').css('width', widthFul * 0.98);
		}

		var profileHeight = $('.profile-background').height();
		$('.second-parallax').css('margin-top', profileHeight + 100);

		var headerHeight = $('header').height();
		$('.user-pop').css('margin-top', headerHeight + 10);
	}

	profileSize();
	$(window).resize(function() {
		profileSize();
	});

  var follow_down = ($('.follow-down').offset().top - $('.follow-down').height() / 2) / 2;

	$(window).scroll(function() {
		var wScroll = $(window).scrollTop();

		if(wScroll > $('.profile-background').height() + 50) {
			$('.profile-background').css('display', 'none');

		} else {
			$('.profile-background').css('display', 'block');
		}
	});


  function ismResponsive() {
    var wHeight = $(window).height();
    var postHeight = wHeight / 3;
    $('.dummy-space').css({'height': postHeight});
  }

  ismResponsive();
  $(window).resize(ismResponsive);


	$('.delete').click(function() {
		$('.back-pop').fadeIn(400);
		$('.pop-up').fadeIn(400);
	});

	$('.back-pop, .delete-cancel').click(function() {
		$('.back-pop').fadeOut(400);
		$('.pop-up').fadeOut(400);
	});

	$('.delete-ok').click(function() {
		var deletepost = 'ok';
		var statusid = $('.profile-background').attr('data-statusid');
		var author = $('.profile-background').attr('data-author');

		$.post('php_parsers/status_system.php', {
			deletepost: deletepost,
			statusid: statusid,
			author: author
		}, function(data) {
			if(data == "success") {
				window.location = "post.php?u=<?php echo $log_username; ?>";
			} else {
				alert(data);
				alert('Sorry, there\'s a problem in the system. Please contact us, so we can fix it! Thank you!');
			}
		})
	});

})

$(function() {
    $('#submitComment').click( function() {
      var comment = $('#comment').val();
			var statusid = $('.profile-background').attr('data-statusid');

      if(comment == '') {

      } else {
        $.post('php_parsers/status_system.php', {
          comment: comment,
					statusid: statusid
        }, function(data) {
          if(data != "Fail") {
						if(data == 1) {
							$('.comments').html(data+" Comment");
						} else {
							$('.comments').html(data+" Comments");
						}
						$('#comment').val('');
						$('.no-comment').fadeOut(100);
            $('#new-comment').prepend('<div class="med-padding"><?php echo $picStyle1; ?><a class="small-margin-left bold font-blue med-font" href="user.php?u=<?php echo $log_username; ?>"><?php echo $name1; ?></a><span class="small-font font-gray small-margin-left">Just now</span><br><div class="med-margin-left tiny-margin-top">'+comment+'</div></div>');
          } else {
            alert('Sorry, there\'s a problem in the system. Please contact us, so we can fix it! Thank you!');
          }
        });
      }

    });



		$('.enroll').click( function() {
			var statusid = $('.profile-background').attr('data-statusid');
			var action = "enroll";

			$.post('php_parsers/status_system.php', {
				statusid: statusid,
				action: action
			}, function(data) {
				if(data == "success") {
				} else {
					alert(data);
					alert('Sorry, there\'s a problem in the system. Please contact us, so we can fix it! Thank you!');
				}
			})
    });

})
</script>
