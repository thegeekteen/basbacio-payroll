[Title]EZ-Payroll [ v.<?php echo $this->config->item("version");?> Basbacio Version ][/Title]

[Styles]
<style>
[handler='color_selector'] {
  border-style:solid;border-color:#c6c6c6;border-width:1px;height:50px;width:50px;display:inline-block;margin:5px;cursor:pointer;
}
</style>
[/Styles]

[Contents]

          <h1 class="h3 mb-4 text-<?php echo $ClassName;?>"><span class="glyphicons"></span> Configuration <span style="font-size:12px;">[ v.<?php echo $this->config->item("version") . " " . $this->config->item("registered_to");?> ]</span></h1>

          <div class="row">

            <div class="col-xl-12 col-lg-12">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div style="cursor:pointer;" class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-<?php echo $ClassName;?>"><span class="glyphicons"></span> Data & Backup <button id="unlockUpload" class="btn btn-sm btn-outline-<?php echo $ClassName;?>" style=''>Unlock</button></h6>
                  <span class="glyphicons" display='caret' style="float:right;"></span>
                </div>
                <!-- Card Body -->
                <div class="card-body" style="display:none;">
                  <h5>Current Data</h5>
                    <label>Projects:</label> <span for-total="projects" style="font-size:12px;">0</span>
                     <br />
                   <label>Employees:</label> <span for-total="employees" style="font-size:12px;">0</span>
                     <br />
                    <label>Weekly Ranges:</label> <span for-total="weekly" style="font-size:12px;">0</span>
                     <br />
                    <label>Weekly Data:</label> <span for-total="weeklydata" style="font-size:12px;">0</span>
                    <p for-text="lockMessage" style='font-size:12px;'>Download Currently Locked. Please click the unlock button</p>
                     <div for-unlock="*" style="display:none;">
                    <button id="downloadDb" class="btn btn-sm btn-primary">Download Database</button>
                    <button id="resetDb" class="btn btn-sm btn-danger">Reset Database</button>
                  </div>

                  <hr />

                  

                  <h5>Restore Backup</h5>

                  <label>File Name:</label> <span form-display="upload-filename" style="font-size:12px;">N/A</span>
                  <br />
                  <label>File Type:</label> <span form-display="upload-filetype"style="font-size:12px;">N/A</span>
                  <br />
                  <label>File Size:</label> <span form-display="upload-filesize" style="font-size:12px;">N/A</span>
                  <br />
                  <p for-text="lockMessage" style='font-size:12px;'>Restore Currently Locked. Please click the unlock button</p>

                  <div for-unlock="*" style="display:none;">
                  <input type="file" id="backupFile" style="display:none;" />
                  <button id="openFile" onclick="document.getElementById('backupFile').click()" class="btn btn-sm btn-warning">Open New File</button>
                  <button id="uploadFile" class="btn btn-sm btn-success">Save Backup File</button>
                  </div>

               </div>
            </div>
          </div>

              <div class="col-xl-12 col-lg-12">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div style="cursor:pointer;" class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-<?php echo $ClassName;?>"><span class="glyphicons"></span> Colors & Appearance</h6>
                  <span class="glyphicons" display='caret' style="float:right;"></span>
                </div>
                <!-- Card Body -->
                <div class="card-body" style="display:none;">


                  <div handler='color_selector' class="bg-gradient-primary"></div>

                  <div handler='color_selector' class="bg-gradient-success"></div>

                  <div handler='color_selector' class="bg-gradient-info"></div>

                  <div handler='color_selector' class="bg-gradient-warning"></div>

                  <div handler='color_selector' class="bg-gradient-danger"></div>

                  <div handler='color_selector' class="bg-gradient-secondary"></div>

                  <div handler='color_selector' class="bg-gradient-dark"></div>

               </div>
            </div>
          </div>

          <div class="col-xl-12 col-lg-12">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div style="cursor:pointer;" class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-<?php echo $ClassName;?>"><span class="glyphicons"></span> Business Information</h6>
                  <span display='caret' class="glyphicons" style="float:right;"></span>
                </div>
                <!-- Card Body -->
                <div form-name="business_information" class="card-body" style="display:none;">
                  <label>Business Name: </label>
                  <input id="business_name" name="BusinessName" type="text" class="form-control" />
                  <hr />
                  <label>Business Address: </label>
                  <input id="business_address" type="text" name="BusinessAddress" class="form-control" />

                <div style=";margin-top:10px;">
                 <button for-form="business_information" class="btn btn-success" style="display:block;">Save Changes</button>
                </div>
               </div>
            </div>
          </div>

          <div class="col-xl-12 col-lg-12">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div style="cursor:pointer;" class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-<?php echo $ClassName;?>"><span class="glyphicons"></span> Print Employee Names</h6>
                  <span display='caret' class="glyphicons" style="float:right;"></span>
                </div>
                <!-- Card Body -->
                <div form-name="print_information" class="card-body" style="display:none;">
                  <label>Prepared By [Name]: </label>
                  <input id="preparedby" name="PrintPreparedBy" type="text" class="form-control" />
                  <label style="margin-top:5px;">Position: </label>
                  <input id="preparedby_position" name="PrintPreparedByPosition" type="text" class="form-control" />
                  <hr />
                  <label>Checked By [Name]: </label>
                  <input id="checkedby" name="PrintCheckedBy" type="text" class="form-control" />
                  <label style="margin-top:5px;">Position: </label>
                  <input id="checkedby_position" name="PrintCheckedByPosition" type="text" class="form-control" />
                  <hr />
                  <label>Released By [Name]: </label>
                  <input id="releasedby" name="PrintReleasedBy" type="text" class="form-control" />
                  <label style="margin-top:5px;">Position: </label>
                  <input id="releasedby_position" name="PrintReleasedByPosition" type="text" class="form-control" />

                <div style=";margin-top:10px;">
                 <button for-form="print_information" class="btn btn-success" style="display:block;">Save Changes</button>
                </div>
               </div>
            </div>
          </div>

            <!-- Area Chart -->
            <div class="col-xl-12 col-lg-12">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div style="cursor:pointer;" class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-<?php echo $ClassName;?>"><span class="glyphicons"></span> Login [Username]</h6>
                  <span class="glyphicons" display='caret' style="float:right;"></span>
                </div>
                <!-- Card Body -->
                <div form-name="username" class="card-body" style="display:none;">

                  <label>Username: </label>
                  <input id="username" name="LoginUsername" type="text" class="form-control" />
                 
                 <div style=";margin-top:10px;">
                 <button for-form="username" class="btn btn-success" style="display:block;">Save Username</button>
                </div>
               </div>
            </div>
          </div>

          <div class="col-xl-12 col-lg-12">
              <div class="card shadow mb-4">
                <!-- Card Header - Dropdown -->
                <div style="cursor:pointer;" class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-<?php echo $ClassName;?>"><span class="glyphicons"></span> Login [Password]</h6>
                  <span display='caret' class="glyphicons" style="float:right;"></span>
                </div>
                <!-- Card Body -->
                <div form-name="login_password" class="card-body" style="display:none;">

                  <label>Old Password: </label>
                  <input id="oldpassword" name="OldLoginPassword" type="password" class="form-control" />

                  <label style="margin-top:5px;">New Password: </label>
                  <input id="newpassword" name="LoginPassword" type="password" class="form-control" />

                  <label style="margin-top:5px;">Retype Password: </label>
                  <input id="newpassword2" type="password" name="LoginPassword2" class="form-control" />
                 
                 <div style=";margin-top:10px;">
                 <button for-form="login_password" class="btn btn-success" style="display:block;">Save Password</button>
                </div>
               </div>
            </div>
          </div>

          <div class="col-xl-12 col-lg-12">
              <div style="cursor:pointer;" class="card shadow mb-4" style="display:none;">
                <!-- Card Header - Dropdown -->
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                  <h6 class="m-0 font-weight-bold text-<?php echo $ClassName;?>"><span class="glyphicons"></span> Weekly Lock Password</h6>
                  <span display='caret' class="glyphicons" style="float:right;"></span>
                </div>
                <!-- Card Body -->
                <div form-name="weeklylockpassword" class="card-body" style="display:none;">

                  <label>Old Password: </label>
                  <input id="woldpassword" type="password" name="OldWeeklyLockPassword" class="form-control" />

                  <label style="margin-top:5px;">New Password: </label>
                  <input id="wnewpassword" type="password" name="WeeklyLockPassword" class="form-control" />

                  <label style="margin-top:5px;">Retype Password: </label>
                  <input id="wnewpassword2" type="password" name="WeeklyLockPassword2" class="form-control" />
                 
                 <div style=";margin-top:10px;">
                 <button for-form="weeklylockpassword" class="btn btn-success" style="display:block;">Save Password</button>
                </div>
               </div>
            </div>
          </div>

            
            </div>


          <div class="modal" id="progressModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h5 class="modal-title" id="exampleModalLabel">Uploading File</h5>
                 
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

          <div class="modal fade" id="lockunlock-modal" tabindex="-1" role="dialog" aria-hidden="true">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header bg-gradient-danger" style="color:white;">
                <h5 class="modal-title"><span class="glyphicons"></span> Unlock Database</h5>
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
                <button id="lockunlocknow-btn" type="button" class="btn btn-primary">Unlock</button>
                <button for-loading="lockunlocknow-btn" style="display:none;" class="btn btn-primary" type="button" disabled>
                  <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                  Please Wait...
                </button>
              </div>
            </div>
          </div>
        </div>


        <!-- /.container-fluid -->
