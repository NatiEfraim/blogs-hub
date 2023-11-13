<?php require './admin/includes/dbh.php'; ?>
<!-- get the data from the first place in index.php -->
<?php
// ////diffine the first select join between the blog_post table and the blog_category table
$sqlGetFirstBlog = "SELECT * FROM blog_post INNER JOIN blog_category ON blog_post.n_category_id = blog_category.n_category_id WHERE n_home_page_placement = '1' AND f_post_status != '2' LIMIT 1";
$queryGetFirstBlog = mysqli_query($conn, $sqlGetFirstBlog);
// ///try run the query
if ($rowGetFirstBlog = mysqli_fetch_assoc($queryGetFirstBlog)) {
    /////get all data from the database.
    $firstBlogCategory = $rowGetFirstBlog['v_category_title'];
    $firstBlogCategoryPath = $rowGetFirstBlog['v_category_path'];
    $firstBlogTitle = $rowGetFirstBlog['v_post_title'];
    $firstBlogPath = $rowGetFirstBlog['v_post_path'];
    $firstBlogMainImageUrl = $rowGetFirstBlog['v_main_image_url'];
    // ///name and img author
    $firstBlogAuthorImageUrl = $rowGetFirstBlog['v_author_image_url'];
    $firstBlogAuthorName = $rowGetFirstBlog['v_author_name'];
}

// ////diffine the second select join between the blog_post table and the blog_category table
$sqlGetSecondBlog = "SELECT * FROM blog_post INNER JOIN blog_category ON blog_post.n_category_id = blog_category.n_category_id WHERE n_home_page_placement = '2' AND f_post_status != '2' LIMIT 1";
$queryGetSecondBlog = mysqli_query($conn, $sqlGetSecondBlog);
// ///try run the query
if ($rowGetSecondBlog = mysqli_fetch_assoc($queryGetSecondBlog)) {
    ////get all inputs from database.
    $secondBlogCategory = $rowGetSecondBlog['v_category_title'];
    $secondBlogCategoryPath = $rowGetSecondBlog['v_category_path'];
    $secondBlogTitle = $rowGetSecondBlog['v_post_title'];
    $secondBlogPath = $rowGetSecondBlog['v_post_path'];
    $secondBlogMainImageUrl = $rowGetSecondBlog['v_main_image_url'];
    // ///name and img author
    $secondBlogAuthorImageUrl = $rowGetSecondBlog['v_author_image_url'];
    $secondBlogAuthorName = $rowGetSecondBlog['v_author_name'];
}


// ////diffine the third select join between the blog_post table and the blog_category table
$sqlGetThirdBlog = "SELECT * FROM blog_post INNER JOIN blog_category ON blog_post.n_category_id = blog_category.n_category_id WHERE n_home_page_placement = '3' AND f_post_status != '2' LIMIT 1";
$queryGetThirdBlog = mysqli_query($conn, $sqlGetThirdBlog);
// ///try run the query
if ($rowGetThirdBlog = mysqli_fetch_assoc($queryGetThirdBlog)) {
    ////get all inputs from database.
    $thirdBlogCategory = $rowGetThirdBlog['v_category_title'];
    $thirdBlogCategoryPath = $rowGetThirdBlog['v_category_path'];
    $thirdBlogTitle = $rowGetThirdBlog['v_post_title'];
    $thirdBlogPath = $rowGetThirdBlog['v_post_path'];
    $thirdBlogMainImageUrl = $rowGetThirdBlog['v_main_image_url'];
    // ///name and img author
    $thirdBlogAuthorImageUrl = $rowGetThirdBlog['v_author_image_url'];
    $thirdBlogAuthorName = $rowGetThirdBlog['v_author_name'];
}

//////////////get all blog_post table where status=1 --> active blog.
$sqlGetAllBlogs = "SELECT * FROM blog_post WHERE f_post_status = '1' ORDER BY n_blog_post_id DESC";
$queryGetAllBlogs = mysqli_query($conn, $sqlGetAllBlogs);

?>

<!-- html part index.php -->
<!DOCTYPE html>
<html class="no-js" lang="en">

