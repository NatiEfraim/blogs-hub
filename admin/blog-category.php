<?php
// include './includes/dbh.php';
// $sqlCategories = "SELECT * FROM blog_category";
// $queryCategories = mysqli_query($conn, $sqlCategories);///get the query
// $numCategories=mysqli_num_rows($queryCategories);///get num of rows in the table
require './includes/db-pdo.php';
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



<!-- html part - add category to database -->
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
        <!--/. NAV TOP  -->

        <!-- /. NAV SIDE  -->
        <div id="page-wrapper">
            <div id="page-inner">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="page-header">
                            Blog Category
                        </h1>
                    </div>
                </div>
                <!-- messeges from the up load form -->
                <?php
                // chaeck if has addcategory form url
                if (isset($_REQUEST['editcategory'])) {
                    if ($_REQUEST['editcategory'] == "success") {
                        echo "<div class='alert alert-success'>
                            <strong>Success</strong> A blog-tag has been changed successfully!
                            </div>";
                    } else if ($_REQUEST['editcategory'] == "error") {
                        echo "<div class='alert alert-danger'>
                            <strong>Success</strong> A blog-tag has not been chagned!
                            </div>";
                    }
                }
                // chaeck if has editcategory form url
                if (isset($_REQUEST['addcategory'])) {
                    if ($_REQUEST['addcategory'] == "success") {
                        echo "<div class='alert alert-success'>
                                            <strong>Success</strong> A blog-tag has been added successfully!
                                            </div>";
                    } else if ($_REQUEST['addcategory'] == "error") {
                        echo "<div class='alert alert-danger'>
                                            <strong>Success</strong> A blog-tag has not been added!
                                            </div>";
                    }
                }
                // chaeck if has deletecategory form url
                if (isset($_REQUEST['deletecategory'])) {
                    if ($_REQUEST['deletecategory'] == "success") {
                        echo "<div class='alert alert-success'>
                        <strong>Success</strong> A blog-tag has been deleted successfully!
                        </div>";
                    } else if ($_REQUEST['deletecategory'] == "error") {
                        echo "<div class='alert alert-danger'>
                        <strong>Success</strong> A blog-tag has not been deleted!
                        </div>";
                    }
                }
                ?>


                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Add A Categories
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form role="form" method="POST" action="./includes/add-category.php">
                                            <!-- 1 -->
                                            <div class="form-group">
                                                <label>Name</label>
                                                <input class="form-control" name="category-name">
                                            </div>
                                            <!-- 2 -->
                                            <div class="form-group">
                                                <label>Meta Title</label>
                                                <input class="form-control" name="category-meta-title">
                                            </div>
                                            <!-- 3 -->
                                            <div class="form-group">
                                                <label>Category path (lower cases,no spacse)</label>
                                                <input class="form-control" name="category-path">
                                            </div>



                                            <button type="submit" name="add-category-btn" class="btn btn-default">Add Category</button>
                                            <!-- <button type="reset" class="btn btn-default">Reset Button</button> -->
                                        </form>
                                    </div>





                                </div>
                                <!-- /.row (nested) -->
                            </div>
                            <!-- /.panel-body -->
                        </div>
                        <!-- /.panel -->
                        <!-- present tables of category -->
                        <!--   Categories table -->
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                All categories
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-striped table-bordered table-hover">
                                        <thead>
                                            <tr>
                                                <th>id</th>
                                                <th>Name</th>
                                                <th>Meta Title</th>
                                                <th>Category Path</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($allCategories as $category) : ?>
                                                <tr>
                                                    <td><?php echo $category->n_category_id;  ?></td>
                                                    <td><?php echo $category->v_category_title; ?></td>
                                                    <td><?php echo $category->v_category_meta_title; ?></td>
                                                    <td>@<?php echo $category->v_category_path; ?></td>
                                                    <td>
                                                        <!-- diirect to the category?group=path -->
                                                        <button class="popup-button" onclick="window.open('../category.php?group=<?php echo $category->v_category_path   ?>','_blank');">View</button>
                                                        <!-- change blog category base on id -->
                                                        <button data-toggle="modal" data-target="#edit<?php echo $category->n_category_id; ?>" class="popup-button">Edit</button>
                                                        <!-- deleteblog category base on id -->
                                                        <button data-toggle="modal" data-target="#delete<?php echo $category->n_category_id; ?>" class="popup-button">Delete</button>
                                                    </td>
                                                    <!-- popup messege for btn-edit-->
                                                    <div class="modal fade" id="edit<?php echo $category->n_category_id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <!-- form change blog category -->
                                                                <form action="./includes/edit-category.php" method="post">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                        <h4 class="modal-title" id="myModalLabel">Edit Category</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="category-id" id="category-id" value="<?php echo $category->n_category_id; ?>">
                                                                        <!-- change the name of the category -->
                                                                        <div class="form-group">
                                                                            <label for="">Name</label>
                                                                            <input type="" class="form-control" name="edit-category-name" value="<?php echo $category->v_category_title; ?>">
                                                                        </div>
                                                                        <!-- change the meta-title of the category -->
                                                                        <div class="form-group">
                                                                            <label for="">Meta Title</label>
                                                                            <input type="" class="form-control" name="edit-category-meta-title" value="<?php echo $category->v_category_meta_title; ?>">
                                                                        </div>
                                                                        <!-- change the category-path of the category -->
                                                                        <div class="form-group">
                                                                            <label for="">Name</label>
                                                                            <input type="" class="form-control" name="edit-category-path" value="<?php echo $category->v_category_path;; ?>">
                                                                        </div>
                                                                        <!-- Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum. -->
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                        <button type="submit" name="edit-category-btn" class="btn btn-primary">Save changes</button>
                                                                    </div>




                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <!-- popup messege for btn-delete-->
                                                    <div class="modal fade" id="delete<?php echo $category->n_category_id; ?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <!-- form delete blog category -->
                                                                <form action="./includes/delete-category.php" method="post">
                                                                    <div class="modal-header">
                                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                                                                        <h4 class="modal-title" id="myModalLabel">Delete Category</h4>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <input type="hidden" name="category-id" id="category-id" value="<?php echo $category->n_category_id; ?>">
                                                                        <p>Are you sure you want to delete this blog category?</p>


                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                                                        <button type="submit" name="delete-category-btn" class="btn btn-primary">Delete</button>
                                                                    </div>




                                                                </form>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- End  Kitchen Sink -->



                    </div>

                    <!-- /.col-lg-12 -->
                </div>



            </div>
            <!-- /. ROW  -->
            <!-- <footer>
                    <p>All right reserved. Template by: <a href="http://webthemez.com">WebThemez</a></p>
                </footer> -->
            <?php include './footer.php'; ?>
        </div>
        <!-- /. PAGE INNER  -->
    </div>
    <!-- /. PAGE WRAPPER  -->
    </div>
    <!-- /. WRAPPER  -->
    <!-- JS Scripts-->
    <!-- jQuery Js -->
    <script src="assets/js/jquery-1.10.2.js"></script>
    <!-- Bootstrap Js -->
    <script src="assets/js/bootstrap.min.js"></script>
    <!-- Metis Menu Js -->
    <script src="assets/js/jquery.metisMenu.js"></script>
    <!-- Custom Js -->
    <script src="assets/js/custom-scripts.js"></script>


</body>

</html>