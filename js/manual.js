// Global variables declaration

	var globallink = 'http://localhost/work/funchat-final-github/';
	var socket;
	var talkingto = '';
	var currentpage = '';
	var previouspage = '';
	var opts = {
	  lines: 13, // The number of lines to draw
	  length: 22, // The length of each line
	  width: 10, // The line thickness
	  radius: 30, // The radius of the inner circle
	  corners: 1, // Corner roundness (0..1)
	  rotate: 0, // The rotation offset
	  direction: 1, // 1: clockwise, -1: counterclockwise
	  color: '#000', // #rgb or #rrggbb or array of colors
	  speed: 1, // Rounds per second
	  trail: 60, // Afterglow percentage
	  shadow: false, // Whether to render a shadow
	  hwaccel: false, // Whether to use hardware acceleration
	  className: 'spinner', // The CSS class to assign to the spinner
	  zIndex: 2e9, // The z-index (defaults to 2000000000)
	  top: '50%', // Top position relative to parent
	  left: '50%' // Left position relative to parent
	};
//Global variables declaration end

$(document).ready(function() {

	$('body').flowtype( { minFont: 16 });
	attachHandler();

	if($('.for_special_thing').html() === "yes") {

		currentpage = 'home_loggedin';
		setInterval(ajax_reloader, 3000);
	}
});

function jumpEMO(link) {

	var spinner = new Spinner(opts).spin();
	$("body").append(spinner.el);

	var datac = "uid=" + $('.userdetailsforjson').attr('uid') + "&accesstoken=" + $('.userdetailsforjson').attr('accesstoken');

	$.ajax({
		url: "emoticons.php?page=" + link,
		type: "POST",
		data: datac
	}).done(function(rdata) {
		if(rdata.length > 0) {
			
			$('.emoticons_container').empty();
			$('.emoticons_container').append("<table class='emo'>" + rdata + "</table>");
		}
		viewChanger('emoticons_page',currentpage);

		// ATTACH JUMP 

		$('.jump_page_no').change(function() {

			var toLink = $(".jump_page_no option:selected").text();
			jumpEMO(toLink);
		});
		// END

		$(spinner.el).remove();
	});
}

function viewChanger(link,from) {

	if(link === "notfixed") {
		link = previouspage;
	}

	$('.' + from ).css('display','none');
	$('.' + from ).css('visibility','none');

	$('.' + link ).css('display','block');
	$('.' + link ).css('visibility','visible');

	currentpage = link;
	previouspage = from;
}

