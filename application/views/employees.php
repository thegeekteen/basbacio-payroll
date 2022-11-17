[Title]EZ-Payroll [ v.<?php echo $this->config->item("version");?> Basbacio Version ][/Title]

[Styles]
<link href="<?php echo base_url();?>assets/DataTables/datatables.min.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/jquery-ui-1.12.1.custom/jquery-ui.min.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/fixedcolumns/css/fixedColumns.bootstrap4.min.css" rel="stylesheet" />
[/Styles]

[Contents]
<div style="margin-bottom:15px;">

<h1 class="h3 mb-4 text-<?php echo $ClassName;?>"><span class="glyphicons"></span> Employees <span style="font-size:12px;">[ v.<?php echo $this->config->item("version") . " " . $this->config->item("registered_to");?> ]</span></h1>
<span style="font-size:12px;display:block;margin-bottom:5px;">Employee Management:</span>
  <button id="addemployee_button" class="btn btn-success btn-sm"><span class="glyphicons"></span> Add Employee</button>
  <button id="deleteemployee_button" class="btn btn-danger btn-sm"><span class="glyphicons"></span> Delete Selected</button>
  <button id="resetselection_button" class="btn btn-warning btn-sm"><span class="glyphicons"></span> Reset Selection</button>

  <hr />
  <span style="font-size:12px;display:block;margin-bottom:5px;">PaySlip Options:</span>

  <button id="printallactive-btn" class="btn btn-primary btn-sm"><span class="glyphicons"></span> Print All Active</button>
  <button id="printselected-btn" class="btn btn-info btn-sm"><span class="glyphicons"></span> Print Selected</button>
</div>

      <table id="employee-list" class="table table-striped" style="min-width:100%;">
        <thead>
          <th style="width:110px;"><input id='select_all' type='checkbox'></input> # <span style='font-size:12px;color:gray;font-weight:normal;'>[<span id='selected_count'>0</span> Selected]</span></th>
          <th style="display:none;">EmployeeID</th>
          <th style="width:280px;">Name</th>
          <th>AddedDate</th>
          <th>Designation</th>
          <th>Basic Rate</th>
          <th>Total Amount</th>
          <th>Total Deduction</th>
          <th>Active</th>
        </thead>
        <tbody style="cursor:pointer;">
        </tbody>
      </table>

