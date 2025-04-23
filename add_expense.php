<?php
include("session.php");
$update = false;
$del = false;
$expenseamount = "";
$expensedate = date("Y-m-d");
$expensecategory = "Entertainment";

if (isset($_POST['add'])) {
    $expenseamount = $_POST['expenseamount'];

    if ($expenseamount <= 0) {
    echo "<script>
            window.onload = function() {
                showPopup('Expense amount must be greater than zero!');
            };
            </script>";
    exit(); // Stop further execution
}


    $expensedate = $_POST['expensedate'];
    $expensecategory = ($_POST['expensecategory'] == "Others") ? mysqli_real_escape_string($con, $_POST['other_expensecategory']) : $_POST['expensecategory'];

    $sql = "INSERT INTO expenses (user_id, expense, expensedate, expensecategory) VALUES ('$userid', '$expenseamount', '$expensedate', '$expensecategory')";
    mysqli_query($con, $sql) or die("Something Went Wrong!");
    header('location: add_expense.php');
}

if (isset($_POST['update'])) {
    $id = $_POST['expense_id'];  // Use POST instead of GET
    $expenseamount = $_POST['expenseamount'];
    $expensedate = $_POST['expensedate'];
    $expensecategory = ($_POST['expensecategory'] == "Others") ? mysqli_real_escape_string($con, $_POST['other_expensecategory']) : $_POST['expensecategory'];

    $sql = "UPDATE expenses SET expense='$expenseamount', expensedate='$expensedate', expensecategory='$expensecategory' WHERE user_id='$userid' AND expense_id='$id'";
    mysqli_query($con, $sql) or die("Error updating record: " . mysqli_error($con));
    header('location: manage_expense.php');
}

if (isset($_GET['delete'])) {
    $expense_id = $_GET['delete'];

    // Prevent SQL Injection
    $expense_id = mysqli_real_escape_string($con, $expense_id);

    // Execute delete query
    $query = "DELETE FROM expenses WHERE expense_id = '$expense_id' AND user_id = '$userid'";
    $result = mysqli_query($con, $query);

    if ($result) {
        // Redirect back to manage_expense.php with success message
        header("Location: manage_expense.php?deleted=success");
        exit();
    } else {
        echo "Error deleting expense: " . mysqli_error($con);
    }
}

