<?php

App::import('Vendor', 'tcpdf/tcpdf');

class XTCPDF extends TCPDF {

    var $xheadercolor = array(0, 0, 0);
    var $xfooterfont = PDF_FONT_NAME_MAIN;
    var $xfooterfontsize = 10;

    private $isLandscape = false;
    private $htmlHeader = '';
    /**
     * Overwrites the default header 
     * set the text in the view using 
     *    $fpdf->xheadertext = 'YOUR ORGANIZATION'; 
     * set the fill color in the view using 
     *    $fpdf->xheadercolor = array(0,0,100); (r, g, b) 
     * set the font in the view using 
     *    $fpdf->setHeaderFont(array('YourFont','',fontsize)); 
     */
    function __construct($htmlHeader = '', $isLandscape=false) {
        parent::__construct();
        $this->htmlHeader = $htmlHeader;
        $this->isLandscape = $isLandscape;
        
    }

    function Header() {
        $this->_configExportPdf();
        // $urlImgSig = Router::url('/admin/img/marca-dashboard.png', true);
        $urlImgSig = Router::url('/web/img/marca-dashboard.png', true);
        $html = '
            <div id="container">
                <table border="0">
                    <tr>
                        <td align="center" style="font-size: 15px !important;font-weight: 600 !important;text-transform: uppercase;color: #114C76;"><br/><br/><br/>
                            <span>Sistema de Perícias Médicas</span>
                        </td>
                    </tr>
                </table>
            </div><hr>';
        if(!empty($this->htmlHeader)){
            $html =  $this->htmlHeader;
        }
        $this->writeHTML($html, true, false, true, false, '');
    }

    private function _configExportPdf() {
        if($this->isLandscape){
            $this->setPageOrientation('L');
        }else{
            $this->setPageOrientation('P');
        }
        $this->SetMargins(PDF_MARGIN_LEFT, 38, PDF_MARGIN_RIGHT);
    }

    /**
     * Overwrites the default footer 
     * set the text in the view using 
     * $fpdf->xfootertext = 'Copyright Â© %d YOUR ORGANIZATION. All rights reserved.'; 
     */
    function Footer() {
        date_default_timezone_set("America/Recife");
        $data = date('d-m-Y');
        $data2 = date("d-m-Y", strtotime($data));
        $hora = date('H:i:s');
        $year = date('Y');
        $this->SetY(-20);
        $this->SetTextColor(0, 0, 0);
        $this->SetFont($this->xfooterfont, '', $this->xfooterfontsize);
        $this->Cell(0, 8, ' Rua Henrique Dias, s/n – Derby - 52010-100 Recife/PE Fone: (81) 3183.4805 / 3183.4798  '.
            '       Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages() , 'T', 1, 'R');
        $this->Cell(0,8, 'Emitido em: '. $data2 . ' às ' . $hora ,'T', 1, 'C');

    }

}
