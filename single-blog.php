<?php
require './admin/includes/db-pdo.php';
require './admin/includes/dbh.php';
// ///check the url if has blog-path
if (isset($_REQUEST['blog'])) {
    ///grab the blogPath
    $blogPath = $_REQUEST['blog'];
    /////diffine the sql query get all blog_post by the specific blogpath
    $sqlGetBlog = "SELECT * FROM blog_post WHERE v_post_path = '$blogPath' AND f_post_status = '1'";
    $queryGetBlog = mysqli_query($conn, $sqlGetBlog);
    /////try run the query - and get the data
    if ($rowGetBlog = mysqli_fetch_assoc($queryGetBlog)) {
        ///////////grab all data from the 'blog_post' table.
        $blogPostId = $rowGetBlog['n_blog_post_id'];
        $blogCategoryId = $rowGetBlog['n_category_id'];
        $blogTitle = $rowGetBlog['v_post_title'];
        $blogMetaTitle = $rowGetBlog['v_post_meta_title'];
        $blogContent = $rowGetBlog['v_post_content'];
        $blogMainImgUrl = $rowGetBlog['v_main_image_url'];
        $blogCreationDate = $rowGetBlog['d_date_created'];
        // ////get the name and the img of the author
        $blogAuthorImageUrl = $rowGetBlog['v_author_image_url'];
        $blogAuthorName = $rowGetBlog['v_author_name'];
    } else {
        /////there is nothing to show up - redirect index.php
        header("Location: ./index.php");
        exit();
    }
    // ////diffine the sql query to get all data from the 'blog_category' table
    $sqlGetCategory = "SELECT * FROM blog_category WHERE n_category_id = '$blogCategoryId'";
    $queryGetCategory = mysqli_query($conn, $sqlGetCategory);
    //////try run the query
    if ($rowGetCategory = mysqli_fetch_assoc($queryGetCategory)) {
        /////save all date we need from the 'blog_category' table
        $categoryTitle = $rowGetCategory['v_category_title'];
        $blogCategoryPath = $rowGetCategory['v_category_path'];
    }
    /////siffine the sql query for get all data from 'blog_tags' table
    $sqlGetTags = "SELECT * FROM blog_tags WHERE n_blog_post_id = '$blogPostId'";
    $queryGetTags = mysqli_query($conn, $sqlGetTags);
    ////try run the query
    if ($rowGetTags = mysqli_fetch_assoc($queryGetTags)) {
        /////save all data we need from the 'blog_tags' table.
        $blogTags = $rowGetTags['v_tag'];
        $blogTagsArr = explode(",", $blogTags);
    }
}

?>


<!-- html part -->
<!DOCTYPE html>
<html class="no-js" lang="en">

<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title>Blogs-Hub | <?php echo $blogMetaTitle; ?></title>
    <meta name="description" content="">
    <meta name="author" content="">

    <!-- mobile specific metas
    ================================================== -->
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="css/vendor.css">
    <link rel="stylesheet" href="css/styles.css">

    <!-- script
    ================================================== -->
    <script src="js/modernizr.js"></script>
    <script defer src="js/fontawesome/all.min.js"></script>

    <!-- favicons
    ================================================== -->
    <link rel="apple-touch-icon" sizes="180x180" href="apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon-16x16.png">
    <link rel="manifest" href="site.webmanifest">

</head>

