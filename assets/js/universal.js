// universal functions;;

$(document).ready(function() {
   $("#loading").hide();
   $('#wrapper').show();
});

function toHumanDate(dateStr) {
  var monthNames = [ "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December" ];
  var theDate = dateStr.split("-");
  var year = theDate[0];
  var month = theDate[1]
  var day = theDate[2];
  return monthNames[month - 1] + " " + day + ", " + year;
}

function quoteattr(s, preserveCR) {
    preserveCR = preserveCR ? '&#13;' : '\n';
    return ('' + s) /* Forces the conversion to string. */
        .replace(/&/g, '&amp;') /* This MUST be the 1st replacement. */
        .replace(/'/g, '&apos;') /* The 4 other predefined entities, required. */
        .replace(/"/g, '&quot;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        /*
        You may add other replacements here for HTML only 
        (but it's not necessary).
        Or for XML, only if the named entities are defined in its DTD.
        */ 
        .replace(/\r\n/g, preserveCR) /* Must be before the next replacement. */
        .replace(/[\r\n]/g, preserveCR);
        ;
}

function isNumber(val) {
    var floatRegex = /^-?\d+(?:[.,]\d*?)?$/;
    if (!floatRegex.test(val))
        return false;

    val = parseFloat(val);
    if (isNaN(val))
        return false;
    return true;
}

function getOffset( el ) {
    var _x = 0;
    var _y = 0;
    while( el && !isNaN( el.offsetLeft ) && !isNaN( el.offsetTop ) ) {
        _x += el.offsetLeft - el.scrollLeft;
        _y += el.offsetTop - el.scrollTop;
        el = el.offsetParent;
    }
    return { top: _y, left: _x };
}

function comma(Num) { //function to add commas to textboxes
      Num += '';
      Num = Num.replace(',', ''); Num = Num.replace(',', ''); Num = Num.replace(',', '');
      Num = Num.replace(',', ''); Num = Num.replace(',', ''); Num = Num.replace(',', '');
      x = Num.split('.');
      x1 = x[0];
      x2 = x.length > 1 ? '.' + x[1] : '';
      var rgx = /(\d+)(\d{3})/;
      while (rgx.test(x1))
          x1 = x1.replace(rgx, '$1' + ',' + '$2');
      return x1 + x2;
  }

function escapeHtml(unsafe) {
    return unsafe
         .replace(/&/g, "&amp;")
         .replace(/</g, "&lt;")
         .replace(/>/g, "&gt;")
         .replace(/"/g, "&quot;")
         .replace(/'/g, "&#039;");
 }
 
function plusday(date, days) {
  var date2 = new Date(date);
  date2.setDate(date2.getDate() + days);
  var month = (date2.getMonth()+1);
  var day = date2.getDate();
  var month = (parseFloat(month) < 9) ? "0" + month : month;
  var day = (parseFloat(day) < 9) ? "0" + day : day;
  return date2.getFullYear() + "-" + month + "-" + day;
}

function genid() {
  return '_' + Math.random().toString(36).substr(2, 9);
}

function random(min, max) {
  return Math.ceil(min + Math.random() * (max - min));
}

var confirmModal = Backbone.View.extend({
    tagName:"div",
    hide:function() {
      $("#confirmModal").modal("hide");
    },
    progressMode:function(onprogress) {
      if (onprogress) {
        this.$el.data("noClosing", true);
        $("#confirmbtn").prop("disabled", true).find(".spinner-border").css("display", "inline-block");
      }
      else{
        this.$el.data("noClosing", false);
        $("#confirmbtn").prop("disabled", false).find(".spinner-border").css("display", "none");
      }
    },
    confirm_func:null,
    initialize:function() {
       var template = '<div class="modal fade" id="confirmModal" tabindex="2" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">' +
          '<div class="modal-dialog" role="document">' +
            '<div class="modal-content">' +
              '<div id="color" style="background-color:#55AA55;color:white;" class="modal-header">' +
                '<h5 class="modal-title" id="exampleModalLabel"><span class="glyphicons"></span> <span id="title">Modal title</span></h5>' +
                '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
                 ' <span aria-hidden="true">&times;</span>'+
                '</button>' +
              '</div>' +
              '<div class="modal-body">' +
                '<p id="text"></p>'+
              '</div>'+
              '<div class="modal-footer">'+
               '<button type="button" class="btn btn-warning" data-dismiss="modal">Cancel</button>'+
                '<button id="confirmbtn" type="button" class="btn btn-danger" data-dismiss="modal"><span class="spinner-border spinner-primary" style="display:none;height:20px;width:20px;vertical-align:middle;"></span> Confirm</button>'+
              '</div>'+
           ' </div>'+
          '</div>'+
        '</div>';
        this.$el = $(template);
        $("body").append(this.$el);
        $("#confirmModal #confirmbtn").on("click", function(e) {
            this.confirm_func();
        }.bind(this));
        $("#confirmModal").on("hide.bs.modal", function(e) {
           if ($(e.currentTarget).data("noClosing")) {
            e.preventDefault();
            e.stopPropagation();
            return false;
          }
        });
    },
    setconfirm(jcaller) {
      this.confirm_func = jcaller;
    },
    show:function(title, type, message) {
      $("#confirmModal").data("noClosing", false);
      if (type == "error")
        this.$el.find("#color").css("background-color", "#D46A6A");
      else
        this.$el.find("#color").css("background-color", "#55AA55");

      this.$el.find("#title").html(title);
      this.$el.find("#text").html(message);
      this.$el.modal("show");
    }
});

var errorModal = Backbone.View.extend({
    tagName:"div",
    prev_modal:null,
    onhide_function:null,
    onhide:function() {
      this.onhide_function();
    },
    initialize:function() {
      var template = '<div class="modal fade" id="errorModal" tabindex="2" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">' +
				  '<div class="modal-dialog" role="document">' +
				    '<div class="modal-content">' +
				      '<div id="color" style="background-color:#55AA55;color:white;" class="modal-header">' +
				        '<h5 id="title" class="modal-title" id="exampleModalLabel">Modal title</h5>' +
				        '<button type="button" class="close" data-dismiss="modal" aria-label="Close">' +
				         ' <span aria-hidden="true">&times;</span>'+
				        '</button>' +
				      '</div>' +
				      '<div class="modal-body">' +
				        '<p id="text"></p>'+
				      '</div>'+
				      '<div class="modal-footer">'+
				        '<button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>'+
				      '</div>'+
				   ' </div>'+
				  '</div>'+
				'</div>';
		$("body").append(template);
    $("#errorModal").on("keyup", function(e) {
        if (e.keyCode == 13) {
            $("#errorModal").modal("hide");
        }
    });
    this.$el = $("#errorModal");
    },
    show:function(title, type, message) {

      if (this.onhide_function == null && this.prev_modal == null) {
         this.$el.off("hidden.bs.modal");
      }

     if (this.onhide_function !== null) {
         this.$el.on("hidden.bs.modal", this.onhide.bind(this));
      }

      if (this.prev_modal !== null) {
        $(this.prev_modal).modal("hide");
        this.$el.on("hidden.bs.modal", this.onhidden.bind(this));
      }
      

      if (type == "error")
        this.$el.find("#color").css("background-color", "#D46A6A");
      else
        this.$el.find("#color").css("background-color", "#55AA55");

      this.$el.find("#title").html(title);
      this.$el.find("#text").html(message);
      this.$el.modal({focus: true});
    },
    onhidden:function() {
      $(this.prev_modal).modal("show");
    },
    setprevmodal:function(jcaller) {
      this.prev_modal = jcaller;
    },
    setprevfunction:function(jcaller) {
      this.onhide_function = jcaller;
    }
});

function isValidDate(dateString) {
  var regEx = /^\d{4}-\d{2}-\d{2}$/;
  if(!dateString.match(regEx)) return false;  // Invalid format
  var d = new Date(dateString);
  var dNum = d.getTime();
  if(!dNum && dNum !== 0) return false; // NaN value, Invalid date
  return d.toISOString().slice(0,10) === dateString;
}

  