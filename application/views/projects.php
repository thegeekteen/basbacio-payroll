
[Title]EZ-Payroll [ v.<?php echo $this->config->item("version");?> Basbacio Version ][/Title]

[Styles]
<link href="<?php echo base_url();?>assets/DataTables/datatables.min.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/jquery-ui-1.12.1.custom/jquery-ui.min.css" rel="stylesheet" />
[/Styles]

[Contents]


          <h1 class="h3 mb-4 text-<?php echo $ClassName;?>"><span class="glyphicons"></span> Projects <span style="font-size:12px;">[ v.<?php echo $this->config->item("version") . " " . $this->config->item("registered_to");?> ]</span></h1>

      <div style="margin-bottom:10px;">
        <button id="addproject_button" class="btn btn-success btn-sm"><span class="glyphicons"></span> Add Projects</button>
      </div>
       <div style="margin-bottom:10px;">
        <span style="display:block;font-size:12px;">Manage:</span>
        <hr style="margin:0px;line-height:0px;margin-bottom:5px;" />
         <button id="delproject_button" class="btn btn-danger btn-sm"><span class="glyphicons"></span> Delete Projects</button>
         <button id="resetselection_button" class="btn btn-warning btn-sm"><span class="glyphicons"></span> Reset Selection</button>
      </div>



      <table id="project-list" class="table table-striped" style="width:100%;">
        <thead>
          <th style="display:none;">ProjectID</th>
          <th style="width:110px;"><input id='select_all' type='checkbox'></input> # <span style='font-size:12px;color:gray;font-weight:normal;'>[<span id='selected_count'>0</span> Selected]</span></th>
          <th style="width:280px;" >Project Name</th>
          <th>Added Date</th>
          <th>Start Date</th>
          <th>End Date</th>
          <th>Project Done</th>
          <th>Options</th>
        </thead>
        <tbody style="cursor:pointer;">
          <!--<tr>
            <td>Gian Lorenzo Abano</td>
            <td>Foreman</td>
            <td>500</td>
            <td>750</td>
            <td>Yes</td>
          </tr>-->
        </tbody>
      </table>

<div class="modal fade" id="addprojectmodal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="min-width:80%;" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#55AA55;color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Add/Edit Project</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label>Project Name:</label>
        <input id="project_name" class="form-control" type="text" />
        <label>Start Date:</label>
        <input id="start_date" class="form-control" type="text" readonly/>
        <label>End Date:</label>
        <input id="end_date" class="form-control" type="text" readonly/>
         <div class="form-check">
          <input type="checkbox" class="form-check-input" id="isfinished">
          <label class="form-check-label" for="isactive" value="1">Finished</label>
        </div>
        <p style="font-size:12px;color:gray;">Check this if this project is already finished</p>

       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="save_projectbtn" type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div>
  </div>
</div>


[/Contents]

[Scripts]
<script src="<?php echo base_url();?>assets/js/underscore.js"></script>
<script src="<?php echo base_url();?>assets/js/backbone.js"></script>
<script src="<?php echo base_url();?>assets/DataTables/datatables.min.js"></script>
<script src="<?php echo base_url();?>assets/js/universal.js"></script>
<script src="<?php echo base_url();?>assets/jquery-ui-1.12.1.custom/jquery-ui.min.js"></script>

<script>
$("[mlink='projects']").addClass("active");

var confirmDialog = new confirmModal();

$("#delproject_button").on("click", function() {
  if (checked_projects.length <= 0) 
    return;

  var onConfirm = function() {
    confirmDialog.progressMode(true);
      $.ajax({
        url:"<?php echo base_url();?>/projects/delete",
        dataType:"json",
        method:"POST",
        data:JSON.stringify(checked_projects),
        success:function(response) {
            confirmDialog.progressMode(false);
            confirmDialog.hide();
            errorModalView.setprevmodal(null);
            if (response["success"]) {
              errorModalView.show("Projects Deleted", "success", "Projects successfully deleted!");
              checked_projects = [];
              table.ajax.reload(null, false);
            }
            else
              errorModalView.show("Projects Not Deleted", "error", "Projects not deleted!<br /><span style='font-size:12px;color:gray;'>"+response.reason+"</span>");
        },
        "error":function(jqXHR, error) {
            confirmDialog.progressMode(false);
            confirmDialog.hide();
            errorModalView.setprevmodal(null);
            errorModalView.show("Server Error", "error", "Projects not deleted!<br /><span style='font-size:12px;color:gray;'>Traceback: " + jqXHR.responseText + "</span>");
        }
      });
  }

  confirmDialog.progressMode(false);
  confirmDialog.setconfirm(onConfirm);
  confirmDialog.show("Delete Projects", "error", "Delete <b>"+checked_projects.length+"</b> projects? All weekly data of projects you selected will be deleted.");
});

