<?php
include_once("php_includes/check_login_status.php");
include_once("classes/develop_php_library.php");
// If user is logged in, header them away

?><?php
// Initialize any variables that the page might echo
$u = "";
$userlevel = "";
$avatar = "";
$country = "";
$bio = "";
$joindate = "";
$lastsession = "";
// Select the member from the users table
$sql = "SELECT * FROM users WHERE username='$log_username' LIMIT 1";
$user_query = mysqli_query($db_conx, $sql);
// Now make sure that user exists in the table
$numrows = mysqli_num_rows($user_query);
if($numrows < 1){
} else {
  while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
    $profile_id = $row["id"];
    $name = $row["name"];
    $city = $row["city"];
    $country = $row["country"];
    $bio = $row["Bio"];
    $userlevel = $row["userlevel"];
    $avatar = $row["avatar"];
    $signup = $row["signup"];
    $lastlogin = $row["lastlogin"];
    $joindate = strftime("%b %d, %Y", strtotime($signup));
    $lastsession = strftime("%b %d, %Y", strtotime($lastlogin));
  }
  $pieces = explode(" ", $name);
  $firstName = $pieces[0];
  if ($bio == "") {
    $bio = $firstName." has not written a bio yet.";
  }

  if($avatar != "") {
    $profilePhoto = '<span class="profile-main profile_p short-animation" style="'.$avatar.'"></span>
    ';
  } else {
    $profilePhoto = '<span class="profile-main profile_p short-animation"></span>';
  }
}


if(isset($_SESSION["username"])){
	$jvVar = "$('.stars')";
	$tooltip = "";
} else {
	$jvVar = "$('.nonexistance')";
	$tooltip = "data-tooltip='Please log in or sign up to rate.'";
}

?><?php
$statuslist = "";
$sql68 = "SELECT * FROM post ORDER BY enrolls DESC LIMIT 10";
$query68 = mysqli_query($db_conx, $sql68);
$statusnumrows68 = mysqli_num_rows($query68);
if($statusnumrows68 > 0){
	while ($row68 = mysqli_fetch_array($query68, MYSQLI_ASSOC)) {
		$statusid = $row68["id"];
		$author = $row68["author"];
		$title = $row68["title"];
		$title = nl2br($title);
		$title = str_replace("&amp;","&",$title);
		$title = stripslashes($title);
		$category = $row68["category"];
		$web = $row68["web"];
		$web = nl2br($web);
		$web = str_replace("&amp;","&",$web);
		$web = stripslashes($web);
		$blurb = $row68["blurb"];
		$blurb = nl2br($blurb);
		$blurb = str_replace("&amp;","&",$blurb);
		$blurb = stripslashes($blurb);
		$price = $row68["price"];
		$apple = $row68["apple"];
		$android = $row68["android"];
		$image = $row68["image"];
		$rating = $row68["rating"];
		$postdate = $row68["postdate"];

		if($android == "" && $apple == "") {
			$androidIcon = '<span class="android float-left med-opacity med-large-font center-text bold small-padding-top font-red">X</span>';
			$appleIcon = '<span class="apple float-left med-opacity med-large-font center-text bold small-padding-top font-red">X</span>';

		} else if($android != '' && $apple != '') {
			$androidIcon = '<a href="'.$android.'"><span class="android float-left med-large-font opacity-hover center-text bold small-padding-top font-red"></span></a>';
			$appleIcon = '<a href="'.$apple.'"><span class="apple float-left med-large-font opacity-hover center-text bold small-padding-top font-red"></span></a>';

		} else if($android != '' && $apple == '') {
			$androidIcon = '<a href="'.$android.'"><span class="android float-left med-large-font opacity-hover center-text bold small-padding-top font-red"></span></a>';
			$appleIcon = '<span class="apple float-left med-opacity med-large-font center-text bold small-padding-top font-red">X</span>';

		} else if($android == '' && $apple != '') {
			$androidIcon = '<span class="android float-left med-opacity med-large-font center-text bold small-padding-top font-red">X</span>';
			$appleIcon = '<a href="'.$apple.'"><span class="apple float-left med-large-font opacity-hover center-text bold small-padding-top font-red"></span></a>';
		}

		// $timeAgoObject = new convertToAgo;
		// $convertedTime = ($timeAgoObject -> convert_datetime($postdate)); // Convert Date Time
		// $when = ($timeAgoObject -> makeAgo($convertedTime));
		if ($image == 'na') {
		  $photo = 'style/no-photo1.svg';
		} else {
			$photo = 'permUploads/'.$image;
		}

		$percentage = 0;

	  if ($price != 0) {
			$price1 = number_format($price);
			$price = '$'.$price1;
		} else if($price == 0) {
			$price = "FREE";
		}

		if($rating != 0) {
			$total = $rating * 20;
			$percentage = round($total, 1, PHP_ROUND_HALF_UP);
		}

		$reviews = "";

		$sql2 = "SELECT * FROM review WHERE postid='$statusid' LIMIT 999999999";
		$query2 = mysqli_query($db_conx, $sql2);
		$statusnumrows2 = mysqli_num_rows($query2);
		if($statusnumrows2 == 0){
				$reviews = "0 Reviews";

		} else if($statusnumrows2 == 1){
				$reviews = "1 Review";

		} else if($statusnumrows2 > 1){
				$reviews = "".$statusnumrows2." Reviews";

		}

		if ($image != 'na') {
			$photo = 'background-image: url(permUploads/'.$image.');';
		} else {
			$photo = "";
		}

		$statuslist .= '
			<div class="full-width border-lightgray border-top-bottom relative med-padding flex-box-between postarea">
				<span class="fake-img float-left postimg med-margin-right" style="'.$photo.'">
				</span>

				<div class="textbox">
					<h2 class="margin-none flex-box-between"><a href="source.php?id='.$statusid.'">'.$title.'</a> <span><a data-id="'.$statusid.'" href="'.$web.'" class="underline font-gray med-font narrow-font enroll">Visit Website</a></span></h2>

					<div class="block font-blue italic bold small--margin-top">
						'.$price.'
					</div>

					<div class="flex-box-between">
						<div class="margin-none display-flex">
						'.$androidIcon.'
						'.$appleIcon.'
						</div>
						<div class="relative star-size" '.$tooltip.'>
							<span class="yellow-background short-animation star-size absolute left top" data-starRating="'.$percentage.'%" style="z-index: 20; width: '.$percentage.'%;"></span>
							<div class="star-rating relative" style="z-index: 21;" data-statusid="'.$statusid.'"></div>
							<span class="transparent absolute cursor-pointer star-size top stars star1" data-rate="1"></span>
							<span class="transparent absolute cursor-pointer star-size top stars star2" data-rate="2"></span>
							<span class="transparent absolute cursor-pointer star-size top stars star3" data-rate="3"></span>
							<span class="transparent absolute cursor-pointer star-size top stars star4" data-rate="4"></span>
							<span class="transparent absolute cursor-pointer star-size top stars star5" data-rate="5"></span>
							<span class="small-font reviews">'.$reviews.'</span>
							<span class="med-font float-right font-blue final-rating">'.$rating.'</span>
						</div>
					</div>

					<div class="block relative small--margin-bottom">
						<p class="margin-none padding-none">'.$blurb.'</p>
					</div>
				</div>
			</div>
    ';

	}
} else {
	$statuslist = '<h2 class="font-lightgray center-text">Hmmm... Looks Like Something Is Wrong.</h2><p class="font-gray bold center-text">Please <a href="contact.php">Contact</a> Us and Let Us Know! Thanks.</p></div><div class="dummy-space">
	</div>';
}

