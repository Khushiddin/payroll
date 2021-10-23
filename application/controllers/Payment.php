<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';


class Payment extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('payment_model');
        $this->load->helper('url');
        $this->isLoggedIn(); 
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
        
        $data['paymentTotal'] = $this->payment_model->getPaymentTotal();

        $this->global['pageTitle'] = 'Arya Collateral : Dashboard';
        
        //$this->load->view('advance/advance');
        $this->loadViews("payment/payment", $this->global, $data , NULL);
    }

    public function paymentList($type=null){
    // POST data
    $postData = $this->input->post();

    $role = $this->session->userdata('role');
    
    if($type){
        $approveType = $type;
    }else{
        if($role==3){
            $approveType = '0';
        }else if($role==2){
            $approveType = '1';
        }else if($role==4){
            $approveType = '2';
        }       
    }
    
    
    $postData['approveType'] = $approveType;          

    // Get data
    $data = $this->payment_model->getPayment($postData);

    echo json_encode($data);
  }
    
    /**
     * This function is used to load the user list
     */
    
    /**
     * This function is used to load the add new form
     */
    function addNewPayment()
    {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('user_model');
    
            $userId = $this->session->userdata('userId');
            //$userId = 7;
            $data['userInfo'] = $this->user_model->getUserInfo($userId);
            
            $this->load->model('payment_model');
            
            $data['vendorInfo'] =$this->payment_model->getVendors();
            
            $this->global['pageTitle'] = 'Arya Collateral : Add New Vendor Payment';

            $this->loadViews("payment/addNewPayment", $this->global, $data, NULL);
        }
    }
    

    function addNewPaymentSave()
    {
        if($this->isAdmin() == FALSE)
        {       
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('vendorId','Vendor','trim|required');
            $this->form_validation->set_rules('makerAmount','Advance Req. Amount','trim|required|numeric');
            $this->form_validation->set_rules('makerRemark','Remarks','trim|required');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNewPayment();
            }
            else
            {
                $userId = $this->security->xss_clean($this->input->post('userId'));
                $vendorId = $this->security->xss_clean($this->input->post('vendorId'));
                $makerAmount = $this->security->xss_clean($this->input->post('makerAmount'));
                $makerRemark = $this->security->xss_clean($this->input->post('makerRemark'));

                $fileName="";
                $config['upload_path']          = 'uploads/';
                $config['allowed_types']        = 'txt|csv|xls|xlsx|jpg|png';
                $config['max_size']             = 0;
                $config['max_width']            = 0;
                $config['max_height']           = 0;

                $this->load->library('upload', $config);
                
                if(!$this->upload->do_upload('file')){
                    
                    $error = array('error' => $this->upload->display_errors());
                    // print_r($error);die();
                    $this->session->set_flashdata('error', $error);
                }else{
                    $imageInfo = $this->upload->data();
                    $fileName = $imageInfo['file_name'];
                }
                
                $userInfo = array('vendorId'=>$vendorId,'userId'=>$userId, 
                    'makerAmount'=>$makerAmount,
                    'makerRemark'=>$makerRemark,
                    'file_name'=>$fileName,
                    'approved'=>'0', 'createdDtm'=>date('Y-m-d H:i:s'));

                // print_r($userInfo);die();
                
                $this->load->model('payment_model');
                $result = $this->payment_model->addNewPayment($userInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Vendor Payment created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Vendor Payment creation failed');
                }
                
                redirect('paymentListing');
            }
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
                redirect('paymentListing');
            }
            
            $data['paymentInfo'] = $this->payment_model->getPaymentInfo($adId);
            
            $data['vendorInfo'] =$this->payment_model->getVendors();

            $this->global['pageTitle'] = 'Arya Collateral : Edit Vendor Payment';
            
            $this->loadViews("payment/editOld", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editPayment()
    {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $id = $this->input->post('id');
            
            $this->form_validation->set_rules('vendorId','Vendor','trim|required');
            $this->form_validation->set_rules('makerAmount','Request Amount','trim|required|numeric');
            $this->form_validation->set_rules('makerRemark','Maker Remark','required');
            if($this->form_validation->run() == FALSE)
            {
                $this->editOld($userId);
            }
            else
            {
                $vendorId = $this->security->xss_clean($this->input->post('vendorId'));
                $makerAmount = $this->security->xss_clean($this->input->post('makerAmount'));
                $makerRemark = $this->security->xss_clean($this->input->post('makerRemark'));
                
                $userInfo = array();
                
                    $userInfo = array('vendorId'=>$vendorId,'makerAmount'=>$makerAmount, 'makerRemark'=>$makerRemark,'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->payment_model->editPayment($userInfo, $id);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'payment updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'payment updation failed');
                }
                
                redirect('paymentListing');
            }
        }
    }


    function checkPayment()
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
                $this->viewPayment($id);
            }
            else
            {
                $checkerAmount = $this->security->xss_clean($this->input->post('checkerAmount'));
                $checkerRemark = $this->security->xss_clean($this->input->post('checkerRemark'));
                $approvedId = $this->session->userdata('userId');
                $userInfo = array();
                
                    $userInfo = array('checkerAmount'=>$checkerAmount, 'checkerRemark'=>$checkerRemark,'approvedBy'=>$approvedId,'approved'=>'2','updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->payment_model->editPayment($userInfo, $id);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Payment updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Payment updation failed');
                }
                
                redirect('paymentListing');
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
        $userInfo = array();
        $approvedId = $this->session->userdata('userId');
        if($status=='reject'){
          $userInfo = array('approved'=>'3','approvedBy'=>$approvedId, 'updatedDtm'=>date('Y-m-d H:i:s'));
        }else if($status=='approved'){
            $userInfo = array('approved'=>'4', 'updatedDtm'=>date('Y-m-d H:i:s'));
        }else{
            $userInfo = array('approved'=>'1', 'updatedDtm'=>date('Y-m-d H:i:s'));
        }
        $result = $this->payment_model->editPayment($userInfo, $id);
        if($result){
            echo json_encode(array('value'=>'Success'));die;
        }else{
            echo json_encode(array('value'=>'Error'));die;
        }        
    }

    function viewPayment($id){
        if($id){

        $data['paymentInfo'] = $this->payment_model->getPaymentInfo($id);

        $this->global['pageTitle'] = 'Arya Collateral : View Payment';

        $this->loadViews("payment/view", $this->global, $data, NULL);
        
        }
    
    }

    public function download($id)
    {
        $name = $this->payment_model->getPaymentInfo($id);
        $filename = $name->file_name;
        $this->load->helper('download');
        $data = file_get_contents('uploads/'.$filename.''); 
        

        force_download($filename, $data);
        print_r($data->file_name);die();
    }

    public function vendorTotal(){
        
        if($this->isAdmin() != TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $searchText = $this->security->xss_clean($this->input->post('searchText'));
            
            $data['searchText'] = $searchText;
            
            $this->load->library('pagination');
            
            $count = $this->payment_model->vendorTotalCount($searchText);

            $returns = $this->paginationCompress ( "paymentListing/", $count, 1000 );

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
            $data["years"] = $this->year;

            $data['userRecords'] = $this->payment_model->vendorTotal($searchText, $returns["page"], $returns["segment"], $month, $year);
            
            $data['expDetail'] = $data['userRecords']['exp_detail'];

            $this->global['pageTitle'] = 'Arya : Vendor Listing';
            
            $this->loadViews("payment/vendorTotal", $this->global, $data, NULL);
        }
    }
}
?>