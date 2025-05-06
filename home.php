<?php
// Includes the database connection script.
// This file should contain your database connection logic ($connect variable)
// and the runAndCheckSQL() and showError() functions.
include_once("connect.php");

// --- PHP Data Fetching for Chart ---
$chart_data = array();
// Add column headers for the Google Chart
$chart_data[] = ['Reading ID / Time', 'Temperature (°C)']; // X-axis, Y-axis

// SQL query to fetch data from the Readings table
// Fetching in ASC order is usually better for time-series charts
$sql_chart = "SELECT readingID, temp FROM Readings ORDER BY readingID ASC";
$result_chart = runAndCheckSQL($connect, $sql_chart);

if ($result_chart && mysqli_num_rows($result_chart) > 0) {
    while ($row_chart = mysqli_fetch_assoc($result_chart)) {
        // Add data rows: ReadingID (can be treated as a category or number) and Temperature
        // Ensure 'temp' is a number for the chart
        $chart_data[] = [$row_chart['readingID'], (float)$row_chart['temp']];
    }
    mysqli_free_result($result_chart);
} else {
    // Add a default data point if no data is found to prevent chart errors
    // and to show an empty chart. Or you can handle this in JS.
    $chart_data[] = ["No Data", 0];
}

// Convert PHP array to JSON for JavaScript
$json_chart_data = json_encode($chart_data);
// --- End of PHP Data Fetching ---
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Climate Control System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="BackgroundStyle.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 20px;
        }
        h2 {
            color: #343a40;
            margin-bottom: 20px;
        }
        .navbar {
            margin-bottom: 20px;
        }
        /* Ensure the chart container has a defined size */
        #temperature_line_chart {
            width: 100%; /* Make chart responsive */
            max-width: 900px; /* Optional: set a max width */
            height: 500px;
            margin: 0 auto; /* Center the chart */
            border: 1px solid #dee2e6; /* Optional: add a border */
            border-radius: 0.375rem; /* Rounded corners */
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            background-color: #fff; /* White background for the chart area */
        }
    </style>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      // Load the Visualization API and the corechart package.
      google.charts.load('current', {'packages':['corechart', 'line']}); // Added 'line' for explicit line chart loading

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawTemperatureChart);

      // Callback that creates and populates a data table,
      // instantiates the line chart, passes in the data and
      // draws it.
      function drawTemperatureChart() {
        // Use the PHP-generated JSON data
        var dataArray = <?php echo $json_chart_data; ?>;

        // Check if dataArray has meaningful data (beyond just headers or the "No Data" placeholder)
        if (dataArray.length <= 1 || (dataArray.length === 2 && dataArray[1][0] === "No Data")) {
            // Handle no data: display a message or an empty chart gracefully
            document.getElementById('temperature_line_chart').innerHTML = '<div class="alert alert-info text-center m-5">No temperature data available to display in the chart.</div>';
            return; // Exit the function if no data
        }

        var data = google.visualization.arrayToDataTable(dataArray);

        var options = {
          title: 'Temperature Readings Over Time',
          curveType: 'function', // Makes the line curved
          legend: { position: 'bottom' },
          hAxis: {
            title: 'Reading Sequence / Time', // Label for X-axis
            // If 'readingID' is not a timestamp, it will be treated as categories/numbers.
            // For actual time-series, you'd format this axis as 'datetime'.
          },
          vAxis: {
            title: 'Temperature (°C)' // Label for Y-axis
          },
          series: { // Customize series color if needed
            0: { color: '#1c91c0' } // Color for the first series (temperature)
          },
          animation: { // Add animation on load
            startup: true,
            duration: 1000,
            easing: 'out',
          },
          explorer: { // Allows zooming and panning
            actions: ['dragToZoom', 'rightClickToReset'],
            axis: 'horizontal',
            keepInBounds: true,
            maxZoomIn: 4.0
          },
          chartArea: {width: '80%', height: '70%'}, // Adjust chart area
          pointSize: 5 // Size of the data points on the line
        };

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.LineChart(document.getElementById('temperature_line_chart'));
        chart.draw(data, options);
      }

      // Redraw chart on window resize for responsiveness
      window.addEventListener('resize', drawTemperatureChart);

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
    <h2>Live Temperature Readings Chart</h2>

    <div id="temperature_line_chart">
        <div class="alert alert-warning text-center m-5" role="alert">
            Chart is loading... If it doesn't appear, please ensure JavaScript is enabled and check the console for errors.
        </div>
    </div>

    <?php
    // The HTML table code has been removed from here.
    // You can add other content below the chart if needed.

    // Optional: Close connection if not handled by script termination
    // if ($connect) {
    // mysqli_close($connect);
    // }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
