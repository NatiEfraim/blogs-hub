<?php
include './dbh.php';
// Handle the form
if (isset($_POST['add-category-btn'])) {
    if (
        // some inputs msissing
        empty($_POST['category-name'])
        || empty($_POST['category-meta-title'])
        || empty($_POST['category-path'])
    ) {
        // Display an error message if any required fields are missing
        echo "<script>alert('Error! Please fill in all required fields')</script>";
        echo "<script>window.location.href = '../blog-category.php';</script>";
    } else {
        // Retrieve user input from the form
        $name = $_POST['category-name'];
        $metaTitle = $_POST['category-meta-title'];
        $categoryPath = $_POST['category-path'];
        // create time create a blog category
        $date = date("Y-m-d");
        $time = date("H:i:sa");

        // echo $name . "<br/>";
        // echo $metaTitle . "<br/>";
        // echo $categoryPath . "<br/>";
        // echo $date . "<br/>";
        // echo $time . "<br/>";


        $sqlAddCategory = "INSERT INTO blog_category (v_category_title,v_category_meta_title,v_category_path,d_date_created,d_time_created)
            VALUES ('$name','$metaTitle','$categoryPath','$date','$time')";
        if (mysqli_query($conn, $sqlAddCategory)) {
            mysqli_close($conn); //close database
            // blog category uploaded successfully
            // echo "<script>alert('The blog category has been added to the database')</script>";
            // Redirect to the show-rooms page after successful creation of a room
            echo "<script>window.location.href = '../blog-category.php?addcategory=success';</script>";
            exit();
        } else {
            mysqli_close($conn); //close database
            // blog category uploaded successfully
            // echo "<script>alert('The blog category has been added to the database')</script>";
            // Redirect to the show-rooms page after successful creation of a room
            echo "<script>window.location.href = '../blog-category.php?addcategory=error';</script>";
        }
    }
} else {
    // Redirect to the blog-category.php - btn has not preesed
    echo "<script>window.location.href = '../blog-category.php';</script>";
}