$("#resetselection_button").on("click", function() {
    checked_projects = [];
    $("#project-list_wrapper [project-selector='true'], #project-list_wrapper input[id='select_all']").prop("checked", false);
    $("#project-list_wrapper span[id='selected_count']").html(checked_projects.length);
});

function escapeHtml(unsafe) {
    return unsafe
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
 }

base_URL = "<?php echo base_url();?>";
$(document).ready(function() {

    table = $("#project-list").DataTable({
         "processing": true,
         "serverSide": true,
         "ajax": "<?php echo base_url();?>projects/list",
         "rowId": 0,
         "language": {
            "emptyTable": "No Projects Available"
          },
         "columnDefs": [ 
            {
                "targets": 0,
                "visible": false,
                "searchable": false
            },
            {
              "targets": [1],
              "searchable":false,
              "orderable":false,
              "defaultContent":""
            },
            {
                "targets": 7,
                "render": function ( data, type, full, meta ) {
                    return "<div style='width:130px;'><button buttontype='edit' projectid='"+full[0]+"' class='btn btn-sm btn-warning' style='border-radius:0px;display:inline;width:100%;'>Edit</button>";
                 }
            }
         ],
         scrollX:true,
         paging:true,
         order:[[0, "desc"]]
    });

    checked_projects = [];
     table.on('search.dt order.dt draw.dt', function () {
        table.column(1, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = "<input type='checkbox' project-selector='true' projectid='"+table.row(cell).id()+"'></input> "+(i+1);
            if (checked_projects.indexOf(table.row(cell).id()) > -1)
              $("#project-list_wrapper input[projectid='"+table.row(cell).id()+"']").prop("checked", true);
        } );

        var checked = $("#project-list_wrapper [project-selector='true']:checked").length;
        var total = $("#project-list_wrapper [project-selector='true']").length;

        if (checked == total && total > 0)
          $("#project-list_wrapper input[id='select_all']").prop("checked", true);
        else
          $("#project-list_wrapper input[id='select_all']").prop("checked", false);
    } ).draw();

      $("#project-list_wrapper input[id='select_all']").on("change", function(e) {
      var checkboxes = $("#project-list_wrapper [project-selector='true']");
        if (e.target.checked) {
          // Select All
          
          for (var c = 0; c < checkboxes.length; c++) {
              $(checkboxes[c]).prop("checked", true);
              var e_id = $(checkboxes[c]).attr("projectid");
              if (checked_projects.indexOf(e_id) < 0)
                checked_projects[checked_projects.length] = e_id;
          }
        } else {
          // Deselect All
         for (var c = 0; c < checkboxes.length; c++) {
              $(checkboxes[c]).prop("checked", false);
              var e_id = $(checkboxes[c]).attr("projectid");
              var index = checked_projects.indexOf(e_id);
              if (index > -1)
                checked_projects.splice(index, 1);
          }
        }

        $("#project-list_wrapper span[id='selected_count']").html(checked_projects.length);
    });


    $("#project-list tbody").on("change", "input[project-selector='true']", function(e) {
        var project_id = e.target.getAttribute("projectid");
        if (e.target.checked) {
          checked_projects[checked_projects.length] = project_id;
        } else {
          var index = checked_projects.indexOf(project_id);
          checked_projects.splice(index, 1);
        }
        $("#project-list_wrapper span[id='selected_count']").html(checked_projects.length);
        
        var checked = $("#project-list_wrapper [project-selector='true']:checked").length;
        var total = $("#project-list_wrapper [project-selector='true']").length;

        if (checked == total)
          $("#project-list_wrapper input[id='select_all']").prop("checked", true);
        else
         $("#project-list_wrapper input[id='select_all']").prop("checked", false);
    });

    $('#project-list tbody').on('click', 'td:not(:nth-child(7)):not(:nth-child(1))', function (e) {
        location.href="<?php echo base_url();?>viewproject/"+table.row(this).id();
    });

    
     $('#project-list tbody').on('click', '[buttontype="edit"]', function (e) {
        myView.reset();
        myModel.set("ProjectID", table.row(e.target.parentNode.parentNode).id());
        myModel.urlRoot = "<?php echo base_url();?>projects/fetch";
        myModel.fetch({
          success:myView.sync_fields.bind(myView)
        });
    });

});

