$(document).ready(function () {

	$('#search_text_input').focus(function(){
		if(window.matchMedia( "(min-width:800px)").matches){
			$(this).animate({width: '250px'}, 500);
		}
	});
	$('.button_holder').on('click',function(){
		document.search_form.submit();
	})

	// Button for profile post
	$("#submit_profile_post").click(function (event) {
        event.stopPropagation(); // Oprește propagarea evenimentului
        event.preventDefault(); // Previne comportamentul implicit al evenimentului
    
        $.ajax({
			type: "POST",
			url: "../includes/ajax_submit_profile_post.php",
			data: $("form.space-y-6").serialize(),
			success: function(msg) {
				$("#post_form").addClass("hide"); // Ascunde formularul adăugând clasa "hide"
			},
			error: function () {
				alert("Failure");
			},
		}).done(function() {
			window.location.reload(); // Reîmprospătează pagina
		});
		http_response_code(200);
		
    });
});


  function getUsers(value, user){
	$.post("../includes/ajax_friend_search.php", {query:value, userLoggedIn:user}, function(data) {
		$(".results").html(data);
	});
  }

  function getDropdownData(user, type) {

	if($(".dropdown_data_window").css("height") == "0px") {

		var pageName;

		if(type == 'notification') {
			pageName = "ajax_load_notifications.php";
			$("span").remove("#unread_notification");
		}
		else if (type == 'message') {
			pageName = "ajax_load_messages.php";
			$("span").remove("#unread_message");
		}

		var ajaxreq = $.ajax({
			url: "../includes/" + pageName,
			type: "POST",
			data: "page=1&userLoggedIn=" + user,
			cache: false,

			success: function(response) {
				$(".dropdown_data_window").html(response);
				$(".dropdown_data_window").css({"padding" : "0px", "height": "280px", "border" : "1px solid #DADADA", "top" : "70px"});
				$("#dropdown_data_type").val(type);
			}

		});

	}
	else {
		$(".dropdown_data_window").html("");
		$(".dropdown_data_window").css({"padding" : "0px", "height": "0px", "border" : "none"});
	}

}

function getLiveSearchUsers(value, user) {

	$.post("../includes/ajax_search.php", {query:value, userLoggedIn: user}, function(data) {

		if($(".search_results_footer_empty")[0]) {
			$(".search_results_footer_empty").toggleClass("search_results_footer");
			$(".search_results_footer_empty").toggleClass("search_results_footer_empty");
		}

		$('.search_results').html(data);
		$('.search_results_footer').html("<a href='search.php?q=" + value + "'>See All Results</a>");

		if(data == "") {
			$('.search_results_footer').html("");
			$('.search_results_footer').toggleClass("search_results_footer_empty");
			$('.search_results_footer').toggleClass("search_results_footer");
		}

	});

}


$(document).click(function(e){

	if(e.target.class != "search_results" && e.target.id != "search_text_input") {

		$(".search_results").html("");
		$('.search_results_footer').html("");
		$('.search_results_footer').toggleClass("search_results_footer_empty");
		$('.search_results_footer').toggleClass("search_results_footer");
	}

	if(e.target.className != "dropdown_data_window") {

		$(".dropdown_data_window").html("");
		$(".dropdown_data_window").css({"padding" : "0px", "height" : "0px"});
	}


});