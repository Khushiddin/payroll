<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

class Upload extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->load->helper('file');
        
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Arya Collateral : Upload File';
        
        $this->loadViews("upload", $this->global, NULL , NULL);
    }

    function upload()
    {
        $this->global['pageTitle'] = 'Arya Collateral : Upload File';
        $this->global['error'] = '';
        
        $this->loadViews("bulkUpload", $this->global, NULL , NULL);
    }

    function uploadTrans()
    {
        $this->global['pageTitle'] = 'Arya Collateral : Upload Salary Transactions';
        $this->loadViews("salaryTrans", $this->global, NULL); 
    }

    function uploadStructure()
    {
        $this->global['pageTitle'] = 'Arya Collateral : Upload Salary Master Structure';
        $this->loadViews("salaryStructure", $this->global, NULL); 
    }

    function saveupload(){

        $config['upload_path']          = 'uploads/';
        $config['allowed_types']        = 'csv';
        $config['max_size']             = 1024;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        
        $this->load->library('upload', $config);
       
        if ( ! $this->upload->do_upload('userfile'))
            {
                $error = array('error' => $this->upload->display_errors());

                //print_r($error);die;
                $this->loadViews('upload/upload', $error);
        }else{

            $data = array('upload_data' => $this->upload->data());

            
            $this->load->library('CSVReader');    

            $csvData = $this->csvreader->parse_csv($data['upload_data']['full_path']);
            
            //echo "<pre>";
            //print_r($csvData);
            //exit;
            $rowCount=0;
            $departmentArr=[]; $designationArr=[];$stateArr=[];$locationArr=[];
            if(!empty($csvData)){
                foreach($csvData as $row){ $rowCount++;
                $skip=0; $error='';    
                $this->load->model('user_model');
                //$data = $this->user_model->getUserInfoByCode($row['empCode']);
                
        /*if($data != '')
        {
            $skip = 1;
            $error .="Employee already Exist, ";
        }*/
        if($row['panNo'] == ''){
            $pass = 'pass@123';
        }else{
            $pass = $row['panNo'];
        }
        //$department_id = $row['departmentId'];
        //$designation_id = $row['designationId'];
        //$state_id = $row['stateId'];
        //$location_id = $row['locationId'];
        
        /* DEPARTMENT ID*/
        if(in_array($row['departmentId'], $departmentArr)){
            $department_id = array_search($row['departmentId'],$departmentArr); 
        }else{
            $department_id = $this->user_model->getdepartmentId($row['departmentId']);
            $departmentArr[$department_id]=$row['departmentId'];   
        }
        if(empty($department_id)){
            //$skip = 1;
            //$error .="Department Not Exist, ";
            $department_id = 0;
        }

        //echo $skip;die;
        /* END DEPARTMENT*/
        /* DESIGNATION */
        if(in_array($row['designationId'], $designationArr)){
            $designation_id = array_search($row['designationId'],$designationArr); 
        }else{
            $designation_id = $this->user_model->getdesignationId($row['designationId']);
            $designationArr[$designation_id]=$row['designationId'];   
        }
        if(empty($designation_id)){
            //$skip = 1;
            //error .="Designation Not Exist, ";
            $designation_id = 5;
        }
        /* END DESIGNATION*/
        /* STATE */
        if(in_array($row['stateId'], $stateArr)){
            $state_id = array_search($row['stateId'],$stateArr); 
        }else{
            $state_id = $this->user_model->getstateId($row['stateId']);
            $stateArr[$state_id]=$row['stateId'];   
        }
        if(empty($state_id)){
            $state_id = 0;
            //$skip = 1;
            //$error .="State Not Exist, ";
        }
        /* END STATE */
        /* LOCATION */
        if(in_array($row['locationId'], $locationArr)){
            $location_id = array_search($row['locationId'],$locationArr); 
        }else{
            $location_id = $this->user_model->getstateId($row['locationId']);
            $locationArr[$location_id]=$row['locationId'];   
        }
        if(empty($location_id)){
            $location_id=0;
            //$skip = 1;
            //$error .="Location Not Exist, ";
        }
        /* END LOCATION */
        $location_id=0;
        $doj = $this->user_model->dateformat($row['doj']);
        
        if($skip!=1){    
                    $userInfo = array('email'=>$row['email'], 'password'=>getHashedPassword($pass),'roleId'=>3, 'name'=> $row['name'],'empCode'=>$row['empCode'],
                    'departmentId'=>$department_id,'doj'=>$doj,'stateId'=>$state_id,'panNo'=>$row['panNo'],'beneficiaryName'=>$row['beneficiaryName'],'acNum'=>$row['acNum'],'bankName'=>$row['bankName'],'branch'=>$row['branch'],'city'=>$row['city'],'ifscCode'=>$row['ifscCode'], 'mobile'=>$row['mobile'],'roleId'=>3,'empRole'=>2,'empType'=>$row['empType'], 'createdBy'=>'1', 'createdDtm'=>date('Y-m-d H:i:s')); 
 
                    // $userInfo = array('password'=>getHashedPassword('pass@123'),'roleId'=>3, 'name'=> $row['name'],'empCode'=>$row['empCode'],'beneficiaryName'=>$row['name'],'acNum'=>$row['acNum'],'bankName'=>$row['bankName'],'branch'=>$row['branch'],'city'=>$row['city'],'ifscCode'=>$row['ifscCode'],'empType'=>'EASY', 'createdBy'=>'1', 'createdDtm'=>date('Y-m-d H:i:s'));
// print_r($userInfo);die();

                    //print_r($userInfo);die();
                        $this->load->model('user_model');
                        $result = $this->user_model->addNewUser($userInfo);
                        if($result){
                            $insertCount++;
                        }
                }
            }
                $notAddCount = ($rowCount - ($insertCount + $updateCount));

                $successMsg = 'Members imported successfully. Total Rows ('.$rowCount.') | Inserted ('.$insertCount.') | Updated ('.$updateCount.') | Not Inserted ('.$notAddCount.')';
                $this->session->set_flashdata('success', $successMsg);

                redirect('upload');

            }
        }
    }

    function salaryCsv()
    {

        $config['upload_path']          = 'uploads/';
        $config['allowed_types']        = 'csv';
        $config['max_size']             = 1024;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        
        $this->load->library('upload', $config);
       
        if ( ! $this->upload->do_upload('userfile'))
            {
                $error = array('error' => $this->upload->display_errors());

                $this->loadViews('upload/upload', $error);
        }else{

            $data = array('upload_data' => $this->upload->data());

            $this->load->library('CSVReader');    

            $csvData = $this->csvreader->parse_csv($data['upload_data']['full_path']);
            //print_r($csvData);die;
            $rowCount=0;
            $insertCount=0;
            if(!empty($csvData)){
                foreach($csvData as $row){ $rowCount++;
                    if($row['empCode'] == '')
                    {
                        break;
                    }
                    
                $this->load->model('user_model');
                
                $data = $this->user_model->getUserInfoByCode($row['empCode']);
                // echo "<pre>";
                // print_r($data);die;
        if($data == '')
        {
            $successMsg = 'User not found whose USERID is :'.$row['id'].'';
            $this->session->set_flashdata('uploadSuccess', $successMsg);
            redirect('uploadTrans');
            break;
        }
        /* Check Current Month and Year Validation*/
        $curentVal = 0;
        $curentVal = $this->user_model->checkEmpAttendance($row['empCode'],$row['month'],$row['year']);
        // print_r($curentVal);die();
        if($curentVal>0){
            $successMsg = 'Duplicate record found - USERID is :'.$row['empCode'].'';
            $this->session->set_flashdata('uploadSuccess', $successMsg);
            redirect('uploadTrans');
            break;   
        }

        $A_gross_withoutBonus = $data->basic+$data->spclAllow+$data->lta+$data->hra;
        
        $A_gross = $data->basic+$data->spclAllow+$data->lta+$data->hra+$data->bonus;
        
        
        $A_epf = (12/100)*($data->basic);
        if($A_gross_withoutBonus <= 21000){
            $A_esi = ceil((0.75/100)*($A_gross+$row['incentive']+$row['rimbersement']));
        }else{
            $A_esi = 0;
        }
        /*Arrear add after ESI */
        $A_gross = $A_gross+$row['arrear'];
        
        $A_deduction = $A_epf+$A_esi+$row['tds']+$row['pt']+$row['advance_deduction']+$row['arrear_pf']+$row['arrear_esic']+$row['other_deduction']+$row['emp_lwf'];
        $A_payroll = $A_gross-$A_deduction+$row['incentive']+$row['rimbersement'];

        $salaryInfo = array('userId'=>$data->userId, 'empCode'=>$row['empCode'],'month'=>$row['month'],'year'=>$row['year'] , 'presentDays'=> $row['presentDays'],'tds'=>$row['tds'],'advance_deduction'=>$row['advance_deduction'],'pt'=>$row['pt'], 'createdDtm'=>date('Y-m-d H:i:s'), 'arrear_pf'=>$row['arrear_pf'],'arrear'=>$row['arrear'],'incentive'=>$row['incentive'],'rimbersement'=>$row['rimbersement'],'arrear_esic'=>$row['arrear_esic'],'other_deduction'=>$row['other_deduction'],'lwf'=>$row['emp_lwf']);

        $salaryResult = $this->user_model->addSalarySlip($salaryInfo);
        
        $userInfo = array('userId'=>$data->userId,'month'=>$row['month'],'year'=>$row['year'] , 'presentDays'=> $row['presentDays'],'basic'=>$data->basic, 'spclAllow'=>$data->spclAllow,'lta'=>$data->lta, 'hra'=>$data->hra, 'bonus'=>$data->bonus, 'gross'=>$A_gross, 'epf' =>$A_epf, 'esi'=>$A_esi,'tds'=>$row['tds'],'advance'=>$row['advance_deduction'],'pt'=>$row['pt'],'deduction'=>$A_deduction,'payroll'=>$A_payroll , 'created_by'=>'1', 'created_at'=>date('Y-m-d H:i:s'));
            
            $result = $this->user_model->addUserTrans($userInfo);
            if($result){
                $insertCount++;
            }
    }
                $notAddCount = ($rowCount - ($insertCount + $updateCount))-1;

                $successMsg = 'Transactions imported successfully. Total Rows Inserted :'.$insertCount.'';
                $this->session->set_flashdata('uploadSuccess', $successMsg);

                redirect('uploadTrans');

            }
        }
    }

    //-------------------------------------------------------------------------------

    function structureCsv()
    {

        $config['upload_path']          = 'uploads/';
        $config['allowed_types']        = 'csv';
        $config['max_size']             = 1024;
        $config['max_width']            = 1024;
        $config['max_height']           = 768;
        
        $this->load->library('upload', $config);
       
        if ( ! $this->upload->do_upload('userfile'))
            {
                $error = array('error' => $this->upload->display_errors());

                $this->loadViews('upload/upload', $error);
        }else{

            $data = array('upload_data' => $this->upload->data());

            $this->load->library('CSVReader');    

            $csvData = $this->csvreader->parse_csv($data['upload_data']['full_path']);
            $rowCount=0;
            $insertCount=0;
            if(!empty($csvData)){
                foreach($csvData as $row){ $rowCount++;
                    if($row['empCode'] == '')
                    {
                        break;
                    }
                    
                $this->load->model('user_model');
                
                $data = $this->user_model->getUserInfoByCode($row['empCode']);
        
        if($data == '')
        {
            $successMsg = 'User not found whose empCode is :'.$row['empCode'].'';
            $this->session->set_flashdata('uploadSuccess', $successMsg);
            redirect('uploadStructure');
            break;
        }

        $skip=1;
        if($row['basic']!=""){
            $basic = $row['basic'];
            $skip=0;
        }
        /*if($row['spclAllow']!=""){
           $spclAllow=$row['spclAllow'];
           $skip=0; 
        }
        if($row['lta']!=""){
           $spclAllow=$row['lta'];
           $skip=0; 
        }*/


       if($skip!=1){
        $userInfo = array('basic'=>$row['basic'], 'spclAllow'=> $row['spclAllow'],'lta'=>$row['lta'],'hra'=>$row['hra'],'bonus'=>$row['bonus'], 'updatedDtm'=>date('Y-m-d H:i:s'));
            $result = $this->user_model->editSalaryStructure($userInfo,$row['empCode']);
            
            if($result){
                $insertCount++;
            }
        }
    }
                $notAddCount = ($rowCount - ($insertCount + $updateCount))-1;

                $successMsg = 'Salary Structure updated successfully. Total Rows Updated :'.$insertCount.'';
                $this->session->set_flashdata('uploadSuccess', $successMsg);

                redirect('uploadStructure');

            }
        }
    }

    //----------------------------------------------------------------------------------

    function import(){
        $data = array();
        $memData = array(); 
        
        // If import request is submitted
        if($this->input->post('importSubmit')){
            // Form field validation rules
            $this->form_validation->set_rules('file', 'CSV file', 'callback_file_check');
            
            // Validate submitted form data
            if($this->form_validation->run() == true){
                $insertCount = $updateCount = $rowCount = $notAddCount = 0;
                
                // If file uploaded
                if(is_uploaded_file($_FILES['file']['tmp_name'])){
                    // Load CSV reader library
                    $this->load->library('CSVReader');
                    
                    // Parse data from CSV file
                    $csvData = $this->csvreader->parse_csv($_FILES['file']['tmp_name']);
                    
                    // Insert/update CSV data into database
                    if(!empty($csvData)){
                        foreach($csvData as $row){ $rowCount++;
                            
                            // Prepare data for DB insertion
                            $memData = array(
                                'name' => $row['Name'],
                                'email' => $row['Email'],
                                'phone' => $row['Phone'],
                                'status' => $row['Status'],
                            );
                            
                            // Check whether email already exists in the database
                            $con = array(
                                'where' => array(
                                    'email' => $row['Email']
                                ),
                                'returnType' => 'count'
                            );
                            $prevCount = $this->member->getRows($con);
                            
                            if($prevCount > 0){
                                // Update member data
                                $condition = array('email' => $row['Email']);
                                $update = $this->member->update($memData, $condition);
                                
                                if($update){
                                    $updateCount++;
                                }
                            }else{
                                // Insert member data
                                $insert = $this->member->insert($memData);
                                
                                if($insert){
                                    $insertCount++;
                                }
                            }
                        }
                        
                        // Status message with imported data count
                        $notAddCount = ($rowCount - ($insertCount + $updateCount));
                        $successMsg = 'Members imported successfully. Total Rows ('.$rowCount.') | Inserted ('.$insertCount.') | Updated ('.$updateCount.') | Not Inserted ('.$notAddCount.')';
                        $this->session->set_userdata('success_msg', $successMsg);
                    }
                }else{
                    $this->session->set_userdata('error_msg', 'Error on file upload, please try again.');
                }
            }else{
                $this->session->set_userdata('error_msg', 'Invalid file, please select only CSV file.');
            }
        }
        redirect('members');
    }
    
    /*
     * Callback function to check file value and type during validation
     */
    function file_check($str){
        $allowed_mime_types = array('text/x-comma-separated-values', 'text/comma-separated-values', 'application/octet-stream', 'application/vnd.ms-excel', 'application/x-csv', 'text/x-csv', 'text/csv', 'application/csv', 'application/excel', 'application/vnd.msexcel', 'text/plain');
        if(isset($_FILES['file']['name']) && $_FILES['file']['name'] != ""){
            $mime = get_mime_by_extension($_FILES['file']['name']);
            $fileAr = explode('.', $_FILES['file']['name']);
            $ext = end($fileAr);
            if(($ext == 'csv') && in_array($mime, $allowed_mime_types)){
                return true;
            }else{
                $this->form_validation->set_message('file_check', 'Please select only CSV file to upload.');
                return false;
            }
        }else{
            $this->form_validation->set_message('file_check', 'Please select a CSV file to upload.');
            return false;
        }
    }
    
    
    
    function pageNotFound()
    {
        $this->global['pageTitle'] = 'CodeInsect : 404 - Page Not Found';
        
        $this->loadViews("404", $this->global, NULL, NULL);
    }
   
}

?>
