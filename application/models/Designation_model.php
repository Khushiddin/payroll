<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : User_model (User Model)
 * User model class to get to handle user related data 
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Designation_model extends CI_Model
{
    /**
     * This function is used to get the user listing count
     * @param string $searchText : This is optional search text
     * @return number $count : This is row count
     */
    function designationListingCount($searchText = '')
    {
        $this->db->select('id, designationName, createdDtm');
        $this->db->from('tbl_designations');
        if(!empty($searchText)) {
            $likeCriteria = "(designationName  LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('isDeleted', 0);
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
    function designationListing($searchText = '', $page, $segment)
    {
        $this->db->select('id, designationName, createdDtm');
        $this->db->from('tbl_designations');
        if(!empty($searchText)) {
            $likeCriteria = "(designationName LIKE '%".$searchText."%')";
            $this->db->where($likeCriteria);
        }
        $this->db->where('isDeleted', 0);
       $this->db->order_by('id', 'DESC');
        $this->db->limit($page, $segment);
        $query = $this->db->get();
        
        $result = $query->result();        
        return $result;
    }
    
    
    
    /**
     * This function is used to add new user to system
     * @return number $insert_id : This is last inserted id
     */
    function addNewDesignation($userInfo)
    {
        $this->db->trans_start();
        $this->db->insert('tbl_designations', $userInfo);
        
        $insert_id = $this->db->insert_id();
        
        $this->db->trans_complete();
        
        return $insert_id;
    }
    
    /**
     * This function used to get user information by id
     * @param number $userId : This is user id
     * @return array $result : This is user information
     */
    function getDesignationInfo($designationId)
    {
        $this->db->select('id, designationName');
        $this->db->from('tbl_designations');
        $this->db->where('isDeleted', 0);
		$this->db->where('id', $designationId);
        $query = $this->db->get();
        return $query->row();
    }
    
    
    /**
     * This function is used to update the user information
     * @param array $userInfo : This is users updated information
     * @param number $userId : This is user id
     */
    function editDesignation($designationInfo, $id)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_designations', $designationInfo);
        
        return TRUE;
    }
    
    
    
    /**
     * This function is used to delete the user information
     * @param number $userId : This is user id
     * @return boolean $result : TRUE / FALSE
     */
    function deleteDesignation($id, $departmentInfo)
    {
        $this->db->where('id', $id);
        $this->db->update('tbl_designations', $departmentInfo);
        return $this->db->affected_rows();
    }

}

  