<div class="modal fade" id="addemployee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="min-width:80%;" role="document">
    <div class="modal-content" style="overflow:hidden;">
      <div class="modal-header" style="background:#55AA55;color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Add Employees</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

        <label>Employee Name:</label>
        <input id="employee_name" class="form-control" type="text" />
        <label>Designation:</label>
        <input id="designation" class="form-control" type="text" />
        <label>Basic Pay:</label>
        <input id="basic_pay" class="form-control" type="text" />
        <label>Total Pay:</label>
        <input id="total_pay" class="form-control" type="text" disabled/>
         <label>Total Deduction:</label>
        <input id="total_deduction" class="form-control" type="text" disabled/>
        <hr />
         <div class="form-check">
          <input type="checkbox" class="form-check-input" id="isactive" checked>
          <label class="form-check-label" for="isactive" value="1">Active</label>
        </div>
        <p style="font-size:12px;color:gray;">Uncheck this if this employee is not working anymore or past employee</p>

        <hr />

        <h5 style="border-bottom-style:solid;border-width:1px;border-color:#c6c6c6;padding-bottom:5px;">Remarks</h5>
        <table id="remarks_table" style="width:100%;">
          <thead>
            <th>RemarksID</th>
            <th style="min-width:20%;max-width:20%;">Date</th>
            <th style="min-width:80%;max-width:80%">Remarks</th>
            <th style="min-width:100px;max-width:100px;">Options</th>
          </thead>
          <tbody>
          </tbody>

        </table>

        <br />
        <label>Date:</label>
        <input id="remarks_date" class="form-control" type="text" readonly/>
        <label>Remarks:</label>
        <input id="remarks_text" class="form-control" type="text" />
        <br />
        <button id="add_remarks" class="btn btn-success btn-block">Add Remarks</button>

        <hr />
         <h5 style="border-bottom-style:solid;border-width:1px;border-color:#c6c6c6;padding-bottom:5px;">Additional / Deductions</h5>

        <div class="row">
            <div class="col-md-6">
              <label>Additionals:</label>
                <table class="table table-striped" style="width:100%;">
                  <thead>
                    <th>Additional Name:</th>
                    <th>Amount</th>
                    <th>Options</th>
                  </thead>
                  <tbody id="additionals_table">
                  </tbody>
                </table>
                <hr />
                <div class="row">
                  <div class="col-md-4">
                    <label>Additional Name:</label>
                    <input id="additional_name" class="form-control" type="text" />
                  </div>
                   <div class="col-md-4">
                    <label>Amount:</label>
                    <input id="additional_amount" class="form-control" type="text" />
                  </div>
                   <div class="col-md-4" style="display:table-cell;vertical-align:bottom;">
                    <br />
                    <button id="add_additional" class="btn btn-success form-control" style="border-radius:0px;vertical-align:bottom;">Add Additional</button>
                  </div>
                </div>
            </div>
            <div class="col-md-6">
              <label>Deductions:</label>
               <table class="table table-striped" style="width:100%;">
                  <thead>
                    <th>Deduction Name:</th>
                    <th>Default Amount</th>
                    <th>Options</th>
                  </thead>
                  <tbody id="deductions_table">

                  </tbody>
                </table>
                <hr />
                <div class="row">
                  <div class="col-md-4">
                    <label>Deduction Name:</label>
                    <input id="deduction_name" class="form-control" type="text" />
                  </div>
                   <div class="col-md-4">
                    <label>Amount:</label>
                    <input id="deduction_amount" class="form-control" type="text" />
                  </div>
                   <div class="col-md-4" style="display:table-cell;vertical-align:bottom;">
                    <br />
                    <button id="add_deduction" class="btn btn-warning form-control" style="border-radius:0px;vertical-align:bottom;">Add Deduction</button>
                  </div>
                </div>
            </div>
        </div>
        

         <table style="border-width:1px;border-style:solid;border-color:#c6c6c6;width:50%;">
        </table>

        
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="save_employeebtn" type="button" class="btn btn-primary">Save changes</button>
      </div>
    
      <div handler="modal-loader" style="background-color:rgba(0, 0, 0, .15);height:100px;width:100%;position:absolute;top:0;left:0;padding-bottom:9999px;margin-bottom:-9999px;overflow:none;display:none;"></div>
        <div class="spinner spinner-border text-<?php echo $ClassName;?>" style="display:none;" role="status">
          <span class="sr-only">Loading...</span>
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

<div class="modal fade" id="printsalary-modal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#55AA55;color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Print Salary</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label>Start Date:</label>
        <input id="print-startdate" type="text" class="form-control" readonly />
         <label>End Date:</label>
        <input id="print-enddate" type="text" class="form-control" disabled />
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="printsalary-btn" type="button" class="btn btn-primary">Print</button>
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
<script src="<?php echo base_url();?>assets/js/universal.js"></script>
<script>
$("[mlink='employees']").addClass("active");

