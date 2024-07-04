<?php


// signin only.
if(isset($_POST['do'])){
	switch($_POST['do']){
		case 'signin':
			if($_POST['pass'] == $Pass){
				$_SESSION['Yohns_fileMan'] = true;
				header("Location: fileMan.php");
			}
		break;
	}
}

if(isset($_SESSION['Yohns_fileMan'])){
	if(isset($_GET['download'])){
		$file = $_SERVER['DOCUMENT_ROOT'].$_GET['download']; //[0] == '/' ? $baseDir.$_GET['download'] : $baseDir.'/'.$_GET['download'];
		if(is_file($file)){
			$ex = explode('/', $file);
			$t_c = count($ex);
			$name = $ex[$t_c-1];
			header('Content-Disposition: attachment; filename="'.$name.'"');
			header('Content-Type: application/octet-stream'); // or application/force-download
			echo file_get_contents($file);
			exit;
		} else {
			$_SESSION['alert_msg'] = "File can not be found\n ".$file;
		}
	}
	if(isset($_POST['do'])){
		switch($_POST['do']){
			case 'new-file':
				$path = $_POST['folder'] == '/' ? '/' : '/'.$_POST['folder'];
				echo $man->newFile($path, $_POST['file-name'], $_POST['file-text']);
				//echo '<pre>'.print_r($_POST,1).'</pre>';
			break;
			case 'edit-file':
				$path = $_POST['folder'] == '/' ? '/' : '/'.$_POST['folder'];
				echo $man->editFile($path, $_POST['file-name'], $_POST['file-text'], $_POST['file-orig-name']);
				//echo '<pre>'.print_r($_POST,1).'</pre>';
			break;
			case 'newFolder':
				$path = $_POST['path'] == '/' ? '/' : '/'.$_POST['path'];
				$ret = $man->newFolder($path, $_POST['name']);
				if($ret == true){
					echo 'ok!';
				} else {
					echo 'There was an error creating the folder.';
				}
			break;
			case 'delete':
				$ret = $man->delete($_POST['file']);
				echo $ret; //.'<pre>'.print_r($_POST,1).'</pre>';
			break;
			case 'zip':
				$path = $_POST['folder'] == '/' ? '/' : '/'.$_POST['folder'].'/';
				//	echo $man->dir.$path;
				$ret = $man->zip_dir($path);
				echo $Yohn['zip_file'];
			break;
			case 'upload':
				ini_set('memory_limit', '100M');
				ini_set('post_max_size', '100M');
				ini_set('upload_max_filesize', '100M');
				$path = $_POST['folder'] == '/' ? '/' : '/'.$_POST['folder'].'/';
				if(isset($_FILES['file']['name']) && is_array($_FILES['file']['name'])){
					//echo $path;
					foreach($_FILES['file']['name'] as $ind => $name){
						$saveUpload = $man->upload($name, $_FILES['file']['tmp_name'][$ind], $path);
					}
				}
				echo 'ok!';
				//echo $path.'<pre>'.print_r($_POST,1).'<br>'.print_r($_FILES,1).'</pre>';
			break;
		}
		exit;
	}
}