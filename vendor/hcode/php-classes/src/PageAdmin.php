<?php 
    namespace hcode;
    use hcode\Page;

    class PageAdmin extends Page {
        public function __construct($opts = array(), $tpL_dir = "/views/admin/")
        {
           parent::__construct($opts, $tpL_dir); 
        }
    }
?>