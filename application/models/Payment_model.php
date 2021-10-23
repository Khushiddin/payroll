<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : User_model (User Model)
 * User model class to get to handle user related data 
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Payment_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    
    //function getTotalApprove
    function getPaymentTotal(){
      $this->db->select('count(BaseTbl.approved) as allcount, BaseTbl.approved')
      ->from('tbl_vendor_payments as BaseTbl')
      ->where('BaseTbl.isDeleted', 0)
      ->group_by('BaseTbl.approved');
      $records = $this->db->get()->result();
      return $records;  
    }


    function getPayment($postData=null){

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
        $searchQuery = " (User.name like '%".$searchValue."%' or User.acNum like '%".$searchValue."%' or Vendor.vendorName like '%".$searchValue."%') ";
     }

     ## Total number of records without filtering
     $this->db->select('count(*) as allcount')
     ->from('tbl_vendor_payments as BaseTbl')
     ->join('tbl_vendors as Vendor', 'Vendor.id = BaseTbl.vendorId','left')
     ->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
     ->where('BaseTbl.isDeleted', 0)
     ->where('BaseTbl.approved',$approveType);
     $records = $this->db->get()->result();
     $totalRecords = $records[0]->allcount;

     ## Total number of record with filtering
      $this->db->select('count(*) as allcount')
      ->from('tbl_vendor_payments as BaseTbl')
      ->join('tbl_vendors as Vendor', 'Vendor.id = BaseTbl.vendorId','left')
      ->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
      ->where('BaseTbl.isDeleted', 0)
      ->where('BaseTbl.approved',$approveType);
      if($searchQuery != '')
      $this->db->where($searchQuery);
      $records = $this->db->get()->result();
      $totalRecordwithFilter = $records[0]->allcount;

 
     ## Fetch records
     
     $this->db->select('BaseTbl.id, BaseTbl.userId, BaseTbl.makerAmount, BaseTbl.checkerAmount,  BaseTbl.createdDtm, User.name,  CONCAT(Vendor.vendorName," - ",Vendor.acNumber) as vendorName')
     ->from('tbl_vendor_payments as BaseTbl')
     ->join('tbl_vendors as Vendor', 'Vendor.id = BaseTbl.vendorId','left')
     ->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
     ->where('BaseTbl.isDeleted', 0)
     ->where('BaseTbl.approved',$approveType);
     
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

        $link = "<a href='".base_url()."viewPayment/".$record->id."' class='btn btn-sm btn-primary'>View</a>";
        
        if(in_array(2, $empRoleArray) && ($approveType=='0' || $approveType=='3') && $currentUser==$record->userId){
            $link.= " <a href='".base_url()."payment/editOld/".$record->id."' class='btn btn-sm btn-info'>Edit</a>";
            $link.= " <a href='".base_url()."payment/editOld/".$record->id."' class='btn btn-sm btn-danger deleteUser'>Delete</a>";
            $link.= " <a href='javascript:void(0);' id='pushButton' onClick='pushApprove(".$record->id.",0)' class='btn btn-sm btn-info'>Submit</a>";
        }
        $data[] = array( 
           "id"=>$record->id,
           "userId"=>$record->userId,
           "makerAmount"=>$record->makerAmount,
           "checkerAmount"=>$record->checkerAmount,
           "vendorName"=>$record->vendorName,
           "name"=>$record->name,
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
    function addNewPayment($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_vendor_payments', $userInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getPaymentInfo($id)
    {
        $this->db->select('BaseTbl.id,BaseTbl.vendorId, BaseTbl.userId, BaseTbl.makerAmount, BaseTbl.checkerAmount, BaseTbl.makerRemark, BaseTbl.checkerRemark,  BaseTbl.createdDtm, User.name, User.acNum, User.bankName, User.empCode,User.ifscCode, BaseTbl.approved, BaseTbl.approvedBy, BaseTbl.updatedDtm,BaseTbl.file_name, CUser.name as checker, CONCAT(Vendor.vendorName,"-",Vendor.acNumber) as vendorName')
        ->from('tbl_vendor_payments as BaseTbl')
        ->join('tbl_vendors as Vendor', 'Vendor.id = BaseTbl.vendorId','left')
        ->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
        ->join('tbl_users as CUser', 'CUser.userId = BaseTbl.approvedBy','left')
        ->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.id', $id);
        $query = $this->db->get();
        return $query->row();
    }
    
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editPayment($userInfo, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_vendor_payments', $userInfo);
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

    function getVendors(){
        $this->db->select('id, CONCAT(vendorName," - ",acNumber) as name');
        $this->db->from('tbl_vendors');
        $this->db->where('isDeleted', 0);
        $this->db->where('approved', '4');
        $this->db->order_by('vendorName', 'ASC');
        $query = $this->db->get();
        return $query->result();    
    }

    function vendorTotal($searchText = '', $page, $segment, $month, $year)
    {
         
        $month = $month;
        $year = $year;  
        $this->db->select('BaseTbl.id,BaseTbl.vendorName,BaseTbl.approved,Detail.makerAmount,Detail.checkerAmount,Detail.createdDtm,User.name');
        $this->db->from('tbl_vendors as BaseTbl');
        $this->db->join('tbl_vendor_payments as Detail', 'Detail.vendorId = BaseTbl.id','left');
        $this->db->join('tbl_users as User', 'User.userId = Detail.userId','left');
                 //->join('tbl_designations as Desg', 'Desg.id = User.designationId','left')
                 //->join('tbl_departments as Dept', 'Dept.id = User.departmentId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.contactNo  LIKE '%".$searchText."%'
                            OR  BaseTbl.vendorName  LIKE '%".$searchText."%'
                            OR  BaseTbl.email  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.approved', '4');
        $this->db->where('MONTH(Detail.createdDtm)', $month);
        $this->db->where('YEAR(Detail.createdDtm)', $year);
        $this->db->group_by('User.userId');
        $this->db->order_by('BaseTbl.id', 'ASC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        $result = array();
        $result['exp_detail'] = $query->result();

        return $result;
  
    }

    function vendorTotalCount($searchText = '')
    {
        $this->db->select('BaseTbl.id,BaseTbl.vendorName,BaseTbl.approved');
        $this->db->from(' tbl_vendors as BaseTbl');
        $this->db->join('tbl_vendor_payments as Detail', 'Detail.vendorId = BaseTbl.id','left');
        $this->db->join('tbl_users as User', 'User.userId = Detail.userId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.contactNo  LIKE '%".$searchText."%'
                            OR  BaseTbl.vendorName  LIKE '%".$searchText."%'
                            OR  BaseTbl.email  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', '0');
        $this->db->where('BaseTbl.approved', '4');
        $this->db->group_by('Detail.vendorId');
        $query = $this->db->get();
        return $query->num_rows();
        // return $query->result();
    }
}

  