<?php defined ( 'BASEPATH' ) or exit ( 'No direct script access allowed' ); 

require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';

class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
    public function Header() {
        
        //if ($this->page == 1) {
        $image_file = K_PATH_IMAGES.'logo.jpg';
        $this->Image($image_file, 0, 10, 50, '', 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        // Set font
        $this->SetFont('helvetica', 'B', 14);
        // Title
        $ret =$this->getHeaderData();

        $this->setCellPaddings($left = '45', $top = '', $right = '', $bottom = '');
        
        $this->Cell(0, 8, $ret['title'], 0, false, 'L', 0, '', 0, false, 'M', 'M');
        $this->Ln(8,false);
        
        $this->SetFont('helvetica', 'B', 14);
        //$this->setCellPaddings( $left = '0', $top = '', $right = '', $bottom = '');
        $this->Cell(0, 8, $ret['string'], 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->Ln(8,false);
        $this->SetFont('helvetica', 'B', 12);
        
        //$this->Cell(0, 10, $text, 1, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->MultiCell(0, 5,$ret['text_color'], 0, 'C', 0, 0, '', '', true);
 }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        //$this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 10);
        $this->Cell(0, 10, 'This pay slip is electronically generated. Physical stamp &
signature not required.', 0, false, 'C', 0, '', 0, false, 'T', 'M');
        // Page number
        //$this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}
