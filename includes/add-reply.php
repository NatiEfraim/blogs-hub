<?php

require_once("../admin/includes/dbh.php");
// ///get all inputs fields
$blogPostId = isset($_POST['replyBlogPostId']) ? $_POST['replyBlogPostId'] : "";
$commentParentId = isset($_POST['commentParentId']) ? $_POST['commentParentId'] : "";
$commentSenderName = isset($_POST['replyCName']) ? $_POST['replyCName'] : "";
$commentEmail = isset($_POST['replyCEmail']) ? $_POST['replyCEmail'] : "";
$comment = isset($_POST['replyCMessage']) ? $_POST['replyCMessage'] : "";
////set the date and the time
$date = date("Y-m-d");
$time = date("H:i:s");
// ///diffine the sql query
$sqlAddReply = "INSERT INTO blog_comments (n_blog_comment_parent_id, n_blog_post_id, v_comment_author, v_comment_author_email, v_comment, d_date_created, d_time_created) VALUES ('$commentParentId', '$blogPostId', '$commentSenderName', '$commentEmail', '$comment', '$date', '$time')";
$queryAddReply = mysqli_query($conn, $sqlAddReply);///run on the 'blog_comments' table

if (!$queryAddReply) {
    $result = "error";
} else {
    $result = "success";
}
echo $result;
