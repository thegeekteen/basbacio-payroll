[Title]EZ-Payroll [ v.<?php echo $this->config->item("version");?> Basbacio Version ][/Title]

[Styles]
<link href="<?php echo base_url();?>assets/DataTables/datatables.min.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/jquery-ui-1.12.1.custom/jquery-ui.min.css" rel="stylesheet" />
<link href="<?php echo base_url();?>assets/fixedcolumns/css/fixedColumns.bootstrap4.min.css" rel="stylesheet" />
<style>
  .mWidth {
    min-width:150px;
    max-width:150px;
    width:150px;
    vertical-align:middle;
  }

  .mWidth > input {
    display:block;
    width:100%;
    height:40px;
    margin:0px;
  }

  table.dataTable td {
    padding:10px;
  }

  .checkbox_container {
    text-align:center;
    min-width:130px;
  }

  .onhover {
    padding:10px;
    color:gray;
    font-size:12px;
    border-bottom-style:solid;
    border-color:#c6c6c6;
    border-width:1px;
  }

  .vhcenter {
    vertical-align:center;
    text-align:center;
  }

  .onhover:hover {
    background-color:#efefef;
    cursor:pointer;
  }

</style>
[/Styles]

[Contents]
<div id="mainView">
<div style="margin-bottom:15px;">


<h1 class="h3 mb-4 text-<?php echo $ClassName;?>"><span class="glyphicons"></span> Vouchers <span style="font-size:12px;">[ v.<?php echo $this->config->item("version") . " " . $this->config->item("registered_to");?> ]</span></h1>
<span style="font-size:12px;display:block;margin-bottom:5px;">Management:</span>
  <button id="addreceipt_button" class="btn btn-success btn-sm"><span class="glyphicons"></span> Add Receipts</button>
  <button id="deletereceipt_button" class="btn btn-danger btn-sm"><span class="glyphicons"></span> Delete Selected</button>
  <button id="resetselection_button" class="btn btn-warning btn-sm"><span class="glyphicons"></span> Reset Selection</button>
