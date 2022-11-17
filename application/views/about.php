[Title]EZ-Payroll [ v.<?php echo $this->config->item("version");?> Basbacio Version ][/Title]

[Contents]

          <h1 class="h3 mb-4 text-<?php echo $ClassName;?>"><span class="glyphicons"></span> EZ-Payroll <span style="font-size:12px;">[ v.<?php echo $this->config->item("version");?> Basbacio Version ]</span></h1>
          <hr />

          <div class="row">

            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-12">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-<?php echo $ClassName;?>"><span class="glyphicons"></span> EZ-Payroll <span style="font-size:12px;">[ v.<?php echo $this->config->item("version") . " " . $this->config->item("registered_to");?> ]</span></h6>
                  <!-- <div class="dropdown no-arrow">
                    <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
                      <div class="dropdown-header">Dropdown Header:</div>
                      <a class="dropdown-item" href="#">Action</a>
                      <a class="dropdown-item" href="#">Another action</a>
                      <div class="dropdown-divider"></div>
                      <a class="dropdown-item" href="#">Something else here</a>
                    </div>
                  </div>-->
                </div>
                <!-- Card Body -->
                <div class="card-body" style='color:#000;'>
                

                  <h4><span class="glyphicons"></span> <span>GL Aba&ntilde;o Systems</span>
                    <br />
                    <h6>
                      <span class="glyphicons"></span> EZ-Payroll <span style="font-size:12px;">[ v.<?php echo $this->config->item("version") . " " . $this->config->item("registered_to");?> ]</span>
                    </h6>
                  <span style="font-size:12px;color:gray;display:block;">Contact No [SMART]: <b>+(63) 950-271-3043</b></span>

                
                  
                  </h4>
                  <hr />
                   <label style="margin:0px;display:block;">Version: <span style="font-size:12px;color:gray;"><?php echo $this->config->item("version");?></span></label>
                   <label style="margin:0px;display:block;">Version Date: <span style="font-size:12px;color:gray;"><?php echo $this->config->item("version_date");?></span></label>
                   <label style="margin:0px;display:block;">Registered to: <span style="font-size:12px;color:gray;"><?php echo $this->config->item("registered_to");?></span></label>
                    <label style="margin:0px;display:block;">Registered Address: <span style="font-size:12px;color:gray;"><?php echo $BusinessAddress;?></span></label>
                     <label style="margin:0px;display:block;">Copyright: <span style="font-size:12px;color:gray;">&copy; <?php echo date("Y");?> EZ-Payroll | GL Aba&ntilde;o Systems</span></label>

                <hr />

                  

                    
               <label style="margin:0px;display:block;">Changelogs:</label>
               <br />
               <div style="width:100%;max-height:150px;overflow-y:auto;">
               <p style="font-size:12px;color:gray;width:300px;">
                Version 3.0:
                <ul style="font-size:12px;color:gray;">
                  <li>[viewproject.php] - allowed add date range that is not exactly an existing start date.</li>
                  <li>[viewproject.php] - date range selector start date sorted desc.</li>
                  <li>[viewproject.php] - rewrite for 3 | 4 days date range.</li>
                  <li>[employees.php] - added undeletable remarks for employee info</li>
                  <li>[employees.php] - rewrite for 3 | 4 days range.</li>
                  <li>[projects.php] - changed default order is latest project desc</li>
                  <li>[pdf_model.php] - rewrite for 3 | 4 days date range and attendance total per day</li>
                </ul>
               </p>

                <p style="font-size:12px;color:gray;width:300px;">
                Version 2.5:
                <ul style="font-size:12px;color:gray;">
                <li>[home.php] - fixed username not included in login validation</li>
                  <li>[receipts.php] - adding vouchers without row data is now allowed / deleting all rows from voucher is now allowed</li>
                  <li>[viewproject.php] - updated attendance invalid input to modal. </li>
                  <li>[viewproject.php] - changed add range algorithm to add employees from latest project weekly data instead from employees sql table. </li>
                  <li>[viewproject.php] - updated payhistory data to choose the latest one from the list.</li>
                  <li>[viewproject.php] - fixed arrangement bug when adding another date range.</li>
                  <li>[viewproject.php] - fixed disappearing checkbox/selected count in attendance table when new employee is added.</li>
                  <li>[employees.php] - fixed additional/deductions not working when editing/saving.</li>
                </ul>
              </p>
            </div>

               <hr />
                    
                   
               <p style="font-size:12px;color:gray;width:300px;">
                  Tito Sonny & Tita Arlene Basbacio,
                  <br /><br />
                  Thank you for choosing me to create your Payroll System. More blessings to come for you and S.B Basbacio Construction. :)
                  <br />
                  <span style="text-align:right;display:block;">
                    - Gian Lorenzo Aba&ntilde;o
                  </span>
               </p>


            


                </div>
              </div>
            </div>

            <!-- Pie Chart -->
            
            </div>


[/Contents]

[Scripts]
   <script>
  $(document).ready(function() {
     $("#loading").hide();
     $('#wrapper').show();
  });
   $("[mlink='about']").addClass("active");
 </script>
[/Scripts]