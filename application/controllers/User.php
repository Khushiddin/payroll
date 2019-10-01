<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class User extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Arya Collateral : Dashboard';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }
    
    /**
     * This function is used to load the user list
     */
    function userListing()
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
            
            $count = $this->user_model->userListingCount($searchText);

			$returns = $this->paginationCompress ( "userListing/", $count, 10 );
            
            $data['userRecords'] = $this->user_model->userListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Arya Collateral : Employee Listing';
            
            $this->loadViews("users", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addNew()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('user_model');

            $data['departments'] = $this->user_model->getUserDepartments();
            $data['designations'] = $this->user_model->getUserDesignations();

            $data['states'] = $this->user_model->getUserStates();            
            
            $data['roles'] = $this->user_model->getUserRoles();
            
            $this->global['pageTitle'] = 'Arya Collateral : Add New Employee';

            $this->loadViews("addNew", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    function checkEmailExists()
    {
        $userId = $this->input->post("userId");
        $email = $this->input->post("email");

        if(empty($userId)){
            $result = $this->user_model->checkEmailExists($email);
        } else {
            $result = $this->user_model->checkEmailExists($email, $userId);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }


    function checkEmpCode()
    {
        $id = $this->input->post("id");
        $empCode = $this->input->post("empCode");

        if(empty($id)){
            $result = $this->user_model->checkEmpCode($empCode);
        } else {
            $result = $this->user_model->checkEmpCode($empCode, $id);
        }

        if(empty($result)){ echo("true"); }
        else { echo("false"); }
    }
    
    /**
     * This function is used to add new user to the system
     */
    function addNewUser()
    {
        if($this->isAdmin() == TRUE)
        {       
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password','Password','required|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','trim|required|matches[password]|max_length[20]');
            //$this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            $this->form_validation->set_rules('empCode','Employee Code','required|min_length[3]');
            $this->form_validation->set_rules('panNo','PAN No','required|min_length[10]');
            $this->form_validation->set_rules('aadhar','Aadhar','required|min_length[12]');
            $this->form_validation->set_rules('pfuan','PF UAN','required|min_length[10]');
            $this->form_validation->set_rules('acNum','Account Number','required|min_length[10]');
            $this->form_validation->set_rules('ifscCode','IFSC Code','required|min_length[5]');
            $this->form_validation->set_rules('basic','BASIC','required|min_length[3]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->addNew();
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = strtolower($this->security->xss_clean($this->input->post('email')));
                $password = $this->input->post('password');
                //$roleId = $this->input->post('role');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                $empCode = $this->security->xss_clean($this->input->post('empCode'));
                $departmentId = $this->security->xss_clean($this->input->post('departmentId'));
                $designationId = $this->security->xss_clean($this->input->post('designationId'));
                $doj = $this->security->xss_clean($this->input->post('doj'));
                $stateId = $this->security->xss_clean($this->input->post('stateId'));
                $locationId = $this->security->xss_clean($this->input->post('locationId'));
                $panNo = $this->security->xss_clean($this->input->post('panNo'));
                $aadhar = $this->security->xss_clean($this->input->post('aadhar'));
                $pfuan = $this->security->xss_clean($this->input->post('pfuan'));
                $beneficiaryName = $this->security->xss_clean($this->input->post('beneficiaryName'));
                $acNum = $this->security->xss_clean($this->input->post('acNum'));
                $bankName = $this->security->xss_clean($this->input->post('bankName'));
                $branch = $this->security->xss_clean($this->input->post('branch'));
                $city = $this->security->xss_clean($this->input->post('city'));
                $ifscCode = $this->security->xss_clean($this->input->post('ifscCode'));
                $basic = $this->security->xss_clean($this->input->post('basic'));
                $transAllow = $this->security->xss_clean($this->input->post('transAllow'));
                $spclAllow = $this->security->xss_clean($this->input->post('spclAllow'));
                $lta = $this->security->xss_clean($this->input->post('lta'));
                $hra = $this->security->xss_clean($this->input->post('hra'));
                $bonus = $this->security->xss_clean($this->input->post('bonus'));
                
                $userInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 
                    'roleId'=>3, 'name'=> $name,'empCode'=>$empCode,
                    'departmentId'=>$departmentId,
                    'designationId'=>$designationId,
                    'doj'=>date('Y-m-d',strtotime($doj)),
                    'stateId'=>$stateId,
                    'locationId'=>$locationId,
                    'panNo'=>$panNo,
                    'aadhar'=>$aadhar,
                    'pfuan'=>$pfuan,
                    'beneficiaryName'=>$beneficiaryName,
                    'acNum'=>$acNum,
                    'bankName'=>$bankName,
                    'branch'=>$branch,
                    'city'=>$city,
                    'ifscCode'=>$ifscCode,
                    'basic'=>$basic,
                    'transAllow'=>$transAllow,
                    'spclAllow'=>$spclAllow,
                    'lta'=>$lta,
                    'hra'=>$hra,
                    'bonus'=>$bonus, 'mobile'=>$mobile, 'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $this->load->model('user_model');
                $result = $this->user_model->addNewUser($userInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New User created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'User creation failed');
                }
                
                redirect('addNew');
            }
        }
    }

    
    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOld($userId = NULL)
    {
        if($this->isAdmin() == TRUE || $userId == 1)
        {
            $this->loadThis();
        }
        else
        {
            if($userId == null)
            {
                redirect('userListing');
            }
            
            //$data['roles'] = $this->user_model->getUserRoles();
            $data['departments'] = $this->user_model->getUserDepartments();
            $data['designations'] = $this->user_model->getUserDesignations();
            $data['states'] = $this->user_model->getUserStates();
            $data['userInfo'] = $this->user_model->getUserInfo($userId);
            
            $this->global['pageTitle'] = 'Arya Collateral : Edit Employee';
            
            $this->loadViews("editOld", $this->global, $data, NULL);
        }
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editUser()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $userId = $this->input->post('userId');
            
            /*$this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password','Password','matches[cpassword]|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','matches[password]|max_length[20]');
            $this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            */
            $this->form_validation->set_rules('fname','Full Name','trim|required|max_length[128]');
            $this->form_validation->set_rules('email','Email','trim|required|valid_email|max_length[128]');
            $this->form_validation->set_rules('password','Password','matches[cpassword]|max_length[20]');
            $this->form_validation->set_rules('cpassword','Confirm Password','matches[password]|max_length[20]');
            //$this->form_validation->set_rules('role','Role','trim|required|numeric');
            $this->form_validation->set_rules('mobile','Mobile Number','required|min_length[10]');
            $this->form_validation->set_rules('empCode','Employee Code','required|min_length[3]');
            $this->form_validation->set_rules('panNo','PAN No','required|min_length[10]');
            $this->form_validation->set_rules('aadhar','Aadhar','required|min_length[12]');
            $this->form_validation->set_rules('pfuan','PF UAN','required|min_length[10]');
            $this->form_validation->set_rules('acNum','Account Number','required|min_length[10]');
            $this->form_validation->set_rules('ifscCode','IFSC Code','required|min_length[5]');
            $this->form_validation->set_rules('basic','BASIC','required|min_length[3]');
            if($this->form_validation->run() == FALSE)
            {
                $this->editOld($userId);
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('fname'))));
                $email = strtolower($this->security->xss_clean($this->input->post('email')));
                $password = $this->input->post('password');
                //$roleId = $this->input->post('role');
                $mobile = $this->security->xss_clean($this->input->post('mobile'));
                $empCode = $this->security->xss_clean($this->input->post('empCode'));
                $departmentId = $this->security->xss_clean($this->input->post('departmentId'));
                $designationId = $this->security->xss_clean($this->input->post('designationId'));
                $doj = $this->security->xss_clean($this->input->post('doj'));
                $stateId = $this->security->xss_clean($this->input->post('stateId'));
                $locationId = $this->security->xss_clean($this->input->post('locationId'));
                $panNo = $this->security->xss_clean($this->input->post('panNo'));
                $aadhar = $this->security->xss_clean($this->input->post('aadhar'));
                $pfuan = $this->security->xss_clean($this->input->post('pfuan'));
                $beneficiaryName = $this->security->xss_clean($this->input->post('beneficiaryName'));
                $acNum = $this->security->xss_clean($this->input->post('acNum'));
                $bankName = $this->security->xss_clean($this->input->post('bankName'));
                $branch = $this->security->xss_clean($this->input->post('branch'));
                $city = $this->security->xss_clean($this->input->post('city'));
                $ifscCode = $this->security->xss_clean($this->input->post('ifscCode'));
                $basic = $this->security->xss_clean($this->input->post('basic'));
                $transAllow = $this->security->xss_clean($this->input->post('transAllow'));
                $spclAllow = $this->security->xss_clean($this->input->post('spclAllow'));
                $lta = $this->security->xss_clean($this->input->post('lta'));
                $hra = $this->security->xss_clean($this->input->post('hra'));
                $bonus = $this->security->xss_clean($this->input->post('bonus'));
                
                $userInfo = array();
                
                if(empty($password))
                {
                    /*$userInfo = array('email'=>$email, 'roleId'=>$roleId, 'name'=>$name,
                                    'mobile'=>$mobile, 'updatedBy'=>$this->vendorId, 'updatedDtm'=>date('Y-m-d H:i:s'));*/

                    $userInfo = array('email'=>$email, 'name'=> $name,'empCode'=>$empCode,
                    'departmentId'=>$departmentId,'designationId'=>$designationId, 'doj'=>date('Y-m-d',strtotime($doj)),'stateId'=>$stateId,'locationId'=>$locationId,'panNo'=>$panNo,'aadhar'=>$aadhar,'pfuan'=>$pfuan, 'beneficiaryName'=>$beneficiaryName, 'acNum'=>$acNum, 'bankName'=>$bankName, 'branch'=>$branch,'city'=>$city,'ifscCode'=>$ifscCode,'basic'=>$basic,'transAllow'=>$transAllow, 'spclAllow'=>$spclAllow, 'lta'=>$lta, 'hra'=>$hra, 'bonus'=>$bonus, 'mobile'=>$mobile, 'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'),'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                else
                {
                    $userInfo = array('email'=>$email, 'password'=>getHashedPassword($password), 'empCode'=>$empCode, 'departmentId'=>$departmentId, 'designationId'=>$designationId, 'doj'=>date('Y-m-d',strtotime($doj)),'stateId'=>$stateId,'locationId'=>$locationId,'panNo'=>$panNo,'aadhar'=>$aadhar,'pfuan'=>$pfuan, 'beneficiaryName'=>$beneficiaryName, 'acNum'=>$acNum, 'bankName'=>$bankName, 'branch'=>$branch,'city'=>$city,'ifscCode'=>$ifscCode,'basic'=>$basic,'transAllow'=>$transAllow, 'spclAllow'=>$spclAllow, 'lta'=>$lta, 'hra'=>$hra, 'bonus'=>$bonus, 'mobile'=>$mobile, 'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'),'updatedDtm'=>date('Y-m-d H:i:s'));
                }
                
                $result = $this->user_model->editUser($userInfo, $userId);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Employee updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Employee updation failed');
                }
                
                redirect('userListing');
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
}

?>