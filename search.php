<?php
require "admin/includes/dbh.php";
// ///check the btn search query has been preesed
if (isset($_REQUEST['query'])) {
    /////grab from the url the query search
    $searchQuery = $_REQUEST['query'];
    //////diffine the sql query in join between  'blog_tags' table and the 'blog_post' table
    $sqlGetSearchResults = "SELECT * FROM blog_tags INNER JOIN blog_post ON blog_tags.n_blog_post_id = blog_post.n_blog_post_id WHERE blog_tags.v_tag LIKE '%" . $searchQuery . "%' OR blog_post.v_post_title LIKE '%" . $searchQuery . "%'";
    $queryGetSearchResults = mysqli_query($conn, $sqlGetSearchResults);

?>
<!-- html part -->
    <!DOCTYPE html>
    <html class="no-js" lang="en">

    <head>

        <!--- basic page needs
    ================================================== -->
        <meta charset="utf-8">
        <title>Blogs-Hub | Search</title>
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

        <?php include "header-opaque.php"; ?>

        <!-- content
    ================================================== -->
        <section class="s-content">


            <!-- page header
        ================================================== -->
            <div class="s-pageheader">
                <div class="row">
                    <div class="column large-12">
                        <h1 class="page-title">
                            <span class="page-title__small-type">Search Results For</span>
                            <?php echo $searchQuery; ?>
                        </h1>
                    </div>
                </div>
            </div> <!-- end s-pageheader-->


            <!-- masonry
        ================================================== -->
            <div class="s-bricks s-bricks--half-top-padding">

                <div class="masonry">
                    <div class="bricks-wrapper h-group">

                        <div class="grid-sizer"></div>

                        <div class="lines">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <!-- loop all results -->
                        <?php

                        while ($rowGetSearchResults = mysqli_fetch_assoc($queryGetSearchResults)) {

                            $blogTitle = $rowGetSearchResults['v_post_title'];
                            $blogPath = $rowGetSearchResults['v_post_path'];
                            $blogSummary = $rowGetSearchResults['v_post_summary'];
                            $blogAltImageUrl = $rowGetSearchResults['v_alt_image_url'];

                        ?>

                            <article class="brick entry" data-aos="fade-up">

                                <div class="entry__thumb">
                                    <a href="single-blog.php?blog=<?php echo $blogPath; ?>" class="thumb-link">
                                        <img src="<?php echo $blogAltImageUrl; ?>" srcset="<?php echo $blogAltImageUrl; ?> 1x, <?php echo $blogAltImageUrl; ?> 2x" alt="">
                                    </a>
                                </div> <!-- end entry__thumb -->

                                <div class="entry__text">
                                    <div class="entry__header">
                                        <h1 class="entry__title"><a href="single-blog.php?blog=<?php echo $blogPath; ?>"><?php echo $blogTitle; ?></a></h1>

                                        <div class="entry__meta">
                                            <span class="byline"">By:
                                        <span class='author'>
                                            <a href=" #">Parth Modi</a>
                                            </span>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="entry__excerpt">
                                        <p><?php echo $blogSummary; ?></p>
                                    </div>
                                    <a class="entry__more-link" href="single-blog.php?blog=<?php echo $blogPath; ?>">Read Blog</a>
                                </div> <!-- end entry__text -->

                            </article> <!-- end article -->

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

        <?php include "footer.php"; ?>

        <!-- Java Script
    ================================================== -->
        <script src="js/jquery-3.5.0.min.js"></script>
        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

    </body>

    </html>

<?php
// not press on the search-btn query
} else {
    header("Location: index.php");
    exit();
}

?>