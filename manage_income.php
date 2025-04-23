<?php
include("session.php");

// Fetch income and expenses
$income_fetched = mysqli_query($con, "SELECT * FROM income WHERE user_id = '$userid'");
$expense_fetched = mysqli_query($con, "SELECT SUM(expense) AS total_expense FROM expenses WHERE user_id = '$userid'");

// Calculate totals
$total_income = 0;
while ($row = mysqli_fetch_array($income_fetched)) {
    $total_income += (float)$row['amount']; // Ensure numerical values
}

$expense_row = mysqli_fetch_array($expense_fetched);
$total_expense = (float)($expense_row['total_expense'] ?? 0); // Default to 0 if NULL
$remaining_balance = $total_income - $total_expense;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Income Manager - Dashboard</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <script src="js/feather.min.js"></script>
    <script src="js/Chart.min.js"></script>
    <script src="js/anime.min.js"></script>
    <style>
        .fadeIn {
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .list-group-item {
            transition: transform 0.2s ease-in-out, color 0.2s ease-in-out;
        }
        .list-group-item:hover,
        .list-group-item.sidebar-active:hover {
            transform: translateX(5px);
            color: rgb(255, 0, 0) !important;
        }
    </style>
</head>
<body>
    
    <div class="d-flex" id="wrapper">
        <!-- Sidebar -->
        <div class="border-right" id="sidebar-wrapper">
            <div class="user text-center">
                <img class="img img-fluid rounded-circle" src="<?php echo $userprofile ?>" width="120">
                <h5><?php echo htmlspecialchars($username); ?></h5>
                <p><?php echo htmlspecialchars($useremail); ?></p>
            </div>
            <div class="sidebar-heading">Management</div>
            <div class="list-group list-group-flush">
                <a href="index.php" class="list-group-item list-group-item-action"><span data-feather="home"></span> Dashboard</a>
                <a href="add_expense.php" class="list-group-item list-group-item-action "><span data-feather="plus-square"></span> Add Expenses</a>
                <a href="manage_expense.php" class="list-group-item list-group-item-action"><span class="border px-1 py-1 d-inline-block" style="border: 2px solid blue; border-radius: 10px; font-weight: bold;">&#8377;</span></span> Manage Expenses</a>
                <a href="manage_income.php" class="list-group-item list-group-item-action sidebar-active"><span data-feather="trending-up"></span> Manage Income</a>
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
            <div class="container-fluid">
                <h3 class="mt-4 text-center">Manage Income</h3>
                <hr>
                <div class="row text-center">
                    <div class="col-md-4 fadeIn">
                        <div class="alert alert-primary">
                            <h5>Total Income:</h5> 
                            <h3>&#8377;<?php echo number_format($total_income, 2); ?></h3>
                        </div>
                    </div>
                    <div class="col-md-4 fadeIn">
                        <div class="alert alert-danger">
                            <h5>Total Expenses:</h5> 
                            <h3>&#8377;<?php echo number_format($total_expense, 2); ?></h3>
                        </div>
                    </div>
                    <div class="col-md-4 fadeIn">
                        <div class="alert alert-success">
                            <h5>Remaining Balance:</h5> 
                            <h3>&#8377;<?php echo number_format($remaining_balance, 2); ?></h3>
                        </div>
                    </div>
                </div>
                
                <!-- Income Input Form -->
                <div class="row justify-content-center fadeIn">
                    <div class="col-md-6">
                        <form method="post" action="add_income.php">
                            <div class="input-group mb-3">
                            <input type="number" class="form-control" name="income_amount" placeholder="Enter amount" required min="1">
                                <div class="input-group-append">
                                    <button class="btn btn-success" type="submit">Add Income</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="text-center mt-3">
                    <a href="edit_income.php" class="btn btn-warning">Edit Income</a>
                </div>


                <!-- Income Chart -->
                <canvas id="incomeChart" class="fadeIn"></canvas>
            </div>
        </div>
    </div>

    <script>
        feather.replace();
        
        let ctx = document.getElementById("incomeChart").getContext("2d");
        let incomeChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: ["Total Income", "Total Expenses", "Remaining Balance"],
                datasets: [{
                    label: 'Financial Overview',
                    data: [<?php echo $total_income; ?>, <?php echo $total_expense; ?>, <?php echo $remaining_balance; ?>],
                    backgroundColor: ['blue', 'red', 'green']
                }]
            },
            options: {
                responsive: true,
                animation: {
                    animateRotate: true,
                    animateScale: true,
                    duration: 2000
                }
            }
        });
    </script>
</body>
</html>
