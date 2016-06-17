{"changed":true,"filter":false,"title":"config.js","tooltip":"/public/ckeditor/config.js","value":"/**\n * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.\n * For licensing, see LICENSE.md or http://ckeditor.com/license\n */\n\nCKEDITOR.editorConfig = function (config) {\n    // Define changes to default configuration here. For example:\n    // config.language = 'fr';\n    // config.uiColor = '#AADC6E';\n    config.toolbar = [\n        { name: 'document', items : [ 'Source','-','Save','NewPage','DocProps','Preview','Print','-','Templates' ] },\n\t{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },\n\t{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },\n\t{ name: 'forms', items : [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', \n        'HiddenField' ] },\n\t'/',\n\t{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },\n\t{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote','CreateDiv',\n\t'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock','-','BidiLtr','BidiRtl' ] },\n\t{ name: 'links', items : [ 'Link','Unlink','Anchor' ] },\n\t{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak','Iframe' ] },\n\t'/',\n\t{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },\n\t{ name: 'colors', items : [ 'TextColor','BGColor' ] },\n\t{ name: 'tools', items : [ 'Maximize', 'ShowBlocks','-','About' ] },\n        {name: 'ckwebspeech', items: ['webSpeechEnabled', 'webSpeechSettings' ]},\n        {name: 'uploadimage', items: ['filebrowserUploadUrl']}\n    ];\n    \n    \n    config.extraPlugins = 'autocorrect,ckwebspeech,uploadimage,contextmenu';\n    \n    config.saveUrl = '/saveImage';\n    //config.saveUrl = 'http://localhost:8000/saveImage';\n    //config.pollUrl = 'https://localhost:3000/socket.io';\n    //config.pollUrl = '/autoSave';\n    \n    //For file uploads, specifically image uploads\n    //config.filebrowserImageUploadUrl = 'https://job.tc/pm/saveImage';\n    config.filebrowserImageUploadUrl = 'http://localhost:8000/saveImage';\n    \n    //For ckwebspeechs\n    config.ckwebspeech = {'culture': 'en',\n        'commandvoice': 'command', //trigger voice commands\n        'commands': [//command list\n            {'newline': 'new line'}, //trigger to add a new line in CKEditor\n            {'newparagraph': 'new paragraph'}, //trigger to add a new paragraph in CKEditor\n            {'undo': 'undo'}, //trigger to undo changes in CKEditor\n            {'redo': 'redo'} //trigger to redo changes in CKEditor\n        ]\n    };\n    \n    //Theme changes\n    config.skin = 'office2013';\n    //config.uiColor = '#F3F4F5';\n    \n    \n     var wh = window.innerHeight;\n    //Css changes\n    config.height = wh - 200 +'px';\n\n    //Disbale resize\n    config.resize_enabled = false;\n\n    //config.autoParagraph = false;\n    config.removePlugins = 'autosave,resize';\n\n};\n","undoManager":{"mark":11,"position":11,"stack":[[{"start":{"row":30,"column":27},"end":{"row":30,"column":28},"action":"insert","lines":["a"],"id":2}],[{"start":{"row":30,"column":28},"end":{"row":30,"column":29},"action":"insert","lines":["u"],"id":3}],[{"start":{"row":30,"column":29},"end":{"row":30,"column":30},"action":"insert","lines":["t"],"id":4}],[{"start":{"row":30,"column":30},"end":{"row":30,"column":31},"action":"insert","lines":["o"],"id":5}],[{"start":{"row":30,"column":31},"end":{"row":30,"column":32},"action":"insert","lines":["c"],"id":6}],[{"start":{"row":30,"column":32},"end":{"row":30,"column":33},"action":"insert","lines":["o"],"id":7}],[{"start":{"row":30,"column":33},"end":{"row":30,"column":34},"action":"insert","lines":["r"],"id":8}],[{"start":{"row":30,"column":34},"end":{"row":30,"column":35},"action":"insert","lines":["r"],"id":9}],[{"start":{"row":30,"column":35},"end":{"row":30,"column":36},"action":"insert","lines":["e"],"id":10}],[{"start":{"row":30,"column":36},"end":{"row":30,"column":37},"action":"insert","lines":["c"],"id":11}],[{"start":{"row":30,"column":37},"end":{"row":30,"column":38},"action":"insert","lines":["t"],"id":12}],[{"start":{"row":30,"column":38},"end":{"row":30,"column":39},"action":"insert","lines":[","],"id":13}],[{"start":{"row":30,"column":27},"end":{"row":30,"column":28},"action":"remove","lines":[","],"id":15}],[{"start":{"row":30,"column":27},"end":{"row":30,"column":38},"action":"remove","lines":["autocorrect"],"id":15}]]},"ace":{"folds":[],"scrolltop":0,"scrollleft":0,"selection":{"start":{"row":14,"column":26},"end":{"row":14,"column":26},"isBackwards":false},"options":{"guessTabSize":true,"useWrapMode":false,"wrapToView":true},"firstLineState":0},"timestamp":1466165138083}