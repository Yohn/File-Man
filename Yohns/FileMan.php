<?php

namespace Yohns;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use FilesystemIterator;

/*
* Help Building this from..
*
* @link http://stackoverflow.com/questions/12077177/how-does-recursiveiteratoriterator-works-in-php
* @author hakre <http://hakre.wordpress.com>
*
* http://stackoverflow.com/questions/1334613/how-to-recursively-zip-a-directory-in-php
* Zip('/folder/to/compress/', './compressed.zip');
*
*/

class FileMan {
	public $dir;
	public $tree;
	private $Yohn;
	private $zip_file;

	public function __construct($Yohn){
		$this->Yohn = $Yohn;
		$this->dir = $Yohn['display_path'];
		$this->zip_file = $this->Yohn['zip_file']; // for downloading zip of directory
	}

	public function newFolder($path, $name){
		// first things first, remove weird ass characters.
		$name = str_replace(' ', '-', $name);
		$fname = preg_replace('/[^a-zA-Z0-9-]/', '', $name);
		$dir = $this->dir.str_replace('_', '/', $path).'/'.$fname;
			//echo '"'.$dir.'"';
		if(mkdir($dir, 0777)){
			return true;
		} else {
			return false;
		}
	}

	public function zip_dir($path){
		$dir = $this->dir.str_replace('_', '/', $path);
		if(is_dir($dir)){
			$this->zip($dir, $this->zip_file);
			if(is_file($this->zip_file)){
				return true;
			} else {
				return false;
			}
		}
	}