if (isset($_GET['edit'])) {
    $id = $_GET['edit'];
    $update = true;
    $record = mysqli_query($con, "SELECT * FROM expenses WHERE user_id='$userid' AND expense_id=$id");
    if (mysqli_num_rows($record) == 1) {
        $n = mysqli_fetch_array($record);
        $expenseamount = $n['expense'];
        $expensedate = $n['expensedate'];
        $expensecategory = $n['expensecategory'];
    } else {
        echo ("WARNING: AUTHORIZATION ERROR: Trying to Access Unauthorized data");
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Expense Manager - Add Expenses</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="js/feather.min.js"></script>
    <style>
        .list-group-item {
            transition: transform 0.2s ease-in-out, color 0.2s ease-in-out;
        }
        .list-group-item:hover,
        .list-group-item.sidebar-active:hover {
            transform: translateX(5px);
            color: rgb(255, 0, 0) !important;
        }

        @keyframes fadeInUp {
            from {
            opacity: 0;
            transform: translateY(20px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
            }
        }

        @keyframes textPulse {
            0%, 100% {
            color: #000;
        }
        50% {
            color: red;
            }   
        }

        h3, label {
            animation: fadeInUp 0.8s ease-in-out;
        }

        input[type="text"], input[type="number"], input[type="date"] {
            transition: all 0.3s ease-in-out;
        }

        input[type="text"]:focus, input[type="number"]:focus, input[type="date"]:focus {
            transform: scale(1.05);
            border-color: red;
        }

        button {
            transition: all 0.3s ease-in-out;
        }

        button:hover {
            transform: scale(1.1);
        }

        .suggestion {
            font-size: 0.9rem;
            color: grey;
            animation: textPulse 1.5s infinite;
        }

    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="border-right" id="sidebar-wrapper">
            <div class="user text-center">
                <img class="img img-fluid rounded-circle" src="<?php echo $userprofile ?>" width="100">
                <h5><?php echo $username ?></h5>
                <p><?php echo $useremail ?></p>
            </div>
            <div class="sidebar-heading">Management</div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action"><span data-feather="home"></span> Dashboard</a>
                <a href="add_expense.php" class="list-group-item list-group-item-action sidebar-active"><span data-feather="plus-square"></span> Add Expenses</a>
                <a href="manage_expense.php" class="list-group-item list-group-item-action"><span class="border px-1 py-1 d-inline-block" style="border: 2px solid blue; border-radius: 10px; font-weight: bold;">&#8377;</span>
                </span> Manage Expenses</a>
                <a href="manage_income.php" class="list-group-item list-group-item-action"><span class="bi bi-cash-stack"></span> Manage Income</a>
            </div>
            <div class="sidebar-heading">Settings</div>
            <div class="list-group list-group-flush">
                <a href="profile.php" class="list-group-item list-group-item-action"><span data-feather="user"></span> Profile</a>
                <a href="logout.php" class="list-group-item list-group-item-action"><span data-feather="power"></span> Logout</a>
            </div>
        </div>
        <!-- Page Content -->
        <div id="page-content-wrapper">
            <nav class="navbar navbar-expand-lg navbar-light border-bottom">
                <button class="toggler" type="button" id="menu-toggle"><span data-feather="menu"></span></button>
            </nav>
            <div class="container">
                <h3 class="mt-4 text-center">Add Your Daily Expenses</h3>
                <hr>
                <form action="add_expense.php<?php if (isset($_GET['edit'])) echo '?edit='.$_GET['edit']; ?>" method="POST" onsubmit="return validateForm()">

                <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><b>Enter Amount (₹)</b></label>
                    <div class="col-md-6">
                    <?php if ($update): ?>
                            <input type="hidden" name="expense_id" value="<?php echo $_GET['edit']; ?>">
                        <?php endif; ?>

                        <input type="number" class="form-control" value="<?php echo $expenseamount; ?>" name="expenseamount" id="expenseamount" required>
                        <span id="error-message" style="color: red; font-size: 14px; display: none; margin-left: 10px;"></span>
                    </div>
                </div>
                
                <div class="form-group row">
                    <label class="col-sm-6 col-form-label"><b>Date</b></label>
                    <div class="col-md-6">
                        <input type="date" class="form-control" value="<?php echo $expensedate; ?>" name="expensedate" required>
                    </div>
                </div>

                <fieldset class="form-group">
                    <div class="row">
                        <legend class="col-form-label col-sm-6 pt-0"><b>Category <span style="color: red;">*</span></b></legend>
                        <div class="col-md">
                            <?php
                            $categories = ["Entertainment", "Food", "Bills & Recharges", "Medicine", "Others"];
                            foreach ($categories as $cat) {
                                echo '<div class="form-check">
                                        <input class="form-check-input" type="radio" name="expensecategory" value="'.$cat.'" required onclick="toggleOtherField(this)">
                                        <label class="form-check-label">'.$cat.'</label>
                                    </div>';
                            }
                            ?>
                            <div id="otherCategoryField" style="display: none; margin-top: 10px;">
                                <input type="text" class="form-control" name="other_expensecategory" id="other_expensecategory" placeholder="Enter custom category">
                                <span id="other-error-message" style="color: red; font-size: 14px; display: none; margin-left: 10px;"></span>
                            </div>
                        </div>
                    </div>
                </fieldset>



                <div class="form-group row">
                <div class="col-md-12 text-right">
                    <?php if ($update): ?>
                        <button type="submit" name="update" class="btn btn-primary btn-block">Update Expense</button>
                    <?php else: ?>
                        <button type="submit" name="add" class="btn btn-success btn-block">Add Expense</button>
                    <?php endif; ?>
                </div>
            </div>

            </form>

            </div>
        </div>
    </div>
    <div id="popupModal" style="display: none; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); background: white; padding: 20px; border-radius: 10px; box-shadow: 0px 4px 6px rgba(0,0,0,0.1); z-index: 1000;">
    <p id="popupMessage" style="margin: 0; padding: 10px 0; font-size: 16px;"></p>
    <button onclick="closePopup()" style="background: red; color: white; border: none; padding: 5px 10px; border-radius: 5px; cursor: pointer;">OK</button>
    </div>


    <script>
    function validateForm() {
        var expenseAmount = document.getElementById("expenseamount").value;
        var errorMessage = document.getElementById("error-message");
        var isValid = true; // Flag to track form validity

        // Validate Expense Amount
        if (expenseAmount <= 0 || expenseAmount === "") {
            errorMessage.innerText = "Amount must be greater than zero!";
            errorMessage.style.display = "inline";
            isValid = false;
        } else {
            errorMessage.style.display = "none"; // Hide error if valid
        }

        // Validate "Other" category input
        var selectedCategory = document.querySelector('input[name="expensecategory"]:checked');
        if (selectedCategory && selectedCategory.value === "Others") {
            var otherInput = document.getElementById("other_expensecategory");
            var otherErrorMessage = document.getElementById("other-error-message");

            if (otherInput.value.trim() === "") {
                otherErrorMessage.innerText = "Please enter an item name for the 'Other' category!";
                otherErrorMessage.style.display = "inline";
                isValid = false;
            } else {
                otherErrorMessage.style.display = "none";
            }
        }

        return isValid; // Return false if any validation fails
    }

    // Add event listener for real-time validation while typing
    document.getElementById("expenseamount").addEventListener("input", function() {
        var errorMessage = document.getElementById("error-message");
        if (this.value <= 0) {
            errorMessage.innerText = "Amount must be greater than zero!";
            errorMessage.style.display = "inline";
        } else {
            errorMessage.style.display = "none";
        }
    });
    </script>

    <script>
    function toggleOtherField() {
        var selectedCategory = document.querySelector('input[name="expensecategory"]:checked');
        var otherField = document.getElementById("otherCategoryField");
        var otherInput = document.getElementById("other_expensecategory");

        if (selectedCategory && selectedCategory.value === "Others") {
            otherField.style.display = "block";
            otherInput.setAttribute("required", "required");
        } else {
            otherField.style.display = "none";
            otherInput.removeAttribute("required");
        }
    }

    // Ensure the toggle function runs when the page loads (for edit mode)
    document.addEventListener("DOMContentLoaded", function() {
        toggleOtherField();
    });

    // Attach event listeners to radio buttons
    document.querySelectorAll('input[name="expensecategory"]').forEach(function(radio) {
        radio.addEventListener("change", toggleOtherField);
    });
    </script>

    <script>
        feather.replace();
    </script>


</body>
</html>