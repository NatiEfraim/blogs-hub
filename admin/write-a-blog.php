<?php
include './includes/dbh.php';
require './includes/db-pdo.php';
session_start();
// try connect with pdo - and grab all data from 'blog_category' table
try {
    // Query the database to select all blog-category from the "blog_category" table
    $sqlCategories = $conn->prepare("SELECT * FROM blog_category");
    $sqlCategories->execute();

    // Fetch all category records as an associative array
    $allCategories = $sqlCategories->fetchAll(PDO::FETCH_OBJ);

    if (count($allCategories) === 0) {
        // Handle the case where no admin records were found
        echo "No categories records found.";
    }
} catch (PDOException $e) {
    // Handle database query errors
    die("Connection failed: " . $e->getMessage());
}


?>


<!-- html part - form write a blog. -->
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Free Bootstrap Admin Template : Dream</title>
    <!-- Bootstrap Styles-->
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <!-- FontAwesome Styles-->
    <link href="assets/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom Styles-->
    <link href="assets/css/custom-styles.css" rel="stylesheet" />
    <!-- Google Fonts-->
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css' />
</head>

<body>
    <div id="wrapper">
        <?php include './header.php';
        include './sidebar.php'; ?>
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            Write A Blog
                        </h1>
                    </div>
                </div>
                <!-- Error/Sucsses msg -->
                <?php
                // chaeck if has addblog form url
                // ////All kind of error msg from add-blog.php
                if (isset($_REQUEST['addblog'])) {
                    if ($_REQUEST['addblog'] == "emptytitle") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please add a blog title.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "emptycategory") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please select a blog category.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "emptysummary") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please enter a blog summary.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "emptycontent") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please add blog content.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "emptytags") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please add some blog tags.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "emptypath") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please add a blog path.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "sqlerror") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please try again.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "pathcontainsspaces") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please do not add any spaces in the blog path.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "emptymainimage") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please upload a main image.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "emptyaltimage") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please upload an alternate image.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "mainimageerror") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please upload another main image.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "altimageerror") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please upload another alternate image.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "invalidtypemainimage") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Main Image -> Upload only jpg, jpeg, png, gif, bmp images.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "invalidtypealtimage") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Alt Image -> Upload only jpg, jpeg, png, gif, bmp images.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "erroruploadingmainimage") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Main Image -> There was an error while uploading. Please try again later.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "erroruploadingaltimage") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Alt Image -> There was an error while uploading. Please try again later.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "titlebeingused") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> The title is being used in another blog. Try picking a different title.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "pathbeingused") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> The blog path is being used in another blog. Try picking a different blog path.
                        </div>";
                    } else if ($_REQUEST['addblog'] == "homepageplacementerror") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> An unexpected error occurred while trying to set the home page placement. Please try again.
                        </div>";
                    }
                }

                ?>
                <!-- /. ROW  -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Write A Blog
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <!-- form write a blog -->
                                        <form role="form" method="POST" class="" action="./includes/add-blog.php" enctype="multipart/form-data" onsubmit="return validateImage();">
                                            <!-- Title -->
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input class="form-control" name="blog-title" value="<?php if (isset($_SESSION['blogTitle'])) {
                                                                                                            echo $_SESSION['blogTitle'];
                                                                                                        } ?>">
                                            </div>
                                            <!-- meta Title -->
                                            <div class="form-group">
                                                <label>meta Title</label>
                                                <input class="form-control" name="blog-meta-title" value="<?php if (isset($_SESSION['blogMetaTitle'])) {
                                                                                                                echo $_SESSION['blogMetaTitle'];
                                                                                                            } ?>">
                                            </div>

                                            <!-- select category -->
                                            <div class="form-group">
                                                <label>Selects Blog category</label>
                                                <select class="form-control" name="blog-category">
                                                    <!-- fetch category from 'blog_category' table -->
                                                    <option>Select category-blog</option>
                                                    <?php

                                                    foreach ($allCategories as $category) :
                                                    ?>
                                                        <option value="<?php echo  $category->n_category_id; ?>"><?php echo  $category->v_category_title; ?></option>

                                                    <?php endforeach; ?>
                                                </select>
                                            </div>

                                            <!-- upload imag -->
                                            <div class="form-group">
                                                <label>Main Image</label>
                                                <input type="file" name="main-blog-image" id="main-blog-image">
                                            </div>

                                            <!-- Alternte image -->
                                            <div class="form-group">
                                                <label>Alternate Image</label>
                                                <input type="file" name="alt-blog-image" id="alt-blog-image">
                                            </div>
                                            <!-- imputs summary text -->
                                            <div class="form-group">
                                                <label>Summery</label>
                                                <textarea class="form-control" rows="3" name="blog-summary">
                                                <?php if (isset($_SESSION['blogTags'])) {
                                                    echo $_SESSION['blogSummary'];
                                                } ?>
                                                </textarea>
                                            </div>
                                            <!-- blog content field -->
                                            <div class="form-group">
                                                <label>Blog content</label>
                                                <textarea class="form-control" rows="3" name="blog-content">
                                                <?php if (isset($_SESSION['blogContent'])) {
                                                    echo $_SESSION['blogContent'];
                                                } ?>
                                                </textarea>
                                            </div>
                                            <!-- blog tag field -->
                                            <div class="form-group">
                                                <label>Blog Tag (saperate by comma)</label>
                                                <input class="form-control" name="blog-tags" value="<?php if (isset($_SESSION['blogTags'])) {
                                                                                                        echo $_SESSION['blogTags'];
                                                                                                    } ?>">
                                                <!-- <p class="help-block">Example block-level help text here.</p> -->
                                            </div>
                                            <!-- blog path -->
                                            <div class="form-group">
                                                <label>Blog path</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">www.blog-hub/</span>
                                                    <input type="text" class="form-control" name="blog-path" value="<?php if (isset($_SESSION['blogPath'])) {
                                                                                                                        echo $_SESSION['blogPath'];
                                                                                                                    } ?>" placeholder="Enter your blog path">
                                                </div>
                                            </div>
                                            <!-- choose radio btn -->
                                            <div class="form-group">
                                                <label>Home page placemment</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement" id="optionsRadiosInline1" value="1" <?php if (isset($_SESSION['blogHomePagePlacement'])) {
                                                                                                                                                if ($_SESSION['blogHomePagePlacement'] == 1) {
                                                                                                                                                    echo "checked=''";
                                                                                                                                                }
                                                                                                                                            } ?>>1
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement" id="optionsRadiosInline2" value="2" <?php if (isset($_SESSION['blogHomePagePlacement'])) {
                                                                                                                                                if ($_SESSION['blogHomePagePlacement'] == 2) {
                                                                                                                                                    echo "checked=''";
                                                                                                                                                }
                                                                                                                                            } ?>>2
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement" id="optionsRadiosInline3" value="3" <?php if (isset($_SESSION['blogHomePagePlacement'])) {
                                                                                                                                                if ($_SESSION['blogHomePagePlacement'] == 3) {
                                                                                                                                                    echo "checked=''";
                                                                                                                                                }
                                                                                                                                            } ?>>3
                                                </label>
                                            </div>



                                            <button type="submit" name="submit-blog" class="btn btn-default">Add Blog</button>
                                            <!-- <button type="reset" class="btn btn-default">Reset Button</button> -->
                                        </form>
                                    </div>
                                </div>
                                <!-- /.row (nested) -->



                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                    </div>
                    <!-- /.col-lg-12 -->
                </div>

                <?php include './footer.php'; ?>
                <!-- <footer>
                    <p>All right reserved. Template by: <a href="http://webthemez.com">WebThemez</a></p>
                </footer> -->
            </div>
            <!-- /. PAGE INNER  -->
        </div>
        <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->
    <!-- JS Scripts-->
    <!-- jQuery Js -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- jQuery latest cdn -->
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>

    <!-- Bootstrap Js -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- Custom Js -->
    <script src="assets/js/custom-scripts.js"></script>
    <!-- function in jQuery - for images uploaed -->
    <script>
        function validateImage() {
            // get the value for main and alt image
            var main_img = $("#main-blog-image").val();
            var alt_img = $("#alt-blog-image").val();
            // ///diffine for alow ext
            var exts = ['jpg', 'jpeg', 'png', 'gif', 'bmp'];

            var get_ext_main_img = main_img.split('.');
            var get_ext_alt_img = alt_img.split('.');

            get_ext_main_img = get_ext_main_img.reverse();
            get_ext_alt_img = get_ext_alt_img.reverse();

            main_image_check = false;
            alt_image_check = false;
            // /////chaeck the main image
            if (main_img.length > 0) {
                if ($.inArray(get_ext_main_img[0].toLowerCase(), exts) >= -1) {
                    main_image_check = true;
                } else {
                    // ///send error msg
                    alert("Error -> Main Image. Upload only jpg, jpeg, png, gif, bmp images.");
                    main_img_check = false;
                }
            } else {
                // ////main image not upload
                alert("Please upload a main image.");
                main_img_check = false;
            }
            // ////check alt image
            if (alt_img.length > 0) {
                if ($.inArray(get_ext_alt_img[0].toLowerCase(), exts) >= -1) {
                    alt_image_check = true;
                } else {
                    alert("Error -> Alternate Image. Upload only jpg, jpeg, png, gif, bmp images.");
                    alt_image_check = false;
                }
            } else {
                // //////alt image not upload
                alert("Please upload a alternate image.");
                alt_image_check = false;
            }
            // ////return boll function
            if (main_image_check == true && alt_image_check == true) {
                return true;
            } else {
                return false;
            }

        }
    </script>

</body>

</html>