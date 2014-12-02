if (typeof jQuery === "undefined") { throw new Error("script.js requires jQuery"); }

( function( $ ) {

	/* ----- MOBILE MENU TOGGLE ----- */
	$(document).ready(function() {
		$('#mobile-menu').click(function() {
			$('#links nav').stop().slideToggle(500);
		});
	});

	/* ----- LIFE-CYCLE GRAPHIC FUNCTIONS ----- */
	$(document).ready(function() {
		
		$('#lc-loan').hover(function() {
			$('.lc-container').addClass('loan');
		}, function() {
			$('.lc-container').removeClass('loan');
		});
		$('#lc-surveillance').hover(function() {
			$('.lc-container').addClass('surveillance');
		}, function() {
			$('.lc-container').removeClass('surveillance');
		});
		$('#lc-watchlist').hover(function() {
			$('.lc-container').addClass('watchlist');
		}, function() {
			$('.lc-container').removeClass('watchlist');
		});
		$('#lc-transfer').hover(function() {
			$('.lc-container').addClass('transfer');
		}, function() {
			$('.lc-container').removeClass('transfer');
		});
		$('#lc-liquidation').hover(function() {
			$('.lc-container').addClass('liquidation');
		}, function() {
			$('.lc-container').removeClass('liquidation');
		});
		$('#lc-reo').hover(function() {
			$('.lc-container').addClass('reo');
		}, function() {
			$('.lc-container').removeClass('reo');
		});
		$('#lc-sold').hover(function() {
			$('.lc-container').addClass('sold');
		}, function() {
			$('.lc-container').removeClass('sold');
		});
		var changeSlide = function(i) {
			var theSlide = i;
			setTimeout(function() {
				$('.lc-container').css('background-image', 'url(' + ri_scripts.theme_images_url + 'life-cycle-0' + theSlide + '.png)	');
				if (theSlide == 7) {
					theSlide = 0;
				}
				theSlide += 1;
				changeSlide(theSlide);
			}, 2000);	
		}
		changeSlide(1);
	});

	/* ----- FEATURES SLIDER ----- */
	$(document).ready(function() {
		var homeSlider = $('#features').lightSlider({
			autoWidth: false,
			adaptiveHeight: true,
			mode: 'fade',
			speed: 500, //ms' 
			controls: false,
			pager: false,
			enableTouch: false,
			enableDrag: false,
			freeMove: false
		});
		$('.features-menu .option').click(function() {
			var theOption = $(this),
			childNumber = theOption.index();
			$('.features-menu .option').removeClass('active');
			theOption.addClass('active');
			homeSlider.goToSlide(childNumber);
		});
		var clientSlider = $('#clients').lightSlider({
			item: 1,
			autoWidth: false,
			adaptiveHeight: true,
			mode: 'slide',
			speed: 500, //ms' 
			controls: false,
			pager: false,
			enableTouch: true,
			enableDrag: true,
			freeMove: true
		});
		$('.client-controls #left').click(function() {
			clientSlider.goToPrevSlide();
		});
		$('.client-controls #right').click(function() {
			clientSlider.goToNextSlide();
		});
	});

	/* ----- ACCORDION FUNCTIONS ----- */
	$(document).ready(function() {
		$('.toggle').click(function() {
			$(this).toggleClass('active');
			$(this).closest('.accordion').next('.accordion-content').slideToggle(250);
			return false;
		});
	});

	/* ----- GRID FUNCTIONS ----- */
	var resizeGrid = function() {
		if (window.matchMedia('(min-width: 620px)').matches) {
			$('.grid div:nth-child(2n+1)').each(function() {
				var leftElement = $(this),
				leftHeight = leftElement.innerHeight(),
				rightElement = $(this).next(),
				rightHeight = rightElement.innerHeight();
				if (leftHeight > rightHeight) {
					rightElement.css('height', leftHeight + 2 + 'px');
				}
				else {
					leftElement.css('height', rightHeight + 2 + 'px');
				}
			});
		}
	}
	$(document).ready(function() {
		resizeGrid();
		$(window).resize(function() {
			$('.grid > div').removeAttr('style');
			resizeGrid();
		});
	});

	/* ----- NIVO LIGHTBOX INITIALIZATION ----- */
	$(document).ready(function() {
		$('a.lightbox').nivoLightbox({
			 onPrev: function() {$('.nivo-lightbox-content').hide().fadeIn(250);},
			 onNext: function() {$('.nivo-lightbox-content').hide().fadeIn(250);}
		});
	});

	/* ----- GOOGLE MAPS API ----- */
	$(document).ready(function() {
		var map,
		MY_MAPTYPE_ID = 'RealINSIGHT',
		mapLocation = new google.maps.LatLng(38.9854212, -77.093595);
		
		if (window.matchMedia('(max-width: 960px)').matches) { // TABLET CENTER
			var mapCenter = new google.maps.LatLng(38.9911669, -77.133389);
		}
		else {
			var mapCenter = new google.maps.LatLng(38.9911669, -77.163389);
		}
		
		function initialize() {
		
		  var featureOpts = [
			   {
				"stylers": [
				  { "saturation": -100 }
				]
			  },{
				"featureType": "road.local",
				"elementType": "geometry",
				"stylers": [
				  { "color": "#454545" },
				  { "visibility": "on" }
				]
			  },{
				"featureType": "road.highway",
				"elementType": "geometry",
				"stylers": [
				  { "color": "#666666" }
				]
			  },{
				"featureType": "landscape",
				"elementType": "geometry",
				"stylers": [
				  { "color": "#333333" }
				]
			  },{
				"featureType": "water",
				"elementType": "geometry",
				"stylers": [
				  { "color": "#646464" }
				]
			  },{
				"featureType": "poi",
				"stylers": [
				  { "visibility": "off" }
				]
			  },{
				"featureType": "road.arterial",
				"elementType": "geometry.fill",
				"stylers": [
				  { "color": "#111111" },
				  { "visibility": "on" }
				]
			  },{
				"featureType": "road.arterial",
				"elementType": "geometry.stroke",
				"stylers": [
				  { "color": "#454545" },
				  { "weight": 1.5 }
				]
			  },{
				"elementType": "labels.text.fill",
				"stylers": [
				  { "color": "#686868" }
				]
			  },{
				"elementType": "labels.text.stroke",
				"stylers": [
				  { "visibility": "on" },
				  { "color": "#222222" }
				]
			  },{
				"featureType": "road",
				"elementType": "labels.icon",
				"stylers": [
				  { "visibility": "on" },
				  { "lightness": -60 }
				]
			  }
		  ];
		
		  var mapOptions = {
			zoom: 13,
			center: mapCenter,
			disableDefaultUI: true,
			scrollwheel: false,
			mapTypeControlOptions: {
			  mapTypeIds: [google.maps.MapTypeId.ROADMAP, MY_MAPTYPE_ID]
			},
			mapTypeId: MY_MAPTYPE_ID
		  };
		
		  map = new google.maps.Map(document.getElementById('map-canvas'),
			  mapOptions);
		
		  var styledMapOptions = {
			name: 'RealINSIGHT'
		  };
		
		  var customMapType = new google.maps.StyledMapType(featureOpts, styledMapOptions);
		
		  map.mapTypes.set(MY_MAPTYPE_ID, customMapType);
		  
		  var markerImage = 'images/marker.svg',
		  myLatLng = mapLocation,
		  mapMarker = new google.maps.Marker({
			  position: myLatLng,
			  map: map,
			  icon: markerImage
		  });
		}
		
		google.maps.event.addDomListener(window, 'load', initialize);
	});

} )( jQuery );	