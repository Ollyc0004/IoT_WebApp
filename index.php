<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IoT Web Interface</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <style>
      /* Set the background color of the entire page to baby blue */
      body {
        background-color: #add8e6; /* Baby blue color */
        font-family: 'Inter', sans-serif; /* Using Inter font as a default nice font */
      }
      .login-box {
        max-width: 450px; /* Adjusted max-width for a slightly more compact login box */
        margin: auto;
        background-color: #ffffff; /* White coloring for the login box */
        padding: 30px; /* Increased padding for better spacing */
        border-radius: 15px; /* Slightly more rounded corners */
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); /* Enhanced box shadow for depth */
      }
      .login-box h2 {
        margin-bottom: 25px; /* Added margin below the title */
      }
      .btn-outline-dark {
        border-color: #343a40;
        color: #343a40;
      }
      .btn-outline-dark:hover {
        background-color: #343a40;
        color: #ffffff;
      }
      .form-label {
        margin-bottom: .5rem; /* Default Bootstrap margin, ensuring consistency */
      }
      .form-control {
        margin-bottom: 1rem; /* Added some space below form controls for better separation */
      }
    </style>
  </head>
  <body>
    <div class="container">
      <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-5">
          <div class="login-box">
            <h2 class="text-center text-black">Login</h2>
            <form method="POST" action="Auth.php">
              <div class="mb-3">
                <label for="email" class="form-label text-black">Email:</label>
                <input type="email" class="form-control" id="email" placeholder="Enter email" name="txtEmail" required>
              </div>
              <div class="mb-3">
                <label for="pwd" class="form-label text-black">Password:</label>
                <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="txtPass" required>
              </div>
              <div class="d-grid gap-2 mt-4"> <button type="submit" class="btn btn-outline-dark btn-block">Login</button>
              </div>
            </form>
            <p class="text-center text-black mt-3">No account? <a href="register.php" class="text-black">Please register here</a></p>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>