base_URL = "<?php echo base_url();?>";
  table = $("#employee-list").DataTable({
       "processing": true,
       "serverSide": true,
       "ajax": "<?php echo base_url();?>employees/list",
       "rowId": 1,
       "columnDefs": [ 
          {
              "targets": 0,
              "visible": true,
              "searchable": false,
              "defaultContent":"",
              "orderable":false,
          },
          {
              "targets": 1,
              "searchable": false,
              "visible":false
          }
       ],
         scrollX:true,
         paging:true   
  });

    checked_employees = [];
    table.on('search.dt order.dt draw.dt', function () {
        table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = "<input type='checkbox' employee-selector='true' employeeid='"+table.row(cell).id()+"'></input> "+(i+1);
            if (checked_employees.indexOf(table.row(cell).id()) > -1)
              $("#employee-list_wrapper input[employeeid='"+table.row(cell).id()+"']").prop("checked", true);
        } );

        var checked = $("#employee-list_wrapper [employee-selector='true']:checked").length;
        var total = $("#employee-list_wrapper [employee-selector='true']").length;

        if (checked == total && total > 0)
          $("#employee-list_wrapper input[id='select_all']").prop("checked", true);
        else
         $("#employee-list_wrapper input[id='select_all']").prop("checked", false);

    } ).draw();

    confirmDialog = new confirmModal();

    $("#resetselection_button").on("click", function() {
      checked_employees = [];
      $("#employee-list_wrapper [employee-selector='true'], #employee-list_wrapper input[id='select_all']").prop("checked", false);
      $("#employee-list_wrapper span[id='selected_count']").html(checked_employees.length);
  });

    $("#deleteemployee_button").on("click", function(e) {
        if (checked_employees.length <= 0)
          return;
        var confirm = function() {

          confirmDialog.progressMode(true);
          $.ajax({
            url:base_URL+"employees/delete",
            dataType:"json",
            data:JSON.stringify(checked_employees),
            method:"POST",
            success:function(response) {
              confirmDialog.progressMode(false);
              confirmDialog.hide();

              errorModalView.setprevmodal(null);
              if (response["success"]) {
                errorModalView.show("Employees Deleted", "success", "Employees successfully deleted!");
                checked_employees = [];
                table.ajax.reload(null, false);
                $("#employee-list_wrapper span[id='selected_count']").html(checked_employees.length);
              }
              else
                errorModalView.show("Employees Not Deleted", "error", "Employees not deleted!<br /><span style='font-size:12px;color:gray;'>"+response.reason+"</span>");
            },
            error:function(jqXHR, error) {
              confirmDialog.progressMode(false);
              confirmDialog.hide();
              errorModalView.setprevmodal(null);
              errorModalView.show("Server Error", "error", "Employees not deleted!<br /><span style='font-size:12px;color:gray;'>Traceback: " + jqXHR.responseText + "</span>");
            }
          });
        };

        confirmDialog.progressMode(false);
        confirmDialog.setconfirm(confirm);
        confirmDialog.show("Delete Employees", "error", "Delete <b>"+checked_employees.length+"</b> employees? All previous data of this employee on the payroll will be deleted.");
    });

    $("#employee-list_wrapper input[id='select_all']").on("change", function(e) {
      var checkboxes = $("#employee-list_wrapper [employee-selector='true']");
        if (e.target.checked) {
          // Select All
          
          for (var c = 0; c < checkboxes.length; c++) {
              $(checkboxes[c]).prop("checked", true);
              var e_id = $(checkboxes[c]).attr("employeeid");
              if (checked_employees.indexOf(e_id) < 0)
                checked_employees[checked_employees.length] = e_id;
          }
        } else {
          // Deselect All
         for (var c = 0; c < checkboxes.length; c++) {
              $(checkboxes[c]).prop("checked", false);
              var e_id = $(checkboxes[c]).attr("employeeid");
              var index = checked_employees.indexOf(e_id);
              if (index > -1)
                checked_employees.splice(index, 1);
          }
        }

        $("#employee-list_wrapper span[id='selected_count']").html(checked_employees.length);
    });

    $("#employee-list tbody").on("change", "input[employee-selector='true']", function(e) {
        var employee_id = e.target.getAttribute("employeeid");
        if (e.target.checked) {
          checked_employees[checked_employees.length] = employee_id;
        } else {
          var index = checked_employees.indexOf(employee_id);
          checked_employees.splice(index, 1);
        }
        $("#employee-list_wrapper span[id='selected_count']").html(checked_employees.length);
        
        var checked = $("#employee-list_wrapper [employee-selector='true']:checked").length;
        var total = $("#employee-list_wrapper [employee-selector='true']").length;

        if (checked == total)
          $("#employee-list_wrapper input[id='select_all']").prop("checked", true);
        else
         $("#employee-list_wrapper input[id='select_all']").prop("checked", false);
    });
  
   $('#employee-list tbody').on('click', 'td:not(:nth-child(1))', function (e) {
    // console.log("EmployeeID: " + table.row(e.currentTarget.parentNode).id());
        $.ajax({
          url:"<?php echo base_url();?>/employees/fetch/"+table.row(e.currentTarget.parentNode).id(),
          type:"json",
          method:"GET",
          success:function(response) {
              if (response.length <= 0) {
                errorModalView.setprevmodal(null);
                errorModalview.show("Edit Error", "error", "Employee don't exist in the system. Please refresh the page.");
                table.ajax.reload(null, false);
                return;
              }
              $("#addemployee").trigger("fetch", [response]);
          },
          error: function(jqxhr) {
                errorModalView.setprevmodal(null);
                errorModalview.show("Server Error", "error", "Server Error occured: " + jqxhr.responseText);
          }
        });
  });


   var print_type="all";

   $("#printsalary-btn").on("click", function() {
    var start_date = $("#print-startdate").val();
    if (start_date.length <= 0) {
      errorModalView.setprevmodal("#printsalary-modal");
      errorModalView.show("Error", "error", "Please select a Start Date");
      return;
    }

    $("#printsalary-modal").modal("hide");
   $("#progressModal").modal({
         backdrop: 'static', 
         keyboard: false
     });
    var params = {};
    var url = "";
     if (print_type == "all") {
        params = {
                "StartDate":start_date
              }
        url = base_URL+"employees/payslipall/"+btoa(JSON.stringify(params));

     } else {
        params = {
                "EmployeeIDs":checked_employees,
                "StartDate":start_date
              }
        url = base_URL+"employees/payslipselected/"+btoa(JSON.stringify(params));
     }
     
       var xhr = new XMLHttpRequest();
       xhr.open("GET", url);
       xhr.onprogress = function (e) {
            // For downloads
            if (e.lengthComputable) {
              console.log("good!");
                var progress = (e.loaded / e.total * 100).toFixed(0);
                $("[role='progressbar_uploader']").css("width", progress+"%").html(progress+"%").attr("aria-valuenow", progress);
            }
        };
        xhr.upload.onprogress = function (e) {
            // For uploads
            if (e.lengthComputable) {
                 var progress = (e.loaded / e.total * 100).toFixed(0);
                 $("[role='progressbar_uploader']").css("width", progress+"%").html(progress+"%").attr("aria-valuenow", progress);
            }
        };
       xhr.responseType = "blob";
       xhr.onload = function () {
          $("#progressModal").modal("hide");
        if (this.status === 200) {
              w = window.open(URL.createObjectURL(xhr.response));
        } else {
          var reader = new FileReader();

        // This fires after the blob has been read/loaded.
        reader.addEventListener('loadend', (e) => {
          const text = e.srcElement.result;
        errorModalView.setprevmodal("#printsalary-modal");
                  try {
                    var res = JSON.parse(text);
                      errorModalView.show("Cannot Print Data", "error", res.reason);
                  } catch (ex) {
                    errorModalView.show("Server Error", "error", "Server Error Occured: " + text);
                  }
        });

        // Start reading the blob as text.
        reader.readAsText(xhr.response);
        }
    };
    xhr.send();
   }); 

   $("#print-startdate").datepicker({
     dateFormat:"yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        beforeShowDay: function(date) {
          return [date.getDay() === 6 || date.getDay() === 3,''];
      }
   });

   $("#print-startdate").on("change", function(e) {
      var daynum = new Date(e.target.value).getDay();
      if (daynum == 6) {
        $("#print-enddate").val(plusday(e.target.value, 3));
      } else if (daynum == 3) {
        $("#print-enddate").val(plusday(e.target.value, 2));
      }
   });

    $("#printselected-btn").on("click", function() {
        print_type = "selected";
         $("#printsalary-modal").modal({
            focus:false
         });
   });

     $("#printallactive-btn").on("click", function() {
        print_type = "all";
        $("#printsalary-modal").modal({
            focus:false
         });
    });

