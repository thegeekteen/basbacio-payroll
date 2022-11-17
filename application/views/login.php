<!DOCTYPE html>
<html lang="en">

<head>

  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title>EZ-Payroll Login</title>

  <!-- Custom fonts for this template-->

  <!-- Custom styles for this template-->
  <link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="<?php echo base_url();?>assets/css/sb-admin-2.min.css" rel="stylesheet">
   <style>
    @font-face {
        font-family: "glyphicons";
        src: url('<?php echo base_url();?>assets/icons/glyphicons-basic-regular.otf') format('opentype');
    }

    .glyphicons {
      display:inline;
      font-family:glyphicons;
      vertical-align:middle;
      margin-right:3px;
    }

    [mlink=*] {
      cursor:pointer;
    }
  </style>


</head>

<body class="bg-gradient-primary">

  <div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

      <div class="col-xl-10 col-lg-12 col-md-9">

        <div class="card o-hidden border-0 shadow-lg my-5">
          <div class="card-body p-0">
            <!-- Nested Row within Card Body -->
            <div class="row">
              <div class="col-lg-12">
                <div class="p-5">
                  <div class="text-center">
                    <h1 class="h4 text-gray-900 mb-4"><span class="glyphicons"></span> EZ-Payroll <span style="font-size:12px;color:gray;">v1.0</span></h1>
                  </div>
                  <?php 
                    if ($this->session->flashdata("errorMessage") != null) {
                         $errors = $this->session->flashdata("errorMessage");
                         if (count($errors) > 0) {
                             echo '<div class="alert alert-danger" style="font-size:14px;">';
                             foreach ($errors as $error) {
                                echo "<b>Error:</b> " . $error . "<br />";
                             }
                            echo '</div>';
                         }
                     }
                    ?>
                
                  <form class="user" submit="" method="POST">
                    <div class="form-group">
                      <input type="text" name="username" value="<?php echo $this->input->post("username", true);?>" class="form-control form-control-user" id="exampleInputEmail" aria-describedby="emailHelp" placeholder="Enter Username">
                    </div>
                    <div class="form-group">
                      <input type="password" name="password" class="form-control form-control-user" id="exampleInputPassword" placeholder="Password">
                    </div>
                    <div class="form-group">
                      <!-- <div class="custom-control custom-checkbox small">
                        <input type="checkbox" class="custom-control-input" id="customCheck">
                        <label class="custom-control-label" for="customCheck">Remember Me</label>
                      </div> -->
                    </div>
                    <button type="submit" class="btn btn-primary btn-user btn-block">
                      Login
                    </button>
                  
                  </form>
                  <hr>
                  <div class="text-center">
                    <span class="small" href="register.html">&copy; <?php echo date("Y");?> EZ-Payroll | GL Abaño Systems</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

      </div>

    </div>

  </div>

</body>

</html>
