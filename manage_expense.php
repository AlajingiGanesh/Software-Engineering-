<?php
include("session.php");

// Fetch all expenses of the user
$exp_fetched = mysqli_query($con, "SELECT * FROM expenses WHERE user_id = '$userid'");

// Fetch the user's expense limit
$result = mysqli_query($con, "SELECT expense_limit FROM users WHERE user_id='$userid'");
$row = mysqli_fetch_assoc($result);
$expense_limit = isset($row['expense_limit']) ? $row['expense_limit'] : 0;

// Calculate total expenses for the current month
$expense_query = mysqli_query($con, "SELECT SUM(expense) AS total_expense FROM expenses WHERE user_id='$userid' AND MONTH(expensedate) = MONTH(CURRENT_DATE())");
$expense_row = mysqli_fetch_assoc($expense_query);
$total_expense = isset($expense_row['total_expense']) ? $expense_row['total_expense'] : 0;

// Show a modal alert if expenses exceed the limit
if ($expense_limit > 0 && $total_expense > $expense_limit) {
    echo "<div class='modal fade' id='expenseAlertModal' tabindex='-1' role='dialog'>
            <div class='modal-dialog' role='document'>
                <div class='modal-content'>
                    <div class='modal-header'>
                        <h5 class='modal-title'>Warning!</h5>
                        <button type='button' class='close' data-dismiss='modal' aria-label='Close'>
                            <span aria-hidden='true'>&times;</span>
                        </button>
                    </div>
                    <div class='modal-body'>
                        Your total expenses (₹$total_expense) have exceeded your limit (₹$expense_limit)!
                    </div>
                    <div class='modal-footer'>
                        <button type='button' class='btn btn-secondary' data-dismiss='modal'>Close</button>
                    </div>
                </div>
            </div>
        </div>";
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

    /* Increase table size */
    .table-container {
        width: 90%;
        margin: auto;
    }

    /* Table effects */
    .table-bordered {
        box-shadow: 5px 8px 20px rgba(6, 5, 0, 0.75); /* Gold shadow */
        border: 2px solid gold; /* Optional: Adds a gold border */
        border-radius: 8px;
        overflow: hidden;
    }

    /* Hover effect */
    .table-hover tbody tr:hover {
        background-color: rgba(0, 247, 255, 0.66);
        transition: background-color 0.3s ease-in-out;
    }

    /* Table row animation */
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(-10px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .table tbody tr {
        animation: fadeIn 0.5s ease-in-out;
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
                <a href="add_expense.php" class="list-group-item list-group-item-action"><span data-feather="plus-square"></span> Add Expenses</a>
                <a href="manage_expense.php" class="list-group-item list-group-item-action sidebar-active"><span class="border px-1 py-1 d-inline-block" style="border: 2px solid blue; border-radius: 10px; font-weight: bold;">&#8377;</span></span> Manage Expenses</a>
                <a href="manage_income.php" class="list-group-item list-group-item-action"><span class="bi bi-cash-stack"></span> Manage Income</a>
            </div>
            <div class="sidebar-heading">Settings</div>
            <div class="list-group list-group-flush">
                <a href="profile.php" class="list-group-item list-group-item-action"><span data-feather="user"></span> Profile</a>
                <a href="logout.php" class="list-group-item list-group-item-action"><span data-feather="power"></span> Logout</a>
            </div>
        </div>
        <!-- /#sidebar-wrapper -->

        <!-- Page Content -->
        <div id="page-content-wrapper">

            <nav class="navbar navbar-expand-lg navbar-light border-bottom">
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
                                <a class="dropdown-item" href="#">Your Profile</a>
                                <a class="dropdown-item" href="#">Edit Profile</a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="logout.php">Logout</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>

            <div class="container-fluid">
                <h3 class="mt-4 text-center">Manage Expenses</h3>
                <?php if (isset($_GET['deleted']) && $_GET['deleted'] == "success") { ?>
                    <div id="delete-alert" class="alert alert-success text-center">
                        Expense deleted successfully!
                    </div>
                <?php } ?>
                <hr>
                <div class="row justify-content-center">

                    <div class="table-container">
                        <table class="table table-hover table-bordered">
                            <thead>
                                <tr class="text-center">
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Amount</th>
                                    <th>Expense Category</th>
                                    <th colspan="2">Action</th>
                                </tr>
                            </thead>

                            <?php $count=1; while ($row = mysqli_fetch_array($exp_fetched)) { ?>
                                <tr>
                                    <td><?php echo $count;?></td>
                                    <td><?php echo $row['expensedate']; ?></td>
                                    <td><?php echo '₹'.$row['expense']; ?></td>
                                    <td><?php echo $row['expensecategory']; ?></td>
                                    <td class="text-center">
                                        <a href="add_expense.php?edit=<?php echo $row['expense_id']; ?>" class="btn btn-primary btn-sm" style="border-radius:0%;">Edit</a>
                                    </td>
                                    <td class="text-center">
                                        <a href="add_expense.php?delete=<?php echo $row['expense_id']; ?>" 
                                        class="btn btn-danger btn-sm delete-btn" 
                                        style="border-radius:0%;" 
                                        onclick="return confirm('Are you sure you want to delete this expense?');">
                                        Delete
                                        </a>
                                    </td>

                                </tr>
                            <?php $count++; } ?>
                        </table>
                    </div>

                </div>
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
    feather.replace(); // Ensure feather icons load properly

    setTimeout(function() {
        let alertBox = document.getElementById("delete-alert");
        if (alertBox) {
            alertBox.style.display = "none";
        }
    }, 3000);

    document.addEventListener("click", function() {
        let alertBox = document.getElementById("delete-alert");
        if (alertBox) {
            alertBox.style.display = "none";
        }
    });
    </script>
    <script>
    // Show the modal when the page loads if the modal exists
    document.addEventListener("DOMContentLoaded", function() {
        var expenseModal = document.getElementById("expenseAlertModal");
        if (expenseModal) {
            $("#expenseAlertModal").modal("show");
        }
    });
    </script>


</body>
</html>