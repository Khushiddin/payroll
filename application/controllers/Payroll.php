<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);
if(!defined('BASEPATH')) exit('No direct script access allowed');
require APPPATH . '/libraries/BaseController.php';

class Payroll extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('payroll_model');
        $this->load->model('user_model');
        $this->isLoggedIn();

        $this->months = ['1'=>'January','2'=>'Feburary','3'=>'March','4'=>'April','5'=>'May','6'=>'June','7'=>'July','8'=>'August','9'=>'September','10'=>'October','11'=>'November','12'=>'December'];
        
        
        $this->years = [2018,2019,2020];    
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
     
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            
            $month = $this->input->post('month');
            $year = $this->input->post('year');

            if(empty($month)){
                $month = date('n');
            }
            if(empty($year)){
                $year = date('Y');
            }
            $data['month'] = $month;
            $data['year']  = $year;
            
            $userId = $this->vendorId;

            $data["months"] = $this->months;
            $data["years"] = $this->years;

            $data["userInfo"] = $this->user_model->getUserInfoById($userId);
                            
            $data['userRecords'] = $this->payroll_model->salarySlip($userId, $month, $year);
            
            $this->global['pageTitle'] = 'Salary Slip | ARYACOLLATERAL :';
            
            $this->loadViews("payroll/salarySlip", $this->global, $data, NULL);
        }        
    }

    public function download($payrollId){

        if($payrollId){

            $data = $this->payroll_model->userSalarySlip($payrollId);
            if(!empty($data)){
                foreach($data as $user){
                    $days = cal_days_in_month(CAL_GREGORIAN, $user->month, $user->year); 
                 // print_r($user);die();
                    if($days<$user->presentDays){
                        $this->session->set_flashdata('error', 'Present Days is greater than month days.');
                        redirect('/payroll/index');
                    }
                    ob_start();
                    $doj   = date('d-m-Y',strtotime($user->doj));
                    $month = $this->months[$user->month]; 
                    $year  = $user->year;
                    $naration = 'Pay Slip for '.$month.'-'.$year;
                    // $gross_structure = $user->basic+$user->transAllow+$user->spclAllow+$user->lta+$user->hra+$user->bonus;
                    // $pDays = $user->presentDays;
                    // $basic      = round($user->basic/$days*$pDays);
                    // $transAllow = round($user->transAllow/$days*$pDays);
                    // $spclAllow  = round($user->spclAllow/$days*$pDays);
                    // $lta        = round($user->lta/$days*$pDays);
                    // $hra        = round($user->hra/$days*$pDays);
                    // $bonus      = round($user->bonus/$days*$pDays);
                    // $gross = $basic+$transAllow+$spclAllow+$lta+$hra+$bonus;

                    

                    $gross_structure = $user->Tbasic+$user->TtransAllow+$user->spclAllow+$user->Tlta+$user->Thra+$user->Tbonus;
                    $pDays = $user->presentDays;
                    $basic      = round($user->Tbasic/$days*$pDays);
                    $transAllow  = round($user->TtransAllow/$days*$pDays);
                    $spclAllow  = round($user->TspclAllow/$days*$pDays);
                    $lta        = round($user->Tlta/$days*$pDays);
                    $hra        = round($user->Thra/$days*$pDays);
                    $bonus      = round($user->Tbonus/$days*$pDays);
                    $gross = $basic+$transAllow+$spclAllow+$lta+$hra+$bonus;
                      

                    /* Deduction */
                    $pf = round(($basic+$transAllow)*12/100);
                    if(strtotime("$user->year-$user->month-01")<=strtotime('2019-11-01')){
                    $A_gross_withoutBonus =$user->Tbasic+$user->TtransAllow+$user->TspclAllow+$user->Tlta+$user->Thra+$user->Tbonus;    
                    }else{
                    $A_gross_withoutBonus =$user->Tbasic+$user->TtransAllow+$user->TspclAllow+$user->Tlta+$user->Thra;
                    } 
                    

                    $A_gross = $basic+$transAllow+$spclAllow+$lta+$hra+$bonus;
            
                    $tds = $user->tds;
                    $advance = $user->advance_deduction;
                    $other = $user->other_deduction;
                    $PT = $user->PT;
                    $vpf = 0;

                    $A_epf = round((12/100)*($basic+$transAllow));
                    
                    if($A_gross_withoutBonus <= 21000){
                        $esic = ceil((0.75/100)*($A_gross+$user->incentive+$user->rimbersement));
                    }else{
                        $esic = 0;
                    }

                    /*Arrear add after ESI */
                    $A_gross = $A_gross+$user->arrear;
                    
                    $deduction = $A_epf+$esic+$user->tds+$user->PT+$user->advance_deduction+$user->arrear_pf+$user->arrear_esic+$user->other_deduction;
                    
                    $netPayment = $A_gross-$deduction+$user->incentive+$user->rimbersement;

                    $netPaymentInWord = $this->getindiancurrency($netPayment);

                    if($user->arrear){
                        $arrear = $user->arrear;
                    }else{ $arrear=0; }
                    if($user->rimbersement){
                        $reimb = $user->rimbersement;
                    }else { $reimb = 0;}

                    if($user->incentive){
                        $incent = $user->incentive;
                    }else { $incent = 0;}
                    if($user->arrear_pf){
                        $arPF = $user->arrear_pf;
                    }else { $arPF = 0;}
                    if($user->arrear_esic){
                        $arES = $user->arrear_esic;
                    }else { $arES = 0;}
                    

    $gst= '';
    if($transAllow>0){
      $gst = <<<EOD
                <tr>
                    <td>TRANS. ALLOWANCE</td>
                    <td align="right">{$transAllow}</td>
                    <td align="right">{$user->TtransAllow}</td>
                    <td align="right">0.00</td>
                    <td align="right">{$transAllow}</td>
                </tr>
                
EOD;
}else{
    $gst = <<<EOD
EOD;

}
                    $this->load->library('Pdf');
                    $pdf = new Pdf('P', 'mm', 'A4', true, 'UTF-8', false);
                    $pdf->SetTitle('Pay Slip');
                    //$pdf->SetHeaderData(PDF_HEADER_LOGO, 35, '','ARYADHAN FINANCIAL SOLUTIONS PVT. LTD.','H-82, Sector-63, Ground floor, Behind Ginger Hotel,Noida-201301 (U.P.)','');
                    $pdf->SetHeaderData(PDF_HEADER_LOGO, 40, '','ARYA COLLATERAL WAREHOUSING SERVICES PVT. LTD.','H-82, Sector-63, Ground floor, Behind Ginger Hotel,
             Noida-201301 (U.P.)','');
                    //$pdf->SetTopMargin(20);
                    $pdf->setFooterMargin(20);
                    $pdf->SetAutoPageBreak(true);
                    $pdf->SetAuthor('Aryadhan');
                    $pdf->SetDisplayMode('real', 'default');
                    $pdf->AddPage();
                    $pdf->Ln(35);
                    //$pdf->SetFont('helvetica', '',9);
                    $pdf->Cell(0, 8,$naration, 0, false, 'C', 0, '', 0, false, 'M', 'M');
                    $pdf->Ln(10,false);
                    $pdf->SetFont('helvetica', '',10);
$tbl = <<<EOD
<style>
    .dttab{
        border-style: dotted;
    }
    </style>
    <table cellspacing="0" cellpadding="5" border="1">
        <tr><td style="width:60%">
            <table border="0" cellpadding="2" cellspacing="0">
                <tr>
                    <td style="width:40%"><strong>Emp Code</strong></td>
                    <td style="width:60%"><strong>: </strong>{$user->empCode}</td>
                </tr>
                <tr>
                    <td><strong>Emp Name</strong></td>
                    <td><strong>: </strong>{$user->name}</td>
                </tr>
                <tr>
                    <td><strong>Department</strong></td>
                    <td><strong>: </strong>{$user->departmentName}</td>
                </tr>
                <tr>
                    <td><strong>Designation</strong></td>
                    <td><strong>: </strong>{$user->designationName}</td>
                </tr>
                <tr>
                    <td><strong>Grade</strong></td>
                    <td><strong>: </strong>NA</td>
                </tr>
                <tr>
                    <td><strong>DOJ</strong></td>
                    <td><strong>: </strong>{$doj}</td>
                </tr>
                <tr>
                    <td><strong>Payable Days</strong></td>
                    <td><strong>: </strong>{$user->presentDays}</td>
                </tr>   
            </table>
            </td>
            <td style="width:40%">
            <table border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td style="width:40%"><strong>Location</strong></td>
                    <td style="width:60%"><strong>: </strong>{$user->location} </td>
                </tr>
                <tr>
                    <td style="width:40%"><strong>IFSC Code</strong></td>
                    <td style="width:60%"><strong>: </strong>{$user->ifscCode} </td>
                </tr>
                <tr>
                    <td style="width:40%"><strong>Bank A/C No.</strong></td>
                    <td style="width:60%"><strong>: </strong>{$user->acNum} </td>
                </tr>
                <tr>
                    <td style="width:40%"><strong>PAN</strong></td>
                    <td style="width:60%"><strong>: </strong>{$user->panNo} </td>
                </tr>
                <tr>
                    <td style="width:40%"><strong>PF UAN</strong></td>
                    <td style="width:60%"><strong>: </strong>{$user->pfuan} </td>
                </tr>
                <tr>
                    <td style="width:40%"><strong>Aadhar Card</strong></td>
                    <td style="width:60%"><strong>: </strong>{$user->aadhar} </td>
                </tr>    
            </table>
            </td>
        </tr>
        <tr>
            <td align="center"><strong>Earnings</strong></td>
            <td align="center"><strong>Deductions</strong></td>
        </tr>
        <tr>
            <td style="width:60%;font-size:10px;" >
                <table border="0" width="100%" cellpadding="2" cellspacing="0">
                    <tr>
                        <td style="width:40%"><strong>Description</strong></td>
                        <td style="width:15%" align="center"><strong>Rate</strong></td>
                        <td style="width:15%" align="center"><strong>Monthly</strong></td>
                        <td style="width:15%" align="center"><strong>Arrear</strong></td>
                        <td style="width:15%" align="center"><strong>Total</strong></td>       
                    </tr>
                    <tr>
                        <td>BASIC</td>
                        <td align="right">{$basic}</td>
                        <td align="right">{$user->Tbasic}</td>
                        <td align="right">0.00</td>
                        <td align="right">{$basic}</td>       
                    </tr>
                    <tr>
                        <td>HRA</td>
                        <td align="right">{$hra}</td>
                        <td align="right">{$user->Thra}</td>
                        <td align="right">0.00</td>
                        <td align="right">{$hra}</td>       
                    </tr>
                    {$gst}
                    <tr>
                        <td>SPECIAL ALLOWANCE</td>
                        <td align="right">{$spclAllow}</td>
                        <td align="right">{$user->TspclAllow}</td>
                        <td align="right">0.00</td>
                        <td align="right">{$spclAllow}</td>       
                    </tr>
                    <tr>
                        <td>LTA</td>
                        <td align="right">{$lta}</td>
                        <td align="right">{$user->Tlta}</td>
                        <td align="right">0.00</td>
                        <td align="right">{$lta}</td>       
                    </tr>
                    
                    <tr>
                        <td>BONUS</td>
                        <td align="right">{$bonus}</td>
                        <td align="right">{$user->Tbonus}</td>
                        <td align="right">0.00</td>
                        <td align="right">{$bonus}</td>       
                    </tr>
                    <tr>
                        <td>ARREAR</td>
                        <td align="right"></td>
                        <td align="right"></td>
                        <td align="right"></td>
                        <td align="right">{$arrear}</td>       
                    </tr>
                    <tr>
                        <td>REIMBURSEMENT</td>
                        <td align="right"></td>
                        <td align="right"></td>
                        <td align="right"></td>
                        <td align="right">{$reimb}</td>       
                    </tr>
                    <tr>
                        <td>INCENTIVE</td>
                        <td align="right"></td>
                        <td align="right"></td>
                        <td align="right"></td>
                        <td align="right">{$incent}</td>       
                    </tr>
                    <tr>
                        <td class="dttab"><strong>GROSS EARNING</strong></td>
                        <td class="dttab" align="right"><strong>{$gross}</strong></td>
                        <td class="dttab" align="right"><strong>{$gross_structure}</strong></td>
                        <td class="dttab" align="right"><strong>0.00</strong></td>
                        <td class="dttab" align="right"><strong>{$A_gross}</strong></td>       
                    </tr>
                    

                </table>
            </td>
            <td style="width:40%;font-size:10px;">
            <table border="0" cellpadding="2" cellspacing="0">
                    <tr>
                        <td style="width:60%"><strong>Description</strong></td>
                        <td style="width:40%" align="center"><strong>Amount</strong></td>
                    </tr>
                    <tr>
                        <td>PF</td>
                        <td align="right">{$pf}</td>
                    </tr>
                    <tr>
                        <td>ESIC</td>
                        <td align="right">{$esic}</td>
                    </tr>
                    <tr>
                        <td>TDS</td>
                        <td align="right">{$tds}</td>
                    </tr>
                    <tr>
                        <td>ADVANCE</td>
                        <td align="right">{$advance}</td>
                    </tr>
                    <tr>
                        <td>OTHER</td>
                        <td align="right">{$other}</td>
                    </tr>
                    <tr>
                        <td>PT</td>
                        <td align="right">{$PT}</td>
                    </tr>
                    <tr>
                        <td>Arrear PF</td>
                        <td align="right">{$arPF}</td>
                    </tr>
                    <tr>
                        <td>Arrear ESIC</td>
                        <td align="right">{$arES}</td>
                    </tr>
                    <tr>
                        <td class="dttab"><strong>GROSS DEDUCTIONS</strong></td>
                        <td class="dttab" align="right">{$deduction}</td>
                    </tr>

                </table>
                </td>
        </tr>
        <tr>
            <td align="center" colspan="2">
                    <h4>Net Pay : {$netPayment} ({$netPaymentInWord})</h4>
            </td>
        </tr>

    </table>            
EOD;
        $pdf->writeHTML($tbl, true, false, false, false, '');
        //$pdf->Write(5, 'CodeIgniter TCPDF Integration');
        $pdf->Output($user->name.'-'.$this->months[$user->month].'.pdf', 'D');   
        ob_end_flush();
exit;
                }
            }
            exit;
        }
        
    }
    
    /**
     * Page not found : error 404
     */
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }


    function getindiancurrency($number)
    {
    
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $hundred = null;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(0 => '', 1 => 'One', 2 => 'Two',
        3 => 'Three', 4 => 'Four', 5 => 'Five', 6 => 'Six',
        7 => 'Seven', 8 => 'Eight', 9 => 'Nine',
        10 => 'Ten', 11 => 'Eleven', 12 => 'Twelve',
        13 => 'Thirteen', 14 => 'Fourteen', 15 => 'Fifteen',
        16 => 'Sixteen', 17 => 'Seventeen', 18 => 'Eighteen',
        19 => 'Nineteen', 20 => 'Twenty', 30 => 'Thirty',
        40 => 'Forty', 50 => 'Fifty', 60 => 'Sixty',
        70 => 'Seventy', 80 => 'Eighty', 90 => 'Ninety');
    
    $digits = array('', 'Hundred','Thousand','Lakh', 'Crore');
    while( $i < $digits_length ) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
            $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
        } else $str[] = null;
    }
    $Rupees = implode('', array_reverse($str));
    //$paise = ($decimal) ? "." . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' Paise' : '';
    //return  ($Rupees ? $Rupees . 'Rupees ' : '') . $paise .;
    return  ($Rupees ? $Rupees . 'Rupees ' : '');
    }

    function employeeListing(){
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->user_model->employeeListingCount($searchText);

            $returns = $this->paginationCompress ( "employeeListing/", $count, 1000 );
            
            $month = $this->input->post('month');
            $year = $this->input->post('year');

            if(empty($month)){
                $month = date('n');
            }
            if(empty($year)){
                $year = date('Y');
            }
            $data['month'] = $month;
            $data['year']  = $year;

            $data["months"] = $this->months;
            $data["years"] = $this->years;
            // print_r($year);
            $data['userRecords'] = $this->user_model->employeeListing($searchText, $returns["page"], $returns["segment"], $month, $year);
            
            $this->global['pageTitle'] = 'Arya : Employee Listing';
            
            $this->loadViews("payroll/employees", $this->global, $data, NULL);
        }

    }
}

?>
