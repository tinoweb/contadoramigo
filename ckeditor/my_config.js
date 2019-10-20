/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.shiftEnterMode = CKEDITOR.ENTER_DIV;
	config.enterMode = CKEDITOR.ENTER_BR;
	config.language = 'pt-br';
	config.uiColor = '#cccccc';
	config.font_names = 'Trebuchet MS';
	config.height = '600px';
	config.width = '100%';
	config.toolbar_My =
	[
	 	['Source'],
		['Bold','Italic','Underline'],
		['Undo','Redo'],
		['Link','Unlink'],
		['Image'],
		['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
		['FontSize','Format','TextColor']
	];
	config.toolbar = 'My';
	config.font_defaultLabel = 'Trebuchet MS';
	config.resize_enabled = false;
	config.fontSize_sizes = '8/8px;10/10px;12/12px;14/14px;16/16px;18/18px;';
	config.protectedSource.push( /<\?[\s\S]*?\?>/g );
	config.contentsCss = '../style_ckeditor.css';
//	config.stylesSet = 'my_styles_companhia';
	config.toolbarCanCollapse = false;
	config.bodyClass = 'body_admin'
};
