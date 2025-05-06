<?php
// Includes the database connection script.
// This file should contain your database connection logic ($connect variable)
// and the runAndCheckSQL() and showError() functions.
include_once("connect.php");

// --- PHP Data Fetching for Chart ---

// Initialize an array to hold the data for Google Charts.
// The first row will define column headers and types.
$chart_output_array = [
    [
        ['label' => 'Time', 'type' => 'datetime'], // X-axis: Time
        ['label' => 'Temperature (°C)', 'type' => 'number']  // Y-axis: Temperature
    ]
];
$chart_data_values = []; // To store the actual data rows

// SQL query to fetch data from the Readings table, including TIMESTAMP.
// Using backticks around TIMESTAMP in case it's a reserved keyword.
// Fetching in ASC order for chronological display on the chart.
$sql_chart = "SELECT `TIMESTAMP`, temp FROM Readings ORDER BY `TIMESTAMP` ASC";
$result_chart = runAndCheckSQL($connect, $sql_chart);

if ($result_chart && mysqli_num_rows($result_chart) > 0) {
    while ($row_chart = mysqli_fetch_assoc($result_chart)) {
        // Convert the SQL timestamp string to a Unix timestamp
        $timestamp = strtotime($row_chart['TIMESTAMP']);

        // Format the timestamp for Google Charts: "Date(year, month_0_indexed, day, hour, minute, second)"
        // This special string format is recognized by arrayToDataTable for datetime columns.
        $google_date_string = "Date(" . date('Y', $timestamp) . "," .
                                       (intval(date('m', $timestamp)) - 1) . "," . // Month is 0-indexed (0-11)
                                       date('d', $timestamp) . "," .
                                       date('H', $timestamp) . "," .
                                       date('i', $timestamp) . "," .
                                       date('s', $timestamp) . ")";

        // Add the processed data row to our values array
        $chart_data_values[] = [$google_date_string, (float)$row_chart['temp']];
    }
    mysqli_free_result($result_chart); // Free the result set
}

// If there are data values, add them to the main chart array
if (!empty($chart_data_values)) {
    foreach ($chart_data_values as $value_row) {
        $chart_output_array[] = $value_row;
    }
}
// If $chart_data_values is empty, $chart_output_array will only contain the header row.
// The JavaScript part will handle displaying a "no data" message in this case.

// Convert the final PHP array to a JSON string for JavaScript to use
$json_chart_data = json_encode($chart_output_array);
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
            background-color: #f8f9fa; /* Light gray background */
        }
        .container {
            margin-top: 20px; /* Add some margin to the top of the container */
        }
        h2 {
            color: #343a40; /* Darker heading color */
            margin-bottom: 20px;
        }
        .navbar {
            margin-bottom: 20px; /* Space below navbar */
        }
        /* Ensure the chart container has a defined size and style */
        #temperature_line_chart {
            width: 100%; /* Make chart responsive within its container */
            max-width: 900px; /* Optional: set a maximum width for larger screens */
            height: 500px; /* Define a fixed height for the chart */
            margin: 20px auto; /* Center the chart on the page and add some vertical margin */
            border: 1px solid #dee2e6; /* Subtle border around the chart */
            border-radius: 0.375rem; /* Rounded corners */
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075); /* Subtle shadow */
            background-color: #fff; /* White background for the chart area */
            padding: 10px; /* Add some padding inside the chart container */
        }
    </style>

    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      // Load the Visualization API and the 'corechart' and 'line' packages.
      google.charts.load('current', {'packages':['corechart', 'line']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.charts.setOnLoadCallback(drawTemperatureChart);

      // Callback function that creates and populates a data table,
      // instantiates the line chart, passes in the data, and draws it.
      function drawTemperatureChart() {
        // Use the PHP-generated JSON data.
        // This dataArray now includes a header row with type definitions.
        var dataArray = <?php echo $json_chart_data; ?>;

        // Check if dataArray has meaningful data (i.e., more than just the header row).
        if (dataArray.length <= 1) {
            // Handle no data: display a message inside the chart div.
            document.getElementById('temperature_line_chart').innerHTML = '<div class="alert alert-info text-center m-5">No temperature data available to display in the chart.</div>';
            return; // Exit the function if no data to plot.
        }

        // Create the data table from the array.
        var data = google.visualization.arrayToDataTable(dataArray);

        // Set chart options
        var options = {
          title: 'Temperature Readings Over Time',
          curveType: 'function', // Makes the line curved for a smoother appearance
          legend: { position: 'bottom' }, // Position of the legend
          hAxis: { // Horizontal axis (Time)
            title: 'Time',
            format: 'MMM d, HH:mm', // Example format: May 6, 22:00
            slantedText: true,      // Slant text to prevent overlap
            slantedTextAngle: 45    // Angle of slant
          },
          vAxis: { // Vertical axis (Temperature)
            title: 'Temperature (°C)'
          },
          series: { // Customize series color if needed
            0: { color: '#1c91c0' } // Color for the temperature line
          },
          animation: { // Add animation on chart load
            startup: true,
            duration: 1000, // Animation duration in milliseconds
            easing: 'out',  // Easing function for the animation
          },
          explorer: { // Allows zooming and panning
            actions: ['dragToZoom', 'rightClickToReset'],
            axis: 'horizontal', // Allow zooming on the horizontal axis
            keepInBounds: true,
            maxZoomIn: 8.0 // Maximum zoom level
          },
          chartArea: {width: '80%', height: '70%'}, // Adjust chart drawing area within the container
          pointSize: 5, // Size of the data points on the line
          tooltip: {isHtml: true} // Enable HTML tooltips for more customization if needed
        };

        // Instantiate and draw the chart, passing in data and options.
        var chart = new google.visualization.LineChart(document.getElementById('temperature_line_chart'));
        chart.draw(data, options);
      }

      // Add an event listener to redraw the chart on window resize to maintain responsiveness.
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
    // Optional: Close connection if connect.php doesn't handle script termination connection closing.
    // if ($connect) {
    //    mysqli_close($connect);
    // }
    ?>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>
</html>