function attachHandler() {

	//Handling attachments

		$(document).on('click','.fc-n-noti', function(e) {
			var thisvala = $(this);
			$.ajax({
				url: globallink + "ajax.php?uid=" + $('.userdetailsforjson').attr('uid') + "&accesstoken=" + $('.userdetailsforjson').attr('accesstoken') + "&nup=" + $(this).attr('fc-n-noti-'),
				type: "GET"
			}).done(function(rdata) {
				thisvala.remove();
			});
		});

		// Handle any normal click
		$(document).on('click', 'a', function(e) {

			if(this.href.indexOf("logout.php") != -1) {
				var spinner = new Spinner(opts).spin();
				$("body").append(spinner.el);

				var datac = "uid=" + $('.userdetailsforjson').attr('uid') + "&accesstoken=" + $('.userdetailsforjson').attr('accesstoken');

				$.ajax({
					url: globallink + "logout.php",
					data: datac,
					type: "POST"
				}).done(function(rdata) {

					var jr = JSON.parse(rdata);
					alert(jr.message);

					if(jr.result === "success") {
						window.location.assign(globallink);
					}
					$(spinner.el).remove();
				});
			}
			else if(this.href.indexOf("profile") != -1) {

				var spinner = new Spinner(opts).spin();
				$("body").append(spinner.el);

				$.ajax({
					url: this.href + "&uid=" + $('.userdetailsforjson').attr('uid') + "&accesstoken=" + $('.userdetailsforjson').attr('accesstoken'),
					type: "GET"
				}).done(function(rdata) {

					var jr = JSON.parse(rdata);

					if(jr.result === "error") {
						alert(jr.message);
					}
					else {
						$('.profile_page_username_h1').html(jr.username);
						$('.profile_page_pimg').html('<img src="' + jr.profimage + '" />');

						$('.profilepage_wrapper.notice').remove();
						if($('.show_if_not_forme') < 1) { $('.rrrr-t-f').append('<div class="new show_if_not_forme"><a href="" class="pp_bl">Add as buddy</a></div>'); }

						if(jr.friend_or_me === "true") {
							$('.addfriendsuccess').remove();
						}
						if(jr.in_req === "true") {
							$('.pp_bl').html('Accept Request');
							$('.pp_bl').attr('href','addfriend.php?accept=' + jr.in_req_id);
						}
						else {
							$('.pp_bl').attr('href','addfriend.php?id=' + jr.uid);
						}

						viewChanger('profilepage',currentpage);
					}

					$(spinner.el).remove();
				});
			}
			else if(this.href.indexOf("addfriend.php?id") != -1) {

				var spinner = new Spinner(opts).spin();
				$("body").append(spinner.el);

				$.ajax({
					url: this.href + "&uid=" + $('.userdetailsforjson').attr('uid') + "&accesstoken=" + $('.userdetailsforjson').attr('accesstoken'),
					type: "GET"
				}).done(function(rdata) {

					var jr = JSON.parse(rdata);

					if(jr.result === "error") {
						$('.profilepage_wrapper').append('<div class="notice error">'+jr.message+'</div>');
					}
					else {
						$('.show_if_not_forme').remove();
						$('.profilepage_wrapper').append('<div class="notice success addfriendsuccess">'+jr.message+'</div>');

						// Send the server the instant friend request notification
						//socket.emit('new friend request', { fromUid: $('.userdetailsforjson').attr('uid'), accesstoken: $('.userdetailsforjson').attr('accesstoken'), toUid: jr.uid });
						//$(spinner.el).remove();
					}
					$(spinner.el).remove();
				});
			}
			else if(this.href.indexOf("addfriend.php?check") != -1) {

				var spinner = new Spinner(opts).spin();
				$("body").append(spinner.el);

				$.ajax({
					url: this.href + "&uid=" + $('.userdetailsforjson').attr('uid') + "&accesstoken=" + $('.userdetailsforjson').attr('accesstoken'),
					type: "GET"
				}).done(function(rdata) {

					var jr = JSON.parse(rdata);

					if(jr.result === "error") {
						alert(jr.message);
						$(spinner.el).remove();
					}
					else {
						var pData = '<div class="new">';
						
						$.each(jr.sresults, function(key) {
							pData += '<a href="profile.php?id=' + jr.sresults[key].sid + '"><div class="pimg_wrapper"><img src="'+jr.sresults[key].spp+'"></div><div class="pname_div">'+jr.sresults[key].sfn+'</div></a>';
						});

						//console.log(pData);
						
						viewChanger('buddyrequestpage','home_loggedin');
						$('.breqres').html('');
						$('.breqres').prepend(pData + "</div>");
						$(spinner.el).remove();
					}
				});
			}
			else if(this.href.indexOf("addfriend.php?accept") != -1) {

				var spinner = new Spinner(opts).spin();
				$("body").append(spinner.el);

				$.ajax({
					url: this.href + "&uid=" + $('.userdetailsforjson').attr('uid') + "&accesstoken=" + $('.userdetailsforjson').attr('accesstoken'),
					type: "GET"
				}).done(function(rdata) {

					var jr = JSON.parse(rdata);

					if(jr.result === "success") {
						$('.pp_bl').html('');
					}
					
					alert(jr.message);
					$(spinner.el).remove();
				});
			}
			else if(this.href.indexOf("pm.php?read") != -1) {

				var spinner = new Spinner(opts).spin();
				var ac_link = (this.href).replace(globallink,'');
				$("body").append(spinner.el);

				$.ajax({
					url: this.href + "&onlymsg&uid=" + $('.userdetailsforjson').attr('uid') + "&accesstoken=" + $('.userdetailsforjson').attr('accesstoken'),
					type: "GET"
				}).done(function(rdata) {

					//console.log(ac_link);
					$('.sendmsg_form').attr('action', ac_link);

					$('.pmpage_list_wrapper').html('');
					$('.pmpage_list_wrapper').prepend(rdata);
					viewChanger('pmpage',currentpage);
					$(spinner.el).remove();

					var exx = ac_link.split("=");
					talkingto = exx[(exx.length)-1];
				});
			}
			else if($(this).attr('fc-goto') === "message_threads") {

				var spinner = new Spinner(opts).spin();
				$("body").append(spinner.el);

				$.ajax({
					url: globallink + "ajax.php?threads&uid=" + $('.userdetailsforjson').attr('uid') + "&accesstoken=" + $('.userdetailsforjson').attr('accesstoken'),
					type: "GET"
				}).done(function(rdata) {
					if(rdata.length > 0) {
						var jobj = JSON.parse(rdata);

						$('.message_threads_container').html('');

						$.each(jobj.pm, function(key) {
							var thisPM = jobj.pm[key];

							var pData = '<li class="small_msg_box_no_pad thread-block-'+thisPM.tid+'"><div id="shouter"><a href="pm.php?read='+thisPM.pmid+'">'+thisPM.username+'</a><span>'+thisPM.time+'</span></div><div class="message unread_om">'+thisPM.message+'</div></li>';
							$('.message_threads_container').prepend(pData);
						});
					}
					viewChanger('message_threads',currentpage);
					$(spinner.el).remove();
				});
			}
			else if($(this).attr('fc-goto') === "online_buddies") {
				var spinner = new Spinner(opts).spin();
				$("body").append(spinner.el);

				$.ajax({
					url: globallink + "ajax.php?online&uid=" + $('.userdetailsforjson').attr('uid') + "&accesstoken=" + $('.userdetailsforjson').attr('accesstoken'),
					type: "GET"
				}).done(function(rdata) {
					if(rdata.length > 0) {
						var jobj = JSON.parse(rdata);

						var obData = '';

						$.each(jobj.online_now, function(key) {

							var theUser = jobj.online_now[key];
							//console.log(theUser);
							obData += '<div class="new"><a href="pm.php?read=' + theUser.uid + '"><div class="pimg_wrapper"><img src="'+theUser.picture+'"></div><div class="pname_div">'+theUser.username+'</div></a></div>';
						});

						$('.online_buddy_ul').html('');
						$('.online_buddy_ul').prepend(obData);
					}
					viewChanger('online_buddies',currentpage);
					$(spinner.el).remove();
				});
			}
			else if(this.href.indexOf("emoticons.php") != -1) {

				var spinner = new Spinner(opts).spin();
				$("body").append(spinner.el);

				var datac = "uid=" + $('.userdetailsforjson').attr('uid') + "&accesstoken=" + $('.userdetailsforjson').attr('accesstoken');

				$.ajax({
					url: this.href,
					type: "POST",
					data: datac
				}).done(function(rdata) {
					if(rdata.length > 0) {
						
						$('.emoticons_container').empty();
						$('.emoticons_container').append("<table class='emo'>" + rdata + "</table>");
					}
					viewChanger('emoticons_page',currentpage);

					// ATTACH JUMP 

					$('.jump_page_no').change(function() {
						var toLink = $(".jump_page_no option:selected").text();
						//console.log(toLink);
						jumpEMO(toLink);
					});
					// END

					$(spinner.el).remove();
				});
			}
			else if(this.href.indexOf("shout.php") != -1) {

				var spinner = new Spinner(opts).spin();
				$("body").append(spinner.el);

				var datac = "uid=" + $('.userdetailsforjson').attr('uid') + "&accesstoken=" + $('.userdetailsforjson').attr('accesstoken');

				$.ajax({
					url: this.href,
					type: "POST",
					data: datac
				}).done(function(rdata) {
					if(rdata.length > 0) {
						
						$('.shout_list_wrapper').empty();
						$('.shout_list_wrapper').append( rdata );
					}
					viewChanger('shoutbox_page',currentpage);

					$(spinner.el).remove();
				});
			}
			else {

				var link = $(this).attr('fc-goto');
				var from = $(this).attr('fc-gofrom');

				viewChanger(link,from);
			}
			e.preventDefault();
		});	
		
		// Handle login form
		$('.login').unbind().bind('submit',function(e) {
			var urla = $('.login').attr('action');
			var data = $('.login').serialize();
			var datac = data + '&submit=Submit';

			var spinner = new Spinner(opts).spin();
			$("body").append(spinner.el);
			$.ajax({
				url: globallink + urla,
				data: datac,
				type: "POST"
			}).done(function(rdata) {

				var jr = JSON.parse(rdata);

				if(jr.result === 'error') {
					alert(jr.message);
					$(spinner.el).remove();
				}
				else if(jr.result === 'success') {

					$('body').css('background','#fff');
					
					$('.fc-welcome-msg').html("Welcome " + jr.userfirstname);

					$('.userdetailsforjson').attr('uid', jr.uid);
					$('.userdetailsforjson').attr('accesstoken', jr.accesstoken);

					$('.fc-buddy-requests').html("You have " + jr.friendrequests + " buddy requests." + '<a href="addfriend.php?check">check</a>');
					if(jr.friendrequests > 0) {
						$('.fc-buddy-requests').css('display','block');
						$('.fc-buddy-requests').css('visibility','visible');
					}
					else {
						$('.fc-buddy-requests').css('display','none');
						$('.fc-buddy-requests').css('visibility','hidden');
					}
					$('.unread_msg_class').attr('code', jr.unreadmsg);
					$('.unread_msg_class').html(jr.unreadmsg);

					$('.online_buddies_class').attr('code', jr.onlinebuddy);
					$('.online_buddies_class').html(jr.onlinebuddy);

					//connectSocket();
					//loadFirstTimeData();
					
					setInterval(ajax_reloader, 3000);

					viewChanger('home_loggedin','home_no_login');
					$(spinner.el).remove();
				}
			});
			e.preventDefault();
		});
		
		// Handle registration form
		$('.register').unbind().bind('submit',function(e) {
			var urla = $('.register').attr('action');
			var data = $('.register').serialize();
			var datac = data + '&submit=submit';
			
			var spinner = new Spinner(opts).spin();
			$("body").append(spinner.el);
			$.ajax({
				url: globallink + urla,
				data: datac,
				type: "POST"
			}).done(function(rdata) {

				var jr = JSON.parse(rdata);
				if(jr.result === 'success') {
					alert(jr.message);
					viewChanger('home_no_login','register_page');
				}
				else {
					alert(jr.message);
				}
				$(spinner.el).remove();
			});
			return false;
		});

		// Handle sendmsg form
		$('.sendmsg_form').unbind().bind('submit',function(e) {
			var urla = $('.sendmsg_form').attr('action') + "&onlymsg";
			var data = $('.sendmsg_form').serialize();
			var datac = data + '&send&';
			datac += "uid=" + $('.userdetailsforjson').attr('uid') + "&accesstoken=" + $('.userdetailsforjson').attr('accesstoken');

			var spinner = new Spinner(opts).spin();
			$("body").append(spinner.el);
			$.ajax({
				url: globallink + urla,
				data: datac,
				type: "GET"
			}).done(function(rdata) {
				
				$('.pmpage_list_wrapper').html('');
				$('.pmpage_list_wrapper').prepend(rdata);
				
				$(spinner.el).remove();
			});
			$('.msg_input').val('');
			return false;
		});	

		// Handle shoutbox form
		$('.shout_form').unbind().bind('submit',function(e) {
			var urla = $('.shout_form').attr('action');
			var data = $('.shout_form').serialize();
			var datac = data + '&send&';
			datac += "uid=" + $('.userdetailsforjson').attr('uid') + "&accesstoken=" + $('.userdetailsforjson').attr('accesstoken');

			var spinner = new Spinner(opts).spin();
			$("body").append(spinner.el);
			$.ajax({
				url: globallink + urla,
				data: datac,
				type: "GET"
			}).done(function(rdata) {
				
				$('.shout_list_wrapper').empty();
				$('.shout_list_wrapper').prepend(rdata);
				
				$(spinner.el).remove();
			});
			$('.smsg_input').val('');
			return false;
		});		
		
		// Handle search form
		$('.search_form').unbind().bind('submit',function(e) {

			$('.sresults').empty();

			var urla = $('.search_form').attr('action');
			var data = $('.search_form').serialize();
			var datac = data + '&submit=Submit';

			var spinner = new Spinner(opts).spin();
			$("body").append(spinner.el);
			$.ajax({
				url: globallink + urla + "?uid=" + $('.userdetailsforjson').attr('uid') + "&accesstoken=" + $('.userdetailsforjson').attr('accesstoken'),
				data: datac,
				type: "POST"
			}).done(function(rdata) {

				var pData = '';
				
				var jr = JSON.parse(rdata);
				if(jr.result === "error") {
					alert(jr.message);
				}
				else {
					$.each(jr.sresults, function(key) {
						pData += '<div class="new"><a href="profile.php?id=' + jr.sresults[key].uid + '"><div class="pimg_wrapper"><img src="'+jr.sresults[key].profimage+'"></div><div class="pname_div">'+jr.sresults[key].username+'</div></a></div>';
					});

					$('.sresults').html('').prepend(pData);
				}

				$(spinner.el).remove();
			});
			e.preventDefault();
		});
	//Handler attachments end
}

