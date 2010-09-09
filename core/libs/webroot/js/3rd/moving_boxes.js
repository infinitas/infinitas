$(function() {
	var totalPanels			= $(".scrollContainer").children().size();

	var regWidth			= $(".panel").css("width");
	var regImgWidth			= $(".panel img").css("width");
	var regTitleSize		= $(".panel h2").css("font-size");
	var regParSize			= $(".panel p").css("font-size");

	var movingDistance	    = $(".scrollContainer div.panel").innerWidth();

	var curWidth			= 350;
	var curImgWidth			= 130;
	var curTitleSize		= "110%";
	var curParSize			= "100%";

	var $panels				= $('#slider .scrollContainer > div');
	if($panels.length == 0) {
		return;
	}
	var $container			= $('#slider .scrollContainer');

	$panels.css({'float' : 'left','position' : 'relative'});

	$("#slider").data("currentlyMoving", false);

	$('.scrollButtons').show();

	$container
		.css('width', ($panels[0].offsetWidth * $panels.length) + 100 )
		.css('left', "-260px");

	var scroll = $('#slider .scroll').css('overflow', 'hidden');

	function returnToNormal(element) {
		$(element)
			.animate({ width: regWidth })
			.find("img")
			.animate({ width: regImgWidth, height: regImgWidth })
		    .end()
			.find("h2")
			.animate({ fontSize: regTitleSize })
			.end()
			.find("p")
			.animate({ fontSize: regParSize });
	};

	function growBigger(element) {
		$(element)
			.animate({ width: curWidth })
			.find("img")
			.animate({ width: curImgWidth, height: curImgWidth })
		    .end()
			.find("h2")
			.animate({ fontSize: curTitleSize })
			.end()
			.find("p")
			.animate({ fontSize: curParSize });
	}

	//direction true = right, false = left
	function change(direction) {
        //if not currently moving
        if (($("#slider").data("currentlyMoving") == false)) {

			$("#slider").data("currentlyMoving", true);

			var next         = direction ? curPanel + 1 : curPanel - 1;
			var __curPanel = next;
			var leftValue    = $(".scrollContainer").css("left");
			var movement	 = direction ? parseFloat(leftValue, 10) - movingDistance : parseFloat(leftValue, 10) + movingDistance;

    		if((direction && !(curPanel < totalPanels))){
    			__curPanel = 1;
    			movement = 280;
    		}
    		else if(!direction && (curPanel <= 1)){
    			__curPanel = totalPanels;
    			movement = (totalPanels-2) * 270 * -1;
    			growBigger("#panel_"+curPanel);
    		}


			$(".scrollContainer")
				.stop()
				.animate({
					"left": movement
				}, function() {
					$("#slider").data("currentlyMoving", false);
				});

			returnToNormal("#panel_"+curPanel);
			growBigger("#panel_"+next);

			curPanel = __curPanel;

			//remove all previous bound functions
			$("#panel_"+(curPanel+1)).unbind();

			//go forward
			$("#panel_"+(curPanel+1)).click(function(){ change(true); });

            //remove all previous bound functions
			$("#panel_"+(curPanel-1)).unbind();

			//go back
			$("#panel_"+(curPanel-1)).click(function(){ change(false); });

			//remove all previous bound functions
			$("#panel_"+curPanel).unbind();
		}
	}

	// Set up "Current" panel and next and prev
	growBigger("#panel_3");
	var curPanel = 3;

	$("#panel_"+(curPanel+1)).click(function(){ change(true); });
	$("#panel_"+(curPanel-1)).click(function(){ change(false); });

	//when the left/right arrows are clicked
	$(".right").click(function(){ change(true); });
	$(".left").click(function(){ change(false); });

	$(window).keydown(function(event){
	  switch (event.keyCode) {
			case 13: //enter
			case 32: //space
				$(".right").click();
				break;

			case 37: //left arrow
			case 39: //right arrow
				$(".right").click();
				break;
	  }
	});
});