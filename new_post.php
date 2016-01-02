<?php
include_once("php_includes/check_login_status.php");
// If user is logged in, header them away
if(!isset($log_username) || $log_username == ""){
	header("location: /");
	exit();
}
?><?php
// Initialize any variables that the page might echo
$u = "";
$sex = "Male";
$userlevel = "";
$country = "";
$joindate = "";
$lastsession = "";
// Make sure the _GET username is set, and sanitize it
if(isset($_GET["u"])){
	$u = preg_replace('#[^a-z0-9]#i', '', $_GET['u']);
}
// Select the member from the users table
$sql = "SELECT * FROM users WHERE username='$log_username' LIMIT 1";
$user_query = mysqli_query($db_conx, $sql);
// Fetch the user row from the query above
while ($row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
	$profile_id = $row["id"];
	$country = $row["country"];
	$userlevel = $row["userlevel"];
	$signup = $row["signup"];
	$lastlogin = $row["lastlogin"];
	$joindate = strftime("%b %d, %Y", strtotime($signup));
	$lastsession = strftime("%b %d, %Y", strtotime($lastlogin));
}
?><?php
if (isset($_POST['title'])){
	// Make sure post data is not empty
	if($_POST['title'] == "" || $_POST['price'] == "" || $_POST['shortDesc'] == "" || $_POST['tags'] == ""){
		mysqli_close($db_conx);
    echo "Please do not leave required fields empty.";
    exit();
	}
	$image = preg_replace('#[^a-z0-9.]#i', '', $_POST['image']);
	//moving the file to the permanent folder
	if ($image != "na") {
		$kaboom = explode(".", $image);
		$fileExt = end($kaboom);
		rename("tempUploads/$image", "permUploads/$image");
		include_once("php_includes/image_resize.php");
		$target_file = "permUploads/$image";
		$resized_file = "permUploads/$image";
		$wmax = 1200;
		$hmax = 1200;
		list($width, $height) = getimagesize($target_file);
		if ($width > $wmax || $height > $hmax) {
			img_resize($target_file, $resized_file, $wmax, $hmax, $fileExt);
		}
	}
	// Clean all of the $_POST vars that will interact with the database
	$title = htmlentities($_POST['title']);
	$title = mysqli_real_escape_string($db_conx, $title);
	$web = htmlentities($_POST['web']);
	$web = mysqli_real_escape_string($db_conx, $web);
	$tags = htmlentities($_POST['tags']);
	$tags = mysqli_real_escape_string($db_conx, $tags);
	$tags = $tags.',';
	$category = $_POST['category'];
	$price = preg_replace("/[^0-9]/","",$_POST['price']);
	$shortDesc = htmlentities($_POST['shortDesc']);
	$shortDesc = mysqli_real_escape_string($db_conx, $shortDesc);
	$android = htmlentities($_POST['android']);
	$android = mysqli_real_escape_string($db_conx, $android);
	$apple = htmlentities($_POST['apple']);
	$apple = mysqli_real_escape_string($db_conx, $apple);
	$longDesc = $_POST['longDesc'];
	$longDesc = mysqli_real_escape_string($db_conx, $longDesc);

	// Make sure account name exists (the profile being posted on)
	$sql = "SELECT COUNT(id) FROM users WHERE username='$log_username' LIMIT 1";
	$query = mysqli_query($db_conx, $sql);
	$row = mysqli_fetch_row($query);
	if($row[0] < 1){
		mysqli_close($db_conx);
		echo "Your account has been either disabled or deleted. Please contact us for more info.";
		exit();
	}

	// Insert the status post into the database now
	$sql = "INSERT INTO post(author, title, category, web, tags, blurb, description, image, android, apple, price, postdate)
			VALUES('$log_username','$title','$category','$web','$tags','$shortDesc','$longDesc','$image','$android','$apple','$price',now())";
	$query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
	$id = mysqli_insert_id($db_conx);
	// mysqli_query($db_conx, "UPDATE status SET osid='$id' WHERE id='$id' LIMIT 1") or die(mysqli_error($db_conx));

	mysqli_close($db_conx);
	echo "post_ok|$log_username";
	exit();
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>NoSettles</title>
	<?php include_once("head.php"); ?>
</head>

<script type="text/javascript">
var hasImage = "na";
hasImage = "na";
function doUpload(id){
	var file = _(id).files[0];
	if(file.name === ""){
		return false;
	}
	if(file.type != "image/jpeg" && file.type != "image/gif" && file.type != "image/png" && file.type != "image/jpeg"){
		alert("That file type is not supported.");
		return false;
	}
	var formdata = new FormData();
	formdata.append("stPic", file);
	var ajax = new XMLHttpRequest();
	ajax.upload.addEventListener("progress", progressHandler, false);
	ajax.addEventListener("load", completeHandler, false);
	ajax.addEventListener("error", errorHandler, false);
	ajax.addEventListener("abort", abortHandler, false);
	ajax.open("POST", "php_parsers/photo_system.php");
	ajax.send(formdata);
}
function progressHandler(event) {
	// var percent = (event.loaded / event.total) * 100;
	// _("outer").innerHTML = "<div id='inner'>"+percent+"%</div>";
	// _("inner").style.width = percent+'%';
}
function completeHandler(event){
	var data = event.target.responseText;
	var datArray = data.split("|");
	if(datArray[0] == "upload_complete"){
		hasImage = datArray[1];
		$('#imgUpload').css('background-image', 'url(tempUploads/'+hasImage+')');
	} else {
		$('.post-status1').html(datArray[0]);
	}
}
function errorHandler(event){
	_("uploadDisplay_SP").innerHTML = "Upload Failed";
	_("triggerBtn_SP").style.display = "block";
}
function abortHandler(event){
	_("uploadDisplay_SP").innerHTML = "Upload Aborted";
	_("triggerBtn_SP").style.display = "block";
}
function triggerUpload(e,elem){
	e.preventDefault();
	_(elem).click();
}

</script>

<script type="text/javascript" src="/tinymce/tinymce.min.js"></script>
<script type="text/javascript">
$(function() {
	tinymce.init({
		plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks fullscreen textcolor colorpicker",
        "insertdatetime media table contextmenu paste imagetools hr"
    ],
    toolbar: "insertfile undo redo | styleselect fontselect fontsizeselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
		selector: "#post-lg-dpn"
	});

});
		// toolbar: "undo redo | styleselect fontselect fontsizeselect | forecolor backcolor | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image",
		// theme: "modern",
  	// skin: 'light',
