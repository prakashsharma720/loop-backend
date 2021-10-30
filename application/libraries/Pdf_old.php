<?php

defined('BASEPATH') OR exit('No direct script access allowed');
include_once APPPATH . '/third_party/mpdf/mpdf.php';

class Pdf {

    public $param;
    public $pdf;
    private $CI;
    private $heading;
    private $footer;
    private $html;
    private $filename;

    public function __construct($param = '"en-GB-x","A3","","",10,10,10,10,6,3') {
        $this->param = $param;
        $this->pdf = new mPDF($this->param);
    }

    public function generate($heading, $html) {
        $this->heading = $heading;
        $this->html = $html;
        $this->filename = $this->heading . '_' . date('Y-m-d-H-i-s') . '.pdf';

        $logo = '<img src="' . base_url('storage/image/logo.png') . '"  width="80px"/>';

        $this->pdf->SetHTMLHeader('
	    <table width="100%"><tr>
            <td width="33%">' . $logo . '</td>
            <td width="33%">' . humanize('_', ' ', $this->heading) . '</td>
            <td width="33%">{DATE j-m-Y}</td>
		</tr>
	    </table>
	    ', 'O');

        $this->pdf->SetHTMLFooter('
		<table width = "100%"><tr>
		<td width = "33%">{DATE j-m-Y}</td>
		<td width = "33%">{PAGENO}/{nbpg}</td>
		<td width = "33%">Sign</td>
		</tr></table>
			');


        $this->pdf->WriteHTML(file_get_contents(base_url('storage/plugins/bootstrap/bootstrap.min.css ')), 1);
        $this->pdf->WriteHTML($this->html);
        $this->pdf->Output($this->filename, "D");
    }

}
