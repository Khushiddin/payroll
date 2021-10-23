<?php
$id = $expensesInfo[0]->id;
$name = $expensesInfo[0]->name;
$empCode = $expensesInfo[0]->empCode;
$bankName = $expensesInfo[0]->bankName;
$acNum = $expensesInfo[0]->acNum;
$ifsc = $expensesInfo[0]->ifscCode;
$userId = $expensesInfo[0]->userId;
$makerAmount = $expensesInfo[0]->makerAmount;
$makerRemark = $expensesInfo[0]->makerRemark;
$checkerAmount = $expensesInfo[0]->checkerAmount;
$checkerRemark = $expensesInfo[0]->checkerRemark;
$accountRemark = $expensesInfo[0]->accountRemark;
$approvedBy = $expensesInfo[0]->approvedBy;
$approved = $expensesInfo[0]->approved;
$checker = $expensesInfo[0]->checker;
$updatedDtm = $expensesInfo[0]->updatedDtm;
$fileName = $expensesInfo[0]->fileName;
$department = $expensesInfo[0]->departmentName;
$role = $this->session->userdata('role');
$courier_track_no = $expensesInfo[0]->courier_track_no;
$courier_date = $expensesInfo[0]->courier_date

?>
<style>
    tbody {
  display:block;
  max-height:300px;
  overflow-y:auto;
}
thead, tbody tr {
  display:table;
  width: var(--table-width);
  table-layout:fixed;
  word-wrap: break-word;

}
.form-control {
    display: block;
    width: 100%;
    height: 24px;
    padding: 3px 3px;
    font-size: 12px;
    line-height: 1.42857143;
    color: #555;
    background-color: #fff;
    background-image: none;
    border: 1px solid #ccc;
    border-radius: 4px;
    -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    box-shadow: inset 0 1px 1px rgba(0,0,0,.075);
    -webkit-transition: border-color ease-in-out .15s,-webkit-box-shadow ease-in-out .15s;
    -o-transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
    transition: border-color ease-in-out .15s,box-shadow ease-in-out .15s;
}