function ajax_reloader() {

	// Set data to be sent
	var data = new Object();

	var uid = $('.userdetailsforjson').attr('uid');
	var accesstoken = $('.userdetailsforjson').attr('accesstoken');

	var postdata = 'uid='+uid+'&accesstoken='+accesstoken;

	if(currentpage === "pmpage") {
		postdata += "&pm_lmi=" + $('.pmpage .small_msg_box').attr('li-msg-id');
	}
	else if(currentpage === "shoutbox_page") {
		postdata += "&s_lmi=" + $('.shoutbox_page .small_msg_box').attr('li-smsg-id');
	}

	// Send data
	$.ajax({
		url: globallink + 'ajax.php',
		data: postdata,
		type: "POST"
	}).done(function(rdata) {

		// Data is sent, some data is received process it.

		if(rdata.length > 0) {
			var jobj = JSON.parse(rdata);
			//console.log(jobj);

			// UPDATE HOMEPAGE NUMBERS
			$('.chatno_li').html('Chat <div class="float_right online_buddies_class" code="'+jobj.mem_on+'">'+jobj.mem_on+'</div></li>');
			$('.msgno_li').html('Messages <div class="float_right unread_msg_class" code="'+jobj.unread_msg+'">'+jobj.unread_msg+'</div>');
			// END

			// UPDATE BUDDY REQUEST NOTIFICATION
			if(jobj.friend_requests > 0) {

				$('.fc-buddy-requests').html("You have " + jobj.friend_requests + " buddy requests. " + '<a href="addfriend.php?check">check</a>');
				$('.fc-buddy-requests').css('display','block');
				$('.fc-buddy-requests').css('visibility','visible');
			}
			else {
				$('.fc-buddy-requests').css('display','none');
				$('.fc-buddy-requests').css('visibility','hidden');
			}
			// END

			// UPDATE NORMAL NOTIFICATION

			$.each(jobj.notifications, function(key) {

				var theNoti = jobj.notifications[key];
				//console.log(theUser);
				var nData = '<p class="notice avg_margin success fc-n-noti fc-n-noti-'+theNoti.nid+'" fc-n-noti-="'+theNoti.nid+'">'+theNoti.message+'</p>';

				$('.fc-n-noti-'+theNoti.nid).remove();
				$('.menu_page').prepend(nData);
			});

			if(jobj.has_new_msg) {
				$.each(jobj.messages, function(key) {

					var thePM = jobj.messages[key];

					var nData = '<li class="small_msg_box" li-msg-id="' + thePM.pmid + '"><div id="shouter"><a href="profile.php?id='+thePM.senderid+'">'+thePM.username+'</a><span>'+thePM.time+'</span></div><div class="message">'+thePM.message+'</div></li>';
					
					$('.pmpage_list_wrapper').prepend(nData);
				});
			}

			if(jobj.has_new_smsg) {
				$.each(jobj.smessages, function(key) {

					var thePM = jobj.smessages[key];

					var nData = '<li class="small_msg_box" li-smsg-id="' + thePM.sid + '"><div id="shouter"><a href="profile.php?id='+thePM.senderid+'">'+thePM.sendername+'</a></div><div class="message">'+thePM.message+'</div></li>';
					
					$('.shout_list_wrapper').prepend(nData);
				});
			}
		}
	});
}