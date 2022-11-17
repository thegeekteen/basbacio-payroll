
var confirmDialog = new confirmModal();

var wkmodel = Backbone.Model.extend({
	urlRoot:base_URL+"viewproject/weekmanage",
	idAttribute:"ProjectWeeklyDataID",
	defaults:{
		"ProjectWeeklyDataID":"",
		"ProjectWeeklyID":"",
		"ProjectID":"",
		"Active":"",
		"Name":"",
		"EmployeeID":"",
		"Rate":"",
		"Saturday":"",
		"Sunday":"",
		"Monday":"",
		"Tuesday":"",
		"Wednesday":"",
		"Thursday":"",
		"Friday":"",
		"Saturday":"",
		"Sunday":"",
		"TotalDays":"",
		"Additional":"",
		"WkAmount":"",
		"Vale":"",
		"AdvanceVale":"",
		"ReceivedAmount":"",
		"Remarks":"",
		"Arrangement":""
	}
});

errorModalView = new errorModal();

var theView = Backbone.View.extend({
	tagName: "<body>",
	el: $("body"),
	elems: {
		"showrangemodal":$("#addrange-modal"),
		"addrangeloading":$("#addrangeloading"),
		"addrange_startdate":$("#addrange-modal #startdate"),
		"addrange_enddate":$("#addrange-modal #enddate"),
		"addemployee":$("#addemployee-modal"),
		"checkall":$("#addemployee-modal input#select_all"),
		"selected_count" : $("#addemployee-modal").find("span#selected_count"),
		"start_date":$("#startdate"),
		"addemployee_btn":$("#addemployee-btn"),
		"printdata_btn":$("#printdata-btn"),
		"addemployeenow_btn":$("#addemployeenow-btn"),
		"addemployee_loading":$("#addemployee-loading"),
		"printdata":$("#printdata-btn")
	},
	events: {
		"click #addrange-btn": "showrangemodal",
		"click #addrangenow-btn" : "addrange",
		"hide.bs.modal #addrange-modal" : "onhidemodal",
		"hide.bs.modal #addemployee-modal" : "onhidemodal",
		"change #addrange-modal #startdate" : "startdate_change",
		"click #addemployee-btn" : "addemployee",
		"change #daterange" : "daterangechange",
		"click #addemployee-modal [handler='employee_checkbox']" : "e_cbox_change",
		"search.dt #employees-table" : "update_checked_count",
		"draw.dt #employees-table" : "update_checked_count",
		"show.bs.modal #addrange-modal" : "onshowrangemodal",
		"show.bs.modal #addemployee-modal" : "onshowrangemodal",
		"click #addemployeenow-btn" : "addemployeenow",
		"click #printdata-btn" : "printdata",
		"click #lockunlock-btn" : "showunlockmodal",
		"click #lockunlocknow-btn" : "lockunlocknow"
	},
	"lockunlocknow" : function(e) {
		var wpassword = $("#weeklylockpassword");
		var button = e.currentTarget;

		if (wpassword.val().length <= 0) {
			errorModalView.setprevfunction(null);
			errorModalView.setprevmodal("#lockunlock-modal");
			errorModalView.show("Password Required", "error", "Please enter weeky lock password.");
			return;
		}

		$(button).css("display", "none");
		$(button.parentNode).find("[for-loading='lockunlocknow-btn']").css("display", "inline");
		$.ajax({
			url:base_URL+"viewproject/lockunlock",
			dataType:"json",
			method:"POST",
			data: JSON.stringify({"ProjectWeeklyID":projectweeklyid, "WeeklyLockPassword":wpassword.val()}),
			success: function(response) {
				$("#lockunlock-modal").modal("hide");
				if (response.success) {
					errorModalView.setprevfunction(null);
					errorModalView.setprevmodal(null);
					errorModalView.show("Success", "success", "Weekly data has been successfully unlocked/locked!");
					$("#daterange").trigger("change");
				} else {
					errorModalView.setprevfunction(null);
					errorModalView.setprevmodal("#lockunlock-modal");
					errorModalView.show("Failed", "error", response.reason);
				}
			}.bind(this),
			error: function(jqXHR, error) {
				errorModalView.setprevfunction(null);
				errorModalView.setprevmodal("#lockunlock-modal");
				errorModalView.show("Server Error", "error", "Server Error has occured. <br /><span style='font-size:12px;color:gray;'>Traceback: "+jqXHR.responseText+"</span>");
			}
		}).done(function() {
			$(button).css("display", "inline");
			$(button.parentNode).find("[for-loading='lockunlocknow-btn']").css("display", "none");
		});;

	},
	"showunlockmodal":function() {
		$("#lockunlock-modal").modal("show");
	},
	printdata:function() {
		/*var printmode = $("#printmode").val();
		window.open(base_URL+"viewproject/printpdf/"+projectweeklyid+"/"+printmode);
		return;*/

       $("#progressModal").modal({
           backdrop: 'static', 
           keyboard: false
       });


       var xhr = new XMLHttpRequest();
       var printmode = $("#printmode").val();

       xhr.open("GET", base_URL+"/viewproject/printpdf/"+projectweeklyid+"/"+printmode);


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
				errorModalView.setprevfunction(null);
				errorModalView.setprevmodal(null);
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
	},
	daterangechange:function(e) {
		checked_weekly = [];
		projectweeklyid = e.target.options[e.target.selectedIndex].getAttribute("project-weekly-id");
		if (projectweeklyid == null) {
			this.elems.addemployee_btn.prop("disabled", true);
			this.elems.printdata_btn.prop("disabled", true);
			$("#deleteselection-btn").prop("disabled", true);
			$("#resetselection-btn").prop("disabled", true);
			$("#lockunlock-btn").prop("disabled", true);
			theTable.clear().draw();
			return;
		}

		this.elems.printdata_btn.prop("disabled", false)
		$("#lockunlock-btn").prop("disabled", false);

		$.ajax({
			url: base_URL + "viewproject/getdaterange/"+projectweeklyid,
			dataType: "json",
			success: function(date_array) {
				/* 
				### FOR GETTING EARLIEST DATE ###
				var dates = date_array.map(function (x) {
					return new Date(x["date"]);
				});

				var latest = new Date(Math.max.apply(null,dates));
				var earliest = new Date(Math.min.apply(null,dates));
				var ex = earliest.getMonth()+1;
				var ex = (ex > 9 ? ex : "0" + ex);
				var earliestFormat = earliest.getFullYear() + "-" + ex + "-" + earliest.getDate();
				*/
				var dayMode = new Date(date_array["info"]["StartDate"]).getDay();
				if (dayMode == 6) {
					theTable.columns([6, 7, 8, 9]).visible(true);
					theTable.columns([10, 11, 12]).visible(false);
				} else if (dayMode == 3) {
					theTable.columns([6, 7, 8, 9]).visible(false);
					theTable.columns([10, 11, 12]).visible(true);
				}

				for (var c = 0; c < date_array["dates"].length; c++) {
					var dayName = $("th[week_column_num='"+date_array["dates"][c]["dayofweek"]+"']").attr("week_column_name");
					if (date_array["dates"][c]["dayofweek"] == "0")
						$("th[week_column_num='"+date_array["dates"][c]["dayofweek"]+"']").html("<span style='color:#D46A6A;'>" + dayName + "<br />" + "[" + date_array["dates"][c]["date"] + "]</span>");
					else
						$("th[week_column_num='"+date_array["dates"][c]["dayofweek"]+"']").html(dayName + "<br />" + "[" + date_array["dates"][c]["date"] + "]");
				}

				lockStatus = parseInt(date_array["info"]["LockStatus"]);
				if (lockStatus > 0) {
					this.elems.addemployee_btn.prop("disabled", true);
					$("#deleteselection-btn").prop("disabled", true);
					$("#resetselection-btn").prop("disabled", true);
					$("#lockunlock-btn").html("<span class='glyphicons'>\uE218</span> Unlock");
				}
				else {
					this.elems.addemployee_btn.prop("disabled", false);
					$("#deleteselection-btn").prop("disabled", false);
					$("#resetselection-btn").prop("disabled", false);
					$("#lockunlock-btn").html("<span class='glyphicons'>\uE217</span> Lock");
				}

				theTable.ajax.url(base_URL+"viewproject/weeklydata/"+projectweeklyid).load();
			}.bind(this),
			error: function(jqXHR, error) {
				alert("Cannot get date error. Traceback: " + jqXHR.responseText);
			}
		});
		
	},
	update_checked_count:function() {
			var selected_count = checked_employees.length;
			this.elems.selected_count.html(selected_count);

			var checked = $("#addemployee-modal [handler='employee_checkbox']:checked").length;
			var total = $("#addemployee-modal [handler='employee_checkbox']").length;

			if ((checked == total) && total > 0) {
				this.elems.checkall.get(0).checked = true;
			}
			else
				this.elems.checkall.get(0).checked = false;
	},
	selectallclick : function(e) {
		if (e.target.checked) {

			// check all
			var checkboxes = $("[handler='employee_checkbox']");
			for (var c = 0; c < checkboxes.length; c++) {
				if (checked_employees.indexOf($(checkboxes[c]).attr("employeeid")) < 0)
					checked_employees[checked_employees.length] = $(checkboxes[c]).attr("employeeid");
			}
			checkboxes.prop("checked", true);
		}
		else {
			// uncheck all
			var checkboxes = $("[handler='employee_checkbox']");
			for (var c = 0; c < checkboxes.length; c++) {
				var i = checked_employees.indexOf($(checkboxes[c]).attr("employeeid"));
				checked_employees.splice(i, 1);
			}
			checkboxes.prop("checked", false);
		}
		this.update_checked_count();
	},
	e_cbox_change: function(e) {
		var e_id = e.target.getAttribute("employeeid");
		if (e.target.checked)
			checked_employees[checked_employees.length] = e_id;
		else {
			var index = checked_employees.indexOf(e_id);
			if (index > -1)
				checked_employees.splice(index, 1);
		}

		this.update_checked_count();
	},
	"addemployee" : function(e) {
		this.elems.addemployee.modal({focus:false}).show();
	},
	startdate_change: function(e) {
		// check if sunday or wednesday
		var start_date = new Date(e.target.value);
		if (start_date.getDay() == 6)
			this.elems.addrange_enddate.val(plusday(e.target.value, 3));
		else if (start_date.getDay() == 3)
			this.elems.addrange_enddate.val(plusday(e.target.value, 2));
		else
			this.elems.addrange_enddate.val("INVALID");
	},
	onhidemodal: function(e) {
		if ($(e.target).data("noClosing")) {
			e.preventDefault();
        	e.stopPropagation();
			 return false;
		}
	},
	showrangemodal: function(e) {
		this.elems.showrangemodal.modal({focus: false}).show();
		this.elems.showrangemodal.data("noClosing", false);
	},
	onshowrangemodal: function(e) {
		$("#addrangenow-btn").css("display", "inline");
		this.elems.addemployeenow_btn.css("display", "inline");
		this.elems.addrangeloading.css("display", "none");
		this.elems.addemployee_loading.css("display", "none");
		this.elems.addemployee.data("noClosing", false);
		$("#employees-table_wrapper").css("pointer-events", "auto");
	},
	addemployeenow: function(e) {
		$(e.target).css("display", "none");
		this.elems.addemployee_loading.css("display", "inline");
		$("#addemployee-modal").data("noClosing", true);
		$("#employees-table_wrapper").css("pointer-events", "none");

		var req = $.ajax({
			url:base_URL+"/viewproject/add_employees/"+projectweeklyid,
			method:"POST",
			dataType:"json",
			data:JSON.stringify(checked_employees),
			success:function(response) {
				this.elems.addemployee.data("noClosing", false);
				if (response.success) {
					this.elems.addemployee.modal("hide");
					errorModalView.setprevfunction(null);
					errorModalView.setprevmodal(null);
					errorModalView.show("Employees Added", "success", "Employees added successfully");
					checked_employees = [];
					employeeTable.ajax.reload(null, false);
					this.update_checked_count();
					theTable.ajax.reload();
					return;
				}
				this.elems.addemployee.modal("hide");
				errorModalView.setprevfunction(null);
				errorModalView.setprevmodal(this.elems.addemployee);
				errorModalView.show("Employees Not Added", "error", "Employees not added. <hr /><span style='font-size:12px;color:gray;'>Traceback: "+response.reason+"</span>");
			}.bind(this),
			error:function(jqXHR, error) {
				this.elems.addemployee.data("noClosing", false);
				errorModalView.setprevfunction(null);
				errorModalView.setprevmodal(this.elems.addemployee);
				errorModalView.show("Employees Not Added", "error", "Employees not added. <hr /><span style='font-size:12px;color:gray;'>Traceback: "+jqXHR.responseText+"</span>");
			}.bind(this)
		});

	},
	addrange:function(e) {
		$(e.target).css("display", "none");
		this.elems.addrangeloading.css("display", "inline");

		var start_date = this.elems.start_date;

		// form validation
		if (!isValidDate(start_date.val())) {
			errorModalView.setprevfunction(null);
			errorModalView.setprevmodal("#addrange-modal");
			errorModalView.show("Not Added", "error", "Please enter a valid start date.");
			return;
		}

		var request = $.ajax({
		  url: base_URL+"viewproject/addrange/"+projectID,
		  method: "POST",
		  data: JSON.stringify({
		  	"StartDate": start_date.val()
		  }),
		  dataType: "json"
		});

		request.done(function( msg ) {
		if (typeof msg.success == undefined || !msg.success) {
		  this.elems.showrangemodal.modal("hide");
		  errorModalView.setprevfunction(null);
		  errorModalView.setprevmodal(null);
		  console.log(msg);
		  errorModalView.show("Range Not Added", "error", "Range not added.<hr /><span style='font-size:12px;color:gray;'>Traceback:<br />"+msg.reason+"</span>");
		  return;
		}

		this.elems.showrangemodal.modal("hide");
		errorModalView.setprevfunction(null);
		  errorModalView.setprevmodal(null);
		  errorModalView.show("Range Added", "success", "Range successfully added.");
		  errorModalView.$el.on("hide.bs.modal", function() {
		  	location.reload();
		  });
		}.bind(this));
		 
		request.fail(function( jqXHR, textStatus ) {
			errorModalView.setprevfunction(null);
			errorModalView.setprevmodal("#addrangemodal");
			errorModalView.show("Not Added", "error", "An error is encountered. <hr /><span style='color:gray;font-size:12px;'>"+jqXHR.responseText+"</span>");
		});
		// process adding range....
	},
	amountupdate:function(args) {
		var wkdata = new wkmodel();
		wkdata.set("ProjectWeeklyDataID", args["ProjectWeeklyDataID"]);
		wkdata.set(args["Type"], args["Value"]);
		wkdata.save(null, {
			success: function(model, response) {
				response["success"] = true;
				args.OnFinish(response);

			},
			error: function(model, response) {
				response_x = {};
	        	try {
	        		response_x = JSON.parse(response.responseText);
	        	} catch (ex) {
	        		response_x ["success"] = false;
	        		response_x ["reason"] = "Unknown Error Occured. Please contact your I.T. for support.";
	        	}
	        	args.OnFinish(response_x);
			}
		});
	},
	dayupdate:function(args) {
		var wkdata = new wkmodel();
		var dayNames = ["Sunday", "Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday"];
		wkdata.set("ProjectWeeklyDataID", args["ProjectWeeklyDataID"]);
		wkdata.set(dayNames[args["DayNum"]], args["Value"]);
		wkdata.save(null, {
        	success: function(model, response) {
        		response["success"] = true;
        		args.OnFinish(response);
	        },
	        error: function(model, response) {
	        	response_x = {};
	        	try {
	        		response_x = JSON.parse(response.responseText);
	        	} catch (ex) {
	        		response_x ["success"] = false;
	        		response_x ["reason"] = "Unknown Error Occured. Please contact your I.T. for support.";
	        	}
	        	args.OnFinish(response_x);
	        },
	        wait:true
	    });
	},
	inputchange:function(e) {
			var captured_value = e.target.value;
			if (captured_value == e.target.getAttribute("current_data"))
				return;

			var loc = theTable.cell(e.target.parentNode).index();
			var col = loc["column"];
			var row = loc["row"];
			var id = theTable.row(e.target.parentNode).id();

			e.target.setAttribute("disabled", true);

			// form validation

			var form_error_callback = function() {
				e.target.removeAttribute("disabled");
				e.target.value = e.target.getAttribute("current_data");
				captured_value = e.target.getAttribute("current_data");
				$(e.target).focus();	
			}
			

			var form_error_modal = function() {
				errorModalView.setprevmodal(null);
				errorModalView.setprevfunction(form_error_callback);
				errorModalView.show("Not Saved", "error", "Please enter a valid number");
			}

			if (parseInt(col) >= 6 && parseInt(col) <= 12) {
				if (parseFloat(captured_value) > 1|| !captured_value.match(/^\d(\.\d{1,2})?$/)) {
					form_error_modal();
					return;
				}
			}
		
			if (parseInt(col) >= 14 && parseInt(col) <= 19) {
				if (isNaN(captured_value)) {
					form_error_modal();
					return;
				}
			}

			if (parseInt(col) == 3) {
				if (isNaN(captured_value)) {
					form_error_modal();
					return;
				}
			}
		

			var clientRow = theTable.row(e.target.parentNode.parentNode).data();
			
			var spinner = '<span class="spinner-border" style="height:10px;width:10px;margin-left:5px;" role="status"></span>';
			var theSpinner = $(spinner);
			$(e.target.parentNode).append(theSpinner);

			var onFinish = function(newData) {
				theSpinner.remove();
				e.target.removeAttribute("disabled");
				if (!newData.success || newData.length <= 4 || !newData["success"]) {
					errorModalView.setprevmodal(null);
					errorModalView.setprevfunction(form_error_callback);
					errorModalView.show("Not Saved", "error", "Cannot save data due to an error.<br /><span style='font-size:12px;color:gray;'>"+newData.reason+"</span>");
					return;
				}
				e.target.setAttribute("current_data", captured_value);
				e.target.value = captured_value;
				var rowNum = theTable.row(e.target.parentNode.parentNode).index();
				if (col == 3)
					theTable.ajax.reload( null, false );
				else {
					theTable.cell({row: rowNum, column: 13}).data(newData["TotalDays"]);
					theTable.cell({row: rowNum, column: 16}).data(newData["WkAmount"]);
					theTable.cell({row: rowNum, column: 19}).data(newData["ReceivedAmount"]);
				}
			}

			if (parseInt(col) >= 6 && parseInt(col) <= 12) {
				var week_column_num = theTable.column(col).header().getAttribute("week_column_num");
				var args = {
					"ProjectWeeklyDataID":id,
					"DayNum":week_column_num,
					"Value":captured_value,
					"OnFinish":onFinish

				};
				this.dayupdate(args);
			} else if (col == 14) {
				// Additional
				var args = {
					"ProjectWeeklyDataID":id,
					"Type":"Additional",
					"Value":captured_value,
					"OnFinish":onFinish
				}
				this.amountupdate(args);
			} else if (col == 15) {
				// Vale
				var args = {
					"ProjectWeeklyDataID":id,
					"Type":"Deduction",
					"Value":captured_value,
					"OnFinish":onFinish
				}
				this.amountupdate(args);
			} else if (col == 17) {
				// Vale
				var args = {
					"ProjectWeeklyDataID":id,
					"Type":"Vale",
					"Value":captured_value,
					"OnFinish":onFinish
				}
				this.amountupdate(args);
			} else if (col == 18) {
				// AdvanceVale
				var args = {
					"ProjectWeeklyDataID":id,
					"Type":"AdvanceVale",
					"Value":captured_value,
					"OnFinish":onFinish
				}
				this.amountupdate(args);
			} else if (col == 20) {
				var args = {
					"ProjectWeeklyDataID":id,
					"Type":"Remarks",
					"Value":captured_value,
					"OnFinish":onFinish
				}
				this.amountupdate(args);
			} else if (col == 3) {
				var args = {
					"ProjectWeeklyDataID":id,
					"Type":"Arrangement",
					"Value":captured_value,
					"OnFinish":onFinish
				}
				this.amountupdate(args);
			}

	},
		render: function() {
			$.fn.dataTable.ext.order['dom-checkbox'] = function  ( settings, col )
			{
			    return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
			        return $('input', td).prop('checked') ? '1' : '0';
			    } );
			}

			$.fn.dataTable.ext.order['dom-text'] = function  ( settings, col )
			{
			    return this.api().column( col, {order:'index'} ).nodes().map( function ( td, i ) {
			        return $('input', td).val();
			    } );
			}

			checked_weekly = [];
			anotherTarget = [];
			theTable = $("#weekly-table").DataTable({
				 "processing": true,
			     "serverSide": false,
			     "rowId": 0,
			     "order": [[3, "asc"]],
			     "rowCallback": function( row, data ) {
			     	if (lockStatus > 0) {
			     		$("#weekly-table_wrapper #select_all").prop("disabled", true);
			     		cbox_html = "<input type='checkbox' disabled/>";
			     	}
			     	else {
			     		$("#weekly-table_wrapper #select_all").prop("disabled", false);
			     		if (checked_weekly.indexOf(data[0]) > -1)
		        			cbox_html = "<input type='checkbox' projectweeklydataid='"+data[0]+"' checked/>";
		        		else
		        			cbox_html =  "<input type='checkbox' projectweeklydataid='"+data[0]+"'/>";
			     	}
				    $("td:eq(0)", row).html(cbox_html);
				  },
			     "columnDefs": [ 
			        {
			        	"targets": [5, 16, 19],
			        	"render": function(data, type, row, meta) {
			        		return "₱ " + parseInt(data).toFixed(2);
			        	}
			        },
			        anotherTarget,
			        {
			            "targets": [0, 1],
			            "visible": false,
			            "searchable": false,
			            "defaultContent":""
			        },
			        {
			        	"targets":[2],
			        	"defaultContent":"",
			        	"className":"dt-center",
			        	"orderDataType": "dom-checkbox"
			        },
			        {
			        	"targets":[6, 7, 8, 9, 10, 11, 12],
			        	"render": function ( data, type, row, meta ) {
			        		var disabled_string = "";
			        		if (lockStatus > 0)
			        			disabled_string = "disabled";
					      	return "<input gotonext='true' style='width:135px;' value='"+data+"' "+disabled_string+"/>";
					    },
					    "orderDataType": "dom-text", 
					    'type': 'string',
					    "width":"100%",
					    "visible":false
			        },
			        {
			        	"targets":[14, 15, 17, 18],
			        	"render": function ( data, type, row, meta ) {
			        		var disabled_string = "";
			        		if (lockStatus > 0)
			        			disabled_string = "disabled";
					      	return "<input gotonext='true' style='width:140px;' value='"+data+"' "+disabled_string+"/>";
					    }
			        },
			        {
			        	"targets":[20],
			        	"render": function ( data, type, row, meta ) {
			        		var disabled_string = "";
			        		if (lockStatus > 0)
			        			disabled_string = "disabled";
					      	return "<input gotonext='true' style='width:230px;' type='text' value='"+data+"' "+disabled_string+"/>";
					    }
			        },
			        {
			        	"targets":[3],
			        	"render": function ( data, type, row, meta ) {
			        		var disabled_string = "";
			        		if (lockStatus > 0)
			        			disabled_string = "disabled";
					      	return "<input style='width:50px;' type='text' value='"+data+"' "+disabled_string+"/>";
					    },
					    "orderDataType": "dom-text"
			        }
			     ],
		       scrollX:true,
		       paging:true   
		       /*fixedColumns: {
		       		leftColumns:5
		       },*/
			});

			$("#weekly-table tbody").click("tr:not([class='shown'])", function(evt) {
				  var tr = $(evt.target).closest("tr");
			      var row = theTable.row( tr );
			 
			        if ( row.child.isShown() ) {
			            // This row is already open - close it
			            row.child.hide();
			            tr.removeClass('shown');
			        }
			        else {
			            // Open this row
			            row.child.show();
			            tr.addClass('shown');
			        }
			});

		    $("#weekly-table tbody").on("change", "input:not([type='checkbox'])", this.inputchange.bind(this));

		    $("#weekly-table tbody").on("focus", "input", function(e) {
				e.target.setAttribute("current_data", e.target.value);
				setTimeout(function() {
					$(e.target).select();
				}, 1);
				// e.target.value = "";
			});


			$("#deleteselection-btn").on("click", function(e) {
				if (checked_weekly.length <= 0)
					return;

				var confirm = function() {

					confirmDialog.progressMode(true);
					var success = function() {
						confirmDialog.progressMode(false);
						confirmDialog.hide();
						errorModalView.setprevmodal(null);
						errorModalView.setprevfunction(null);
						errorModalView.show(checked_weekly.length + " Rows Deleted", "success", "Selected rows deleted successfully.");
						checked_weekly = [];
						$("#weekly-table_wrapper #selected_count").html(checked_weekly.length);
						theTable.ajax.reload(null, false);
					}
					var fail = function(message) {
						confirmDialog.progressMode(false);
						confirmDialog.hide();
						errorModalView.setprevmodal(null);
						errorModalView.setprevfunction(null);
						errorModalView.show(checked_weekly.length + " Rows Not Deleted", "error", "Selected rows not deleted. <hr /> Traceback: <span style='font-size:12px;color:gray;'>"+message+"</span>");
					}
					$.ajax({
						url: base_URL + "viewproject/delete",
						method: "POST",
						data: JSON.stringify(checked_weekly),
						dataType: "json",
						success: function(response) {
							if (response["success"])
								success();
							else
								fail(response["reason"]);
						},
						error: function(jqXHR, error) {
							fail(jqXHR.responseText);
						}
					});
				}
				confirmDialog.setconfirm(confirm);
				confirmDialog.show("Confirm Employee Deletion", "error", "Are you sure you want to delete <b>" + checked_weekly.length + "</b> selected rows?");
			});


			//.DTFC_LeftWrapper
			$("#resetselection-btn").on("click", function() {
				$("#weekly-table_wrapper input[type='checkbox']").prop("checked", false);
				checked_weekly = "";
				update_weekly_selected_count();
			});

			$("#weekly-table").on("draw.dt", function(e) {
				/*for (var c = 0; c < checked_weekly.length; c++) {
					$("#weekly-table_wrapper .DTFC_LeftWrapper input[type='checkbox'][projectweeklydataid='"+checked_weekly[c]+"']").prop("checked", true);
				}*/
				update_weekly_selected_count();
			});

		

			var update_weekly_selected_count = function() {
				$("#weekly-table_wrapper #selected_count").html(checked_weekly.length);
				var checked = $("#weekly-table_wrapper input[type='checkbox']:not('#select_all'):checked").length;
		    	var total = $("#weekly-table_wrapper input[type='checkbox']:not('#select_all')").length;
		    	if (checked == total && total > 0)
		    		$("#weekly-table_wrapper #select_all").prop("checked", true);
		    	else
		    		$("#weekly-table_wrapper #select_all").prop("checked", false);
			}

		    $("#weekly-table_wrapper").not("#select_all").on("click", "input[type='checkbox']", function(e) {
		    	var projectweeklydataid = e.target.getAttribute("projectweeklydataid");
		    	if (e.target.checked)
		    		checked_weekly[checked_weekly.length] = projectweeklydataid;
		    	else {
	    			var index = checked_weekly.indexOf(projectweeklydataid);
	    			checked_weekly.splice(index,1);
		    	}

		    	update_weekly_selected_count();
		    });

			$("#weekly-table_wrapper #select_all").on("click", function(e) {
			e.stopPropagation();
			
			var checkboxes = $("#weekly-table_wrapper input[type='checkbox']:not('#select_all')");
				if (e.target.checked) {
					// select all
					console.log(checkboxes.length);
					for (var c = 0; c < checkboxes.length; c++) {

						var projectweeklydataid = checkboxes[c].getAttribute("projectweeklydataid");
						var in_list = checked_weekly.indexOf(projectweeklydataid);
						if (in_list < 0)
							checked_weekly[checked_weekly.length] = projectweeklydataid;
						// $("#weekly-table_wrapper .DTFC_LeftWrapper input[type='checkbox']").prop("checked", true);
						checkboxes.prop("checked", true);
					}
				} else {
					// deselect all
					for (var c = 0; c < checkboxes.length; c++) {
						var projectweeklydataid = checkboxes[c].getAttribute("projectweeklydataid");
						var in_list = checked_weekly.indexOf(projectweeklydataid);
						if (in_list > -1) {
							checked_weekly.splice(in_list, projectweeklydataid);
							$("#weekly-table_wrapper input[type='checkbox'][projectweeklydataid='"+projectweeklydataid+"']").prop("checked", false);
						}
					}
					checkboxes.prop("checked", false);
				}
				
				update_weekly_selected_count();
				
			});

			$("#weekly-table tbody").on("focusout", "input", function(e) {
				e.target.value = e.target.getAttribute("current_data");
			});

			scroller = $('#weekly-table_wrapper .dataTables_scrollBody');
			// prev_scroll = scroller.scrollLeft();
			$("#weekly-table tbody").on("keydown", "input[gotonext='true']", function(e) {
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
			});


			checked_employees = [];

			employeeTable = $("#employees-table").DataTable({
				serverSide:true,
				processing:true,
				ajax: base_URL+"employees/list",
			    rowId: 1,
			    columnDefs: [
				    {
				    	targets:[0],
				    	visible:false,
				    	defaultContent:""
				    },
			    	{
			    		"targets":[1],
			    		"render":function(data, type, row, meta) {
			    			if (checked_employees.indexOf(data) > -1)
			    				return "<input type='checkbox' handler='employee_checkbox' employeeid='"+data+"' checked />"
			    			else
			    				return "<input type='checkbox' handler='employee_checkbox' employeeid='"+data+"' />"
			    		},
			    		
			    		"orderDataType": "dom-checkbox"
			    	},
			    ]

			});

			$('#employees-table thead th input#select_all').on('click', function(e){
			   e.stopPropagation();
			   this.selectallclick(e);
			}.bind(this));




	},
	initialize: function() {
		 this.render();
		 this.elems.addrange_startdate.datepicker({
	      dateFormat:"yy-mm-dd",
	      changeMonth: true,
	      changeYear: true,
	      beforeShowDay: function(date) {
			    return [date.getDay() === 6 || date.getDay() === 3,''];
			}
	    });
		   $("#weeklylockpassword").on("keydown", function(e) {
		    if (e.keyCode == 13)
		      $("#lockunlocknow-btn").trigger("click");
		  });

		 document.getElementById("daterange").options.selectedIndex = 0;
		 this.elems.addemployee_btn.prop("disabled", true);
		 // myEmployees.on('change reset add remove', this.render, this);
	}
});
myView = new theView();

