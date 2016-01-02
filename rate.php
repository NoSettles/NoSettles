<?php
  include_once("php_includes/check_login_status.php");

  if(isset($log_username) && $log_username != '') {
    $rate_log = '.stars';
  } else {
    $rate_log = '.non-existance';
  }

  if(isset($_POST['rating']) && $_POST['rating'] <= 5) {
    $statusid = $_POST['statusid'];
    $rating = $_POST['rating'];

    if($rating > 5 || $rating < 1) {
      echo "cheat";
      exit();
    } else {
      $sql = "SELECT * FROM review WHERE postid='$statusid' AND user='$log_username' LIMIT 1";
      $query = mysqli_query($db_conx, $sql);
      $statusnumrows = mysqli_num_rows($query);
      if($statusnumrows > 0){
        $sql = "UPDATE review SET rating='$rating' WHERE postid='$statusid' AND user='$log_username' LIMIT 1";
        $query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
      } else {
        $sql = "INSERT INTO review(postid, user, rating)
            VALUES('$statusid','$log_username','$rating')";
        $query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
      }
      $sql = "SELECT SUM(rating) FROM review WHERE postid='$statusid'";
      $query = mysqli_query($db_conx, $sql);
      $statusnumrows = mysqli_num_rows($query);
      while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $allRatings = $row['SUM(rating)'];
      }
      $sql = "SELECT id FROM review WHERE postid='$statusid'";
      $query = mysqli_query($db_conx, $sql);
      $statusnumrows = mysqli_num_rows($query);
      $finalRating = $allRatings / $statusnumrows;
      $finalRating = round($finalRating, 1);

      $sql = "UPDATE post SET rating='$finalRating' WHERE id='$statusid' LIMIT 1";
      $query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));

      echo $finalRating."|".$statusnumrows;
      exit();
    }
  }

?>
<script type="text/javascript">
$(function() {
  $('<?php echo $rate_log; ?>').click(function() {
    var $this = $(this);
    var statusid = $(this).siblings('.star-rating').attr('data-statusid');
    var rating = $(this).attr('data-rate');

    $.post('rate.php', {
			statusid: statusid,
			rating: rating
		}, function(data) {
			if(data != "cheat" || data != "fail") {
				var datArray = data.split("|");
				var finalRating = datArray[0];
				var reviews = datArray[1];
        if(reviews == 1) {
          $this.siblings('.reviews').html(reviews+' Review');
        } else {
          $this.siblings('.reviews').html(reviews+' Reviews');
        }
        $this.siblings('.final-rating').html(finalRating);
			} else {
				alert('Sorry, there\'s a problem in the system. Please contact us, so we can fix it! Thank you!');
			}
		})
  });
})
</script>
