/*!
 * jQuery Upload File Plugin
 * version: 4.0.10
 * @requires jQuery v1.5 or later & form plugin
 * Copyright (c) 2013 Ravishanker Kusuma
 * http://hayageek.com/
 */

/*!
 * CleanUploader CKEditor plugin
 * version: 1.0
 * @requires Jquery Latest version, Bootstrap Latest version, MySql, PHP capable server.
 * Copyright (c) 2016 Anders Larsson
 * Website
 */

$("#uploadButtonContainer").hide();

//current selected number of files
var numberOfFilesSelected = 0;

function addNewImage(image,size,extension) {
	$("#list").append($('<tr id="'+image+'"><td><img src="uploads/thumb/'+image+'.'+extension+'" onClick="useImage(this.src);" id="addImg" data-toggle="tooltip" data-placement="right" data-original-title="Add image"/></td><td><p>'+image+'.'+extension+'</p></td><td><p>'+size+'kb</p></td><td><button type="button" class="btn btn-danger pull-right" onClick="deleteImage(\''+image+'\',\''+extension+'\');"><i class="glyphicon glyphicon-trash"> </i> Delete</button></td></tr>').hide().fadeIn(500));

};

function deleteImage(image,ext) {
	$.post( "delete.php", { name: image+'.'+ext})
	.done(function( data ) {
    if (JSON.parse(data)){
        $("#"+image).fadeOut(250,"linear");
	}
  });
};

function controlOfFileExtension(files){

	var fileExtensions = "jpg,png,gif,jpeg".toLowerCase().split(",");
	var filesLength = files.length;
	for (var i = 0; i < filesLength; i++) {
		var fileName = files[i].name;
		var ext = fileName.split('.').pop().toLowerCase();
		if(jQuery.inArray(ext, fileExtensions) >= 0) {
			numberOfFilesSelected++;
		}
	}
	if(numberOfFilesSelected > 0){
		$("#uploadButtonContainer").show();
	}
};
function checkNumberOfFiles(){
	if(numberOfFilesSelected <= 0){
		$("#uploadButtonContainer").hide();
	}
};

function startUpload() {
	downloadObj.startUpload();
};

function useImage(src) {
	newsrc = src.replace('thumb/','');

	function getUrlParam( paramName ) {
        var reParam = new RegExp( '(?:[\?&]|&)' + paramName + '=([^&]+)', 'i' ) ;
        var match = window.location.search.match(reParam) ;

        return ( match && match.length > 1 ) ? match[ 1 ] : null ;
    }
    var funcNum = getUrlParam( 'CKEditorFuncNum' );
    var imgSrc = newsrc;
    var fileUrl = imgSrc;
    window.opener.CKEDITOR.tools.callFunction( funcNum, fileUrl );
    window.close();
};

//Get config data, Not used
/*function getConfigData(type) {
	$.post( "config/config.php", { requestType: type })
		.done(function( data ) {
		return JSON.parse(data);
	});
};*/


//Login Modal
$(window).load(function(){
	$('#loginModal').modal('show');
});

// Functions for Install
$(function () {
  $('[data-toggle="tooltip"]').tooltip()
});

function checkPassword() {

	if($('#admin_password').val() != $('#admin_password_again').val()){
		$("#admin_password_again").attr('class', 'form-control notEqualTextfield');
		$("#admin_password").attr('class', 'form-control notEqualTextfield');
	} else {
		$("#admin_password_again").attr('class', 'form-control equalTextField');
		$("#admin_password").attr('class', 'form-control equalTextField');
	}
}
