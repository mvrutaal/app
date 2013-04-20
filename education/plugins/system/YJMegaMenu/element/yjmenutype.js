// JavaScript Document
var YP = {
	start: function(){
		
		YP.table = $('paramsyj_group_holder1').getParent().getParent().getParent().getParent();
		YP.rows = YP.table.getElements('tr');		
		YP.accDiv = YP.table.getParent();
		
		var f = function(){
			var s = YP.table.getSize();
			YP.accDiv.setStyles({'height':s['size'].y});
		}
		f.delay(500, YP);
		
		
		YP.holders();
		YP.normalLink();
		YP.module();
		YP.modulePos();
	},
	
	holders: function(){
		var rows = [3,4,5];
		
		if( $('paramsyj_group_holder1').checked ) YP.showRows( rows, true );
		if( $('paramsyj_group_holder0').checked ) YP.showRows( rows, false );
		
		$('paramsyj_group_holder1').addEvent('click', function(){			
			if( this.checked ){
				YP.showRows( rows, true );
			}			
		})
		$('paramsyj_group_holder0').addEvent('click', function(){			
			if( this.checked ){
				YP.showRows( rows, false );
			}			
		})
		
	},
	
	normalLink: function(){
		var hideRows = [7, 8, 9];
		
		var el = $('params[yj_item_type]0');
		if( el.checked )
			YP.showRows( hideRows, false );
		
		el.addEvent('click', function(){
			if( this.checked ){
				YP.showRows( hideRows, false );
			}
		})
		
	},
	
	module: function(){
		var hideRows = [9];
		var showRows = [7,8];
		
		var el = $('params[yj_item_type]1');
		if( el.checked ){
			YP.showRows( hideRows, false );
			YP.showRows( showRows, true );			
		}
		el.addEvent('click', function(){
			if( this.checked ){
				YP.showRows( hideRows, false );
				YP.showRows( showRows, true );
			}
		})
		
	},
	
	modulePos: function(){
		var hideRows = [8];
		var showRows = [7,9];
		
		var el = $('params[yj_item_type]2');
		if( el.checked ){
			YP.showRows( hideRows, false );
			YP.showRows( showRows, true );
		}
		
		el.addEvent('click', function(){
			if( this.checked ){
				YP.showRows( hideRows, false );
				YP.showRows( showRows, true );
			}
		})
		
	},
	
	showRows: function( rows, status ){
		rows.each( function( el ){
			YP.rows[el].setStyle('display', status ? '':'none');			
		})	
		
		var s = YP.table.getSize();
		YP.accDiv.setStyles({'height':s['size'].y});		
	}
	
}

window.addEvent('load', YP.start);