</script>

<body>
  <?php include_once("template_PageTop.php"); ?>


	<main>
		<div class="parallax" style="padding-bottom: 0;">
			<div class="profile-background border-top-bottom border-gray large-padding padding-top-bottom" >
				<h1 class="center-text large-font font-white narrow-font">Let's Share Learning!</h1>
			</div>


			<div class="parallax-user ">
				<div class="pageMiddle relative">


					<div class="lg-dpn-background relative screen-right long-animation">
						<h2 class="gray center-text italic">Full Description:</h2>
						<h3 class="gray center-text">Here you can write, edit and insert any details about the source. There's no limit, sit back, relax and write!</h3>
						<hr>

						<textarea name="Large Description" id="post-lg-dpn"></textarea>

						<span class="post-status2" style="color: #f00; font-weight: bold;"></span>
						<hr>
						<input type="submit" value="Complete Post" class="med-btn green-white-btn small-margin med-margin-left complete-post" />
						<input type="submit" value="Back" class="med-btn gray-white-btn small-margin go-back" />
					</div>



					<div class="absolute long-animation go-left screen-left top right full-width">
						<div class="border-top-bottom border-gray small-margin-top">
							<h2 class="center-text">Before You Post!</h2>
							<p class="center-text">Please check our website to make sure that the source you are about to add does not exist. Please read the "Notes" area for clarification.<br>Thank you for your time!</p>
						</div>


						<div class="med-full-container small-margin-top setting-message">
							<div class="flex-box-between">

								<div class="width-45">

									<label class="white-background small-padding bold box-label relative">Details:*</label>
									<div class="border-lightgray small-padding-bottom">
										<div class="fancy-text-form fancy-login-form small-med-margin-top">
											<input type="text" maxlength="55" id="post-title" class="transparent-blue-box med-long-textbox post-required" />
											<label>Title*</lable>
										</div>

										<div class="fancy-text-form fancy-login-form small-med-margin-top">
											<select name="Category" id="post-category" class="transparent-blue-select med-long-textbox post-required">
												<option value=""></option>
												<option value="Arts">Arts</option>
												<option value="Business">Business</option>
												<option value="Computer">Computer</option>
												<option value="Cooking">Cooking</option>
												<option value="Dance">Dance</option>
												<option value="History">History</option>
												<option value="Language">Language</option>
												<option value="Math">Math</option>
												<option value="Music">Music</option>
												<option value="Programming">Programming</option>
												<option value="Psychology">Psychology</option>
												<option value="School Related">School Related</option>
												<option value="Science">Science</option>
												<option value="Self Defense">Self Defense</option>
												<option value="Sports">Sports</option>
												<option value="Technology">Technology</option>
												<option value="Textiles">Textiles</option>
												<option value="Video">Video</option>
												<option value="Other">Other...</option>
											</select>
											<label>Category*</lable>
										</div>

										<div class="fancy-text-form fancy-login-form small-med-margin-top" data-tooltip="Example: https://example.com">
											<input type="text" id="post-web" class="transparent-blue-box med-long-textbox post-required" />
											<label>Link To Site*</lable>
										</div>

										<div class="fancy-text-form fancy-login-form small-med-margin-top" data-tooltip="Seperate you tags by 'commas'.">
											<input type="text" maxlength="45" id="post-tag" class="transparent-blue-box med-long-textbox post-required" />
											<label>Tags*</lable>
										</div>

										<div class="fancy-text-form fancy-login-form small-med-margin-top">
											<label class="display-block italic">Blurb:* <em>(maximum: 550 characters)</em></label>
											<textarea name="Small Description" id="post-st-dpn" class="transparent-blue-textarea med-long-textbox contact-required post-required" placeholder="A Short Blurb About Your Fundraiser" maxlength="550"></textarea>
										</div>
									</div>

									<label class="white-background small-padding bold box-label relative">Cost:*</label>
									<div class="border-lightgray small-padding-bottom price-section">
										<div class="small-margin-top med-margin-left small-padding">
											<label class="inline cursor-pointer" for="post-free"><span class="checkboxSpan relative inline-block supertiny--margin-bottom short-animation"></span><span class="small-margin-left">Free</span></label>
											<input id="post-free" type="checkbox" class="transparent"><br/>
										</div>
										<div class="fancy-text-form fancy-login-form small-med-margin-top price-textbox">
											<span><i class="fa fa-usd font-blue fa-lg"></i></span>
											<input type="text" id="post-price" class="transparent-blue-box med-long-textbox" />
											<label class="med-larger-margin-left" for="post-price">Price</lable>
										</div>
									</div>


									<label class="white-background small-padding bold box-label relative" style="z-index: 5;">Apps:</label>
									<div class="border-lightgray small-padding-bottom" data-tooltip="If there are no apps, please leave the boxes empty.">
										<div class="fancy-text-form fancy-login-form small-med-margin-top">
											<span><i class="fa fa-apple font-lightblack fa-lg"></i></span>
											<input type="text" maxlength="300" id="post-apple" class="transparent-blue-box med-long-textbox" />
											<label class="med-larger-margin-left">Apple App Url:</label>
										</div>

										<div class="fancy-text-form fancy-login-form small-med-margin-top">
											<span><i class="fa fa-android font-AndroidGreen fa-lg"></i></span>
											<input type="text" maxlength="300" id="post-android" class="transparent-blue-box med-long-textbox" />
											<label class="med-larger-margin-left">Android App Url:</label>
										</div>
									</div>

									<label class="white-background small-padding bold box-label relative">Picture Of The Source:</label>
									<div class="border-lightgray small-padding-bottom">
										<div class="fancy-text-form fancy-login-form small-med-margin-top" data-tooltip="Please select an image file under 2MB.">
											<label class="display-block italic">Upload Photo:</label>
											<span class='upload display-none'>
												<form id='image_SP' enctype='multipart/form-data' method='post'>
													<input accept="image/*" type="file" name="FileUpload" id="fu_SP" onchange="doUpload('fu_SP')"/>
												</form>
											</span>
											<div class="cursor-pointer opacity-hover photo-upld" id="imgUpload" onclick="triggerUpload(event, 'fu_SP')" >
											</div>
										</div>
									</div>

								</div>

								<div class=" med-half-container small-margin-top small-padding">
									<h3 class="margin-none font-gray">Notes</h3>

									<p class="font-gray">Please avoid the use of any swear words, <strong>we seriously do not appreciate that kind of language</strong>.</p>

									<p class="font-gray">Don't hesitate to ask us any questions! We'll be more than happy to help you with any questions that you might have. <a href="contact.php" class="underline">Contact Us</a>.</p>

									<p class="font-gray"><strong>Please specify the cost of the source in USD.</strong></p>

									<p class="font-gray"><strong>If you leave the page without posting your source, any data on the input fields will be deleted right away</strong>.</p>

									<p class="font-gray"><strong>Duplicated sources will be removed if found.</strong></p>

									<p class="font-gray"><strong>Please leave the apps area blank, if there are no apps.</strong></p>

									<p class="font-gray">Make sure to fill in the entire form. Once it's complete, simply click on "Next Step".</p>
								</div>

							</div>
						</div>
						<span class="post-status1" style="color: #f00; font-weight: bold;"></span>
						<hr>
						<input type="submit" value="Next Step" class="med-btn green-white-btn small-margin submit-post" />
						<a href="user.php?u=<?php echo $log_username; ?>" class="med-btn gray-white-btn small-margin" > Cancel Post </a>
						<a href="contact.php" class="med-btn blue-white-btn small-margin display-none" > Contact Us </a>

					</div>
				</div>
			</div>
		</div>
	</main>

  <?php include_once("template_PageBottom.php"); ?>
