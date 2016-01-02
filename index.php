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

?><?php
$statuslist1 = "";
$ptid = "";
$space = "";
$sql = "SELECT ptid FROM featured ORDER BY date DESC LIMIT 3";
$query = mysqli_query($db_conx, $sql);
$statusnumrows = mysqli_num_rows($query);
if($statusnumrows > 0){
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$ptid = $row["ptid"];

		$sql68 = "SELECT * FROM post WHERE id='$ptid'";
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

				$statuslist1 .= '
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
		}
	}
} else {
	$statuslist1 = '<h2 class="font-lightgray center-text">Hmmm... Looks Like Something Is Wrong.</h2><p class="font-gray bold center-text">Please <a href="contact.php">Contact</a> Us and Let Us Know! Thanks.</p></div><div class="dummy-space">
	</div>';
}
?><?php
$statuslist = "";
$sql68 = "SELECT * FROM post ORDER BY enrolls DESC LIMIT 3";
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
?>
<!DOCTYPE html>
<html lang="en">
<head>

	<!--/////////////////////////Meta tags/////////////////////////////-->

  <title>NoSettles | Learn Something New</title>
  <meta name="description" content="Stop dreaming. Make your fundraiser within minutes and turn your dreams into reality!">

  <link rel="stylesheet" href="ism/css/my-slider.css"/>
	<?php include_once("head.php"); ?>

  <script type="text/javascript" src="ism/js/ism-2.0.1-min.js"></script>
	<?php include_once("stars.php"); ?>

  <script type="text/javascript">
		function replyToStatus() {
			_("st_ok").innerHTML = "Please login or sign up to comment, post and donate.";
		}

		$(window).scroll(function() {
			var sc = $(window).scrollTop()
			var sc_para = sc * 1.3;
			if (sc > 200) {
				$('.idea1, .idea2, .idea3').css('background-position', '0 -'+sc_para+'px, 0 0');

				if(sc > $('.window-tint').offset().top - $(window).height()){

					var opacity = (sc - $('.window-tint').offset().top + 500) / (sc / 10);

					$('.window-tint').css({'opacity': opacity});

				}
			}

			var blur = sc / 100;

			if(sc < $('.ism-slider').height()) {
				$('.ism-slider').css({'filter': 'blur('+blur+'px)', '-moz-filter': 'blur('+blur+'px)'});
			}
		});

		$(function() {

			function ismResponsive() {
				var wHeight = $(window).height();
				var ismHeight = (wHeight / 3) * 2;
				$('.ism-slider').css({'height': ismHeight});
				$('.header-img-parallax').css({'margin-top': ismHeight - 11});
			}

			ismResponsive();
			$(window).resize(ismResponsive);

		})
	</script>

</head>
<body data-pageName="home">

	<?php include_once("template_PageTop.php"); ?>


<div class="ism-slider fixed" style="border-radius: 0; top: -1%;; z-index: 9;" data-play_type="loop">
	<ol>
    <li>
			<div style="background-image: url(ism/image/slides/_u/1443055834370_925381.jpg)" class="image full">
				<h1 class="absolute large-font text-shadow left-text-align" style="margin-left: 10%; margin-top: 20%;">Learn Anything.</h1>
			</div>

		</li>
    <li>
			<div style="background-image: url(ism/image/slides/_u/1443056005351_94525.jpg)" class="image full">
				<h1 class="absolute large-font font-white text-shadow left-text-align" style="margin-left: 10%; margin-top: 20%;">Get The Best Sources.</h1>
			</div>

		</li>
    <li>
			<div style="background-image: url(ism/image/slides/_u/1443056039341_126365.jpg)" class="image full image-bottom">
				<h1 class="absolute large-font font-white third-width text-shadow left-text-align" style="margin-left: 10%; margin-top: 20%;">Learn To Do Anything You Ever Wanted.</h1>
			</div>

		</li>
    <li>
			<div style="background-image: url(ism/image/slides/_u/math.jpg)" class="image full">
				<h1 class="absolute large-font font-white third-width text-shadow left-text-align" style="margin-left: 10%; margin-top: 20%;">No Limits.</h1>
			</div>

		</li>
    <li>
			<div style="background-image: url(ism/image/slides/home-office-336377_1280.jpg)" class="image full">
				<h1 class="absolute large-font font-white third-width text-shadow left-text-align" style="margin-left: 10%; margin-top: 20%;">No More Hours Of Research Needed.</h1>
			</div>

		</li>
  </ol>

</div>

<main>
	<div class="header-img-parallax">
		<p class="ism-badge small-font"><a class="ism-link" target="_blank" href="http://imageslidermaker.com">Slider generated with ISM</a></p>
	  <div class="pageMiddle">
			<div class="center" style="width: 90%; margin-top: 25px;">
			  <form class="searchMain" onsubmit="return false;">
			    <input type="text" class="searchTermMain med-large-font" id="search" placeholder="Start Learning" maxlength="50" autofocus/>
			    <input class="searchMainButton" type="submit" />
			  </form>
			</div>
<!-- Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam nunc sem, dictum et nisl quis, tristique vulputate justo. Etiam in urna id odio egestas ullamcorper id eget justo. Vestibulum laoreet imperdiet nibh ac malesuada. Sed quis mauris molestie metus. -->
	    <div class="container-1 transparent">
	      <h1>Featured</h1>
	      <div class="flex-box-culumn">

					<?php echo $statuslist1; ?>

	      </div>
				<a href="featured.php" class="font-blue med-med-font float-right">See More <i class="fa fa-arrow-right"></i></a>
	    </div>



	    <div class="container-2 transparent med-margin-top">
	      <h1>What Others Are Learning</h1>
	      <div class="flex-box-culumn">

					<?php echo $statuslist; ?>




	      </div>
				<a href="popular.php" class="font-blue med-med-font float-right">See More <i class="fa fa-arrow-right"></i></a>
	    </div>


			<div class="container-3 transparent med-margin-top border-lightgray border-top-bottom">
	      <h1>Start Learning New Skills</h1>
	      <div class="flex-box">
					<div class="center-text">
						<p class="big-font">With NoSettles you can search for the best sources in the world to learn your favourite skills. You can learn anything from programming, to marketing, to skiing and to anything that you might find interesting. <strong>Start Learning Today!</strong></p> <br>
						<div class="center-text">
							<a href="about.php" class="transparent-blue-btn med-btn">Learn More</a>
						</div>
					</div>
				</div>
	    </div>

	    <div class="flex-box small-margin-top">

	    	<div class="circle med-square idea1">
					<div class="window-tint">
            <div class="promo-text"><strong><span>Learn</span></strong></div>
          </div>
	    	</div>

	    	<div class="circle med-square idea2">
					<div class="window-tint">
            <div class="promo-text"><strong><span>Something</span></strong></div>
          </div>
	    	</div>

	    	<div class="circle med-square idea3" >
					<div class="window-tint">
            <div class="promo-text"><strong><span>New</span></strong></div>
          </div>
	    	</div>

	    </div>

	  </div>
	</div>
</main>


<?php include_once("template_PageBottom.php"); ?>

<script type="text/javascript">
	$(function() {
		$(window).scroll(function() {
	    var sc = $(window).scrollTop();
			var ismHeight = $('.ism-slider').height();
			if(sc > ismHeight) {
				$('.ism-slider').fadeOut(100);
			} else {
				$('.ism-slider').fadeIn(100);
			}

		});
	})
</script>

</body>
</html>
