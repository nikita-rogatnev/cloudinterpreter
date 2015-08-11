<?php
/**
 * @file fileman.php
 * @desc A File Management Object written in PHP5. This Object allows you to Generate a Download-able Document 'on-the-fly'.
 * @author Sean O'Donnell http://code.seanodonnell.com
 */
class FileMan {

    /** @var string File Name */
    private $file;

    /** @var string File Content/Data */
    private $content;

    /** @var string File Mime/Type */
    private $type;

    /** @var object File Stream */
    private $f_conn;

    /**
     * @desc Object Constructor
     * @param string File Name
     * @param string File Content
     * @param string File Mime/Type
     */
    public function FileMan($f_name,$f_content,$f_type) {
        $this->file = $f_name;
        $this->content = $f_content;
        $this->type = $f_type;
        $this->write_file();
        $this->download_file();
        return;
    }

    /** @desc write the $content to the $file */
    private function write_file() {
        $this->f_conn = @fopen($this->file,'w+');
        @fputs($this->f_conn,$this->content,strlen($this->content));
        @fclose($this->f_conn);
        return;
    }

    /** @desc prompt the user to download the $file */
    private function download_file() {
        header("Content-type: ".$this->type);
        header("Content-Disposition: attachment; filename=".$this->file);
        header("Content-Length: ".filesize($this->file));
        @readfile($this->file);
    }
}
?>