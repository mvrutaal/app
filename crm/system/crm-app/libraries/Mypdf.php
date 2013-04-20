<?php

// Width: 195

// Load TCPDF
require_once APPPATH.'libraries/tcpdf/config/lang/eng.php';
require_once APPPATH.'libraries/tcpdf/tcpdf.php';

// Extend the TCPDF class to create custom Header and Footer
class Mypdf extends TCPDF {

    //Page header
    public function Header()
    {
    	// Set font
        $this->SetFont('helvetica', 'B', 10);

        // Get Header Data
        $headerdata = $this->getHeaderData();

        // Title
        $this->Cell(210, 15, strtoupper($this->page_title), 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $this->Cell(0, 15, ci()->config->item('owner_short'), 0, false, 'R', 0, '', 0, false, 'M', 'M');
        $this->Image('@'.file_get_contents(FCPATH.'assets/logos/logo_crm_vertical.png'), 172, 1, 34, 0, 'PNG', '', '', false, 300, '', false, false, 0, false, false, false);
    }

    // Page footer
    public function Footer()
    {
        $this->SetFont('helvetica', 'B', 8);
        $this->Cell(65, 10, 'For internal use only', 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $this->Cell(65, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Cell(65, 10, date('d/M/Y'), 0, false, 'R', 0, '', 0, false, 'M', 'M');
    }
}