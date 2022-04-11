current_page = 1;
jQuery
    (
        function($)
		{
            $(".pagin").click
			(
				function ()
				{
					console.log (" a1s: " + $("#apartaments1").serialize ());
					console.log ("lll");
					var current_page = $("#apartaments1");
					var data =
					{
						"action": "loadmore",
						"page" : current_page
					};
					console.log (" cphtml: " + current_page.html ());
					console.log (" cps: " + current_page.serialize ());
					console.log (" cpaa: " + current_page.attr ("action"));
					console.log (" cpam: " + current_page.attr ("method"));
					console.log (" da1: " + data ["action"]);
					console.log (" dp: " + data ["page"].html ());
					$.ajax
					(
						{
							url: window.location.href,
							//data: data, // form data
							type: "post", // POST
							success: function (data)
							{
								console.log (" da2: " + data ["action"]);
								$("#apartments").html (data ["page"].html()); // insert data
							}
						}
					);
				}
			);
        }
    );