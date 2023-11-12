<?php

require "dbh.php";
session_start();
// ////check the form from the edit-blog.php
if (isset($_POST['submit-edit-blog'])) {
    //////grab all inputs from the form of edit-blog.php.
    $blogId = $_POST['blog-id'];
    $title = $_POST['blog-title'];
    $metaTitle = $_POST['blog-meta-title'];
    $blogCategoryId = $_POST['blog-category'];
    $blogSummary = $_POST['blog-summary'];
    $blogContent = $_POST['blog-content'];
    $blogTags = $_POST['blog-tags'];
    $blogPath = $_POST['blog-path'];
    $homePagePlacement = $_POST['blog-home-page-placement'];
    /////carete date and time of changes.
    $date = date("Y-m-d");
    $time = date("H:i:s");
    ///////check for any empty inputs.
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

    if (strpos($blogPath, " ") !== false) {
        formError("pathcontainsspaces");
    }

    if (empty($homePagePlacement)) {
        $homePagePlacement = 0;
    }
    ///////diffine the sql query for the blog_title and the blog_path
    $sqlCheckBlogTitle = "SELECT v_post_title FROM blog_post WHERE v_post_title = '$title' AND v_post_title != '$title' AND f_post_status != '2'";
    $queryCheckBlogTitle = mysqli_query($conn, $sqlCheckBlogTitle);

    $sqlCheckBlogPath = "SELECT v_post_path FROM blog_post WHERE v_post_path = '$blogPath' AND v_post_path != '$blogPath' AND f_post_status != '2'";
    $queryCheckBlogPath = mysqli_query($conn, $sqlCheckBlogPath);
    /////send msg to the user of taken inputs in database
    if (mysqli_num_rows($queryCheckBlogTitle) > 0) {
        formError("titlebeingused");
    } else if (mysqli_num_rows($queryCheckBlogPath) > 0) {
        formError("pathbeingused");
    }
    ////////Handle with the homepageplacement
    if ($homePagePlacement != 0) {

        $sqlCheckBlogHomePagePlacement = "SELECT * FROM blog_post WHERE n_home_page_placement = '$homePagePlacement' AND f_post_status != '2'";
        $queryCheckBlogHomePagePlacement = mysqli_query($conn, $sqlCheckBlogHomePagePlacement);

        if (mysqli_num_rows($queryCheckBlogHomePagePlacement)) {

            $sqlUpdateBlogHomePagePlacement = "UPDATE blog_post SET n_home_page_placement = '0' WHERE n_home_page_placement = '$homePagePlacement' AND f_post_status != '2'";

            if (!mysqli_query($conn, $sqlUpdateBlogHomePagePlacement)) {
                formError("homepageplacementerror");
            }
        }
    }
    //////send to the update and upload file function.
    $mainImgUrl = uploadImage($_FILES["main-blog-image"]["name"], "main-blog-image", "main", "v_main_image_url");
    $altImgUrl = uploadImage($_FILES["alt-blog-image"]["name"], "alt-blog-image", "alt", "v_alt_image_url");
    ////////update data changes with divided files if changes.
    if ($mainImgUrl == "noupdate") {
        if ($altImgUrl == "noupdate") {
            // ////In case the main img and alt img not changed
            $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title = '$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";
        } else {
            // ///just the main img has been changed
            $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title = '$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent', v_alt_image_url = '$altImgUrl', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";
        }
    } else if ($altImgUrl == "noupdate") {
        if ($mainImgUrl != "noupdate") {
            //////////just alt img has been changed
            $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title = '$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent', v_main_image_url = '$mainImgUrl', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";
        }
    } else {
        /////main img and alt img has been changed
        $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title = '$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent', v_main_image_url = '$mainImgUrl', v_alt_image_url = '$altImgUrl', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";
    }
    //////here update data in the 'blog_tags' table
    $sqlUpdateBlogTags = "UPDATE blog_tags SET v_tag = '$blogTags' WHERE n_blog_post_id = '$blogId'";

    //////check if the query sql run propaply.
    if (mysqli_query($conn, $sqlUpdateBlog) && mysqli_query($conn, $sqlUpdateBlogTags)) {
        formSuccess();////send updateblog msg - success.
    } else {
        formError("sqlerror");////send updateblog msg - error.
    }
} else {
    /////rediirect to the inde.php - no btn has been preesed
    header("Location: ../index.php");
    exit();
}
///////form of success msg
function formSuccess()
{

    require "dbh.php";
    mysqli_close($conn);
    /////cancel the session data
    unset($_SESSION['editBlogId']);
    unset($_SESSION['editTitle']);
    unset($_SESSION['editMetaTitle']);
    unset($_SESSION['editCategoryId']);
    unset($_SESSION['editSummary']);
    unset($_SESSION['editContent']);
    unset($_SESSION['editPath']);
    unset($_SESSION['editTags']);
    unset($_SESSION['editHomePagePlacement']);
    //////redirrect with the success sg
    header("Location: ../blogs.php?updateblog=success");
    exit();
}

