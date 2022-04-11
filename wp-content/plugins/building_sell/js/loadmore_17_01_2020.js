current_page = 1;
jQuery
(
	function ($)
	{
		
		function lmtag(elem, tag){
			$(elem).text("Загружаю..."); // изменяем текст кнопки, вы также можете добавить прелоадер
					var data =
					{
						"action": "loadmore",
						"query" : true_posts,
						"page" : current_page,
						"max-pages" : max_pages
					};
					if (location.href.indexOf ("/novosti/") + 1)
						current_page++;
					$.ajax
					(
						{
							url: ajaxurl, // обработчик
							data: data, // данные
							type: "POST", // тип запроса
							success: function(data)
							{
								var d = $(tag).html () + data;
								$(tag).html (d); // вставляем новые посты
								if (current_page == max_pages)
									$("#true_loadmore").css ("display", "none");
							}
						}
					);
					$(elem).text ("Показать больше"); // изменяем текст кнопки, вы также можете добавить прелоадер
		}
		
		
		
	
		$('#true_loadmore').click
		(
			function ()
			{						
				
				if (location.href.indexOf ("/novosti/") + 1)
					lmtag(this, "#blog_container");
				else				
					lmtag(this, "#lcpc");					
			}
		);
		$(".count-box").each
		(
			function ()
			{
				if ($(this).html () == "0")
					$(this).html ("");
			}
		);
		$(".heart-svg").click
		(
			function ()
			{
				if (($(this).parent ("label").children (".count-box").html () == "1") && ($(this).parent ("label").parent ("div.wp_ulike_general_class").hasClass ("wp_ulike_is_liked")))
					hiddenZero ($(this).parent ("label").children ("span.count-box"));
				if ($(this).parent ("label").children (".count-box").length == 0)
					$(this).parent ("label").append ("<span class='count-box'>1</span>");
				if ($(this).parent ("label").children (".count-box").html () == "")
					$(this).parent ("label").children ("span.count-box").html ("1");
			}
		);
		$(".count-box").click
		(
			function ()
			{
				if (($(this).html () == "1") && ($(this).parent ("label").children ("svg").html ().indexOf ("AAB8C2")))
					hiddenZero ($(this));
			}
		);
		
		function hiddenZero (el)
		{
			setTimeout
			(
				function ()
				{
					$(".count-box").each
					(
						function ()
						{
							if ($(this).html () == "0")
								$(this).html ("");
						}
					);
					el.html ("");
				},
				3000
			);			
		}
	}
);