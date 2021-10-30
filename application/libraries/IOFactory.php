<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 *  ======================================= 
 *  Author     : Team Tech Arise 
 *  License    : Protected 
 *  Email      : info@techarise.com 
 * 
 *  ======================================= 
 */
// require_once APPPATH . "PHPExcel/IOFactory.php";
require_once ('PHPExcel/Classes/PHPExcel/IOFactory.php');

class IOFactory extends PHPExcel_IOFactory {
    public function __construct() {
        parent::__construct();
    }
}
?>