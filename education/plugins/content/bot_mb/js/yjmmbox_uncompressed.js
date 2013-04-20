/*
 * popbox by Boris Popoff (http://gueschla.com)
 *
 * Based on Cody Lindley's Thickbox, MIT License
 *
 * Licensed under the MIT License:
 *   http://www.opensource.org/licenses/mit-license.php
 */

// on page




window.addEvent('domready', POP_init);


// prevent javascript error before the content has loaded
POP_WIDTH = 0;
POP_HEIGHT = 0;
var POP_doneOnce = 0 ;

// add popbox to href elements that have a class of .popbox
function POP_init(){
	$$("a.popbox").each(function(el){el.onclick=POP_bind});
}

function POP_bind(event) {
	var event = new Event(event);
	// stop default behaviour
	event.preventDefault();
	// remove click border
	this.blur();
	// get caption: either title or name attribute
	var caption = this.title || this.name || "";
	// get rel attribute for image groups
	var group = this.rel || false;
	// display the box for the elements href
	POP_show(caption, this.href, group);
	this.onclick=POP_bind;
	return false;
}


// called when the user clicks on a popbox link
function POP_show(caption, url, rel) {
		
		
		
	// create iframe, overlay and box if non-existent

	if ( !$("POP_overlay") )
	{
		new Element('iframe').setProperty('id', 'POP_HideSelect').injectInside(document.body);
		$('POP_HideSelect').setOpacity(0.2) //make it 50% transparent
		new Element('div').setProperty('id', 'POP_overlay').injectInside(document.body);
		$('POP_overlay').setOpacity(0.2) //make it 50% transparent
		POP_overlaySize();
		new Element('div').setProperty('id', 'POP_load').injectInside(document.body);
		$('POP_load').innerHTML = "<img src='plugins/content/bot_mb/images/loading2.gif' />";
             	POP_load_position();
		new Fx.Style('POP_overlay', 'opacity',{duration: 500, transition: Fx.Transitions.sineInOut}).start(0,0.6);

	}
	
	if ( !$("POP_load") )
	{		
		new Element('div').setProperty('id', 'POP_load').injectInside(document.body);
		$('POP_load').innerHTML = "<img src='plugins/content/bot_mb/images/loading2.gif' />";
		POP_load_position();
	}
	
	if ( !$("POP_window") )
	{
		new Element('div').setProperty('id', 'POP_window').injectInside(document.body);		
		$('POP_window').setStyles({'top':150, 'display':'block', 'opacity':.2});
	}
	/* my code */
	POP_FX = new Fx.Styles('POP_window', {duration: 200, transition: Fx.Transitions.linear, wait:false});
	
	$("POP_overlay").onclick=POP_remove;
	window.onscroll=POP_positionEffect;

	// check if a query string is involved
	var baseURL = url.match(/(.+)?/)[1] || url;

	// regex to check if a href refers to an image
	var imageURL = /\.(jpe?g|png|gif|bmp)/gi;

	// check for images
	if ( baseURL.match(imageURL) ) {
		var dummy = { caption: "", url: "", html: "" };
		
		var prev = dummy,
			next = dummy,
			imageCount = "";
			
		// if an image group is given
		if ( rel ) {
			function getInfo(image, id, label) {
				return {
					caption: image.title,
					url: image.href,
					html: "<span id='POP_" + id + "'>&nbsp;&nbsp;<a href='#'>" + label + "</a></span>"
				}
			}
		
			// find the anchors that point to the group
			var imageGroup = [] ;
			$$("a.popbox").each(function(el){
				if (el.rel==rel) {imageGroup[imageGroup.length] = el ;}
			})

			var foundSelf = false;
			
			// loop through the anchors, looking for ourself, saving information about previous and next image
			for (var i = 0; i < imageGroup.length; i++) {
				var image = imageGroup[i];
				var urlTypeTemp = image.href.match(imageURL);
				
				// look for ourself
				if ( image.href == url ) {
					foundSelf = true;
					imageCount = "Image " + (i + 1) + " of "+ (imageGroup.length);
				} else {
					// when we found ourself, the current is the next image
					if ( foundSelf ) {
						next = getInfo(image, "next", "<div id='next'></div>");
						// stop searching
						break;
					} else {
						// didn't find ourself yet, so this may be the one before ourself
						prev = getInfo(image, "prev", "<div id='prev'></div>");
					}
				}
			}
		}
		
		imgPreloader = new Image();

		imgPreloader.onload = function() {
			imgPreloader.onload = null;

			// Resizing large images
			var x = window.getWidth() - 150;
			var y = window.getHeight() - 150;
			var imageWidth = imgPreloader.width;
			var imageHeight = imgPreloader.height;
			if (imageWidth > x) {
				imageHeight = imageHeight * (x / imageWidth); 
				imageWidth = x; 
				if (imageHeight > y) { 
					imageWidth = imageWidth * (y / imageHeight); 
					imageHeight = y; 
				}
			} else if (imageHeight > y) { 
				imageWidth = imageWidth * (y / imageHeight); 
				imageHeight = y; 
				if (imageWidth > x) { 
					imageHeight = imageHeight * (x / imageWidth); 
					imageWidth = x;
				}
			}
			// End Resizing
			
			// TODO don't use globals
			POP_WIDTH = imageWidth + 30;
			POP_HEIGHT = imageHeight + 60;
			
			if (window.opera){	
				var fromLeft = (window.getScrollLeft() + (window.getWidth() - POP_WIDTH)/2);
				var fromTop = (window.getScrollTop() + (window.getHeight() - POP_HEIGHT)/11);
			}else{		
				var fromLeft = (window.getScrollLeft() + (window.getWidth() - POP_WIDTH)/2);
				var fromTop = (window.getScrollTop() + (window.getHeight() - POP_HEIGHT)/2);				
			}	
			
			
			if (POP_doneOnce==0){
				POP_FX.set({
					'height': POP_HEIGHT.toInt(),
	   				'width': POP_WIDTH.toInt(),
	   				'top':fromTop.toInt(),
	   				'left':fromLeft.toInt()
				});
			}
			
			/* my code */
			POP_FX.start({
				'height': POP_HEIGHT.toInt(),
   				'width': POP_WIDTH.toInt(),
   				'top':fromTop.toInt(),
   				'left':fromLeft.toInt()
			}).chain(function(){
				
				
				
				// TODO empty window content instead
				$("POP_window").innerHTML += "<a href='' id='POP_ImageOff' title='Close'><img id='POP_Image' src='"+url+"' width='"+imageWidth+"' height='"+imageHeight+"' alt='"+caption+"'/></a>" + "<div id='POP_caption'>"+caption+"<div id='POP_secondLine'>" + imageCount + prev.html + next.html + "<div id='naslink'>Powered by <a href='http://www.youjoomla.com' title='Youjoomla Joomla Templates Club. Home of Multimedia Box' target='_blank' >Multimedia Box</a></div> </div></div><div id='POP_closeWindow'><a href='#' id='POP_closeWindowButton' title='Close'><img id='close' src='plugins/content/bot_mb/images/close.png' /></a></div>";
				$("POP_closeWindowButton").onclick = POP_remove;
				
				function buildClickHandler(image) {
					return function() {
						$("POP_window").empty();
						//new Element('div').setProperty('id', 'POP_window').injectInside(document.body);
						
						POP_show(image.caption, image.url, rel);
						return false;
					};
				}
				var goPrev = buildClickHandler(prev);
				var goNext = buildClickHandler(next);
				if ( $('POP_prev') ) {
					$("POP_prev").onclick = goPrev;
				}
				
				if ( $('POP_next') ) {		
					$("POP_next").onclick = goNext;
				}
				
				document.onkeydown = function(event) {
					var event = new Event(event);
					switch(event.code) {
					case 27:
						POP_remove();
						break;
					case 190:
						if( $('POP_next') ) {
							document.onkeydown = null;
							goNext();
						}
						break;
					case 188:
						if( $('POP_prev') ) {
							document.onkeydown = null;
							goPrev();
						}
						break;
					}
				}
				
				// TODO don't remove loader etc., just hide and show later
				$("POP_ImageOff").onclick = POP_remove;
				
				
				
			});
		
			//POP_position();
			POP_showWindow();			
			
		}
		imgPreloader.src = url;
		
	} else { //code to show html pages
		
		var queryString = url.match(/\?(.+)/)[1];
		var params = POP_parseQuery( queryString );
		
		POP_WIDTH = (params['width']*1) + 30;
		POP_HEIGHT = (params['height']*1) + 40;

		var ajaxContentW = POP_WIDTH - 30,
			ajaxContentH = POP_HEIGHT - 45;
		
		if(url.indexOf('POP_iframe') != -1){				
			urlNoQuery = url.split('POP_');		
			$("POP_window").innerHTML += "<div id='POP_title'><div id='POP_ajaxWindowTitle'>"+caption+"</div><div id='naslink'>Powered by <a href='http://www.youjoomla.com' title='Youjoomla Joomla Templates Club. Home of Multimedia Box' target='_blank' >Multimedia Box</a></div><div id='POP_closeAjaxWindow'><a href='#' id='POP_closeWindowButton' title='Close'><img id='close' src='plugins/content/bot_mb/images/close.png' /></div></div><iframe frameborder='0' hspace='0' src='"+urlNoQuery[0]+"' id='POP_iframeContent' name='POP_iframeContent' style='width:"+(ajaxContentW + 29)+"px;height:"+(ajaxContentH + 17)+"px;' onload='POP_showWindow()'> </iframe>";
		} else {
			$("POP_window").innerHTML += "<div id='POP_title'><div id='POP_ajaxWindowTitle'>"+caption+"</div><div id='POP_closeAjaxWindow'><a href='#' id='POP_closeWindowButton'><img id='close' src='plugins/content/bot_mb/images/close.png' /></a></div></div><div id='POP_ajaxContent' style='width:"+ajaxContentW+"px;height:"+ajaxContentH+"px;'></div>";
		}
				
		$("POP_closeWindowButton").onclick = POP_remove;
		
			if(url.indexOf('POP_inline') != -1){	
				$("POP_ajaxContent").innerHTML = ($(params['inlineId']).innerHTML+"<div id='naslink'>Powered by <a href='http://www.youjoomla.com' title='Youjoomla Joomla Templates Club. Home of Multimedia Box' target='_blank' >Multimedia Box</a></div>");
				POP_position();
				POP_showWindow();
			}else if(url.indexOf('POP_iframe') != -1){
				POP_position();
				if(frames['POP_iframeContent'] == undefined){//be nice to safari
					$(document).keyup( function(e){ var key = e.keyCode; if(key == 27){POP_remove()} });
					POP_showWindow();
				}
			}else{
				var handlerFunc = function(){
					POP_position();
					POP_showWindow();
				};
				var myRequest = new Ajax(url, {method: 'get',update: $("POP_ajaxContent"),onComplete: handlerFunc}).request();
			}
	}

	window.onresize=function(){ POP_position(); POP_load_position(); POP_overlaySize();}  
	
	document.onkeyup = function(event){ 	
		var event = new Event(event);
		if(event.code == 27){ // close
			POP_remove();
		}	
	}
		
}