</div>

      <table id="mainlist" class="table table-striped" style="min-width:100%;">
        <thead>
          <th><input id='select_all' type='checkbox'></input> # <span style='font-size:12px;color:gray;font-weight:normal;'>[<span id='selected_count'>0</span> Selected]</span></th>
          <th>VoucherNo</th>
          <th>Date</th>
          <th>Payee</th>
          <th>Total Volume</th>
          <th>Total Amount</th>
          <th>Options</th>
        </thead>
        <tbody style="cursor:pointer;">
        </tbody>
      </table>
 </div>

   <div class="modal fade" id="addeditreceipts" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" style="min-width:95%;" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#55AA55;color:white;">
        <h5 class="modal-title" id="exampleModalLabel">Add/Edit Receipts</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">

       <label>Voucher No</label>
       <input id="ReceiptNo" gotonext="true" class="form-control" style="display:block;border-radius:0px;" />
       <label>Date</label>
       <input id="Date" class="form-control" style="display:block;border-radius:0px;" />
       <label>Owner/Payee Name</label>
       <input id="OwnerName" class="form-control" style="display:block;border-radius:0px;" />
       <label>Total Amount</label>
       <input id="TotalAmount" class="form-control" style="display:block;border-radius:0px;" readonly/>
       <label>Total Volume</label>
       <input id="TotalVolume" class="form-control" style="display:block;border-radius:0px;" readonly/>
       <hr />
       <button id="removeSelected" class="btn btn-sm btn-danger">Remove Selected</button>
       <hr />

          <table id="modaltable" class="table table-striped">
            <thead>
              <th><span style="font-weight:bold;font-size:12px;"><input id='select_all' type='checkbox'></input> # [<span id='selected_count'>0</span> Selected]</span></th>
              <th>Date</th>
              <th>Owner Name</th>
              <th>Plate No</th>
              <th>DR No</th>
              <th>Item</th>
              <th>Length</th>
              <th>Width</th>
              <th>Height</th>
              <th>Volume</th>
              <th>Difference</th>
              <th>Unit Price</th>
              <th>Total</th>
              <th>Options</th>
            </thead>
            <tbody>
              
            </tbody>
          </table>

       <div style="border-top-style:solid;border-color:#c6c6c6;border-width:1px;padding-top:5px;margin-top:10px;">
          <div style="float:right">
            <button id="addRow" class="btn btn-success">Add Row</button>
          </div>
        </div>


      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button id="savebtn" type="button" class="btn btn-primary">Save changes</button>
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
  modalView = Backbone.View.extend({
    el:$("#addeditreceipts"),
    events: {
      "change #modaltable tbody input" : 'inputchange',
      "click #modaltable tbody button[caller='deletebtn']" : 'deleteRow',
      "focus #modaltable tbody input[key='Date']" :'datepicker_render',
      "mousedown #searchContext .item" : 'searchresultclick',
      "focus #modaltable tbody input[key='PlateNo']" : 'platefocus',
      "focusout #modaltable tbody input[key='PlateNo']" : 'hidesearch',
      "keyup #modaltable tbody input[key='PlateNo']" : 'onplatesearch',
      "keydown #modaltable tbody input[key='PlateNo']" : 'onplatesearch',
      "search.dt #modaltable" : 'table_numbering',
      "order.dt #modaltable" : 'table_numbering',
      "draw.dt #modaltable" : 'table_numbering',
      "click #modaltable tbody input[mtable-selector='true']" : 'table_multiselect',
      "change #modaltable_wrapper input[id='select_all']" : 'select_all',
      "click #removeSelected" : 'deleteSelectedRow',
      "click #savebtn" : 'savedata',
      "change input#ReceiptNo, input#OwnerName, input#TotalVolume, input#TotalAmount, input#Date" : "receiptInfoChange",
      "click #addRow" : "addRow",
      "hide.bs.modal" : "onmodalhide"
    },
    keyboard_focus:function(e) {
      if (e.keyCode==39) {
          // next one;
          var current = e.target.parentNode.nextSibling;
          while (current != null) {
            var ipt = $(current).find("[gotonext='true']");
            if (ipt.length > 0) {
              // var crn = $(current).find("[gotonext='true']");
              
              ipt.focus();
              //scroller.scrollLeft(ipt.offset().left + ipt.width());
              break;
            }
            current = current.nextSibling;
          }
        }
        if (e.keyCode==37) {
          // prev one;
          var current = e.target.parentNode.previousSibling;
          while (current != null) {
            var ipt = $(current).find("[gotonext='true']");
            if (ipt.length > 0) {
              
              // var crn = $(current).find("[gotonext='true']");
              // $('#weekly-table_wrapper .dataTables_scrollBody').scrollLeft(crn.position().left + (crn.width() - 100));
              
              ipt.focus();
              // scroller.scrollLeft(ipt.offset().left - 750);
              
              break;
            }
            current = current.previousSibling;
          }
        }
        if (e.keyCode==40) {
          e.preventDefault();
          // go down
          var current_index = e.target.parentNode.cellIndex;
          if (current_index > 4) {
            return;
          }
          if (e.target.parentNode.parentNode.nextSibling == null)
            return;

          var current = e.target.parentNode.parentNode.nextSibling.children[4].children[0];
          $(current).focus();

        }
        if (e.keyCode==38) {
          e.preventDefault();
          // go up
          var current_index = e.target.parentNode.cellIndex;
          if (current_index > 4) {
            return;
          }
          if (e.target.parentNode.parentNode.previousSibling == null)
            return;
          var current = e.target.parentNode.parentNode.previousSibling.children[4].children[0];
          $(current).focus();

        }
    },
    initialize:function() {
      $.fn.dataTable.ext.order['dom-text'] = function  ( settings, col )
      {
          return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
              return $('input', td).val();
          } );
      };

      this.modalTable = this.$("#modaltable").DataTable({
        scrollY:        "300px",
        scrollX:        true,
        order:[[1, "asc"]],
        columnDefs:[
        {
          "targets":0,
          "width":"50px",
          "orderable":false,
          "className":"checkbox_container"
        },
          {
            "targets":[1, 2, 3 ,4 ,5 ,6, 7, 8, 9, 10, 11, 12],
            "className":"mWidth",
            "orderDataType": "dom-text",
            "type": 'string'
          },
          {
            "targets" : 10,
            "orderable":false,
            "className" : "mWidth"
          }
        ]
      });

      searchContext = $("<div id='searchContext' style='display:none;min-height:100px;max-height:200px;border-style:solid;border-width:1px;border-color:#c6c6c6;width:200px;background-color:#FFFFFF;position:fixed;overflow:auto;z-index:99999;font-size:12px;'></div>");
      noResults = "<p style='text-align:center;vertical-align:middle;line-height:100px;'>No Results</p>";
      this.$(".modal-body").append(searchContext);

     this.$("#Date").datepicker({
          dateFormat:"yy-mm-dd",
          changeMonth: true,
          changeYear: true
     })
      this.renderTable.bind(this);
      this.recalculate.bind(this);
      this.getindexbyclientid.bind(this);
      this.fullrender_table.bind(this);
      this.update_check_count.bind(this);
      this.deleteSelectedRow.bind(this);
      this.validate.bind(this);
      // this.keyboard_focus.bind(this);
      // $("#modaltable tbody").on("keydown", "input[gotonext='true']", this.keyboard_focus);
    },
    autoId:<?php echo json_encode($autoId);?>,
    checkedids:[],
    rowData:[],
    remove_from_server:[],
    receiptInfo:{
      "AggregatesID":"",
      "ReceiptNo":"",
      "Date":"",
      "OwnerName":"",
      "TotalAmount":"",
      "TotalVolume":"",
      "Date":"",
    },
    onmodalhide : function(e) {
        if ($(e.currentTarget).data("noClosing")) {
          e.stopPropagation();
          e.preventDefault();
          return false;
        }
        if (this.onhide != null)
          this.onhide();
    },
    addRow : function() {
          var addRow = this.modalTable.row.add([
              '<td><input gotonext="true" class="form-control" type="checkbox" mtable-selector="true" /></td>',
              '<td><input gotonext="true" key="Date" /></td>',
              '<td><input gotonext="true" key="OwnerName" /></td>',
              '<td><input gotonext="true" key="PlateNo" /></td>',
              '<td><input gotonext="true" key="DRNo" /></td>',
              '<td><input gotonext="true" key="Item" /></td>',
              '<td><input gotonext="true" key="Length" /></td>',
              '<td><input gotonext="true" key="Width" /></td>',
              '<td><input gotonext="true" key="Height" /></td>',
              '<td><input gotonext="true" key="Volume" disabled/></td>',
              '<td><input gotonext="true" key="Difference" /></td>',
              '<td><input gotonext="true" key="UnitPrice" /></td>',
              '<td><input gotonext="true" key="TotalAmount" disabled/></td>',
              '<td><button caller="deletebtn" style="border-radius:0px;margin:0px;" class="btn btn-block btn-danger">Remove</button></td>'
            ]);
          var clientID = 'id' + genid();
          addRow.node().id = clientID;
          this.rowData[this.rowData.length] =  {
            "ClientID":clientID,
            "AggregatesDataID":"",
            "Date":"",
            "OwnerName":"",
            "DRNo":"",
            "PlateNo":"",
            "Item":"",
            "Length":"",
            "Width":"",
            "Height":"",
            "Volume":"",
            "Difference":"",
            "UnitPrice":"",
            "TotalAmount":""
          }
          addRow.draw(false);
    },
    receiptInfoChange: function(e) {
          var key = e.target.getAttribute("id");
          this.receiptInfo[key] = e.target.value;
    },
    savedata: function(e) {
          var sendData = {"receiptInfo":this.receiptInfo, "rowData":this.rowData, "remove_from_server":this.remove_from_server};
          // validate data first!

          var validate_message = this.validate(sendData);
          if (validate_message !== undefined) {
              this.$el.modal("hide");
               errorModal.setprevmodal(this.$el);
               errorModal.show("Form Error", "error", validate_message);
               return;
          }
          

          var jsonData = JSON.stringify(sendData);
          $.ajax({
            "url":"<?php echo base_url();?>receipts/save",
            "method":"POST",
            "type":"json",
            "data":jsonData,
            "success":function(res) {
               this.$el.modal("hide");
               errorModal.setprevmodal(null);
               errorModal.show("Saved", "success", "We have successfully saved your data. AggregatesID: " + res["AggregatesID"]);
               mView.mainTable.ajax.reload(null, false);
               this.autoId = res["AutoID"];
            }.bind(this),
            "error":function(res) {
               this.$el.modal("hide");
               errorModal.setprevmodal(this.$el);
               errorModal.show("Error", "error", "We cannot save your data. Error: " + res.responseJSON["reason"]);
            }.bind(this)
          });
      },
    validate: function(toValidate) {

      // validate receipt info..

       var rInfo = toValidate["receiptInfo"];
       for (var key in rInfo) {
          if (rInfo[key].length <= 0 && key !== "AggregatesID")
            return "Please input a valid: " + key;
       }

       // validate data
       var data = toValidate["rowData"];
       for (var i = 0; i < data.length; i++) {

          for (var key in data[i]) {
              if (key == "AggregatesDataID" || key == "AggregatesID")
              continue;

              if (data[i][key].length <= 0)
                return "Index: "+i+" | Please input a valid data for: " + key;

              if (key == "Length")
                if (data[i][key].length <= 0 || !isNumber(data[i][key]))
                  return "Index: "+i+" | Please enter a valid number for " + key;

              if (key == "Width")
                if (data[i][key].length <= 0 || !isNumber(data[i][key]))
                  return "Index: "+i+" | Please enter a valid number for " + key;

             if (key == "Height")
                if (data[i][key].length <= 0 || !isNumber(data[i][key]))
                  return "Index: "+i+" | Please enter a valid number for " + key;

             if (key == "UnitPrice")
                if (data[i][key].length <= 0 || !isNumber(data[i][key]))
                  return "Index: "+i+" | Please enter a valid number for " + key;
          }
       }

       return undefined;

    },
    progressMode : function(onprogress) {
      if (onprogress) {
        this.$el.data("noClosing", true);
        this.$(".spinner").css("display", "block");
        this.$("[handler='modal-loader']").css("display", "block");
      } else {
        this.$el.data("noClosing", false);
        this.$(".spinner").css("display", "none");
        this.$("[handler='modal-loader']").css("display", "none");
      }
    },
    select_all : function(e) {
        var checkboxes = $("#modaltable_wrapper [mtable-selector='true']");
        if (e.target.checked) {
          // Select All
          
          for (var c = 0; c < checkboxes.length; c++) {
              $(checkboxes[c]).prop("checked", true);
              var e_id = $(checkboxes[c].parentNode.parentNode).attr("id");
              if (this.checkedids.indexOf(e_id) < 0)
                this.checkedids[this.checkedids.length] = e_id;
          }
        } else {
          // Deselect All
         for (var c = 0; c < checkboxes.length; c++) {
              $(checkboxes[c]).prop("checked", false);
              var e_id = $(checkboxes[c].parentNode.parentNode).attr("id");
              var index = this.checkedids.indexOf(e_id);
              if (index > -1)
                this.checkedids.splice(index, 1);
          }
        }
        this.update_check_count();
    },
    table_multiselect : function(e) {
        var indexid = $(e.target.parentNode.parentNode).attr("id");
        if (e.target.checked) {
          this.checkedids[this.checkedids.length] = indexid;
        } else {
          var index = this.checkedids.indexOf(indexid);
          this.checkedids.splice(index, 1);
        }
        this.update_check_count();
    },
    update_check_count: function() {
        var checked = $("#modaltable_wrapper [mtable-selector='true']:checked").length;
        var total = $("#modaltable_wrapper [mtable-selector='true']").length;

        if (checked == total && total > 0)
          $("#modaltable_wrapper input[id='select_all']").prop("checked", true);
        else
          $("#modaltable_wrapper input[id='select_all']").prop("checked", false);

        $("#modaltable_wrapper span[id='selected_count']").html(this.checkedids.length);
    },
    table_numbering:function () {

      if (this.modalTable == undefined)
        return;

        this.modalTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = "<input type='checkbox' mtable-selector='true' index='"+this.modalTable.row(cell).id()+"'></input> "+(i+1);
            if (this.checkedids.indexOf(this.modalTable.row(cell).id()) > -1)
              $(cell).find("input[mtable-selector='true']").prop("checked", true);
        }.bind(this));

        this.update_check_count();
    },
    datepicker_render:function(e) {
        $(e.target).datepicker({
          dateFormat:"yy-mm-dd",
          changeMonth: true,
          changeYear: true
        });
    },
    searchresultclick:function(e) {
      var curRow = searchContext.data("currentIndex");
      var cTarget = $(e.currentTarget);
      var oname = cTarget.find("span[attr='OwnerName']").text();
      var plateno = cTarget.find("span[attr='PlateNo']").text();
      var lengthval = cTarget.find("span[attr='Length']").text();
      var widthval = cTarget.find("span[attr='Width']").text();
      this.rowData[curRow]["OwnerName"] = oname;
      this.rowData[curRow]["PlateNo"]  = plateno;
      this.rowData[curRow]["Length"] = lengthval;
      this.rowData[curRow]["Width"] = widthval;
      this.renderTable();
      this.recalculate();
      this.hidesearch();
    },
    platefocus:function(e) {
          var target = $(e.target).offset();
          var left = target.left;
          var top = target.top+40;
          searchContext.css("display", "block");
          searchContext.css("left", left);
          searchContext.css("top", top);
          searchContext.html(noResults);
          searchContext.data("currentIndex", this.modalTable.row(e.target.parentNode.parentNode).index());
          $(e.target).trigger("keyup");
    },
    hidesearch:function(e) {
      searchContext.css("display", "none");
    },
    onplatesearch:function(e) {
        $.ajax({
          "url":"<?php echo base_url();?>plates/searchPlate/"+encodeURI(e.target.value),
          "method":"GET",
          "dataType":"json",
          "success":function(response) {
            if (response.length <= 0) {
               searchContext.html(noResults);
               return;
            }

            var html = "";
            for (var i = 0; i < response.length; i++) {
              html += "<div class='item onhover'><span style='display:block;'>Name: <span attr='OwnerName'>"+response[i]["OwnerName"]+"</span><span><span style='display:block;'>PlateNo: <span attr='PlateNo'>"+response[i]["PlateNo"]+"</span></span><span style='display:block;'>Length: <span attr='Length'>"+response[i]["Length"]+"</span></span><span style='display:block;'>Width: <span attr='Width'>"+response[i]["Width"]+"</span></span></div>";
            }
            searchContext.html(html);
          },
          "error":function(response) {
            searchContext.html("<p style='text-align:center;vertical-align:middle;line-height:100px;'>Error Occured</p>");
          }
         });
    },
    deleteSelectedRow:function(e) {
        for (var i = 0; i < this.checkedids.length; i++) {
            var id = this.checkedids[i];
            var clientid = this.getindexbyclientid(id);
            var serverid = this.rowData[clientid]["AggregatesDataID"];
            if (serverid !== "" && serverid.length > 0)
              this.remove_from_server.push(serverid);
            this.rowData.splice(clientid, 1);
            this.modalTable.row("#"+id).remove();
        }
        this.modalTable.draw(false);
        this.checkedids = [];
        this.update_check_count();
        this.recalculate();
    },
    deleteRow:function(e) {
        var theRow = this.modalTable.row(e.target.parentNode.parentNode);
        var id = $(theRow.nodes()[0]).attr("id");
        var clientid = this.getindexbyclientid(id);

        var serverid = this.rowData[clientid]["AggregatesDataID"];
        if (serverid !== "" && serverid.length > 0)
          this.remove_from_server.push(serverid);
        this.rowData.splice(clientid, 1);
        theRow.remove().draw();
        this.recalculate();
        this.update_check_count();
    },
    recalculate: function() {
      var totalVolume = 0.00;
      var totalAmount = 0.00;
      var isValid = false;
      for (var i = 0; i < this.rowData.length; i++) {
          isValid = true;

          if (isNumber(this.rowData[i]["Length"]) && isNumber(this.rowData[i]["Width"]) && isNumber(this.rowData[i]["Height"])) {
              this.rowData[i]["Volume"] = this.rowData[i]["Length"] * this.rowData[i]["Width"] * this.rowData[i]["Height"];
              totalVolume += this.rowData[i]["Volume"];
              this.rowData[i]["Volume"] = parseFloat(this.rowData[i]["Volume"]).toFixed(2);
            } else {
            isValid = false
            this.rowData[i]["Volume"] = "";
          }

          if (isNumber(this.rowData[i]["UnitPrice"]) && isNumber(this.rowData[i]["Volume"])) {
             this.rowData[i]["TotalAmount"] = this.rowData[i]["Volume"] * this.rowData[i]["UnitPrice"];
             totalAmount += this.rowData[i]["TotalAmount"];
             this.rowData[i]["TotalAmount"] = parseFloat(this.rowData[i]["TotalAmount"]).toFixed(2);
           } else {
            isValid = false;
            this.rowData[i]["TotalAmount"] = "";
          }
         
      }

      this.$el.find("#TotalAmount").val("₱ " + comma(totalAmount.toFixed(2)));
      this.$el.find("#TotalVolume").val(comma(totalVolume.toFixed(2)));
      this.receiptInfo["TotalAmount"] = parseFloat(totalAmount).toFixed(2);
      this.receiptInfo["TotalVolume"] = parseFloat(totalVolume).toFixed(2);

      // return isValid;
    },
    renderTable: function() {
      for (var i = 0; i < this.rowData.length; i++) {
        var curRow = this.modalTable.rows("#"+this.rowData[i]["ClientID"]).nodes()[0];
        for (var keyName in this.rowData[i]) {
          if (keyName == "TotalAmount")
            $(curRow).find("input[key='"+keyName+"']").val("₱ " + comma(this.rowData[i][keyName]));
          else if (keyName == "Volume")
             $(curRow).find("input[key='"+keyName+"']").val(comma(this.rowData[i][keyName]));
          else
            $(curRow).find("input[key='"+keyName+"']").val(this.rowData[i][keyName]);
        }
      }
      this.modalTable.rows().invalidate("dom");
    },
    fullrender_table:function(data) {
      this.reset();
      this.rowData = data["rowData"];
      this.receiptInfo = data["receiptInfo"];
      for (var i = 0; i < this.rowData.length; i++) {
        var rowAdd = this.modalTable.row.add([  
              '<td><input gotonext="true" class="form-control" type="checkbox" mtable-selector="true" /></td>',
              '<td><input gotonext="true" key="Date" value="'+quoteattr(this.rowData[i]["Date"])+'" /></td>',
              '<td><input gotonext="true" key="OwnerName" value="'+quoteattr(this.rowData[i]["OwnerName"])+'" /></td>',
              '<td><input gotonext="true" key="PlateNo" value="'+quoteattr(this.rowData[i]["PlateNo"])+'" /></td>',
              '<td><input gotonext="true" key="DRNo" value="'+quoteattr(this.rowData[i]["DRNo"])+'" /></td>',
              '<td><input gotonext="true" key="Item" value="'+quoteattr(this.rowData[i]["Item"])+'" /></td>',
              '<td><input gotonext="true" key="Length" value="'+quoteattr(this.rowData[i]["Length"])+'" /></td>',
              '<td><input gotonext="true" key="Width" value="'+quoteattr(this.rowData[i]["Width"])+'" /></td>',
              '<td><input gotonext="true" key="Height" value="'+quoteattr(this.rowData[i]["Height"])+'" /></td>',
              '<td><input gotonext="true" key="Volume" value="'+quoteattr(comma(parseFloat(this.rowData[i]["Volume"]).toFixed(2)))+'" disabled/></td>',
              '<td><input gotonext="true" key="Difference" value="'+quoteattr(this.rowData[i]["Difference"])+'" /></td>',
              '<td><input gotonext="true" key="UnitPrice" value="'+quoteattr(this.rowData[i]["UnitPrice"])+'" /></td>',
              '<td><input gotonext="true" key="TotalAmount" value="'+quoteattr("₱ " + comma(parseFloat(this.rowData[i]["TotalAmount"]).toFixed(2)))+'" disabled/></td>',
              '<td><button caller="deletebtn" style="border-radius:0px;margin:0px;" class="btn btn-block btn-danger">Remove</button></td>'
          ]);
         var clientID = 'id' + genid();
         rowAdd.node().id = clientID;
         this.rowData[i]["ClientID"] = clientID;
      }
      this.$el.find("#OwnerName").val(this.receiptInfo["OwnerName"]);
      this.$el.find("#Date").val(this.receiptInfo["Date"]);
      this.$el.find("#ReceiptNo").val(this.receiptInfo["ReceiptNo"]);
      this.$el.find("#TotalAmount").val("₱ " + comma(parseFloat(this.receiptInfo["TotalAmount"]).toFixed(2)));
      this.$el.find("#TotalVolume").val(comma(parseFloat(this.receiptInfo["TotalVolume"]).toFixed(2)));
      this.modalTable.draw(false);
    },
    getindexbyclientid:function(id) {
      for (var i = 0; i < this.rowData.length; i++) {
          if (this.rowData[i]["ClientID"] == id)
            return i;
      }
      return -1;
    },
    inputchange : function(e) {
       var key = e.target.getAttribute("key");
       var index = this.getindexbyclientid($(this.modalTable.row(e.target.parentNode.parentNode).nodes()[0]).attr("id"));
       this.rowData[index][key] = e.target.value;
       this.recalculate();
       this.renderTable();
    },
    reset:function(e) {
        this.$el.find("#OwnerName").val("");
        this.$el.find("#Date").val("");
        this.$el.find("#ReceiptNo").val(this.autoId);
        this.$el.find("#TotalAmount").val("₱ 0.00");
        this.$el.find("#TotalVolume").val("0.00");

        this.rowData = [];
        this.receiptInfo = {
          "AggregatesID":"",
          "ReceiptNo":this.autoId,
          "Date":"",
          "OwnerName":"",
          "TotalAmount":"0.00",
          "TotalVolume":"0.00"
        };

        this.checkedids = [];
        this.remove_from_server = [];
        this.modalTable.clear();
        this.modalTable.draw();
    }
  });


