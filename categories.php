<?php
require './admin/includes/db-pdo.php';
require "./admin/includes/dbh.php";
// ///check the url - if has group
if (isset($_REQUEST['group'])) {
    /////get category path from the url.
    $categoryPath = $_REQUEST['group'];
    ////diffine the sql query
    $sqlGetCategory = "SELECT * FROM blog_category WHERE v_category_path = '$categoryPath'";
    $queryGetCategory = mysqli_query($conn, $sqlGetCategory);
    //////try to run the query
    if ($rowGetCategory = mysqli_fetch_assoc($queryGetCategory)) {
        ///////grab all data has needed
        $categoryId = $rowGetCategory['n_category_id'];
        $categoryTitle = $rowGetCategory['v_category_title'];
        $categoryMetaTitle = $rowGetCategory['v_category_meta_title'];
    } else {
        // ///redirect index.php
        header("Location: ./index.php");
        exit();
    }
?>
    <!-- html part -->
    <!DOCTYPE html>
    <html class="no-js" lang="en">

    <head>

        <!--- basic page needs
    ================================================== -->
        <meta charset="utf-8">
        <title>Blogs-Hub | <?php echo $categoryMetaTitle; ?></title>
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


            <!-- page header
        ================================================== -->
            <div class="s-pageheader">
                <div class="row">
                    <div class="column large-12">
                        <h1 class="page-title">
                            <span class="page-title__small-type">Category</span>
                            <?php echo $categoryTitle; ?>
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
                        <!-- while loop for all blogs by the related category. -->
                        <?php
                        // ///diffine the sql query get all blog_post table by match category_id
                        $sqlGetAllBlogs = "SELECT * FROM blog_post WHERE n_category_id = '$categoryId' AND f_post_status = '1' ORDER BY n_blog_post_id DESC";
                        $queryGetAllBlogs = mysqli_query($conn, $sqlGetAllBlogs);
                        /////loop all results
                        while ($rowGetAllBlogs = mysqli_fetch_assoc($queryGetAllBlogs)) {
                            // /////save the data.
                            $blogTitle = $rowGetAllBlogs['v_post_title'];
                            $blogPath = $rowGetAllBlogs['v_post_path'];
                            $blogSummary = $rowGetAllBlogs['v_post_summary'];
                            $blogAltImageUrl = $rowGetAllBlogs['v_alt_image_url'];
                            $blogAuthorName = $rowGetAllBlogs['v_author_name'];

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
                                            <span class="byline">By:
                                                <span class='author'>
                                                    <a href="#"><?php echo $blogAuthorName; ?></a>
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
<?php
} else {
    // ///redirect back index.php
    header("Location: ./index.php");
}
?>