//helper functions below

function POP_showWindow(){
	//$("POP_load").remove();
	//$("POP_window").setStyles({display:"block",opacity:'0'});
	
	if (POP_doneOnce==0) {
		POP_doneOnce = 1;
		var myFX = new Fx.Style('POP_window', 'opacity',{
			duration: 250, 
			transition: Fx.Transitions.sineInOut, 
			onComplete:function(){
				if ($('POP_load')) { 
					$('POP_load').remove();
				}
			} 
		}).start(0,1);
	} else {
		$('POP_window').setStyle('opacity',1);
		if ($('POP_load')) { $('POP_load').remove();}
	}
}

function POP_remove() {
 	$("POP_overlay").onclick=null;
	document.onkeyup=null;
	document.onkeydown=null;
	
	if ($('POP_imageOff')) $("POP_imageOff").onclick=null;
	if ($('POP_closeWindowButton')) $("POP_closeWindowButton").onclick=null;
	if ( $('POP_prev') ) { $("POP_prev").onclick = null; }
	if ( $('POP_next') ) { $("POP_next").onclick = null; }

	new Fx.Style('POP_window', 'opacity',{duration: 500, transition: Fx.Transitions.sineInOut, onComplete:function(){$('POP_window').remove();} }).start(1,0);
	new Fx.Style('POP_overlay', 'opacity',{duration: 500, transition: Fx.Transitions.sineInOut, onComplete:function(){$('POP_overlay').remove();} }).start(0.6,0);

	window.onscroll=null;
	window.onresize=null;	
	
	$('POP_HideSelect').remove();
	POP_init();
	POP_doneOnce = 0;
	return false;
}

