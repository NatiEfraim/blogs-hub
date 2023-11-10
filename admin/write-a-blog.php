<?php
include './includes/dbh.php';
require './includes/db-pdo.php';
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
                                                <input class="form-control" name="blog-title">
                                            </div>
                                            <!-- meta Title -->
                                            <div class="form-group">
                                                <label>meta Title</label>
                                                <input class="form-control" name="blog-meta-title">
                                            </div>

                                            <!-- select category -->
                                            <div class="form-group">
                                                <label>Selects Blog category</label>
                                                <select class="form-control" name="blog-category">
                                                    <!-- fetch category from 'blog_category' table -->
                                                    <option value="">Select category-blog</option>
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
                                                <textarea class="form-control" rows="3" name="blog-summary"></textarea>
                                            </div>
                                            <!-- blog content field -->
                                            <div class="form-group">
                                                <label>Blog content</label>
                                                <textarea class="form-control" rows="3" name="blog-content"></textarea>
                                            </div>
                                            <!-- blog tag field -->
                                            <div class="form-group">
                                                <label>Blog Tag (saperate by comma)</label>
                                                <input class="form-control" name="blog-tags">
                                                <!-- <p class="help-block">Example block-level help text here.</p> -->
                                            </div>
                                            <!-- blog path -->
                                            <div class="form-group">
                                                <label>Blog path</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">www.blog-hub/</span>
                                                    <input type="text" class="form-control" name="blog-path" placeholder="Enter your blog path">
                                                </div>
                                            </div>
                                            <!-- choose radio btn -->
                                            <div class="form-group">
                                                <label>Home page placemment</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement" id="optionsRadiosInline1" value="option1" checked="">1
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement" id="optionsRadiosInline2" value="option2">2
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement" id="optionsRadiosInline3" value="option3">3
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