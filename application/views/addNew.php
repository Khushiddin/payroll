<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Employee Management
        <small>Add / Edit Employee</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Employee User Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addUser" action="<?php echo base_url() ?>addNewUser" method="post" role="form">
                        <input type="hidden" name="type" value="<?php echo $type; ?>">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Employee Name</label>
                                        <input type="text" class="form-control required" value="<?php echo set_value('fname'); ?>" id="fname" name="fname" maxlength="128">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email address</label>
                                        <input type="text" class="form-control required email" id="email" value="<?php echo set_value('email'); ?>" name="email" maxlength="128">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control required" id="password" name="password" maxlength="20">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="cpassword">Confirm Password</label>
                                        <input type="password" class="form-control required equalTo" id="cpassword" name="cpassword" maxlength="20">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="empCode">Emp Code</label>
                                        <input type="text" class="form-control required" id="empCode" name="empCode" maxlength="20">
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <?php if($type == 'ARYA'){?>
                                    <div class="form-group">
                                        <label for="department">Department</label>
                                        <select class="form-control required" id="departmentId" name="departmentId">
                                            <option value="0">Select Department</option>
                                            <?php
                                            if(!empty($departments))
                                            {
                                                foreach ($departments as $dt)
                                                {
                                                    ?>
                                                    <option value="<?php echo $dt->id ?>" <?php if($dt->id == set_value('departmentId')) {echo "selected=selected";} ?>><?php echo $dt->departmentName ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                <?php } ?>
                                </div>
                            </div>
                            <?php if($type == 'ARYA'){?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="empCode">Designation</label>
                                        <select class="form-control required" id="designationId" name="designationId">
                                            <option value="0">Select Designation</option>
                                            <?php
                                            if(!empty($designations))
                                            {
                                                foreach ($designations as $dt)
                                                {
                                                    ?>
                                                    <option value="<?php echo $dt->id ?>" <?php if($dt->id == set_value('designationId')) {echo "selected=selected";} ?>><?php echo $dt->designationName ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="department">DOJ</label>
                                        <input type="text" class="form-control required datepicker" id="doj" name="doj">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <select class="form-control required" id="stateId" name="stateId" onchange="getlocation(this.value);">
                                            <option value="0">Select State</option>
                                            <?php
                                            if(!empty($states))
                                            {
                                                foreach ($states as $st)
                                                {
                                                    ?>
                                                    <option value="<?php echo $st->id ?>" <?php if($st->id == set_value('stateId')) {echo "selected=selected";} ?>><?php echo $st->name ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="department">Location</label>
                                        <select class="form-control required" id="locationId" name="locationId">
                                            <option value="0">Select Location</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="panNo">Pan No</label>
                                        <input type="text" class="form-control required" id="panNo" name="panNo" maxlength="20">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="department">Aadhar</label>
                                        <input type="text" class="form-control required" id="aadhar" name="aadhar" maxlength="20">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="empCode">PF UAN</label>
                                        <input type="text" class="form-control required  digits" id="pfuan" name="pfuan" maxlength="20">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Mobile Number</label>
                                        <input type="text" class="form-control required digits" id="mobile" value="<?php echo set_value('mobile'); ?>" name="mobile" maxlength="10">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Mobile Number</label>
                                        <input type="text" class="form-control required digits" id="mobile" value="<?php //echo set_value('mobile'); ?>" name="mobile" maxlength="10">
                                    </div>
                                </div> -->
                                <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="role">Role</label>
                                        <select class="form-control required" id="role" name="role">
                                            <option value="0">Select Role</option>
                                            <?php
                                            //if(!empty($roles))
                                            {
                                                //foreach ($roles as $rl)
                                                {
                                                    ?>
                                                    <option value="<?php //echo $rl->roleId ?>" <?php //if($rl->roleId == set_value('role')) {echo "selected=selected";} ?>><?php //echo $rl->role ?></option>
                                                    <?php
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div> -->    
                            </div>
                        <?php }?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="beneficiaryName">Beneficiary Name</label>
                                        <input type="text" class="form-control required" id="beneficiaryName" name="beneficiaryName" maxlength="20">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="acNum">Account Number</label>
                                        <input type="text" class="form-control required digits" id="acNum" value="<?php echo set_value('acNum'); ?>" name="acNum" maxlength="20">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="bankName">Bank Name</label>
                                        <input type="text" class="form-control required" id="bankName" name="bankName">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Branch</label>
                                        <input type="text" class="form-control required" id="branch"  name="branch">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control required" id="city" name="city" maxlength="30">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">IFSC Code</label>
                                        <input type="text" class="form-control required" id="ifscCode" name="ifscCode" maxlength="20">
                                    </div>
                                </div>
                            </div>
                            <?php if($type == 'ARYA'){?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">BASIC</label>
                                        <input type="basic" class="form-control required digits" id="basic" name="basic" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">TRANSPORT ALLOWANCE</label>
                                        <input type="text" class="form-control required digits" id="transAllow" name="transAllow" maxlength="10">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">SPL. ALLOWANCE</label>
                                        <input type="text" class="form-control required digits" id="spclAllow" name="spclAllow" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">LTA</label>
                                        <input type="text" class="form-control required digits" id="lta" name="lta" maxlength="10">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="city">HRA</label>
                                        <input type="text" class="form-control required digits" id="hra" name="hra" maxlength="10">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mobile">Monthly Bonus</label>
                                        <input type="text" class="form-control required digits" id="bonus" name="bonus" maxlength="10">
                                    </div>
                                </div>
                            </div>
                        <?php }?>
                        </div><!-- /.box-body -->
    
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Submit" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-md-4">
                <?php
                    $this->load->helper('form');
                    $error = $this->session->flashdata('error');
                    if($error)
                    {
                ?>
                <div class="alert alert-danger alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('error'); ?>                    
                </div>
                <?php } ?>
                <?php  
                    $success = $this->session->flashdata('success');
                    if($success)
                    {
                ?>
                <div class="alert alert-success alert-dismissable">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->session->flashdata('success'); ?>
                </div>
                <?php } ?>
                
                <div class="row">
                    <div class="col-md-12">
                        <?php echo validation_errors('<div class="alert alert-danger alert-dismissable">', ' <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button></div>'); ?>
                    </div>
                </div>
            </div>
        </div>    
    </section>
    
</div>
<script src="<?php echo base_url(); ?>assets/js/addUser.js" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script>
    
    function getlocation(val){
        //console.log(baseURL + "getlocation");
        if(val){

            jQuery.ajax({
            type : "POST",
            dataType : "json",
            url : baseURL + "getlocation",
            data : { stateId : val } 
            }).done(function(data){
                $("#locationId").find('option').remove();
                if(data.status = true) {
                    $.each(data.data,function(k,val){
                        //$.each(val,function(k,vald){
                            $("#locationId").append($("<option></option>").attr("value",val.id)
                        .text(val.name));
                        //});
                    });    
                }
                else if(data.status = false) { alert("User deletion failed"); }
                else { alert("Access denied..!"); }
            });   
        }
    }
</script>