<body id="top">


    <!-- preloader
    ================================================== -->
    <div id="preloader">
        <div id="loader"></div>
    </div>


    <!-- header
    ================================================== -->
    <?php include './header-opaque.php'; ?>



    <!-- content
    ================================================== -->
    <section class="s-content">

        <div class="row">
            <div class="column large-12">

                <article class="s-content__entry format-standard">

                    <div class="s-content__media">
                        <div class="s-content__post-thumb">
                            <img src="<?php echo $blogMainImgUrl; ?>" srcset="<?php echo $blogMainImgUrl; ?> 2100w, 
                            <?php echo $blogMainImgUrl; ?> 1050w, 
                            <?php echo $blogMainImgUrl; ?> 525w" sizes="(max-width: 2100px) 100vw, 2100px" alt="">
                        </div>
                    </div> <!-- end s-content__media -->

                    <div class="s-content__entry-header">
                        <h1 class="s-content__title s-content__title--post"><?php echo $blogTitle; ?></h1>
                    </div> <!-- end s-content__entry-header -->

                    <div class="s-content__primary">

                        <div class="s-content__entry-content">

                            <!-- blog-content -->
                            <?php echo $blogContent; ?>



                        </div> <!-- end s-entry__entry-content -->

                        <div class="s-content__entry-meta">

                            <div class="entry-author meta-blk">
                                <div class="author-avatar">
                                    <img class="avatar" src="<?php echo $blogAuthorImageUrl; ?>" alt="">
                                </div>
                                <div class="byline">
                                    <span class="bytext">Posted By</span>
                                    <a href="#"><?php echo $blogAuthorName; ?></a>
                                </div>
                            </div>

                            <div class="meta-bottom">

                                <div class="entry-cat-links meta-blk">
                                    <div class="cat-links">
                                        <span>In</span>
                                        <a href="./categories.php?group=<?php echo $blogCategoryPath; ?>"><?php echo $categoryTitle; ?></a>

                                    </div>
                                    <!-- date created. -->
                                    <span>On</span>
                                    <?php echo date("M j, Y", strtotime($blogCreationDate)); ?>

                                </div>

                                <div class="entry-tags meta-blk">
                                    <span class="tagtext">Tags</span>
                                    <!-- loop all tags categories -->
                                    <?php

                                    for ($i = 0; $i < count($blogTagsArr); $i++) {
                                        if (!empty($blogTagsArr[$i])) {
                                            echo "<a href='search.php?query=" . $blogTagsArr[$i] . "'>" . $blogTagsArr[$i] . "</a>";
                                        }
                                    }

                                    ?>
                                </div>

                            </div>

                        </div> <!-- s-content__entry-meta -->


                        <div class="s-content__pagenav">
                            <!-- get the prev and next blogPost  -->
                            <?php
                            //////diffine sql query for the prev blog-post
                            $sqlGetPreviousBlog = "SELECT * FROM blog_post WHERE n_blog_post_id = (SELECT max(n_blog_post_id) FROM blog_post WHERE n_blog_post_id < '" . $blogPostId . "') AND f_post_status = '1'";
                            $queryGetPreviousBlog = mysqli_query($conn, $sqlGetPreviousBlog);
                            ////diffine the sql qeury for the next query
                            $sqlGetNextBlog = "SELECT * FROM blog_post WHERE n_blog_post_id = (SELECT min(n_blog_post_id) FROM blog_post WHERE n_blog_post_id > '" . $blogPostId . "') AND f_post_status = '1'";
                            $queryGetNextBlog = mysqli_query($conn, $sqlGetNextBlog);
                            ////run the 2 query and print them 
                            if ($rowGetPreviousBlog = mysqli_fetch_assoc($queryGetPreviousBlog)) {
                                $previousBlogName = $rowGetPreviousBlog['v_post_title'];
                                $previousBlogPath = $rowGetPreviousBlog['v_post_path'];

                                echo "<div class='prev-nav'>
                                        <a href='single-blog.php?blog=" . $previousBlogPath . "' rel='prev'>
                                            <span>Previous</span>
                                            " . $previousBlogName . "
                                        </a>
                                    </div>";
                            }

                            if ($rowGetNextBlog = mysqli_fetch_assoc($queryGetNextBlog)) {
                                $nextBlogName = $rowGetNextBlog['v_post_title'];
                                $nextBlogPath = $rowGetNextBlog['v_post_path'];

                                echo "<div class='prev-nav'>
                                        <a href='single-blog.php?blog=" . $nextBlogPath . "' rel='prev'>
                                            <span>Next</span>
                                            " . $nextBlogName . "
                                        </a>
                                    </div>";
                            }

                            ?>

                        </div>
                        <!-- end s-content__pagenav -->

                    </div> <!-- end s-content__primary -->
                </article> <!-- end entry -->

            </div> <!-- end column -->
        </div> <!-- end row -->


        <!-- comments
        
        ================================================== -->
        <!-- handel with the comments -->
        <?php
        ////get all comments from the database
        $sqlGetAllComments = "SELECT * FROM blog_comments WHERE n_blog_post_id = '$blogPostId'";
        $queryGetAllComments = mysqli_query($conn, $sqlGetAllComments);
        $numComments = mysqli_num_rows($queryGetAllComments);

        ?>
        <div class="comments-wrap">

            <div id="comments" class="row">
                <div class="column large-12">

                    <h3><?php echo $numComments; ?> Comments</h3>

                    <!-- START commentlist -->
                    <ol class="commentlist" id="commentlist">

                        <?php
                        /////get all comment from the 'blog_comments' table - where has no parents - mean first comments
                        $sqlGetComments = "SELECT * FROM blog_comments WHERE n_blog_post_id = '$blogPostId' AND n_blog_comment_parent_id = '0' ORDER BY d_date_created ASC";
                        $queryGetComments = mysqli_query($conn, $sqlGetComments); ////run the query in database

                        while ($rowComments = mysqli_fetch_assoc($queryGetComments)) {
                            /////get and save all data we need - for the haed comment
                            $commentId = $rowComments['n_blog_comment_id'];
                            $commentAuthor = $rowComments['v_comment_author'];
                            $comment = $rowComments['v_comment'];
                            $commentDate = $rowComments['d_date_created'];
                            ////check about this is comments if has replay comments
                            $sqlCheckCommentChildren = "SELECT * FROM blog_comments WHERE n_blog_comment_parent_id = '$commentId' ORDER BY d_date_created ASC";
                            $queryCheckCommentChildren = mysqli_query($conn, $sqlCheckCommentChildren); ////run the query in database
                            $numCommentChildren = mysqli_num_rows($queryCheckCommentChildren); ///get num of row of replay
                            ///In case has no replay
                            if ($numCommentChildren == 0) {

                        ?>

                                <li class="depth-1 comment">
                                    <div class="comment__content">
                                        <div class="comment__info">
                                            <!-- get the comment-author-id -->
                                            <input type="hidden" id="comment-author-<?php echo $commentId; ?>" value="<?php echo $commentAuthor; ?>">
                                            <div class="comment__author"><?php echo $commentAuthor; ?></div>
                                            <div class="comment__meta">
                                                <div class="comment__time"><?php echo date("M j, Y", strtotime($commentDate)); ?></div>
                                                <div class="comment__reply">
                                                    <a class="comment-reply-link" href="#reply-comment-section" onclick="prepareReply('<?php echo $commentId; ?>');">Reply</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="comment__text">
                                            <p><?php echo $comment; ?></p>
                                        </div>
                                    </div>
                                </li>

                            <?php

                            } else {
                                //// in case the comment id has replys - show all.
                            ?>

                                <li class="thread-alt depth-1 comment">
                                    <div class="comment__content">
                                        <div class="comment__info">
                                            <input type="hidden" id="comment-author-<?php echo $commentId; ?>" value="<?php echo $commentAuthor; ?>">
                                            <div class="comment__author"><?php echo $commentAuthor; ?></div>
                                            <div class="comment__meta">
                                                <div class="comment__time"><?php echo date("M j, Y", strtotime($commentDate)); ?></div>
                                                <div class="comment__reply">
                                                    <!-- send the function of adding comment -->
                                                    <a class="comment-reply-link" href="#reply-comment-section" onclick="prepareReply('<?php echo $commentId; ?>');">Reply</a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="comment__text">
                                            <p><?php echo $comment; ?></p>
                                        </div>
                                    </div>

                            <?php

                                while ($rowCommentChildren = mysqli_fetch_assoc($queryCheckCommentChildren)) {
                                    ////save all replay comments from database - for the replay comments
                                    $commentIdChild = $rowCommentChildren['n_blog_comment_id'];
                                    $commentAuthorChild = $rowCommentChildren['v_comment_author'];
                                    $commentChild = $rowCommentChildren['v_comment'];
                                    $commentDateChild = $rowCommentChildren['d_date_created'];

                                    echo "<ul class='children'>
                                            <li class='depth-2 comment'>
                                                <div class='comment__content'>
                                                    <div class='comment__info'>
                                                        <div class='comment__author'>" . $commentAuthorChild . "</div>
                                                        <div class='comment__meta'>
                                                            <div class='comment__time'>" . date("M j, Y", strtotime($commentDateChild)) . "</div>
                                                        </div>
                                                    </div>
                                                    <div class='comment__text'>
                                                        <p>" . $commentChild . "</p>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>";
                                }
                            }
                        }

                            ?>

                                </li>
                    </ol>
                    <!-- END commentlist -->

                </div> <!-- end col-full -->
            </div> <!-- end comments -->

            <!-- add-comment form -->
            <div class="row comment-respond">

                <!-- START respond -->
                <div id="respond" class="column">

                    <h3>
                        Add Comment
                        <span>Your email address will not be published.</span>
                    </h3>
                    <!-- msg for user after adding aommnet -->
                    <p style="color:green;display:none;" id="comment-success">Your comment was added successfully.</p>
                    <p style="color:red;display:none;" id="comment-error"></p>

                    <form name="commentForm" id="commentForm">
                        <fieldset>
                            <!-- get the blog-post-id by hidden input -->
                            <input type="hidden" name="replyBlogPostId" id="replyBlogPostId" value="<?php echo $blogPostId; ?>">
                            <!-- fill your name -->
                            <div class="form-field">
                                <input name="cName" id="cName" class="h-full-width h-remove-bottom" placeholder="Your Name" value="" type="text">
                            </div>
                            <!-- fill your email -->
                            <div class="form-field">
                                <input name="cEmail" id="cEmail" class="h-full-width h-remove-bottom" placeholder="Your Email" value="" type="text">
                            </div>

                            <!-- <div class="form-field">
                                <input name="cWebsite" id="cWebsite" class="h-full-width h-remove-bottom" placeholder="Website" value="" type="text">
                            </div> -->

                            <div class="message form-field">
                                <textarea name="cMessage" id="cMessage" class="h-full-width" placeholder="Your Message"></textarea>
                            </div>

                            <br>
                            <input name="submit" id="sumbitCommentForm" class="btn btn--primary btn-wide btn--large h-full-width" value="Add Comment" type="submit">

                        </fieldset>
                    </form> <!-- end form -->

                </div>
                <!-- END respond-->

            </div> <!-- end comment-respond -->

        </div> <!-- end comments-wrap -->


    </section> <!-- end s-content -->


    <!-- footer
    ================================================== -->
    <?php include './footer.php'; ?>



    <!-- Java Script
    ================================================== -->
    <script src="js/jquery-3.5.0.min.js"></script>
    <!-- jQuery latest cdn -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

    <!-- all function in jQuery of replay or add comment -->
    <script>
        // ///main first function
        $(document).ready(function() {
            prepareComment();
        });
        // ////function check input of email.
        function checkEmail(email) {
            var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            if (!regex.test(email)) {
                return false;
            } else {
                return true;
            }
        }
        // ///function of show add-commnet and hide replay-comment
        function prepareComment() {
            $("#comment-success").css("display", "none");
            $("#comment-error").css("display", "none");
            $("#reply-comment-section").hide();
            $("#add-comment-section").show();
        }
        // ////function of deal with the add-comment form
        $(document).on('submit', '#commentForm', function(e) {
            ////prevent defalt
            e.preventDefault();
            ////hide the msg from user
            $("#comment-success").css("display", "none");
            $("#comment-error").css("display", "none");
            //////get val inputs
            var name = $("#cName").val();
            var email = $("#cEmail").val();
            var comment = $("#cMessage").val();
            /////check for any error
            if (!name || !email || !comment) {
                $("#comment-error").css("display", "block");
                $("#comment-error").html("Please fill all fields.");
            } else if (name.length > 50) {
                $("#comment-error").css("display", "block");
                $("#comment-error").html("The name input field can only be a max of 50 characters.");
            } else if (email.length > 50) {
                $("#comment-error").css("display", "block");
                $("#comment-error").html("The email input field can only be a max of 50 characters.");
            } else if (comment.length > 500) {
                $("#comment-error").css("display", "block");
                $("#comment-error").html("The comment input field can only be a max of 500 characters.");
            } else if (checkEmail(email) == false) {
                $("#comment-error").css("display", "block");
                $("#comment-error").html("Please enter a valid email address.");
            } else {
                /////config the add-comment
                var date = new Date();
                var months = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
                /////diffine the date
                var dateFormatted = months[date.getMonth()] + " " + date.getDate() + ", " + date.getFullYear();

                $.ajax({
                    method: "POST",
                    url: "includes/add-comment.php",
                    data: $(this).serialize(),
                    success: function(data) {
                        if (data == "success") {
                            var newComment = "<li class='depth-1 comment><div class='comment__content'><div class='comment__info'><div class='comment__author'>" + name + "</div><div class='comment__meta'><div class='comment__time'>" + dateFormatted + "</div></div></div><div class='comment__text'><p>" + comment + "</p></div></div></li>";
                            $("#comment-success").css("display", "block");
                            $("#commentlist").append(newComment);
                            $("#commentForm").hide();
                        } else {
                            $("#comment-error").css("display", "block");
                            $("#comment-error").html("There was an error while adding your comment. Please try again later.");
                        }
                    }
                });
            }
        });
    </script>

</body>

</html>