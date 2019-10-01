<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

require APPPATH . '/libraries/BaseController.php';

/**
 * Class : User (UserController)
 * User Class to control all user related operations.
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Designation extends BaseController
{
    /**
     * This is default constructor of the class
     */
    public function __construct()
    {
        parent::__construct();
        $this->load->model('designation_model');
        $this->isLoggedIn();   
    }
    
    /**
     * This function used to load the first screen of the user
     */
    public function index()
    {
        $this->global['pageTitle'] = 'Aryadhan : Dashboard';
        
        $this->loadViews("dashboard", $this->global, NULL , NULL);
    }
    
    /**
     * This function is used to load the user list
     */
    function designationListing()
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
            
            $count = $this->designation_model->designationListingCount($searchText);

			$returns = $this->paginationCompress ( "designationListing/", $count, 10 );
            
            $data['designationRecords'] = $this->designation_model->designationListing($searchText, $returns["page"], $returns["segment"]);
            
            $this->global['pageTitle'] = 'Aryadhan : Designation Listing';
            
            $this->loadViews("designation/designation", $this->global, $data, NULL);
        }
    }

    /**
     * This function is used to load the add new form
     */
    function addNewDesignation()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->model('designation_model');
            
            $this->global['pageTitle'] = 'Aryadhan : Add New Designation';

            $this->loadViews("designation/addNewDesignation", $this->global, "", NULL);
        }
    }

    /**
     * This function is used to check whether email already exist or not
     */
    
    /**
     * This function is used to add new user to the system
     */
    function addNewDesignationSave()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $this->form_validation->set_rules('designationName','Designation Name','trim|required|max_length[128]');
            if($this->form_validation->run() == FALSE)
            {
                $this->addNewDesignation();
            }
            else
            {
                $name = ucwords(strtolower($this->security->xss_clean($this->input->post('designationName'))));
                
                $designationInfo = array('designationName'=>$name, 'createdBy'=>$this->vendorId, 'createdDtm'=>date('Y-m-d H:i:s'));
                
                $this->load->model('designation_model');
                $result = $this->designation_model->addNewDesignation($designationInfo);
                
                if($result > 0)
                {
                    $this->session->set_flashdata('success', 'New Designation created successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Designation creation failed');
                }
                
                redirect('addNewDesignation');
            }
        }
    }

    
    /**
     * This function is used load user edit information
     * @param number $userId : Optional : This is user id
     */
    function editOld($designationId = NULL)
    {
        
            if($designationId == null)
            {
                redirect('designationListing');
            }
            
            $data['designationInfo'] = $this->designation_model->getDesignationInfo($designationId);
            
            $this->global['pageTitle'] = 'Aryadhan : Edit Designation';
            
            $this->loadViews("designation/editOld", $this->global, $data, NULL);
        
    }
    
    
    /**
     * This function is used to edit the user information
     */
    function editDesignation()
    {
        if($this->isAdmin() == TRUE)
        {
            $this->loadThis();
        }
        else
        {
            $this->load->library('form_validation');
            
            $id = $this->input->post('id');
            
            $this->form_validation->set_rules('designationName','Designation Name','trim|required|max_length[128]');
            
            if($this->form_validation->run() == FALSE)
            {
                $this->editOld($id);
            }
            else
            {
                $designationName = ucwords(strtolower($this->security->xss_clean($this->input->post('designationName'))));
                
                $designationInfo = array();
                
                $designationInfo = array('designationName'=>ucwords($designationName), 'updatedDtm'=>date('Y-m-d H:i:s'));
                
                $result = $this->designation_model->editDesignation($designationInfo, $id);
                
                if($result == true)
                {
                    $this->session->set_flashdata('success', 'Designation updated successfully');
                }
                else
                {
                    $this->session->set_flashdata('error', 'Designation updation failed');
                }
                
                redirect('designationListing');
            }
        }
    }


    /**
     * This function is used to delete the user using userId
     * @return boolean $result : TRUE / FALSE
     */
    function deleteDesignation()
    {
        if($this->isAdmin() == TRUE)
        {
            echo(json_encode(array('status'=>'access')));
        }
        else
        {
            $id = $this->input->post('id');
            $designationInfo = array('isDeleted'=>1, 'updatedDtm'=>date('Y-m-d H:i:s'));
            
            $result = $this->designation_model->deleteDesignation($id, $designationInfo);
            
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

}

?>