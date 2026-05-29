/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	
	
    config.font_names = 'Arial/Arial;Courier New/Courier New;Tahoma/Tahoma;Times New Roman/Times New Roman;';
   
    config.toolbar =    
    [   
    ['Save','NewPage','Preview'],    
    ['Cut','Copy','Paste','PasteText','PasteFromWord'],
    ['Undo','Redo','-','Find','Replace','SelectAll'],
    ['Form', 'Button', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select'],
    ['Link','Unlink'],
    ['Image','Flash','Table','HorizontalRule','SpecialChar'],
    '/',
    ['Format','Font','FontSize'],
    ['TextColor','BGColor'],
    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
    ['NumberedList','BulletedList'],
    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock']
    ];
};