[/Contents]

[Scripts]
<script src="<?php echo base_url();?>assets/js/underscore.js"></script>
<script src="<?php echo base_url();?>assets/js/backbone.js"></script>
<script src="<?php echo base_url();?>assets/js/universal.js"></script>
<script>

  $("#weeklylockpassword").on("keydown", function(e) {
    if (e.keyCode == 13)
      $("#lockunlocknow-btn").trigger("click");
  });

  function serialize(form_name) {
    var inputs = $("[form-name='"+form_name+"']").find("input");
    var values = {};
    for (var f = 0; f < inputs.length; f++) {
      values[inputs[f].getAttribute("name")] = inputs[f].value;
    }
    return values;
  }

  var confirmDialog = new confirmModal();

  $("#resetDb").on("click", function() {
      var onconfirm = function() {
          confirmDialog.progressMode(true);
          $.ajax({
            url:"<?php echo base_url();?>config/reset",
            method:"POST",
            data:JSON.stringify({"Password": currentPassword}),
            dataType:"json",
            "success":function(response, status, options) {
                confirmDialog.progressMode(false);
                confirmDialog.hide();
                if (response.success) {
                  errorModalView.setprevmodal(null);
                  errorModalView.show("Reset", "success", "Database has been reset successfully.");
                   $("#errorModal").on("hidden.bs.modal", function() {
                      location.reload();
                  });
                } else {
                  errorModalView.setprevmodal(null);
                  errorModalView.show("Reset Failed", "error", response.reason);
                }
            },
            "error":function(jqxhr, status, options) {
                confirmDialog.progressMode(false);
                confirmDialog.hide();
                errorModalView.setprevmodal(null);
                errorModalView.show("Server Error", "error", "A server error occured: " + jqxhr.responseText);
            }
          });

      }
      confirmDialog.setconfirm(onconfirm);
      confirmDialog.show("Reset Database", "error", "Are you sure you want to reset the database?");
  });

  base_URL = '<?php echo base_url();?>';
   $("[mlink='config']").addClass("active");
   $(".card-header").on("click", function(e) {
      $(e.target.parentNode.parentNode).find(".card-body").toggle("fast", "swing");
      var caret =  $(e.target.parentNode.parentNode).find("[display='caret']");
      if (caret.html() == "\uE221") {
        caret.html("\uE222");
      } else {
         caret.html("\uE221");
      }
     
   });

   $("#unlockUpload").on("click", function() {
      $("#lockunlock-modal").modal("show");
   });

   var currentPassword = null;

