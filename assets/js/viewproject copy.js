// Models

var Employee = Backbone.Model.extend({
	idAttribute:"PDataID",
	defaults: {
		"PWTID": 0,
		"ProjectID": 0,
		"EmployeeID": 0,
		"FullName":"N/A",
		"Monday": 0,
		"Tuesday": 0,
		"Wednesday": 0,
		"Thursday": 0,
		"Friday": 0,
		"Saturday": 0,
		"Sunday": 0,
		"TotalDays": 0,
		"Additional": 0,
		"Vale":0,
		"AdvanceVale":0,
		"ReceivedAmount":0,
		"Remarks":"N/A",
		"Rate":0,
		"Transfered":false
	},
	initialize: function() {
		this.bind("change:FullName", function() {
			console.log("Name Changed!");
		});
	}
});

// Collection

Employees = Backbone.Collection.extend({
	model:Employee,
	parse: function(response) {
		return response;
	},
	initialize: function() {

	},
	process: function(projectid, weekid) {
		this.url = base_URL + "projects/view/"+projectid+"/"+weekid;
		this.fetch({
			type: 'POST',
		    success: function () {
		    	// nothing here
		    }
		});
	}
});

myEmployees = new Employees();
myEmployees.process();

// View

var theView = Backbone.View.extend({
	tagName: "<tbody>",
	el: $("#employee-table"),
	transfer_el: $("#transfer_modal"),
	daterange: $("#daterange"),
	daterangechanged: function(e) {
		alert(e.target.value);
		myEmployees.add({PDataID:4, "FullName":"Sonny Basbacio"});
	},
	events: {
    	"change input": "onupdate",
    	"click [typename='transfer']": "ontransfer"
	},
	spinner_template: '<span style="height:15px;width:15px;" typename="loading" class="spinner-border">',
	template: _.template('<tr PDataID="<%= PDataID%>">' +
              '<td><%= RowNumber %></td>' +
              '<td><%= FullName %></td>' +
              '<td><%= Rate %></td>' +
              '<td><input type="number" typename="monday" style="width:50px;display:inline;" value="<%= Monday %>" /></div></td>' +
              '<td><input type="number" typename="tuesday" value="<%= Tuesday %>"" style="width:50px;" /></td>' +
              '<td><input type="number" typename="wednesday" value="<%= Wednesday %>"" style="width:50px;" /></td>' +
              '<td><input type="number" typename="thursday" value="<%= Thursday %>" style="width:50px;" /></td>' +
              '<td><input type="number" typename="friday" value="<%= Friday %>"" style="width:50px;" /></td>' +
              '<td><input type="number" typename="saturday" value="<%= Saturday %>"" style="width:50px;" /></td>' +
              '<td><input type="number" typename="sunday" value="<%= Sunday %>"" style="width:50px;" /></td>' +
              '<td typename="TotalDays"><%= TotalDays %></td>' +
              '<td><input type="number" value="<%= Additional %>"" style="width:100px;" /></td>' +
              '<td typename="WkAmount"><%= WkAmount %></td>' +
              '<td><input type="number" value="<%= Vale %>"" style="width:100px;" /></td>' +
              '<td><input type="number" value="<%= AdvanceVale %>"" style="width:100px;" /></td>' +
              '<td><input type="number" value="<%= ReceivedAmount %>" style="width:100px;" readonly/></td>' +
              '<td><%= Remarks %></td>' +
              '<td><button typename="transfer" class="btn btn-warning btn-sm">Transfer</button></td>' +
            '</tr>'),

	onupdate: function(e) {
		$(e.target.parentNode).append(this.spinner_template);
		e.target.parentNode.parentNode.querySelectorAll('td[typename=TotalDays]')[0].innerHTML = "OK!";
		alert(e.target.parentNode.parentNode.getAttribute("PDataID") + " | " + e.target.getAttribute("typename") + ": " + e.target.value);
		$(e.target.parentNode).find("[typename='loading']")[0].remove();
	},
	ontransfer: function(e) {
		var PDataID = e.target.parentNode.parentNode.getAttribute("PDataID");
		this.transfernow();
	},
	transfernow: function() {
		this.transfer_el.modal("show");
	},
	render: function() {
		var el = this.$el;
		var template = this.template;
		el.html("");
		myEmployees.each(function (e) {
			var i = 0;
			el.append(template({
					"PDataID": e.get("PDataID"),
					"RowNumber": i+1,
					"PWTID": 0,
					"ProjectID": 0,
					"EmployeeID": 0,
					"FullName":e.get("FullName"),
					"Monday": 0,
					"Tuesday": 0,
					"Wednesday": 0,
					"Thursday": 0,
					"Friday": 0,
					"Saturday": 0,
					"Sunday": 0,
					"TotalDays": 0,
					"Additional": 0,
					"WkAmount":1000,
					"Vale":0,
					"AdvanceVale":0,
					"ReceivedAmount":0,
					"Remarks":"N/A",
					"Rate":0,
					"Transfered":false
				}
			));
		});
	}, 
	initialize: function() {
		 this.daterange.on("change", this.daterangechanged);
		 myEmployees.on('change reset add remove', this.render, this);
	}
});
myView = new theView();

