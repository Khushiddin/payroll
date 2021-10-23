<?php if(!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class : User_model (User Model)
 * User model class to get to handle user related data 
 * @author : Kishor Mali
 * @version : 1.1
 * @since : 15 November 2016
 */
class Payroll_model extends CI_Model
{
    
        
function salarySlip($userId, $fromDate, $toDate)
    {
        $userId = $userId;
        $this->db->select('id, month, presentDays');
        $this->db->from('tbl_salary_slip as Slip');
        $this->db->join('tbl_users as User', 'User.userId = Slip.userId','left');
        if(!empty($month)) {
            $this->db->where('Slip.month',$month);
        }
        if(!empty($year)) {
            $this->db->where('Slip.year',$year);
        }
        if(!empty($userId)) {
            $this->db->where('Slip.userId',$userId);
        }
        $this->db->order_by('Slip.id', 'DESC');
        
        $query = $this->db->get();
        
        $result = $query->result();        
        
        return $result;
    }

function userSalarySlip($payrollId){

    if($payrollId){
        
        $this->db->select('User.*,Slip.month,Slip.year,Slip.presentDays,Slip.tds, Dept.departmentName, Des.designationName, ST.name as location, Slip.advance_deduction, Slip.PT, Slip.arrear, Slip.incentive, Slip.rimbersement, Slip.arrear_pf, Slip.arrear_esic,Tran.basic as Tbasic,Tran.spclAllow as TspclAllow,Tran.lta as Tlta,Tran.hra as Thra,Tran.bonus as Tbonus,Tran.gross,Tran.epf,Tran.esi,Tran.advance,Tran.pt,Tran.deduction,Tran.payroll');
        $this->db->from('tbl_salary_slip as Slip');
        $this->db->join('tbl_users as User','User.userId=Slip.userId','left');
        $this->db->join('tbl_departments as Dept','Dept.id=User.departmentId','left');
        $this->db->join('tbl_designations as Des','Des.id=User.designationId','left');
        $this->db->join('tbl_states as ST','ST.id=User.locationId','left');
        $this->db->join('tbl_user_trans as Tran','Tran.userId=Slip.userId','Tran.month=Slip.month','Tran.year=Slip.year','left');
        $this->db->where('Slip.id',$payrollId);
        $query = $this->db->get();
        $result =  $query->result();

        return $result;

    }
}


    
}

  