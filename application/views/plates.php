[Title]EZ-Payroll [ v.<?php echo $this->config->item("version");?> Basbacio Version ][/Title]

[Styles]
<link href="<?php echo base_url();?>assets/DataTables/datatables.min.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/jquery-ui-1.12.1.custom/jquery-ui.min.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/fixedcolumns/css/fixedColumns.bootstrap4.min.css" rel="stylesheet" />
<style>
  #plates-list tbody tr td {
      vertical-align:middle;
  }
</style>
[/Styles]

[Contents]
<div id="mainView">
<div style="margin-bottom:15px;">


<h1 class="h3 mb-4 text-<?php echo $ClassName;?>"><span class="glyphicons"></span> Truck License Plates <span style="font-size:12px;">[ v.<?php echo $this->config->item("version") . " " . $this->config->item("registered_to");?> ]</span></h1>
<span style="font-size:12px;display:block;margin-bottom:5px;">Management:</span>
  <button id="addplate_button" class="btn btn-success btn-sm"><span class="glyphicons"></span> Add Truck/PlateNo</button>
  <button id="deleteplate_button" class="btn btn-danger btn-sm"><span class="glyphicons"></span> Delete Selected</button>
  <button id="resetselection_button" class="btn btn-warning btn-sm"><span class="glyphicons"></span> Reset Selection</button>
</div>

      <table id="plates-list" class="table table-striped" style="min-width:100%;">
        <thead>
          <th><input id='select_all' type='checkbox'></input> # <span style='font-size:12px;color:gray;font-weight:normal;'>[<span id='selected_count'>0</span> Selected]</span></th>
          <th column_name="owner">Owner/Payee Name</th>
          <th column_name="plateno">Plate No.</th>
          <th column_name="length">Length</th>
          <th column_name="width">Width</th>
          <th column_name="options">Options</th>
        </thead>
        <tbody style="cursor:pointer;">
        </tbody>
      </table>
 </div>

   <div class="modal fade" id="addeditplate" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="min-width:80%;" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#55AA55;color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Add/Edit License Plates</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <label>Owner/Payee Name:</label>
        <input model="platemodel" name="OwnerName" class="form-control" type="text" />
        <label>License Plate:</label>
        <input model="platemodel" name="PlateNo" class="form-control" type="text" />
        <label>Length:</label>
        <input model="platemodel" name="Length" class="form-control" type="text" />
        <label>Width:</label>
        <input model="platemodel" name="Width" class="form-control" type="text" />

       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="save_platebtn" type="button" class="btn btn-primary">Save changes</button>
      </div>

      <div handler="modal-loader" style="background-color:rgba(0, 0, 0, .15);height:100%;width:100%;position:absolute;top:0;left:0;overflow:none;display:none;"></div>
        <div class="spinner spinner-border text-<?php echo $ClassName;?>" style="display:none;" role="status">
          <span class="sr-only">Loading...</span>
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

// Model for Add/Edit Model
var plateModel = Backbone.Model.extend({
  idAttribute:"LicensePlateID",
  urlRoot:"<?php echo base_url();?>plates/manage",
  defaults:{
    "LicensePlateID":null,
    "OwnerName":"",
    "PlateNo":"",
    "Length":"",
    "Width":"",
  },
  validate: function(data) {
     if (data["OwnerName"].length <= 0) {
        return 'Please enter Owner Name';
     }
     if (data["PlateNo"].length <= 0) {
        return 'Please enter the License No';
     }
     if (data["Width"].length <= 0) {
        return 'Please enter a valid width';
     }
     if (data["Length"].length <= 0) {
        return 'Please enter a valid length';
     }
  }
});

