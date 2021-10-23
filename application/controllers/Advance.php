<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Advance extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('advance_model');
        $this->load->helper('url');
        $this->isLoggedIn();   
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
        
        $data['advanceTotal'] = $this->advance_model->getAdvanceTotal();
        $data['pendingCountt'] = $this->advance_model->getPendingTotal();

        $this->global['pageTitle'] = 'Arya Collateral : Dashboard';
        // print_r($data);die();
        //$this->load->view('advance/advance');
        $this->loadViews("advance/advance", $this->global, $data , NULL);
    }

    public function vendor($type=null)
    {
        if(empty($type)){
            $type = 0;
        }
        $searchText = $this->security->xss_clean($this->input->post('searchText'));
            
        $data['searchText'] = $searchText;

        $data['type'] = $type;
        
        $data['advanceTotal'] = $this->advance_model->getVendorTotal();

        $this->global['pageTitle'] = 'Arya Collateral : Dashboard';
        
        //$this->load->view('advance/advance');
        $this->loadViews("vendor/vendor", $this->global, $data , NULL);
    }

    public function vendorList($type=null){
    // POST data
    $postData = $this->input->post();

    $role = $this->session->userdata('role');
    // print_r($role);die();
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
    $data = $this->advance_model->getVendor($postData);
    // print_r($data);
    echo json_encode($data);
  }

    public function advanceList($type=null){
    // POST data
    $postData = $this->input->post();

    //$role = $this->session->userdata('role');
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
    }
    
    
    $postData['approveType'] = $approveType;          

    // Get data
    $data = $this->advance_model->getAdvance($postData);

    echo json_encode($data);
  }
    
    /**
     * This function is used to load the user list
     */
    function advanceListing()
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
            
            $count = $this->advance_model->advanceListingCount($searchText);

			$returns = $this->paginationCompress ( "advanceListing/", $count, 10 );
            
            $data['advanceRecords'] = $this->advance_model->advanceListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Arya Collateral : Advance Listing';
            
            $this->loadViews("advance/advance", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addNewAdvance()
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
            
            $this->global['pageTitle'] = 'Arya Collateral : Add New Advance';

            $this->loadViews("advance/addNewAdvance", $this->global, $data, NULL);
        }
    }

    function addNewVendor()
    {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            
            
            $this->global['pageTitle'] = 'Arya Collateral : Add New Vendor';

            $this->loadViews("vendor/addNewVendor", $this->global, NULL);
        }
    }

    function addNewVendorSave()
    { 
        if($this->isAdmin() == FALSE)
        {       
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('vendorName','Vendor Name','trim|required');
            $this->form_validation->set_rules('address','Address','trim|required');
            $this->form_validation->set_rules('contactNo','Contact Number','trim|required|numeric');
            $this->form_validation->set_rules('acNumber','Account Number','trim|required|numeric');
            $this->form_validation->set_rules('panNumber','Pan Number','trim|required');
            $this->form_validation->set_rules('gstNumber','Gst Number','trim|required');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNewVendor();
                
            }
            else
            {
                $vendorName = $this->security->xss_clean($this->input->post('vendorName'));
                $address = $this->security->xss_clean($this->input->post('address'));
                $contactNo = $this->security->xss_clean($this->input->post('contactNo'));
                $email = $this->security->xss_clean($this->input->post('email'));
                $beneficiaryName = $this->security->xss_clean($this->input->post('beneficiaryName'));
                $acNumber = $this->security->xss_clean($this->input->post('acNumber'));
                $bankName = $this->security->xss_clean($this->input->post('bankName'));
                $branch = $this->security->xss_clean($this->input->post('branch'));
                $city = $this->security->xss_clean($this->input->post('city'));
                $ifsc = $this->security->xss_clean($this->input->post('ifsc'));
                $panNumber = $this->security->xss_clean($this->input->post('panNumber'));
                $gstNumber = $this->security->xss_clean($this->input->post('gstNumber'));
                
                $userInfo = array( 
                    'vendorName'=>$vendorName,
                    'address'=>$address,
                    'contactNo'=>$contactNo,
                    'email'=>$email,
                    'beneficiaryName'=>$beneficiaryName,
                    'acNumber'=>$acNumber,
                    'bankName'=>$bankName,
                    'branch'=>$branch,
                    'city'=>$city,
                    'ifsc'=>$ifsc,
                    'panNumber'=>$panNumber,
                    'gstNumber'=>$gstNumber,
                    'approved'=>'0', 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $this->load->model('advance_model');
                $result = $this->advance_model->addNewVendor($userInfo);
                // print_r($result);die();
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Vendor created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Vendor creation failed');
                }
                
                redirect('adVendor');
            }
        }
    }
    

    function addNewAdvanceSave()
    {
        if($this->isAdmin() == FALSE)
        {       
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('makerAmount','Advance Req. Amount','trim|required|numeric');
            $this->form_validation->set_rules('makerRemark','Remarks','trim|required');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNew();
            }
            else
            {
                $userId = $this->security->xss_clean($this->input->post('userId'));
                $makerAmount = $this->security->xss_clean($this->input->post('makerAmount'));
                $makerRemark = $this->security->xss_clean($this->input->post('makerRemark'));
                
                $userInfo = array('userId'=>$userId, 
                    'makerAmount'=>$makerAmount,
                    'makerRemark'=>$makerRemark,
                    'createdBy'=>$this->session->userdata('userId'),
                    'approved'=>'0', 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $this->load->model('advance_model');
                $result = $this->advance_model->addNewAdvance($userInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Advance created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Advance creation failed');
                }
                
                redirect('adListing');
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
                redirect('adListing');
            }
            
            $data['advanceInfo'] = $this->advance_model->getAdvanceInfo($adId);
            
            $this->global['pageTitle'] = 'Arya Collateral : Edit Advance';
            
            $this->loadViews("advance/editOld", $this->global, $data, NULL);
        }
    }

    function editOldVendor($adId = NULL)
    {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            if($adId == null)
            {
                redirect('adVendor');
            }
            
            $data['vendorInfo'] = $this->advance_model->getVendorInfo($adId);
            
            $this->global['pageTitle'] = 'Arya Collateral : Edit Advance';
            
            $this->loadViews("vendor/editOld", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editAdvance()
    {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $id = $this->input->post('id');
            
            $this->form_validation->set_rules('makerAmount','Request Amount','trim|required|numeric');
            $this->form_validation->set_rules('makerRemark','Maker Remark','required');
            if($this->form_validation->run() == FALSE)
            {
                $this->editOld($userId);
            }
            else
            {
                $makerAmount = $this->security->xss_clean($this->input->post('makerAmount'));
                $makerRemark = $this->security->xss_clean($this->input->post('makerRemark'));
                
                $userInfo = array();
                
                    $userInfo = array('makerAmount'=>$makerAmount, 'makerRemark'=>$makerRemark,'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->advance_model->editAdvance($userInfo, $id);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'advance updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'advance updation failed');
                }
                
                redirect('adListing');
            }
        }
    }

    function editVendor()
    {
        if($this->isAdmin() == FALSE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $id = $this->input->post('id');
            
            $this->form_validation->set_rules('vendorName','Vendor Name','trim|required');
            $this->form_validation->set_rules('address','Address Remark','required');
            if($this->form_validation->run() == FALSE)
            {
                $userId = 1;
                $this->editOldVendor($userId);
            }
            else
            {
                $vendorName = $this->security->xss_clean($this->input->post('vendorName'));
                $address = $this->security->xss_clean($this->input->post('address'));
                $contactNo = $this->security->xss_clean($this->input->post('contactNo'));
                $email = $this->security->xss_clean($this->input->post('email'));
                $beneficiaryName = $this->security->xss_clean($this->input->post('beneficiaryName'));
                $acNumber = $this->security->xss_clean($this->input->post('acNumber'));
                $bankName = $this->security->xss_clean($this->input->post('bankName'));
                $branch = $this->security->xss_clean($this->input->post('branch'));
                $city = $this->security->xss_clean($this->input->post('city'));
                $ifsc = $this->security->xss_clean($this->input->post('ifsc'));
                $panNumber = $this->security->xss_clean($this->input->post('panNumber'));
                $gstNumber = $this->security->xss_clean($this->input->post('gstNumber'));
                
                $userInfo = array();
                
                    $userInfo = array('vendorName'=>$vendorName, 'address'=>$address, 'contactNo'=>$contactNo, 'email'=>$email, 'beneficiaryName'=>$beneficiaryName, 'acNumber'=>$acNumber, 'bankName'=>$bankName, 'branch'=>$branch, 'city'=>$city, 'ifsc'=>$ifsc, 'panNumber'=>$panNumber, 'gstNumber'=>$gstNumber, 'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->advance_model->editVendor($userInfo, $id);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'vendor updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'vendor updation failed');
                }
                
                redirect('adVendor');
            }
        }
    }


    function checkAdvance()
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
                $this->viewAdvance($id);
            }
            else
            {
                $checkerAmount = $this->security->xss_clean($this->input->post('checkerAmount'));
                $checkerRemark = $this->security->xss_clean($this->input->post('checkerRemark'));
                $approvedId = $this->session->userdata('userId');
                $userInfo = array();
                
                    $userInfo = array('checkerAmount'=>$checkerAmount, 'checkerRemark'=>$checkerRemark,'approvedBy'=>$approvedId,'approved'=>'2','updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->advance_model->editAdvance($userInfo, $id);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'advance updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'advance updation failed');
                }
                
                redirect('adListing');
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
    function loginHistoy($userId = NULL)
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $userId = ($userId == NULL ? 0 : $userId);

            $searchText = $this->input->post('searchText');
            $fromDate = $this->input->post('fromDate');
            $toDate = $this->input->post('toDate');

            $data["userInfo"] = $this->user_model->getUserInfoById($userId);

            $data['searchText'] = $searchText;
            $data['fromDate'] = $fromDate;
            $data['toDate'] = $toDate;
            
            $this->load->library('pagination');
            
            $count = $this->user_model->loginHistoryCount($userId, $searchText, $fromDate, $toDate);

            $returns = $this->paginationCompress ( "login-history/".$userId."/", $count, 10, 3);

            $data['userRecords'] = $this->user_model->loginHistory($userId, $searchText, $fromDate, $toDate, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'CodeInsect : User Login History';
            
            $this->loadViews("loginHistory", $this->global, $data, NULL);
        }        
    }

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
    function profileUpdate($active = "details")
    {
        $this->load->library('form_validation');
            
        $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
        $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
        $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]|callback_emailExists');        
        
        if($this->form_validation->run() == FALSE)
        {
            $this->profile($active);
        }
        else
        {
            $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
            $mobile = $this->security->xss_clean($this->input->post('mobile'));
            $email = strtolower($this->security->xss_clean($this->input->post('email')));
            
            $userInfo = array('name'=>$name, 'email'=>$email, 'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->user_model->editUser($userInfo, $this->vendorId);
            
            if($result == true)
            {
                $this->session->set_userdata('name', $name);
                $this->session->set_flashdata('success', 'Profile updated successfully');
            }
            else
            {
                $this->session->set_flashdata('error', 'Profile updation failed');
            }

            redirect('profile/'.$active);
        }
    }

    /**
     * This function is used to change the password of the user
     * @param text $active : This is flag to set the active tab
     */
    function changePassword($active = "changepass")
    {
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('oldPassword','Old password','required|max_length[20]');
        $this->form_validation->set_rules('newPassword','New password','required|max_length[20]');
        $this->form_validation->set_rules('cNewPassword','Confirm new password','required|matches[newPassword]|max_length[20]');
        
        if($this->form_validation->run() == FALSE)
        {
            $this->profile($active);
        }
        else
        {
            $oldPassword = $this->input->post('oldPassword');
            $newPassword = $this->input->post('newPassword');
            
            $resultPas = $this->user_model->matchOldPassword($this->vendorId, $oldPassword);
            
            if(empty($resultPas))
            {
                $this->session->set_flashdata('nomatch', 'Your old password is not correct');
                redirect('profile/'.$active);
            }
            else
            {
                $usersData = array('password'=>getHashedPassword($newPassword), 'updatedBy'=>$this->vendorId,
                                'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->user_model->changePassword($this->vendorId, $usersData);
                
                if($result > 0) { $this->session->set_flashdata('success', 'Password updation successful'); }
                else { $this->session->set_flashdata('error', 'Password updation failed'); }
                
                redirect('profile/'.$active);
            }
        }
    }

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
        $accountRemark = $this->input->post('accountRemark');
        $userInfo = array();
        $approvedId = $this->session->userdata('userId');
        if($status=='reject'){
          $userInfo = array('approved'=>'3','approvedBy'=>$approvedId, 'updatedDtm'=>date('Y-m-d H:i:s'),'accountRemark'=>$accountRemark);
        }else if($status=='approved'){
            $userInfo = array('approved'=>'4', 'updatedDtm'=>date('Y-m-d H:i:s'),'accountRemark'=>$accountRemark);
        }else{
            $userInfo = array('approved'=>'1', 'updatedDtm'=>date('Y-m-d H:i:s'));
        }
        $result = $this->advance_model->editAdvance($userInfo, $id);
        if($result){
            echo json_encode(array('value'=>'Success'));die;
        }else{
            echo json_encode(array('value'=>'Error'));die;
        }        
    }

    function vendorajaxapprove(){

        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $userInfo = array();
        $approvedId = $this->session->userdata('userId');
        if($status=='reject'){
          $userInfo = array('approved'=>'3','approvedBy'=>$approvedId, 'updatedDtm'=>date('Y-m-d H:i:s'));
        }else if($status=='approved'){
            $userInfo = array('approved'=>'4', 'updatedDtm'=>date('Y-m-d H:i:s'));
        }else if($status=='0'){
            $userInfo = array('approved'=>'1', 'updatedDtm'=>date('Y-m-d H:i:s'));
        }else{
            $userInfo = array('approved'=>'2', 'updatedDtm'=>date('Y-m-d H:i:s'));
        }
        $result = $this->advance_model->editVendor($userInfo, $id);
        if($result){
            echo json_encode(array('value'=>'Success'));die;
        }else{
            echo json_encode(array('value'=>'Error'));die;
        }        
    }

    function deleteVendor(){

        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $userInfo = array();
        $approvedId = $this->session->userdata('userId');
        
          $userInfo = array('isDeleted'=>'1', 'updatedDtm'=>date('Y-m-d H:i:s'));
        
        $result = $this->advance_model->editVendor($userInfo, $id);
        if($result){
            echo json_encode(array('value'=>'Success'));die;
        }else{
            echo json_encode(array('value'=>'Error'));die;
        }       
    }

    function deleteAdvance(){

        $id = $this->input->post('id');
        $status = $this->input->post('status');
        $userInfo = array();
        $approvedId = $this->session->userdata('userId');
        
          $userInfo = array('isDeleted'=>'1', 'updatedDtm'=>date('Y-m-d H:i:s'));
        
        $result = $this->advance_model->editAdvance($userInfo, $id);
        if($result){
            echo json_encode(array('value'=>'Success'));die;
        }else{
            echo json_encode(array('value'=>'Error'));die;
        }       
    }

    function viewAdvance($id){
        if($id){

        $data['advanceInfo'] = $this->advance_model->getAdvanceInfo($id);

        $this->global['pageTitle'] = 'Arya Collateral : View Advance';

        $this->loadViews("advance/view", $this->global, $data, NULL);
        
        }
    
    }

    function viewVendor($id){
        if($id){

        $data['vendorInfo'] = $this->advance_model->getVendorInfo($id);

        $this->global['pageTitle'] = 'Arya Collateral : View Advance';
// print_r($data);die();
        $this->loadViews("vendor/view", $this->global, $data, NULL);
        
        }
    
    }

    function vendorListing()
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
            
            $count = $this->advance_model->advanceListingCount($searchText);

            $returns = $this->paginationCompress ( "advanceListing/", $count, 10 );
            
            $data['advanceRecords'] = $this->advance_model->advanceListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Arya Collateral : Advance Listing';
            
            $this->loadViews("advance/advance", $this->global, $data, NULL);
        }
    }
}
?>