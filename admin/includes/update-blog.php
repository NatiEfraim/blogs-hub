<?php
require "dbh.php";
session_start();

// Function to safely escape and quote string values for SQL queries
function escapeString($conn, $value)
{
    return mysqli_real_escape_string($conn, $value);
}


if (isset($_POST['submit-edit-blog'])) {
    // ///get all inputs from the form edit-blog
    $blogId = $_POST['blog-id'];
    $title = escapeString($conn, $_POST['blog-title']);
    $metaTitle = escapeString($conn, $_POST['blog-meta-title']);
    $blogCategoryId = $_POST['blog-category'];
    $blogSummary = escapeString($conn, $_POST['blog-summary']);
    $blogContent = escapeString($conn, $_POST['blog-content']);
    $blogTags = escapeString($conn, $_POST['blog-tags']);
    $blogPath = escapeString($conn, $_POST['blog-path']);
    $homePagePlacement = $_POST['blog-home-page-placement'];
    $blogAuthorName = escapeString($conn, $_POST['blog-author-name']);
    ///ceate date and time
    $date = date("Y-m-d");
    $time = date("H:i:s");

    // Check for empty inputs
    if (empty($title) || empty($blogCategoryId) || empty($blogSummary) || empty($blogContent) || empty($blogTags) || empty($blogPath) || empty($blogAuthorName)) {
        formError("Some fields are empty."); ////send error msg to user
    }
    //////blog-path error
    if (strpos($blogPath, " ") !== false) {
        formError("Path contains spaces.");
    }

    if (empty($homePagePlacement)) {
        $homePagePlacement = 0; // Default choice
    }

    // Update home page placement if needed
    if ($homePagePlacement != 0) {
        $sqlUpdateBlogHomePagePlacement = "UPDATE blog_post SET n_home_page_placement = '0' WHERE n_home_page_placement = '$homePagePlacement' AND f_post_status != '2'";
        if (!mysqli_query($conn, $sqlUpdateBlogHomePagePlacement)) {
            formError("Failed to update home page placement.");
        }
    }
    //////handel with the immages files in the database
    $mainImgUrl = uploadImage($_FILES["main-blog-image"]["name"], "main-blog-image", "main", "v_main_image_url");
    $altImgUrl = uploadImage($_FILES["alt-blog-image"]["name"], "alt-blog-image", "alt", "v_alt_image_url");
    $authorImgUrl = uploadImage($_FILES["author-blog-image"]["name"], "author-blog-image", "author", "v_author_image_url");
    // Construct and execute the SQL query
    if (($mainImgUrl == "noupdate") && ($altImgUrl == "noupdate") && ($authorImgUrl == "noupdate")) {
        /////nothing from the images files has been chagnes
        ///diffine the query
        $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title = '$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent', v_author_name = '$blogAuthorName', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";
    } else if (($mainImgUrl == "noupdate") && ($altImgUrl == "noupdate")) {
        /////just the author image file has been chagnes
        ///diffine the query
        $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title = '$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent',v_author_image_url='$authorImgUrl', v_author_name = '$blogAuthorName', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";
    } else if (($altImgUrl == "noupdate") && ($authorImgUrl == "noupdate")) {
        /////just the main image file has been chagnes
        ///diffine the query
        $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title = '$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent',v_main_image_url='$mainImgUrl', v_author_name = '$blogAuthorName', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";
    } else if (($mainImgUrl == "noupdate") && ($authorImgUrl == "noupdate")) {
        /////just the alt image file has been chagnes
        ///diffine the query
        $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title = '$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent',v_alt_image_url='$altImgUrl', v_author_name = '$blogAuthorName', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";
    } else {
        /////need to change all the 3 images files in the database
        ///diffine the query
        $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title = '$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent',v_main_image_url='$mainImgUrl',v_alt_image_url='$altImgUrl',v_author_image_url='$authorImgUrl', v_author_name = '$blogAuthorName', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";
    }
    /////genera; sql update.
    // $sqlUpdateBlog = "UPDATE blog_post SET n_category_id = '$blogCategoryId', v_post_title = '$title', v_post_meta_title = '$metaTitle', v_post_path = '$blogPath', v_post_summary = '$blogSummary', v_post_content = '$blogContent', v_author_name = '$blogAuthorName', n_home_page_placement = '$homePagePlacement', d_date_updated = '$date', d_time_updated = '$time' WHERE n_blog_post_id = '$blogId'";

    if (mysqli_query($conn, $sqlUpdateBlog)) {
        // Update blog_tags table
        $sqlUpdateBlogTags = "UPDATE blog_tags SET v_tag = '$blogTags' WHERE n_blog_post_id = '$blogId'";
        if (mysqli_query($conn, $sqlUpdateBlogTags)) {
            formSuccess();
        } else {
            ////feil try to uplad to data
            formError("Failed to update blog_tags table.");
        }
    } else {
        formError("SQL error: " . mysqli_error($conn));
    }
}








//////////////////////////Functions

/////form of success msg
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

    unset($_SESSION['editAuthorName']); ////un-set edit-authorName

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
                $imgUrl = "http://localhost/dashboard/blogs-hub/admin/images/blog-images/" . $imgNewName;
            } else {
                formError("erroruploading" . $imgType . "image");
            }
        }

        return $imgUrl;
    }
}
