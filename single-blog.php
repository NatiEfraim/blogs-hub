<?php
require './admin/includes/db-pdo.php';
require './admin/includes/dbh.php';
// ///check the url if has blog-path
if (isset($_REQUEST['blog'])) {
    ///grab the blogPath
    $blogPath = $_REQUEST['blog'];
    /////diffine the sql query
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
                                    <img class="avatar" src="images/avatars/user-06.jpg" alt="">
                                </div>
                                <div class="byline">
                                    <span class="bytext">Posted By</span>
                                    <a href="#">Prth Modi</a>
                                </div>
                            </div>

                            <div class="meta-bottom">

                                <div class="entry-cat-links meta-blk">
                                    <div class="cat-links">
                                        <span>In</span>
                                        <a href="./categories.php?group=<?php echo $blogCategoryPath; ?>"><?php echo $categoryTitle; ?></a>
                                        <!-- <a href="#0">Design</a>
                                        <a href="#0">Work</a> -->
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
                                    <!-- <a href="#">orci</a>
                                    <a href="#">lectus</a>
                                    <a href="#">varius</a>
                                    <a href="#">turpis</a> -->
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

                            <!-- <div class="prev-nav">
                                <a href="#" rel="prev">
                                    <span>Previous</span>
                                    Tips on Minimalist Design
                                </a>
                            </div>
                            <div class="next-nav">
                                <a href="#" rel="next">
                                    <span>Next</span>
                                    A Practical Guide to a Minimalist Lifestyle.
                                </a>
                            </div> -->
                        </div>
                        <!-- end s-content__pagenav -->

                    </div> <!-- end s-content__primary -->
                </article> <!-- end entry -->

            </div> <!-- end column -->
        </div> <!-- end row -->


        <!-- comments
        ================================================== -->
        <div class="comments-wrap">

            <div id="comments" class="row">
                <div class="column large-12">

                    <h3>5 Comments</h3>

                    <!-- START commentlist -->
                    <ol class="commentlist">

                        <li class="depth-1 comment">

                            <div class="comment__avatar">
                                <img class="avatar" src="images/avatars/user-01.jpg" alt="" width="50" height="50">
                            </div>

                            <div class="comment__content">

                                <div class="comment__info">
                                    <div class="comment__author">Itachi Uchiha</div>

                                    <div class="comment__meta">
                                        <div class="comment__time">Oct 05, 2020</div>
                                        <div class="comment__reply">
                                            <a class="comment-reply-link" href="#0">Reply</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="comment__text">
                                    <p>Adhuc quaerendum est ne, vis ut harum tantas noluisse, id suas iisque mei. Nec te inani ponderum vulputate,
                                        facilisi expetenda has et. Iudico dictas scriptorem an vim, ei alia mentitum est, ne has voluptua praesent.</p>
                                </div>

                            </div>

                        </li> <!-- end comment level 1 -->

                        <li class="thread-alt depth-1 comment">

                            <div class="comment__avatar">
                                <img class="avatar" src="images/avatars/user-04.jpg" alt="" width="50" height="50">
                            </div>

                            <div class="comment__content">

                                <div class="comment__info">
                                    <div class="comment__author">John Doe</div>

                                    <div class="comment__meta">
                                        <div class="comment__time">Oct 05, 2020</div>
                                        <div class="comment__reply">
                                            <a class="comment-reply-link" href="#0">Reply</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="comment__text">
                                    <p>Sumo euismod dissentiunt ne sit, ad eos iudico qualisque adversarium, tota falli et mei. Esse euismod
                                        urbanitas ut sed, et duo scaevola pericula splendide. Primis veritus contentiones nec ad, nec et
                                        tantas semper delicatissimi.</p>
                                </div>

                            </div>

                            <ul class="children">

                                <li class="depth-2 comment">

                                    <div class="comment__avatar">
                                        <img class="avatar" src="images/avatars/user-03.jpg" alt="" width="50" height="50">
                                    </div>

                                    <div class="comment__content">

                                        <div class="comment__info">
                                            <div class="comment__author">Kakashi Hatake</div>

                                            <div class="comment__meta">
                                                <div class="comment__time">Oct 05, 2020</div>
                                                <div class="comment__reply">
                                                    <a class="comment-reply-link" href="#0">Reply</a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="comment__text">
                                            <p>Duis sed odio sit amet nibh vulputate
                                                cursus a sit amet mauris. Morbi accumsan ipsum velit. Duis sed odio sit amet nibh vulputate
                                                cursus a sit amet mauris</p>
                                        </div>

                                    </div>

                                    <ul class="children">

                                        <li class="depth-3 comment">

                                            <div class="comment__avatar">
                                                <img class="avatar" src="images/avatars/user-04.jpg" alt="" width="50" height="50">
                                            </div>

                                            <div class="comment__content">

                                                <div class="comment__info">
                                                    <div class="comment__author">John Doe</div>

                                                    <div class="comment__meta">
                                                        <div class="comment__time">Oct 04, 2020</div>
                                                        <div class="comment__reply">
                                                            <a class="comment-reply-link" href="#0">Reply</a>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="comment__text">
                                                    <p>Investigationes demonstraverunt lectores legere me lius quod ii legunt saepius. Claritas est
                                                        etiam processus dynamicus, qui sequitur mutationem consuetudium lectorum.</p>
                                                </div>

                                            </div>

                                        </li>

                                    </ul>

                                </li>

                            </ul>

                        </li> <!-- end comment level 1 -->

                        <li class="depth-1 comment">

                            <div class="comment__avatar">
                                <img class="avatar" src="images/avatars/user-02.jpg" alt="" width="50" height="50">
                            </div>

                            <div class="comment__content">

                                <div class="comment__info">
                                    <div class="comment__author">Shikamaru Nara</div>

                                    <div class="comment__meta">
                                        <div class="comment__time">Oct 03, 2020</div>
                                        <div class="comment__reply">
                                            <a class="comment-reply-link" href="#0">Reply</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="comment__text">
                                    <p>Typi non habent claritatem insitam; est usus legentis in iis qui facit eorum claritatem.</p>
                                </div>

                            </div>

                        </li> <!-- end comment level 1 -->

                    </ol>
                    <!-- END commentlist -->

                </div> <!-- end col-full -->
            </div> <!-- end comments -->


            <div class="row comment-respond">

                <!-- START respond -->
                <div id="respond" class="column">

                    <h3>
                        Add Comment
                        <span>Your email address will not be published.</span>
                    </h3>

                    <form name="contactForm" id="contactForm" method="post" action="" autocomplete="off">
                        <fieldset>

                            <div class="form-field">
                                <input name="cName" id="cName" class="h-full-width h-remove-bottom" placeholder="Your Name" value="" type="text">
                            </div>

                            <div class="form-field">
                                <input name="cEmail" id="cEmail" class="h-full-width h-remove-bottom" placeholder="Your Email" value="" type="text">
                            </div>

                            <div class="form-field">
                                <input name="cWebsite" id="cWebsite" class="h-full-width h-remove-bottom" placeholder="Website" value="" type="text">
                            </div>

                            <div class="message form-field">
                                <textarea name="cMessage" id="cMessage" class="h-full-width" placeholder="Your Message"></textarea>
                            </div>

                            <br>
                            <input name="submit" id="submit" class="btn btn--primary btn-wide btn--large h-full-width" value="Add Comment" type="submit">

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
    <script src="js/plugins.js"></script>
    <script src="js/main.js"></script>

</body>

</html>