(function($) {
  "use strict";

  /*----------------------------
      hcOffcanvasNav Active
    ------------------------------*/
  $('#main-nav').hcOffcanvasNav({
    disableAt: 1024,
    customToggle: $('.toggle'),
    navTitle: 'All Categories',
    levelTitles: true,
    levelTitleAsBack: true
  });

  $('#lang').on('change',function(){
    var value = $('#lang').val();
    var url = `lang/set`;
    $.ajax({
			type: 'GET',
			url: url,
			data: {lang: value},
			dataType: 'json',
			success: function(response){ 
        if(response == 'success')
        {
          location.reload();
        }
			},
			error: function(xhr, status, error) 
			{
				$.each(xhr.responseJSON.errors, function (key, item) 
				{
					Sweet('error',item)
					$("#errors").html("<li class='text-danger'>"+item+"</li>")
				});
				errosresponse(xhr, status, error);
			}
		})
  });

})(jQuery);