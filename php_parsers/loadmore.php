<?php
include_once("../php_includes/check_login_status.php");
include_once("../classes/develop_php_library.php");
?><?php
if(isset($_SESSION["username"])){
	$jvVar = "$('.stars')";
	$tooltip = "";
} else {
	$jvVar = "$('.nonexistance')";
	$tooltip = "data-tooltip='Please log in or sign up to rate.'";
}

if(isset($_POST['action']) && $_POST['action'] == 'load more searches') {
  $search = $_POST['search'];
  $post_nm = $_POST['post_nm'];
  $post_nm2 = $post_nm + 10;

  $statuslist = "";
  $space = "";
  $sql = "SELECT * FROM post WHERE tags LIKE '%$search%' OR blurb LIKE '%$search%' ORDER BY enrolls Desc LIMIT $post_nm, $post_nm2";
  $query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
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
  			<div class="full-width border-lightgray border-top-bottom relative med-padding flex-box-between postarea">
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
  	$statuslist = '';
  }

	echo 'success|'.$statuslist;
  exit();
}
?><?php
if(isset($_POST['action']) && $_POST['action'] == 'load more feed') {
  $post_nm = $_POST['post_nm'];
  $post_nm2 = $post_nm + 5;

	$statuslist = "";
	$statuslist1 = "";
	$sql = "SELECT interest FROM interests WHERE username='$log_username' ORDER BY postdate DESC LIMIT $post_nm, $post_nm2";
	$query = mysqli_query($db_conx, $sql);
	$statusnumrows = mysqli_num_rows($query);
	if($statusnumrows >= 1){
		while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
			$interest = $row["interest"];

			$sql68 = "SELECT * FROM post WHERE tags LIKE '%$interest%' OR blurb LIKE '%$interest%' ORDER BY postdate DESC LIMIT 10";
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
						</div>';


				}
				$statuslist1 .= '
					<h3 class="center-text font-gray margin-none med-margin-top">'.$interest.'</h3><br>'.$statuslist;
				$statuslist = "";
			}
		}
	}

	echo 'success|'.$statuslist1;
  exit();
}

?><?php
if(isset($_POST['action']) && $_POST['action'] == 'load more user') {
  $post_nm = $_POST['post_nm'];
  $u = $_POST['u'];
  $post_nm2 = $post_nm + 10;

	$statuslist = "";
	$ptid = "";
	$sql = "SELECT ptid FROM enroll WHERE username='$u' ORDER BY date DESC LIMIT $post_nm, $post_nm2";
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
						</div>';

				}
			}
		}
	}

  echo 'success|'.$statuslist;
  exit();
}
?><?php
if(isset($_POST['action']) && $_POST['action'] == 'load more post') {
  $post_nm = $_POST['post_nm'];
  $u = $_POST['u'];
  $post_nm2 = $post_nm + 10;

	$statuslist = "";
	$sql = "SELECT * FROM post WHERE author='$u' ORDER BY postdate DESC LIMIT $post_nm, $post_nm2";
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
				<div class="full-width border-lightgray border-top-bottom relative med-padding flex-box-between postarea">
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
	}

  echo 'success|'.$statuslist;
  exit();
}

if(isset($_POST['action']) && $_POST['action'] == 'load more popular') {
  $post_nm = $_POST['post_nm'];
  $post_nm2 = $post_nm + 10;

  $statuslist = "";
  $space = "";
  $sql = "SELECT * FROM post ORDER BY enrolls ASC LIMIT $post_nm, $post_nm2";
  $query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
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
  			<div class="full-width border-lightgray border-top-bottom relative med-padding flex-box-between postarea">
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
  	$statuslist = '';
  }

	echo 'success|'.$statuslist;
  exit();
}


if(isset($_POST['action']) && $_POST['action'] == 'load more featured') {
  $post_nm = $_POST['post_nm'];
  $post_nm2 = $post_nm + 10;

	$statuslist1 = "";
	$ptid = "";
	$space = "";
	$sql = "SELECT ptid FROM featured ORDER BY date DESC LIMIT $post_nm, $post_nm2";
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
  	$statuslist1 = '';
  }

	echo 'success|'.$statuslist1;
  exit();
}

?>