$sql68 = "SELECT * FROM post ORDER BY enrolls DESC";
$query = mysqli_query($db_conx, $sql68);
$popular = mysqli_num_rows($query);
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php echo $name; ?></title>
	<?php include_once("head.php"); ?>
	<?php include_once("stars.php"); ?>

</head>
<body>

	<?php include_once("template_PageTop.php"); ?>
	<!-- <div class="fixed user-pop">
		<h1 class="margin-none center-text"><i class="profile-main gray-background"></i><?php echo $name; ?></h1>
	</div> -->

	<main>
		<div class="parallax" style="padding-bottom: 0;">

			<div class="profile-background border-top-bottom border-gray large-padding padding-top-bottom" >
				<div class="pageMiddle">
					<div class="profile-info profile-contain transparent-dark-background">

            <h1 class="font-white narrow-font center-text">Most Popular Posts</h1>
            <p class="font-gray">
              <b>In case you were wondering:</b><br>
              This page sorts posts by the amount people that have enrolled them. This way we can understand how many people have taken a look at the post and we can have a better understanding of how many people have seen and been engaged to this post.
            </p>

					</div>
				</div>
			</div>

			<div class="parallax-user">

				<div class="pageMiddle med-margin-top relative popular-results" style="z-index: 20;">


					<?php echo $statuslist; ?>


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
		$('.parallax-user').css('margin-top', profileHeight + 100);

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

	$('body').on('click','.enroll', function() {
		var statusid = $(this).attr('data-id');
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

	var post_nm = '<?php echo $statusnumrows68; ?>';
	$(window).scroll(function() {
		var sc = $(window).scrollTop();
		var wh = $(window).height();
		var dh = $(document).height();
		if(sc >= dh - (wh + 400) ) {
			if(post_nm < +'<?php echo $popular ?>'){
				$('.background-loader').fadeIn(100);
				$('body').css('overflow-y', 'hidden');
				$.post('php_parsers/loadmore.php', {
					post_nm: post_nm,
					action: 'load more popular'

				}, function(data) {
					var datArray = data.split("|");
					var stage = datArray[0];
					var info = datArray[1];

					if(stage != "success") {
						alert('Whoops. Something is wrong. Please contact us and tell us about it. Thank you!');
						// alert(data);

					} else {
						$('.background-loader').fadeOut(100);
						$('body').css('overflow-y', 'scroll');
						$('.popular-results').append(info);
						post_nm = +post_nm + 10;
						var textboxWidth = $('.postarea').width() - $('.postimg').width() - 21;
						$('.textbox').css({'width': textboxWidth});
					}
				});
			}
		}
	});
})

</script>