$("#lockunlocknow-btn").on("click", function(e) {
  var wpassword = $("#weeklylockpassword");
  var pw = wpassword.val();
  var button = e.currentTarget;

    if (wpassword.val().length <= 0) {
      errorModalView.setprevmodal("#lockunlock-modal");
      errorModalView.show("Password Required", "error", "Please enter weeky lock password.");
      return;
    }

    $(button).css("display", "none");
    $(button.parentNode).find("[for-loading='lockunlocknow-btn']").css("display", "inline");
    $.ajax({
      url:base_URL+"config/verifypass",
      dataType:"json",
      method:"POST",
      data: JSON.stringify({"password":pw}),
      success: function(response) {
        $("#lockunlock-modal").modal("hide");
        if (response.success) {
          errorModalView.setprevmodal(null);
          errorModalView.show("Success", "success", "Database successfully unlocked!");
          $("[for-text='lockMessage'], [for-unlock='*']").toggle();
          $("#unlockUpload").css("display", "none");
          currentPassword = pw;
          
        } else {
          errorModalView.setprevmodal("#lockunlock-modal");
          errorModalView.show("Failed", "error", response.reason);
        }
      }.bind(this),
      error: function(jqXHR, error) {
        errorModalView.setprevmodal("#lockunlock-modal");
        errorModalView.show("Server Error", "error", "Server Error has occured. <br /><span style='font-size:12px;color:gray;'>Traceback: "+jqXHR.responseText+"</span>");
      }
    }).done(function() {
      $(button).css("display", "inline");
      $(button.parentNode).find("[for-loading='lockunlocknow-btn']").css("display", "none");
    });;
})


   configModel = Backbone.Model.extend({
      urlRoot:base_URL+"config/manageconfig",
      idAttribute:"ConfigID",
      validate:function(attrs, options) {
        if (typeof options.toValidate !== "undefined") {
            var toValidate = options.toValidate;
            for (var v = 0; v < toValidate.length; v++) {
                switch (toValidate[v]) {
                  case "BusinessName":
                      var value = attrs[toValidate[v]];
                      if (value.length <= 0)
                        return "Please enter your Business Name.";
                  break;

                  case "BusinessAddress":
                     var value = attrs[toValidate[v]];
                      if (value.length <= 0)
                        return "Please enter your Business Address.";
                  break;

                  case "LoginUsername":
                     var value = attrs[toValidate[v]];
                      if (value.length <= 0)
                        return "Please enter your Login Username.";
                  break;

                  case "LoginPassword":
                   var value = attrs[toValidate[v]];
                      if (value.length <= 0)
                        return "Please enter a Login Password.";
                  break;

                  case "LoginPassword2":
                   var value = attrs[toValidate[v]];
                      if (value.length <= 0)
                        return "Please enter a Login Password.";
                  break;

                  case "PrintPreparedBy":
                    var value = attrs[toValidate[v]];
                      if (value.length <= 0)
                        return "Please enter Prepared By Name.";
                  break;

                  case "PrintPreparedByPosition":
                    var value = attrs[toValidate[v]];
                      if (value.length <= 0)
                        return "Please enter a Prepared By position name.";
                  break;

                  case "PrintCheckedBy":
                    var value = attrs[toValidate[v]];
                      if (value.length <= 0)
                        return "Please enter Checked By name.";
                  break;

                  case "PrintCheckedByPosition":
                    var value = attrs[toValidate[v]];
                      if (value.length <= 0)
                        return "Please enter Checked By position.";
                  break;

                  case "PrintReleasedBy":
                    var value = attrs[toValidate[v]];
                      if (value.length <= 0)
                        return "Please enter Released By name.";
                  break;

                  case "PrintReleasedByPosition":
                    var value = attrs[toValidate[v]];
                      if (value.length <= 0)
                        return "Please enter Released By position.";
                  break;
                }
            }
        }
      },
      defaults:{
        "ConfigID":"1",
        "ClassName":"",
        "BusinessName":"",
        "BusinessAddress":"",
        "LoginUsername":"",
        "OldLoginPassword":"",
        "LoginPassword":"",
        "OldWeeklyLockPassword":"",
        "WeeklyLockPassword":"",
        "PrintPreparedBy":"",
        "PrintCheckedBy":"",
        "PrintReleasedBy":"",
        "PrintPreparedByPosition":"",
        "PrintCheckedByPosition":"",
        "PrintReleasedByPosition":""
      }
   });

   $("#backupFile").on("change", function() {
      var theFile = document.getElementById("backupFile");
      $("[form-display='upload-filename']").html(theFile.files[0].name);
      $("[form-display='upload-filetype']").html(theFile.files[0].type);
      $("[form-display='upload-filesize']").html((theFile.files[0].size / 1000000).toFixed(2) + "MB");
   });

   $("#downloadDb").on("click", function(e) {
       $("#progressModal").modal({
           backdrop: 'static', 
           keyboard: false
       });

       $.ajax({
            url: "<?php echo base_url();?>config/dbdownload",
            method: 'post',
            data: JSON.stringify({"Password" : currentPassword}),
            xhrFields: {
                'responseType': 'blob'
            },
            xhr: function () {
                var xhr = $.ajaxSettings.xhr();
                xhr.onprogress = function (e) {
                    // For downloads
                    if (e.lengthComputable) {
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
                return xhr;
            },
            "success":function(response) {
                $("#progressModal").modal("hide");
                var link = document.createElement('a');
                var filename = 'basbacio-payroll.db';
                link.href = URL.createObjectURL(response);
                link.download = filename;
                link.click();
            },
            "error":function(jqxhr, error, options) {
               $("#progressModal").modal("hide");
                errorModalView.setprevmodal(null);
                errorModalView.show("Database Not Downloaded", "error", "Server Error Occured: " + jqxhr.responseText + " | " + error);
            }
        });
   });


   $("#uploadFile").on("click", function() {

    if (currentPassword == null) {
      alert("Please unlock first!");
      return;
    }

    var fd = new FormData();
    fd.append("backupFile", document.getElementById("backupFile").files[0]);
    fd.append("password", currentPassword);
    $("#progressModal").modal({
         backdrop: 'static', 
         keyboard: false
    });

        $.ajax({
            url: "<?php echo base_url();?>config/files",
            method: 'post',
            data: fd,
            dataType:"json",
            processData: false,
            contentType: false,
            xhr: function () {
                var xhr = $.ajaxSettings.xhr();
                xhr.onprogress = function e() {
                    // For downloads
                    if (e.lengthComputable) {
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
                return xhr;
            },
            "success":function(response) {
                $("#progressModal").modal("hide");
                if (response.success) {
                  errorModalView.setprevmodal(null);
                  errorModalView.show("Database Restored", "success", "Database has been restored! The page will refresh automatically when you close this dialog.");
                  $("#errorModal").on("hidden.bs.modal", function() {
                      location.reload();
                  });
                } else {
                  errorModalView.setprevmodal(null);
                  errorModalView.show("Database Not Restored", "error", "Database read error occured: " + response.reason);
                }
            },
            "error":function(jqxhr) {
                errorModalView.setprevmodal(null);
                errorModalView.show("Database Not Restored", "error", "Server Error Occured: " + jqxhr.responseText);
            }
        });
   });




   var myConfigModel = new configModel();
   var errorModalView = new errorModal();


   configView = Backbone.View.extend({
      el:$("body"),
      model: myConfigModel,
      events: {
          "click div[handler='color_selector']" : "color_selector",
          "click [for-form]" : "save_changes"
      },
      save_changes : function(e) {
        var formType = e.currentTarget.getAttribute("for-form");

        if (formType == "login_password") {
            if ($("#newpassword").val() !== $("#newpassword2").val()) {
                errorModalView.setprevmodal(null);
                errorModalView.show("Password Not Match", "error", "Please retype your password!");
                return;
            }
        }
        
        if (formType == "weeklylockpassword") {
            if ($("#wnewpassword").val() !== $("#wnewpassword2").val()) {
                errorModalView.setprevmodal(null);
                errorModalView.show("Password Not Match", "error", "Please retype your password!");
                return;
            }
        }

        var cfg = new configModel();
        var theForm = serialize(formType);
        var ok = cfg.set(theForm, {validate:true, toValidate:Object.keys(theForm)});
        if (!ok) {
          errorModalView.setprevmodal(null);
          errorModalView.show("Invalid Values", "error", cfg.validationError);
          return;
        }
        

        cfg.save(null, {
          "patch":true,
          "success":function(model, response) {
              errorModalView.setprevmodal(null);
              errorModalView.show("Saved", "success", "Changes successfully saved!");
          },
          "error":function(model, response) {
            try {
              errorModalView.setprevmodal(null);
              errorModalView.show("Error", "error", JSON.parse(response.responseText)["reason"]);
            } catch (ex) {
              errorModalView.setprevmodal(null);
              errorModalView.show("Server Error", "error", response.responseText);
            }
          }
        });


      },
      c_progress : false,
      color_selector : function(e) {
          var progress = $('<hr />' + 
                 '<div class="spinner-border text-prmary" style="text-align:center;width:20px;height:20px;" role="status"></div>' +
                 '<span style="font-size:12px;"> Saving... Please wait.</span>');
          if (this.c_progress)
            return;

          $(e.currentTarget.parentNode).append(progress);

          this.c_progress = true;
          var cfg = new configModel();
          var elj = $(e.currentTarget);
          cfg.set({"ClassName":elj.attr("class").split("-")[2]});
          cfg.save(null, {
            patch:true,
            validate:false,
            success:function(model, response) {
                var bg_color = elj.css("background-color");
                $("div[handler='color_selector']").css("box-shadow", "0px 0px 0px");
                elj.css("box-shadow", "0px 0px 10px " + bg_color);

                var classes = $(".navbar-nav").attr("class");
                var rg = /(bg-gradient-[^\s]+)/;
                var current_color = rg.exec(classes)[0];
                
               
                $("#accordionSidebar").removeClass(current_color);
                $("#accordionSidebar").addClass(elj.attr("class"));
                var header_titles = $(".text-"+current_color.split("-")[2]);
                var header_button = $(".btn-outline-"+current_color.split("-")[2]);
                header_button.removeClass("btn-outline-"+current_color.split("-")[2]);
                header_button.addClass("btn-outline-"+elj.attr("class").split("-")[2]);
                console.log(".btn-outline-"+elj.attr("class").split("-")[2]);

                var prev_color = "text-"+current_color.split("-")[2];
                var set_color = "text-"+elj.attr("class").split("-")[2];
                header_titles.removeClass(prev_color);
                header_titles.addClass(set_color);
                this.c_progress = false;
                progress.remove();
            }.bind(this),
            error: function(model, response) {
                errorModalView.setprevmodal(null);
                errorModalView.show("Server Error", "error", response.responseText);
                this.c_progress = false;
                progress.remove();
            }.bind(this)
          });
      },
      update_fields:function(model) {
        $("[for-total='projects']").html(model.get("ProjectCount"));
        $("[for-total='employees']").html(model.get("EmployeeCount"));
        $("[for-total='weeklydata']").html(model.get("ProjectWeeklyDataCount"));
        $("[for-total='weekly']").html(model.get("ProjectWeeklyCount"));
        $("#business_name").val(model.get("BusinessName"));
        $("#business_address").val(model.get("BusinessAddress"));
        $("#username").val(model.get("LoginUsername"));
        $("#preparedby").val(model.get("PrintPreparedBy"));
        $("#preparedby_position").val(model.get("PrintPreparedByPosition"));
        $("#checkedby").val(model.get("PrintCheckedBy"));
        $("#checkedby_position").val(model.get("PrintCheckedByPosition"));
        $("#releasedby").val(model.get("PrintReleasedBy"));
        $("#releasedby_position").val(model.get("PrintCheckedByPosition"));
      },
      initialize:function() {
         this.model.fetch({success:this.update_fields.bind(this)});
         var mark_color = $("[handler='color_selector'][class='bg-gradient-<?php echo $ClassName;?>']");
         var color = mark_color.css("background-color");
         mark_color.css("box-shadow", "0px 0px 10px " + color);
      }
   });
   myConfigView = new configView();

 </script>
[/Scripts]