function sinImagen (event, sinimagen) {

  	if (! event) event = window.event;
  	var img = event.srcElement ? event.srcElement : event.currentTarget;

  	img.onerror 	= null;
  	img.src 		= sinimagen;
}

function precarga (imagenes) {

  	for (var i = 0; i < imagenes.length; i++) $('<img />').attr('src', imagenes[i]);
}

//carousel-indicators (generador de indicators dinamico, para ahorrarse un bucle de php)
/*function carouselIndicators (carousel) {

	var items 		= $('.item', carousel);
	var indicators 	= $('<ol />').addClass ('carousel-indicators');

	items.each (function (index) {

		var li = $('<li />').attr ({
			'data-target': '#' + carousel.attr ('id'),
			'data-slide-to': index
		}).css ({
			marginRight: 3,
			marginLeft: 3
		});

		if (! index) li.addClass('active');
		indicators.append (li);
	});

	carousel.prepend (indicators);
}*/