// This is the mainView;
  mainView = Backbone.View.extend({
    el:$("#mainView"),
    initialize:function() {
      errorModal = new errorModal();
      confirmModal = new confirmModal();
      this.modalView = new modalView();
      this.mainTable = this.$el.find("#mainlist").DataTable({
           "processing": true,
           "serverSide": true,
           "scrollX":true,
           "ajax": "<?php echo base_url();?>receipts/list",
           "rowId": "AggregatesID",
           "columnDefs":[
              {
                "targets":0,
                "orderable":false,
                "className":"vhcenter",
                "data":"AggregatesID",
                "render" : function(data) {
                  return "<input type='checkbox' aggregatesid='"+data+"' />";
                }
              },
              {
                "width":"50px",
                "targets":1,
                "data":"ReceiptNo"
              },
              {
                "targets":2,
                "data":"Date"
              },
              {
                "targets":3,
                "data":"OwnerName"
              },
              {
                "targets":4,
                "data":"TotalVolume",
                "render":function(data) {
                  return comma(parseFloat(data).toFixed(2));
                }
              },
              {
                "targets":5,
                "data":"TotalAmount",
                 "render":function(data) {
                  return "₱ " + comma(parseFloat(data).toFixed(2));
                }
              },
              {
                "targets":6,
                "defaultContent":"<button method='print' class='btn btn-primary btn-sm'>Print</button> <button method='remove' class='btn btn-danger btn-sm'>Remove</button>"
              }
           ]
      });
      this.delete_items.bind(this);
    },
    events: {
      "click #resetselection_button" : "reset_selection",
      "click #addreceipt_button" : "addreceipt",
      "click #mainlist tbody td:not(:nth-child(1), :nth-child(7))" : "rowselected",
      "click #mainlist tbody button[method='remove']" : "deleterow",
      "change #mainlist_wrapper input#select_all" : "onselectall",
      "change #mainlist tbody tr input[type='checkbox']" : "onmarkrow",
      "order.dt #mainlist" : "table_numbering",
      "search.dt #mainlist" : "table_numbering",
      "draw.dt #mainlist" : "table_numbering",
      "click button#deletereceipt_button" : "delete_selected",
      "click #mainlist tbody tr button[method='print']" : "printSelected"
    },
    checkedids : [],
    reset_selection : function(e) {
      var checkboxes = this.$("#mainlist_wrapper input[type='checkbox']:checked").prop("checked", false);
      this.checkedids = [];
      this.update_check_count();
    },
    printSelected : function(e) {
      var id = $(e.target).closest("tr").attr("id");
      window.open("<?php echo base_url();?>receipts/print/"+id);
    },
    delete_selected : function(e) {
      var totalSelected = this.checkedids.length;
      if (totalSelected <= 0)
        return;

      confirmModal.show("Delete Selected Rows", "error", "Are you sure you want to delete the selected " + totalSelected + " row/s?");
      confirmModal.setconfirm(function() {
         confirmModal.progressMode(true);
         this.delete_items(JSON.stringify(this.checkedids));
         this.checkedids = [];
         this.update_check_count();
      }.bind(this));

    },
    onselectall : function(e) {
        var checkboxes = this.$("#mainlist_wrapper tbody tr input[type='checkbox']");
        if (e.target.checked) {
          // Select All
          
          for (var c = 0; c < checkboxes.length; c++) {
              $(checkboxes[c]).prop("checked", true);
              var e_id = $(checkboxes[c].parentNode.parentNode).attr("id");
              if (this.checkedids.indexOf(e_id) < 0)
                this.checkedids[this.checkedids.length] = e_id;
          }
        } else {
          // Deselect All
         for (var c = 0; c < checkboxes.length; c++) {
              $(checkboxes[c]).prop("checked", false);
              var e_id = $(checkboxes[c].parentNode.parentNode).attr("id");
              var index = this.checkedids.indexOf(e_id);
              if (index > -1)
                this.checkedids.splice(index, 1);
          }
        }
        this.update_check_count();
    },
    onmarkrow: function(e) {
        var indexid = $(e.target.parentNode.parentNode).attr("id");
        if (e.target.checked) {
          this.checkedids[this.checkedids.length] = indexid;
        } else {
          var index = this.checkedids.indexOf(indexid);
          this.checkedids.splice(index, 1);
        }
        this.update_check_count();
    },
    update_check_count : function() {
        var checked = this.$("#mainlist_wrapper tbody input[type='checkbox']:checked").length;
        var total = this.$("#mainlist_wrapper tbody input[type='checkbox']").length;


        if (checked == total && total > 0)
          this.$("#mainlist_wrapper input[id='select_all']").prop("checked", true);
        else
          this.$("#mainlist_wrapper input[id='select_all']").prop("checked", false);

        this.$("#mainlist_wrapper span[id='selected_count']").html(this.checkedids.length);
    },
    table_numbering:function () {
      if (this.mainTable == undefined)
        return;

        this.mainTable.column(0, {search:'applied', order:'applied'}).nodes().each( function (cell, i) {
            cell.innerHTML = "<input type='checkbox' aggregatesid='"+this.mainTable.row(cell).id()+"'></input> "+(i+1);
            if (this.checkedids.indexOf(this.mainTable.row(cell).id()) > -1)
              $(cell).find("input[type='checkbox']").prop("checked", true);
        }.bind(this));

        this.update_check_count();
    },
    "addreceipt" : function(e) {
        this.modalView.reset();
        $("#addeditreceipts").modal("show");
    },
    "rowselected" : function(e) {
        var id = this.mainTable.row(e.currentTarget.parentNode).id();
        this.modalView.$el.modal("show");
        this.modalView.progressMode(true);
        $.ajax({
          "url":"<?php echo base_url();?>receipts/get/"+id,
          "method":"GET",
          "dataType":"json",
          "success":function(res) {
              this.modalView.fullrender_table(res);
              this.modalView.progressMode(false);
          }.bind(this),
          "error":function(res) {

          }.bind(this)
        });
    },
    "delete_items" : function(values) {
          $.ajax({
          url:"<?php echo base_url();?>receipts/delete",
          method:"POST",
          dataType:"json",
          data: values,
          "success":function(res) {
                confirmModal.progressMode(false);
                confirmModal.hide();
                errorModal.setprevmodal(null);
                errorModal.show("Deleted", "success", "We have successfully deleted the selected row/s");
                this.mainTable.ajax.reload(null, false);
            }.bind(this),
          "error":function(res) {
                confirmModal.progressMode(false);
                confirmModal.hide();
                errorModal.setprevmodal(null);
                errorModal.show("Not Deleted", "error", "An error occured while deleting the selected row/s : " + res["reason"]);
            }.bind(this)
        });
    },
    "deleterow" : function(e) {
      var aggregatesid = this.mainTable.row($(e.target).closest("tr")).id();
      confirmModal.show("Delete Row", "error", "Are you sure you want to delete Aggregates ID: " + aggregatesid);
      confirmModal.setconfirm(function() {
        // Delete Now!
        confirmModal.progressMode(true);
        this.delete_items(JSON.stringify([aggregatesid]));
        this.checkedids.splice(aggregatesid, 1);
        this.update_check_count();
      }.bind(this));
    }
  });



window.onload = function() {
  mView = new mainView();
}
</script>
[/Scripts]
