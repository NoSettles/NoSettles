<?php
include_once("php_includes/check_login_status.php");
include_once("classes/develop_php_library.php");
// If user is logged in, header them away

?><?php
// Initialize any variables that the page might echo
$s = "";
// Make sure the _GET username is set, and sanitize it
if(isset($_GET["s"])){
	$s = $_GET['s'];
	$search = str_replace("+"," ",$s);
	$search = str_replace("OplusO","+",$search);
} else {
	header('location: /');
}

if(isset($_SESSION["username"])){
	$jvVar = "$('.stars')";
	$tooltip = "";
} else {
	$jvVar = "$('.nonexistance')";
	$tooltip = "data-tooltip='Please log in or sign up to rate.'";
}

$sql = "SELECT id FROM post WHERE tags LIKE '%$search%' OR blurb LIKE '%$search%' ORDER BY enrolls DESC";
$query = mysqli_query($db_conx, $sql);
$searches = mysqli_num_rows($query);

?><?php
$statuslist = "";
$space = "";
$sql = "SELECT * FROM post WHERE tags LIKE '%$search%' OR blurb LIKE '%$search%' ORDER BY enrolls DESC LIMIT 10";
$query = mysqli_query($db_conx, $sql);
$statusnumrows = mysqli_num_rows($query);
if($statusnumrows > 0){
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$statusid = $row["id"];
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
		$image = $row["image"];
		$rating = $row["rating"];
		$postdate = $row["postdate"];

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

		$timeAgoObject = new convertToAgo;
		$convertedTime = ($timeAgoObject -> convert_datetime($postdate)); // Convert Date Time
		$when = ($timeAgoObject -> makeAgo($convertedTime));
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

		$sql2 = "SELECT * FROM review WHERE postid='$statusid'";
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
			<div class="border-lightgray border-top-bottom relative med-padding flex-box-between postarea">
				<span class="fake-img float-left postimg med-margin-right"  style="'.$photo.'">
				</span>

				<div class="textbox">
					<h2 class="margin-none flex-box-between"><a href="source.php?id='.$statusid.'">'.$title.'</a> <span><a data-id="'.$statusid.'" href="'.$web.'" target="_blank" class="underline font-gray med-font narrow-font enroll">Visit Website</a></span></h2>

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
			</div>';

	}
} else {
	$statuslist = '<div class="med-large-font font-lightgray center-text full-width">Sorry! We couldn\'t find anything similar to "'.$search.'".<br />Try making your search less specific.</div><div class="dummy-space"></div>';
}

if($statusnumrows == 1) {
	$space = '<div class="dummy-space"></div>';
}

$searcher = '';
if(isset($log_username) && $log_username != '') {
	$searcher = $log_username;
} else {
	$searcher = '';
}
$sql = "INSERT INTO search (username, search, postdate)
				VALUES('$searcher','$search',now())";
$query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
$uid = mysqli_insert_id($db_conx);

?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title><?php echo $search; ?></title>
	<?php include_once("head.php"); ?>
	<?php include_once("stars.php"); ?>
	<?php include_once("search.php"); ?>
	<?php include_once("rate.php"); ?>

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
					<div class="flex-box-between profile-info profile-contain transparent-dark-background">

						<div class="center" style="width: 90%; margin-top: 25px;">
						  <form class="searchMain" onsubmit="return false;">
						    <input type="text" class="searchTermMain med-large-font font-white" id="search" placeholder="Try Another Search" maxlength="50"/>
						    <input class="searchMainButton" type="submit" />
						  </form>
						</div>

					</div>
				</div>
			</div>

			<div class="parallax-user">

				<div class="pageMiddle med-margin-top relative searchresults" style="z-index: 20;">

					<h2 class="large-font center-text font-lightgray">Search Results:</h2>

					<?php echo $statuslist;
								echo $space;
					?>


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

	var post_nm = '<?php echo $statusnumrows; ?>';

	$(window).scroll(function() {
    var sc = $(window).scrollTop();
		var wh = $(window).height();
		var dh = $(document).height();
		if(sc >= dh - (wh + 400) ) {
			if(post_nm < +'<?php echo $searches ?>'){
				$('.background-loader').fadeIn(100);
				$('body').css('overflow-y', 'hidden');
				$.post('php_parsers/loadmore.php', {
					post_nm: post_nm,
					search: '<?php echo $search; ?>',
					action: 'load more searches'

				}, function(data) {
					var datArray = data.split("|");
					var stage = datArray[0];
					var info = datArray[1];

					if(stage != "success") {
						// alert('Whoops. Something is wrong. Please contact us and tell us about it. Thank you!');
						alert(data);

					} else {
						$('.background-loader').fadeOut(100);
						$('body').css('overflow-y', 'scroll');
						$('.searchresults').append(info);
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
