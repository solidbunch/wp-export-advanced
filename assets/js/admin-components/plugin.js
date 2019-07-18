import $ from 'jquery';

export default class Theme {
	/**
	 Constructor
	 **/
	constructor() {
		this.build();
		this.events()
	}

	/**
	 Build page elements, plugins init
	 **/
	build() {

	}

	/**
	 Set page events
	 **/
	events() {
		this.SelectPostType();
	}

	/**
	 * Select Post Type
	 **/
	SelectPostType() {

		const form = $('#export-filters');
		const filters = form.find('.export-filters');
		form.find('input:radio').change(function() {
			filters.slideUp('fast');
			var postslug = $(this).val();
			$('#' + postslug + '-filters').slideDown();
		});

	}

}
