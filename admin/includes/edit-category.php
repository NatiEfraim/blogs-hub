<?php
require "dbh.php";

if (isset($_POST['edit-category-btn'])) {

    $id = $_POST['category-id'];
    $name = $_POST['edit-category-name'];
    $metaTitle = $_POST['edit-category-meta-title'];
    $categoryPath = $_POST['edit-category-path'];

    $sqlEditCategory = "UPDATE blog_category SET v_category_title = '$name', v_category_meta_title = '$metaTitle', v_category_path = '$categoryPath' WHERE n_category_id = '$id'";

    if (mysqli_query($conn, $sqlEditCategory)) {
        mysqli_close($conn);
        header("Location: ../blog-category.php?editcategory=success");
        exit();
    } else {
        mysqli_close($conn);
        header("Location: ../blog-category.php?editcategory=error");
        exit();
    }
} else {
    header("Location: ../index.php");
    exit();
}
// include './dbh.php';
// // Handle the form
// if (isset($_POST['edit-category-btn'])) {
//     if (
//         // some inputs msissing
//         empty($_POST['category-id'])
//         || empty($_POST['edit-category-name'])
//         || empty($_POST['edit-category-meta-title'])
//         || empty($_POST['edit-category-path'])
//     ) {
//         // Display an error message if any required fields are missing
//         echo "<script>alert('Error! Please fill in all required fields')</script>";
//         echo "<script>window.location.href = '../blog-category.php';</script>";
//     } else {
//         // Retrieve user input from the form
//         $id = $_POST['category-id'];
//         echo $id;
//         $name = $_POST['edit-category-name'];
//         $metaTitle = $_POST['edit-category-meta-title'];
//         $categoryPath = $_POST['edit-category-path'];

//         $sqlEditCategory = "UPDATE blog_category SET v_category_title='$name',v_category_meta_title='$metaTitle',v_category_path='$categoryPath' WHERE id='$id'";
//         if (mysqli_query($conn, $sqlEditCategory)) {
//             mysqli_close($conn); //close database
//             // blog category has been changed successfully
//             // Redirect to the show-rooms page after successful creation of a room
//             echo "<script>window.location.href = '../blog-category.php?editcategory=success';</script>";
//             exit();
//         } else {
//             mysqli_close($conn); //close database
//             // blog category uploaded successfully
//             // Redirect to the show-rooms page after successful creation of a room
//             // echo "<script>window.location.href = '../blog-category.php?editcategory=error';</script>";
//             exit();
//         }
//     }
// } else {
//     // Redirect to the blog-category.php - btn has not preesed
//     // echo "<script>window.location.href = '../blog-category.php';</script>";
// }