function formError($errorCode)
{

    require "dbh.php";
    /////save in session the last update inputs
    $_SESSION['editTitle'] = $_POST['blog-title'];
    $_SESSION['editMetaTitle'] = $_POST['blog-meta-title'];
    $_SESSION['editCategoryId'] = $_POST['blog-category'];
    $_SESSION['editSummary'] = $_POST['blog-summary'];
    $_SESSION['editContent'] = $_POST['blog-content'];
    $_SESSION['editTags'] = $_POST['blog-tags'];
    $_SESSION['editPath'] = $_POST['blog-path'];
    $_SESSION['editHomePagePlacement'] = $_POST['blog-home-page-placement'];

    mysqli_close($conn); ////close the connection to database
    //////reddirect with error msg.
    header("Location: ../edit-blog.php?updateblog=" . $errorCode);
    exit();
}
//////////function for update and upload new file img
function uploadImage($img, $imgName, $imgType, $imgDbColumn)
{

    require "dbh.php";

    $imgUrl = "";

    $validExt = array("jpg", "png", "jpeg", "bmp", "gif");

    if ($img == "") {
        return "noupdate";
    } else {

        if ($_FILES[$imgName]["size"] <= 0) {
            formError($imgType . "imageerror");
        } else {

            $ext = strtolower(end(explode(".", $img)));
            if (!in_array($ext, $validExt)) {
                formError("invalidtype" . $imgType . "image");
            }

            // delete old image
            $blogId = $_POST['blog-id'];
            ///////get from the blog_post table the old imgurl
            $sqlGetOldImage = "SELECT " . $imgDbColumn . " FROM blog_post WHERE n_blog_post_id = '$blogId'";
            $queryGetOldImage = mysqli_query($conn, $sqlGetOldImage);

            if ($rowGetOldImage = mysqli_fetch_assoc($queryGetOldImage)) {
                $oldImgURL = $rowGetOldImage[$imgDbColumn];
            }

            if (!empty($oldImgURL)) {
                $oldImgURLArray = explode("/", $oldImgURL);
                $oldImgName = end($oldImgURLArray);
                $oldImgPath = "../images/blog-images/" . $oldImgName;
                unlink($oldImgPath); ////delete the file from the folder
            }
            ///////diffine the dir for new img file
            $folder = "../images/blog-images/";
            ////dififne name for the img
            $imgNewName = rand(10000, 990000) . '_' . time() . '.' . $ext;
            $imgPath = $folder . $imgNewName;
            ////try upload and set the imgURL
            if (move_uploaded_file($_FILES[$imgName]['tmp_name'], $imgPath)) {
                $imgUrl = "http://localhost/blog/admin/images/blog-images/" . $imgNewName;
            } else {
                formError("erroruploading" . $imgType . "image");
            }
        }

        return $imgUrl;
    }
}
?>


<?php

// include './dbh.php';
// session_start();

// // Handle the form
// if (isset($_POST['submit-edit-blog'])) {

//     // Retrieve user input from the form
//     $title = $_POST['blog-title'];
//     $metaTitle = $_POST['blog-meta-title'];
//     $blogCategoryId = $_POST['blog-category'];
//     $blogSummary = $_POST['blog-summary'];
//     $blogContent = $_POST['blog-content'];
//     $blogTags = $_POST['blog-tags'];
//     $blogPath = $_POST['blog-path'];
//     $homePagePlacement = $_POST['blog-home-page-placement'];
//     // create time create a blog 
//     $date = date("Y-m-d");
//     $time = date("H:i:s");

