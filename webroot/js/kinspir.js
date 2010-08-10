$(function(){	

	/**** PROLOSERS CODE ****/
	//// AJAX LINK ////
	$('a.ajax').live('click', function(e) {
		$($(this).attr('rel')).load($(this).attr('href'),function(data){
			$(this).removeClass('loading');
		});
		e.preventDefault();
	});
	//// AJAX SUBMIT ////
	//// AJAX AUTOLOADING ///
	/*
	$.get('div.ajax', function(data) {  
		$("#bar").replaceWith(data);  
	});*/
	//// ELEMENT TOGGLE ////
	$('.toggle').live('click', function(e) {
		if($($(this).attr('rel')).is(':visible')){
			$(this).text($(this).attr('title'));
			if ($(this).hasClass('ajax')) {
				$($(this).attr('rel')).slideUp(500, function() {
					$(this).empty();
				});
			} else {
				$($(this).attr('rel')).slideUp(500);
			}
			e.preventDefault();
		} else {
			$(this).attr('title',$(this).text()).text($(this).text()+' [X]').addClass('cancel');
			if ($(this).hasClass('ajax')) {
				$($(this).attr('rel')).addClass('loading').slideDown(500);
			} else {
				$($(this).attr('rel')).slideDown(500);
			}
			e.preventDefault();
		};
	});
	//// MENU ANIMATION ////
	/*
	$('ul#menu li a').hover(function(){
		$(this).animate({backgroundPosition: '(0 -40px)'}, 150);
	},function(){
		$(this).animate({backgroundPosition: '(0 0)'}, 75);
	});
	*/
	//// USER MENU ////
	$('#userSettings').hover(function(){
		$('ul', this).slideDown(200);
	},function(){
		$('ul', this).slideUp(100);
	});
	//// LIGHTBOX ////
	$('.ajax:not([rel])').colorbox({width: '700px'});
	//// DATEPICKER ////
	$('input.datepicker').live('click', function() {
		$(this).datepicker({showOn:'focus'}).focus();
	});
	//// FORM SUBMIT DISABLE ////
	$('form').live('submit', function(){
	    $('input[type=submit]', this).attr('disabled', 'disabled');
	});
	//// AJAX FORM SUBMIT ////
	
	//// BREADCRUMBS QUICK SWAP////
	// @updated added delay - Phil
	var _counter = 0;
	$('#breadcrumbs div').hover(function() {
			$navObj = $('ul', this);
			_counter = setInterval((function() { $navObj.fadeIn(100) }), 250);
	}, function() {
		clearInterval(_counter);
		$navObj.fadeOut(100);
	});
	/**** /PROLOSERS CODE ****/
	
	//$("ul.list-links").accordion();/** side menu accordion - see jquery ui docs for help:  http://jqueryui.com/demos/  **/
	
	// any links that happen in #mid-col.ajax-panel we want them to load inside the div as ajax requests
	// @todo add another selector here so that ajax requests are optional
	$("#mid-col.ajax-panel a").live("click", function (event) {
		/* TRAVIS' CODE 
		$.ajax({complete:function (XMLHttpRequest, textStatus) {afterLoad();},
		dataType:"html",
		success:function (data, textStatus) {$("#mid-col").html(data);}, url:this.href});//*/
		$('#mid-col.ajax-panel').load($(this).attr('href'));
		return false;
	});
	
	// setup autocomplete for messaging
	// @todo find a better way to do this?
	$("#MessageRecipients").fcbkcomplete({
		json_url: basePath + "/connections.json",
		cache: true,
		filter_case: false,
		filter_hide: true,
		firstselected: true,
		filter_selected: true,
		maxitems: 5,
		newel: false,
		complete_text: 'Type the name of a connection',
		focusme: true      
	});
	
	// initialize widget on a container, passing in all the defaults.
	// the defaults will apply to any notification created within this
	// container, but can be overwritten on notification-by-notification
	// basis. (jquery.notify)
	$container = $("#notifications-container").notify();
	
	// perform our afterLoad actions
	afterLoad();
});

// this is our callback that happens when a message is sent successfully using ajax
function messageSent() {
	$.fn.colorbox.close();
	notify(undefined, { title:'Message Sent', text:'Your message has been sent successfully.'},{ expires:true });
};

