jQuery(document).ready(function() {
//Add section
	jQuery( '.fields-icon' ).on('click', '.add-section:not(.unclickable)', function(e) {
		e.preventDefault();		
		var sort_active = jQuery( this ).closest( 'form' ).find('.thead');		
		var active_section = jQuery( '.thunder-add-section' ).find('.section').clone();
		sort_active.prepend( active_section );	
	});
//open/close all fields trigger
	jQuery( '.fields-icon' ).on('click', '.close-tab', function(e) {
		e.preventDefault();
		var close = jQuery(this).parents( '.th-admin-model' );
		close.find('.admin-field-menu').removeClass('close').addClass('open');
		close.find('.admin-field-body').show();		
		jQuery(this).removeClass('close-tab').addClass('open-tab');
	});
	jQuery( '.fields-icon' ).on('click', '.open-tab', function(e) {
		e.preventDefault();
		var open = jQuery(this).parents( '.th-admin-model' );
		open.find('.admin-field-menu').removeClass('open').addClass('close');
		open.find('.admin-field-body').hide();		
		jQuery(this).removeClass('open-tab').addClass('close-tab');		
	});
//Customize fields option open/close
	jQuery( 'ul' ).on('click', '.switch-field', function(e) {
		e.preventDefault();
		var close = jQuery(this);
		if ( close.is( '.on') ) {
			close.removeClass('on').next().hide();						
		} else {
			close.addClass( 'on' ).next().show();						
		}		
	});	
//Delete section
	jQuery( 'ul' ).on( 'click', '.section-remove', function(e) {
		e.preventDefault();
		if (confirm('This item will be deleted')) {
			jQuery( this ).parent().remove();
		}
		return false;		
	});
//Delete field
	jQuery( document ).on( 'click', '.field-remove', function(e) {
		e.preventDefault();
		var item = jQuery( this );
		var form = jQuery(this).parents('.th-admin-model').find('form');
		if (confirm('This item will be deleted')) {				
			item.parent().remove();	
			form.trigger('save');
		}
		return false;
	});	
//Trigger for show/hide popup fields
	jQuery( document ).on('init:active', '.thunder_sort_main', function(e) {
		console.log('ok');
		var sort_active = jQuery( this );
		if ( sort_active.is( '.active' ) ) {
			jQuery( '.hoveradded' ).removeClass( 'hoveradded' );//close field chack
			jQuery( '.add-thumb' ).hide();
			jQuery( '.back-thumb' ).hide();	
			sort_active.find( '.admin-option-field-zone' ).hide();
			jQuery( '.selected' ).removeClass( 'selected' ).siblings( 'li' ).fadeIn( 300 ).show();
			sort_active.removeClass( 'active' );
			jQuery( '.thunder_sort_main' ).hide();
		} else {
			sort_active.addClass( 'active' );
			jQuery( '.thunder_sort_main' ).show();
		}
	});
//Choose field in popup
	jQuery( document ).on('init:selected', '.thunder_sort li', function(e) {
		var field = jQuery( this );
		if ( field.is( '.selected' ) ) {
			field.removeClass( 'selected' ).siblings( 'li' ).fadeIn( 300 ).show();
			jQuery( '.add-thumb' ).hide();
			jQuery( '.back-thumb' ).hide();	
			field.find( '.admin-option-field-zone' ).hide();
		} else {
			field.addClass( 'selected' ).siblings( 'li' ).fadeOut( 300 ).hide();			
			jQuery( '.add-thumb' ).show();
			jQuery( '.back-thumb' ).show();	
			field.find( '.admin-option-field-zone' ).show();
		}
	});
//Add field trigger
	/*jQuery( '.add-field' ).on('click', function(e) {
		e.preventDefault();	
		///var form = jQuery(this).parents('.th-admin-model').find('form');
		//form.trigger('save');	
		jQuery( '.thunder_sort_main' ).trigger( 'init:active' );		
		jQuery( this ).closest( '.admin-field-menu' ).next().find( '.thead' ).addClass( 'hoveradded' );	
	});*/
	jQuery( '.fields-icon' ).on('click', '.add-field:not(.unclickable)', function(e) {
		e.preventDefault();	
		jQuery(this).closest('.admin-field-menu').append('<div class="load-spinner rotate-circle"></div>');
		var form = jQuery(this).parents('.th-admin-model').find('form');
		var tpl = form.data('name');
		var flag = true;
		jQuery('.add-section').addClass('unclickable');
		jQuery('.add-field').addClass('unclickable');
		jQuery('.preview').addClass('unclickable');
		jQuery('.resetgroup').addClass('unclickable');
		jQuery('.saveform').addClass('unclickable');
			
		jQuery.ajax({
			url: ajaxurl,
			data: '&action=thunder_admin_fields&form='+tpl,
			dataType: 'JSON',
			type: 'POST',			
			success:function(data) {
					console.log(data);
					var field = jQuery('.thunder_sort_main');
					if (field.length > 0) {
						field.remove();
					}
					form.find('.load-spinner').remove();
					jQuery('.thunder-admin-contain').append(data.response)	
					//jQuery( '.form-table' ).find('.load-spinner').fadeOut(200);
						//jQuery( '.form-table' ).append(data).fadeIn(1000);
						//console.log(field);
						//console.log(form);
					//console.log(data);	
						jQuery('.thunder_sort_main').trigger( 'init:active' );		
						form.find( '.admin-field-menu' ).next().find( '.thead' ).addClass( 'hoveradded' );
						jQuery('.unclickable').removeClass('unclickable');							
			}
				
		});			
		
	});	
	
//close popup
	jQuery( document ).on('click', '.list_close', function(e) {
		/*var form = jQuery(this).parents('.th-admin-model').find('form');
		form.trigger('save');*/		
		jQuery( this ).trigger( 'init:active' );				
	});

//choose field
	jQuery( document ).on('click', '.thunder_sort li', function(e) {	
		//jQuery( this ).trigger( 'init:selected' );
		jQuery( this ).addClass( 'selected' ).siblings( 'li' ).fadeOut( 300 ).hide();			
		jQuery( '.add-thumb' ).show();
		jQuery( '.back-thumb' ).show();	
		jQuery( this ).find( '.admin-option-field-zone' ).show();					
	});	

//Return back
	jQuery( document ).on( 'click', '.back-thumb', function(e) {		
		jQuery( '.selected' ).trigger( 'init:selected' );
	});


//Add field via popup 	НУЖНО СДЕСЬ СДЕЛАТЬ СЕЙВ
	jQuery( document ).on( 'click', '.thunder_sort_main .add-thumb', function(e) {
		var tpl = jQuery('.thunder_sort_main').data('tpl');
		var form = jQuery('.th-admin-model-'+tpl).find('form');
		var li_clone = jQuery( '.selected' ).clone();
		li_clone.find( '.admin-option-field-zone' ).hide();	
		/*var rand = Math.floor(Math.random() * (100 - 1)) + 1;
		li_clone.find('input, select, textarea').attr('name', function(i,val){
                	return val.replace(/-/i, 'head-'+rand);
                });*/			
		jQuery( '.hoveradded' ).prepend( li_clone );		
		jQuery( '.selected' ).trigger( 'init:selected' );		
		jQuery( '.thunder_sort_main' ).trigger( 'init:active' );		
		form.trigger('save');		
	});


//Drag&drop all fields	'head','content','bottom'	
    jQuery( '.thead, .tcontent, .tbottom' ).sortable({
        connectWith: '.thead, .tcontent, .tbottom',
    	revert: 200,
        placeholder: "ui-state-highlight",  
        stop: function (event, ui) {
            var zone = ui.item.closest( 'ul' ).data( 'zone' ); 
            ui.item.find( '.zone' ).val(zone);              
            ui.item.find( 'input, select, textarea' ).attr( 'name', function(i,val){ 
            	return val.replace(/(^[a-z]+)-/i, zone+'-');
            });               
        }
    }).disableSelection();

	
//Display fields after page loading
	jQuery( '.thunder-admin-body .rowselect' ).find('option:selected').each(function() {		
	    if (this.value == 'samerow') {    			
			jQuery(this).closest('li').css({ 'background': '#cfcd92' });	
		} /*else {    			
			jQuery(this).closest('li').css({ 'width': '', 'display': '' });
		}*/
	})
//Changing these display fields	
    jQuery( '.admin-field-body' ).on( 'change', '.rowselect', function() {
    	var choose = jQuery(this).closest('li');
    	jQuery(this).find('option:selected').each(function() {
    		if (this.value == 'samerow') {    			
    			jQuery(this).closest('li').css({ 'background': '#cfcd92' });	
    		} else {    			
    			jQuery(this).closest('li').css({ 'background': '#a5a5a5' });
    		}
    	});
    });


//////////////////
/////		/////
/////	   ////
////      ///
////////////
////	/////
////	 /////
////	  /////

///////////////////////////
///////////////////////////
//Global Options
/////////////////////////
jQuery( '#admin_page_after_login' ).find('option:selected').each(function() {		
	    if (this.value == '1') {    			
			jQuery('.after_login').hide();	
		} 
		return;
	})
jQuery( '#admin_page_after_login' ).on( 'change', function() {
	jQuery( '.after_login' ).toggle();
});








///////////////
////////////
////////////////
///
//
//
//  REQUST APPROVE USERS ДЛЯ    БЕЙДЖЕВ

	// Verify user 
	/*jQuery('.upadmin-verify').on('click',function(e){
		e.preventDefault();
		var link = jQuery(this);
		var parent = jQuery(this).parents('.upadmin-pending-verify');
		jQuery.ajax({
			url: ajaxurl,
			data: 'action=thunder_verify_user&user_id=' + jQuery(this).data('user'),
			dataType: 'JSON',
			type: 'POST',
			success:function(data){
				parent.fadeOut();
				if (data.count === '0' || data.count == '' || !data.count) {
					jQuery('.upadmin-bubble-new').remove();
				} else {
					jQuery('.upadmin-bubble-new').html( data.count );
				}
				jQuery('.toplevel_page_userpro').find('span.update-count').html( data.count );
			}
		});
		return false;
	});
	
	/// Unverify user 
	jQuery('.upadmin-unverify').on('click',function(e){
		e.preventDefault();
		var link = jQuery(this);
		var parent = jQuery(this).parents('.upadmin-pending-verify');
		jQuery.ajax({
			url: ajaxurl,
			data: 'action=thunder_unverify_user&user_id=' + jQuery(this).data('user'),
			dataType: 'JSON',
			type: 'POST',
			success:function(data){
				parent.fadeOut();
				if (data.count === '0' || data.count == '' || !data.count) {
					jQuery('.upadmin-bubble-new').remove();
				} else {
					jQuery('.upadmin-bubble-new').html( data.count );
				}
				jQuery('.toplevel_page_userpro').find('span.update-count').html( data.count );
			}
		});
		return false;
	});*/




//////////
/////////
///////////////
////////////////////////
///////////////				ADMIN REQUEST
/////////
/////////////////
////////////////////
/////////////////////
///////////////////
////////////////////////
	/* deny user registration */
	jQuery('.upadmin-user-deny').on('click',function(e){
		e.preventDefault();
		var link = jQuery(this);
		var parent = jQuery(this).parents('.upadmin-pending-verify');
		jQuery.ajax({
			url: ajaxurl,
			data: 'action=thunder_admin_user_deny&user_id=' + jQuery(this).data('user'),
			dataType: 'JSON',
			type: 'POST',
			success:function(data){
				parent.fadeOut();
				if (data.count === '0' || data.count == '' || !data.count) {
					jQuery('.pending-count').remove();
				} else {
					jQuery('.pending-count').html( data.count );
				}
				jQuery('.toplevel_page_thunder').find('span.pending-count').html( data.count );
			}
		});
		return false;
	});
	
	/* approve user registration */
	jQuery('.upadmin-user-approve').on('click',function(e){
		e.preventDefault();
		var link = jQuery(this);
		var parent = jQuery(this).parents('.upadmin-pending-verify');
		jQuery.ajax({
			url: ajaxurl,
			data: 'action=thunder_admin_user_approve&user_id=' + jQuery(this).data('user'),
			dataType: 'JSON',
			type: 'POST',
			success:function(data){
				parent.fadeOut();
				if (data.count === '0' || data.count == '' || !data.count) {
					jQuery('.pending-count').remove();
				} else {
					jQuery('.pending-count').html( data.count );
				}
				jQuery('.toplevel_page_thunder').find('span.pending-count').html( data.count );
			}
		});
		return false;
	});










/*
//
//
 @xxxxxxxxxxxxxx STYLING
//
//
//
*/

	
//Trigger for show/hide popup fields
	jQuery( '.thunder_sort_style' ).on('init:active', function() {
		var sort_active = jQuery( this );
		if ( sort_active.is( '.active' ) ) {			
			sort_active.removeClass( 'active' );
			jQuery( '.thunder_sort_style' ).hide();
		} else {
			sort_active.addClass( 'active' );
			jQuery( '.thunder_sort_style' ).show();
		}
	});

//close popup
	jQuery( '.thunder_sort_style_body' ).on('click', '.list_close_style', function() {		
		jQuery( this ).trigger( 'init:active' );			
	});

//Form admin preview
	jQuery( '.preview:not(.unclickable)' ).on( 'click', function() {
		jQuery( this ).trigger( 'init:preview' );
	});

	jQuery( document ).on( 'init:preview', '.preview:not(.unclickable)', function(e) {		
		jQuery('.preview').addClass('unclickable');
		var form = jQuery(this).closest('form');
		var name = form.data('name');
		var templ = form.data('templ');
		form.parents().find('.thunder-admin-head').children().remove();
		jQuery('#thunder-css').remove(); 

		jQuery('.thunder-admin-head').prepend('<div class="load-spinner rotate-circle"></div>');
		jQuery('html, body').animate({
			scrollTop: jQuery('.nav-tab-wrapper').offset().top
		}, 500);

		jQuery.ajax({
			url: ajaxurl,
			data: 'action=thunder_preview_group&name='+name+'&templ='+templ,
			dataType: 'JSON',
			type: 'POST',
			success:function(data){						
				jQuery('.thunder-admin-head').find('.load-spinner').remove().fadeOut(200);						
				form.parents().find('.thunder-admin-head').prepend(data.body).fadeIn(400);
				form.parents().find('.thunder-admin-head').prepend(data.css).fadeIn(400);
				console.log(data.googlestyle);
				if (data.googlestyle != '' ) {
					console.log(jQuery('head'));
					if (jQuery('#thunder-style-googlefont').length >0) {
						jQuery('#thunder-style-googlefont').remove();
						jQuery('head').append(data.googlestyle);
					} else {
						jQuery('head').append(data.googlestyle);
					}
				}				
				jQuery('.preview').removeClass('unclickable');
			}
		});
		return false;				
	});

//Mouse on preview form
	jQuery('.thunder-admin-head').on('mouseover', '.hoverview', function (e) {
		var currentElem = null;

		var ter = e.target;
		console.log(this);
		console.log(ter);
				
		currentElem = jQuery(this).closest( '.hoverview' );; 
		jQuery( currentElem ).css( 'border', '1px solid #000' );
  
	});
	jQuery('.thunder-admin-head').on('mouseout', '.hoverview', function (e) {
		var currentElem = null;
		var ter = e.target;	
		currentElem = jQuery(this).closest( '.hoverview' );; 
		jQuery( currentElem ).css( 'border', '' );		
	});


//Background style
	jQuery('.thunder-admin-head').on('click', '.puzzle', function (e) {
		jQuery( '.thunder_sort_style' ).trigger( 'init:active' );
		var name = jQuery( this ).closest('.thunder-admin-head').find('form').data('name');
		var templ = jQuery( this ).closest('.thunder-admin-head').find('form').data('templ');
		var zone = 'thunder-body'; 
		console.log(name);
		console.log(templ);
		console.log(zone);
		console.log('style_preview');

		jQuery( '.thunder_sort_style_body' ).find( '.form-table' ).remove();
		jQuery( '.load-spinner' ).fadeIn(200);
		jQuery.ajax({
			url: ajaxurl,
			data: 'action=thunder_style_preview_group&name='+name+'&templ='+templ+'&zone='+zone,
			dataType: 'JSON',
			type: 'POST',
			success:function(data){	
			//console.log(data
				jQuery( '.thunder_sort_style_body' ).find('.load-spinner').hide(200);				
				jQuery( '.thunder_sort_style_body' ).append(data).fadeIn(1000);

				jQuery( '.switch-css-checkbox ').each(function() {
					if (jQuery( this ).is( ':checked' )) {												
						var check = jQuery( this ).closest( 'table' ).find( '.hidem-toogle' );	
						jQuery( this ).closest( 'tr' ).find( '.style-off' ).removeClass('style-off'); //remove 'style-off'
						//console.log(check.find( '.css-mark' ));
						if ( check.find( '.css-mark' ).length == 0 ) {
							check.append( '<a href="#" class="css-mark"></a>' );
						}						
					}				
					
				});

				jQuery('.border-number-check').each(function() { 
						//console.log('check');
						var item = jQuery(this);
						//var elem = jQuery( '.'+item );
						if ( item.val() == 'none' ) {
						//console.log('none');					
							item.closest( 'td' ).prev().addClass('style-off').find( 'input' ).prop( "disabled", true );
							item.closest( 'td' ).next().addClass('style-off').find( '.thunder-color-picker' ).prop( "disabled", true );					
						} else { 
							//console.log('none-show');
							item.closest( 'td' ).prev().next().prop( "disabled", false );
							item.closest( 'td' ).next().next().prop( "disabled", false );
						}
							
					});
				
				jQuery( '.thunder-color-picker' ).wpColorPicker();

				jQuery('.font-text-style').fontselect().change(function(){
			        var font = jQuery(this).val().replace(/\+/g, ' ');
			        font = font.split(':');
			        jQuery(this).val(font[0]);
				});	

									
			}
		});
		return false;	
	});























//Add background img
 var formfield = '';
	jQuery( document ).on('click', '.img-back', function() {
        formfield = jQuery(this).prev('input'); //The input field that will hold the uploaded file url		
        tb_show('','media-upload.php?TB_iframe=true'); 
        return false;
    });  
    //adding my custom function with Thick box close function tb_close() .
    jQuery( document ).old_tb_remove = jQuery( document ).tb_remove;
    jQuery( document ).tb_remove = function() {
            jQuery( document ).old_tb_remove(); // calls the tb_remove() of the Thickbox plugin
            formfield=null;
        };
    // user inserts file into post. only run custom if user started process using the above process
    // window.send_to_editor(html) is how wp would normally handle the received data 
    window.original_send_to_editor = window.send_to_editor;
    window.send_to_editor = function(html){
        if (formfield) {
            fileurl = jQuery('img',html).attr('src');
            jQuery(formfield).val(fileurl);
            tb_remove();
        } else {
            window.original_send_to_editor(html);
        }
    };




	jQuery('.thunder-admin-head').on('click', '.hoverview', function (e) {
		jQuery( '.thunder_sort_style' ).trigger( 'init:active' );

		//var target = e && e.target || window.event.srcElement;
		//console.log(target);
		//var form = jQuery(this).closest('form');
		var name = jQuery( this ).closest('.thunder-admin-head').find('form').data('name');
		var templ = jQuery( this ).closest('.thunder-admin-head').find('form').data('templ');
		var zone = jQuery( this ).data('zone'); //thead
		//var templ = form.data('templ');
		console.log(name);
		console.log(templ);
		console.log(zone);
		console.log('style_preview');


		jQuery( '.thunder_sort_style_body' ).find( '.form-table' ).remove();
		jQuery( '.load-spinner' ).fadeIn(200);

		jQuery.ajax({
			url: ajaxurl,
			data: 'action=thunder_style_preview_group&name='+name+'&templ='+templ+'&zone='+zone,
			dataType: 'JSON',
			type: 'POST',
			success:function(data){	
			//console.log(data);			
				jQuery( '.thunder_sort_style_body' ).append(data).fadeIn(1000);
				jQuery( '.thunder_sort_style_body' ).find('.load-spinner').hide(200);
				jQuery( '.thunder-color-picker' ).wpColorPicker();

				jQuery( '.switch-css-checkbox ').each(function() {
					if (jQuery( this ).is( ':checked' )) {												
						var check = jQuery( this ).closest( 'table' ).find( '.hidem-toogle' );	
						jQuery( this ).closest( 'tr' ).find( '.style-off' ).removeClass('style-off'); //remove 'style-off'
						//console.log(check.find( '.css-mark' ));
						if ( check.find( '.css-mark' ).length == 0 ) {
							check.append( '<a href="#" class="css-mark"></a>' );
						}						
					}				
					
				});

				jQuery('.border-number-check').each(function() {  
						//console.log('check');
						var item = jQuery(this);
						//var elem = jQuery( '.'+item );
						if ( item.val() == 'none' ) {
						//console.log('none');					
							item.closest( 'td' ).prev().addClass('style-off').find( 'input' ).prop( "disabled", true );
							item.closest( 'td' ).next().addClass('style-off').find( '.thunder-color-picker' ).prop( "disabled", true );					
						} else { 
							//console.log('none-show');
							item.closest( 'td' ).prev().next().prop( "disabled", false );
							item.closest( 'td' ).next().next().prop( "disabled", false );
						}
							
					});


				jQuery('.font-text-style').fontselect().change(function(){
			        var font = jQuery(this).val().replace(/\+/g, ' ');
			        font = font.split(':');
			        jQuery(this).val(font[0]);
				});


			}
		});
		return false;	
	});


	jQuery( document ).on( 'click', '.switch-css-checkbox', function() {
		var target = jQuery(this).data( 'trigger' );
		var elem = jQuery( '.'+target );
		var none = elem.closest( 'tr' ).find( '.border-number-check' );
		var styleoff = elem.prev().hasClass( 'style-off' );
		if (elem ) {
			if (jQuery( this ).is( ':checked' )) {
				jQuery(elem).prop( "disabled", false );//enable
				jQuery(elem).closest('td').removeClass('style-off');
				if ( none.val() == 'none' ) {
					jQuery(elem).prop( "disabled", true );//disable
					none.prop( "disabled", false );//enable
					none.closest('td').prev().addClass('style-off');
					none.closest('td').next().addClass('style-off');
				}
				jQuery(elem).closest('.second-off').show();				
			} else {
				jQuery(elem).prop( "disabled", true );
				jQuery(elem).closest('td').addClass('style-off');
				if ( none.val() == 'none' ) {					
					none.prop( "disabled", true );//enable					
				}				
				jQuery(elem).closest('.second-off').hide();	
			}
		};
	});

//for border none switch in popup style window
	jQuery( document ).on( 'change', '.border-number-check', function(e) {
		var item = jQuery(this);
		//var elem = jQuery( '.'+item );
		//console.log(item);
		//console.log(item.closest('td').prev().find('input'));
		if ( item.val() == 'none' ) {
		//console.log('e');					
			item.closest('td').prev().addClass('style-off').find('input').prop( "disabled", true ); //disabled
			item.closest('td').next().addClass('style-off').find('.thunder-color-picker').prop( "disabled", true );					
		} else { 
			//console.log('q');
			item.closest('td').prev().removeClass('style-off').find('input').prop( "disabled", false );
			item.closest('td').next().removeClass('style-off').find('.thunder-color-picker').prop( "disabled", false );
		}
			
	});

	//jQuery('.style-off').click(false);
	/*jQuery( '.form-table' ).on( 'click', 'tr', function() {
		jQuery('a').click(false);
		console.log(1);
	})*/
	
		
	
	//jQuery('td').prop('onclick',null).off('click');
		

/*	jQuery( '.form-table' ).on( 'click', 'td', function() {
		jQuery(this).prop( "disabled").off('click');
	})*/



//Change text
		//jQuery('.form-table').on('click', '.hoverview', function (e)
   



//save options in DB from popup styl
	jQuery('.thunder_sort_style_body').on('click', '.th-button-primary:not(.unclickable)', function(e){
		e.preventDefault();
		var target = jQuery(this).addClass('unclickable');
		var form = jQuery('.form-table').find('form');
		var log = form.serialize();
		console.log('true');
		console.log(form);
		console.log(log);

		jQuery( '.form-table' ).find( 'form' ).hide();
		jQuery( '.load-spinner' ).fadeIn(200);
		jQuery.ajax({
			url: ajaxurl,
			data: form.serialize() + '&action=update_styles',
			dataType: 'JSON',
			type: 'POST',
			statusCode: {
				200: function(){	
					jQuery( '.form-table' ).find('.load-spinner').fadeOut(200);
						//jQuery( '.form-table' ).append(data).fadeIn(1000);
						console.log(1);
					//console.log(data);	
						target.removeClass('unclickable');
						jQuery( '.thunder_sort_style' ).trigger( 'init:active' );

						var tpl = target.data('name');
						var preview = jQuery( '.th-admin-model-'+tpl ).find( '.fields-icon' ).find( '.preview' );
						jQuery( preview ).trigger( 'init:preview' );	
						console.log(preview);							
					}
				}
		});		
		return false;
	})

//Customize fields option open/close
	jQuery( '.thunder_sort_style_body' ).on('click', 'a.switch-field', function(e) {
		e.preventDefault();		
		var close = jQuery(this);
		if ( close.is( '.on') ) {
			close.removeClass('on').closest('thead').next().hide();						
		} else {
			close.addClass( 'on' ).closest('thead').next().show();						
		}
		return false;		
	});	





	/* Save forms */
	jQuery('.fields-icon').on('click', '.saveform:not(.unclickable)', function(e) {
		e.preventDefault();
		//jQuery('.saveform').addClass('unclickable');
		form = jQuery(this).parents('.th-admin-model').find('form');
		form.trigger('save');
		
	});
	
	jQuery( document ).on('save', '.thunder-admin-body form', function(e){
		e.preventDefault();
		jQuery('.add-section').addClass('unclickable');
		jQuery('.add-field').addClass('unclickable');
		jQuery('.preview').addClass('unclickable');
		jQuery('.resetgroup').addClass('unclickable');
		jQuery('.saveform').addClass('unclickable');
		//var target = jQuery(this).addClass('unclickable'); //
		form = jQuery(this);
		//var role = 'login';
		//var group = 'default';
		var name = jQuery(this).data('name');
		var templ = jQuery(this).data('templ');
		//var zone = jQuery(this).data('zone');
		console.log(this);

		form.find('.admin-field-menu').append('<div class="load-spinner rotate-circle"></div>');
		
		jQuery.ajax({
			url: ajaxurl,
			data: form.serialize() + '&action=thunder_save_group&name='+name+'&templ='+templ,
			dataType: 'JSON',
			type: 'POST',
			success:function(data){	
			console.log(data);			
				form.find('.load-spinner').remove();
				jQuery('.unclickable').removeClass('unclickable');
				/*var obj;
				for (key in data){
				form.parents().find('.upadmin-groups-view').append('<p>'+key+'</p>');
				}*/
			}
		});
		return false;
	});

	jQuery( '.facebook_sdk' ).on('click', function() {
		//jQuery( '.help-facebook' ).toggle();
		jQuery('html, body').animate({
			scrollTop: jQuery('.help-facebook').toggle().offset().top
		}, 500);
	});
	

		/* The groups that will receive fields */
	/*jQuery('.upadmin-tpl-body ul').sortable({
        receive: function(e,ui) {
            copyHelper= null;
			var list = jQuery(this).parents('.upadmin-tpl-body');
			jQuery.each( list.find("li[data-special^='newsection']"), function(i, v){
				section_word = 'newsection' + i;
				jQuery(this).data('special', section_word);
				jQuery(this).find('input').each(function(){
					jQuery(this).attr('name', jQuery(this).attr('name').replace('newsection', section_word));
					jQuery(this).attr('id', jQuery(this).attr('id').replace('newsection', section_word));
				});
			});
        }
	});*/
	
	/* Add new section field */
	/*jQuery('ul#upadmin-newsection').sortable({
		connectWith: ".upadmin-tpl-body ul",
		forcePlaceholderSize: false,
		helper: function(e,li) {
			copyHelper= li.clone().insertAfter(li);
			return li.clone();
		},
		stop: function() {
			copyHelper && copyHelper.remove();
		}
	});*/
	
	/* Moving out field/sorting between fields */
	/*var itemList = jQuery('#thunder_sort');
	itemList.sortable({
		connectWith: ".upadmin-tpl-body ul",
		forcePlaceholderSize: false,
		helper: function(e,li) {
			copyHelper= li.clone().insertAfter(li);
			return li.clone();
		},
		stop: function() {
			copyHelper && copyHelper.remove();
		},
        update: function(event, ui) {
            opts = {
                url: ajaxurl,
                type: 'POST',
                async: true,
                cache: false,
                dataType: 'json',
                data:{
                    action: 'userpro_field_sort',
                    order: itemList.sortable('toArray').toString()
                },
                success: function(data) {
                    return; 
                },
                error: function(xhr,textStatus,e) {
                    return; 
                }
            };
            jQuery.ajax(opts);
        }
	});*/

});