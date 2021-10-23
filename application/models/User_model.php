<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : User_model (User Model)
 * User model class to get to handle user related data 
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class User_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function userListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.createdDtm, Role.role','BaseTbl.empCode','BaseTbl.empCode','Dept.departmentName','Des.designationName, BaseTbl.isDeleted');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        $this->db->join('tbl_departments as Dept', 'Dept.id = BaseTbl.departmentId','left');
        $this->db->join('tbl_designations as Des', 'Des.id = BaseTbl.designationId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        //$this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        $query = $this->db->get();
        
        return $query->num_rows();
    }
    
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @param number $page : This is pagination offset
     * @param number $segment : This is pagination limit
     * @return array $result : This is result
     */
    function userListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.pan_verify, BaseTbl.name, BaseTbl.mobile, BaseTbl.createdDtm, Role.role,BaseTbl.empCode, Dept.departmentName, Des.designationName, BaseTbl.isDeleted, BaseTbl.panNo');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        $this->db->join('tbl_departments as Dept', 'Dept.id = BaseTbl.departmentId','left');
        $this->db->join('tbl_designations as Des', 'Des.id = BaseTbl.designationId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        //$this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        $this->db->where('BaseTbl.empType', 'ARYA');
        $this->db->order_by('BaseTbl.userId', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
         
        $result = $query->result();
        return $result;
    }

    function eUserListing($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.createdDtm, Role.role,BaseTbl.empCode, Dept.departmentName, Des.designationName,BaseTbl.isDeleted');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        $this->db->join('tbl_departments as Dept', 'Dept.id = BaseTbl.departmentId','left');
        $this->db->join('tbl_designations as Des', 'Des.id = BaseTbl.designationId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        //$this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        $this->db->where('BaseTbl.empType', 'EASY');
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
    function addNewUser($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_users', $userInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();

        //$this->db->last_query();die;
        
        return $insert_id;
    }

    function addSalarySlip($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_salary_slip', $userInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }

    function addUserTrans($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_user_trans', $userInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getUserInfo($userId)
    {
        $this->db->select('userId, name, email, mobile, roleId,empRole,empCode,departmentId,designationId,doj,stateId,locationId,panNo,aadhar, pfuan, beneficiaryName, acNum, bankName, branch, city, ifscCode, basic, transAllow, spclAllow, lta, hra, bonus, advanceLimit, expenseLimit, vendorLimit');
        $this->db->from('tbl_users');
        $this->db->where('isDeleted', 0);
		$this->db->where('roleId !=', 1);
        $this->db->where('userId', $userId);
        $query = $this->db->get();
        
        //echo $this->db->last_query();die;
        return $query->row();
    }

    function getUserInfoByCode($empCode)
    {
        $this->db->select('userId, name, email, mobile, roleId,empRole,empCode,departmentId,designationId,doj,stateId,locationId,panNo,aadhar, pfuan, beneficiaryName, acNum, bankName, branch, city, ifscCode, basic, transAllow, spclAllow, lta, hra, bonus');
        $this->db->from('tbl_users');
        //$this->db->where('isDeleted', 0);
        $this->db->where('roleId !=', 1);
        $this->db->where('empCode', $empCode);
        $query = $this->db->get();
        return $query->row();
    }

    function getAllUsers($searchText = '', $page, $segment)
    {
        $this->db->select('BaseTbl.userId, BaseTbl.email, BaseTbl.name, BaseTbl.mobile, BaseTbl.createdDtm, Role.role,BaseTbl.empCode, Dept.departmentName, Des.designationName');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_roles as Role', 'Role.roleId = BaseTbl.roleId','left');
        $this->db->join('tbl_departments as Dept', 'Dept.id = BaseTbl.departmentId','left');
        $this->db->join('tbl_designations as Des', 'Des.id = BaseTbl.designationId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.email  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.mobile  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        $this->db->where('BaseTbl.empType', 'ARYA');
        $this->db->order_by('BaseTbl.userId', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
         
        $result = $query->result();
        return $result;
    }
    
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editUser($userInfo, $userId)
    {
        $this->db->where('userId', $userId);
        $this->db->update('tbl_users', $userInfo);
        
        return TRUE;
    }

    function editSalaryStructure($userInfo, $empCode)
    {
        $this->db->where('empCode', $empCode);
        $this->db->update('tbl_users', $userInfo);
        
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
        $this->db->select('userId, name, email, mobile, roleId, isDeleted');
        $this->db->from('tbl_users');
        //$this->db->where('isDeleted', 0);
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
        return $result = $query->row();
        //return $result->userId;
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

    function employeeListing($searchText = '', $page, $segment, $month, $year)
    {
        
        $month = $month;
        $year = $year;  
        $this->db->select('BaseTbl.userId, BaseTbl.name, BaseTbl.empCode, BaseTbl.acNum,BaseTbl.ifscCode,BaseTbl.bankName, Trans.basic, Trans.transAllow, Trans.spclAllow, Trans.lta, Trans.hra, Trans.bonus, Slip.presentDays, Slip.month, Slip.year, Slip.tds, Slip.advance_deduction, Slip.PT, Slip.arrear, Slip.incentive, Slip.rimbersement, Slip.arrear_pf, Slip.arrear_esic, Slip.other_deduction, Slip.lwf');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_salary_slip as Slip', 'Slip.userId = BaseTbl.userId','left');
        $this->db->join('tbl_user_trans as Trans', 'Trans.userId = BaseTbl.userId','left');
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
        $this->db->where('Trans.month', $month);
        $this->db->where('Trans.year', $year);
        $this->db->order_by('BaseTbl.userId', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        $result = $query->result();
        return $result;
    }

    function employeeListingCount($searchText = '')
    {
        $this->db->select('BaseTbl.userId, BaseTbl.name, BaseTbl.empCode, BaseTbl.acNum,BaseTbl.ifscCode,BaseTbl.bankName, Trans.basic, Trans.transAllow, Trans.spclAllow, Trans.lta, Trans.hra, Trans.bonus, Slip.presentDays, Slip.month, Slip.year, Slip.tds, Slip.advance_deduction, Slip.PT');
        $this->db->from('tbl_users as BaseTbl');
        $this->db->join('tbl_salary_slip as Slip', 'Slip.userId = BaseTbl.userId','left');
        $this->db->join('tbl_user_trans as Trans', 'Trans.userId = BaseTbl.userId','left');
        if(!empty($searchText)) {
            $likeCriteria = "(BaseTbl.empCode  LIKE '%".$searchText."%'
                            OR  BaseTbl.name  LIKE '%".$searchText."%'
                            OR  BaseTbl.acNum  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('BaseTbl.isDeleted', 0);
        $this->db->where('BaseTbl.roleId !=', 1);
        //$this->db->where('Slip.month', $month);
        //$this->db->where('Slip.year', $year);
        //$this->db->where('Trans.month', $month);
        //$this->db->where('Trans.year', $year);
        $query = $this->db->get();
        
        return $query->num_rows();
    } 

    function checkEmpAttendance($empCode,$month,$year){
        if($empCode){
            $this->db->select('BaseTbl.id')->from('tbl_salary_slip as BaseTbl')->where('BaseTbl.empCode',$empCode)->where('BaseTbl.month',$month)->where('BaseTbl.year',$year);
            $query = $this->db->get();
            return $query->num_rows();
        }
    }   

    function checkEmpAtt($empCode){
        if($empCode){
            $this->db->select('BaseTbl.userId')->from('tbl_users as BaseTbl')->where('BaseTbl.empCode',$empCode);
            $query = $this->db->get();
            return $query->num_rows();
        }
    }

    function getdesignationId($designation)
    {
        if($designation){
            $this->db->select('id');
            $this->db->from('tbl_designations');
            $this->db->where('isDeleted', 0);
            $this->db->where('designationName ', $designation);
            $query = $this->db->get();
           if($query->num_rows()>0){
                return $query->row()->id;        
           }
                    
        }
    }

    function getdepartmentId($department)
    {
        if($department){
            $this->db->select('id');
            $this->db->from('tbl_departments');
            $this->db->where('isDeleted', 0);
            $this->db->where('departmentName ', $department);
            $query = $this->db->get();
           if($query->num_rows()>0){
                return $query->row()->id;        
           }
                    
        }
    }
    function getstateId($state)
    {
        if($state){
            $this->db->select('id');
            $this->db->from('tbl_states');
            $this->db->where('name ', $state);
            $query = $this->db->get();
           if($query->num_rows()>0){
                return $query->row()->id;        
           }
                    
        }
    }

    function dateformat($value) {
        if (strstr($value, "/")) {
            $dateValue = explode("/", $value);
        } else {
            $dateValue = explode("-", $value);
        }
        if ($dateValue[1] > 12 && $dateValue[0] <= 12) {
            $dateValue = explode("-", $dateValue[1] . "-" . $dateValue[0] . "-" . $dateValue[2]);
        }
        $value = $this->curDate($dateValue[0] . "-" . $dateValue[1] . "-" . $dateValue[2], "d-m-Y");
        return $value;
    }

    function curDate($dat,$type){
       return date('Y-m-d',strtotime($dat));
    }

    function getUserName($id)
    {
        $this->db->select('name');
        $this->db->from('tbl_users');
        $this->db->where('userId', $id);
        $query = $this->db->get();
        
        return $query->row();
    }

}

  