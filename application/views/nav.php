<style>
  /*!
 * Start Bootstrap - Simple Sidebar (https://startbootstrap.com/template-overviews/simple-sidebar)
 * Copyright 2013-2019 Start Bootstrap
 * Licensed under MIT (https://github.com/BlackrockDigital/startbootstrap-simple-sidebar/blob/master/LICENSE)
 */
body {
  overflow-x: hidden;
}

#sidebar-wrapper {
  min-height: 100vh;
  margin-left:-10rem;
  -webkit-transition: margin .25s ease-out;
  -moz-transition: margin .25s ease-out;
  -o-transition: margin .25s ease-out;
  transition: margin .25s ease-out;
}

#sidebar-wrapper .sidebar-heading {
  padding: 0.875rem 1.25rem;
  font-size: 1.2rem;
}

#sidebar-wrapper .list-group {
  width:100%;
}

#page-content-wrapper {
  min-width: 100vw;
}

#wrapper.toggled #sidebar-wrapper {
  margin-left: 0;
}

@media (min-width: 768px) {
  #sidebar-wrapper {
    margin-left: 0;
  }

  #page-content-wrapper {
    min-width: 0;
    width: 100%;
  }

  #wrapper.toggled #sidebar-wrapper {
    margin-left: -15rem;
  }
}


div.sidebar-heading {
  color:white;
}

.list-group-item {
  width:100%;
  color:white;
  cursor:pointer;
}

.list-group-item:hover {
  background-color:#212529 !important;
  color:white;
}


@media (min-width: 768px) {
  div.sidebar-heading-nav {
    display:none;
  }
  button.navbar-toggler {
    visibility:hidden;
  }
  div.nav_right {
    display:block;
  }
   button#togglesidebar {
    display:block;
  }
}

@media (max-width: 768px) {
  div.sidebar-heading-nav {
    display:inline;
    color:white;
  }

  button.navbar-toggler {
    visibility:visible;
  }

  div.nav_right {
    display:none;
  }

  button#togglesidebar {
    display:none;
  }
}


div.list-group a.active {
  border-style:none !important;
  border-top-style:none !important;
  background-color:#212529 !important;
  color:white;
}



</style>

<div class="d-flex" id="wrapper">

    <!-- Sidebar -->
    <div class="bg-dark border-right" id="sidebar-wrapper">
      <div class="sidebar-heading"><img src="<?php echo base_url();?>assets/img/builder.png" style="height:50px;width:50px;" /><h6 style="font-weight:lighter;">S.B. Basbacio Construction & Supply</h6></div>
      <div class="list-group list-group-flush">
        <a mlink='dashboard' href="<?php echo base_url();?>dashboard" class="list-group-item list-group-item-action bg-dark">Dashboard</a>
        <a mlink='projects' href="<?php echo base_url();?>projects" class="list-group-item list-group-item-action bg-dark">Projects</a>
        <a mlink='employees' href="<?php echo base_url();?>employees" class="list-group-item list-group-item-action bg-dark">Employees</a>
        <a href="#" class="list-group-item list-group-item-action bg-dark">About</a>
        <a href="#" class="list-group-item list-group-item-action bg-dark">Log Out</a>
      </div>
    </div>
    <!-- /#sidebar-wrapper -->

    <!-- Page Content -->
    <div id="page-content-wrapper">

      <nav class="navbar navbar-fixed navbar-collapse navbar-dark bg-dark border-bottom" style="margin-left:-1px;z-index:-1;">
          <div class="sidebar-heading-nav"><img src="<?php echo base_url();?>assets/img/builder.png" style="height:50px;width:50px;display:inline;" /><h6 style="font-weight:lighter;display:inline;margin-left:5px;font-size:14px;">S.B. Basbacio Construction & Supply</h6></div>



        <div class="nav_left">
         <button id="togglesidebar" class='btn btn-warning btn-sm'>Toggle Sidebar</button>
        </div>


        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>


        <div class="right nav_right">
         <a href="#" class="list-group-item list-group-item-action bg-dark">Log Out</a>
        </div>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
          <ul class="navbar-nav ml-auto mt-2 mt-lg-0">
            <li class="nav-item" mlink='dashboard'>
              <a mlink='dashboard' class="nav-link" href="#">Dashboard</a>
            </li>
            <li class="nav-item" mlink='projects'>
              <a class="nav-link" href="#">Projects</a>
            </li>
            <li class="nav-item" mlink='employees'>
              <a class="nav-link" href="#">Employees</a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="#">About</a>
            </li>
              <li class="nav-item">
              <a class="nav-link" href="#">Log Out</a>
            </li>
           
          </ul>
        </div>
      </nav>