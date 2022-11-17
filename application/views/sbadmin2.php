<!DOCTYPE html>
<html lang="en">

<head>
  
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">

  <title><?php echo $pageTitle?></title>
  <link href="<?php echo base_url();?>assets/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
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

    .unselectable {
        user-select: none;
        -moz-user-select: none;
        -khtml-user-select: none;
        -webkit-user-select: none;
        -o-user-select: none;
    } 

    .spinner{
      position: absolute;
      height: 100px;
      width: 100px;
      top: 50%;
      left: 50%;
      margin-left: -50px;
      margin-top: -50px;
    }
  </style>
  <?php echo $pageStyles;?>

</head>

<body id="page-top">

  <div  id="loading" class="spinner spinner-border text-<?php echo $ClassName;?>" role="status">
  <span class="sr-only">Loading...</span>
</div>

  <!-- Page Wrapper -->
  <div id="wrapper" style="display:none;">

    <!-- Sidebar -->
    <ul id="accordionSidebar" class="navbar-nav bg-gradient-<?php echo $ClassName;?> sidebar sidebar-dark accordion">

      <!-- Sidebar - Brand -->
      <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?php echo base_url();?>dashboard">
        <div class="sidebar-brand-icon">
          <span style='font-family:glyphicons;margin-right:2px;font-size:30px;'></span>
        </div>
        <div class="sidebar-brand-text mx-0" style="font-size:12px;font-style:lighter;"><span><?php echo $BusinessName;?></span></div>
      </a>

      <!-- Divider -->
      <hr class="sidebar-divider my-0">

      <!-- Nav Item - Dashboard -->
      <li class="nav-item" mlink="dashboard">
        <a class="nav-link" href="<?php echo base_url();?>dashboard">
          <!-- <i class="fas fa-fw fa-tachometer-alt"></i> -->
          <span><span class="glyphicons"></span> Dashboard</span></a>
      </li>
       <li class="nav-item" mlink="projects">
        <a class="nav-link" href="<?php echo base_url();?>projects">
            <!-- <i class="fas fa-fw fa-tachometer-alt"></i> -->
          <span><span class="glyphicons"></span> Projects</span></a>
      </li>
      <li class="nav-item" mlink="employees">
        <a class="nav-link" href="<?php echo base_url();?>employees">
           <!-- <i class="fas fa-fw fa-tachometer-alt"></i> -->
          <span><span class="glyphicons"></span> Employees & Payslips</span></a>
      </li>

     <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
          <i class="glyphicons" style="font-style:normal;"></i>
          <span>Plates &amp; Vouchers</span>
        </a>
        <div id="collapseTwo" class="collapse hide" aria-labelledby="headingTwo" data-parent="#accordionSidebar" style="">
          <div class="bg-white py-2 collapse-inner rounded">
            <h6 class="collapse-header">PLATES &amp; VOUCHERS:</h6>
            <a class="collapse-item" href="<?php echo base_url();?>plates">License Plates</a>
            <a class="collapse-item" href="<?php echo base_url();?>receipts">Vouchers</a>
          </div>
        </div>
      </li>

      <li class="nav-item" mlink="config">
        <a class="nav-link" href="<?php echo base_url();?>config">
           <!-- <i class="fas fa-fw fa-tachometer-alt"></i> -->
          <span><span class="glyphicons"></span> Configuration</span></a>
      </li>

      <li class="nav-item" mlink="about">
        <a class="nav-link" href="<?php echo base_url();?>about">
           <!-- <i class="fas fa-fw fa-tachometer-alt"></i> -->
          <span><span class="glyphicons"></span> About</span></a>
      </li>

      <!-- Heading -->
     

      <!-- Divider -->
      <hr class="sidebar-divider d-none d-md-block">

      <!-- Sidebar Toggler (Sidebar) -->
      <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
      </div>

    </ul>
    <!-- End of Sidebar -->

    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">

      <!-- Main Content -->
      <div id="content">

        <!-- Topbar -->
        <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

          <!-- Sidebar Toggle (Topbar) -->
          <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
            <i class="fa fa-bars"></i>
          </button>

          <!-- Topbar Navbar -->
          <ul class="navbar-nav ml-auto">

            <div class="topbar-divider d-none d-sm-block"></div>

            <!-- Nav Item - User Information -->
            <li class="nav-item dropdown no-arrow">
              <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <span class="mr-2 d-none d-lg-inline text-gray-600 small">EZ-Payroll</span>
                <span class="fa fa-caret-down"></span>
              </a>
              <!-- Dropdown - User Information -->
              <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in" aria-labelledby="userDropdown">
                <a class="dropdown-item" href="<?php echo base_url();?>config">
                  <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                  Configuration
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                  <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                  Logout
                </a>
              </div>
            </li>

          </ul>

        </nav>
        <!-- End of Topbar -->

        <!-- Begin Page Content -->
        <div id="page_content" class="container-fluid">

         
          <?php echo $pageContents;?>

        </div>
        <!-- /.container-fluid -->

      </div>
      <!-- End of Main Content -->

      <!-- Footer -->
      <footer class="sticky-footer bg-white">
        <div class="container my-auto">
          <div class="copyright text-center my-auto">
            <span>Copyright &copy; <?php echo date("Y");?> EZ-Payroll | GL Aba&ntilde;o Systems</span>
          </div>
        </div>
      </footer>
      <!-- End of Footer -->

    </div>
    <!-- End of Content Wrapper -->

  </div>
  <!-- End of Page Wrapper -->

  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>

  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="<?php echo base_url()."home/logout";?>">Logout</a>
        </div>
      </div>
    </div>
  </div>

  <!-- Bootstrap core JavaScript-->
  <script src="<?php echo base_url();?>assets/vendor/jquery/jquery.min.js"></script>
  <script src="<?php echo base_url();?>assets/vendor/bootstrap/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url();?>assets/vendor/jquery-easing/jquery.easing.min.js"></script>
  <script src="<?php echo base_url();?>assets/js/sb-admin-2.min.js"></script>
  <?php echo $pageScripts;?>

</body>

</html>
