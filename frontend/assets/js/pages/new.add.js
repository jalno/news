var TicketAdd = function () {
	var form = $('.create_form');
	var runUserListener = function(){
		$("input[name=author_name]", form).autocomplete({
			source: function( request, response ) {
				$.ajax({
					url: "/fa/userpanel/users",
					dataType: "json",
					data: {
						ajax:1,
						word: request.term
					},
					success: function( data ) {
						if(data.hasOwnProperty('status')){
							if(data.status){
								if(data.hasOwnProperty('items')){
									response( data.items );
								}
							}
						}

					}
				});
			},
			select: function( event, ui ) {
				$(this).val(ui.item.name+(ui.item.lastname ? ' '+ui.item.lastname : ''));
				$('input[name=author]', form).val(ui.item.id);
				return false;
			},
			focus: function( event, ui ) {
				$(this).val(ui.item.name+(ui.item.lastname ? ' '+ui.item.lastname : ''));
				$('input[name=author]', form).val(ui.item.id);
				return false;
			}
		}).autocomplete( "instance" )._renderItem = function( ul, item ) {
			return $( "<li>" )
				.append( "<strong>" + item.name+(item.lastname ? ' '+item.lastname : '')+ "</strong><small class=\"ltr\">"+item.email+"</small><small class=\"ltr\">"+item.cellphone+"</small>" )
				.appendTo( ul );
		};
	};
	var mouseHover = function (){
		$(window).ready(function(){
			$(".news-image").mouseover(function(){
		        $(".news-image-buttons").css("display", "block");
		    });
		    $(".news-image").mouseout(function(){
		        $(".news-image-buttons").css("display", "none");
		    });
		});
	};
	return {
		init: function() {
			runUserListener();
			mouseHover();
		}
	}
}();
$(function(){
	TicketAdd.init();
});
