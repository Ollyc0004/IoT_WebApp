<?php
// Includes the database connection script.
// This file should contain your database connection logic ($connect variable)
// and the runAndCheckSQL() and showError() functions.
include_once("connect.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Climate Control System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="BackgroundStyle.css">

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {'packages':['corechart']});
      google.charts.setOnLoadCallback(drawChart);

      function drawChart() {
        var data = google.visualization.arrayToDataTable([
          ['Year', 'Sales', 'Expenses'],
          ['2004',  1000,      400],
          ['2005',  1170,      460],
          ['2006',  660,       1120],
          ['2007',  1030,      540]
        ]);

        var options = {
          title: 'Company Performance',
          curveType: 'function',
          legend: { position: 'bottom' }
        };

        var chart = new google.visualization.LineChart(document.getElementById('curve_chart'));

        chart.draw(data, options);
      }
    </script>
</head>
<body>

<nav class="navbar navbar-expand-lg bg-body-tertiary rounded-bottom">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Climate Control System</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
      <div class="navbar-nav">
        <a class="nav-link active" aria-current="page" href="home.php">Home</a>
        <a class="nav-link" href="history.php">History</a>
        <a class="nav-link" href="settings.php">Settings</a>
      </div>
    </div>
  </div>
</nav>

<div class="container">
    <h2>Live Temperature Readings</h2>

    <?php
    // SQL query to fetch data from the Readings table
    $sql = "SELECT readingID, temp FROM Readings ORDER BY readingID DESC"; // Fetches all readings, ordered by ID descending (latest first)

    // Execute the query using the function from connect.php
    // $connect should be your mysqli connection variable from connect.php
    $result = runAndCheckSQL($connect, $sql);

    if ($result) {
        // Check if there are any rows returned
        if (mysqli_num_rows($result) > 0) {
            echo '<div class="table-responsive rounded">'; // Added for responsiveness and to ensure border-radius applies
            echo '<table class="table table-striped table-hover table-bordered">'; // Bootstrap table classes
            echo '<thead class="table-light">'; // Light header
            echo '<tr>';
            echo '<th scope="col">Temperature (°C)</th>';
            echo '</tr>';
            echo '</thead>';
            echo '<tbody>';

            // Loop through each row of the result set
            while ($row = mysqli_fetch_assoc($result)) {
                echo '<tr>';
                // Sanitize output to prevent XSS, though ReadingsID is likely an integer
                echo '<td>' . htmlspecialchars($row['temp']) . '</td>';
                echo '</tr>';
            }

            echo '</tbody>';
            echo '</table>';
            echo '</div>';
        } else {
            // If no rows are returned
            echo '<div class="alert alert-info" role="alert">';
            echo 'No temperature readings found in the database.';
            echo '</div>';
        }
        // Free the result set
        mysqli_free_result($result);
    } else {
        // If the query failed, runAndCheckSQL would have already called showError() and died.
        // However, as a fallback or if runAndCheckSQL is modified:
        echo '<div class="alert alert-danger" role="alert">';
        echo 'Error fetching data from the database. Please check the connection and query.';
        echo '</div>';
    }

    // It's good practice to close the database connection when it's no longer needed,
    // though PHP often handles this at the end of script execution.
    // mysqli_close($connect); // Uncomment if your connect.php doesn't handle closing.
    ?>
</div>

<div id="curve_chart" style="width: 900px; height: 500px"></div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
