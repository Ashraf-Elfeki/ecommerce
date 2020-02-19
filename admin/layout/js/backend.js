
/*global $*/
$(function () {
	'use strict';

/* Dashboard */

	$('.toggle-info').click(function () {

		$(this).toggleClass('selected').parent().next('.panel-body').slideToggle(100);

		if ($(this).hasClass('selected')) {
			$(this).html('<i class="fas fa-plus fa-2x"></i>');
		} else {
			$(this).html('<i class="fas fa-minus fa-2x"></i>');
		}

	});

/* Fire The Select Box Plugin
	$("select").selectBoxIt({
		autoWidth: false,
		theme: "jqueryui"
	});
*/		
	$('[placeholder]').focus(function () {
		$(this).attr('data-text', $(this).attr('placeholder'));
		$(this).attr('placeholder', '');
	}).blur(function() {
		$(this).attr('placeholder', $(this).attr('data-text'));
	});

	$('input').each(function () {
		if ($(this).attr('required') === 'required') {
			$(this).after('<span class="form-control-feedback">*</span>');
		}
	});


/* Show Password Field On */
	var passField = $('.password');
	$('.show-pass').hover(function (){
		passField.attr('type','text');
	}, function (){
		passField.attr('type','password');
	});


/* Confirm message*/
	$('.confirm').click(function (){
		return confirm("Are You Sure");
	});

/* Toggle For Category Data From Category Name*/

	$('.cat h3').click(function(){
		$(this).next('.full-view').fadeToggle(200);
	});


/* Toggle For Category Data From Option panel*/

	$('.cat-option span').click(function(){
		$(this).addClass('active').siblings('span').removeClass('active');
		if ($(this).data('view') === 'full') {
			$('.cat .full-view').fadeIn(200);
		}else {
			$('.cat .full-view').fadeOut(200);
		}
	});

	$('.sub-cat').hover(function(){
		$(this).find('.delete-sub-cat').fadeIn();
	}, function(){
		$(this).find('.delete-sub-cat').fadeOut();
	});
	
});



	
