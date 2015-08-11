<?php
/**
#
# file: word.php
# desc: Generate a Download-able Microsoft Word Document 'on-the-fly'
#
# example by: Sean O'Donnell
 */

/** the 'FileMan' Object is Required */
require 'fileman.php';

/** assign your html content to a variable */
$file['content'] = 'test content';

/** generate the download-able document */ 
new FileMan("example.doc",$file['content'],"application/word"); 
?>