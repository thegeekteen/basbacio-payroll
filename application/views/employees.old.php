[Title]EZ-Payroll [ v.1.00 Basbacio Version ][/Title]

[Styles]
<link href="<?php echo base_url();?>assets/DataTables/datatables.min.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/jquery-ui-1.12.1.custom/jquery-ui.min.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/fixedcolumns/css/fixedColumns.bootstrap4.min.css" rel="stylesheet" />
[/Styles]

[Contents]
<div style="margin-bottom:15px;">

<h1 class="h3 mb-4 text-<?php echo $ClassName;?>"><span class="glyphicons"></span> Employees <span style="font-size:12px;">[ v.1.00 Basbacio Version ]</span></h1>
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
          <th>Active</th>
        </thead>
        <tbody style="cursor:pointer;">
        </tbody>
      </table>

<div class="modal fade" id="addemployee" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="min-width:80%;" role="document">
    <div class="modal-content">
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
        <hr />
         <div class="form-check">
          <input type="checkbox" class="form-check-input" id="isactive" checked>
          <label class="form-check-label" for="isactive" value="1">Active</label>
        </div>
        <p style="font-size:12px;color:gray;">Uncheck this if this employee is not working anymore or past employee</p>

        <hr />

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
       scrollY:300,
       scrollX:true,
       scrollCollapse: true,
       paging:true,        
       fixedHeader: true
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
        myaddModalView.resetmodelview();
        CurEmployeeModel.urlRoot = "<?php echo base_url();?>employees/fetch/"+table.row(this).id();
        CurEmployeeModel.fetch({
            success: function (collection, response, options) {
                // you can pass additional options to the event you trigger here as well

            },
            error: function (collection, response, options) {
                // you can pass additional options to the event you trigger here as well

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
          return [date.getDay() === 6,''];
      }
   });

   $("#print-startdate").on("change", function(e) {
      $("#print-enddate").val(plusday(e.target.value, 6))
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

var modelDefaults = {
      "EmployeeID":0,
      "name":"",
      "position":"",
      "basic_pay":0,
      "active":1,
      "additionals":[],
      "deductions":[],
      "remove_from_server":[]
    };
var EmployeeModel = Backbone.Model.extend ({
    "idAttribute":"EmployeeID",
    "urlRoot":"<?php echo base_url();?>employees/save",
    "defaults": modelDefaults,
    "validate": function(e) {
      var errorMessage = "";
        if (e.name.length <= 0)
          errorMessage = "Name field can't be empty.";
        else if (e.position.length <= 0)
          errorMessage = "Position field can't be empty.";
        else if (e.basic_pay.length <= 0 || !new RegExp(/^\d*?\.?\d{1,2}$/, "g").test(e.basic_pay))
          errorMessage = "Please enter a valid basic pay.";
        return errorMessage;
    }
});

CurEmployeeModel = new EmployeeModel();

var errorModalView = new errorModal();

var addModalView = Backbone.View.extend({
      tagName:"div",
      el:$("#addemployee"),
      elems:{
        "employee_name":$("#employee_name"),
        "designation":$("#designation"),
        "basic_pay":$("#basic_pay"),
        "total_pay":$("#total_pay"),
        "additional_button":$("#add_additional"),
        "deduction_button":$("#add_deduction"),
        "showbutton":$("#addemployee_button"),
        "additionals_table":$("#additionals_table"),
        "deductions_table":$("#deductions_table"),
        "additional":$("#additional_name"),
        "additional_amount":$("#additional_amount"),
        "deduction_name":$("#deduction_name"),
        'deduction_amount':$("#deduction_amount")
      },
      events: {
        "click #save_employeebtn" : "savenow",
        "click #add_additional" : "additional_add",
        "click #add_deduction" : "deduction_add",
        "click [removetype='additionals']" : 'remove_additionals',
        "click [removetype='deductions']" : "remove_deductions"

      },
      "remove_deductions": function(e) {
          var trow = e.target.parentNode.parentNode;
          var array_index = e.target.getAttribute("arrayid");
          var server_id = e.target.getAttribute("server_id");

           CurEmployeeModel.get("deductions").splice(array_index, 1);
          if (typeof server_id !== "undefined") {
            CurEmployeeModel.get("remove_from_server").push(server_id);
          }
          $(trow).remove();
      },
      "remove_additionals": function(e) {
          var trow = e.target.parentNode.parentNode;
          var array_index = e.target.getAttribute("arrayid");
          var server_id = e.target.getAttribute("server_id");

          
            CurEmployeeModel.get("additionals").splice(array_index, 1);

          if (typeof server_id !== "undefined") {
            CurEmployeeModel.get("remove_from_server").push(server_id);
          }

          $(trow).remove();
      },
      "additional_add":function(e) {
          // On Additional Add
          var additional_name = this.elems["additional"].val();
          var additional_amount = this.elems["additional_amount"].val();

          // Validation Step!

          errorModalView.setprevmodal("#addemployee");
          if (additional_name.length <= 0) {
            errorModalView.show("Error", "error", "Please enter a valid name.");
            return;
          }

          if (!new RegExp(/^\d*?\.?\d{1,2}$/, "g").test(additional_amount)) {
            errorModalView.show("Error", "error", "Please enter a valid amount.");
            return;
          }

              CurEmployeeModel.get("additionals").push(
                  {
                      "name":additional_name,
                      "amount":additional_amount
                  }
              );

          var c = CurEmployeeModel.get("additionals").length - 1;

          this.elems["additionals_table"].append("<tr><td>"+additional_name+"</td><td>"+additional_amount+"</td><td><button class='btn btn-sm btn-danger' removetype='additionals' arrayid='"+c+"'>Remove</button></tr>");

          this.elems["additional"].val("");
          this.elems["additional_amount"].val("");

      },
      "deduction_add":function(e) {
         // On Deduction Add
          var deduction_name = this.elems["deduction_name"].val();
          var deduction_amount = this.elems["deduction_amount"].val();

          errorModalView.setprevmodal("#addemployee");
          if (deduction_name.length <= 0) {
            errorModalView.show("Error", "error", "Please enter a valid name.");
            return;
          }

          if (!/\d+(\.\d{1,2})?/.test(deduction_amount)) {
            errorModalView.show("Error", "error", "Please enter a valid amount.");
            return;
          }

              CurEmployeeModel.get("deductions").push(
                  {
                      "name":deduction_name,
                      "amount":deduction_amount
                  }
              );

          var c = CurEmployeeModel.get("deductions").length - 1;

          this.elems["deductions_table"].append("<tr><td>"+deduction_name+"</td><td>"+deduction_amount+"</td><td><button class='btn btn-sm btn-danger' removetype='deductions' arrayid='"+c+"'>Remove</button></tr>");

           this.elems["deduction_name"].val("");
           this.elems["deduction_amount"].val("");
      },
      "savenow":function(e) {
          // SaveOrEdit Employee
            var savedata = {
              "name":this.elems.employee_name.val(),
              "position":this.elems.designation.val(),
              "basic_pay":this.elems.basic_pay.val(),
              "active":(this.$el.find("#isactive").get(0).checked ? "1" : "0")
            };

            CurEmployeeModel.set(savedata);
            if (!CurEmployeeModel.isValid()) {
                errorModalView.setprevmodal("#addemployee");
                errorModalView.show("Not Saved", "error", "There is an error processing the request.<hr /><p style='font-size:12px;color:gray;'>Error Info: "+escapeHtml(CurEmployeeModel.validationError)+"</p>");
                return;
             }

            CurEmployeeModel.clone().save(savedata, {
                success: function(model, response) {

                  this.resetmodelview();
                  this.$el.modal("hide");
                  errorModalView.setprevmodal(null);
                  errorModalView.show("Saved", "success", "Employee successfully added or saved.");
                  table.ajax.reload();

                }.bind(this),
                error: function(model, response) {
                 errorModalView.setprevmodal("#addemployee");
                 errorModalView.show("Not Saved", "error", "There is an error processing the request.<hr /><p style='font-size:12px;color:red;'>"+escapeHtml(response.responseText)+"</p>");
                }
            });

      },
      showbutton_click:function(e) {
          CurEmployeeModel.clear().set(EmployeeModel.defaults);
          CurEmployeeModel.set("additionals", []);
          CurEmployeeModel.set("deductions", []);
          CurEmployeeModel.set("remove_from_server", []);
          this.resetmodelview();
          this.$el.modal("show");
      },
      sync_fields:function() {
          this.resetmodelview();
          var e_id = CurEmployeeModel.get("EmployeeID");
          var name = CurEmployeeModel.get("name");
          var position = CurEmployeeModel.get("position");
          var basic_pay = CurEmployeeModel.get("basic_pay");
          var additionals = CurEmployeeModel.get("additionals");
          var deductions = CurEmployeeModel.get("deductions");
          var total_pay = CurEmployeeModel.get("totalamount");
          var active = CurEmployeeModel.get("active");

          this.$el.find("#isactive").get(0).checked = active;

          this.elems.employee_name.val(name);
          this.elems.designation.val(position);
          this.elems.basic_pay.val(basic_pay);
          this.elems.total_pay.val(total_pay);
          if (parseInt(active) > 0) {
             $("#isactive").prop("checked", true);
          } else {
             $("#isactive").prop("checked", false);
          }

          // additionals
          for (var i = 0; i < additionals.length; i++) {
            this.elems["additionals_table"].append("<tr><td>"+additionals[i]["name"]+"</td><td>"+additionals[i]["amount"]+"</td><td><button class='btn btn-sm btn-danger' removetype='additionals' server_id='"+additionals[i]["_id"]+"' arrayid='0'>Remove</button></tr>");
          }

          // deductions
          for (var i = 0; i < deductions.length; i++) {
            this.elems["deductions_table"].append("<tr><td>"+deductions[i]["name"]+"</td><td>"+deductions[i]["amount"]+"</td><td><button class='btn btn-sm btn-danger' removetype='deductions' server_id='"+deductions[i]["_id"]+"' arrayid='0'>Remove</button></tr>");
          }

           CurEmployeeModel.set("additionals", []);
          CurEmployeeModel.set("deductions", []);
          this.$el.modal("show");

      },
      "resetmodelview": function() {
          this.elems.employee_name.val("");
          this.elems.designation.val("");
          this.elems.basic_pay.val("");
          this.elems["additionals_table"].html("");
          this.elems["deductions_table"].html("");
          this.elems.total_pay.val("");
      },
      "model":CurEmployeeModel,
      initialize:function() {
        this.elems["showbutton"].on("click", this.showbutton_click.bind(this));
        this.deduction_add.bind(this);
        this.additional_add.bind(this);
        this.model.on("sync", this.sync_fields.bind(this));
        this.$el.find("#isactive").get(0).checked = true;
      }
  });
  myaddModalView = new addModalView();
</script>
[/Scripts]
