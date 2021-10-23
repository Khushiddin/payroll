<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : User_model (User Model)
 * User model class to get to handle user related data 
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Advance_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    
    //function getTotalApprove
    function getAdvanceTotal(){
      $this->db->select('count(BaseTbl.approved) as allcount, BaseTbl.approved')
      ->from('tbl_advances as BaseTbl')
      ->where('BaseTbl.isDeleted', 0)
      ->group_by('BaseTbl.approved');
      $currentUser = $this->session->userdata('userId');
      $empRole = $this->session->userdata('empRole');
      $userarray =  explode(',', $empRole);
      if(in_array(2, $userarray) && count($userarray) < 2){
              $this->db->where('userId',$currentUser);
          }
      $records = $this->db->get()->result();
      return $records;  
    }

    function getPendingTotal(){
        $currentUser = $this->session->userdata('userId');

      $this->db->select('*')
      ->from('tbl_advances as BaseTbl')
      ->where('BaseTbl.userId', $currentUser)
      ->where('BaseTbl.approved', '0')
      ->where('BaseTbl.isDeleted', 0);
      // ->group_by('BaseTbl.approved');
      $records = count($this->db->get()->result());
      // print_r($records);die();
      return $records;  
    }

    function getVendorTotal(){
      $this->db->select('count(BaseTbl.approved) as allcount, BaseTbl.approved')
      ->from('tbl_vendors as BaseTbl')
      ->where('BaseTbl.isDeleted', 0)
      ->group_by('BaseTbl.approved');
      $records = $this->db->get()->result();
      return $records;  
    }

    function getVendor($postData=null){

     $response = array();

     ## Read value
     $draw = $postData['draw'];
     $start = $postData['start'];
     $rowperpage = $postData['length']; // Rows display per page
     $columnIndex = $postData['order'][0]['column']; // Column index
     $columnName = $postData['columns'][$columnIndex]['data']; // Column name
     $columnSortOrder = $postData['order'][0]['dir']='desc'; // asc or desc
     $searchValue = $postData['search']['value']; // Search value
     $approveType = $postData['approveType'];
     ## Search 
     $searchQuery = "";
     if($searchValue != ''){
        $searchQuery = " (BaseTbl.vendorName like '%".$searchValue."%' or BaseTbl.id like '%".$searchValue."%' or BaseTbl.email like '%".$searchValue."%') ";
     }
// print_r($approveType);die();
     ## Total number of records without filtering
     $this->db->select('count(*) as allcount')
     ->from('tbl_vendors as BaseTbl')
     // ->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
     ->where('BaseTbl.isDeleted', 0)
     ->where('BaseTbl.approved',$approveType);
     $records = $this->db->get()->result();
     $totalRecords = $records[0]->allcount;

     ## Total number of record with filtering
      $this->db->select('count(*) as allcount')
      ->from('tbl_vendors as BaseTbl')
      // ->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
      ->where('BaseTbl.isDeleted', 0)
      ->where('BaseTbl.approved',$approveType);
      if($searchQuery != '')
      $this->db->where($searchQuery);
      $records = $this->db->get()->result();
      $totalRecordwithFilter = $records[0]->allcount;

 
     ## Fetch records
     
     $this->db->select('BaseTbl.id, BaseTbl.vendorName, BaseTbl.address, BaseTbl.contactNo, BaseTbl.email, BaseTbl.beneficiaryName,  BaseTbl.createdDtm')
     ->from('tbl_vendors as BaseTbl')
     // ->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
     ->where('BaseTbl.isDeleted', 0)
     ->where('BaseTbl.approved',$approveType);
     
     if($searchQuery != '')
    $this->db->where($searchQuery);
    $this->db->order_by($columnName, $columnSortOrder);
    $this->db->limit($rowperpage, $start);
    $records = $this->db->get()->result();
// print_r($records);
     $role = $this->session->userdata('role');
     $data = array();
     $empRole = $this->session->userdata('empRole');
     $empRoleArray =  explode(',', $empRole);
     $currentUser = $this->session->userdata('userId');
     

     foreach($records as $key => $record ){

        $link = "<a href='".base_url()."viewVendor/".$record->id."' class='btn btn-sm btn-primary'>View</a>";
         
        if(in_array(2, $empRoleArray) && ($approveType=='0' || $approveType=='3')){
            $link.= " <a href='".base_url()."vendor/editOld/".$record->id."' class='btn btn-sm btn-info'>Edit</a>";
            // $link.= " <a href='".base_url()."vendor/deleteVendor/".$record->id."' class='btn btn-sm btn-danger deleteUser'>Delete</a>";
            $link.= " <a href='javascript:void(0);' id='pushButton' onClick='deleteUser(".$record->id.",0)' class='btn btn-sm btn-danger'>Delete</a>";
            $link.= " <a href='javascript:void(0);' id='pushButton' onClick='pushApprove(".$record->id.",0)' class='btn btn-sm btn-info'>Submit</a>";
        }
        $data[] = array( 
          "id"=>$record->id,
           "vendorName"=>$record->vendorName,
           "address"=>$record->address,
           "contactNo"=>$record->contactNo,
           "email"=>$record->email,
           "beneficiaryName"=>$record->beneficiaryName,
           // "acNumber"=>$record->acNumber,
           // "bankName"=>$record->bankName,
           // "branch"=>$record->branch,
           // "city"=>$record->city,
           // "ifsc"=>$record->ifsc,
           // "panNumber"=>$record->panNumber,
           // "gstNumber"=>$record->gstNumber,
           "createdDtm"=>date('d-m-Y',strtotime($record->createdDtm)),
           "link"=>$link
        ); 
     }

     ## Response
     $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
     );

     return $response; 
   }


    function getAdvance($postData=null){

     $response = array();

     ## Read value
     $draw = $postData['draw'];
     $start = $postData['start'];
     $rowperpage = $postData['length']; // Rows display per page
     $columnIndex = $postData['order'][0]['column']; // Column index
     $columnName = $postData['columns'][$columnIndex]['data']; // Column name
     $columnSortOrder = $postData['order'][0]['dir']='desc'; // asc or desc
     $searchValue = $postData['search']['value']; // Search value
     $approveType = $postData['approveType'];
     ## Search 
     $searchQuery = "";
     if($searchValue != ''){
        $searchQuery = " (User.name like '%".$searchValue."%' or User.acNum like '%".$searchValue."%') ";
     }

     ## Total number of records without filtering
     $this->db->select('count(*) as allcount')
     ->from('tbl_advances as BaseTbl')
     ->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
     ->where('BaseTbl.isDeleted', 0)
     ->where('BaseTbl.approved',$approveType);
     $currentUser = $this->session->userdata('userId');
      $empRole = $this->session->userdata('empRole');
      $userarray =  explode(',', $empRole);
      if((in_array(2, $userarray) && count($userarray) < 2) || ($approveType == '0')){
              $this->db->where('User.userId',$currentUser);
          }
     $records = $this->db->get()->result();
     $totalRecords = $records[0]->allcount;

     ## Total number of record with filtering
      $this->db->select('count(*) as allcount')
      ->from('tbl_advances as BaseTbl')
      ->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
      ->where('BaseTbl.isDeleted', 0)
      ->where('BaseTbl.approved',$approveType);
      $currentUser = $this->session->userdata('userId');
      $empRole = $this->session->userdata('empRole');
      $userarray =  explode(',', $empRole);
      if((in_array(2, $userarray) && count($userarray) < 2) || ($approveType == '0')){
              $this->db->where('User.userId',$currentUser);
          }
      if($searchQuery != '')
      $this->db->where($searchQuery);
      $records = $this->db->get()->result();
      $totalRecordwithFilter = $records[0]->allcount;

 
     ## Fetch records
     
     $this->db->select('BaseTbl.id, BaseTbl.userId, BaseTbl.makerAmount, BaseTbl.checkerAmount, BaseTbl.createdDtm, User.name, User.acNum, User.bankName, User.empCode')
     ->from('tbl_advances as BaseTbl')
     ->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
     ->where('BaseTbl.isDeleted', 0)
     ->where('BaseTbl.approved',$approveType);
     $currentUser = $this->session->userdata('userId');
      $empRole = $this->session->userdata('empRole');
      $userarray =  explode(',', $empRole);
      if((in_array(2, $userarray) && count($userarray) < 2) || ($approveType == '0')){
              $this->db->where('User.userId',$currentUser);
          }
     
     if($searchQuery != '')
    $this->db->where($searchQuery);
    $this->db->order_by($columnName, $columnSortOrder);
    $this->db->limit($rowperpage, $start);
    $records = $this->db->get()->result();

     $role = $this->session->userdata('role');
     $data = array();
     $empRole = $this->session->userdata('empRole');
     $empRoleArray =  explode(',', $empRole);
     $currentUser = $this->session->userdata('userId');

     foreach($records as $record ){

        $link = "<a href='".base_url()."viewAdvance/".$record->id."' class='btn btn-sm btn-primary'>View</a>";
        
        // if($role=='3' && ($approveType=='0' || $approveType=='3')){
        if(in_array(2, $empRoleArray) && ($approveType=='0' || $approveType=='3' ) && $currentUser==$record->userId){
            $link.= " <a href='".base_url()."advance/editOld/".$record->id."' class='btn btn-sm btn-info'>Edit</a>";
            // $link.= " <a href='".base_url()."advance/editOld/".$record->id."' class='btn btn-sm btn-danger deleteUser'>Delete</a>";
            $link.= " <a href='javascript:void(0);' id='pushButton' onClick='deleteUser(".$record->id.",0)' class='btn btn-sm btn-danger'>Delete</a>";
            $link.= " <a href='javascript:void(0);' id='pushButton' onClick='pushApprove(".$record->id.",0)' class='btn btn-sm btn-info'>Submit</a>";
        }
        // if((in_array(2, $empRoleArray) && ($approveType=='0' || $approveType=='3' ) && $currentUser==$record->userId) || $approveType!='0')
        // if((($currentUser == $record->userId) && in_array(2, $empRoleArray) ) || ( (in_array(3, $empRoleArray) || in_array(4, $empRoleArray)) && $approveType!='0') )
        $data[] = array( 
           "id"=>$record->id,
           "userId"=>$record->userId,
           "empCode"=>$record->empCode,
           "makerAmount"=>$record->makerAmount,
           "checkerAmount"=>$record->checkerAmount,
           "name"=>$record->name,
           "acNum"=>$record->acNum,
           "createdDtm"=>date('d-m-Y',strtotime($record->createdDtm)),
           "link"=>$link
        ); 
     }

     ## Response
     $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecords,
        "iTotalDisplayRecords" => $totalRecordwithFilter,
        "aaData" => $data
     );

     return $response; 
   }



    function advanceListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.id, BaseTbl.userId, BaseTbl.makerAmount, BaseTbl.checkerAmount,  BaseTbl.createdDtm, User.name, User.acNum, User.bankName');
        $this->db->from('tbl_advances as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function vendorListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.id, BaseTbl.vendorName, BaseTbl.address, BaseTbl.contactNo, BaseTbl.email, BaseTbl.beneficiaryName,  BaseTbl.createdDtm');
        $this->db->from('tbl_vendors as BaseTbl');
        // $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();
        return $query->num_rows();
    }
    
    function advanceListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.id, BaseTbl.userId, BaseTbl.makerAmount, BaseTbl.checkerAmount,  BaseTbl.createdDtm, User.name, User.acNum, User.bankName');
        $this->db->from('tbl_advances as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->order_by('BaseTbl.userId', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }
    
    /**
     * This function is used to get the user roles information
     * @return array $result : This is result of the query
     */
    function getUserRoles()
    {
        $this->db->select('roleId, role');
        $this->db->from('tbl_roles');
        $this->db->where('roleId !=', 1);
        $query = $this->db->get();
        
        return $query->result();
    }

    /**
     * This function is used to check whether email id is already exist or not
     * @param {string} $email : This is email id
     * @param {number} $userId : This is user id
     * @return {mixed} $result : This is searched result
     */
    function checkEmailExists($email, $userId = 0)
    {
        $this->db->select("email");
        $this->db->from("tbl_users");
        $this->db->where("email", $email);   
        $this->db->where("isDeleted", 0);
        if($userId != 0){
            $this->db->where("userId !=", $userId);
        }
        $query = $this->db->get();

        return $query->result();
    }


    function checkEmpCode($empCode, $id = 0)
    {
        $this->db->select("empCode");
        $this->db->from("tbl_users");
        $this->db->where("empCode", $empCode);   
        $this->db->where("isDeleted", 0);
        if($id != 0){
            $this->db->where("id !=", $id);
        }
        $query = $this->db->get();

        return $query->result();
    }
    
    
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewAdvance($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_advances', $userInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    function addNewVendor($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_vendors', $userInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getAdvanceInfo($id)
    {
        $this->db->select('BaseTbl.id, BaseTbl.userId, BaseTbl.makerAmount, BaseTbl.checkerAmount, BaseTbl.makerRemark, BaseTbl.checkerRemark, BaseTbl.accountRemark,  BaseTbl.createdDtm, User.name, User.acNum, User.bankName, User.empCode,User.ifscCode, BaseTbl.approved, BaseTbl.approvedBy, BaseTbl.updatedDtm, CUser.name as checker')
        ->from('tbl_advances as BaseTbl')
        ->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
        ->join('tbl_users as CUser', 'CUser.userId = BaseTbl.approvedBy','left')
        ->where('BaseTbl.isDeleted', 0);
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    function getAdvanceInfoByMonth($id,$month,$year)
    {
        $this->db->select('BaseTbl.id, BaseTbl.userId, BaseTbl.makerAmount, BaseTbl.checkerAmount, BaseTbl.makerRemark, BaseTbl.checkerRemark,  BaseTbl.createdDtm, User.name, User.acNum, User.bankName, User.empCode,User.ifscCode, BaseTbl.approved, BaseTbl.approvedBy, BaseTbl.updatedDtm, CUser.name as checker')
        ->from('tbl_advances as BaseTbl')
        ->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
        ->join('tbl_users as CUser', 'CUser.userId = BaseTbl.approvedBy','left')
        ->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.userId', $id);
        $this->db->where('MONTH(BaseTbl.updatedDtm)', $month);
        $this->db->where('YEAR(BaseTbl.updatedDtm)', $year);
        $query = $this->db->get();
        return $query->result();
    } 

    function getVendorInfo($id)
    {
        $this->db->select('BaseTbl.id, BaseTbl.vendorName, BaseTbl.address, BaseTbl.contactNo, BaseTbl.email, BaseTbl.beneficiaryName,BaseTbl.acNumber,BaseTbl.bankName,BaseTbl.branch,BaseTbl.city,BaseTbl.ifsc,BaseTbl.panNumber,BaseTbl.gstNumber,BaseTbl.approved,  BaseTbl.createdDtm')
        ->from('tbl_vendors as BaseTbl')
        // ->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
        // ->join('tbl_users as CUser', 'CUser.userId = BaseTbl.approvedBy','left')
        ->where('BaseTbl.isDeleted', 0);
        $this->db->where('id', $id);
        $query = $this->db->get();
        return $query->row();
    }
    
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editAdvance($userInfo, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_advances', $userInfo);
        
        return TRUE;
    }

    function editVendor($userInfo, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_vendors', $userInfo);
        
        return TRUE;
    }
    
    
    
    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteUser($userId, $userInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);
        
        return $this->db->affected_rows();
    }


    /**
     * This function is used to match users password for change password
     * @param number $userId : This is user id
     */
    function matchOldPassword($userId, $oldPassword)
    {
        $this->db->select('userId, password');
        $this->db->where('userId', $userId);        
        $this->db->where('isDeleted', 0);
        $query = $this->db->get('tbl_users');
        
        $user = $query->result();

        if(!empty($user)){
            if(verifyHashedPassword($oldPassword, $user[0]->password)){
                return $user;
            } else {
                return array();
            }
        } else {
            return array();
        }
    }
    
    /**
     * This function is used to change users password
     * @param number $userId : This is user id
     * @param array $userInfo : This is user updation info
     */
    function changePassword($userId, $userInfo)
    {
        $this->db->where('userId', $userId);
        $this->db->where('isDeleted', 0);
        $this->db->update('tbl_users', $userInfo);
        
        return $this->db->affected_rows();
    }


    /**
     * This function is used to get user login history
     * @param number $userId : This is user id
     */
    function loginHistoryCount($userId, $searchText, $fromDate, $toDate)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.sessionData, BaseTbl.machineIp, BaseTbl.userAgent, BaseTbl.agentString, BaseTbl.platform, BaseTbl.createdDtm');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.sessionData LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($fromDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) >= '".date('Y-m-d', strtotime($fromDate))."'";
            $this->db->where($likeCriteria);
        }
        if(!empty($toDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) <= '".date('Y-m-d', strtotime($toDate))."'";
            $this->db->where($likeCriteria);
        }
        if($userId >= 1){
            $this->db->where('BaseTbl.userId', $userId);
        }
        $this->db->from('tbl_last_login as BaseTbl');
        $query = $this->db->get();
        
        return $query->num_rows();
    }

    /**
     * This function is used to get user login history
     * @param number $userId : This is user id
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function loginHistory($userId, $searchText, $fromDate, $toDate, $page, $segment)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.sessionData, BaseTbl.machineIp, BaseTbl.userAgent, BaseTbl.agentString, BaseTbl.platform, BaseTbl.createdDtm');
        $this->db->from('tbl_last_login as BaseTbl');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.sessionData  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($fromDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) >= '".date('Y-m-d', strtotime($fromDate))."'";
            $this->db->where($likeCriteria);
        }
        if(!empty($toDate)) {
            $likeCriteria = "DATE_FORMAT(BaseTbl.createdDtm, '%Y-%m-%d' ) <= '".date('Y-m-d', strtotime($toDate))."'";
            $this->db->where($likeCriteria);
        }
        if($userId >= 1){
            $this->db->where('BaseTbl.userId', $userId);
        }
        $this->db->order_by('BaseTbl.id', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }

    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfoById($userId)
    {
        $this->db->select('userId, name, email, mobile, roleId');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
        $this->db->where('userId', $userId);
        $query = $this->db->get();
        
        return $query->row();
    }

    /**
     * This function used to get user information by id with role
     * @param number $userId : This is user id
     * @return aray $result : This is user information
     */
    function getUserInfoWithRole($userId)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.roleId, Roles.role');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Roles','Roles.roleId = BaseTbl.roleId');
        $this->db->where('BaseTbl.userId', $userId);
        $this->db->where('BaseTbl.isDeleted', 0);
        $query = $this->db->get();
        
        return $query->row();
    }

    function getUserDepartments()
    {
        $this->db->select('id, departmentName');
        $this->db->from('tbl_departments');
        $this->db->where('isDeleted', 0);
        $this->db->order_by('departmentName', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function getUserDesignations()
    {
        $this->db->select('id, designationName');
        $this->db->from('tbl_designations');
        $this->db->where('isDeleted', 0);
        $this->db->order_by('designationName', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function getUserStates()
    {
        $this->db->select('id, name');
        $this->db->from('tbl_states');
        $this->db->where('parentId', 0);
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }
    
    function getlocation($stateId)
    {
        $this->db->select('id, name');
        $this->db->from('tbl_states');
        $this->db->where('parentId', $stateId);
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    function employeeListing($searchText = '', $page, $segment)
    {
        
        $month = 9;
        $year = 2019;  
        $this->db->select('BaseTbl.userId, BaseTbl.name, BaseTbl.empCode, BaseTbl.acNum,BaseTbl.ifscCode,BaseTbl.bankName, BaseTbl.basic, BaseTbl.transAllow, BaseTbl.spclAllow, BaseTbl.lta, BaseTbl.hra, BaseTbl.bonus, Slip.presentDays, Slip.month, Slip.year, Slip.tds, Slip.advance_deduction, Slip.PT');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_salary_slip as Slip', 'Slip.userId = BaseTbl.userId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.empCode  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.acNum  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        $this->db->where('Slip.month', $month);
        $this->db->where('Slip.year', $year);
        $this->db->order_by('BaseTbl.userId', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function employeeListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.userId, BaseTbl.name, BaseTbl.empCode, BaseTbl.acNum,BaseTbl.ifscCode,BaseTbl.bankName, BaseTbl.basic, BaseTbl.transAllow, BaseTbl.spclAllow, BaseTbl.lta, BaseTbl.hra, BaseTbl.bonus, Slip.presentDays, Slip.month, Slip.year, Slip.tds, Slip.advance_deduction, Slip.PT');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_salary_slip as Slip', 'Slip.userId = BaseTbl.userId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.empCode  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.acNum  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        $query = $this->db->get();
        
        return $query->num_rows();
    }    
}

  