modelDefaults = {
    "EmployeeID":0,
    "name":"",
    "position":"",
    "basic_pay":0,
    "active":1,
    "additionals":[],
    "client_additionals":[],
    "deductions":[],
    "client_deductions":[],
    "remove_from_server":[],
    "remarks":[],
    "remarks_client":[]
};
current_employee = modelDefaults;

var errorModalView = new errorModal();

var addModalView = Backbone.View.extend({
      tagName:"div",
      el:$("#addemployee"),
      events: {
        "click #save_employeebtn" : "savenow",
        "click #add_additional" : "additional_add",
        "click #add_deduction" : "deduction_add",
        "click [removetype='additionals']" : 'remove_additionals',
        "click [removetype='deductions']" : "remove_deductions",
        "fetch" : "syncfields",
        "hide.bs.modal" : "onhide",
        "click #add_remarks" : "addremarks"
      },
      progressMode : function(onprogress) {
          if (onprogress) {
            this.$el.data("noClosing", true);
            this.$el.find(".spinner").css("display", "block");
            this.$el.find("[handler='modal-loader']").css("display", "block");
          } else {
            this.$el.data("noClosing", false);
            this.$el.find(".spinner").css("display", "none");
             this.$el.find("[handler='modal-loader']").css("display", "none");
          }
      },
      onhide:function(e) {
        if ($(e.currentTarget).data("noClosing")) {
          e.stopPropagation();
          e.preventDefault();
          return false;
        }
      },
      "remove_deductions": function(e) {
          var trow = e.target.parentNode.parentNode;
          var array_index = e.target.getAttribute("arrayid");
          var server_id = e.target.getAttribute("server_id");

          if (array_index != null)
            current_employee["client_deductions"].splice(array_index, 1);
          if (server_id != null)
            current_employee["remove_from_server"].push(server_id);

          $(trow).remove();
      },
      "remove_additionals": function(e) {
          var trow = e.target.parentNode.parentNode;
          var array_index = e.target.getAttribute("arrayid");
          var server_id = e.target.getAttribute("server_id");

          
          if (array_index != null)
            current_employee["client_additionals"].splice(array_index, 1);
          if (server_id != null)
            current_employee["remove_from_server"].push(server_id);

          $(trow).remove();
      },
      "additional_add":function(e) {
          // On Additional Add
          var additional_name = $("#additional_name").val();
          var additional_amount = $("#additional_amount").val();

          // Validation Step!

          if (additional_name.length <= 0) {
            errorModalView.setprevmodal("#addemployee");
            errorModalView.show("Error", "error", "Please enter a valid name.");
            return;
          }

          if (!new RegExp(/^\d*?\.?\d{1,2}$/, "g").test(additional_amount)) {
            errorModalView.setprevmodal("#addemployee");
            errorModalView.show("Error", "error", "Please enter a valid amount.");
            return;
          }

          current_employee["client_additionals"].push(
                  {
                      "name":additional_name,
                      "amount":additional_amount
                  }
              );

          var c =  current_employee["client_additionals"].length - 1;

          $("#additionals_table").append("<tr><td>"+additional_name+"</td><td>"+additional_amount+"</td><td><button class='btn btn-sm btn-danger' removetype='additionals' arrayid='"+c+"'>Remove</button></tr>");

          $("#additional_name").val("");
          $("#additional_amount").val("");

      },
      "deduction_add":function(e) {
         // On Deduction Add
          var deduction_name = $("#deduction_name").val();
          var deduction_amount = $("#deduction_amount").val();

          
          if (deduction_name.length <= 0) {
            errorModalView.setprevmodal("#addemployee");
            errorModalView.show("Error", "error", "Please enter a valid name.");
            return;
          }

          if (!/\d+(\.\d{1,2})?/.test(deduction_amount)) {
            errorModalView.setprevmodal("#addemployee");
            errorModalView.show("Error", "error", "Please enter a valid amount.");
            return;
          }

              current_employee["client_deductions"].push(
                  {
                      "name":deduction_name,
                      "amount":deduction_amount
                  }
              );

          var c = current_employee["client_deductions"].length - 1;

          $("#deductions_table").append("<tr><td>"+deduction_name+"</td><td>"+deduction_amount+"</td><td><button class='btn btn-sm btn-danger' removetype='deductions' arrayid='"+c+"'>Remove</button></tr>");

           $("#deduction_name").val("");
           $("#deduction_amount").val("");
      },
      "savenow":function(e) {

          current_employee["name"] = $("#employee_name").val();
          current_employee["position"] = $("#designation").val();
          current_employee["basic_pay"] = $("#basic_pay").val();
          var isChecked = $("#isactive").get(0).checked;
          current_employee["active"] = (isChecked ? 1 : 0);

          var errorMessage = [];
          if (current_employee.name.length <= 0)
            errorMessage.push("Please enter an employee name.");
          if (current_employee.position.length <= 0)
            errorMessage.push("Please enter a position/designation name.");
          if (!/\d+(\.\d{1,2})?/.test(current_employee.basic_pay))
            errorMessage.push("Please enter a valid basic pay");

          if (errorMessage.length > 0) {
            errorModalView.setprevmodal("#addemployee");
            var htmlMessage = "";
            errorMessage.forEach(function(item, index) {
              htmlMessage += item + "<br />";
            });
            errorModalView.show("Failed", "error", htmlMessage.slice(0, - 6));
            return;
          }

          this.progressMode(true);

          $.ajax({
            url:"<?php echo base_url();?>employees/save",
            method:"POST",
            dataType:"json",
            data:JSON.stringify(current_employee),
            success: function(response) {
              this.progressMode(false);
              this.$el.modal("hide");
                if (response.success) {
                  errorModalView.setprevmodal(null);
                  errorModalView.show("Employee Saved", "success", "Employee successfully saved.");
                  table.ajax.reload(null, false);
                } else {
                  errorModalView.setprevmodal("#addemployee");
                  errorModalView.show("Employee Not Saved", "error", "An Error Occured: " + response.reason);
                }
            }.bind(this),
            error: function(jqxhr, error, options) {
              this.progressMode(false);
            this.$el.modal("hide");
                 errorModalView.setprevmodal("#addemployee");
                 errorModalView.show("Employee Not Saved", "error", "A Sever Error Occured: " + jqxhr.responseText);
            }.bind(this)
          });



      },
      showbutton_click:function(e) {
          this.resetfields();
          this.$el.modal("show");
      },
      syncfields:function(e, employee_data) {
        this.resetfields();
          current_employee = employee_data;
          current_employee["client_additionals"] = [];
          current_employee["client_deductions"] = [];
          current_employee["remove_from_server"] = [];
          current_employee["remarks_client"] = [];

          $("#employee_name").val(current_employee["name"]);
          $("#designation").val(current_employee["position"]);
          $("#basic_pay").val(current_employee["basic_pay"]);
          $("#total_pay").val(current_employee["totalamount"]);
          $("#total_deduction").val(current_employee["totaldeduction"]);
          if (parseInt(current_employee["active"]) > 0) {
             $("#isactive").prop("checked", true);
          } else {
             $("#isactive").prop("checked", false);
          }

          // additionals
          for (var i = 0; i < current_employee["additionals"].length; i++) {
            $("#additionals_table").append("<tr><td>"+current_employee["additionals"][i]["name"]+"</td><td>"+current_employee["additionals"][i]["amount"]+"</td><td><button class='btn btn-sm btn-danger' removetype='additionals' server_id='"+current_employee["additionals"][i]["_id"]+"'>Remove</button></tr>");
          }

          // deductions
          for (var i = 0; i < current_employee["deductions"].length; i++) {
            $("#deductions_table").append("<tr><td>"+current_employee["deductions"][i]["name"]+"</td><td>"+current_employee["deductions"][i]["amount"]+"</td><td><button class='btn btn-sm btn-danger' removetype='deductions' server_id='"+current_employee["deductions"][i]["_id"]+"'>Remove</button></tr>");
          }

          // remarks
          for (var i = 0; i < current_employee["remarks"].length; i++) {
            this.remarks_table.row.add([current_employee["remarks"][i]["RemarksID"], toHumanDate(current_employee["remarks"][i]["RemarksDate"]), current_employee["remarks"][i]["RemarksText"], "<span style='color:gray;'>N/A</span>"]);
            this.remarks_table.draw();
          }

          this.$el.modal("show");
      },
      resetfields: function() {
          current_employee = modelDefaults;
          current_employee["additionals"] = [];
          current_employee["deductions"] = [];
          current_employee["remove_from_server"] = [];
          current_employee["client_additionals"] = [];
          current_employee["client_deductions"] = [];
          $("#employee_name").val("");
          $("#designation").val("");
          $("#basic_pay").val("");
          $("#total_pay").val("");
          $("#deductions_table").html("");
          $("#additionals_table").html("");
          $("#total_deduction").val("");
          this.remarks_table.clear();
          this.remarks_table.draw();
          this.$("#remarks_date").val("");
          this.$("#remarks_text").val("");
      },
      remarks_cancel:function(e) {
          var array_index = e.target.getAttribute("array_index");
          current_employee["remarks_client"].splice(array_index, 1);
          this.remarks_table.row(e.target.parentNode.parentNode).remove();
          this.remarks_table.draw();
      },
      addremarks : function(e) {
        var date = this.$("#remarks_date");
        var text = this.$("#remarks_text");

        if (date.val().length <= 0 || text.val().length <= 0) {
            errorModalView.setprevmodal("#addemployee");
            errorModalView.show("Error", "error", "Please enter a date and remarks");
            return;
        }

        current_employee["remarks_client"].push({"date":date.val(), "text":text.val()});
        var array_index = current_employee["remarks_client"].length - 1;

        this.remarks_table.row.add([null, toHumanDate(date.val()), text.val(), "<button array_index='"+array_index+"' remarks='cancel_btn' class='btn btn-danger btn-sm'>Cancel</button>"]);

        date.val("");
        text.val("");
        this.remarks_table.draw();
      },
      initialize:function() {
        $("#addemployee_button").on("click", this.showbutton_click.bind(this));
          this.syncfields.bind(this);
          this.remarks_table = this.$("#remarks_table").DataTable({
            "paging":true,
            "columnDefs":[
              {
                "targets":[0],
                "visible":false
              },
            ]
          });
          this.$("#remarks_date").datepicker({dateFormat:"yy-mm-dd"});
          $("#remarks_table").on("click", "button[remarks='cancel_btn']", this.remarks_cancel.bind(this));
      }
  });
  myaddModalView = new addModalView();
</script>
[/Scripts]
