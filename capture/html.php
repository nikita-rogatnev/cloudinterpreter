<?php
/**
#
# file: html.php
# desc: Generate a Download-able HTML Document 'on-the-fly'
#
# example by: Sean O'Donnell
 */

/** the 'FileMan' Object is Required */
require 'fileman.php';

/** assign your html content to a variable */
$file['content'] = 'test content2';

/** generate the download-able document */ 
new FileMan("example.html",$file['content'],"text/html"); 
?>