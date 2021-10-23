<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';
error_reporting(0);
/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Expenses extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('expenses_model');
        $this->load->helper('url');
        $this->isLoggedIn();
        $this->load->helper(array('form', 'url'));
        $this->load->helper('file');
        $this->months = ['01'=>'January','02'=>'Feburary','03'=>'March','04'=>'April','05'=>'May','06'=>'June','07'=>'July','08'=>'August','09'=>'September','10'=>'October','11'=>'November','12'=>'December'];  
        $this->year = ['2018','2019','2020','2021']; 
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index($type=null)
    {
        if(empty($type)){
            $type = 0;
        }

        $searchText = $this->security->xss_clean($this->input->post('searchText'));
            
        $data['searchText'] = $searchText;

        $data['type'] = $type;
        
        $data['expensesTotal'] = $this->expenses_model->getExpensesTotal(); 
        $data['pendingCountt'] = $this->expenses_model->getPendingExp();

        $this->global['pageTitle'] = 'Arya Collateral : Expenses';
        
        //$this->load->view('expenses/expenses');
        $this->loadViews("expenses/expenses", $this->global, $data , NULL);
    }

    public function expensesList($type=null){
    // POST data
    $postData = $this->input->post();

    $role = $this->session->userdata('role');
    $empRole = $this->session->userdata('empRole');
    $userarray =  explode(',', $empRole);
    if($type){
        $approveType = $type;
    }else{

        if(in_array(2, $userarray)){
            $approveType = '0';
        }else if(in_array(3, $userarray)){
            $approveType = '1'; 
        }else if(in_array(4, $userarray)){
            $approveType = '2'; 
        }
        /*if($role==3){
            $approveType = '0';
        }else if($role==2){
            $approveType = '1';
        }else if($role==4){
            $approveType = '2';
        }*/       
    }
    
    
    $postData['approveType'] = $approveType;          

    // Get data
    $data = $this->expenses_model->getExpenses($postData);

    echo json_encode($data);
  }
    
    /**
     * This function is used to load the user list
     */
    function expensesListing()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {        
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->expenses_model->expensesListingCount($searchText);

			$returns = $this->paginationCompress ( "expensesListing/", $count, 10 );
            
            $data['expensesRecords'] = $this->expenses_model->expensesListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Arya Collateral : Expenses Listing';
            
            $this->loadViews("expenses/expenses", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addNewExpenses()
    {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('user_model');
    
            $userId = $this->session->userdata('userId');
            
            $data['userInfo'] = $this->user_model->getUserInfo($userId);
            
            $data['field'] = $this->expenses_model->getField();

            $data['month'] = $this->months;

            $data['year'] = $this->year;

            $monthId = $this->input->post('month');

            $year = $this->input->post('year');

            
            if($monthId & $year){
                
                $pdate = $year.'-'.$monthId.'-01';
            
                $startDate = date('Y-m-01',strtotime($pdate));
            
                $endDate   = date('Y-m-t',strtotime($pdate));

                $data['cmon'] = $monthId;
                 
                $data['cyear'] = $year;
             
            }else{
                
                $startDate = date('Y-m-01');
                $endDate   = date('Y-m-t');
                $data['cmon'] = date('m');
                $data['cyear'] = date('Y');    
            }
            
            while (strtotime($startDate) <= strtotime($endDate)){

            $dates[] = $startDate;
                
            $startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));       
            
            }

            $data['dates'] = $dates;
              
            $this->global['pageTitle'] = 'Arya Collateral : Add New Expenses';

            $this->loadViews("expenses/addNewExpenses", $this->global, $data, NULL);
        }
    }
    

    function addNewExpensesSave()
    {
        if($this->isAdmin() == FALSE)
        {       
            $this->loadThis();
        }
        else
        {
            //$this->load->library('form_validation');
            
            //$this->form_validation->set_rules('makerAmount','Expenses Req. Amount','trim|required|numeric');
            //$this->form_validation->set_rules('makerRemark','Remarks','trim|required');
            
            //if($this->form_validation->run() == FALSE)
            //{
               // $this->addNewExpenses();
            //}
            //else
            //{
                $monthNum  = $this->security->xss_clean($this->input->post('expMonth'));
                $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                $monthName = $dateObj->format('F');
                $userId = $this->security->xss_clean($this->input->post('userId'));
                $entry_date = $this->security->xss_clean($this->input->post('entry_date'));
                $location = $this->security->xss_clean($this->input->post('location'));
                $description = $this->security->xss_clean($this->input->post('description'));
                $travel = $this->security->xss_clean($this->input->post('travel'));
                $mobile_exp = $this->security->xss_clean($this->input->post('mobile_exp'));
                $fooding = $this->security->xss_clean($this->input->post('fooding'));
                $lodging = $this->security->xss_clean($this->input->post('lodging'));
                $local_conv = $this->security->xss_clean($this->input->post('local_conv'));
                $printing = $this->security->xss_clean($this->input->post('printing'));
                $courier = $this->security->xss_clean($this->input->post('courier'));
                $labour_charge = $this->security->xss_clean($this->input->post('labour_charge'));
                $dunnage = $this->security->xss_clean($this->input->post('dunnage'));
                $wh_cleaning = $this->security->xss_clean($this->input->post('wh_cleaning'));
                $lock_key = $this->security->xss_clean($this->input->post('lock_key'));
                
                //File Upload
                $fileName="";
                $config['upload_path']          = 'expenses/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 0;
                $config['max_width']            = 0;
                $config['max_height']           = 0;
                
                $this->load->library('upload', $config);
                
                if(!$this->upload->do_upload('userfile')){
                    
                    $error = array('error' => $this->upload->display_errors());
                    $this->session->set_flashdata('error', $error);
                }else{
                    $imageInfo = $this->upload->data();
                    $fileName = $imageInfo['file_name'];
                }

                
                //local convence
                $l_entry_date = $this->security->xss_clean($this->input->post('l_entry_date'));
                $l_location  = $this->security->xss_clean($this->input->post('l_location'));
                $from  = $this->security->xss_clean($this->input->post('from'));
                $to  = $this->security->xss_clean($this->input->post('to'));
                $km  = $this->security->xss_clean($this->input->post('km'));
                $transport = $this->security->xss_clean($this->input->post('transport'));
                $amount = $this->security->xss_clean($this->input->post('amount'));

                $travelSum = array_sum($travel);
                $mobileSum = array_sum($mobile_exp);
                $foodingSum = array_sum($fooding);
                $lodgingSum = array_sum($lodging);
                $localSum = array_sum($local_conv);
                $printingSum = array_sum($printing);
                $courierSum = array_sum($courier);
                $labourSum = array_sum($labour_charge);
                $dunnageSum = array_sum($dunnage);
                $cleaningSum = array_sum($wh_cleaning);
                $lockSum = array_sum($lock_key);
                $makerAmount =$travelSum+$mobileSum+$foodingSum+$lodgingSum+$localSum+$printingSum+$courierSum+$labourSum+$dunnageSum+$cleaningSum+$lockSum;

                //local amount
                $localAmount = array_sum($amount);
                
                $makerAmount = $makerAmount+$localAmount; 
                
                if($makerAmount>0){
                    
                    $userInfo = array('userId'=>$userId,'expense_month'=>$monthName, 'makerAmount'=>$makerAmount,'makerRemark'=>'','approved'=>'0','fileName'=>$fileName, 'createdDtm'=>date('Y-m-d H:i:s'));

                    $this->load->model('expenses_model');
                
                    $result = $this->expenses_model->addNewExpenses($userInfo);

                    if(!empty($location)){
                    
                    foreach($location as $k=>$rec){
                        if($rec){
                        $userInfo = array('expenses_id'=>$result, 'entry_date'=>date('Y-m-d',strtotime($entry_date[$k])),'location'=>$rec, 'description'=>$description[$k], 'travel'=>$travel[$k], 'mobile_exp'=>$mobile_exp[$k],'fooding'=>$fooding[$k],'lodging'=>$lodging[$k],'local_conv'=>$local_conv[$k],'printing'=>$printing[$k],'courier'=>$courier[$k], 'labour_charge'=>$labour_charge[$k],'dunnage'=>$dunnage[$k],'wh_cleaning'=>$wh_cleaning[$k],'lock_key'=>$lock_key[$k], 'createdDtm'=>date('Y-m-d H:i:s'));

                            $this->load->model('expenses_model');
                            
                            $result2 = $this->expenses_model->addNewExpensesDetail($userInfo);

                             }   
                        }
                   }

                   if(!empty($l_location)){
                    foreach($l_location as $k=>$res){
                        if($res){
                        $userInfo = array('expenses_id'=>$result, 'entry_date'=>date('Y-m-d',strtotime($l_entry_date[$k])),'location'=>$res, 'dis_from'=>$from[$k], 'dis_to'=>$to[$k],'km'=>$km[$k],'transport'=>$transport[$k], 'amount'=>$amount[$k],'createdDtm'=>date('Y-m-d H:i:s'));

                            $this->load->model('expenses_model');
                            
                            $result3 = $this->expenses_model->addNewExpensesLocal($userInfo);                            
                        }
                    }
                   }

                }    
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Expenses created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'expenses creation failed');
                }
                
                redirect('exListing');
            //}
        }
    }

    
    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOld($adId = NULL)
    {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            if($adId == null)
            {
                redirect('exListing');
            }

            $this->load->model('user_model');
            $this->load->model('advance_model');
            
            $userId = $this->session->userdata('userId');
            
            $data['userInfo'] = $this->user_model->getUserInfo($userId);
            
            $data['field'] = $this->expenses_model->getField();

            $data['expensesInfo'] = $this->expenses_model->getExpensesInfo($adId);

            // if($data['expensesInfo'][0]->entry_date){
            // $startDate = $data['expensesInfo'][0]->entry_date;
            // $endDate   = date('Y-m-t',strtotime($startDate));
            // }else{
            // $startDate = date('Y-m-01');
            // $endDate   = date('Y-m-t');
            // }

            // if(!empty($data['expensesInfo'][50][0]->local_entry)){
            // $startDate2 = $data['expensesInfo'][50][0]->local_entry;
            // $endDate2   = date('Y-m-t',strtotime($startDate2));
            // }else{
            // $startDate2 = date('Y-m-01');
            // $endDate2   = date('Y-m-t');
            // }


            
            // while (strtotime($startDate) <= strtotime($endDate)){

            // $dates[] = $startDate;
                
            // $startDate = date ("Y-m-d", strtotime("+1 day", strtotime($startDate)));       
            
            // }

            // while (strtotime($startDate2) <= strtotime($endDate2)){

            // $local_dates[] = $startDate2;
                
            // $startDate2 = date ("Y-m-d", strtotime("+1 day", strtotime($startDate2)));       
            
            // }
            if($data['expensesInfo'][0]->entry_date){
            $startDate = $data['expensesInfo'][0]->entry_date;
            $st   = date('Y-m-01',strtotime($startDate));
            $endDate   = date('Y-m-t',strtotime($startDate));
            }else if(!empty($data['expensesInfo'][50][0]->local_entry)){
            $startDate = $data['expensesInfo'][50][0]->local_entry;
            $st   = date('Y-m-01',strtotime($startDate));
            $endDate   = date('Y-m-t',strtotime($startDate));
            }else{
            $st = date('Y-m-01');
            $startDate = date('Y-m-01');
            $endDate   = date('Y-m-t');
            }
            
            while (strtotime($st) <= strtotime($endDate)){

            $dates[] = $st;
                
            $st = date ("Y-m-d", strtotime("+1 day", strtotime($st)));       
            
            }

            $data['dates'] = $dates;
            // $data['local_dates'] = $local_dates;
            $advMonth = date ("m", strtotime($data['dates'][0]));
            $advYear = date ("Y", strtotime($data['dates'][0]));

            $dataadvance = $this->advance_model->getAdvanceInfoByMonth($data['expensesInfo'][0]->userId,$advMonth,$advYear);
            
            $data['dataadvance'] = $dataadvance;
            $advTotal = 0;
            foreach ($dataadvance as $key => $value) {
                $advTotal = $advTotal+$value->checkerAmount;
            }
            // print_r($dataadvance);die();
            $data['advTotal'] = $advTotal;
            
            $this->global['pageTitle'] = 'Arya Collateral : Edit expenses';
            
            $this->loadViews("expenses/editOld", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editExpenses()
    {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            //$this->load->library('form_validation');
            
            //$id = $this->input->post('id');
            
            //$this->form_validation->set_rules('makerAmount','Request Amount','trim|required|numeric');
            
            //$this->form_validation->set_rules('makerRemark','Maker Remark','required');
            
            //if($this->form_validation->run() == FALSE)
            //{
            //    $this->editOld($id);
            //}
            //else
            //{
                $id = $this->security->xss_clean($this->input->post('id'));
                $userId = $this->security->xss_clean($this->input->post('userId'));
                $entry_date = $this->security->xss_clean($this->input->post('entry_date'));
                $location = $this->security->xss_clean($this->input->post('location'));
                $description = $this->security->xss_clean($this->input->post('description'));
                $travel = $this->security->xss_clean($this->input->post('travel'));
                $mobile_exp = $this->security->xss_clean($this->input->post('mobile_exp'));
                $fooding = $this->security->xss_clean($this->input->post('fooding'));
                $lodging = $this->security->xss_clean($this->input->post('lodging'));
                $local_conv = $this->security->xss_clean($this->input->post('local_conv'));
                $printing = $this->security->xss_clean($this->input->post('printing'));
                $courier = $this->security->xss_clean($this->input->post('courier'));
                $labour_charge = $this->security->xss_clean($this->input->post('labour_charge'));
                $dunnage = $this->security->xss_clean($this->input->post('dunnage'));
                $wh_cleaning = $this->security->xss_clean($this->input->post('wh_cleaning'));
                $lock_key = $this->security->xss_clean($this->input->post('lock_key'));
                $detail_id = $this->security->xss_clean($this->input->post('detail_id'));
                $remark = $this->security->xss_clean($this->input->post('accountRemark'));
                $fileName = $this->security->xss_clean($this->input->post('userfile'));

                //FILE UPLOAD
                $config['upload_path']          = 'expenses/';
                $config['allowed_types']        = 'gif|jpg|png';
                $config['max_size']             = 0;
                $config['max_width']            = 0;
                $config['max_height']           = 0;
                
                $this->load->library('upload', $config);
                
                if(!$this->upload->do_upload('userfile')){
                    //$error = array('error' => $this->upload->display_errors());
                    //$this->session->set_flashdata('error', $error);
                }else{
                    $imageInfo = $this->upload->data();
                    $fileName = $imageInfo['file_name'];
                }


                //local convence
                $l_entry_date = $this->security->xss_clean($this->input->post('l_entry_date'));
                $l_location  = $this->security->xss_clean($this->input->post('l_location'));
                $from  = $this->security->xss_clean($this->input->post('from'));
                $to  = $this->security->xss_clean($this->input->post('to'));
                $km  = $this->security->xss_clean($this->input->post('km'));
                $transport = $this->security->xss_clean($this->input->post('transport'));
                $amount = $this->security->xss_clean($this->input->post('amount'));
                $local_id = $this->security->xss_clean($this->input->post('local_id'));

                $travelSum = array_sum($travel);
                $mobileSum = array_sum($mobile_exp);
                $foodingSum = array_sum($fooding);
                $lodgingSum = array_sum($lodging);
                $localSum = array_sum($local_conv);
                $printingSum = array_sum($printing);
                $courierSum = array_sum($courier);
                $labourSum = array_sum($labour_charge);
                $dunnageSum = array_sum($dunnage);
                $cleaningSum = array_sum($wh_cleaning);
                $lockSum = array_sum($lock_key);
                $makerAmount =$travelSum+$mobileSum+$foodingSum+$lodgingSum+$localSum+$printingSum+$courierSum+$labourSum+$dunnageSum+$cleaningSum+$lockSum;

                //local amount
                $localAmount = array_sum($amount);
                
                $makerAmount = $makerAmount+$localAmount; 

                
                $userInfo = array();

                $role = $this->session->userdata('role');
                // $userId = $this->session->userdata('userId');
                $checkerAmount=0; $accountRemark=''; $checkerRemark=''; $approvedBy=0;  
                $empRole = $this->session->userdata('empRole');
                $currentUser = $this->session->userdata('userId');
                $userarray =  explode(',', $empRole); 
//                 if(in_array(4, $userarray) && $currentUser != $userId){
//                     $approved = '4'; 
//                     $accountRemark = $remark;
//                     $approvedBy = $currentUser; 
//                     $checkerAmount = $makerAmount;
//                     $userInfo = array('checkerAmount'=>$checkerAmount,'accountRemark'=>$accountRemark, 'approved'=>$approved, 'updatedDtm'=>date('Y-m-d H:i:s'));
//                 }else if(in_array(3, $userarray) && $currentUser != $userId){
//                     $approved = '2';
//                     $checkerAmount = $makerAmount;
//                     $checkerRemark = $remark;
//                     $approvedBy = $currentUser;
//                     $userInfo = array('checkerAmount'=>$checkerAmount,'checkerRemark'=>$checkerRemark, 'approved'=>$approved, 'approvedBy'=>$approvedBy, 'updatedDtm'=>date('Y-m-d H:i:s'));

//                 }else if(in_array(2, $userarray)){
//                     $approved = '0';
//                     $userInfo = array('makerAmount'=>$makerAmount,'fileName'=>$fileName, 'approved'=>$approved, 'updatedDtm'=>date('Y-m-d H:i:s'));
//                 }          
// // print_r($userInfo);die();
                
//                 $this->load->model('expenses_model');
                
//                 $result = $this->expenses_model->editExpenses($userInfo, $id);
                if(empty($_POST['EditSave']))
            {
                if(in_array(4, $userarray) && $currentUser != $userId){
                    $approved = '4'; 
                    $accountRemark = $remark;
                    $approvedBy = $currentUser; 
                    $checkerAmount = $makerAmount;
                    $userInfo = array('checkerAmount'=>$checkerAmount,'accountRemark'=>$accountRemark, 'finance_approved_date'=>date('Y-m-d H:i:s'), 'approved'=>$approved, 'updatedDtm'=>date('Y-m-d H:i:s'));
                }else if(in_array(3, $userarray) && $currentUser != $userId){
                    $approved = '2';
                    $checkerAmount = $makerAmount;
                    $checkerRemark = $remark;
                    $approvedBy = $currentUser;
                    $userInfo = array('checkerAmount'=>$checkerAmount,'checkerRemark'=>$checkerRemark, 'approved'=>$approved, 'checker_approved_date'=>date('Y-m-d H:i:s'), 'approvedBy'=>$approvedBy, 'updatedDtm'=>date('Y-m-d H:i:s'));

                }else if(in_array(2, $userarray)){
                    $approved = '0';
                    $userInfo = array('makerAmount'=>$makerAmount,'fileName'=>$fileName, 'approved'=>$approved,'updatedDtm'=>date('Y-m-d H:i:s'));
                }      

                $this->load->model('expenses_model');
                
                $result = $this->expenses_model->editExpenses($userInfo, $id);
            } 
                
                if(!empty($location)){
                    
                    foreach($location as $k=>$rec){
                        if($rec){
                        $userInfo = array('expenses_id'=>$id, 'entry_date'=>date('Y-m-d',strtotime($entry_date[$k])),'location'=>$rec, 'description'=>$description[$k], 'travel'=>$travel[$k], 'mobile_exp'=>$mobile_exp[$k],'fooding'=>$fooding[$k],'lodging'=>$lodging[$k],'local_conv'=>$local_conv[$k],'printing'=>$printing[$k],'courier'=>$courier[$k], 'labour_charge'=>$labour_charge[$k],'dunnage'=>$dunnage[$k],'wh_cleaning'=>$wh_cleaning[$k],'lock_key'=>$lock_key[$k],'updatedDtm'=>date('Y-m-d H:i:s'));

                            $this->load->model('expenses_model');
                            
                            if($detail_id[$k]){
                                $result2 = $this->expenses_model->editExpensesDetails($userInfo,$detail_id[$k]);
                            }else{
                                $result2 = $this->expenses_model->addNewExpensesDetail($userInfo);
                            }

                        }   
                    }
                }

                if(!empty($l_location)){
                    foreach($l_location as $k=>$res){
                        if($res){
                        $userInfo = array('expenses_id'=>$id, 'entry_date'=>date('Y-m-d',strtotime($l_entry_date[$k])),'location'=>$res, 'dis_from'=>$from[$k], 'dis_to'=>$to[$k],'km'=>$km[$k],'transport'=>$transport[$k], 'amount'=>$amount[$k],'updatedDtm'=>date('Y-m-d H:i:s'));

                            $this->load->model('expenses_model');

                            if($local_id[$k]){
                                $result3 = $this->expenses_model->editExpensesLocals($userInfo,$local_id[$k]);
                            }else{
                                $result3 = $this->expenses_model->addNewExpensesLocal($userInfo);
                            }
                            //$result3 = $this->expenses_model->addNewExpensesLocal($userInfo);                            
                        }
                    }
                }

                if($result == true)
                {
                    $this->session->set_flashdata('success', 'expenses updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'expenses updation failed');
                }
                
                redirect('exListing');
            //}
        }
    }


    function checkexpenses()
    {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $id = $this->input->post('id');
            
            $this->form_validation->set_rules('checkerAmount','Approved Amount','trim|required|numeric');
            $this->form_validation->set_rules('checkerRemark','Checker Remark','required');
            if($this->form_validation->run() == FALSE)
            {
                $this->viewexpenses($id);
            }
            else
            {
                $checkerAmount = $this->security->xss_clean($this->input->post('checkerAmount'));
                $checkerRemark = $this->security->xss_clean($this->input->post('checkerRemark'));
                $approvedId = $this->session->userdata('userId');
                $userInfo = array();
                
                    $userInfo = array('checkerAmount'=>$checkerAmount, 'checkerRemark'=>$checkerRemark,'approvedBy'=>$approvedId,'approved'=>'2','updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->expenses_model->editexpenses($userInfo, $id);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'expenses updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'expenses updation failed');
                }
                
                redirect('exListing');
            }
        }
    }


    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $userId = $this->input->post('userId');
            $userInfo = array('isDeleted'=>1,'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->user_model->deleteUser($userId, $userInfo);
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
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

    /**
     * This function used to show login history
     * @param number $userId : This is user id
     */
        /**
     * This function is used to show users profile
     */
    function profile($active = "details")
    {
        $data["userInfo"] = $this->user_model->getUserInfoWithRole($this->vendorId);
        $data["active"] = $active;
        
        $this->global['pageTitle'] = $active == "details" ? 'Arya Collateral : My Profile' : 'Arya Collateral : Change Password';
        $this->loadViews("profile", $this->global, $data, NULL);
    }

    /**
     * This function is used to update the user details
     * @param text $active : This is flag to set the active tab
     */
    
    /**
     * This function is used to change the password of the user
     * @param text $active : This is flag to set the active tab
     */
    
    /**
     * This function is used to check whether email already exist or not
     * @param {string} $email : This is users email
     */
    function emailExists($email)
    {
        $userId = $this->vendorId;
        $return = false;

        if(empty($userId)){
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if(empty($result)){ $return = true; }
        else {
            $this->form_validation->set_message('emailExists', 'The {field} already taken');
            $return = false;
        }

        return $return;
    }


    function getlocation()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $stateId = $this->input->post('stateId');
            $result = $this->warehouse_model->getlocation($stateId);
            if ($result > 0) { echo(json_encode(array('status'=>TRUE,'data'=>$result))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }

    function ajaxapprove(){

        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $remark = $this->input->post('accRemark');
        $userInfo = array();
        $approvedId = $this->session->userdata('userId');
        $role = $this->session->userdata('role');
        $accountRemark =''; $checkerRemark ='';
        if($role=='4'){
            $accountRemark = $remark;
        }else if($role=='2'){
            $checkerRemark = $remark;
        }
        if($status=='reject'){
          $userInfo = array('approved'=>'3','approvedBy'=>$approvedId,'checkerRemark'=>$checkerRemark, 'accountRemark'=>$accountRemark, 'finance_approved_date'=>date('Y-m-d H:i:s'),'updatedDtm'=>date('Y-m-d H:i:s'));
        }else if($status=='approved'){
            $userInfo = array('approved'=>'4', 'updatedDtm'=>date('Y-m-d H:i:s'),'finance_approved_date'=>date('Y-m-d H:i:s'));
        }else if($status==1){
            
            $checkerAmount = $this->expenses_model->getMakerAmount($id);
            $checkAmount = $checkerAmount[0]->makerAmount; 
            
            $userInfo = array('approved'=>'2','checkerAmount'=>$checkAmount,'approvedBy'=>$approvedId, 'updatedDtm'=>date('Y-m-d H:i:s'),'checker_approved_date'=>date('Y-m-d H:i:s'));
        }else{
            $userInfo = array('approved'=>'1', 'updatedDtm'=>date('Y-m-d H:i:s'));
        }
        $result = $this->expenses_model->editExpenses($userInfo, $id);
        if($result){
            echo json_encode(array('value'=>'Success'));die;
        }else{
            echo json_encode(array('value'=>'Error'));die;
        }        
    }

    function viewexpenses($id){
        
        if($id){

        $data['field'] = $this->expenses_model->getField();

        $data['expensesInfo'] = $this->expenses_model->getexpensesInfo($id);
        
        $this->load->model('advance_model');
        $advMonth = date ("m", strtotime($data['expensesInfo'][0]->entry_date));
        $advYear = date ("Y", strtotime($data['expensesInfo'][0]->entry_date));

        $dataadvance = $this->advance_model->getAdvanceInfoByMonth($data['expensesInfo'][0]->userId,$advMonth,$advYear);
            // echo "<pre>";
            // print_r($dataadvance);die();
        $data['dataadvance'] = $dataadvance;

        $advTotal = 0;
        foreach ($dataadvance as $key => $value) {
            $advTotal = $advTotal+$value->checkerAmount;
        }
            
        $data['advTotal'] = $advTotal;

        $this->global['pageTitle'] = 'Arya Collateral : View Expenses';

        $this->loadViews("expenses/view", $this->global, $data, NULL);
        
        }
    
    }

    public function pdfExpenses($id){

        if($id){

            $data  = $this->expenses_model->getExpensesInfo($id);
            $field = $this->expenses_model->getField();
            $entry = date('M-Y',strtotime($data[0]->entry_date));
            $nar = "Expenses For The Month Of ".$entry;
            if(!empty($data)){
                    ob_start();
                    $this->load->library('Pdf');
                    $pdf = new Pdf('L', 'mm', 'A4', true, 'UTF-8', false);
                    $pdf->SetTitle('Expenses');
                    $pdf->SetHeaderData(PDF_HEADER_LOGO, 35, '','ARYA COLLATERAL WAREHOUSING SERVICES PVT. LTD.','H-82, Sector-63, Ground floor, Behind Ginger Hotel,
             Noida-201301 (U.P.)','');
                    //$pdf->SetTopMargin(20);
                    $pdf->SetPrintFooter(false);
                    $pdf->setFooterMargin(20);
                    $pdf->SetAutoPageBreak(true);
                    $pdf->SetAuthor('Arya');
                    $pdf->SetDisplayMode('real', 'default');
                    $pdf->AddPage('L');
                    $pdf->Ln(35);
                    //$pdf->SetFont('helvetica', '',9);
                    $pdf->Cell(0, 8, $nar, 0, false, 'C', 0, '', 0, false, 'M', 'M');
                    $pdf->Ln(10,false);
                    $pdf->SetFont('helvetica', '',8);

                    $fields="";
                    foreach($field as $rec){
                    $fields .= '<td  align="center">'.$rec->field.'</td>';   
                    }


$name = $data[0]->name;
$empCode = $data[0]->empCode;
$empDept = $data[0]->departmentName;
$bankName = $data[0]->bankName;
$acNum = $data[0]->acNum;
$ifsc = $data[0]->ifscCode;
$mobile = $data[0]->mobile;
$userId = $data[0]->userId;
$makerAmount = $data[0]->makerAmount;
$makerRemark = $data[0]->makerRemark;
$checkerAmount = $data[0]->checkerAmount;
$checkerRemark = $data[0]->checkerRemark;
$accountRemark = $data[0]->accountRemark;
$approvedBy = $data[0]->approvedBy;
$checker = $data[0]->checker;
if(!empty($data[0]->entry_date))
{
    $this->load->model('advance_model');
        $advMonth = date ("m", strtotime($data[0]->entry_date));
        $advYear = date ("Y", strtotime($data[0]->entry_date));

        $dataadvance = $this->advance_model->getAdvanceInfoByMonth($userId,$advMonth,$advYear);
            // echo "<pre>";
            // print_r($dataadvance);die();
        // $data['dataadvance'] = $dataadvance;

        $advTotal = 0;
        foreach ($dataadvance as $key => $value) {
            $advTotal = $advTotal+$value->checkerAmount;
        }
        // print_r($advTotal);die();
}


$iterate="";
$tra=0; $mob=0; $foo=0;$lod=0;$conv=0;$print=0;$cour=0;$lab=0;$dun=0;$whclean=0;$lock=0; $total=0;
foreach($data as $k=>$res){
    if(isset($k) && $k!='50'){
    $date = date('d-m-Y',strtotime($res->entry_date));
    $tra = $tra+$res->travel;
    $mob = $mob+$res->mobile_exp;
    $foo = $foo+$res->fooding;
    $lod = $lod+$res->lodging;
    $conv = $conv+$res->local_conv;
    $print = $print+$res->printing;
    $cour = $cour+$res->courier;
    $lab = $lab+$res->labour_charge;
    $dun = $dun+$res->dunnage;
    $whclean = $whclean+$res->wh_cleaning;
    $lock = $lock+$res->lock_key;
    
    $iterate .='<tr>
                    <td align="center">'.$date.'</td>
                    <td align="center">'.$res->location.'</td>
                    <td align="center">'.$res->description.'</td>
                    <td align="center">'.$res->travel.'</td>
                    <td align="center">'.$res->mobile_exp.'</td>
                    <td align="center">'.$res->fooding.'</td>
                    <td align="center">'.$res->lodging.'</td>
                    <td align="center">'.$res->local_conv.'</td>
                    <td align="center">'.$res->printing.'</td>
                    <td align="center">'.$res->courier.'</td>
                    <td align="center">'.$res->labour_charge.'</td>
                    <td align="center">'.$res->dunnage.'</td>
                    <td align="center">'.$res->wh_cleaning.'</td>
                    <td align="center">'.$res->lock_key.'</td>
                </tr>';
}  } 
$total = $tra+$mob+$foo+$lod+$conv+$print+$cour+$lab+$dun+$whclean+$lock;

$paymentInWord = $this->getindiancurrency($total);

/* Local Conv*/
$local="";
$local_sum=0;
if(isset($data['50'])){
foreach($data['50'] as $rec){
     if($rec->local_entry){
     $local_date = date('d-m-Y',strtotime($rec->local_entry));
     $local_sum = $local_sum+$rec->local_amount; 
$local .='<tr>
            <td align="center">'.$local_date.'</td>
            <td align="center">'.$rec->local_location.'</td>
            <td align="center">'.$rec->dis_from.'</td>
            <td align="center">'.$rec->dis_to.'</td>
            <td align="center">'.$rec->km.'</td>
            <td align="center">'.$rec->transport.'</td>
            <td align="center">'.$rec->local_amount.'</td>
        </tr>';
}   }

$local .='<tr>
            <td colspan="6" align="center"></td>
            <td align="center">Total :'.$local_sum.'</td>
        </tr>';
}        

$exptotal = $total+$local_sum;

$tbl = <<<EOD
<style>
    .dttab{
        border-style: dotted;
    }
    </style>
    <table cellspacing="0" cellpadding="5" border="0">
        <tr><td style="width:50%">
            <table border="0" cellpadding="2" cellspacing="0">
                <tr>
                    <td style="width:40%"><strong>Emp Code</strong></td>
                    <td style="width:60%"><strong>: </strong>{$empCode}</td>
                </tr>
                <tr>
                    <td><strong>Emp Name</strong></td>
                    <td><strong>: </strong>{$name}</td>
                </tr>
                <tr>
                    <td><strong>Designation</strong></td>
                    <td><strong>: </strong></td>
                </tr>
                <tr>
                    <td><strong>Department</strong></td>
                    <td><strong>: </strong>{$empDept}</td>
                </tr>
                
                
            </table>
            </td>
            <td style="width:50%">
            <table border="0" cellpadding="4" cellspacing="0">
                <tr>
                    <td style="width:40%"><strong>Mobile</strong></td>
                    <td style="width:60%"><strong>: {$mobile}</strong> </td>
                </tr>
                <tr>
                    <td style="width:40%"><strong>Bank A/C No.</strong></td>
                    <td style="width:60%"><strong>: </strong>{$acNum} </td>
                </tr>
                <tr>
                    <td style="width:40%"><strong>IFSC Code</strong></td>
                    <td style="width:60%"><strong>: {$ifsc}</strong></td>
                </tr>
                <tr>
                    <td style="width:40%"><strong>Expense Total</strong></td>
                    <td style="width:60%"><strong>: {$exptotal}</strong></td>
                </tr>
                <tr>
                    <td style="width:40%"><strong>Approved Advance Total</strong></td>
                    <td style="width:60%"><strong>: {$advTotal}</strong></td>
                </tr>
             </table>
            </td>
        </tr>
        <tr>
            <td colspan="2"><table border="1" cellpadding="0" cellspacing="0">
                <tr>
                    <td align="center">Date</td>
                    <td align="center">Location</td>
                    <td align="center"; width="90px">Description</td>
                    <td align="center">Travelling - Bus / Train</td>
                    <td align="center">Mobile Exp/ Courier/ Printing Stationary</td>
                    <td align="center">Fooding</td>
                    <td align="center">Lodging & Boarding</td>
                    <td align="center">Room Rent& Office Rent</td>
                    <td align="center">Transport / Freight Charges</td>
                    <td align="center">Assets(Tablet / New Mobile / Office Equipments)</td>
                    <td align="center">Labour Loading & Unloading Charges</td>
                    <td align="center">Dunnage /Tarpaulin /chemical</td>
                    <td align="center">Warehouse Sweeper / Samplier / Cleaning Charges</td>
                    <td align="center">Lock & key /Repair/ Other Charges</td>
                </tr>
EOD;





$tbl .= <<<EOD
            {$iterate}
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td align="center"><strong>{$tra}</strong></td>
                    <td align="center"><strong>{$mob}</strong></td>
                    <td align="center"><strong>{$foo}</strong></td>
                    <td align="center"><strong>{$lod}</strong></td>
                    <td align="center"><strong>{$conv}</strong></td>
                    <td align="center"><strong>{$print}</strong></td>
                    <td align="center"><strong>{$cour}</strong></td>
                    <td align="center"><strong>{$lab}</strong></td>
                    <td align="center"><strong>{$dun}</strong></td>
                    <td align="center"><strong>{$whclean}</strong></td>
                    <td align="center"><strong>{$lock}</strong></td>
                </tr>
                <tr>
                    <td colspan="7" align="center">{$paymentInWord} </td>
                    <td colspan="7" align="center"><strong>TOTAL : {$total}</strong></td>
                </tr>
                </table>
            </td>
        </tr>
        <tr><td colspan="2"><strong>Local Conv.</strong></td></tr>
        <tr>
            <td colspan="2">
            <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center">Date</td>
                <td align="center">Location</td>
                <td align="center">From</td>
                <td align="center">To</td>
                <td align="center">km</td>
                <td align="center">Mode Of Transport</td>
                <td align="center">Amount</td>
            </tr>
             {$local}   
            </table>
            </td>
        </tr>
        <tr>
            <td></td><td></td>
        </tr>
                    
EOD;



//------------------------------
/* Advance Conv*/
$adv="";
// $local_sum=0;
if(isset($dataadvance)){
foreach($dataadvance as $recAdv){
     if($recAdv->updatedDtm){
     $local_date = date('d-m-Y',strtotime($recAdv->updatedDtm));
     // $local_sum = $local_sum+$recAdv->local_amount;  
$adv .='<tr>
            <td align="center">'.$local_date.'</td>
            <td align="center">'.$recAdv->makerAmount.'</td>
            <td align="center">'.$recAdv->checkerAmount.'</td>
            <td align="center">'.$recAdv->checker.'</td>
            <td align="center">'.$recAdv->makerRemark.'</td>
        </tr>';
}   }

// $local .='<tr>
//             <td colspan="6" align="center"></td>
//             <td align="center">Total :'.$local_sum.'</td>
//         </tr>';
}        



$tbl .= <<<EOD
            
                
        <tr><td colspan="2"><strong>Advance For The Month Of {$entry}</strong></td></tr>
        <tr>
            <td colspan="2">
            <table border="1" cellpadding="0" cellspacing="0">
            <tr>
                <td align="center">Date</td>
                <td align="center">Maker Amount</td>
                <td align="center">Checker Amount</td>
                <td align="center">Checker</td>
                <td align="center">Maker Remark</td>
            </tr>
             {$adv}   
            </table>
            </td>
        </tr>
        <tr>
            <td></td><td></td>
        </tr>
        <tr>
            <td>Employee Signature :</td>
            <td>Approved By : {$checker}</td>
        </tr>
        <tr>
            <td>Date :</td>
            <td>Approval Signature :</td>
        </tr>
        <tr>
            <td></td>
            <td>Name :</td>
        </tr>
        <tr>
            <td></td>
            <td>Designation :</td>
        </tr>
    </table>            
EOD;





//---------------------------------




        $pdf->writeHTML($tbl, true, false, false, false, '');
        //$pdf->Write(5, 'CodeIgniter TCPDF Integration');
        $pdf->Output('sample.pdf', 'I');   
        ob_end_flush();
exit;
                }
            }
            exit;
        
        
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

    function expenseTotal(){
        
        if($this->isAdmin() != TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $userId = $this->input->get('userId');
            //print_r($userId);die;
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->expenses_model->expenseTotalCount($userId,$searchText);
// print_r($count);die();
            $returns = $this->paginationCompress ( "employeeListing/", $count, 1000 );

            $month = $this->input->post('month');
            $year = $this->input->post('year');

            if(empty($month)){
                $month = date('n');
                // $month = '11';
            }
            if(empty($year)){
                $year = date('Y');
                // $year = '2019';
            }
            $data['month'] = $month;
            $data['year']  = $year;

            $data["months"] = $this->months;
            $data["years"] = $this->year;

            $data['userRecords'] = $this->expenses_model->expenseTotal($userId,$searchText, $returns["page"], $returns["segment"], $month, $year);
            
            //print_r($data['userRecords']);die;
            $data['expDetail'] = $data['userRecords']['exp_detail'];
            $data['expLocal'] = $data['userRecords']['exp_local'];
            $data['Advance'] = $data['userRecords']['advance'];

            $Rarray = $data['expDetail'];
            $Larray = $data['expLocal'];
            $Advarray = $data['userRecords']['advance'];
            $result = array();
            // $expense = array_column($Rarray,'userId');

            $expense = array_map(function($e) {
    return is_object($e) ? $e->userId : $e['userId'];
}, $Rarray);
// $version = phpversion();
            // echo "<pre>";
            // print_r($titles);die();

            // foreach( $Larray as $key => $value )
            // {
            //     $count = count($Rarray);
            //   if( !in_array( $value->userId, $expense ))
            //   {
            //     // print_r($value->designationName);die();
            //     $Rarray[$count]->id = 'only local';
            //     $Rarray[$count]->userId = $value->userId;
            //     $Rarray[$count]->amount = $value->amount;
            //     $Rarray[$count]->updatedDtm = $value->updatedDtm;
            //     $Rarray[$count]->departmentName = $value->departmentName;
            //     $Rarray[$count]->designationName = $value->designationName;
            //     $Rarray[$count]->empCode = $value->empCode;
            //     $Rarray[$count]->name = $value->name;
            //     $Rarray[$count]->approvedBy = $value->approvedBy;
            //     $Rarray[$count]->bankName = $value->bankName;
            //     $Rarray[$count]->acNum = $value->acNum;
            //     $Rarray[$count]->ifscCode = $value->ifscCode;
            //     // print_r($Rarray[$count]);die();
            //   }
            //   else
            //   {
                
            //   }
            // }

            foreach( $Advarray as $key => $value )
            {
                $count = count($Rarray);
              if( !in_array( $value->userId, $expense ))
              {
                $Rarray[$count]->id = 'only advance';
                $Rarray[$count]->userId = $value->userId;
                $Rarray[$count]->advanceamount = $value->advanceamount;
                $Rarray[$count]->createdDtm = $value->createdDtm;
                $Rarray[$count]->checker_approved_date = $value->checker_approved_date;
                $Rarray[$count]->finance_approved_date = $value->finance_approved_date;
                $Rarray[$count]->updatedDtm = $value->updatedDtm;
                $Rarray[$count]->departmentName = $value->departmentName;
                $Rarray[$count]->designationName = $value->designationName;
                $Rarray[$count]->empCode = $value->empCode;
                $Rarray[$count]->name = $value->name;
                $Rarray[$count]->approvedBy = $value->approvedBy;
                $Rarray[$count]->bankName = $value->bankName;
                $Rarray[$count]->acNum = $value->acNum;
                $Rarray[$count]->ifscCode = $value->ifscCode;
              }
              else
              {
                
              }
            }


            foreach ($Rarray as $key => $arr)
             {
                foreach ($Larray as $key2 => $arr2) 
                {
                    if(!empty($arr->id))
                    {
                        // if ($arr2->expenses_id == $arr->id) {
                        if ($arr2->userId == $arr->userId) {
                            $Rarray[$key]->amount = $arr2->amount;
                        }else{
                            // echo "k";
                        }
                    }
                }
             }

        $data['expDetail'] = $Rarray;

            foreach ($Rarray as $key => $arr) 
            {
                foreach ($Advarray as $key3 => $arr3) 
                {
                    if(!empty($arr->id))
                    {
                        if ($arr3->userId == $arr->userId) {
                            $Rarray[$key]->advanceamount = $arr3->advanceamount;
                        }else{
                            // echo "k";
                        }
                    }
                }
            }
        // echo "<pre>";
        //     print_r($data['expDetail']);die();
            $this->global['pageTitle'] = 'Arya : Expense Listing';
            
            $this->loadViews("expenses/expenseTotal", $this->global, $data, NULL);
        }

    }


    function updatecourier(){
        if($this->input->post()){
        $coid = $this->input->post('id');
        $courierdate = $this->input->post('getdate');
        $courierno = $this->input->post('courierNum');
        $datas = array('courier_date'=>$courierdate,'courier_track_no'=>$courierno);            
        $result = $this->expenses_model->updatecourierdates($datas,$coid);
            if($result){
            echo json_encode(array('value'=>'Success'));die;
            }else{
            echo json_encode(array('value'=>'Error'));die;
            }
        }
        else{
            echo json_encode(array('value'=>'Error'));die;
        } 
    }

    //Function to delete expense 

    function deleteExpense()
    {
        if(!$this->isAdmin())
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $expenseId = $this->input->post('expenseId');
            $expenseInfo = array('isDeleted'=>1, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->expenses_model->deleteExpense($expenseId, $expenseInfo);
            
            if ($result > 0) { echo(json_encode(array('status'=>TRUE))); }
            else { echo(json_encode(array('status'=>FALSE))); }
        }
    }

}
?>