var model = Backbone.Model.extend({
    urlRoot:"<?php echo base_url();?>projects/save",
    idAttribute:"ProjectID",
    defaults:{
        "ProjectID":null,
        "ProjectName":"",
        "StartDate":"",
        "EndDate":"",
        "ProjectDone":""
    },
     "validate": function(e) {
      var errorMessage = "";
        if (e.ProjectName.length <= 0)
          errorMessage = "Name field can't be empty.";
        else if (e.StartDate.length <= 0)
          errorMessage = "Start Date field can't be empty.";
        else if (e.EndDate.length <= 0)
          errorMessage = "End Datefield can't be empty.";

        return errorMessage;
    }
});
var myModel = new model();

var view = Backbone.View.extend({
  el:$("#addprojectmodal"),
  elems:{
      addproject_button:$("#addproject_button"),
      projectname:$("#project_name"),
      startdate:$("#start_date"),
      enddate:$("#end_date"),
      finished:$("#isfinished"),
      progressButton:$('<button class="btn btn-primary" disabled><span style="width:20px;height:20px;" class="spinner spinner-border"></span> Processing...</button>')
  },
  events: {
      "click #save_projectbtn": "saveproject"
  },
  onhide:function(e) {
    if ($(e.target).data("noClosing")) {
      e.stopPropagation();
      e.preventDefault();
      return false;
    }
  },
  reset: function() {
    this.elems.projectname.val("");
    this.elems.startdate.val("");
    this.elems.enddate.val("");
    this.elems.finished.get(0).checked = false;
  },
  progressMode: function(onProgress) {
    if (onProgress) {
       this.$el.data("noClosing", true);
       this.$el.find("input:not(#startdate):not(#enddate)").prop("disabled", true);
       $("#save_projectbtn").css("display", "none").parent().append(this.elems.progressButton);

     } else {
        this.$el.data("noClosing", false);
         $("#save_projectbtn").css("display", "inline");
        this.$el.find("input:not(#startdate):not(#enddate)").prop("disabled", false);
        this.elems.progressButton.remove();
     }
  },
  saveproject:function(e) {
      var pdone = (this.elems.finished.get(0).checked ? "1" : "0");
      var val = {
        "ProjectName":this.elems.projectname.val(),
        "StartDate":this.elems.startdate.val(),
        "EndDate":this.elems.enddate.val(),
        "ProjectDone":pdone
      }

      myModel.set(val);
      if (!myModel.isValid()) {
      errorModalView.setprevmodal("#addprojectmodal");
      errorModalView.show("Not Saved", "error", "There is an error processing the request due to validation error.<hr /><p style='font-size:12px;color:gray;'>Error Info: "+escapeHtml(myModel.validationError)+"</p>");
      return;
      }

      this.progressMode(true);

      myModel.clone().save(null, {
        success: function(model, response, options) {
            this.progressMode(false);
            table.ajax.reload(null, false);
            errorModalView.setprevmodal(null);
            this.$el.modal("hide");

            if (response.success)
              errorModalView.show("Saved", "success", "Project successfully saved.</p>");
            else
               errorModalView.show("Not Saved", "error", "Project not saved.<hr /><p style='font-size:12px;color:gray;'>Backend Info: <br />"+escapeHtml(response.reason)+"</p>");

        }.bind(this), 
        error: function(model, response) {
          console.log(response);
           errorModalView.setprevmodal("#addprojectmodal");
           errorModalView.show("Not Saved", "error", "There is an error processing the request due to backend error.<hr /><p style='font-size:12px;color:gray;'>Error Info: "+escapeHtml(response.responseText)+"</p>");
        }

      });
  },
  showprojectmodal: function(e) {
    myModel.clear();
    this.reset();
    this.$el.modal({focus: false}).show();
  },
  sync_fields: function() {
      this.reset();
      this.elems.projectname.val(myModel.get("ProjectName"));
      this.elems.startdate.val(myModel.get("StartDate"));
      this.elems.enddate.val(myModel.get("EndDate"));
      this.elems.finished.get(0).checked = (myModel.get("ProjectDone") == "1" ? true : false);
      if (parseInt(myModel.get("ProjectDone")) > 0) {
         $("#isactive").prop("checked", true);
      } else {
         $("#isactive").prop("checked", false);
      }
      this.$el.modal("show");
  },
  initialize:function() {
    this.elems.addproject_button.on("click", this.showprojectmodal.bind(this));
    this.elems.startdate.datepicker({
      dateFormat:"yy-mm-dd",
      changeMonth: true,
      changeYear: true
    });
    this.elems.enddate.datepicker({
      dateFormat:"yy-mm-dd",
      changeMonth: true,
      changeYear: true
    });
    this.progressMode.bind(this);
    $("#addprojectmodal").on("hide.bs.modal", this.onhide.bind(this));
    // myModel.on("sync", this.sync_fields.bind(this));
  }
});

myView = new view();
var errorModalView = new errorModal();
  
</script>
[/Scripts]
