<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Manage Role
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Select Checkbox</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    <?php
                    $userarray =  explode(',', $user->empRole);
                           
                    ?>
                    <?php $this->load->helper("form"); ?>
                    <form role="form" id="addDesignation" action="<?= base_url().'updateRole/'.$user->userId; ?>" method="post" role="form">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <input type="Checkbox" name="role[]" value="2" <?php if(in_array(2, $userarray)) { ?> checked <?php } ?> >
                                        <label for="fname">Maker</label>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <input type="Checkbox" name="role[]" value="3" <?php if(in_array(3, $userarray)) { ?> checked <?php } ?>>
                                        <label for="fname">Checker</label>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <input type="Checkbox" name="role[]" id="account"  onchange="abc()" value="4" <?php if(in_array(4, $userarray)) { ?> checked <?php } ?> >
                                        <label for="fname">Account</label>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <input type="Checkbox" name="role[]" id="advanceCheck"  onchange="abc()" value="5" <?php if(in_array(5, $userarray)) { ?> checked <?php } ?> >
                                        <label for="fname">Advance</label>
                                        <input type="text" name="advanceLimit" placeholder="Limit" id="advanceLimit" value="<?php echo $user->advanceLimit ?>" style="display: none;">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <input type="Checkbox" name="role[]" value="6" onchange="expense()" id="expenseCheck" <?php if(in_array(6, $userarray)) { ?> checked <?php } ?>>
                                        <label for="fname">Expense</label>
                                        <input type="text" name="expenseLimit" placeholder="Limit" id="expenseLimit" value="<?php echo $user->expenseLimit ?>" style="display: none;">
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <input type="Checkbox" name="role[]" value="7" onchange="vendor()" id="vendorCheck" <?php if(in_array(7, $userarray)) { ?> checked <?php } ?>>
                                        <label for="fname">Vendor</label>
                                        <input type="text" name="vendorLimit" placeholder="Limit" id="vendorLimit" value="<?php echo $user->vendorLimit ?>" style="display: none;">
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                        <!-- /.box-body -->
    
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
<script src="<?php echo base_url(); ?>assets/js/addDepartment.js" type="text/javascript"></script>

<script type="text/javascript">
    
    function abc()
    {
   var check = $("#advanceCheck").prop('checked');
   if(check == true){
    $("#advanceLimit").show();
   }else{
    $("#advanceLimit").hide();
   }

}

function expense()
    {
        console.log('as');
   var expense =  $("#expenseCheck").prop('checked');
   if(expense == true){
    $("#expenseLimit").show();
   }else{
    $("#expenseLimit").hide();
   }
  
}

function vendor()
    {
        console.log('as');
   var expense =  $("#vendorCheck").prop('checked');
   if(expense == true){
    $("#vendorLimit").show();
   }else{
    $("#vendorLimit").hide();
   }
  
}

$(document).ready(function(){
         var check = $("#advanceCheck").prop('checked');
   if(check == true){
    $("#advanceLimit").show();
   }else{
    $("#advanceLimit").hide();
   }

   var expense =  $("#expenseCheck").prop('checked');
   if(expense == true){
    $("#expenseLimit").show();
   }else{
    $("#expenseLimit").hide();
   }

   var expense =  $("#vendorCheck").prop('checked');
   if(expense == true){
    $("#vendorLimit").show();
   }else{
    $("#vendorLimit").hide();
   }

   })

</script>