[Title]EZ-Payroll [ v.<?php echo $this->config->item("version");?> Basbacio Version ][/Title]

[Styles]
<link href="<?php echo base_url();?>assets/DataTables/datatables.min.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/jquery-ui-1.12.1.custom/jquery-ui.min.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/fixedcolumns/css/fixedColumns.bootstrap4.min.css" rel="stylesheet" />
<style>
  .dt-right {
    text-align:right;
  }
   .dt-center {
    text-align:center;
  }
  input::-webkit-outer-spin-button,
  input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
  }

  /* Firefox */
  input[type=number] {
    -moz-appearance:textfield;
  }
</style>
[/Styles]


[Contents]

      
      <h1 class="h3 mb-4 text-<?php echo $ClassName;?>"><span class="glyphicons"></span> View Project <span style="font-size:12px;">[ v.<?php echo $this->config->item("version") . " " . $this->config->item("registered_to");?> ]</span></h1>
      <h4 style="font-weight:lighter;"><?php echo htmlentities($project["ProjectName"]);?></h4>
      <p style="font-size:12px;color:gray">
        <label style="margin:0px;">Project Start Date:</label>
        <span><?php echo (new DateTime($project["StartDate"]))->format("F d, Y");?></span>
        <br style="margin:0px;" />
        <label style="margin:0px;">Project End Date:</label>
        <span><?php echo (new DateTime($project["EndDate"]))->format("F d, Y");;?></span>
      </p>

          <div class="form-group">
            <label for="start-date">Select Date Range: </label>
            <select id="daterange" class="form-control">
              <option value="" selected="selected"> --- Select Range ---</option>
              <?php

              foreach ($project["WeeklyData"] as $wdata) {
                  $template = "<option project-weekly-id='".$wdata["ProjectWeeklyID"]."'>" . (new DateTime($wdata["StartDate"]))->format("F d, Y") . " - " .(new DateTime($wdata["EndDate"]))->format("F d, Y") ."</option>";
                  echo $template;
              }

              ?>

            </select>
          </div>

          <div class="form-group" style="margin-bottom:2px;">
            <label style="font-size:12px;color:gray;">Weekly Management:</label>
            <hr style="margin:0px;margin-bottom:3px;" />
            <button id="addrange-btn" class="btn btn-sm btn-primary" style="display:inline-block;"><span class="glyphicons"></span> Add Date Range</button>
            <button id="addemployee-btn" class="btn btn-sm btn-primary" style="display:inline-block;" disabled><span class="glyphicons"></span> Add Employee</button>
            <button id="lockunlock-btn" class="btn btn-sm btn-danger" style="display:inline-block;" disabled><span class="glyphicons"></span> Lock</button>
          </div>

           <div class="form-group" style="margin-top:2px;">
            <label style="font-size:12px;color:gray;">Selection Option:</label>
            <hr style="margin:0px;margin-bottom:3px;" />
            <span>
              <button id="deleteselection-btn" class="btn btn-sm btn-danger" disabled><span class="glyphicons"></span> Delete Selected</button>
              <button id="resetselection-btn" class="btn btn-sm btn-warning" disabled><span class="glyphicons"></span>  Reset Selected</button>
            </span>
          </div>

            <div class="form-group" style="">

            <label style="font-size:12px;color:gray;">Print Options:</label>
            <hr style="margin:0px;margin-bottom:2px;" />
            <select id="printmode" class="form-control">
              <option value="1">Received Amount</option>
              <option value="2">Vale Amount</option>
            </select>

            <button id="printdata-btn" type="button" class="btn btn-sm btn-success" style="display:block;margin-top:3px;" disabled><span class="glyphicons"></span> Print Data</button>
          </div>

          <hr />
      

      <div>
        <table id="weekly-table" class="table table-striped table-sm" style="width:100%;">
          <thead>
              <th style="display:none;">ProjectWeeklyDataID</th>
              <th style="display:none;">EmployeeID</th>
              <th style="min-width:100px;"><input type="checkbox" id="select_all" /> <span style='color:gray;font-size:12px;'>[<span id="selected_count">0</span>] Selected</span></th>
              <th style="min-width:60px;">#</th>
              <th style="min-width:250px;">Name</th>
              <th style="text-align:center;min-width:100px;">Rate</th>
              <th week_column_num="6" week_column_name="S" style="text-align:center;min-width:150px;">S</th>
              <th week_column_num="0" week_column_name="Su" style="text-align:center;min-width:150px;">Su</th>
              <th week_column_num="1" week_column_name="M" style="text-align:center;min-width:150px;">M</th>
              <th week_column_num="2" week_column_name="T" style="text-align:center;min-width:150px;">T</th>
              <th week_column_num="3" week_column_name="W" style="text-align:center;min-width:150px;">W</th>
              <th week_column_num="4" week_column_name="Th" style="text-align:center;min-width:150px;">TH</th>
              <th week_column_num="5" week_column_name="F" style="text-align:center;min-width:150px;">F</th>
              <th style="text-align:center;min-width:100px;">Total Days</th>
              <th style="text-align:center;min-width:150px;">+</th>
              <th style="text-align:center;min-width:150px;">-</th>
              <th style="text-align:center;min-width:100px;">Wk Amount</th>
              <th style="text-align:center;min-width:150px;">Vale</th>
              <th style="text-align:center;min-width:150px;">Advance Vale</th>
              <th style="text-align:center;min-width:150px;">Received Amount</th>
              <th style="text-align:center;min-width:250px;">Remarks</th>
          </thead>

          <tbody style="cursor:pointer;">
          </tbody>
        
        </table>
      </div>

