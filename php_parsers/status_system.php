<?php
include_once("../php_includes/check_login_status.php");
include_once("../classes/develop_php_library.php");
?><?php
if(isset($_POST['comment'])) {
  $comment = $_POST['comment'];
  $comment = mysqli_real_escape_string($db_conx, $comment);
	$statusid23 = $_POST['statusid'];
  if($comment == "") {
    echo 'Fail';
    exit();
  } else {
    // Insert the comment into the database now
    $sql = "INSERT INTO comments(postid, user, data, postdate)
        VALUES('$statusid23','$log_username','$comment',now())";
    $query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
    $id = mysqli_insert_id($db_conx);

    $sql = "SELECT * FROM comments WHERE postid='$statusid23' ORDER BY postdate DESC LIMIT 999999999";
    $query = mysqli_query($db_conx, $sql);
    $statusnumrows = mysqli_num_rows($query);
    echo $statusnumrows;
    exit();
  }

}
?><?php
if(isset($_POST['deletepost']) && $_POST['deletepost'] == "ok") {
	$statusid24 = $_POST['statusid'];
  $author = $_POST['author'];

	if($log_username == $author) {
		mysqli_query($db_conx, "DELETE FROM post WHERE id='$statusid24'") or die(mysqli_error($db_conx));
		mysqli_close($db_conx);
		echo "success";
		exit();
	} else {
		echo "fail";
	}
}
?><?php
if(isset($_POST['action']) && $_POST['action'] == "enroll") {
	$statusid25 = $_POST['statusid'];

  $sql = "SELECT * FROM enroll WHERE ptid='$statusid25' AND username='$log_username' LIMIT 1";
  $query = mysqli_query($db_conx, $sql);
  $statusnumrows = mysqli_num_rows($query);
  if($statusnumrows > 0){
  	echo "success";

  } else if($statusnumrows == 0) {
    $sql = "INSERT INTO enroll(username, ptid, date)
        VALUES('$log_username','$statusid25',now())";
    $query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
    $id = mysqli_insert_id($db_conx);
  	echo "success";

  } else {
    echo "fail";
  }

  $sql = "SELECT * FROM enroll WHERE ptid='$statusid25'";
  $query = mysqli_query($db_conx, $sql);
  $statusnumrows = mysqli_num_rows($query);
  $sql = "UPDATE post SET enrolls='$statusnumrows' WHERE id='$statusid25' LIMIT 1";
  $query = mysqli_query($db_conx, $sql) or die(mysqli_error($db_conx));
  exit();

}
?>
