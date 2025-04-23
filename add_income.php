<?php
include("session.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $income_amount = (float)$_POST['income_amount']; // Ensure numeric input

    if ($income_amount > 0) {
        $query = "INSERT INTO income (user_id, amount) VALUES ('$userid', '$income_amount')";
        if (mysqli_query($con, $query)) {
            echo "Income added successfully.";
            header("Location: index.php"); // Redirect after success
            exit();
        } else {
            echo "Error: " . mysqli_error($con);
        }
    } else {
        echo "Invalid income amount.";
    }
} else {
    echo "Invalid request.";
}
?>