//     // chaecking input if are correct or emty filed
//     if (empty($title)) {
//         formError("emptytitle");
//     } else if (empty($blogCategoryId)) {
//         formError("emptycategory");
//     } else if (empty($blogSummary)) {
//         formError("emptysummary");
//     } else if (empty($blogContent)) {
//         formError("emptycontent");
//     } else if (empty($blogTags)) {
//         formError("emptytags");
//     } else if (empty($blogPath)) {
//         formError("emptypath");
//     }
//     // path contain spaces.
//     if (strpos($blogPath, " ") !== false) {
//         formError("pathcontainsspaces");
//     }
//     // defualt home-pacement
//     if (empty($homePagePlacement)) {
//         $homePagePlacement = 0;
//     }
//     // /////check if there is blog_title or blog_path has been used.
//     $sqlCheckBlogTitle = "SELECT v_post_title FROM blog_post WHERE v_post_title = '$title' AND v_post_title != '$title' AND f_post_status != '2'";
//     $queryCheckBlogTitle = mysqli_query($conn, $sqlCheckBlogTitle);

//     $sqlCheckBlogPath = "SELECT v_post_path FROM blog_post WHERE v_post_path = '$blogPath' AND v_post_path != '$blogPath' AND f_post_status != '2'";
//     $queryCheckBlogPath = mysqli_query($conn, $sqlCheckBlogPath);

//     // send msg error.
//     if (mysqli_num_rows($queryCheckBlogTitle) > 0) {
//         formError("titlebeingused");
//     } else if (mysqli_num_rows($queryCheckBlogPath) > 0) {
//         formError("pathbeingused");
//     }
//     // /////check about the homePagePlacement if is chooded
//     if ($homePagePlacement != 0) {
//         // ///replace homePagePlacement.
//         $sqlCheckBlogHomePagePlacement = "SELECT * FROM blog_post WHERE n_home_page_placement = '$homePagePlacement' AND f_post_status != '2'";
//         $queryCheckBlogHomePagePlacement = mysqli_query($conn, $sqlCheckBlogHomePagePlacement);
//         // ////homePagePlacement has been taken -> replace them.
//         if (mysqli_num_rows($queryCheckBlogHomePagePlacement)) {

//             $sqlUpdateBlogHomePagePlacement = "UPDATE blog_post SET n_home_page_placement = '0' WHERE n_home_page_placement = '$homePagePlace' AND f_post_status != '2'";
//             // ///sql query not has been succesessfully.
//             if (!mysqli_query($conn, $sqlUpdateBlogHomePagePlacement)) {
//                 // //////send error msg.
//                 formError("homepageplacementerror");
//             }
//         }
//     }
//     // ////diffine mainImgUrl and altImgUrl.
//     // ///use the function.
//     $mainImgUrl = uploadImage($_FILES["main-blog-image"]["name"], "main-blog-image", "main", "v_main_image_url");
//     $altImgUrl = uploadImage($_FILES["alt-blog-image"]["name"], "alt-blog-image", "alt", "v_alt_image_url");

//     // ///check if there is any changes in the files upload img.
//     if ($mainImgUrl == "noupdate") {
//         // ///in case no chagnes in main img colum
//         if ($altImgUrl == "noupdate") {
//             // ///in case no chagnes in main img and in alt img colums
//             $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title = '$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";
//         } else {
//             // ///in case no chagnes in main img colum only.
//             $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title = '$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent', v_alt_image_url = '$altImgUrl', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";
//         }
//     } else if ($altImgUrl == "noupdate") {
//         // ///in case no chagnes in alt img colum
//         if ($mainImgUrl != "noupdate") {
//             // ///in case no chagnes in main img and in alt img colums
//             $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title = '$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent', v_main_image_url = '$mainImgUrl', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";
//         }
//     } else {
//         // ///in case there is changes in main img and in alt img colums.
//         $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title = '$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent', v_main_image_url = '$mainImgUrl', v_alt_image_url = '$altImgUrl', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";
//     }
//     // ///here need to run sql query for 'blog_tags' table.