</style>
<link href='//cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css' rel='stylesheet' type='text/css'>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js"></script>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Expenses Management
        <small>Expenses Advance</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">View Expenses Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form role="form" action="<?php echo base_url() ?>checkExpenses" method="post" id="checkAdvance" role="form">
                        <!-- <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Name</label>
                                        <div><?php echo $name.' ('.$empCode.')' ?></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Designation</label>
                                        <div>Manager</div>
                                    </div>
                                    
                                </div>
                        </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Bank Name</label>
                                        <div><?=$bankName?></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Bank A/C No.</label>
                                        <div><?=$acNum?></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Bank IFSC</label>
                                        <div><?=$ifsc?></div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Request Expenses Amount</label>
                                        <div><?=$makerAmount?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Request Date</label>
                                        <div><?php echo date('d-m-Y',strtotime($expensesInfo[0]->createdDtm));?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Expenses Approved Amount</label>
                                        <div><?=$checkerAmount?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Checker Remarks</label>
                                        <div><?=$checkerRemark?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Account Remarks</label>
                                        <div><?=$accountRemark?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Department</label>
                                        <div><?=$department?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Approved By</label>
                                        <div><?=$checker?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Approved Date</label>
                                        <div><?php echo date('d-m-Y',strtotime($updatedDtm)); ?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Advance Approved Total</label>
                                        <div><?=$advTotal?></div>
                                    </div>
                                </div>
                            </div> -->
                            <div style="margin-left:12px;">
                        <div class="row">
                            <div class="col-md-3">                                
                                <div class="form-group">
                                    <label for="fname">Name:</label>
                                    <div><?php echo $name.' ('.$empCode.')' ?></div>
                                </div>
                            </div>
                            <div class="col-md-3">                                
                                <div class="form-group">
                                    <label for="fname">Designation:</label>
                                    <div>Manager</div>
                                </div>
                            </div>
                            <div class="col-md-3">                                
                                <div class="form-group">
                                    <label for="fname">Bank Name:</label>
                                    <div><?=$bankName?></div>
                                </div>
                            </div>
                            <div class="col-md-3">                                
                                <div class="form-group">
                                    <label for="fname">Bank A/C No.:</label>
                                    <div><?=$acNum?></div>
                                </div>
                            </div>
                        </div>
                            <div class="row">
                                <div class="col-md-3">                                
                                    <div class="form-group">
                                        <label for="fname">Bank IFSC:</label>
                                        <div><?=$ifsc?></div>
                                    </div>
                                </div>
                                <div class="col-md-3">                                
                                    <div class="form-group">
                                        <label for="fname">Request Expenses Amount:</label>
                                        <div><?=$makerAmount?></div>
                                    </div>
                                </div>
                                <div class="col-md-3">                                
                                    <div class="form-group">
                                        <label for="fname">Request Date:</label>
                                        <div><?php echo date('d-m-Y',strtotime($expensesInfo[0]->createdDtm));?></div>
                                    </div>
                                </div>
                                <div class="col-md-3">                                
                                    <div class="form-group">
                                        <label for="fname">Expenses Approved Amount:</label>
                                        <div><?=$checkerAmount?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">                                
                                    <div class="form-group">
                                        <label for="fname">Checker Remarks:</label>
                                        <div><?=$checkerRemark?></div>
                                    </div>
                                </div>
                                <div class="col-md-3">                                
                                    <div class="form-group">
                                        <label for="fname">Account Remarks:</label>
                                        <div><?=$accountRemark?></div>
                                    </div>
                                </div>
                                <div class="col-md-3">                                
                                    <div class="form-group">
                                        <label for="fname">Department:</label>
                                        <div><?=$department?></div>
                                    </div>
                                </div>
                                <div class="col-md-3">                                
                                    <div class="form-group">
                                        <label for="fname">Approved By:</label>
                                        <div><?=$checker?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">                                
                                    <div class="form-group">
                                        <label for="fname">Approved Date:</label>
                                        <div><?php echo date('d-m-Y',strtotime($updatedDtm)); ?></div>
                                    </div>
                                </div>
                                <div class="col-md-3">                                
                                    <div class="form-group">
                                        <label for="fname">Advance Approved Total:</label>
                                        <div><?=$advTotal?></div>
                                    </div>
                                </div>
                                <div class="col-md-3">                                
                                    <div class="form-group">
                                        <label for="fname">Courier Track No.:</label>
                                        <div><?=$courier_track_no?></div>
                                    </div>
                                </div>
                                <div class="col-md-3">                                
                                <div class="form-group">
                                    <label for="fname">Courier Date:</label>
                                    <div><?=$courier_date?></div>
                                </div>
                            </div>
                            </div>
                        </div>
                    </form>

                    <div class="row table-responsive">
                        <table id='empTable' class= 'dataTable cell-border' width="100%">
                                    <thead>
                                        <tr>
                                            <th width="7%"><label>Date</label> </th>
                                            <th width="7%"> Location</th>
                                            <th width="7%">Description</th>
                                            <!-- <?php foreach($field as $rec){ ?>
                                            <th width="7%"><?=$rec->field?></th>
                                            <?php } ?> -->
                                            <th width="7%">Travelling - Bus / Train</th>
                                            <th width="7%">Mobile Exp/ Courier/ Printing Stationary</th>
                                            <th width="7%">Fooding</th>
                                            <th width="7%">Lodging & Boarding</th>
                                            <th width="7%">Room Rent& Office Rent</th>
                                            <th width="7%">Transport / Freight Charges</th>
                                            <th width="7%">Assets(Tablet / New Mobile / Office Equipments)</th>
                                            <th width="7%">Labour Loading & Unloading Charges</th>
                                            <th width="7%">Dunnage /Tarpaulin /chemical</th>
                                            <th width="7%">Warehouse Sweeper / Samplier / Cleaning Charges</th>
                                            <th width="7%">Lock & key /Repair/ Other Charges</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        foreach($expensesInfo as $k=>$res){
                                            if(isset($k) && $k!='50'){
                                            ?>
                                        <tr>
                                            <td width="7%"><?php echo date('d-m-Y',strtotime($res->entry_date));?></td>
                                            <td width="7%"><?=$res->location?></td>
                                            <td width="7%"><?=$res->description?></td>
                                            <td width="7%"><?=$res->travel?></td>
                                            <td width="7%"><?=$res->mobile_exp?></td>
                                            <td width="7%"><?=$res->fooding?></td>
                                            <td width="7%"><?=$res->lodging?></td>
                                            <td width="7%"><?=$res->local_conv?></td>
                                            <td width="7%"><?=$res->printing?></td>
                                            <td width="7%"><?=$res->courier?></td>
                                            <td width="7%"><?=$res->labour_charge?></td>
                                            <td width="7%"><?=$res->dunnage?></td>
                                            <td width="7%"><?=$res->wh_cleaning?></td>
                                            <td width="7%"><?=$res->lock_key?></td>
                                        </tr>
                                    <?php } } ?>
                                   </tbody>
                                </table>
                        
                    </div>
                    <hr>
                    <div>Local Conv.</div>
                    <div class="row table-responsive">
                        <table id='empTable' class= 'dataTable cell-border' width="100%">
                                    <thead>
                                        <tr>
                                            <th width="16%"><label>Date</label> </th>
                                            <th width="15%"> Location</th>
                                            <th width="15%">From</th>
                                            <th width="15%">To</th>
                                            <th width="15%">Km</th>
                                            <th width="16%">Mode of Transport</th>
                                            <th width="10%">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(isset($expensesInfo['50'])){
                                            foreach($expensesInfo['50'] as $rec){?>
                                        <tr>
                                            <td width="7%"><?php echo date('d-m-Y',strtotime($rec->local_entry));?></td>
                                            <td width="7%"><?=$rec->local_location?></td>
                                            <td width="7%"><?=$rec->dis_from?></td>
                                            <td width="7%"><?=$rec->dis_to?></td>
                                            <td width="7%"><?=$rec->km?></td>
                                            <td width="7%"><?=$rec->transport?></td>
                                            <td width="7%"><?=$rec->local_amount?></td>
                                        </tr>
                                    <?php }  } ?>
                                   </tbody>
                                </table>
                        
                    </div>
                    <hr>
                    <div><h4>Advance Info</h4></div>
                    <div class="row table-responsive">
                        <table id='empTable' class= 'dataTable cell-border' width="100%" style="padding-right: 15px;">
                                    <thead>
                                        <tr>
                                            <th width="16%" style="padding-left: 110px;"><label>Date</label> </th>
                                            <th width="15%" style="padding-left: 75px;"> Maker Amount</th>
                                            <th width="15%" style="padding-left: 70px;">Checker Amount</th>
                                            <th width="15%" style="padding-left: 100px;">Checker</th>
                                            <th width="15%" style="padding-left: 70px;">Maker Remark</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                            if(isset($dataadvance)){
                                            foreach($dataadvance as $rec){?>
                                        <tr>
                                            <td width="7%" style="text-align: center;"><?php echo date('d-m-Y',strtotime($rec->updatedDtm));?></td>
                                            <td width="7%" style="text-align: center;"><?=$rec->makerAmount?></td>
                                            <td width="7%" style="text-align: center;"><?=$rec->checkerAmount?></td>
                                            <td width="7%" style="text-align: center;"><?=$rec->checker?></td> 
                                            <td width="7%" style="text-align: center;"><?=$rec->makerRemark?></td>
                                        </tr>
                                    <?php }  } ?>
                                   </tbody>
                                </table>
                        
                    </div>
                    <div>
                        <?php if($fileName){?>
                            Attachment Copy Download : <a href="<?php echo base_url() ?>expenses/<?php echo $fileName;?>" target="_blank" download="<?php echo $fileName;?>" class="btn btn-download">Download</a>  
                        <?php } ?>
                    </div>
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

<script type="text/javascript">
    
$(document).ready(function(){
    
    var checkAdvanceForm = $("#checkAdvance");
    
    var validator = checkAdvanceForm.validate({
        
        rules:{
            checkerAmount :{ required : true },
            checkerRemark :{ required : true },
            }, 
        messages:{
            checkerAmount :{ required : "This field is required" }
            checkerRemark :{ required : "This field is required" }
        }
    });
});


</script>
<script>
        function pushApprove(id,status){
            var confirmMsg = confirm('Are you sure, you want to '+status+' this advance?');
            if(confirmMsg==true){
                $("#pushButton").hide();
                $.ajax({
                    url: '<?=base_url()?>ajaxapprove',
                    data:{"id":id,'status':status},
                    type: 'POST',
                    dataType: 'json',
                    success: function(response){
                        location.href="<?=base_url()?>adListing";
                    },
                    error: function(){ alert('Failed');}
                });
            }
        }
    </script>