// perform these actions on every page load, including ajax requests (because DOM might have changed)
function afterLoad(type) {
	// round off the corners on the boxes
//	$('.box, .index, .form, h4 .actions, .view, .related, ul li.order-list, div.form form div.submit input, #full-calendar').corner("5px");
	//$('.box, .index, .form, h4 .actions, .view, .related, ul.order-list li, ul.item-list li, #full-calendar').corner("5px");
	//$('.box h4, .index h4, .form h4, .actions h4, .view h4, .related h4, div.box h4 div.box-actions').corner("5px top");
    
	// setup autocomplete widget for comboboxes
	$.widget("ui.combobox", {
		_create: function() {
			var self = this;
			var select = this.element.hide();
			var input = $("<input>")
				.insertAfter(select)
				.autocomplete({
					source: function(request, response) {
						var matcher = new RegExp(request.term, "i");
						response(select.children("option").map(function() {
							var text = $(this).text();
							if (this.value && (!request.term || matcher.test(text)))
								return {
									id: this.value,
									label: text.replace(new RegExp("(?![^&;]+;)(?!<[^<>]*)(" + $.ui.autocomplete.escapeRegex(request.term) + ")(?![^<>]*>)(?![^&;]+;)", "gi"), "<strong>$1</strong>"),
									value: text
								};
						}));
					},
					delay: 0,
					change: function(event, ui) {
						if (!ui.item) {
							// remove invalid value, as it didn't match anything
							$(this).val("");
							return false;
						}
						select.val(ui.item.id);
						console.log(ui.item.id);
						self._trigger("selected", event, {
							item: select.find("[value='" + ui.item.id + "']")
						});
						
					},
					minLength: 0
				})
				.addClass("ui-widget ui-widget-content ui-corner-all");
		}
	});

	/*$('.view dt, .box h4, .index h4, .form h4, .actions h4, .related h4').addClass("ui-widget ui-widget-header ui-corner-all");
	$('form input, form textarea, form select').addClass("ui-widget ui-widget-content ui-corner-all");
	$('.box, .index, .form, h4 .actions, .related, #full-calendar').addClass("ui-widget ui-corner-all");
	$('.order-list li, ul.order-list li, ul.item-list li, .hidden-link-actions .ui-icon').addClass("ui-state-default ui-corner-all hoverable");
	$('.form-panel').addClass("ui-widget ui-widget-content ui-corner-all");
	//$(".view dl").accordion();*/
	$('.order-list li, .item-list li, .box-actions .ui-icon').addClass("ui-state-default ui-corner-all");
	$('.box h4, .index h4, .form h4, .related h4, #full-calendar, form input, form textarea, form select').addClass("ui-widget ui-corner-all");

	// setup autocomplete on the recipients for messaging
	// currently only used when replying to a message
	// @todo find a better way to do this?
//	if (type == "Messages") {
		$("#Recipients").fcbkcomplete({
			json_url: basePath + "/connections.json",
			cache: true,
			filter_case: false,
			filter_hide: true,
			firstselected: true,
			filter_selected: true,
			maxitems: 5,
			newel: false,
			complete_text: 'Type the name of a connection',
			focusme: true      
		});
//	}
		
	if (type == 'MessageSent') {
		messageSent();
	}
	
	if (type == 'Permissions') {
	}

	// if workspaceId exists
	if (typeof workspaceId != "undefined") {
		$("#task-groups").sortable({
			update: function(){updateGroupOrder();},
			handle: '.movable',
			axis: 'y'
		});
		$('.movable').addClass('draggable');
	}
	
	/*
  // make the cursor over <li> element to be a pointer instead of default
  $('ul.item-list li').css('cursor', 'pointer')
  // iterate through all <li> elements with CSS class = "clickable"
  // and bind onclick event to each of them
  .click(function() {
      // when user clicks this <li> element, redirect it to the page
      // to where the fist child <a> element points
      window.location = $('a', this).attr('href');
  });
  */
	//$(".autocomplete").combobox();
};

function updateOrder(type, id) {
	switch(type) {
		case "tasks":
			var parent = "task-group";
			break;
		default:
			return false;
	}
	$.ajax({
		url: basePath + "/" + type + "/order/" + parent + ":" + id,
		data: $("#" + parent + "-" + type + "-" + id).sortable("serialize"),
		type: "POST"
	});
}

function updateGroupOrder() {
	$.ajax({
		url: basePath + "/task_groups/order/workspace:" + workspaceId,
		data: $("#task-groups").sortable("serialize"),
		type: "POST"
	});
}

// setup convenience method for creating jquery.notify notifications
function notify( template, vars, opts ){
	return $container.notify("create", template, vars, opts);
}