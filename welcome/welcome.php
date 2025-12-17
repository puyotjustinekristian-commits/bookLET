<?php session_start(); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Welcome to bookLET</title>

    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css"
      rel="stylesheet"
    />

    <link rel="stylesheet" href="../css/style.css" />

    <style>
      body {
        background-color: #193764;
        min-height: 100vh;
      }

      .login-card {
        width: 95vw;
        max-width: 1300px;
        border-radius: 20px;
        overflow: hidden;
        background-color: #ffffff;
      }

      .left-img {
        width: 100%;
        height: 100%;
        object-fit: cover;
      }

      .left-col {
        max-height: 600px;
      }
    </style>
  </head>

  <body class="d-flex justify-content-center align-items-center">
    <div class="card login-card shadow">
      <div class="row g-0">
        <div class="col-12 col-md-8 left-col">
          <img src="../images/welcome.jpg" class="left-img img-fluid" />
        </div>
        <div
          class="col-12 col-md-4 d-flex flex-column justify-content-center align-items-center p-4"
        >
          <h4 class="text-center mb-4">
            <b>Welcome to bookLET</b>
          </h4>

          <div class="w-100" style="max-width: 260px">
            <a
              class="btn btn-primary mb-3 w-100"
              data-bs-toggle="modal"
              data-bs-target="#login-modal"
            >
              Log in
            </a>

            <a
              class="btn btn-outline-secondary w-100"
              data-bs-toggle="modal"
              data-bs-target="#signin-modal"
            >
              Sign up
            </a>
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="signin-modal"
      tabindex="-1"
      aria-labelledby="ModalFormLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body">
            <button
              type="button"
              class="btn-close btn-close-white"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
            <div class="myform">
              <h1 class="text-center">Sign In</h1>
              <form action="signin.php" method="POST">
                <div class="mb-3 mt-4">
                  <label for="firstname" class="form-label" aria-required="true"
                    >First Name</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    name="firstname"
                    id="firstname"
                    autocomplete="off"
                  />
                </div>
                <div class="mb-3 mt-4">
                  <label for="surname" class="form-label" aria-required="true"
                    >Surname</label
                  >
                  <input
                    type="text"
                    class="form-control"
                    name="surname"
                    id="surname"
                    autocomplete="off"
                  />
                </div>
                <div class="mb-3 mt-4">
                  <label
                    for="signinEmail"
                    class="form-label"
                    aria-required="true"
                    >Email Address</label
                  >
                  <input
                    type="email"
                    class="form-control"
                    name="signinEmail"
                    id="signinEmail"
                    autocomplete="off"
                  />
                </div>
                <div class="mb-3">
                  <label
                    for="signinPassword"
                    class="form-label"
                    aria-required="true"
                    >Password</label
                  >
                  <input
                    type="password"
                    class="form-control"
                    name="signinPassword"
                    id="signinPassword"
                  />
                </div>
                <div class="mb-3">
                  <label
                    for="confirmPassword"
                    class="form-label"
                    aria-required="true"
                    >Confirm Password</label
                  >
                  <input
                    type="password"
                    class="form-control"
                    name="confirmPassword"
                    id="confirmPassword"
                  />
                </div>
                <button type="submit" class="btn btn-light mt-3">SUBMIT</button>
              </form>
              

              <?php if(isset($_SESSION['signup_error'])): ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?= $_SESSION['signup_error']; ?>
                </div>
                <script>
                  window.onload = function() {
                    new bootstrap.Modal(document.getElementById('signin-modal')).show();
                  };
                </script>
                <?php unset($_SESSION['signup_error']); ?>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div
      class="modal fade"
      id="login-modal"
      tabindex="-1"
      aria-labelledby="ModalFormLabel"
      aria-hidden="true"
    >
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-body">
            <button
              type="button"
              class="btn-close btn-close-white"
              data-bs-dismiss="modal"
              aria-label="Close"
            ></button>
            <div class="myform">
              <h1 class="text-center">Login Account</h1>
              <form action="login.php" method="POST">
                <div class="mb-3 mt-4">
                  <label for="LoginEmail" class="form-label"
                    >Email Address</label
                  >
                  <input
                    type="email"
                    class="form-control"
                    id="LoginEmail"
                    name="LoginEmail"
                    required
                  />
                </div>

                <div class="mb-3">
                  <label class="form-label">Password</label>
                  <input
                    type="password"
                    class="form-control"
                    id="LoginPassword"
                    name="LoginPassword"
                    required
                  />
                </div>
                <button type="submit" class="btn btn-light mt-3">LOGIN</button>
              </form>


             <?php if(isset($_SESSION['login_error'])): ?>
                <div class="alert alert-danger mt-3" role="alert">
                    <?= $_SESSION['login_error']; ?>
                </div>
                <script>
                  window.onload = function() {
                    new bootstrap.Modal(document.getElementById('login-modal')).show();
                  };
                </script>
                <?php unset($_SESSION['login_error']); ?>
             <?php endif; ?>

              <p>
                Not a member?
                <a
                  type="button"
                  data-bs-toggle="modal"
                  data-bs-target="#signin-modal"
                  >Signup now</a
                >
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
      crossorigin="anonymous"
    ></script>
  </body>
</html>