<div class="modal fade" id="lockunlock-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header bg-gradient-danger" style="color:white;">
        <h5 class="modal-title"><span class="glyphicons"></span> Lock/Unlock</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label>Password:</label>
        <input id="weeklylockpassword" type="password" class="form-control" />

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button id="lockunlocknow-btn" type="button" class="btn btn-primary">Lock/Unlock</button>
        <button for-loading="lockunlocknow-btn" style="display:none;" class="btn btn-primary" type="button" disabled>
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          Please Wait...
        </button>
      </div>
    </div>
  </div>
</div>


<!-- transfer_modal -->
<div class="modal fade" id="addrange-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background-color:#55AA55;color:white;">
        <h5 class="modal-title" >Add Date Range</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label>Start Date:</label>
        <input id="startdate" type="text" class="form-control" readonly/>
         <label>End Date:</label>
        <input id="enddate" type="text" class="form-control" disabled/>
        <hr />
        <p style="font-size:12px;color:gray;">* End Date is automatically 7 Days after the Start Date</p>

      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button id="addrangenow-btn" type="button" class="btn btn-primary">Add Range</button>
        <button id="addrangeloading" style="display:none;" class="btn btn-primary" type="button" disabled>
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          Please Wait...
        </button>
      </div>
    </div>
  </div>
</div>

 <div class="modal" id="progressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Processing</h5>
                 
                </div>
                <div class="modal-body">
                  <div class="progress">
                    <div class="progress-bar" role="progressbar_uploader" style="width: 0%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%</div>
                  </div>
                </div>
                <div class="modal-footer">
                </div>
              </div>
            </div>
          </div>


<div class="modal fade" id="addemployee-modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" style="min-width:80%;" role="document"> 
    <div class="modal-content">
      <div class="modal-header" style="background-color:#55AA55;color:white;">
        <h5 class="modal-title">Add Employee</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
      
          <h5>Select Employee</h5>
          <hr />
          
           <table id="employees-table" class="table table-striped table-sm" style="width:100%;">
            <thead>
              <th></th>
              <th><input type="checkbox" id="select_all" /> <span style="font-weight:normal;color:gray;font-size:12px;"> [<span id="selected_count">0</span> Selected]</span></th>
              <th>Employee Name</th>
            </thead>
            <tbody>
             
            </tbody>
          </table>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        <button id="addemployeenow-btn" type="button" class="btn btn-primary">Add Employees</button>
        <button id="addemployee-loading" style="display:none;" class="btn btn-primary" type="button" disabled>
          <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
          Please Wait...
        </button>
      </div>
    </div>
  </div>
</div>
[/Contents]

[Scripts]
<script src="<?php echo base_url();?>assets/js/underscore.js"></script>
<script src="<?php echo base_url();?>assets/js/backbone.js"></script>
<script src="<?php echo base_url();?>assets/DataTables/datatables.min.js"></script>
<script src="<?php echo base_url();?>assets/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>
<script src="<?php echo base_url();?>assets/fixedcolumns/js/dataTables.fixedColumns.min.js"></script>
<script src="<?php echo base_url();?>assets/js/universal.js"></script>
<script>
  projectID = "<?php echo $project["ProjectID"];?>";
  base_URL = "<?php echo base_url();?>";
  $("[mlink='projects']").addClass("active");
</script>
<script src="<?php echo base_url();?>assets/js/viewproject.js"></script>
[/Scripts]