//     //////try and chaeck run the query update blog_post table.
//     if (mysqli_query($conn, $sqlUpdateBlog) && mysqli_query($conn, $sqlUpdateBlogTags)) {
//         formSuccess();
//     } else {
//         formError("sqlerror");
//     }

// } else {
//     // Redirect to the index.php - btn has not preesed
//     // echo "<script>window.location.href = '../index.php';</script>";
//     header("Location: ../index.php");
// }
// // function dealing with messege to the user.
// // ///success-msg
// function formSuccess()
// {

//     require "dbh.php";
//     mysqli_close($conn); ////close database connection.
//     // ////after success update the data in the blog_post table - cancel the session.
//     unset($_SESSION['editBlogId']);
//     unset($_SESSION['editTitle']);
//     unset($_SESSION['editMetaTitle']);
//     unset($_SESSION['editCategoryId']);
//     unset($_SESSION['editSummary']);
//     unset($_SESSION['editContent']);
//     unset($_SESSION['editPath']);
//     unset($_SESSION['editTags']);
//     unset($_SESSION['editHomePagePlacement']);
//     ///////redirrect with success msg.
//     header("Location: ../blogs.php?updateblog=success");
//     exit();
// }
// // ///error-msg
// function formError($errorCode)
// {

//     require "dbh.php";
//     // ////save in session last changes.
//     $_SESSION['editTitle'] = $_POST['blog-title'];
//     $_SESSION['editMetaTitle'] = $_POST['blog-meta-title'];
//     $_SESSION['editCategoryId'] = $_POST['blog-category'];
//     $_SESSION['editSummary'] = $_POST['blog-summary'];
//     $_SESSION['editContent'] = $_POST['blog-content'];
//     $_SESSION['editTags'] = $_POST['blog-tags'];
//     $_SESSION['editPath'] = $_POST['blog-path'];
//     $_SESSION['editHomePagePlacement'] = $_POST['blog-home-page-placement'];

//     mysqli_close($conn); ////close he database
//     // //redirect - with error msg.
//     header("Location: ../edit-blog.php?updateblog=" . $errorCode);
//     exit();
// }







// // function that will be respansible about update and upload images.
// function uploadImage($img, $imgName, $imgType, $imgDbColumn)
// {

//     require "dbh.php";

//     $imgUrl = "";

//     $validExt = array("jpg", "png", "jpeg", "bmp", "gif");

//     if ($img == "") {
//         // ///nothing chaged
//         return "noupdate";
//     } else {

//         if ($_FILES[$imgName]["size"] <= 0) {
//             formError($imgType . "imageerror");
//         } else {

//             $ext = strtolower(end(explode(".", $img)));
//             if (!in_array($ext, $validExt)) {
//                 formError("invalidtype" . $imgType . "image");
//             }

//             // delete old image
//             $blogId = $_POST['blog-id']; ///get the id og the blog_post data.
//             /////sql query for the old img that in the database
//             $sqlGetOldImage = "SELECT " . $imgDbColumn . " FROM blog_post WHERE n_blog_post_id = '$blogId'";
//             $queryGetOldImage = mysqli_query($conn, $sqlGetOldImage);

//             if ($rowGetOldImage = mysqli_fetch_assoc($queryGetOldImage)) {
//                 $oldImgURL = $rowGetOldImage[$imgDbColumn];
//             }

//             if (!empty($oldImgURL)) {
//                 $oldImgURLArray = explode("/", $oldImgURL);
//                 $oldImgName = end($oldImgURLArray);
//                 // ///diffine the path dir
//                 $oldImgPath = "../images/blog-images/" . $oldImgName;
//                 unlink($oldImgPath); ////delete the old file.
//             }
//             ////diffine the folder
//             $folder = "../images/blog-images/";
//             // /////choose random number fole new name img
//             $imgNewName = rand(10000, 990000) . '_' . time() . '.' . $ext;
//             $imgPath = $folder . $imgNewName;
//             //////check for the upload img file to the folder.
//             if (move_uploaded_file($_FILES[$imgName]['tmp_name'], $imgPath)) {
//                 $imgUrl = "http://localhost/blog/admin/images/blog-images/" . $imgNewName;
//             } else {
//                 formError("erroruploading" . $imgType . "image");
//             }
//         }

//         return $imgUrl;
//     }
// }
