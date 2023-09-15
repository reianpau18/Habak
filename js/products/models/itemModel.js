/* jshint undef: true, unused: true */
/* global define: false */
define(['backbone'], function(){
	"use strict";

	var ItemModel = Backbone.Model.extend({
		defaults: {
			name: '',
			price: '',
			code: '',
			thumb: '',
			gallery: [],
			details: '',
			path: '',
			label: {
				icon: 'fa-bomb',
				text: ''
			}
		}
	});

	return ItemModel;
});