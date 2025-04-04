<?php
include("session.php");
$exp_fetched = mysqli_query($con, "SELECT * FROM expenses WHERE user_id = '$userid'");

if (isset($_POST['save'])) {
    $fname = $_POST['first_name'];
    $lname = $_POST['last_name'];

    $sql = "UPDATE users SET firstname = '$fname', lastname='$lname' WHERE user_id='$userid'";
    if (mysqli_query($con, $sql)) {
        echo "Records were updated successfully.";
    } else {
        echo "ERROR: Could not able to execute $sql. " . mysqli_error($con);
    }
    header('location: profile.php');
}
if (isset($_POST['expense_limit'])) {
    $new_limit = mysqli_real_escape_string($con, $_POST['expense_limit']);
    $query = "UPDATE users SET expense_limit='$new_limit' WHERE user_id='$userid'";
    if (mysqli_query($con, $query)) {
        echo "<div class='alert alert-success'>Expense limit updated successfully!</div>";
    } else {
        echo "<div class='alert alert-danger'>Failed to update expense limit.</div>";
    }
}

$result = mysqli_query($con, "SELECT expense_limit FROM users WHERE user_id='$userid'");
$row = mysqli_fetch_assoc($result);
$expense_limit = isset($row['expense_limit']) ? $row['expense_limit'] : 0;

// Calculate total expenses for the current month
$expense_query = mysqli_query($con, "SELECT SUM(expense) AS total_expense FROM expenses WHERE user_id='$userid' AND MONTH(expensedate) = MONTH(CURRENT_DATE())");
$expense_row = mysqli_fetch_assoc($expense_query);
$total_expense = isset($expense_row['total_expense']) ? floatval($expense_row['total_expense']) : 0;

// Determine color based on limit
$inputColor = ($total_expense > $expense_limit) ? 'red' : 'green';


if (isset($_POST['but_upload'])) {

    $name = $_FILES['file']['name'];
    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["file"]["name"]);

    // Select file type
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Valid file extensions
    $extensions_arr = array("jpg", "jpeg", "png", "gif");

    // Check extension
    if (in_array($imageFileType, $extensions_arr)) {

        // Insert record
        $query = "UPDATE users SET profile_path = '$name' WHERE user_id='$userid'";
        mysqli_query($con, $query);

        // Upload file
        move_uploaded_file($_FILES['file']['tmp_name'], $target_dir . $name);

        header("Refresh: 0");
    }
}


?>
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>Expense Manager - Dashboard</title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="css/style.css" rel="stylesheet">

    <!-- Feather JS for Icons -->
    <script src="js/feather.min.js"></script>

    <style>
    .list-group-item {
        transition: transform 0.2s ease-in-out, color 0.2s ease-in-out;
    }

    .list-group-item:hover,
    .list-group-item.sidebar-active:hover {
        transform: translateX(5px);
        color:rgb(255, 0, 0) !important;
    }

    /* Style for floating label effect */
    .form-group {
        position: relative;
        margin-bottom: 1.5rem;
    }

    .form-group label {
        position: absolute;
        top: 50%;
        left: 10px;
        transform: translateY(-50%);
        transition: all 0.3s ease-in-out;
        font-size: 16px;
        color: #999;
        pointer-events: none;
    }

    .form-control:focus ~ label,
    .form-control:not(:placeholder-shown) ~ label {
        top: 0;
        left: 5px;
        font-size: 12px;
        color: black;
        background: white;
        padding: 0 5px;
    }

    </style>
</head>