		/**
	 * Zip function will
	 *
	 * @param string $source
	 * 		A directory of files to zip
	 * @param string $destination
	 * 		The path/to/file.zip to put the zip file
	 * @return bool
	 */
	public function zip($source, $destination){
		if(!extension_loaded('zip') || !file_exists($source)){ return false; }
		$zip = new ZipArchive();
		if(!$zip->open($destination, ZIPARCHIVE::CREATE)){ return false; }
		$source = str_replace('\\', '/', realpath($source));
		if(is_dir($source) === true){
			$files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);
			foreach ($files as $file){
				$file = str_replace('\\', '/', $file);
				// Ignore "." and ".." folders
				if( in_array(substr($file, strrpos($file, '/')+1), array('.', '..')) )
						continue;
				$file = realpath($file);
				if (is_dir($file) === true){
						$zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
				} else if (is_file($file) === true){
						$zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
				}
			}
		} elseif (is_file($source) === true){
			$zip->addFromString(basename($source), file_get_contents($source));
		}
		return $zip->close();
	}

	public function upload($name, $tmp_name, $path){
		if(is_file($this->dir.str_replace('_', '/', $path).$name)){
			$i = 0;
			for(;;){
				if(!is_file($this->dir.str_replace('_', '/',$path).$i.'_'.$name)){
					$newPath = $this->dir.str_replace('_', '/',$path).$i.'_'.$name;
					break;
				}
				$i++;
			}
		} else {
			$newPath = $this->dir.str_replace('_', '/',$path).$name;
		}
		move_uploaded_file($tmp_name, $newPath);
		return true;
	}

	public function delete($file){
		$file = $file[0] == '/' ? $file : '/'.$file;
		if(is_file($this->dir.$file)){
			@unlink($this->dir.$file);
			return 'ok!';
		} else {
			return 'fould not find file - '.$this->dir.$file;
		}
	}

	public function newFile($folder, $name, $cont){
		$name = str_replace(' ', '-', $name);
		$rep_name = preg_replace('/[^a-zA-Z0-9-]/', '', $name);
		if(is_dir($this->dir.$folder)){
			$file = $this->dir.$folder.'/'.$rep_name.'.txt';
			if(is_file($file)){
				$i = 0;
				for(;;){
					if(!is_file($this->dir.$folder.'/'.$rep_name.'-'.$i.'.txt')){
						$file = $this->dir.$folder.'/'.$rep_name.'-'.$i.'.txt';
						break;
					}
					$i++;
				}
			}
			$fp = fopen($file, 'w');
			if(flock($fp, LOCK_EX)){
				fwrite($fp, htmlentities($cont));
				flock($fp, LOCK_UN);
				$locked = true;
			}
			fclose($fp);
			if(is_file($file) && isset($locked)){
				return 'ok';
			} else {
				return 'Error creating file.';
			}
		} else {
			return 'Folder not found';
		}
	}

	public function editFile($folder, $name, $cont, $origName){
	//	return 'hi';
		if($origName != $name){
			$name = str_replace(' ', '-', $name);
			$rep_name = preg_replace('/[^a-zA-Z0-9-]/', '', $name);
		} else {
			$rep_name = $name;
		}
		if(is_dir($this->dir.$folder)){
			$file = $this->dir.$folder.'/'.$rep_name.'.txt';
			if(is_file($file)) unlink($file);
			$fp = fopen($file, 'w');
			if(flock($fp, LOCK_EX)){
				fwrite($fp, htmlentities($cont));
				flock($fp, LOCK_UN);
				$locked = true;
			}
			fclose($fp);
			if(is_file($file) && isset($locked)){
				return 'ok';
			} else {
				return 'Error locking file. Please try again.';
			}
		} else {
			return 'No directory found';
		}
	}

	public function browse(){
		return $this->get_dir_ary();
	}

	private function get_dir_ary(){
		// Create recursive dir iterator which skips dot folders
		$dir2 = new RecursiveDirectoryIterator($this->dir,
			FilesystemIterator::SKIP_DOTS);
		// Flatten the recursive iterator, folders come before their files
		$it  = new RecursiveIteratorIterator($dir2,
			RecursiveIteratorIterator::SELF_FIRST);
		// Maximum depth is 1 level deeper than the base folder
		//$it->setMaxDepth(1);
		// Basic loop displaying different messages based on file or folder

		foreach ($it as $fileinfo) {
			$type = $fileinfo->isDir() ? 'dir' : 'file';
			$full_path = $this->dir.'/'.$it->getSubPath().'/'.$fileinfo->getFilename();
			if($type == 'file'){
				// get extension..
				$ex = explode('.', $fileinfo->getFilename());
				$t_e = count($ex);
				$ext = strtolower($ex[$t_e-1]);
				if(!in_array($ext, $this->Yohn['block_ext'])){ // no blocked file extensions..
					if(in_array($ext, array('gif', 'jpg', 'png'))){ // img extensions extra feed..
						$img = getimagesize($full_path);
						$ret2[$it->getSubPath()][$type][$fileinfo->getFilename()] = array(
							'name' => $fileinfo->getFilename(),
							'path' => $it->getSubPath(),
							'ext' => $ext,
							'created' => filectime($full_path),
							'modified' => filemtime($full_path),
							'filesize' => filesize($full_path),
							'width' => $img[0],
							'height' => $img[1],
							'mime' => $img['mime']
						);
					} else {
						$ret2[$it->getSubPath()][$type][$fileinfo->getFilename()] = array(
							'name' => $fileinfo->getFilename(),
							'path' => $it->getSubPath(),
							'ext' => $ext,
							'created' => filectime($full_path),
							'modified' => filemtime($full_path),
							'filesize' => filesize($full_path)
						);
					}
				}
			} else {
				$ret2[$it->getSubPath()][$type][$fileinfo->getFilename()] = array(
					'name' => $fileinfo->getFilename(),
					'path' => $it->getSubPath(),
					'created' => filectime($full_path),
					'modified' => filemtime($full_path)
				);
			}
			/*if ($fileinfo->isDir()) {
				printf("Folder From %s - %s\n", $it->getSubPath(), $fileinfo->getFilename());
					$ret['dir'][$it->getSubPath()][] = $fileinfo->getFilename();
				} elseif ($fileinfo->isFile()) {
					printf("File From %s - %s\n", $it->getSubPath(), $fileinfo->getFilename());
				$ret['file'][$it->getSubPath()][] = $fileinfo->getFilename();
			}*/
		}
		$this->tree = $ret2;
		return $ret2;
	}

	public function build_tree($path){
		// make sure trees built.
		if(isset($this->tree) && is_array($this->tree)){
			// make sure path is found
			if(isset($this->tree[$path])){
				$path_name = $path == '' ? '<a href="javascript:load_home(\'folder_\');">Home</a>' : $path;
				$ret = '<ul class="nav nav-list ul-dir" id="folder_'.str_replace('/', '_', $path).'">
				<li class="nav-header">'.$path_name.'</li>';
				// check if directories exist
				if(isset($this->tree[$path]['dir'])){
					// sort that shit..
					asort($this->tree[$path]['dir']);
					//echo '<pre>'.print_r($this->tree[$path]['dir'],1).'</pre>';
					foreach($this->tree[$path]['dir'] as $n => $ary){
						$ret .= '<li>
							<a class="dir" href="javascript:void(0);" data-path="'.$ary['path'].'" data-name="'.$ary['name'].'" data-created="'.$ary['created'].'" data-modified="'.$ary['modified'].'">
								<em class="icon-folder-close"></em>
								'.$n.'
							</a>';
						$dir_path = $path == '' ? $n : $path.'/'.$n;
						$ret .= $this->build_tree($dir_path);
						//<pre>'.print_r($ary,1).'</pre>';
							//$this->build_tree();
						$ret .= '</li>';
					}
				}

				// check if files exist
				if(isset($this->tree[$path]['file'])){
					asort($this->tree[$path]['file']);
					// file name to the array with evrythg
					foreach($this->tree[$path]['file'] as $n => $ary){
						if(in_array($ary['ext'], array('png', 'gif', 'jpg'))){
							$add = ' data-width="'.$ary['width'].'" data-height="'.$ary['height'].'"';
						} else {
							$add = '';
						}
						$ret .= '<li>
							<a class="file" href="javascript:void(0);" data-path="'.$this->Yohn['display_path'].$ary['path'].'" data-name="'.$ary['name'].'" data-ext="'.$ary['ext'].'" data-created="'.$ary['created'].'" data-modified="'.$ary['modified'].'" data-filesize="'.$ary['filesize'].'"'.$add.'>
								<img src="'.$this->icons($ary['ext']).'" alt="'.$ary['ext'].'">
								'.$n.'
							</a>
						</li>';
					}
				}
				return $ret.'</ul>';
			} else {
				return '<ul class="nav nav-list" id="folder_'.str_replace('/', '_', $path).'"><li class="nothing-found">Nothing found..</li></ul>';
			}
		} else {
			return 'You need to call browse() first.';
		}
	}

	public function icons($file){
		//$file = $file == 'woff' ? 'wff' : $file;
		$is = str_replace('[ext]', $file, $this->Yohn['icon_dir']);
		if(is_file($is)){
			return $is;
		} else {
			$is = str_replace('[ext]', 'question', $this->Yohn['icon_dir']);
			return $is;
		}
	}
}