<head>

    <!--- basic page needs
    ================================================== -->
    <meta charset="utf-8">
    <title>Blogs-Hub</title>
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
    <!-- end s-header -->
    <?php include './header.php'; ?>
    <?php require './admin/includes/dbh.php'; ?>
    <!-- hero
    ================================================== -->
    <section id="hero" class="s-hero">

        <div class="s-hero__slider">
            <?php


            ?>
            <!-- first homepageplacment -->
            <div class="s-hero__slide">

                <div class="s-hero__slide-bg" style="background-image: url('<?php echo $firstBlogMainImageUrl; ?>');"></div>

                <div class="row s-hero__slide-content animate-this">
                    <div class="column">
                        <div class="s-hero__slide-meta">
                            <span class="cat-links">
                                <a href="./categories.php?group=<?php echo $firstBlogCategoryPath; ?>"><?php echo $firstBlogCategory; ?></a>
                                <!-- <a href="#0">Design</a> -->
                            </span>
                            <span class="byline">
                                Posted by
                                <span class="author">
                                    <a href="#"><?php echo $firstBlogAuthorName; ?></a>
                                </span>
                            </span>
                        </div>
                        <h1 class="s-hero__slide-text">
                            <a href="./single-blog.php?blog=<?php echo $firstBlogPath; ?>">
                                <?php echo $firstBlogTitle; ?>
                            </a>
                        </h1>
                    </div>
                </div>

            </div>
            <!-- end s-hero__slide -->

            <!-- second homepageplacment -->
            <div class="s-hero__slide">

                <div class="s-hero__slide-bg" style="background-image: url('<?php echo $secondBlogMainImageUrl; ?>');"></div>

                <div class="row s-hero__slide-content animate-this">
                    <div class="column">
                        <div class="s-hero__slide-meta">
                            <span class="cat-links">
                                <a href="./categories.php?group=<?php echo $secondBlogCategoryPath; ?>"><?php echo $secondBlogCategory; ?></a>
                            </span>
                            <span class="byline">
                                Posted by
                                <span class="author">
                                    <a href="#"><?php echo $secondBlogAuthorName; ?></a>
                                </span>
                            </span>
                        </div>
                        <h1 class="s-hero__slide-text">
                            <a href="./single-blog.php?blog=<?php echo $secondBlogPath; ?>">
                                <?php echo $secondBlogTitle; ?>
                            </a>
                        </h1>
                    </div>
                </div>

            </div>
            <!-- end s-hero__slide -->


            <!-- third homepageplacment -->
            <div class="s-hero__slide">

                <div class=" s-hero__slide-bg" style="background-image: url('<?php echo $thirdBlogMainImageUrl; ?>');"></div>

                <div class="row s-hero__slide-content animate-this">
                    <div class="column">
                        <div class="s-hero__slide-meta">
                            <span class="cat-links">
                                <a href="./categories.php?group=<?php echo $thirdBlogCategoryPath;  ?>"><?php echo $thirdBlogCategory; ?></a>
                                <!-- <a href="#0">Lifestyle</a> -->
                            </span>
                            <span class="byline">
                                Posted by
                                <span class="author">
                                    <a href="#"><?php echo $thirdBlogAuthorName; ?></a>
                                </span>
                            </span>
                        </div>
                        <h1 class="s-hero__slide-text">
                            <a href="./single-blog.php?blog=<?php echo $thirdBlogPath; ?>">
                                <?php echo $thirdBlogTitle; ?>
                            </a>
                        </h1>
                    </div>
                </div>

            </div>
            <!-- end s-hero__slide -->

        </div>
        <!-- end s-hero__slider -->

        <div class="s-hero__social hide-on-mobile-small">
            <p>Follow</p>
            <span></span>
            <ul class="s-hero__social-icons">
                <li><a href="https://www.facebook.com/"><i class="fab fa-facebook-f" aria-hidden="true"></i></a></li>
                <li><a href="https://twitter.com/><i class=" fab fa-twitter" aria-hidden="true"></i></a></li>
                <li><a href="https://www.instagram.com/"><i class="fab fa-instagram" aria-hidden="true"></i></a></li>
                <li><a href="https://www.youtube.com/"><i class="fab fa-youtube" aria-hidden="true"></i></a></li>
            </ul>
        </div> <!-- end s-hero__social -->

        <div class="nav-arrows s-hero__nav-arrows">
            <button class="s-hero__arrow-prev">
                <svg viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg" width="15" height="15">
                    <path d="M1.5 7.5l4-4m-4 4l4 4m-4-4H14" stroke="currentColor"></path>
                </svg>
            </button>
            <button class="s-hero__arrow-next">
                <svg viewBox="0 0 15 15" xmlns="http://www.w3.org/2000/svg" width="15" height="15">
                    <path d="M13.5 7.5l-4-4m4 4l-4 4m4-4H1" stroke="currentColor"></path>
                </svg>
            </button>
        </div> <!-- end s-hero__arrows -->

    </section> <!-- end s-hero -->


    <!-- content
    ================================================== -->
    <section class="s-content s-content--no-top-padding">


        <!-- masonry
        ================================================== -->
        <div class="s-bricks">

            <div class="masonry">
                <div class="bricks-wrapper h-group">

                    <div class="grid-sizer"></div>

                    <div class="lines">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                    <!-- loop while and present all blog_post that activ -->
                    <?php
                    while ($rowGetAllBlogs = mysqli_fetch_assoc($queryGetAllBlogs)) {
                        //////get all data.
                        $blogTitle = $rowGetAllBlogs['v_post_title'];
                        $blogPath = $rowGetAllBlogs['v_post_path'];
                        $blogSummary = $rowGetAllBlogs['v_post_summary'];
                        $blogAltImageUrl = $rowGetAllBlogs['v_alt_image_url'];
                        $blogAuthorImageUrl = $rowGetAllBlogs['v_author_image_url'];
                        $blogAuthorName = $rowGetAllBlogs['v_author_name'];

                    ?>

                        <article class="brick entry" data-aos="fade-up">

                            <div class="entry__thumb">
                                <a href="./single-blog.php?blog=<?php echo $blogPath;  ?>" class="thumb-link">
                                    <img src="<?php echo $blogAltImageUrl; ?>" srcset="<?php echo $blogAltImageUrl; ?> 1x, <?php echo $blogAltImageUrl; ?> 2x" alt="">
                                </a>
                            </div> <!-- end entry__thumb -->

                            <div class="entry__text">
                                <div class="entry__header">
                                    <h1 class="entry__title"><a href="./single-blog.php?blog=<?php echo $blogPath;  ?>"><?php echo $blogTitle;  ?></a></h1>

                                    <div class="entry__meta">
                                        <span class="byline">By:
                                            <span class='author'>
                                                <a href="#"><?php echo $blogAuthorName; ?></a>
                                            </span>
                                        </span>

                                    </div>
                                </div>
                                <div class="entry__excerpt">
                                    <p>
                                        <?php echo $blogSummary; ?>
                                    </p>
                                </div>
                                <a class="entry__more-link" href="./single-blog.php?blog=<?php echo $blogPath;  ?>">Read Blog</a>
                            </div> <!-- end entry__text -->

                        </article>
                        <!-- end loop while of blogPost -->
                    <?php } ?>




                </div> <!-- end brick-wrapper -->

            </div> <!-- end masonry -->

            <div class="row">
                <div class="column large-12">
                    <nav class="pgn">
                        <ul>
                            <li>
                                <span class="pgn__prev" href="#0">
                                    Prev
                                </span>
                            </li>
                            <li><a class="pgn__num" href="#0">1</a></li>
                            <li><span class="pgn__num current">2</span></li>
                            <li><a class="pgn__num" href="#0">3</a></li>
                            <li><a class="pgn__num" href="#0">4</a></li>
                            <li><a class="pgn__num" href="#0">5</a></li>
                            <li><span class="pgn__num dots">…</span></li>
                            <li><a class="pgn__num" href="#0">8</a></li>
                            <li>
                                <span class="pgn__next" href="#0">
                                    Next
                                </span>
                            </li>
                        </ul>
                    </nav> <!-- end pgn -->
                </div> <!-- end column -->
            </div> <!-- end row -->

        </div> <!-- end s-bricks -->

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