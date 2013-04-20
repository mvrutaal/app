/**
 * YJSlide - image slider
 * @version		3.0
 * @MooTools version 1.3
 * @author	Constantin Boiangiu <info [at] constantinb.com>
 */

var YJSlide = new Class({	
	Implements: [Options],
	options: {
			outerContainer : null, /* outer items container */
			innerContainer : null, /* inner items container */
			elements: null, /* css class for slides */ 
			navigation: {
				'forward':null, /* forward link id */ 
				'back':null /* backward link id */
			},
			slideType: 0,  //0 : fade; 1 : scroll; 2 : scrollfade
			orientation: 1, //  0 : vertical; 1 : horizontal
			slideTime: 3000,
			duration: 600,
			tooltips: 1, // 0: display tips; 1: don't display tips
			autoslide: 1,
			navInfo: null,
			navLinks: null
		},
	
	
	initialize: function(options){
		this.setOptions(options);
		this.elements = $$(this.options.elements);
		this.start();	
	},	
	start: function(){
		this.currentElement = 0;	
		this.direction = 1; // -1: back; 1:forward
		this.elements = $(this.options.innerContainer).getElements(this.options.elements);
		
		this.showEffect = {};
		this.hideEffect = {};
		
		if( this.options.slideType!==0 ){
			if( this.options.orientation == 1 ){
				this.showEffect.left = [1200,0];
				this.hideEffect.left = [0,1200];
			}else{
				this.showEffect.top = [400,0];
				this.hideEffect.top = [0,400];
			}
		}
		if( this.options.slideType!==1 ){
			this.showEffect.opacity = [0,1];
			this.hideEffect.opacity = [1,0];
		}
		
		
		/* slides */
		this.elements.each( function(el, i){			
			
			el.setStyles({
				'display':'block',
				'position':'absolute',
				'top':0,
				'left':0,
				'z-index':(100-i)
			});	
			
			if( this.options.slideType!==1 && i!==this.currentElement  )
				el.setStyle('opacity',0);
			
			this.elements[i]['fx'] = new Fx.Morph(el, {wait:false, duration: this.options.duration});
			
			if(i!==this.currentElement)
				this.elements[i]['fx'].start(this.hideEffect);
						
			el.addEvent('mouseover', function(event){
				if($defined(this.period)){
					$clear(this.period);					
				}	
			}.bind(this));
			el.addEvent('mouseout', function(event){
				if(this.options.autoslide==0){
					this.period = this.rotateSlides.periodical(this.options.slideTime, this);
				}
			}.bind(this));
			
		}.bind(this));
		
		/* add tooltips if set */
		if(!this.options.tooltips){
			new Tips($(this.options.innerContainer).getElements('.YJS_link'),{
				className: 'YJS_tips'
			});
		}
		/* autoslide on command */
		if(!this.options.autoslide){
			this.period = this.rotateSlides.periodical(this.options.slideTime, this);
		}
		/* add navigation */
		this.setNavigation();
		
		if(this.options.navLinks)
			this.secondNavigation();
	},
	
	rotateSlides: function(){
		var next = this.currentElement+this.direction;
		if( next < 0 ) next = this.elements.length-1;
		if( next >  this.elements.length-1) next = 0;
		this.nextSlide(next);	
	},
	
	nextSlide: function(slide){
		if(slide==this.currentElement) return;
		this.elements[this.currentElement]['fx'].start(this.hideEffect);
		this.elements[slide]['fx'].start(this.showEffect);
		this.currentElement = slide;
		
		if($(this.options.navInfo)){
			$(this.options.navInfo).set('html','Link '+(slide+1)+' of '+this.elements.length);
		}
		
	},
	
	setNavigation: function(){
		if(!$(this.options.navigation.forward)) return;
		
		$(this.options.navigation.forward).addEvent('click', function(event){
			new Event(event).stop();
			this.direction = 1;
			this.resetAutoslide();			
			this.rotateSlides();
		}.bind(this));
		
		$(this.options.navigation.back).addEvent('click', function(event){
			new Event(event).stop();
			this.direction = -1;
			this.resetAutoslide();
			this.rotateSlides();
		}.bind(this));
		
	},
	
	resetAutoslide: function(){
		if($defined(this.period)){
			$clear(this.period);
			this.period = this.rotateSlides.periodical(this.options.slideTime, this);
		}
	},
	
	secondNavigation: function(){
		this.navElements = $$(this.options.navLinks);
		this.navElements.each(function(el,i){
			
			el.addEvent('click', function(event){
				new Event(event).stop();
				this.resetAutoslide();
				this.nextSlide(i);				
			}.bind(this));
			
		}.bind(this));
		
		if( !this.options.tooltips ){
			new Tips(this.navElements,{
				className: 'YJS_tips'
			});
		}
		
	}
	
	

});


//when the dom is ready
window.addEvent('domready', function() {
  //store titles and text
  $$('.YJS_link').each(function(element,index) {
    var content = element.get('title').split('::');
    element.store('tip:title', content[0]);
    element.store('tip:text', content[1]);
  });
  
});