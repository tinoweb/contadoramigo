/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.shiftEnterMode = CKEDITOR.ENTER_DIV;
	config.enterMode = CKEDITOR.ENTER_BR;
//	config.extraPlugins = 'stylesheetparser';
	// Only add rules for <p> and <span> elements.
//	config.stylesheetParser_validSelectors = /\^(div|p|span)\.\w+/;
	config.language = 'pt-br';
	config.uiColor = '#cccccc';
	config.font_names = 'Trebuchet MS';
	config.height = '400px';
	config.width = '642px';
	config.toolbar_My =
	[
	 	['Source'],
		['Bold','Italic','Underline'],
		['Undo','Redo'],
		['Link','Unlink'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['FontSize','Format','TextColor']
//		['class']
	];
	config.toolbar = 'My';
	config.useComputedState = false;
	config.font_defaultLabel = 'Trebuchet MS';
	//config.resize_enabled = false;
	config.fontSize_sizes = '8/8px;10/10px;12/12px;14/14px;16/16px;18/18px;';
//	config.protectedSource.push( /<\?[\s\S]*?\?>/g );
	config.contentsCss = '../estilo.css';
	config.toolbarCanCollapse = false;
	config.bodyClass = 'divNovidadesHomeCKEditor'
};