<body>

    <div class="d-flex" id="wrapper">

        <!-- Sidebar -->
        <div class="border-right" id="sidebar-wrapper">
            <div class="user">
                <img class="img img-fluid rounded-circle" src="<?php echo $userprofile ?>" width="120">
                <h5><?php echo $username ?></h5>
                <p><?php echo $useremail ?></p>
            </div>
            <div class="sidebar-heading">Management</div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action"><span data-feather="home"></span> Dashboard</a>
                <a href="add_expense.php" class="list-group-item list-group-item-action "><span data-feather="plus-square"></span> Add Expenses</a>
                <a href="manage_expense.php" class="list-group-item list-group-item-action "><span class="border px-1 py-1 d-inline-block" style="border: 2px solid blue; border-radius: 10px; font-weight: bold;">&#8377;</span></span> Manage Expenses</a>
                <a href="manage_income.php" class="list-group-item list-group-item-action"><span class="bi bi-cash-stack"></span> Manage Income</a>
            </div>
            <div class="sidebar-heading">Settings </div>
            <div class="list-group list-group-flush">
                <a href="profile.php" class="list-group-item list-group-item-action sidebar-active"><span data-feather="user"></span> Profile</a>
                <a href="logout.php" class="list-group-item list-group-item-action "><span data-feather="power"></span> Logout</a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">

            <nav class="navbar navbar-expand-lg navbar-light  border-bottom">


                <button class="toggler" type="button" id="menu-toggle" aria-expanded="false">
                    <span data-feather="menu"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <img class="img img-fluid rounded-circle" src="<?php echo $userprofile ?>" width="25">
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="profile.php">Your Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                    <h3 class="mt-4 text-center">Update Profile</h3>
                    <hr>
                        <form class="form" method="post" action="" enctype='multipart/form-data'>
                            <div class="text-center mt-3">
                                <img src="<?php echo $userprofile; ?>" class="text-center img img-fluid rounded-circle avatar" width="120" alt="Profile Picture">
                            </div>
                            <div class="input-group col-md mb-3 mt-3">
                                <div class="custom-file">
                                    <input type="file" name='file' class="custom-file-input" id="profilepic" aria-describedby="profilepicinput">
                                    <label class="custom-file-label" for="profilepic">Change Photo</label>
                                </div>
                                <div class="input-group-append">
                                    <button class="btn btn-secondary" type="submit" name='but_upload' id="profilepicinput">Upload Picture</button>
                                </div>
                            </div>


                        </form>



                        <form class="form" action="" method="post" id="registrationForm" autocomplete="off">
                        <div class="form-group">
                            <input type="text" class="form-control" name="first_name" id="first_name" placeholder=" " value="<?php echo $firstname; ?>">
                            <label for="first_name" class="floating-label">First Name</label>
                        </div>

                        <div class="form-group">
                            <input type="text" class="form-control" name="last_name" id="last_name" placeholder=" " value="<?php echo $lastname; ?>">
                            <label for="last_name" class="floating-label">Last Name</label>
                        </div>

                        <div class="form-group">
                            <label for="expense_limit">Set Monthly Expense Limit (₹)</label>
                            <input type="number" class="form-control animated-input"
                                id="expense_limit" name="expense_limit"
                                placeholder="To set the limit amount"
                                value="<?php echo $expense_limit > 0 ? htmlspecialchars($expense_limit, ENT_QUOTES, 'UTF-8') : ''; ?>"
                                step="0.01" required
                                style="color: <?php echo $inputColor; ?>;">
                        </div>


                        <div class="form-group">
                            <input type="email" class="form-control" name="email" id="email" placeholder=" " value="<?php echo $useremail; ?>" disabled>
                            <label for="email" class="floating-label">Email</label>
                        </div>


                            <div class="form-group">
                                <div class="col-md">
                                    <br>
                                    <button class="btn btn-block btn-md btn-success" style="border-radius:0%;" name="save" type="submit">Save Changes</button>
                                </div>
                            </div>
                        </form>
                        <!--/tab-content-->

                    </div>
                    <!--/col-9-->
                </div>
            </div>
        </div>
        <!-- /#page-content-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- Bootstrap core JavaScript -->
    <script src="js/jquery.slim.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <!-- Menu Toggle Script -->
    <script>
        $("#menu-toggle").click(function(e) {
            e.preventDefault();
            $("#wrapper").toggleClass("toggled");
        });
    </script>
    <script>
        feather.replace()
    </script>
    <script type="text/javascript">
        $(document).ready(function() {


            var readURL = function(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();

                    reader.onload = function(e) {
                        $('.avatar').attr('src', e.target.result);
                    }

                    reader.readAsDataURL(input.files[0]);
                }
            }


            $(".file-upload").on('change', function() {
                readURL(this);
            });
        });
    </script>
    <script>
        $(document).ready(function () {
            $(".form-control").on("focus blur", function (e) {
                $(this).siblings("label").toggleClass("active", e.type === "focus" || this.value.length > 0);
            });
        });
    </script>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        var expenseInput = document.getElementById("expense_limit");

        function updateInputStyle() {
            if (expenseInput.value) {
                if (parseFloat(expenseInput.value) < <?php echo $total_expense; ?>) {
                    expenseInput.style.color = "red";
                } else {
                    expenseInput.style.color = "green";
                }
                expenseInput.style.fontWeight = "bold";
            }
        }

        expenseInput.addEventListener("input", updateInputStyle);
        updateInputStyle(); // Apply styles on page load if a value exists

        // Show modal only if on manage_expense.php
        if (window.location.pathname.includes("manage_expense.php")) {
            var expenseModal = document.getElementById("expenseAlertModal");
            if (expenseModal) {
                $("#expenseAlertModal").modal("show");
            }
        }
    });
    </script>

</body>

</html>