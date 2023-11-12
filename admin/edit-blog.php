<?php
include './includes/dbh.php';

session_start(); ///start session.
// ////check the url.
if (isset($_REQUEST['blogid'])) {
    // ///case the id is emty.
    $blogId = $_REQUEST['blogid'];

    if (empty($blogId)) {
        header("Location: blogs.php");
        exit();
    }

    // ////save the blogId in session for editblog.
    $_SESSION['editBlogId'] = $_REQUEST['blogid'];
    // //////sql query - get all data from 'blog_post' table
    $sqlGetBlogDetails = "SELECT * FROM blog_post WHERE n_blog_post_id = '$blogId'";
    $queryGetBlogDetails = mysqli_query($conn, $sqlGetBlogDetails);
    // ///////try to fetch all data.
    if ($rowGetBlogDetails = mysqli_fetch_assoc($queryGetBlogDetails)) {
        // ////////save all in session - for editBlog.
        $_SESSION['editTitle'] = $rowGetBlogDetails['v_post_title'];
        $_SESSION['editMetaTitle'] = $rowGetBlogDetails['v_post_meta_title'];
        $_SESSION['editCategoryId'] = $rowGetBlogDetails['n_category_id'];
        $_SESSION['editSummary'] = $rowGetBlogDetails['v_post_summary'];
        $_SESSION['editContent'] = $rowGetBlogDetails['v_post_content'];
        $_SESSION['editPath'] = $rowGetBlogDetails['v_post_path'];
        $_SESSION['editHomePagePlacement'] = $rowGetBlogDetails['n_home_page_placement'];
    } else {
        // ////in case the futch went wrong.
        header("Location: blogs.php");
        exit();
    }
    // ////sql query get  data from the 'blog_tags' table by blogId.
    $sqlGetBlogTags = "SELECT * FROM blog_tags WHERE n_blog_post_id = '$blogId'";
    $queryGetBlogTags = mysqli_query($conn, $sqlGetBlogTags);
    if ($rowGetBlogTags = mysqli_fetch_assoc($queryGetBlogTags)) {
        // ////save the v_tags in session for editTags.
        $_SESSION['editTags'] = $rowGetBlogTags['v_tag'];
    }
} else if (isset($_SESSION['editBlogId'])) {
    # code...
} else {
    // ///redirrect to blogs.php
    header("Location: ./blogs.php");
    exit();
}
if (isset($_SESSION['editBlogId'])) {
    // /////get the 2 colums of img from the 'blog_post' table by editBlogId from session. 
    $sqlGetImages = "SELECT * FROM blog_post WHERE n_blog_post_id = '" . $_SESSION['editBlogId'] . "'";
    $queryGetImages = mysqli_query($conn, $sqlGetImages);
    if ($rowGetImages = mysqli_fetch_assoc($queryGetImages)) {
        $mainImgUrl = $rowGetImages['v_main_image_url'];
        $altImgUrl = $rowGetImages['v_alt_image_url'];
    }
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
    <!-- Summernote Fonts-->
    <link href='./summernote/summernote-lite.min.css' rel='stylesheet' type='text/css' />
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
                            Edit Blog post.
                        </h1>
                    </div>
                </div>
                <!-- Error msg -->
                <?php
                // chaeck if has updateblog form url
                // ////All kind of error msg from add-blog.php
                if (isset($_REQUEST['updateblog'])) {
                    if ($_REQUEST['updateblog'] == "emptytitle") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please add a blog title.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "emptycategory") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please select a blog category.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "emptysummary") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please enter a blog summary.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "emptycontent") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please add blog content.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "emptytags") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please add some blog tags.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "emptypath") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please add a blog path.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "sqlerror") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please try again.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "pathcontainsspaces") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please do not add any spaces in the blog path.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "emptymainimage") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please upload a main image.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "emptyaltimage") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please upload an alternate image.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "mainimageerror") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please upload another main image.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "altimageerror") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Please upload another alternate image.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "invalidtypemainimage") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Main Image -> Upload only jpg, jpeg, png, gif, bmp images.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "invalidtypealtimage") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Alt Image -> Upload only jpg, jpeg, png, gif, bmp images.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "erroruploadingmainimage") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Main Image -> There was an error while uploading. Please try again later.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "erroruploadingaltimage") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> Alt Image -> There was an error while uploading. Please try again later.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "titlebeingused") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> The title is being used in another blog. Try picking a different title.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "pathbeingused") {
                        echo "<div class='alert alert-danger'>
                            <strong>Error!</strong> The blog path is being used in another blog. Try picking a different blog path.
                        </div>";
                    } else if ($_REQUEST['updateblog'] == "homepageplacementerror") {
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
                                Edit: <?php if ($_SESSION['editTitle']) {
                                            echo $_SESSION['editTitle'];
                                        } ?>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <!-- form for update-blog. -->
                                        <form role="form" method="POST" class="" action="./includes/update-blog.php" enctype="multipart/form-data">
                                            <!-- get the blogId form the data and send that to the update-blog.php. -->
                                            <input type="hidden" name="blog-id" value="<?php echo $blogId; ?>">

                                            <!-- Title -->
                                            <div class="form-group">
                                                <label>Title</label>
                                                <input class="form-control" name="blog-title" value="<?php if (isset($_SESSION['editTitle'])) {
                                                                                                            echo $_SESSION['editTitle'];
                                                                                                        } ?>">
                                            </div>
                                            <!-- meta Title -->
                                            <div class="form-group">
                                                <label>meta Title</label>
                                                <input class="form-control" name="blog-meta-title" value="<?php if (isset($_SESSION['editMetaTitle'])) {
                                                                                                                echo $_SESSION['editMetaTitle'];
                                                                                                            } ?>">
                                            </div>

                                            <!-- select category -->
                                            <div class="form-group">
                                                <label>Selects Blog category</label>
                                                <select class="form-control" name="blog-category">
                                                    <!-- fetch category from 'blog_category' table -->
                                                    <option>Select category-blog</option>
                                                    <?php

                                                    $sqlCategories = "SELECT * FROM blog_category";
                                                    $queryCategories = mysqli_query($conn, $sqlCategories);

                                                    while ($rowCategories = mysqli_fetch_assoc($queryCategories)) {

                                                        $cId = $rowCategories['n_category_id'];
                                                        $cName = $rowCategories['v_category_title'];

                                                        if ($_SESSION['editCategoryId'] == $cId) {
                                                            echo "<option value='" . $cId . "' selected=''>" . $cName . "</option>";
                                                        } else {
                                                            echo "<option value='" . $cId . "'>" . $cName . "</option>";
                                                        }
                                                    }

                                                    ?>
                                                </select>
                                            </div>

                                            <!-- upload imag -->
                                            <div class="form-group">
                                                <label>Update Main Image</label>
                                                <input type="file" name="main-blog-image" id="main-blog-image">
                                                <?php
                                                // ///in case the is selected main img
                                                if (!empty($mainImgUrl)) {
                                                    echo "<p style='font-size:inherit;'><a href='' data-toggle='modal' data-target='#main-image' class='popup-button' style='margin-top:10px'>View Existing Image</a></p>";
                                                }
                                                ?>
                                            </div>

                                            <!-- Alternte image -->
                                            <div class="form-group">
                                                <label>Update Alternate Image</label>
                                                <input type="file" name="alt-blog-image" id="alt-blog-image">
                                                <?php
                                                // ///in case the is selected alt img
                                                if (!empty($altImgUrl)) {
                                                    echo "<p style='font-size:inherit;'><a href='' data-toggle='modal' data-target='#alt-image' class='popup-button' style='margin-top:10px'>View Existing Image</a></p>";
                                                }
                                                ?>
                                            </div>
                                            <!-- inputs summary text -->
                                            <div class="form-group">
                                                <label>Summery</label>
                                                <textarea class="form-control" rows="3" name="blog-summary">
                                                <?php if (isset($_SESSION['editSummary'])) {
                                                    echo $_SESSION['editSummary'];
                                                } ?>
                                                </textarea>
                                            </div>
                                            <!-- blog content field -->
                                            <div class="form-group">
                                                <label>Blog content</label>
                                                <textarea class="form-control" rows="3" id="summernote" name="blog-content">
                                                <?php if (isset($_SESSION['editContent'])) {
                                                    echo $_SESSION['editContent'];
                                                } ?>
                                                </textarea>
                                            </div>
                                            <!-- blog tag field -->
                                            <div class="form-group">
                                                <label>Blog Tag (saperate by comma)</label>
                                                <input class="form-control" name="blog-tags" value="<?php if (isset($_SESSION['editTags'])) {
                                                                                                        echo $_SESSION['editTags'];
                                                                                                    } ?>">
                                                <!-- <p class="help-block">Example block-level help text here.</p> -->
                                            </div>
                                            <!-- blog path -->
                                            <div class="form-group">
                                                <label>Blog path</label>
                                                <div class="input-group">
                                                    <span class="input-group-addon">www.blog-hub/</span>
                                                    <input type="text" class="form-control" name="blog-path" value="<?php if (isset($_SESSION['editPath'])) {
                                                                                                                        echo $_SESSION['editPath'];
                                                                                                                    } ?>" placeholder="Enter your blog path">
                                                </div>
                                            </div>
                                            <!-- choose radio btn -->
                                            <div class="form-group">
                                                <label>Home page placemment</label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement" id="optionsRadiosInline1" value="1" <?php if (isset($_SESSION['editHomePagePlacement'])) {
                                                                                                                                                if ($_SESSION['editHomePagePlacement'] == 1) {
                                                                                                                                                    echo "checked=''";
                                                                                                                                                }
                                                                                                                                            } ?>>1
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement" id="optionsRadiosInline2" value="2" <?php if (isset($_SESSION['editHomePagePlacement'])) {
                                                                                                                                                if ($_SESSION['editHomePagePlacement'] == 2) {
                                                                                                                                                    echo "checked=''";
                                                                                                                                                }
                                                                                                                                            } ?>>2
                                                </label>
                                                <label class="radio-inline">
                                                    <input type="radio" name="blog-home-page-placement" id="optionsRadiosInline3" value="3" <?php if (isset($_SESSION['editHomePagePlacement'])) {
                                                                                                                                                if ($_SESSION['editHomePagePlacement'] == 3) {
                                                                                                                                                    echo "checked=''";
                                                                                                                                                }
                                                                                                                                            } ?>>3
                                                </label>
                                            </div>



                                            <button type="submit" name="submit-edit-blog" class="btn btn-default">Save changes</button>
                                            <!-- <button type="reset" class="btn btn-default">Reset Button</button> -->
                                        </form>
                                    </div>
                                </div>
                                <!-- /.row (nested) -->
                                <?php if (!empty($mainImgUrl)) : ?>
                                    <!-- main img moadl -->
                                    <div class="modal fade" id="main-image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title" id="myModalLabel">Main Image</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="<?php echo $mainImgUrl; ?>" style="max-width:100%; height:auto;" />
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
                                <!-- alt img moadl -->
                                <?php if (!empty($altImgUrl)) : ?>
                                    <div class="modal fade" id="alt-image" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                    <h4 class="modal-title" id="myModalLabel">Alt Image</h4>
                                                </div>
                                                <div class="modal-body">
                                                    <img src="<?php echo $altImgUrl; ?>" style="max-width:100%; height:auto;" />
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif; ?>
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
    <!-- Summernote Js -->
    <script src="./summernote/summernote.min.js"></script>
    <!-- start the summernote -->
    <script>
        $(document).ready(function() {
            $('#summernote').summernote({
                height: 300,
                minHeight: null,
                maxHeight: null,
                focus: false
            });
        });
    </script>


</body>

</html>