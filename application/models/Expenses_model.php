<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : User_model (User Model)
 * User model class to get to handle user related data 
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Expenses_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    
    //function getTotalApprove
    function getExpensesTotal(){
      $this->db->select('count(BaseTbl.approved) as allcount, BaseTbl.approved')
      ->from('tbl_expenses as BaseTbl')
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

    function getPendingExp(){
      $currentUser = $this->session->userdata('userId');
      $this->db->select('*')
      ->from('tbl_expenses as BaseTbl')
      ->where('BaseTbl.userId', $currentUser)
      ->where('BaseTbl.approved', '0')
      ->where('BaseTbl.isDeleted', 0);
      $records = count($this->db->get()->result());
      return $records;  
 
    }


    function getExpenses($postData=null){

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
     ->from('tbl_expenses as BaseTbl')
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
      ->from('tbl_expenses as BaseTbl')
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
     
     $this->db->select('BaseTbl.id, BaseTbl.userId, BaseTbl.makerAmount, BaseTbl.checkerAmount,  BaseTbl.createdDtm,User.empCode, User.name, User.acNum, User.bankName, BaseTbl.courier_date, BaseTbl.courier_track_no, BaseTbl.checker_approved_date,BaseTbl.finance_approved_date,BaseTbl.expense_month')
     ->from('tbl_expenses as BaseTbl')
     ->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
     //,ted.entry_date
     //->join('tbl_expenses_details as ted', 'ted.expenses_id = BaseTbl.id','left')
     //->group_by('ted.expenses_id')
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
    $userarray =  explode(',', $empRole);
    $currentUser = $this->session->userdata('userId');

     foreach($records as $record ){

        $link ='';
        $link .= "<a href='".base_url()."/viewExpenses/".$record->id."' class='fa fa-eye'> | </a>";
        // print_r($link);
        if(in_array(2, $userarray) && ($approveType=='0' || $approveType=='3') && $currentUser==$record->userId){
            $link.= " <a href='".base_url()."/expenses/editOld/".$record->id."' class='fa fa-pencil'> | </a>";
            $link.= " <a href='".base_url()."javascript:void(0);' id='pushButton' onClick='pushApprove(".$record->id.",0)' class='fa fa-thumbs-o-up'> | </a>";
            $link.= " <a class='deleteExpense' href='#' data-expenseid=".$record->id." title='Delete'><i class='fa fa-trash'></i></a> |";
        }
        if(in_array(3, $userarray) && $approveType=='1' && $currentUser!=$record->userId){
            $link.= " <a href='".base_url()."/expenses/editOld/".$record->id."' class='fa fa-pencil'> | </a>";
            //$link.= " <a href='javascript:void(0);' id='pushButton' onClick='pushApprove(".$record->id.",1)' class='btn btn-sm btn-info'>Assign</a>";
        }
        if(in_array(4, $userarray) && $approveType=='2'){
            $link.= " <a href='".base_url()."/expenses/editOld/".$record->id."' class='fa fa-pencil'> | </a>";
         /*   $courierdate='<button type="button" class="fa fa-calendar" onclick="func(this)" data="'.$record->id.'" data-toggle="modal" data-target="#myModal"></button>';
         */   //$link.= " <a href='javascript:void(0);' id='pushButton' onClick='pushApprove(".$record->id.",1)' class='btn btn-sm btn-info'>Approved</a>";
        }

        if(in_array(4, $userarray)/* && $approveType=='2' || $approveType=='1'*/){
            $courierdate='<button type="button" class="fa fa-calendar" onclick="func(this)" data="'.$record->id.'" data-toggle="modal" data-target="#myModal"></button>';
        }
        $link .= " <a href='".base_url()."/pdfExpenses/".$record->id."' class='fa fa-file-pdf-o'></a>";

        // if((in_array(2, $userarray) && ($approveType=='0' || $approveType=='3') && $currentUser==$record->userId) || $approveType!='0')
       
            // if((($currentUser == $record->userId) && in_array(2, $userarray) ) || ( (in_array(3, $userarray) || in_array(4, $userarray)) && $approveType!='0') ){

                if(isset($record->checker_approved_date)){
                        $checkerdate=date('d-m-Y',strtotime($record->checker_approved_date));
                }else{
                    $checkerdate='pending';
                }
                if(isset($record->finance_approved_date)){
                    $financedate=date('d-m-Y',strtotime($record->finance_approved_date));
                }else{
                    $financedate='pending';
                }
                if(isset($record->courier_date)){
                    $courierdate=date('d-m-Y',strtotime($record->courier_date));
                }/*else{
                    $courierdate='<button type="button" class="fa fa-calendar" onclick="func(this)" data="'.$record->id.'" data-toggle="modal" data-target="#myModal"></button>';
                }*/

                if(isset($record->courier_track_no)){
                    $courierno= $record->courier_track_no;
                }else{
                    $courierno='pending';
                }

                    $data[] = array( 
                       "id"=>$record->id,
                       "empCode"=>$record->empCode,
                       "userId"=>$record->userId,
                       //"month"=>'',
                       "month"=>$record->expense_month,
                       "makerAmount"=>$record->makerAmount,
                       "checkerAmount"=>$record->checkerAmount,
                       "name"=>$record->name,
                       "acNum"=>$record->acNum,
                       "createdDtm"=>date('d-m-Y',strtotime($record->createdDtm)),
                       "link"=> $link,
                       "checker_approved_date"=>$checkerdate,
                       "finance_approved_date"=>$financedate,
                       "Courier_date"=>$courierdate,
                       "Courier_no"=>$courierno,
                       "entry_date"=>''
                    ); 
                // }
    
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
    function addNewExpenses($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_expenses', $userInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function addNewExpensesDetail($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_expenses_details', $userInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function addNewExpensesLocal($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_expenses_locals', $userInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    
    
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getExpensesInfo($id)
    {
        $expInfo=array();
        $this->db->select('BaseTbl.id, BaseTbl.userId, BaseTbl.makerAmount, BaseTbl.checkerAmount, BaseTbl.makerRemark, BaseTbl.checkerRemark,BaseTbl.accountRemark,  BaseTbl.createdDtm, User.name, User.acNum, User.bankName,User.ifscCode,User.mobile, User.empCode, BaseTbl.approved, BaseTbl.approvedBy, BaseTbl.updatedDtm, CUser.name as checker,ED.entry_date, ED.location, ED.description, ED.travel, ED.mobile_exp, ED.fooding, ED.lodging, ED.local_conv, ED.printing, ED.courier, ED.labour_charge, ED.dunnage, ED.wh_cleaning, ED.lock_key, ED.id as detail_id,  BaseTbl.fileName,Dept.departmentName,BaseTbl.courier_date,BaseTbl.courier_track_no')
        //LD.dis_from, LD.dis_to, LD.km, LD.transport, LD.amount as local_amount, LD.id as local_id,LD.entry_date as local_entry, LD.location as local_location,
        ->from('tbl_expenses as BaseTbl')
        ->join('tbl_expenses_details as ED','ED.expenses_id=BaseTbl.id','left')
        ->where('ED.isDeleted', '0')
        //->join('tbl_expenses_locals as LD','LD.expenses_id=BaseTbl.id','left')
        ->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
        ->join('tbl_users as CUser', 'CUser.userId = BaseTbl.approvedBy','left')
        ->join('tbl_departments as Dept', 'Dept.id = User.departmentId','left')
        ->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.id', $id);
        //echo $sql = $this->db->get_compiled_select('mytable');
        //exit;   
        // $this->db->order_by("User.departmentId", "desc");
        $query = $this->db->get();

        $expInfo = $query->result();
        // print_r($expInfo);die();
        $expLocal = $this->getExpenseslocal($id);
        if(count($expLocal)>0){
            $expInfo['50'] = $expLocal;
        }
        return $expInfo; 

    }

    function getExpenseslocal($id){
        $this->db->select('LD.dis_from, LD.dis_to, LD.km, LD.transport, LD.amount as local_amount, LD.id as local_id,LD.entry_date as local_entry, LD.location as local_location')
        ->from('tbl_expenses_locals as LD')
        ->where('LD.isDeleted', '0')
        ->where('LD.expenses_id',$id);
        $query = $this->db->get();
        $expInfo = $query->result();
        return $expInfo;

    }
    
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editExpenses($userInfo, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_expenses', $userInfo);
        
        return TRUE;
    }

    function editExpensesDetails($userInfo, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_expenses_details', $userInfo);
        return TRUE;
    }

    function editExpensesLocals($userInfo, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_expenses_locals', $userInfo);
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

    
    function getField(){
        $this->db->select('field_name,field')->from('tbl_expenses_field');
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function getMakerAmount($id){
        $this->db->select('makerAmount');
        $this->db->from('tbl_expenses')->where('id', $id);
        $query = $this->db->get();
        return $query->result();

    }   

    function expenseTotalCount($userId,$searchText = '')
    {
        $this->db->select('BaseTbl.userId,BaseTbl.id,BaseTbl.approved,SUM(Detail.mobile_exp) as mobile,SUM(Detail.travel) as travel,SUM(Detail.fooding) as fooding,SUM(Detail.lodging) as lodging,SUM(Detail.local_conv) as local_conv,SUM(Detail.printing) as printing,SUM(Detail.courier) as courier,SUM(Detail.labour_charge) as labour_charge,SUM(Detail.dunnage) as dunnage,SUM(Detail.wh_cleaning) as wh_cleaning,SUM(Detail.lock_key) as lock_key,User.empCode,User.name,User.bankName,User.acNum,User.ifscCode');
        $this->db->from('tbl_expenses as BaseTbl');
        $this->db->join('tbl_expenses_details as Detail', 'Detail.expenses_id = BaseTbl.id','left');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.empCode  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.acNum  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($userId)) {
            $this->db->where('BaseTbl.userId', $userId); 
        }
        $this->db->where('BaseTbl.isDeleted', '0');
        $this->db->where('BaseTbl.approved', '4');
        $this->db->group_by('Detail.expenses_id');
        // $this->db->where('MONTH(Local.entry_date)', '11');
        /*$this->db->where('Slip.month', $month);
        $this->db->where('Slip.year', $year);
        $this->db->where('Trans.month', $month);
        $this->db->where('Trans.year', $year);*/
        $query = $this->db->get();
        
        return $query->num_rows();
        // return $query->result();
    } 

    function expenseTotal($userId,$searchText = '', $page, $segment, $month, $year)
    {
         
        // if(!empty($userId)){
        //     $month = time();
        //     for ($i = 1; $i <= 12; $i++) {
        //       $month = strtotime('last month', $month);
        //       $months[] = date("Y-m-d", $month);
        //     }
        // }else{
        //     $month = $month;
        //     $year = $year;
        // } 

        //print_r($months);die;
          
        $month = $month;
        $year = $year;  
        $this->db->select('BaseTbl.userId,BaseTbl.id,BaseTbl.updatedDtm,BaseTbl.approvedBy,Detail.expenses_id,BaseTbl.approved,SUM(Detail.mobile_exp) as mobile,SUM(Detail.travel) as travel,SUM(Detail.fooding) as fooding,SUM(Detail.lodging) as lodging,SUM(Detail.local_conv) as local_conv,SUM(Detail.printing) as printing,SUM(Detail.courier) as courier,SUM(Detail.labour_charge) as labour_charge,SUM(Detail.dunnage) as dunnage,SUM(Detail.wh_cleaning) as wh_cleaning,SUM(Detail.lock_key) as lock_key,User.empCode,User.name,User.bankName,User.acNum,User.ifscCode,Dept.departmentName,Desg.designationName,BaseTbl.courier_date,BaseTbl.courier_track_no,BaseTbl.checker_approved_date,BaseTbl.finance_approved_date,BaseTbl.createdDtm');
        $this->db->from('tbl_expenses as BaseTbl');
        $this->db->join('tbl_expenses_details as Detail', 'Detail.expenses_id = BaseTbl.id','left');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
                 ->join('tbl_designations as Desg', 'Desg.id = User.designationId','left')
                 ->join('tbl_departments as Dept', 'Dept.id = User.departmentId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.empCode  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.acNum  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        if(!empty($userId)) {
            $this->db->where('BaseTbl.userId', $userId); 
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.approved', '4');

        $this->db->where('MONTH(Detail.entry_date)', $month);
        $this->db->where('YEAR(Detail.entry_date)', $year);
        $this->db->group_by('User.userId');
        $this->db->order_by('BaseTbl.id', 'ASC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        $result = array();
        $result['exp_detail'] = $query->result();

        //echo $this->db->last_query();die;
        $this->db->select('SUM(Local.amount) as amount,User.empCode,User.name,User.bankName,User.acNum,User.ifscCode,User.userId,Dept.departmentName,Desg.designationName,BaseTbl.approvedBy,Local.updatedDtm');
        $this->db->from('tbl_expenses as BaseTbl');
        $this->db->join('tbl_expenses_locals as Local', 'Local.expenses_id = BaseTbl.id','left');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
                 ->join('tbl_designations as Desg', 'Desg.id = User.designationId','left')
                 ->join('tbl_departments as Dept', 'Dept.id = User.departmentId','left');
         if(!empty($userId)) {
            $this->db->where('BaseTbl.userId', $userId); 
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.approved', '4');
        $this->db->where('MONTH(Local.entry_date)', $month);
        $this->db->where('YEAR(Local.entry_date)', $year);
        $this->db->group_by('User.userId');
        $this->db->order_by('BaseTbl.userId', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        // $result = array();
        $result['exp_local'] = $query->result();

        $this->db->select('BaseTbl.userId,SUM(BaseTbl.checkerAmount) as advanceamount,BaseTbl.approvedBy,BaseTbl.updatedDtm,User.empCode,User.name,User.bankName,User.acNum,User.ifscCode,Dept.departmentName,Desg.designationName');
        $this->db->from('tbl_advances as BaseTbl');
        $this->db->join('tbl_users as User', 'User.userId = BaseTbl.userId','left')
                 ->join('tbl_designations as Desg', 'Desg.id = User.designationId','left')
                 ->join('tbl_departments as Dept', 'Dept.id = User.departmentId','left');
        if(!empty($userId)) {
            $this->db->where('BaseTbl.userId', $userId); 
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.approved', '4');
        $this->db->where('MONTH(BaseTbl.updatedDtm)', $month);
        $this->db->where('YEAR(BaseTbl.updatedDtm)', $year);
        $this->db->group_by('User.userId');
        $this->db->order_by('BaseTbl.userId', 'DESC');
        // $this->db->limit($page, $segment);
        $query = $this->db->get();
        // $result = array();
        $result['advance'] = $query->result();
        return $result;
        
    }


    public function updatecourierdates($data,$coid){
        if($coid){
            $this->db->where('id', $coid);
            $this->db->update('tbl_expenses', $data);
            return TRUE;    
        }else{
            return FALSE;
        }
    }

    function deleteExpense($expenseId, $expenseInfo)
    {
        $this->db->trans_start();

        $this->db->where('id', $expenseId);
        $this->db->update('tbl_expenses', $expenseInfo);

        $this->db->where('expenses_id', $expenseId);
        $this->db->update('tbl_expenses_details', $expenseInfo);

        $this->db->where('expenses_id', $expenseId);
        $this->db->update('tbl_expenses_locals', $expenseInfo);

        $this->db->trans_complete();
        
        return $this->db->affected_rows();
    }

}

  