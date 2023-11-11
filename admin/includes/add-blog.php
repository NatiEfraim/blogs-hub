<?php

include './dbh.php';
session_start();

// Handle the form
if (isset($_POST['submit-blog'])) {

    // Retrieve user input from the form
    $title = $_POST['blog-title'];
    $metaTitle = $_POST['blog-meta-title'];
    $blogCategoryId = $_POST['blog-category'];
    $blogSummary = $_POST['blog-summary'];
    $blogContent = $_POST['blog-content'];
    $blogTags = $_POST['blog-tags'];
    $blogPath = $_POST['blog-path'];
    $homePagePlacement = $_POST['blog-home-page-placement'];
    // create time create a blog 
    $date = date("Y-m-d");
    $time = date("H:i:s");

    // chaecking input if are correct or emty filed
    if (empty($title)) {
        formError("emptytitle");
    } else if (empty($blogCategoryId)) {
        formError("emptycategory");
    } else if (empty($blogSummary)) {
        formError("emptysummary");
    } else if (empty($blogContent)) {
        formError("emptycontent");
    } else if (empty($blogTags)) {
        formError("emptytags");
    } else if (empty($blogPath)) {
        formError("emptypath");
    }
    // path contain spaces.
    if (strpos($blogPath, " ") !== false) {
        formError("pathcontainsspaces");
    }
    // defualt home-pacement
    if (empty($homePagePlacement)) {
        $homePagePlacement = 0;
    }
    // /////check if there is title or metaTitle has been used.
    $sqlCheckBlogTitle = "SELECT v_post_title FROM blog_post WHERE v_post_title = '$title' AND f_post_status != '2'";
    $queryCheckBlogTitle = mysqli_query($conn, $sqlCheckBlogTitle);

    $sqlCheckBlogPath = "SELECT v_post_path FROM blog_post WHERE v_post_path = '$blogPath' AND f_post_status != '2'";
    $queryCheckBlogPath = mysqli_query($conn, $sqlCheckBlogPath);
    // send msg error.
    if (mysqli_num_rows($queryCheckBlogTitle) > 0) {
        formError("titlebeingused");
    } else if (mysqli_num_rows($queryCheckBlogPath) > 0) {
        formError("pathbeingused");
    }
    // /////check about the homePagePlacement if is chooded
    if ($homePagePlacement != 0) {
        // ///replace homePagePlacement.
        $sqlCheckBlogHomePagePlacement = "SELECT * FROM blog_post WHERE n_home_page_placement = '$homePagePlacement' AND f_post_status != '2'";
        $queryCheckBlogHomePagePlacement = mysqli_query($conn, $sqlCheckBlogHomePagePlacement);
        // ////homePagePlacement has been taken -> replace them.
        if (mysqli_num_rows($queryCheckBlogHomePagePlacement)) {

            $sqlUpdateBlogHomePagePlacement = "UPDATE blog_post SET n_home_page_placement = '0' WHERE n_home_page_placement = '$homePagePlace' AND f_post_status != '2'";
            // ///sql query not has been succesessfully.
            if (!mysqli_query($conn, $sqlUpdateBlogHomePagePlacement)) {
                // //////send error msg.
                formError("homepageplacementerror");
            }
        }
    }
    // ////diffine mainImgUrl and altImgUrl.
    // ///use the function.
    $mainImgUrl = uploadImage($_FILES["main-blog-image"]["name"], "main-blog-image", "main");
    $altImgUrl = uploadImage($_FILES["alt-blog-image"]["name"], "alt-blog-image", "alt");



    // ///// insert the date to the 'category_blog' table.
    $sqlAddBlog = "INSERT INTO blog_post (n_category_id,v_post_title,v_post_meta_title,v_post_path,v_post_summary,v_post_content,v_main_image_url,v_alt_image_url,n_home_page_placement,f_post_status,d_date_created,d_time_created)
    VALUES ('$blogCategoryId','$title','$metaTitle','$blogPath','$blogSummary','$blogContent','$mainImgUrl','$altImgUrl','$homePagePlacement','1','$date','$time')";
    if (mysqli_query($conn, $sqlAddBlog)) {
        // ////after adding a blog to database - unset session
        // /////store the inputs in the session.
        unset($_SESSION['blogTitle']);
        unset($_SESSION['blogMetaTitle']);
        unset($_SESSION['blogCategoryId']);
        unset($_SESSION['blogSummary']);
        unset($_SESSION['blogContent']);
        unset($_SESSION['blogTags']);
        unset($_SESSION['blogPath']);
        unset($_SESSION['blogHomePagePlacement']);



        mysqli_close($conn); //close database
        echo "<script>window.location.href = '../blogs.php?addblog=success';</script>";
        exit();
    } else {
        formError("sqlerror");
    }
} else {
    // Redirect to the index.php - btn has not preesed
    // echo "<script>window.location.href = '../index.php';</script>";
    header("Location: ../index.php");
}
// function dealing with error messege to the user.
function formError($errorCode)
{
    require "dbh.php";
    // /////store the inputs in the session.
    $_SESSION['blogTitle'] = $_POST['blog-title'];
    $_SESSION['blogMetaTitle'] = $_POST['blog-meta-title'];
    $_SESSION['blogCategoryId'] = $_POST['blog-category'];
    $_SESSION['blogSummary'] = $_POST['blog-summary'];
    $_SESSION['blogContent'] = $_POST['blog-content'];
    $_SESSION['blogTags'] = $_POST['blog-tags'];
    $_SESSION['blogPath'] = $_POST['blog-path'];
    $_SESSION['blogHomePagePlacement'] = $_POST['blog-home-page-placement'];
    // ////close sql.
    mysqli_close($conn);
    header("Location: ../write-a-blog.php?addblog=" . $errorCode); ///sent the url the error-code.
    exit();
}
// function that will be respansible about upload images.
function uploadImage($img, $imgName, $imgType)
{

    $imgUrl = "";
    // ////diffime alow ext
    $validExt = array("jpg", "png", "jpeg", "bmp", "gif");
    // ////emty image - or not alwed image
    if ($img == "") {
        formError("empty" . $imgType . "image");
    } else if ($_FILES[$imgName]["size"] <= 0) {
        formError($imgType . "imageerror");
    } else {

        $ext = strtolower(end(explode(".", $img)));
        // ////not alwed ext
        if (!in_array($ext, $validExt)) {
            // ////error msg
            formError("invalidtype" . $imgType . "image");
        }
        // ///diffine dir folder
        $folder = "../images/blog-images/";
        //////diffine random name number
        $imgNewName = rand(10000, 990000) . '_' . time() . '.' . $ext;
        $imgPath = $folder . $imgNewName;
        /////try uploaed
        if (move_uploaded_file($_FILES[$imgName]['tmp_name'], $imgPath)) {
            $imgUrl = "http://localhost/dashboard/blog/admin/images/blog-images/" . $imgNewName;
        } else {
            formError("erroruploading" . $imgType . "image");
        }
    }
    /////return img url.
    return $imgUrl;
}
