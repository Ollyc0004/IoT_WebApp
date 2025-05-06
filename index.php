<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>IoT Web Interface</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <style>
        .login-box {
            max-width: 1000px; /*Max width of login box */
            margin: auto;
            background-color: #ffffff; /*White colouring*/
            padding: 20px;
            border-radius: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
    </style>
  </head>
  <body>
    <<div class="container">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-5">
            <div class="login-box">
            <h2 class="text-black">Login</h2>    
            <form method="POST" action="Auth.php">
                <!--Email field for entering users email-->
                    <div class="mb-3">
                        <label for="email" class="form-label text-black">Email:</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter email" name="txtEmail">
                    </div>
                    <!--Password field for entering users password-->
                    <div class="mb-3">
                        <label for="pwd" class="form-label text-black">Password:</label>
                        <input type="password" class="form-control" id="pwd" placeholder="Enter password" name="txtPass">
                    </div>
                    <!--Google Captcha implementation-->
                    <div class="row justify-content-center">
                        <div class="col-md-9">
                            <div class="g-recaptcha" data-sitekey="6Ld9yqUpAAAAAGRg3AQPOxqGGps_hw1fHOTSqkfI"></div>
                        </div>
                    </div>
                    <!--Button for submitting details entered into the form to be authenticated-->
                    <div class="row justify-content-center mt-3">
                        <div class="col-md-6">
                            <div class="form-group text-center mb-3">
                                <button type="submit" class="btn btn-outline-dark btn-block">Login</button>
                            </div>
                        </div>
                    </div>
                </form>
                <!--Line of text which refers the user to the register page if they don't have an account-->
                <p class="text-center text-black">No account? <a href="register.php" class="text-black">Please register here</a></p>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>
  </body>
</html>