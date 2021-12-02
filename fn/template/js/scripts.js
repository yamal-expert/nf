function getRandomInt(){
	return Math.floor(Math.random() * 99999999);
}

$(document).ready(function(){
	
	$('.nav-link').click(function(event) {
		event.preventDefault();
		var id = $(this).attr("id");


		var URL = "ajax-content-1.php?rndval=" + getRandomInt();
		let Data = new FormData();
		Data.append('id', id);
		
		$.ajax({
			url: URL,
			type: "post",
			cache: false,
			data: Data,
			dataType: "html",
			contentType: false,
			processData: false,
			success: function(data) {
				$(".ajax-content-1").html(data);				
			},
			error: function(jqXHR, textStatus) {
				alert("Ошибка: " + textStatus);
			}
		});

	});	
	
	$('.show-search-panel').click(function(event) {
		event.preventDefault();

		var URL = "ajax-content-3.php?rndval=" + getRandomInt();
		let Data = new FormData();
		
		$.ajax({
			url: URL,
			type: "post",
			cache: false,
			data: Data,
			dataType: "html",
			contentType: false,
			processData: false,
			success: function(data) {
				$(".ajax-content-1").html(data);				
			},
			error: function(jqXHR, textStatus) {
				alert("Ошибка: " + textStatus);
			}
		});

	});	
	
});