// View for Add/Edit Modal
var plateView = Backbone.View.extend({
  el:$("#addeditplate"),
  model:null,
  events:{
      "change input[model='platemodel']" : function (e) {
         objName = e.target.getAttribute("name");
         objValue = e.target.value
         this.model.set({[objName] : objValue});
      },
      "hide.bs.modal" : function(e) {
        if ($(e.currentTarget).data("noClosing")) {
          e.stopPropagation();
          e.preventDefault();
          return false;
        }
      },
      "click #save_platebtn" : function(e) {
          if (!this.model.isValid()) {
              this.$el.modal("hide");
              mConfirmModal.setprevmodal(this.$el);
              mConfirmModal.show("Error", "error", "Form Validation: " + this.model.validationError);
              return;
          }

          this.progressMode(true);
          this.model.save(null, {
            validate:true,
            success:function(model, response) {
               this.progressMode(false);
               this.$el.modal("hide");
               mConfirmModal.setprevmodal(null);
               mConfirmModal.show("Success", "success", "Data successfully saved");
               mView.tableReload();

            }.bind(this), 
            error:function(model, response) {
                this.progressMode(false);
                this.$el.modal("hide");
                mConfirmModal.setprevmodal(this.$el);
                mConfirmModal.show("Error", "error", "An error occured: " + response.responseText);
                mView.tableReload();
            }.bind(this)
          });
      }
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
  updateFields : function() {
    this.$el.find("[name='OwnerName']").val(this.model.get("OwnerName"));
    this.$el.find("[name='PlateNo']").val(this.model.get("PlateNo"));
    this.$el.find("[name='Length']").val(this.model.get("Length"));
    this.$el.find("[name='Width']").val(this.model.get("Width"));
  },
  resetFields : function() {
    this.$el.find("[model='platemodel']").val("");
  },
  initialize:function() {
    _.bindAll(this, "updateFields", "resetFields");
  }
});

// View for the DataTables
var mainView = Backbone.View.extend({
  el:$("#mainView"),
  events:{
    "click #resetselection_button" : function(e) {
      this.checkedids = [];
      $("#plates-list_wrapper [indi='true'], #plates-list_wrapper input[id='select_all']").prop("checked", false);
      $("#plates-list_wrapper span[id='selected_count']").html(this.checkedids.length);
    },
    "click #addplate_button" : function(e) {
       myplateView.model = new plateModel();
       myplateView.resetFields();
       $("#addeditplate").modal("show");
    },
    "click #deleteplate_button" : function(e) {
        var totalSelected = this.checkedids.length;
        if (totalSelected <= 0)
          return;
        mDeleteModal.show("Confirm Delete", "error", "Are you sure you want to delete the selected <b>" + totalSelected + "</b> items?");
        mDeleteModal.setconfirm(function(e) {
           mDeleteModal.progressMode(true);
           var theIds = JSON.stringify(this.checkedids);
            $.ajax({
              url:"<?php echo base_url();?>plates/delete",
              method:"POST",
              dataType:"json",
              data: theIds,
              success: function(res) {
                 mDeleteModal.progressMode(false);
                 mDeleteModal.hide();
                 mConfirmModal.setprevmodal(null);
                 mConfirmModal.show("success", "Deleted", "We have successfully deleted the selected items.");
                 this.tableReload();
                
              }.bind(this),
              error: function(res) {
                mDeleteModal.progressMode(false);
                mDeleteModal.hide();
                mConfirmModal.setprevmodal(null);
                mConfirmModal.show("error", "Not Deleted", "Error encountered when deleting the selected items.");
                this.tableReload();
              }.bind(this)

            });
        }.bind(this));
    }
  },
  checkedids:[],
  render:function() {
            table = $("#plates-list").DataTable({
           "processing": true,
           "serverSide": true,
           "ajax": "<?php echo base_url();?>plates/listall",
           "rowId": "LicensePlateID",
           "columnDefs": [ 
              {
                  "targets": 0,
                  "searchable": false,
                  "defaultContent":"",
                  "orderable":false,
                  "visible":true,
                  "data":"LicensePlateID",
                  "render": function(data) {
                      return "<input type='checkbox' indi='true' value='' lid='"+data+"' />";
                  }
              },
               {
                  "targets": 1,
                  "searchable": true,
                  "defaultContent":"",
                  "visible":true,
                  "data":"OwnerName"
              },
               {
                  "targets": 2,
                  "searchable": true,
                  "defaultContent":"",
                 
                  "visible":true,
                  "data":"PlateNo"
              },
               {
                  "targets": 3,
                  "searchable": false,
                  "defaultContent":"",
                  
                  "visible":true,
                  "data":"Length"
              },
               {
                  "targets": 4,
                  "searchable": false,
                  "defaultContent":"",
                  "visible":true,
                  "data":"Width"
              },
               {
                  "targets": 5,
                  "searchable": false,
                  "defaultContent":"<button tpe='delete' class='btn btn-sm btn-danger'>Delete</button>",
                  
                  "visible":true,
              }
           ],
             scrollX:true,
             paging:true   
      });

      // on checklist change...
     $("#plates-list tbody").on("change", "input[indi='true']", this.checkchange.bind(this));
     // on datatable item click
     $('#plates-list tbody').on('click', 'td:not(:nth-child(1), :nth-child(6))', this.rowselected.bind(this));
     // on select all clicked
     $("#plates-list_wrapper input[id='select_all']").on("change", this.selectall.bind(this));
     // on search redraw
     table.on('search.dt order.dt draw.dt', this.checklist_render.bind(this)).draw();
     // on delete button
     $("#plates-list").on("click", "[tpe='delete']", this.sdelete.bind(this));

  },
  sdelete: function(e) {
      var getRow = e.target.parentNode.parentNode;
      theId = table.row(getRow).id();
      mDeleteModal.show("Delete Item", "error", "Are you sure you want to delete the selected item?");
      mDeleteModal.setconfirm(function(e) {
        mDeleteModal.progressMode(true);
        $.ajax({
          url:"<?php echo base_url();?>plates/delete",
          method:"POST",
          dataType:"json",
          data:JSON.stringify([theId]),
          success:function(res) {
            mDeleteModal.progressMode(false);
            mDeleteModal.hide();
            mDeleteModal.setconfirm(null);
            mConfirmModal.setprevmodal(null);
            mConfirmModal.show("success", "Data Deleted", "We have successfully deleted the data.");
            this.tableReload();
          }.bind(this),
          error: function(res) {
            mDeleteModal.progressMode(false);
            mDeleteModal.hide();
            mDeleteModal.setconfirm(null);
            mConfirmModal.setprevmodal(null);
            mConfirmModal.show("error", "Data Not Deleted", "Error occured in deleting the data.");
            this.tableReload();
          }.bind(this)
        });
      }.bind(this));
  },
  checklist_render:function(e) {
       table.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = "<input type='checkbox' indi='true' lid='"+table.row(cell).id()+"'></input> "+(i+1);
            if (this.checkedids.indexOf(table.row(cell).id()) > -1)
              $("#plates-list_wrapper input[lid='"+table.row(cell).id()+"']").prop("checked", true);
        }.bind(this));

        var checked = $("#plates-list_wrapper [indi='true']:checked").length;
        var total = $("#plates-list_wrapper [indi='true']").length;

        if (checked == total && total > 0)
          $("#plates-list_wrapper input[id='select_all']").prop("checked", true);
        else
         $("#plates-list_wrapper input[id='select_all']").prop("checked", false);
  },
  selectall:function(e) {
     var checkboxes = $("#plates-list_wrapper [indi='true']");
        if (e.target.checked) {
          // Select All
          
          for (var c = 0; c < checkboxes.length; c++) {
              $(checkboxes[c]).prop("checked", true);
              var e_id = $(checkboxes[c]).attr("lid");
              if (this.checkedids.indexOf(e_id) < 0)
                this.checkedids[this.checkedids.length] = e_id;
          }
        } else {
          // Deselect All
         for (var c = 0; c < checkboxes.length; c++) {
              $(checkboxes[c]).prop("checked", false);
              var e_id = $(checkboxes[c]).attr("lid");
              var index = this.checkedids.indexOf(e_id);
              if (index > -1)
                this.checkedids.splice(index, 1);
          }
        }
      $("#plates-list_wrapper span[id='selected_count']").html(this.checkedids.length);
  },
  rowselected:function(e) {
      myplateView.$el.modal("show");
      myplateView.progressMode(true);
      myplateView.model = new plateModel();
      myplateView.model.set("LicensePlateID", table.row(e.currentTarget.parentNode).id());
      myplateView.model.fetch({
            success: function(model, response) {
                myplateView.updateFields();
                myplateView.progressMode(false);
            },
            error: function(model, response) {
                this.progressMode(false);
                myplateView.$el.modal("hide");
                mConfirmModal.setprevmodal(null);
                mConfirmModal.show("Error", "error", "An error occured: " + response.responseText);
            }
      });
  },
  checkchange:function(e) {
     var l_id = e.target.getAttribute("lid");
        if (e.target.checked) {
          this.checkedids[this.checkedids.length] = l_id;
        } else {
          var index = this.checkedids.indexOf(l_id);
          this.checkedids.splice(index, 1);
        }
        $("#plates-list_wrapper span[id='selected_count']").html(this.checkedids.length);
        
        var checked = $("#plates-list_wrapper [indi='true']:checked").length;
        var total = $("#plates-list_wrapper [indi='true']").length;

        if (checked == total)
          $("#plates-list_wrapper input[id='select_all']").prop("checked", true);
        else
         $("#plates-list_wrapper input[id='select_all']").prop("checked", false);
  },
  tableReload: function() {
    table.ajax.reload(null, false);
  },
  initialize:function(e) {
    mDeleteModal = new confirmModal();
    mConfirmModal = new errorModal();
    myplateView = new plateView();
  }
});


window.onload = function() {
  mView = new mainView();
  mView.render();
}

</script>
[/Scripts]