</body>

<script type="text/javascript">
	$(function() {

		function topImage() {
			var profileHeight = $('.profile-background').height();
			$('.parallax-user').css('margin-top', profileHeight + 100);

			$('.parallax-user').css('min-height', $('.go-left').height() + 50);

			var longTextWidth = $('.med-long-textbox').width();
			$('.width-45').css({'min-width': longTextWidth + 40});
		}

		topImage();
		$(window).resize(function() {
			topImage();
		});

		$(window).scroll(function() {
			var wScroll = $(window).scrollTop();

			if(wScroll > $('.profile-background').height() + 100) {
				$('.profile-background').css('display', 'none');

			} else {
				$('.profile-background').css('display', 'block');

			}
		});



		$('.post-required').keyup(function() {
				$('.post-status1').html(' ');
		});

		$('#post-free').click(function() {
			if(!$(this).is(':checked')) {
				$("#post-free").siblings().children('.checkboxSpan').removeClass('checked');
				$(".price-textbox").animate({width:'toggle'},350);

			} else {
				$("#post-free:checked").siblings().children('.checkboxSpan').addClass('checked');
				$('#post-price').val('');
				$(".price-textbox").animate({width:'toggle'},350);
			}
		});

		$('#post-free, #post-price').click(function () {
			$('.price-section').removeClass('errorBox');
			$('.post-status1').html(' ');
		});

		$('.submit-post').click(function() {
      var $post_title = $('#post-title').val();
      var $post_category = $('#post-category').val();
			var $post_tag = $('#post-tag').val();
      var $post_st_dpn = $('#post-st-dpn').val();
      var $post_price = $('#post-price').val();
			var $post_apple = $('#post-apple').val();
			var $post_android = $('#post-android').val();
			var $post_web = $('#post-web').val();
			var $post_free = $('#post-free');
      var $post_status = $('.post-status1');

			if($post_title == "" || $post_category == "" || $post_st_dpn == "" || $post_category == "" || $post_web == "") {
				$post_status.html('Please fill in all the fields that have * on them.');

			} else if(!$post_free.is(':checked') && $post_price == "") {
				$post_status.html('Please fill in the cost area.');
				$('.price-section').addClass('errorBox');

			} else if($post_price != "" && !isNumeric($post_price)) {
			  	$post_status.html('Please write the price in numbers.');
			  	$('#post-price').addClass('error');

			} else {
				$('html, body').animate({
			    scrollTop: $(".lg-dpn-background").offset().top - 100
				}, 500);
				$('.go-left').removeClass('right', 700);
				$('.lg-dpn-background').addClass('left', 700);

			}



		});

		$('.complete-post').click(function() {
			var full_story = tinyMCE.get('post-lg-dpn').getContent();
      var $post_title = $('#post-title').val();
      var $post_category = $('#post-category').val();
			var $post_tag = $('#post-tag').val();
      var $post_st_dpn = $('#post-st-dpn').val();
      var $post_price = $('#post-price').val();
			var $post_apple = $('#post-apple').val();
			var $post_android = $('#post-android').val();
			var $post_web = $('#post-web').val();
			var $post_free = $('#post-free');

			if (full_story == "") {
				$('.post-status2').html('Please write down some details about the source.');
			} else {

				if($post_free.is(':checked')) {
				 $post_price = "0";
			 	}

				$.post('new_post.php', {
					title: $post_title,
					category: $post_category,
					web: $post_web,
					tags: $post_tag,
					shortDesc: $post_st_dpn,
					price: $post_price,
					android: $post_android,
					apple: $post_apple,
					longDesc: full_story,
					image: hasImage

				}, function(data) {
					var datArray = data.split("|");
					var stage = datArray[0];
					var user = datArray[1];

					if(stage == "post_ok") {
						window.location = "post.php?u="+user;

					} else {
						$('.post-status2').html(data)

					}

				});
			}

		});

		// $('.lg-dpn-background').mCustomScrollbar({
	  // 	theme:"minimal-dark"
		// });

		$('.go-back').click(function() {
			$('.go-left').addClass('right', 700);
			$('.lg-dpn-background').removeClass('left', 700);
		});

	})
</script>


</html>
