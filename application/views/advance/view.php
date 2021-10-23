<?php
$id = $advanceInfo->id;
$name = $advanceInfo->name;
$empCode = $advanceInfo->empCode;
$bankName = $advanceInfo->bankName;
$acNum = $advanceInfo->acNum;
$userId = $advanceInfo->userId;
$makerAmount = $advanceInfo->makerAmount;
$makerRemark = $advanceInfo->makerRemark;
$ifsc = $advanceInfo->ifscCode;
$checkerAmount = $advanceInfo->checkerAmount;
$checkerRemark = $advanceInfo->checkerRemark;
$approvedBy = $advanceInfo->approvedBy;
$approved = $advanceInfo->approved;
$checker = $advanceInfo->checker;
$role = $this->session->userdata('role');
$empRole = $this->session->userdata('empRole');
$Rolearray =  explode(',', $empRole);
$currentUser = $this->session->userdata('userId');
$advanceLimit = $this->session->userdata('advanceLimit');
// print_r($advanceInfo);die(); 
?>

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Advance Management
        <small>View Advance</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-8">
              <!-- general form elements -->
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">View Advance Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                    
                    <form action="<?php echo base_url() ?>checkAdvance" method="post" id="checkAdvance" >
                        <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Name</label>
                                        <div><?php echo $name.' ('.$empCode.')'.' '.$acNum ?></div>
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
                            </div>
                            <div class="row">
                                 <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">IFSC Code</label>
                                        <div><?=$ifsc?></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Advance Req. Amount</label>
                                        <div><?=$makerAmount?></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Remarks</label>
                                        <div><?=$makerRemark?></div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Request Date</label>
                                        <div><?php echo date('d-m-Y',strtotime($advanceInfo->createdDtm));?></div>
                                    </div>
                                    
                                </div>
                            </div>
                            <?php if(in_array(3, $Rolearray) && $approved=='1' && $userId!=$currentUser){ ?>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Advance Approved Amount</label>
                                         <input type="hidden" name="advanceLimit" id="advanceLimit" value="<?=$advanceLimit?>">
                                        <input type="text" class="form-control required" value="<?php echo $checkerAmount ?>" id="checkerAmount" name="checkerAmount" maxlength="128">
                                        <div id ="errors"></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Advance Approved Remarks</label>
                                        <textarea name="checkerRemark" id="checkerRemark" class="form-control required"><?php echo $checkerRemark; ?></textarea>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                            
                            <?php if($approved=='2' || $approved=='4'){ ?>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Advance Approved Amount</label>
                                        <div><?=$checkerAmount?></div>
                                    </div>
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Advance Approved Remarks</label>
                                        <div><?=$checkerRemark?></div>
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
                                        <div><?php echo date('d-m-Y',strtotime($advanceInfo->updatedDtm)); ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                        <?php if(in_array(4, $Rolearray) && $approved=='2') { ?>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Remark</label>
                                        <textarea name="accountRemark" id="accountRemark" class="form-control required"></textarea>
                                    </div>
                                </div>
                            </div>
                        <?php }elseif($approved=='3' && !empty($advanceInfo->accountRemark)){ ?>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Account Remark</label>
                                        <div><?php echo $advanceInfo->accountRemark; ?></div>
                                    </div>
                                </div>
                            </div>    
                        <?php
                            }
                        ?>    
                        <?php if((in_array(3, $Rolearray) && $approved=='1') || (in_array(4, $Rolearray) && $approved=='2')) { ?>
                        <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />        
                        <div class="box-footer">
                            <?php if(!in_array(4, $Rolearray)){ ?>
                            <input type="button" class="btn btn-primary" value="Submit" onclick="checkAmount()" />
                            <?php } else { ?>
                            <input type="button" class="btn btn-primary" value="Approved" onclick="pushApprove(<?=$id;?>,'approved')" />
                            <?php } ?>
                            <input type="button" class="btn btn-default" value="Reject" onclick="pushApprove(<?=$id;?>,'reject')" />
                        </div>
                        <?php } ?>
                    </form>
                </div>
            </div>
            
        </div>    
    </section>
</div>


<script type="text/javascript">
        function pushApprove(id,status){
            var confirmMsg = confirm('Are you sure, you want to '+status+' this advance?');
            if(confirmMsg==true){
                var accountRemark = $('#accountRemark').val();
                //alert(accountRemark);
                $("#pushButton").hide();
                $.ajax({
                    url: '<?=base_url()?>ajaxapprove',
                    data:{"id":id,'status':status,"accountRemark":accountRemark},
                    type: 'POST',
                    dataType: 'json',
                    success: function(response){
                        location.href="<?=base_url()?>adListing";
                    },
                    error: function(){ alert('Failed');}
                });
            }
        } 

        function checkAmount()
        {
            var limit = parseInt($("#advanceLimit").val());
            var approve = parseInt($("#checkerAmount").val());
            console.log(approve);
            console.log(limit);

            if(limit== 'NULL' || approve <= limit)
            {
                document.getElementById('checkAdvance').submit();
                        
            }else{
                 document.getElementById('errors').innerHTML="*You can Approve upto "+limit+"*";
            }
        }
        
    </script>
