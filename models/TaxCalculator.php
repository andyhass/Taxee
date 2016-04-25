<?php  if ( ! defined('CDN_URL')) exit('No direct script access allowed');
/**
*
*/

class TaxCalculatorModel {
    public $ssa_rate       = 0.062;
    public $medicare_rate  = 0.0145;
    public $supported_years = array(2014, 2015, 2016);
}