function POP_position() {
	
/*	if (window.opera){
	
	$("POP_window").setStyles({width: POP_WIDTH+'px', 
				 left: (window.getScrollLeft() + (window.getWidth() - POP_WIDTH)/2)+'px',
				 top: (window.getScrollTop() + (window.getHeight() - POP_HEIGHT)/2)+'px'});
				 //top: '170px'});
				 }else{
					$("POP_window").setStyles({width: POP_WIDTH+'px', 
				 left: (window.getScrollLeft() + (window.getWidth() - POP_WIDTH)/2)+'px',
				 top: (window.getScrollTop() + (window.getHeight() - POP_HEIGHT)/2)+'px'});
				 //top: '170px'});	 
					 
					 
				 }*/
	
	POP_positionEffect();			 
}

function POP_positionEffect() {
		
	if (window.opera){
	
		POP_FX.start({
			'left':(window.getScrollLeft() + (window.getWidth() - POP_WIDTH)/2),
			//'top':(window.getScrollTop() + (window.getHeight() - POP_HEIGHT)/2)+'px'});
			'top':(window.getScrollTop() + (window.getHeight() - POP_HEIGHT)/11)
		});		
		
	}else{		
		
		POP_FX.start({
			'left':(window.getScrollLeft() + (window.getWidth() - POP_WIDTH)/2),
			//'top':(window.getScrollTop() + (window.getHeight() - POP_HEIGHT)/2)+'px'});
			'top':(window.getScrollTop() + (window.getHeight() - POP_HEIGHT)/2)
		});		
	}	
}

function POP_overlaySize(){
	// we have to set this to 0px before so we can reduce the size / width of the overflow onresize 
	$("POP_overlay").setStyles({"height": '0px', "width": '0px'});
	$("POP_HideSelect").setStyles({"height": '0px', "width": '0px'});
	if (window.opera){
	$("POP_overlay").setStyles({"height": window.getScrollHeight()+500 +'px', "width": window.getScrollWidth()+'px'});
	}else{
		$("POP_overlay").setStyles({"height": window.getScrollHeight()+'px', "width": window.getScrollWidth()+'px'});
	}
	$("POP_HideSelect").setStyles({"height": window.getScrollHeight()+'px',"width": window.getScrollWidth()+'px'});
}

function POP_load_position() {
	if ($("POP_load")) { 
		$("POP_load").setStyles({
			left: (window.getScrollLeft() + (window.getWidth() - 56)/2)+'px', 
			top: (window.getScrollTop() + ((window.getHeight()-20)/2))+'px',
			display:"block"
		}); 
	}
}

function POP_parseQuery ( query ) {
	// return empty object
	if( !query )
		return {};
	var params = {};
	
	// parse query
	var pairs = query.split(/[;&]/);
	for ( var i = 0; i < pairs.length; i++ ) {
		var pair = pairs[i].split('=');
		if ( !pair || pair.length != 2 )
			continue;
		// unescape both key and value, replace "+" with spaces in value
		params[unescape(pair[0])] = unescape(pair[1]).replace(/\+/g, ' ');
   }
   return params;

}


/*
	Mediabox version 0.7.3 - John Einselen (http://iaian7.com)
	updated 24.09.07

	tested in OS X 10.5 using FireFox 3, Flock 2, Opera 9.6, Safari 3, and Camino 1.5
	tested in Windows Vista using Internet Explorer 7, FireFox 2, Opera 9, and Safari 3
	loads flash, flv, quicktime, wmv, and html content in a Lightbox-style window effect.

	based on Slimbox version 1.4 - Christophe Beyls (http://www.digitalia.be)
			 Slimbox Extended version 1.3.1 - Yukio Arita (http://homepage.mac.com/yukikun/software/slimbox_ex/)
			 Videobox Mod version 0.1 - Faruk Can 'farkob' Bilir (http://www.gobekdeligi.com/videobox/)
			 DM_Moviebox.js - Ductchmonkey (http://lib.dutchmoney.com/)
			(licensed same as originals, MIT-style)

	inspired by the grandaddy of them all, Lightbox v2 - Lokesh Dhakar (http://www.huddletogether.com/projects/lightbox2/)

	distributed under the MIT license
*/

