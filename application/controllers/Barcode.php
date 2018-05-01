<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Barcode extends CI_Controller 
{
	function __construct()
	{
		parent::__construct();
    }

	function index()
	{
        $code = 'placeholder code';
        if(!empty($_GET['code']))
        {
            $code = $_GET['code'];
        }
        echo '<style>#qrbox>div{margin: auto;}</style>';
		echo gen_barcode($code,'html');		
	}
}