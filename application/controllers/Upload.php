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
        $this->global['pageTitle'] = 'Aryadhan : Upload File';
        
        $this->loadViews("upload", $this->global, NULL , NULL);
    }

    function upload()
    {
        $this->global['pageTitle'] = 'Aryadhan : Upload File';
        $this->global['error'] = '';
        
        $this->loadViews("upload/upload", $this->global, NULL , NULL);
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

                $this->loadViews('upload/upload', $error);
        }else{

            $data = array('upload_data' => $this->upload->data());

            $this->load->library('CSVReader');    

            $csvData = $this->csvreader->parse_csv($data['upload_data']['full_path']);

            if(!empty($csvData)){
                foreach($csvData as $row){ $rowCount++;
                    
                    $userInfo = array('email'=>$row['email'], 'password'=>getHashedPassword($row['panNo']),'roleId'=>3, 'name'=> $row['name'],'empCode'=>$row['empCode'],
                    'departmentId'=>$row['departmentId'],'designationId'=>$row['designationId'],'doj'=>date('Y-m-d',strtotime($row['doj'])),'stateId'=>$row['stateId'],'locationId'=>$row['locationId'],'panNo'=>$row['panNo'],'aadhar'=>$row['aadhar'],'pfuan'=>$row['pfuan'],'beneficiaryName'=>$row['beneficiaryName'],'acNum'=>$row['acNum'],'bankName'=>$row['bankName'],'branch'=>$row['branch'],'city'=>$row['city'],'ifscCode'=>$row['ifscCode'],'basic'=>$row['basic'],'transAllow'=>$row['transAllow'], 'spclAllow'=>$row['spclAllow'],'lta'=>$row['lta'], 'hra'=>$row['hra'], 'bonus'=>$row['bonus'], 'mobile'=>$row['mobile'], 'createdBy'=>'1', 'createdDtm'=>date('Y-m-d H:i:s'));
                        $this->load->model('user_model');
                        $result = $this->user_model->addNewUser($userInfo);
                        if($result){
                            $insertCount++;
                        }
                }
                $notAddCount = ($rowCount - ($insertCount + $updateCount));

                $successMsg = 'Members imported successfully. Total Rows ('.$rowCount.') | Inserted ('.$insertCount.') | Updated ('.$updateCount.') | Not Inserted ('.$notAddCount.')';
                $this->session->set_flashdata('success', $successMsg);

                redirect('upload');

            }
        }
    }

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