var Mediabox = {
	init: function(options){
		this.options = Object.extend({
			resizeDuration: 240,
			resizeTransition: Fx.Transitions.sineInOut,
			overlayOpacity: 0.6,		// Overlay opacity, 0-1
			topDistance: 15,			// Divisor of the total visible window height, higher number = higher Mediabox placement on screen
										// If you wish to change this to an absolute pixel value, scroll down to lines 128 and 129 and swap the commenting slashes
			initialWidth: 360,
			initialHeight: 360,
			defaultWidth: 640,			// Default width (px)
			defaultHeight: 360,			// Default height(px)
			animateCaption: true,		// This is not smooth animation in IE 6 with XML prolog.
										// If your site is XHTML strict with XML prolog, disable this option.
		// Mediaplayer settings and options
			playerpath: 'plugins/content/bot_mb/js/mediaplayer.swf',	// Path to the mediaplayer.swf or flvplayer.swf file
			backcolor:  '0x777777',		// Base color for the controller, color name / hex value (0x000000)
			frontcolor: '0x000000',		// Text and button color for the controller, color name / hex value (0x000000)
			lightcolor: '0x000000',		// Rollover color for the controller, color name / hex value (0x000000)
			fullscreen: 'true',			// Display fullscreen button
			autostart: 'true',			// Automatically plays the video on load
		// Quicktime options (QT plugin used for partial WMV support as well)
			autoplay: 'true',			// Automatically play movie, true / false
			bgcolor: 'black',			// Background color, name / hex value
			controller: 'true',			// Show controller, true / false
		// Flickr options
			fkBGcolor: '#000000',		// Background colour option
			fkFullscreen: 'true',		// Enable fullscreen button
		// Revver options
			revverID: '187866',			// Revver affiliate ID
			revverFullscreen: 'true',	// Fullscreen option
			revverBack: '#000000',		// Background colour
			revverFront: '#ffffff',		// Foreground colour
			revverGrad: '#000000',		// Gradation colour
		// Seesmic options
			ssFullscreen: 'true',		// Fullscreen option for Seesmic
		// Youtube options
			ytAutoplay: '1',			// Auto play, 0=false, 1=true
		// Veoh options
			vhAutoplay: '1',			// Enable autoplay, 0=false 1=true
			vhFullscreen: 'true',		// Enable fullscreen
		// Vimeo options
			vmFullscreen: '1',			// Fullscreen option, 0=false, 1=true
			vmTitle: '1',				// Show video title
			vmByline: '1',				// Show byline
			vmPortrait: '1',			// Show author portrait
			vmColor: '5ca0b5'			// Custom controller colours, hex value minus the #
		}, options || {});

		if(window.ie6 && document.compatMode=="BackCompat"){ this.options.animateCaption = false; }	// IE 6 - XML prolog problem

		this.anchors = [];
		$each(document.links, function(el){
			if (el.rel && el.rel.test(/^mediabox/i)){
				if(el.href.match(/\#yjmb_/i)){
					el.removeEvents();
				}
				el.onclick = this.click.pass(el, this);
				this.anchors.push(el);
			}
		}, this);
		this.eventKeyDown = this.keyboardListener.bindAsEventListener(this);
		this.eventPosition = this.position.bind(this);
		this.overlay = new Element('div').setProperty('id', 'lbOverlay').injectInside(document.body);
		this.center = new Element('div').setProperty('id', 'lbCenter').setStyles({width: this.options.initialWidth+'px', height: this.options.initialHeight+'px', marginLeft: '-'+(this.options.initialWidth/2)+'px', display: 'none'}).injectInside(document.body);
		this.canvas = new Element('div').setProperty('id', 'lbImage').injectInside(this.center);
		this.bottomContainer = new Element('div').setProperty('id', 'lbBottomContainer').setStyle('display', 'none').injectInside(document.body);
		this.bottom = new Element('div').setProperty('id', 'lbBottom').injectInside(this.bottomContainer);
		new Element('a').setProperties({id: 'lbCloseLink', href: '#'}).injectInside(this.bottom).onclick = this.overlay.onclick = this.close.bind(this);
		this.caption = new Element('div', {'id': 'lbCaption'}).injectInside(this.bottom);
		new Element('div').setStyle('clear', 'both').injectInside(this.bottom);

		/* Build effects */
		var nextEffect = this.nextEffect.bind(this);
		this.fx = {
			overlay: this.overlay.effect('opacity', {duration: 500}).hide(),
			center: this.center.effects({duration: this.options.resizeDuration, transition: this.options.resizeTransition, onComplete: nextEffect}),
			content: this.canvas.effect('opacity', {duration: 500, onComplete: nextEffect}),
			bottom: this.bottomContainer.effect('height', {duration: 400, onComplete: nextEffect})
		};
	},

	click: function(link){
		return this.open(link.href, link.title, link.rel);
	},

	open: function(url, title, rel){
		this.href = url;
		this.title = title;
		this.rel = rel;
		this.position();
		this.setup(true);
		var wh = (window.getHeight() == 0) ? window.getScrollHeight() : window.getHeight();
		var st = document.body.scrollTop  || document.documentElement.scrollTop;
		this.top = st + (wh / this.options.topDistance);
//		this.top = 100;	// this is the code needed for an absolute pixel value, instead of proportional positioning
		this.center.setStyles({top: this.top+'px', display: ''});
		this.fx.overlay.start(this.options.overlayOpacity);
		this.center.className = 'lbLoading';
		return this.loadVideo(url);
	},

	position: function(){
		this.overlay.setStyles({'top': window.getScrollTop()+'px', 'height': window.getHeight()+'px'});
	},

	setup: function(open){
		var aDim = this.rel.match(/[0-9]+/g);													// videobox rel settings
		this.contentsWidth = (aDim && (aDim[0] > 0)) ? aDim[0] : this.options.defaultWidth;		// videobox rel settings
		this.contentsHeight = (aDim && (aDim[1] > 0)) ? aDim[1] : this.options.defaultHeight;	// videobox rel settings

		var elements = $A(document.getElementsByTagName('object'));								// hide page content
		elements.extend(document.getElementsByTagName(window.ie ? 'select' : 'embed'));
		elements.each(function(el){
			if (open) el.lbBackupStyle = el.style.visibility;
			el.style.visibility = open ? 'hidden' : el.lbBackupStyle;
		});

		var fn = open ? 'addEvent' : 'removeEvent';
		window[fn]('scroll', this.eventPosition)[fn]('resize', this.eventPosition);
		document[fn]('keydown', this.eventKeyDown);
		this.step = 0;
	},

	keyboardListener: function(event){
		switch (event.keyCode){
			case 27: case 88: case 67: this.close(); break;
		}
	},

	loadVideo: function(url){
		this.step = 1;

// DailyMotion
		if (url.match(/dailymotion\.com/i)) {
			this.type = 'flash';
			this.object = new SWFObject(url, "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
// Flickr
		} else if (url.match(/flickr\.com/i)) {
			this.type = 'flashobj';
			var videoId = url.split('/');
			this.videoID = videoId[5];
			this.object = '<object type="application/x-shockwave-flash" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" data="http://www.flickr.com/apps/video/stewart.swf?v=1.173" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"> <param name="flashvars" value="intl_lang=en-us&amp;photo_secret=a8e6cdca81&amp;photo_id='+this.videoID+'"></param> <param name="movie" value="http://www.flickr.com/apps/video/stewart.swf?v=1.173"></param> <param name="bgcolor" value="'+this.options.fkBGcolor+'"></param> <param name="allowFullScreen" value="'+this.options.fkFullscreen+'"></param><embed type="application/x-shockwave-flash" src="http://www.flickr.com/apps/video/stewart.swf?v=1.173" bgcolor="'+this.options.fkBGcolor+'" allowfullscreen="'+this.options.fkFullscreen+'" flashvars="intl_lang=en-us&amp;photo_secret=a8e6cdca81&amp;photo_id='+this.videoID+'" height="'+this.contentsHeight+'" width="'+this.contentsWidth+'"></embed></object>';
// Google Video
		} else if (url.match(/google\.com\/videoplay/i)) {
			this.type = 'flash';
			var videoId = url.split('=');
			this.videoID = videoId[1];
			this.object = new SWFObject("http://video.google.com/googleplayer.swf?docId="+this.videoID+"&autoplay=1&hl=en", "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
// Metacafe
		} else if (url.match(/metacafe\.com\/watch/i)) {
			this.type = 'flash';
			var videoId = url.split('/');
			this.videoID = videoId[4];
			this.object = new SWFObject("http://www.metacafe.com/fplayer/"+this.videoID+"/.swf", "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
// MyspaceTV
		} else if (url.match(/myspacetv\.com/i)) {
			this.type = 'flash';
			var videoId = url.split('=');
			this.videoID = videoId[2];
			this.object = new SWFObject("http://lads.myspace.com/videos/vplayer.swf?m="+this.videoID+"&v=2&type=video", "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
//			this.type = 'flashobj';
//			this.object = '<embed src="http://lads.myspace.com/videos/vplayer.swf" flashvars="m='+this.videoID+'&v=2&type=video" type="application/x-shockwave-flash" allowFullScreen="true" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" bgcolor="#FFFFFF" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>';
// Revver
		} else if (url.match(/revver\.com/i)) {
			this.type = 'flash';
			var videoId = url.split('/');

			this.videoID = videoId[4];
			this.object = new SWFObject("http://flash.revver.com/player/1.0/player.swf?mediaId="+this.videoID+"&affiliateId="+this.options.revverID+"&allowFullScreen="+this.options.revverFullscreen+"&backColor="+this.options.revverBack+"&frontColor="+this.options.revverFront+"&gradColor="+this.options.revverGrad+"&shareUrl=revver", "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
//			this.type = 'flashobj';
//			this.object = '<object width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" data="http://flash.revver.com/player/1.0/player.swf?mediaId='+this.videoID+'&affiliateId='+this.options.revverID+'" type="application/x-shockwave-flash" id="revvervideoa17743d6aebf486ece24053f35e1aa23"><param name="Movie" value="http://flash.revver.com/player/1.0/player.swf?mediaId='+this.videoID+'&affiliateId='+this.options.revverID+'"></param><param name="FlashVars" value="allowFullScreen='+this.options.revverFullscreen+'&backColor=#000000&frontColor=#ffffff&gradColor=#000000&shareUrl=revver"></param><param name="AllowFullScreen" value="'+this.options.revverFullscreen+'"></param><param name="AllowScriptAccess" value="always"></param><embed type="application/x-shockwave-flash" src="http://flash.revver.com/player/1.0/player.swf?mediaId='+this.videoID+'&affiliateId='+this.options.revverID+'" pluginspage="http://www.macromedia.com/go/getflashplayer" allowScriptAccess="always" flashvars="allowFullScreen='+this.options.revverFullscreen+'&backColor=#000000&frontColor=#ffffff&gradColor=#000000&shareUrl=revver" allowfullscreen="'+this.options.revverFullscreen+'" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'"></embed></object>';
// Seesmic
		} else if (url.match(/seesmic\.com/i)) {
			this.type = 'flash';
			var videoId = url.split('/');
			this.videoID = videoId[5];
			this.object = new SWFObject("http://seesmic.com/Standalone.swf?video="+this.videoID, "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen',this.options.ssFullscreen);
//			this.type = 'flashobj';
//			this.object = '<object width="'+this.contentsWidth+'" height="'+this.contentsHeight+'"><param name="movie" value="http://seesmic.com/Standalone.swf?video='+this.videoID+'"></param><param name="allowFullScreen" value="'+this.options.ssFullscreen+'" /><embed src="http://seesmic.com/Standalone.swf?video='+this.videoID+'" type="application/x-shockwave-flash" allowFullScreen="'+this.options.ssFullscreen+'" wmode="transparent" allowScriptAccess="sameDomain" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'"></embed></object>';
// YouTube
		} else if (url.match(/youtube\.com\/watch/i)) {
			this.type = 'flash';
			var videoId = url.split('=');
			this.videoID = videoId[1];
			this.object = new SWFObject("http://www.youtube.com/v/"+this.videoID+"&autoplay=1", "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
// Veoh
		} else if (url.match(/veoh\.com/i)) {
			this.type = 'flash';
			var videoId = url.split('videos/');
			this.videoID = videoId[1];
			this.object = new SWFObject("http://www.veoh.com/videodetails2.swf?permalinkId="+this.videoID+"&id=2907158&player=videodetailsembedded&videoAutoPlay="+this.options.vhAutoplay, "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
//			this.type = 'flashobj';

//			this.object = '<embed src="http://www.veoh.com/videodetails2.swf?permalinkId='+this.videoID+'&id=2907158&player=videodetailsembedded&videoAutoPlay='+this.options.vhAutoplay+'" allowFullScreen="'+this.options.vhFullscreen+'" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" bgcolor="#FFFFFF" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer"></embed>';
// Viddler
		} else if (url.match(/viddler\.com/i)) {
			var videoId = url.split('/');
			this.videoId1 = videoId[4];
			this.videoId2 = videoId[6];
			this.videoID = "viddler_"+this.videoId1+"_"+this.videoId2;
//			this.type = 'flash';
//			this.object = new SWFObject("http://www.veoh.com/videodetails2.swf?permalinkId="+this.videoID+"&id=2907158&player=videodetailsembedded&videoAutoPlay="+this.options.vhAutoplay, "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
//			this.object.addParam('allowscriptaccess','always');
//			this.object.addParam('allowfullscreen','true');
			this.type = 'flashobj';
			this.object = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" id="'+this.videoID+'"><param name="movie" value="http://www.viddler.com/player/e5398221/" /><param name="allowScriptAccess" value="always" /><param name="allowFullScreen" value="'+this.options.vdFullscreen+'" /><embed src="http://www.viddler.com/player/e5398221/" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" type="application/x-shockwave-flash" allowScriptAccess="always" allowFullScreen="'+this.options.vdFullscreen+'" name="'+this.videoID+'" ></embed></object>';
// Vimeo
		} else if (url.match(/vimeo\.com/i)) {
			this.type = 'flash';
			var videoId = url.split('/');
			this.videoID = videoId[3];
			this.object = new SWFObject("http://www.vimeo.com/moogaloop.swf?clip_id="+this.videoID+"&amp;server=www.vimeo.com&amp;fullscreen=1&amp;show_title=1&amp;show_byline=1&amp;show_portrait=1&amp;color=5ca0b5", "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
//			this.type = 'flashobj';
//			this.object = '<object id="mediabox" type="application/x-shockwave-flash" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" data="http://www.vimeo.com/moogaloop.swf?clip_id='+this.videoID+'&amp;server=www.vimeo.com&amp;fullscreen='+this.options.vmFullscreen+'&amp;show_title='+this.options.vmTitle+'&amp;show_byline='+this.options.vmByline+'&amp;show_portrait='+this.options.vmPortrait+'&amp;color=5ca0b5"><param name="quality" value="best" /><param name="allowfullscreen" value="true" /><param name="scale" value="showAll" /><param name="movie" value="http://www.vimeo.com/moogaloop.swf?clip_id='+this.videoID+'&amp;server=www.vimeo.com&amp;fullscreen='+this.options.vmFullscreen+'&amp;show_title='+this.options.vmTitle+'&amp;show_byline='+this.options.vmByline+'&amp;show_portrait='+this.options.vmPortrait+'&amp;color='+this.options.vmColor+'" /></object>';
// 12seconds
		} else if (url.match(/12seconds\.tv/i)) {
			var videoId = url.split('/');
			this.videoID = videoId[5];
//			this.type = 'flash';
//			this.object = new SWFObject("http://embed.12seconds.tv/players/remotePlayer.swf", "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
//			this.object.addParam('FlashVars', 'vid='+this.videoID+'');
//			this.object.addParam('allowscriptaccess','always');
//			this.object.addParam('allowfullscreen','true');
			this.type = 'flashobj';
			this.object = '<object type="application/x-shockwave-flash" data="http://embed.12seconds.tv/players/remotePlayer.swf" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" ><param name="movie" value="http://embed.12seconds.tv/players/remotePlayer.swf" /><param name="FlashVars" value="vid='+this.videoID+'"/><embed src="http://embed.12seconds.tv/players/remotePlayer.swf" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" flashvars="vid='+this.videoID+'"></embed></object>';
// Flash .SWF
		} else if (url.match(/\.swf/i)) {
			this.type = 'flash';
			this.object = new SWFObject(url, "sfwvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
// Flash .FLV
		} else if (url.match(/\.flv/i)) {
			this.type = 'flash';
			this.object = new SWFObject(this.options.playerpath+"?file="+url+"&autostart="+this.options.autostart+"&displayheight="+this.contentsHeight+"&allowfullscreen="+this.options.fullscreen+"&usefullscreen="+this.options.fullscreen+"&backcolor="+this.options.backcolor+"&frontcolor="+this.options.frontcolor+"&lightcolor="+this.options.lightcolor, "flvvideo", this.contentsWidth, this.contentsHeight, "9", "#000000", "wmode", "transparent");
			this.object.addParam('allowscriptaccess','always');
			this.object.addParam('allowfullscreen','true');
//			this.type = 'flashobj';
//			this.object = '<embed src="'+this.options.playerpath+'" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" allowscriptaccess="always" allowfullscreen="'+this.options.fullscreen+'" flashvars="height='+this.contentsHeight+'&width='+this.contentsWidth+'&file='+url+'$usefullscreen='+this.options.fullscreen+'"/>';
// Quicktime .MOV
		} else if (url.match(/\.mov/i)) {
			this.type = 'qt';
			if (this.options.controller=='true') {this.contentsHeight = (this.contentsHeight*1)+16};
			if (navigator.plugins && navigator.plugins.length) {
				this.object = '<object id="mediabox" standby="loading quicktime..." type="video/quicktime" codebase="http://www.apple.com/qtactivex/qtplugin.cab" data="'+url+'" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'"><param name="src" value="'+url+'" /><param name="scale" value="aspect" /><param name="controller" value="'+this.options.controller+'" /><param name="autoplay" value="'+this.options.autoplay+'" /><param name="bgcolor" value="'+this.options.bgcolor+'" /><param name="enablejavascript" value="true" /></object>';
			} else {
				this.object = '<object classid="clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B" standby="loading quicktime..." codebase="http://www.apple.com/qtactivex/qtplugin.cab" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" id="mediabox"><param name="src" value="'+url+'" /><param name="scale" value="aspect" /><param name="controller" value="'+this.options.controller+'" /><param name="autoplay" value="'+this.options.autoplay+'" /><param name="bgcolor" value="'+this.options.bgcolor+'" /><param name="enablejavascript" value="true" /></object>';
			}
// Windows Media .WMV
		} else if (url.match(/\.wmv/i)) {
			this.type = 'wmv';
			if (this.options.controller=='true') {this.contentsHeight = (this.contentsHeight*1)+16};
			if (navigator.plugins && navigator.plugins.length) {
				this.object = '<object id="mediabox" standby="loading windows media..." type="video/x-ms-wmv" data="'+url+'" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" /><param name="src" value="'+url+'" /><param name="autoStart" value="'+this.options.autoplay+'" /></object>';
			} else {
				this.object = '<object id="mediabox" standby="loading windows media..." classid="CLSID:22D6f312-B0F6-11D0-94AB-0080C74C7E95" type="video/x-ms-wmv" data="'+url+'" width="'+this.contentsWidth+'" height="'+this.contentsHeight+'" /><param name="filename" value="'+url+'" /><param name="showcontrols" value="'+this.options.controller+'"><param name="autoStart" value="'+this.options.autoplay+'" /><param name="stretchToFit" value="true" /></object>';
			}
// DIVX Media .AVI	// Courtesy of Jetstream Jetwave
//		} else if (url.match(/\.avi/i)) {
//			this.type = 'qt';
//			if (this.options.controller=='true') {this.contentsHeight = (this.contentsHeight*1)+16};
//			if (navigator.plugins && navigator.plugins.length) {
//				this.object = '<object codebase="http://go.divx.com/plugin/DivXBrowserPlugin.cab" height="'+this.contentsHeight+'" width="'+this.contentsWidth+'" classid="clsid:67DABFBF-D0AB-41fa-9C46-CC0F21721616"><param name="autoplay" value="false"><param name="src" value="'+url+'" /><param name="custommode" value="Stage6" /><param name="showpostplaybackad" value="false" /><embed type="video/divx" src="'+url+'" pluginspage="http://go.divx.com/plugin/download/" showpostplaybackad="false" custommode="Stage6" autoplay="false" height="'+this.contentsHeight+'" width="'+this.contentsWidth+'" /></object>';
//			}
// Inline element
		} else if (url.match(/\#yjmb_/i)) {
			this.type = 'element';
			var Id = url.split('#');
			this.element = Id[1];
			this.object = $(this.element).innerHTML;
// iFrame content
		} else {
			this.type = 'iframe';
			this.iframeId = "lbFrame_"+new Date().getTime();	// Safari would not update iframe content that had a static id.
			this.object = new Element('iframe').setProperties({id: this.iframeId, width: this.contentsWidth, height: this.contentsHeight, frameBorder:0, scrolling:'auto', src:url});
		}

		this.nextEffect();
		return false;
	},

	nextEffect: function(url){
		switch (this.step++){
		case 1:
			this.canvas.style.width = this.bottom.style.width = this.contentsWidth+'px';
			this.canvas.style.height = this.contentsHeight+'px';
			this.caption.innerHTML = this.title;

			if (this.center.clientHeight != this.canvas.offsetHeight){
				this.fx.center.start({height: this.canvas.offsetHeight, width: this.canvas.offsetWidth, marginLeft: -this.canvas.offsetWidth/2});
				break;
			} else if (this.center.clientWidth != this.canvas.offsetWidth){
				this.fx.center.start({height: this.canvas.offsetHeight, width: this.canvas.offsetWidth, marginLeft: -this.canvas.offsetWidth/2});
				break;
			}
			this.step++;

		case 2:
			this.bottomContainer.setStyles({top: (this.top + this.center.clientHeight)+'px', height:'0px', marginLeft: this.center.style.marginLeft, width:this.center.style.width, display: ''});
			this.fx.content.start(1);
			this.step++;

		case 3:
			if (this.type == 'flash'){
				this.object.write(this.canvas);
			} else if (this.type == 'iframe'){
				this.object.injectInside(this.canvas)
			} else {
				this.canvas.setHTML(this.object);
			}
			this.currentObject = document.getElementById('mediabox');
			this.center.className = '';
			break;
			this.step++;

		case 4:
			if (this.options.animateCaption){
				this.fx.bottom.start(0,this.bottom.offsetHeight);
				break;
			}
			this.bottomContainer.style.height = (this.bottom.offsetHeight)+'px';

		case 5:
			this.step = 0;
		}
	},

	close: function(){
			if (this.type == 'qt' && window.webkit) {
				this.currentObject.Stop();	// safari needs to call Stop() to remove the object's audio stream...
			}
			if (navigator.plugins && navigator.plugins.length) {
				this.canvas.setHTML('');
			} else {
				if (window.ie6) {
					this.canvas.innerHTML = '';
				} else {
					this.canvas.innerHTML = '';
				}
			}
			this.currentObject = null;
			this.currentObject = Class.empty;
			this.type = false;

		if (this.step < 0) return;
		this.step = -1;

		for (var f in this.fx) this.fx[f].stop();
		this.center.style.display = this.bottomContainer.style.display = 'none';
		this.fx.overlay.chain(this.setup.pass(false, this)).start(0);
		return false;
	}
};

window.addEvent('load', Mediabox.init.bind(Mediabox));

/**
 * SWFObject v1.5: Flash Player detection and embed - http://blog.deconcept.com/swfobject/
 *
 * SWFObject is (c) 2007 Geoff Stearns and is released under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 *
 */
if(typeof deconcept=="undefined"){var deconcept=new Object();}if(typeof deconcept.util=="undefined"){deconcept.util=new Object();}if(typeof deconcept.SWFObjectUtil=="undefined"){deconcept.SWFObjectUtil=new Object();}deconcept.SWFObject=function(_1,id,w,h,_5,c,_7,_8,_9,_a){if(!document.getElementById){return;}this.DETECT_KEY=_a?_a:"detectflash";this.skipDetect=deconcept.util.getRequestParameter(this.DETECT_KEY);this.params=new Object();this.variables=new Object();this.attributes=new Array();if(_1){this.setAttribute("swf",_1);}if(id){this.setAttribute("id",id);}if(w){this.setAttribute("width",w);}if(h){this.setAttribute("height",h);}if(_5){this.setAttribute("version",new deconcept.PlayerVersion(_5.toString().split(".")));}this.installedVer=deconcept.SWFObjectUtil.getPlayerVersion();if(!window.opera&&document.all&&this.installedVer.major>7){deconcept.SWFObject.doPrepUnload=true;}if(c){this.addParam("bgcolor",c);}var q=_7?_7:"high";this.addParam("quality",q);this.setAttribute("useExpressInstall",false);this.setAttribute("doExpressInstall",false);var _c=(_8)?_8:window.location;this.setAttribute("xiRedirectUrl",_c);this.setAttribute("redirectUrl","");if(_9){this.setAttribute("redirectUrl",_9);}};deconcept.SWFObject.prototype={useExpressInstall:function(_d){this.xiSWFPath=!_d?"expressinstall.swf":_d;this.setAttribute("useExpressInstall",true);},setAttribute:function(_e,_f){this.attributes[_e]=_f;},getAttribute:function(_10){return this.attributes[_10];},addParam:function(_11,_12){this.params[_11]=_12;},getParams:function(){return this.params;},addVariable:function(_13,_14){this.variables[_13]=_14;},getVariable:function(_15){return this.variables[_15];},getVariables:function(){return this.variables;},getVariablePairs:function(){var _16=new Array();var key;var _18=this.getVariables();for(key in _18){_16[_16.length]=key+"="+_18[key];}return _16;},getSWFHTML:function(){var _19="";if(navigator.plugins&&navigator.mimeTypes&&navigator.mimeTypes.length){if(this.getAttribute("doExpressInstall")){this.addVariable("MMplayerType","PlugIn");this.setAttribute("swf",this.xiSWFPath);}_19="<embed type=\"application/x-shockwave-flash\" src=\""+this.getAttribute("swf")+"\" width=\""+this.getAttribute("width")+"\" height=\""+this.getAttribute("height")+"\" ";_19+=" id=\""+this.getAttribute("id")+"\" name=\""+this.getAttribute("id")+"\" ";var _1a=this.getParams();for(var key in _1a){_19+=[key]+"=\""+_1a[key]+"\" ";}var _1c=this.getVariablePairs().join("&");if(_1c.length>0){_19+="flashvars=\""+_1c+"\"";}_19+="/>";}else{if(this.getAttribute("doExpressInstall")){this.addVariable("MMplayerType","ActiveX");this.setAttribute("swf",this.xiSWFPath);}_19="<object id=\""+this.getAttribute("id")+"\" classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" width=\""+this.getAttribute("width")+"\" height=\""+this.getAttribute("height")+"\" >";_19+="<param name=\"movie\" value=\""+this.getAttribute("swf")+"\" />";var _1d=this.getParams();for(var key in _1d){_19+="<param name=\""+key+"\" value=\""+_1d[key]+"\" />";}var _1f=this.getVariablePairs().join("&");if(_1f.length>0){_19+="<param name=\"flashvars\" value=\""+_1f+"\" />";}_19+="</object>";}return _19;},write:function(_20){if(this.getAttribute("useExpressInstall")){var _21=new deconcept.PlayerVersion([6,0,65]);if(this.installedVer.versionIsValid(_21)&&!this.installedVer.versionIsValid(this.getAttribute("version"))){this.setAttribute("doExpressInstall",true);this.addVariable("MMredirectURL",escape(this.getAttribute("xiRedirectUrl")));document.title=document.title.slice(0,47)+" - Flash Player Installation";this.addVariable("MMdoctitle",document.title);}}if(this.skipDetect||this.getAttribute("doExpressInstall")||this.installedVer.versionIsValid(this.getAttribute("version"))){var n=(typeof _20=="string")?document.getElementById(_20):_20;n.innerHTML=this.getSWFHTML();return true;}else{if(this.getAttribute("redirectUrl")!=""){document.location.replace(this.getAttribute("redirectUrl"));}}return false;}};deconcept.SWFObjectUtil.getPlayerVersion=function(){var _23=new deconcept.PlayerVersion([0,0,0]);if(navigator.plugins&&navigator.mimeTypes.length){var x=navigator.plugins["Shockwave Flash"];if(x&&x.description){_23=new deconcept.PlayerVersion(x.description.replace(/([a-zA-Z]|\s)+/,"").replace(/(\s+r|\s+b[0-9]+)/,".").split("."));}}else{if(navigator.userAgent&&navigator.userAgent.indexOf("Windows CE")>=0){var axo=1;var _26=3;while(axo){try{_26++;axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash."+_26);_23=new deconcept.PlayerVersion([_26,0,0]);}catch(e){axo=null;}}}else{try{var axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.7");}catch(e){try{var axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash.6");_23=new deconcept.PlayerVersion([6,0,21]);axo.AllowScriptAccess="always";}catch(e){if(_23.major==6){return _23;}}try{axo=new ActiveXObject("ShockwaveFlash.ShockwaveFlash");}catch(e){}}if(axo!=null){_23=new deconcept.PlayerVersion(axo.GetVariable("$version").split(" ")[1].split(","));}}}return _23;};deconcept.PlayerVersion=function(_29){this.major=_29[0]!=null?parseInt(_29[0]):0;this.minor=_29[1]!=null?parseInt(_29[1]):0;this.rev=_29[2]!=null?parseInt(_29[2]):0;};deconcept.PlayerVersion.prototype.versionIsValid=function(fv){if(this.major<fv.major){return false;}if(this.major>fv.major){return true;}if(this.minor<fv.minor){return false;}if(this.minor>fv.minor){return true;}if(this.rev<fv.rev){return false;}return true;};deconcept.util={getRequestParameter:function(_2b){var q=document.location.search||document.location.hash;if(_2b==null){return q;}if(q){var _2d=q.substring(1).split("&");for(var i=0;i<_2d.length;i++){if(_2d[i].substring(0,_2d[i].indexOf("="))==_2b){return _2d[i].substring((_2d[i].indexOf("=")+1));}}}return "";}};deconcept.SWFObjectUtil.cleanupSWFs=function(){var _2f=document.getElementsByTagName("OBJECT");for(var i=_2f.length-1;i>=0;i--){_2f[i].style.display="none";for(var x in _2f[i]){if(typeof _2f[i][x]=="function"){_2f[i][x]=function(){};}}}};if(deconcept.SWFObject.doPrepUnload){if(!deconcept.unloadSet){deconcept.SWFObjectUtil.prepUnload=function(){__flash_unloadHandler=function(){};__flash_savedUnloadHandler=function(){};window.attachEvent("onunload",deconcept.SWFObjectUtil.cleanupSWFs);};window.attachEvent("onbeforeunload",deconcept.SWFObjectUtil.prepUnload);deconcept.unloadSet=true;}}if(!document.getElementById&&document.all){document.getElementById=function(id){return document.all[id];};}var getQueryParamValue=deconcept.util.getRequestParameter;var FlashObject=deconcept.SWFObject;var SWFObject=deconcept.SWFObject;