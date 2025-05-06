<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IoT Web Interface</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <link rel="stylesheet" href="BackgroundStyle.css">
  </head>
  <body>
    <div class="container">
      <div class="row justify-content-center align-items-center vh-100">
        <div class="col-md-5">
          <div class="login-box">
            <h2 class="text-center text-black">Login</h2>
            <form method="POST" action="auth.php">
            <div class="mb-3">
                <label for="username" class="form-label text-black">Username:</label>
                <input type="text" class="form-control" id="username" placeholder="Enter username" name="txtUsername" required>
              </div>
              <div class="mb-3">
                <label for="pwd" class="form-label text-black">Password:</label>
                <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="txtPass" required>
              </div>
              <div class="d-grid gap-2 mt-4"> <button type="submit" class="btn btn-outline-dark btn-block">Login</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  </body>
</html>
