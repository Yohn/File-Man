<?php

use Yohns\FileMan;

$Pass = 'Skem9YohnsJw3b';

$Yohn['display_path'] = './'; // make this be the path to the folder you would like to see.
$Yohn['block_ext'] = array('yohn');
$Yohn['icon_dir'] = 'styles/file-icons/png/[ext].png';
$Yohn['zip_file'] = './compressed_folder.zip';

ob_start("ob_gzhandler");
error_reporting(E_ALL);
ini_set('display_errors', E_ALL);
session_start();

include 'Yohns/FileMan.php';
$man = new FileMan($Yohn);
include 'ajax.php';

?>
<!DOCTYPE html>
<html>
<head lang="en">
<meta charset="utf-8">
<title>Yohns ~ File Manager</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<link href="styles/vi-bootstrap.css" rel="stylesheet">
<link href="styles/bs-override.css" rel="stylesheet">
<link href="styles/plugins/jschrs_modal/modal.css" rel="stylesheet">
<link href="styles/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet">

<script type="text/javascript" src="styles/plugins/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="styles/plugins/jquery.form.min.js"></script>
<script type="text/javascript" src="styles/plugins/bootstrap-2.3.2/js/bootstrap.js"></script>
<script type="text/javascript" src="styles/plugins/bs.file-upload.js"></script>
<script type="text/javascript" src="styles/plugins/jschrs_modal/bootstrap-modalmanager.js"></script>
<script type="text/javascript" src="styles/plugins/jschrs_modal/bootstrap-modal.js"></script>
<script type="text/javascript" src="styles/plugins/autoresize.js"></script>
<style type="text/css">
.block {word-wrap:no-wrap; overflow:hidden;}
.block:hover {overflow:visible; z-index:2; background:#eee; word-wrap:wrap;}
#SideTree .file img {width:25px;}
.nav-list {border-bottom:1px solid #ddd;}

.table-view, .dir-view {margin:0; list-style:none;}
.dir-view li {float:left; max-height:90px; height:90px; width:90px; max-width:90px; background:#fafafa; margin:1px; padding:3px; text-align:center; word-wrap:break-word; border:1px solid #ddd; border-radius:3px; overflow:hidden;;}
.dir-view li img {display:block; margin:0 auto;}
.dir-view li:hover {background:#f1f1f1; border-color:#08c;}

.dir-view li a {display:block; height:100%; width:100%;}
.nothing-found {width:100% !important; max-width:100% !important; font-style:italic;}
.nothing-found span {display:table-cell !important; vertical-align:middle;}

.even-row {background:#fafafa; border:1px solid #eee; border-width:1px 0; }
.table-cell-a {overflow:hidden; word-wrap:break-word}
textarea {width:98%; max-width:98%;}

/* from http://lea.verou.me/demos/css3-patterns.html */
.checkered {
	-webkit-background-size: 8px 8px;
		 -moz-background-size: 8px 8px;
					background-size: 8px 8px; /* Controls the size of the stripes */
	background-image: -webkit-gradient(linear, 0 0, 100% 100%, color-stop(.25, #eee), color-stop(.25, transparent), to(transparent)),
										-webkit-gradient(linear, 0 100%, 100% 0, color-stop(.25, #eee), color-stop(.25, transparent), to(transparent)),
										-webkit-gradient(linear, 0 0, 100% 100%, color-stop(.75, transparent), color-stop(.75, #eee)),
										-webkit-gradient(linear, 0 100%, 100% 0, color-stop(.75, transparent), color-stop(.75, #eee));
	background-image: -webkit-linear-gradient(45deg, #eee 25%, transparent 25%, transparent),
										-webkit-linear-gradient(-45deg, #eee 25%, transparent 25%, transparent),
										-webkit-linear-gradient(45deg, transparent 75%, #eee 75%),
										-webkit-linear-gradient(-45deg, transparent 75%, #eee 75%);
	background-image: -moz-linear-gradient(45deg, #eee 25%, transparent 25%, transparent),
										-moz-linear-gradient(-45deg, #eee 25%, transparent 25%, transparent),
										-moz-linear-gradient(45deg, transparent 75%, #eee 75%),
										-moz-linear-gradient(-45deg, transparent 75%, #eee 75%);
	background-image: -ms-linear-gradient(45deg, #eee 25%, transparent 25%, transparent),
										-ms-linear-gradient(-45deg, #eee 25%, transparent 25%, transparent),
										-ms-linear-gradient(45deg, transparent 75%, #eee 75%),
										-ms-linear-gradient(-45deg, transparent 75%, #eee 75%);
	background-image: -o-linear-gradient(45deg, #eee 25%, transparent 25%, transparent),
										-o-linear-gradient(-45deg, #eee 25%, transparent 25%, transparent),
										-o-linear-gradient(45deg, transparent 75%, #eee 75%),
										-o-linear-gradient(-45deg, transparent 75%, #eee 75%);
	background-image: linear-gradient(45deg, #eee 25%, transparent 25%, transparent),
										linear-gradient(-45deg, #eee 25%, transparent 25%, transparent),
										linear-gradient(45deg, transparent 75%, #eee 75%),
										linear-gradient(-45deg, transparent 75%, #eee 75%);
}
</style>
<?php
if(isset($_SESSION['alert_msg'])){
	?>
	<script type="text/javascript">
	alert('<?php echo $_SESSION['alert_msg']; ?>')
	</script>
	<?php
	unset($_SESSION['alert_msg']);
}
?>
</head>
<body>
<div class="page-container"><div class="container">
	<?php
	if(!isset($_SESSION['Yohns_fileMan'])){
		?>
	<header class="page-header"><h1>Yohns File Manager</h1></header>
	<div class="row-fluid">
		<div class="span6 offset3">
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" class="form-horizontal well">
				<legend>Sign-in Below</legend>
				<div class="control-group">
          <label class="control-label" for="inputPassword">Password</label>
          <div class="controls">
            <input type="password" id="pass" name="pass" placeholder="Password">
          </div>
        </div>
        <div id="result"></div>
        <div class="form-actions">
					<input type="hidden" name="do" value="signin">
          <button type="submit" class="btn btn-primary">Sign in</button>
        </div>
			</form>
		</div>
	</div>
		<?php
	} else {
			 // inc/vi-imps
			$go = $man->browse();

?>
	<header class="page-header"><h1>Yohns File Manager</h1></header>
	<div class="row-fluid">
		<div class="span3">
			<div class="well well-small" id="SideTree">
				<?php
				// building the directory tree..
				$tree = $man->build_tree('');
				echo $tree;
				//echo $go['tree'];
				?>
			</div>
		</div>
		<div class="span9">
			<div class="row-fluid">
				<div class="span4">
					<div class="btn-toolbar">
						<div class="btn-group">
						</div>
						<div class="btn-group">
							<button class="btn" type="button" data-toolbar="new" data-new="folder"><em class="icon-plus"></em> New Folder</button>
							<button class="btn" type="button" data-toolbar="new" data-new="file"><em class="icon-pencil"></em> New File</button>
						</div>
					</div>
				</div>
				<div class="span5">
					<form action="fileMan.php" method="post"  enctype="multipart/form-data" id="uploadForm">
						<div class="btn-toolbar">
							<div class="btn-group">
								<div class="fileupload fileupload-new" data-provides="fileupload">
									<div class="input-append input-prepend">
										<div class="uneditable-input span2"><i class="icon-file fileupload-exists"></i> <span class="fileupload-preview"></span></div>
										<span class="btn btn-file">
											<span class="fileupload-new" title="Select File To Upload"><em class="icon-upload"></em> Select file</span>
											<span class="fileupload-exists" title="Change File"><em class="icon-refresh"></em></span><input type="file" name="file[]" multiple />
										</span>
										<a href="#" class="btn fileupload-exists" data-dismiss="fileupload" title="Remove File"><em class="icon-remove"></em></a>
										<button type="submit" class="btn btn-primary fileupload-exists" title="Upload File"><em class="icon-upload"></em></button>
										<input type="hidden" name="do" value="upload">
										<input type="hidden" id="FolderIn" value="" name="folder">
									</div>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div class="span3">
					<div class="btn-toolbar">
						<div class="btn-group" data-toggle="buttons-radio">
							<button class="btn" type="button" data-toolbar="table"><em class="icon-table"></em> Table View</button>
							<button class="btn" type="button" data-toolbar="list"><em class="icon-reorder"></em> List View</button>
						</div>
					</div>
				</div>
			</div>
			<div class="progress progress-striped active" id="progress">
				<div class="bar" style="width:100%;"></div>
			</div>
			<ul class="breadcrumb" id="fm_crumb">
				<li>/</li>
			</ul>
			<div id="RightTree" class="clearfix"></div>
			<div id="preview-block" class="hide">
				<div id="preview-type" class="text-center"></div>
				<div class="row-fluid">
					<div class="span6 offset3">
						<br>
						<div class="well well-small">
							<div class="text-center"><strong id="preview-file-name"></strong></div>
							<div class="row-fluid">
								<div class="span6" id="preview-title">

								</div>
								<div class="span6" id="preview-detail">

								</div>
							</div>
							<div id="preview-toolbar" class="text-center">

							</div>
						</div>
					</div>
				</div>
			</div>
			<div id="file-block" class="hide">
				<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" id="file-block-form" class="form-inline">
					<div class="alert alert-info text-center">Currently we can only create txt files</div>
					<div class="text-center well">
						<p><textarea rows="10" name="file-text" id="file-text"></textarea></p>
						<p><em>For support tickets, please use the file naming scheme -> </em><strong>YYYY-MM-DD-subject</strong></p>
						<label for="file-name">File Name:&nbsp;&nbsp;</label><input type="text" id="file-name" name="file-name">
						<br><br>
						<input type="hidden" name="do" value="new-file" id="file-do">
						<input type="hidden" name="file-orig-name" value="" id="file-orig-name">
						<button type="submit" class="btn btn-primary" id="file-submit"><em class="icon-ok"></em> Save File</button>
					</div>
				</form>
			</div>
			<br><br>
			<div class="alert alert-warning clearfix">
				<strong>TODO:</strong>
				<ul>
					<li>Fix the dang ole left side show / hidden thing where it doesnt load the right side because the left side was opened already. So instead of hiding it, just reload it anyways..</li>
					<li>Use full urls to be able to go back to that page via links.</li>
					<li>Create Files.</li>
					<li>Drag and Drop files on page to upload</li>
					<li>Instead of refreshing the page after upload / folder creation have it update the left side tree.
						<ul><li>- OR send to a page with director=dir in url, and load_home that way.</li></ul>
					</li>
					<li>Table view sort by columns</li>
					<li>Move file</li>
					<li>Rename file</li>
					<li>Download whole folder in zip</li>
					<li>Get icons for..
						<ul>
							<li>m2v</li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</div>

<script type="text/javascript">

function humanFileSize(bytes, si) {
    var thresh = si ? 1000 : 1024;
    if(bytes < thresh) return bytes + ' B';
    var units = si ? ['kB','MB','GB','TB','PB','EB','ZB','YB'] : ['KiB','MiB','GiB','TiB','PiB','EiB','ZiB','YiB'];
    var u = -1;
    do {
        bytes /= thresh;
        ++u;
    } while(bytes >= thresh);
    return bytes.toFixed(1)+' '+units[u];
};

function timeConverter(UNIX_timestamp){
	var a = new Date(UNIX_timestamp*1000);
	var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
	var year = a.getFullYear();
	var month = months[a.getMonth()];
	var date = a.getDate();
	var hour = a.getHours();
	var min = a.getMinutes();
//	var sec = a.getSeconds();
	if(hour > 11){
		var mer = 'PM'
		if(hour > 12){
			hour = hour-12
		}
	} else {
		var mer = 'AM'
	}
	var time = month+' '+date+', '+year+' '+hour+':'+min+' '+mer //+':'+sec ;
	return time;
}

function load_home(path){
	$('#preview-block, #file-block').fadeOut()
	var mm = $('#'+path)
		/*mm.removeClass('hide').parents('li').each(function(){
			var tt = $(this)
			tt.find('> ul').removeClass('hide')
			alert(tt.attr('class'))
		}) */
	var ls_clone = mm.clone(), rs = $('#RightTree'), title, b = '', i, dir, dira, cr = '', ph = 'folder'
		, folderIn
		if(path == 'folder_'){
			folderIn = '/'
		} else {
			folderIn = path.replace(/folder_/, '').replace(/_/g, '/')
		}
	$('#FolderIn').val(folderIn)

	rs.fadeOut('fast', function(){
		ls_clone.find('ul').remove() // removes the child ul element
		ls_clone.removeClass('nav nav-list hide').addClass('dir-view ul-dir')
		i = ls_clone.attr('id')
		ls_clone.attr('id', 'big_'+i)
		dir = i.replace(/folder_/, '')
		dira = dir.split('_')
		for(var a in dira){
			if(dira[a] != ''){
				ph += '_'+dira[a]
				cr += '<li><a href="javascript:load_home(\''+ph+'\')" >'+dira[a]+'</a>  <span class="divider">/</span></li>'
				b = dira[a]
			}
		}
		ls_clone.find('.nav-header').remove()
		/*title = ls_clone.find('.nav-header')
		b = title.html()
		title.remove() */
		$('#fm_crumb').html('<li><button class="btn btn-small btn-small" onclick="load_home(\'folder_\')"><em class="icon-home icon-large"></em></button> <span class="divider">/</span></li>'+cr+'<li class="pull-right"><button type="button" class="btn btn-small tip" title="Download Zip File of all this Content" id="download-zip"><em class="icon-briefcase"></em></button></li>') //+'<li class="active">'+b+'</li>')
		rs.html('')
		// for the folders..
		ls_clone.find('.icon-folder-close').replaceWith('<img src="styles/file-icons/bluefolder3.png" alt="Folder">')
		ls_clone.find('.icon-folder-open').replaceWith('<img src="styles/file-icons/bluefolder3.png" alt="Folder">')
		// for the files..
		ls_clone.find('[data-ext]').each(function(){
			var th = $(this), ext = th.data('ext'), path = th.data('path'), name = th.data('name')
			switch(ext){
				case 'jpg':
				 case 'gif':
				  case 'png': // 'vi-imps/'+
					th.find('img').attr('src', path+'/'+name)
				break;

			}
		})
		ls_clone.removeClass('hide').appendTo('#RightTree')
		rs.fadeIn('fast')
	})
}

$(function(){
	$('#SideTree ul li ul').css('display', 'none')
	$('#file-text').autoGrow()
	//on page load, we need to configure the first page of directory tree.
	// may need to be changed when we change the baseDir param
	<?php
	if(isset($_GET['folder'])){
		echo "alert('sorry I didnt load the correct folder, to be done in the future.');";
		/*$folder = str_replace('folder_', '', $_GET['folder']);
		$ex = explode('_', $folder);
		$t = count($ex);
		$name = $ex[$t-1];
		unset($ex[$t-1]);
		$path = implode('/', $ex);
		if($path == '') $path = '""';
		?>

		var te = $('#<?php echo $_GET['folder']; ?>'), c = te.length
		if(c > 0){
			// this half way works. we need to open the left side for it to work correctly.
			load_home('<?php echo $_GET['folder']; ?>')
			//te.parent('li').find('> .dir').trigger('click')
			//alert(te.parent('li').find('> .dir').html())
			//var p = te.parent('li').find('> a.dir').trigger('click')
		} else { alert('folder not found'); }
		<?php
		*/
		//		echo 'load_home(\''.$_GET['folder'].'\')';
	} else {
		//echo 'load_home(\'folder_\')';
	}
	?>
	load_home('folder_')
	$('.fileupload [title], .tip').tooltip()

	$('[data-new=folder]').popover({
		container : 'body',
		title : 'New Folder',
		placement : 'bottom',
		trigger : 'manual',
		html : true,
		content : function(){
			return '<div class="row-fluid">'
				+'<div class="span4">'
					+'Folder Name:'
				+'</div>'
				+'<div class="span8">'
					+'<input type="text" id="new-folder-name" class="span11">'
				+'</div>'
			+'</div><div class="text-center"><button type="button" class="btn btn-primary" id="save-new-folder"><em class="icon-folder"></em> Save Folder</button></div>'
		}
	})
	var $progress = $('#progress'), $bar = $progress.find('.bar')

	$('#uploadForm').ajaxForm({
		beforeSend: function() {
				var percentVal = '0%';
				$progress.slideDown()
				$bar.width(percentVal).html(percentVal);
		},
		uploadProgress: function(event, position, total, percentComplete) {
				var percentVal = percentComplete + '%';
				$bar.width(percentVal).html(percentVal);
		},
		success: function() {
				var percentVal = '100%';
				$bar.width(percentVal).html(percentVal);
		},
		complete: function(xhr) {
		//alert(xhr.responseText)
			window.location = 'fileMan.php'
			//status.html(xhr.responseText);
		}
	})
	$('#file-block-form').on('submit', function(){
		if($('#file-name').val() == ''){
			alert('Please include a file name for this file.')
		} else {
			var folderIn = $('#FolderIn').val(), sub = $('#file-submit')
			sub.button('loading')
			$(this).ajaxSubmit({
				data: {folder : folderIn},
				success: function(r){
					if(r == 'ok'){
						window.location = 'fileMan.php'
					} else {
						alert(r)
						sub.button('reset')
					}
					//window.location = 'fileMan.php'
				},
				error: function(){
					alert('There was an error creating the file.')
					sub.button('reset')
				}
			})
		}
		return false
	})
	$(document).tooltip({selector : '.tip'})
	// download a zip file of the current folder
	.delegate('#download-zip', 'click', function(){
		var path = $('#FolderIn').val(), th = $(this), em = th.find('em')
		em.removeClass('icon-briefcase').addClass('icon-spin icon-spinner')
		alert('you thought you could download a zip file of this folder right? '+path)
		$.post('fileMan.php', {do : 'zip', folder : path}, function(r){
			alert(r)
			em.addClass('icon-briefcase').removeClass('icon-spin icon-spinner')
			window.location = '/inc/'+r
		})
	})
	// saving new folder
	.delegate('#save-new-folder', 'click', function(){
		var name = $('#new-folder-name'), path = $('#FolderIn')
		if(name.val() != ''){
			$progress.slideDown().find('.bar').width('100%')
			$.post('fileMan.php', {do : 'newFolder', name : name.val(), path : path.val()}, function(r){
				$progress.slideUp()
				if(r == 'ok!'){
					window.location = 'fileMan.php'
				} else {
					alert('There was an error creating your new directory '+r)
				}
			})
		} else {
			alert('We cant save a folder without a name..')
		}
		$('[data-new=folder]').popover('hide')
	})
	// main tree click
	.delegate('#SideTree .dir', 'click', function(){
		var th = $(this), ul, clone, par = th.parent() //, lis = th.parents('li')
		ul = par.find('> ul')
		ul.slideToggle('fast', function() {
			if(ul.is(':hidden')){
				par.removeClass('active').find('.icon-folder-open').removeClass('icon-folder-open').addClass('icon-folder-close')
				// should we open up the next parents ul?
				load_home(par.parents('ul:first').attr('id'))
			} else {
				$('#SideTree li.active').removeClass('active')
				par.addClass('active')
				th.find('.icon-folder-close').removeClass('icon-folder-close').addClass('icon-folder-open')
				load_home(ul.attr('id'))
			}
		})
	})
	// if a directory is clicked on the right side, make it trigger a click on the left side!
	.delegate('#RightTree .dir', 'click', function(){
		var th = $(this), path = th.data('path'), name = th.data('name')
			, par = th.parents('.ul-dir'), ul = par.attr('id'), sul = ul.replace(/big_/, '')
		$('#'+sul).find('.dir').each(function(){
			var th1 = $(this), name1 = th1.data('name'), path1 = th1.data('path')
			if(name1 == name && path1 == path){
				th1.trigger('click')
				return false
			}
		})
	})
	.delegate('.file', 'click', function(){
		//data-created="1377278588" data-modified="1377100051" data-filesize="463">
		var th = $(this), path = th.data('path'), name = th.data('name'), ext = th.data('ext')
			, created = th.data('created'), modified = th.data('modified'), size = th.data('filesize')
			, rs = $('#RightTree'), pblock = $('#preview-block')
			, ptitle = $('#preview-title'), pdetail = $('#preview-detail')
			, pls = '', prs = ''
			//		$('#file-block').fadeOut('fast')
		rs.fadeOut('fast', function(){
			// build toolbar
			$('#preview-toolbar').html(
					'<button type="button" class="btn btn-success" data-toolbar="download" data-download="'+path+'/'+name+'"><em class="icon-download"></em> Download</button> '
					+'<button type="button" class="btn btn-inverse" data-toolbar="move" data-move="'+path+'/'+name+'"><em class="icon-move"></em> Move</button> '
					+'<button type="button" class="btn btn-warning" data-toolbar="rename" data-rename="'+path+'/'+name+'"><em class="icon-edit"></em> Rename</button> '
					+'<button type="button" class="btn btn-danger" data-toolbar="delete" data-delete="'+path+'/'+name+'"><em class="icon-edit"></em> Delete</button> '
			)
			$('#fm_crumb')/*.find('li:last').append(' <span class="divider">/</span>').parent()*/.append('<li class="active">'+name+'</li>')
			// build prev
			$('#preview-file-name').html(name)
			if(ext == 'jpg' || ext == 'gif' || ext == 'png'){ // took out vi-imps/ on 2/7/2014
				$('#preview-type').html('<div class="checkered"><img src="'+path+'/'+name+'" alt="Preview"></div>')
				// we should show the dimensions..
				pls += '<strong>Dimensions:</strong> <br>'
				prs += th.data('width')+'x'+th.data('height')+' <br>'
			} else if(ext == 'txt'){
				if(path != '') path = '/'+path
				$('#preview-type').html('<pre id="load-txt"><em class="icon-spin icon-spinner icon-large"></em><br><?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', $Yohn['display_path']); ?>'+path+'/'+name+'</pre>');
				//$('#preview-type').html('<pre><?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', $Yohn['display_path']); ?>'+path+'/'+name+'</pre>');
				//$('#load-txt').load('<?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', $Yohn['display_path']); ?>'+path+'/'+name, function(response, status, xhr) {
				$.post('<?php echo str_replace($_SERVER['DOCUMENT_ROOT'], '', $Yohn['display_path']); ?>'+path+'/'+name, function(response, status, xhr) {
					if (status == "error") {
						var msg = "Sorry but there was an error: ";
						alert(msg + xhr.status + " " + xhr.statusText);
					} else {
						$('#preview-type').html('<pre class="text-left" id="load-txt">'+response+'</pre><br><div class="text-center"><button type="button" class="btn btn-primary" id="edit-file"><em class="icon-edit"></em> Edit File</button></div>');
						//alert(response+' '+xhr.status + " " + xhr.statusText);
					}
				})
			} else {
				var icon = th.find('img').clone()
				$('#preview-type').html(icon)
			}
			// build table
			pls += '<strong>Filesize:</strong> <br>'
				+'<strong>Last Modified</strong><br>'
				+'<strong>Created</strong><br>'
			prs += humanFileSize(size,true)+'<br>'
				+timeConverter(modified)+'<br>'
				+timeConverter(created)+'<br>'
			ptitle.html(pls)
			pdetail.html(prs)

			pblock.fadeIn('slow')
		})
	})
	.delegate('#edit-file', 'click', function(){
		$('#preview-block').fadeOut('fast')
		$('#file-block').fadeIn('fast')
		$('#file-do').val('edit-file')
		$('#file-text').html($('#load-txt').text())
		var name = $('#fm_crumb li:last').text(), sp = name.split('.'), le = sp.length, nn = ''
		$('#file-orig-name').val(name)
		for(var i = 0; i<le-1; i++){
			if(sp[i] != ''){
				if(nn != '') nn += '.'
				nn += sp[i]
			}
		}
		$('#file-name').val(nn)
	//		$('#preview-type
	})
	.delegate('[data-toolbar]', 'click', function(){
		var th = $(this), tb = th.data('toolbar'), what = th.data(tb)
		switch(tb){
			case 'download':
				window.location = '/inc/fileMan.php?download='+what
			break;
			case 'new':
				switch(what){
					case 'folder':
						th.popover('toggle')
						$('.popover-title').html('New Folder In "'+$('#FolderIn').val()+'"')
					break;
					case 'file':
						$('#RightTree, #preview-block').fadeOut('fast')
						$('#file-block').fadeIn('fast')
						$('#fm_crumb').append('<li class="active">New File</li>')
						$('#file-do').val('new-file')
						$('#file-text').html('')
						//alert('creating new files is not set up yet!')
					break;
				}
			break;
			case 'list':
				var fin = $('#FolderIn').val()
				if(fin == '/'){
					load_home('folder_')
				} else {
					//alert(fin)
					load_home('folder_'+fin.replace("/", '_'))
				}
			break;
			case 'table':
				var fin = $('#FolderIn').val(), rs = $('#RightTree'), rsh = rs.html(), nrs = $(rsh)
				if(nrs.hasClass('table-view')){
					break;
				}
				nrs.removeClass('dir-view').addClass('table-view')
				nrs.find('li').each(function(){
				// class="file" href="javascript:void(0);" data-path="images" data-name="200-100-5.png"
				// data-ext="png" data-created="1377535349"
				// data-modified="1377535349" data-filesize="105512" data-width="200" data-height="200"
					var th = $(this), a = th.find('a'), created = a.data('created'), mod = a.data('modified')
						, ext = a.data('ext'), size = a.data('filesize'), build = '<div class="row-fluid"><div class="span3 table-cell-a">'+th.clone().html()+'</div>'
					if(ext && (ext == 'jpg' || ext == 'gif' || ext == 'png')){
						// its an image.. so it gets formatted differently.
						build += '<div class="span2">'+a.data('width')+'x'+a.data('height')+'</div>'
							+'<div class="span3">'+humanFileSize(size,true)+'</div>'
							+'<div class="span1">'+ext+'</div>'
					} else if (a.hasClass('dir')){
						build += '<div class="span2">dir</div><div class="span3"></div>'
					} else {
						build += '<div class="span2">'+ext+'</div>'
							+'<div class="span3">'+humanFileSize(size,true)+'</div>'
					}
					build += '<div class="span3">'+timeConverter(mod)+'</div>'
					+'</div>'
					th.html(build)
				})
				nrs.find('li:even').addClass('even-row')
				rs.html(nrs)
				//alert(nrs.html())
				//alert(fin)
			break;
			case 'delete':
				// send confirmation
				if(confirm('Are you sure you want to delete this file? You will not get it back. ')){
					// for redirecting..
					var sp = what.split('/'), t = sp.length, loc = 'folder', i
					if(sp[0] == ''){
						loc += '_'
					} else {
						for(i=0;i<t-1;i++){
							loc += '_'+sp[i]
						}
					}
					// delete yo
					$.post('fileMan.php', {do: 'delete', file : what}, function(r){
						if(r == 'ok!'){
							window.location = 'fileMan.php?folder='+loc
						} else {
							alert(r)
						}
					})
				}
				// +'<button data-delete="'+path+'/'+name+'"><em class="icon-edit"></em> Delete</button> '
			break;
			case 'rename':
				var curr = $('#preview-file-name').html()
					, sp = curr.split('.'), c_sp = sp.length
					console.log(sp)
					sp.splice(c_sp-1, 1)
					console.log(sp)
			break;
			default:
			alert('Hey! I didnt set this up yet.')
			/*			case 'move':
			break;
			case 'rename':
			break; */
		}
	})

	$('#progress').slideUp('fast', function(){
		$(this).removeClass('active').addClass('hide').find('.bar').width('0%')
	})
})
</script>
	<?php
	} // /end if user is signed in

	?>
</div></div>
</body>
</html>
<?php
//echo '<pre>'.print_r($browse,1).'</pre>';
?>