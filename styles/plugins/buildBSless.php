<?php

// we're just building the css files for the website using less so we can customize the bootstrap theming.
include('lessphp/lessc.inc.php');
$less = new lessc;
echo '<pre>'.$less->compileFile("bootstrap-2.3.2/less/bootstrap.less")."\r\n\r\n/* Bootstraps responsive.. */\n".$less->compileFile("bootstrap-2.3.2/less/responsive.less");
/* this way aint saving... maybe because of permissions on this folder or something?..
// defaults
$go = $less->checkedCompile("bootstrap-2.3.2/less/bootstrap.less", "vi-bootstrap.css");
// responsives
$go2 = $less->checkedCompile("bootstrap-2.3.2/less/responsive.less", "vi-bootstrap-responsive.css");
*/
?>