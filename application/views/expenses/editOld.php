<?php
$id = $expensesInfo[0]->id;
$name = $expensesInfo[0]->name;
$empCode = $expensesInfo[0]->empCode;
$bankName = $expensesInfo[0]->bankName;
$acNum = $expensesInfo[0]->acNum;
$userId = $expensesInfo[0]->userId;
$makerAmount = $expensesInfo[0]->makerAmount;
$makerRemark = $expensesInfo[0]->makerRemark;
$checkerAmount = $expensesInfo[0]->checkerAmount;
$checkerRemark = $expensesInfo[0]->checkerRemark;
$approvedBy = $expensesInfo[0]->approvedBy;
$approved = $expensesInfo[0]->approved;
$checker = $expensesInfo[0]->checker;
$fileName = $expensesInfo[0]->fileName;
$role = $this->session->userdata('role');
$empRole = $this->session->userdata('empRole');
$userarray =  explode(',', $empRole);
$currentUser = $this->session->userdata('userId');

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
<link rel="stylesheet" href="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker3.min.css" />

<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Expenses Management
        <small>Add / Edit Expenses</small>
      </h1>
    </section>
    
    <section class="content">
    
        <div class="row">
            <!-- left column -->
            <div class="col-md-12">
              <!-- general form elements -->
                
                
                
                <div class="box box-primary">
                    <div class="box-header">
                        <h3 class="box-title">Enter Expenses Details</h3>
                    </div><!-- /.box-header -->
                    <!-- form start -->
                   <?php foreach($field as $rec){ ?>
                                            <input type="hidden"  name="arrayval[]" value="<?php echo $rec->field_name; ?>">
                                            <?php } ?>
                                             
                    <form role="form" action="<?php echo base_url() ?>editExpenses" method="post" id="editAdvance" role="form" enctype="multipart/form-data">
                        <input type="hidden" name="userId" value="<?=$userId?>">
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
                                        <label for="fname">Request Expenses Amount</label>
                                        <div><?=$makerAmount?></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Approved Expenses Amount</label>
                                        <div><?=$checkerAmount?></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Grand Total(Table 1,Table 2)</label>
                                        <div id="totaldiv"></div>
                                    </div>
                                    
                                </div>
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname"> Approved Advance Total</label>
                                        <div><?=$advTotal?></div>
                                    </div>
                                    
                                </div>
                            </div>
                            <div class="row table-responsive">
                                <table id='empTable' class='display dataTable' width="100%">
                                    <thead>
                                        <tr>
                                            <th><label>Date</label> </th>
                                            <th> Location</th>
                                            <th>Description</th>
                                            <!-- <?php foreach($field as $rec){ ?>
                                            <th scope="col"><?=$rec->field?></th>
                                            <?php } ?> -->
                                            <th>Travelling - Bus / Train</th>
                                            <th>Mobile Exp/ Courier/ Printing Stationary</th>
                                            <th>Fooding</th>
                                            <th>Lodging & Boarding</th>
                                            <th>Room Rent& Office Rent</th>
                                            <th>Transport / Freight Charges</th>
                                            <th>Assets(Tablet / New Mobile / Office Equipments)</th>
                                            <th>Labour Loading & Unloading Charges</th>
                                            <th>Dunnage /Tarpaulin /chemical</th>
                                            <th>Warehouse Sweeper / Samplier / Cleaning Charges</th>
                                            <th>Lock & key /Repair/ Other Charges</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($dates as $date){

                                            foreach($expensesInfo as $k=>$res){
                                                if(isset($k) && $k!='50' && $res->entry_date==$date){
                                                    $location    = $res->location;     
                                                    $description = $res->description;
                                                    $out['travel']= $res->travel;
                                                    $out['mobile_exp']= $res->mobile_exp;
                                                    $out['fooding']=$res->fooding;
                                                    $out['lodging']=$res->lodging;
                                                    $out['local_conv']=$res->local_conv;
                                                    $out['printing']=$res->printing;
                                                    $out['courier']=$res->courier;
                                                    $out['labour_charge']=$res->labour_charge;
                                                    $out['dunnage']=$res->dunnage;
                                                    $out['wh_cleaning']=$res->wh_cleaning;
                                                    $out['lock_key']=$res->lock_key;
                                                    $out['detail_id']=$res->detail_id;
                                                    break;     
                                                }else{
                                                    $location    ="";
                                                    $description ="";
                                                    $out['travel']="";
                                                    $out['mobile_exp']="";
                                                    $out['fooding']="";
                                                    $out['lodging']="";
                                                    $out['local_conv']="";
                                                    $out['printing']="";
                                                    $out['courier']="";
                                                    $out['labour_charge']="";
                                                    $out['dunnage']="";
                                                    $out['wh_cleaning']="";
                                                    $out['lock_key']="";
                                                    $out['detail_id']="";

                                                }
                                            }    
                                        ?>
                                        <tr>
                                            <td>
                                                <input type="text" name="entry_date[]"  value="<?php echo date('d-m-Y',strtotime($date));?>" class="form-control" readonly></td>
                                            <td>
                                                <input type="text" name="location[]" class="form-control" value="<?=$location?>"></td>
                                            <td>
                                                <input type="text" name="description[]" class=" form-control" value="<?=$description?>">
                                            </td>
                                            
                                            <?php foreach($field as $rec){ ?>
                                            <td><input type="text" name="<?=$rec->field_name?>[]" class=" form-control <?php echo $rec->field_name?>" value="<?php echo $out[$rec->field_name]?>" onChange="calculateTotal()"></td> 
                                            
                                            <?php } ?>
                                             
                                            <td><input type="hidden" name="detail_id[]" value="<?=$out['detail_id']?>"></td>
                                        </tr>
                                    <?php } ?>
                                        
                                   </tbody>
                                </table>
                                <table class='display dataTable' width="100%" style="padding-right: 40px;">
                                    <thead>
                                        <tr>
                                            <td style="padding: 8px 10px;">
                                                <input type="text" name="" class="form-control" value="Total" readonly></td>
                                            <td style="padding: 8px 10px;">
                                                <input type="text" name="" class="form-control" readonly=""></td>
                                            <td style="padding: 8px 10px;">
                                                <input type="text" name="" class="required form-control" readonly="">
                                            </td>
                                            
                                            <?php foreach($field as $rec){ ?>
                                            <td style="padding: 8px 10px;"><input type="text" id="<?=$rec->field_name?>" class="required form-control " value="<?php echo $out[$rec->field_name]?>"></td> 
                                            <?php } ?>
                                           
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <hr>
                            <div>LOCAL </div>
                            <div class="row table-responsive">
                                <table id='empTable' class='display dataTable' width="100%">
                                    <thead style="width: 100%">
                                        <tr>
                                            <th style="text-align: center;"><label>Date</label> </th>
                                            <th style="text-align: center;"> Location</th>
                                            <th style="text-align: center;">From</th>
                                            <th style="text-align: center;">To</th>
                                            <th style="text-align: center;">Km</th>
                                            <th style="text-align: center;">Mode of Transport</th>
                                            <th style="text-align: center;">Amount</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($dates as $date){
                                            if(isset($expensesInfo['50'])){
                                             foreach($expensesInfo['50'] as $rec){
                                                if($rec->local_entry==$date){
                                                    $local_location = $rec->local_location;     
                                                    $from       = $rec->dis_from;
                                                    $to         = $rec->dis_to;
                                                    $km         = $rec->km;
                                                    $transport  = $rec->transport;
                                                    $amount     = $rec->local_amount;
                                                    $local_id   = $rec->local_id;
                                                    break;     
                                                }else{
                                                    $local_location    ="";
                                                    $from ="";$to ="";$km=""; $transport="";$amount="";$local_id="";
                                                    
                                                }
                                            }    }else{
                                                    $local_location    ="";
                                                    $from ="";$to ="";$km=""; $transport="";$amount="";$local_id="";
                                                    
                                                }
                                        ?>
                                        <tr style="width: 100%;">
                                            <td>
                                                <input type="text" name="l_entry_date[]"  value="<?php echo date('d-m-Y',strtotime($date));?>" class="form-control" readonly></td>
                                            <td>
                                                <input type="text" name="l_location[]" class="form-control" value="<?=$local_location?>"></td>
                                            <td>
                                                <input type="text" name="from[]" class="form-control" value="<?=$from?>">
                                            </td>
                                            <td>
                                                <input type="text" name="to[]" class="form-control" value="<?=$to?>">
                                            </td>
                                            <td>
                                                <input type="text" name="km[]" class="form-control" value="<?=$km?>">
                                            </td>
                                            <td>
                                                <input type="text" name="transport[]" class="form-control" value="<?=$transport?>">
                                            </td>
                                            <td>
                                                <input type="text" id="amount" name="amount[]" class="form-control amount" value="<?=$amount?>" onChange="calculateTotal()">
                                            </td>
                                            <input type="hidden" name="local_id[]" value="<?=$local_id?>">
                                        </tr>
                                    <?php } ?>
                                    
                                   </tbody>
                                </table>
                                <table class='display dataTable' width="100%">
                                    <thead>
                                        <tr>
                                            <td>
                                                <input type="text" class="form-control" value="Total" readonly></td>
                                            <td>
                                                <input type="text" class="form-control" readonly></td>
                                            <td>
                                                <input type="text"  class="required form-control" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="required form-control" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="required form-control" readonly>
                                            </td>
                                            <td>
                                                <input type="text" class="required form-control" readonly>
                                            </td>
                                            <td>
                                                <input type="text" id="total_amount" class="required form-control">
                                            </td>
                                            
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <hr>
                    <div><h4>Advance Info</h4></div>
                    <div class="row table-responsive">
                        <table id='empTable' class= 'dataTable cell-border' width="100%" style="padding-right: 15px;">
                                    <thead>
                                        <tr>
                                            <th width="16%" style="padding-left: 100px;"><label>Date</label> </th>
                                            <th width="15%" style="padding-left: 80px;"> Maker Amount</th>
                                            <th width="15%" style="padding-left: 70px;">Checker Amount</th>
                                            <th width="15%" style="padding-left: 100px;">Checker</th>
                                            <th width="15%" style="padding-left: 60px;">Maker Remark</th>
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
                        
                    </div><hr>
                            <?php if(in_array(2, $userarray)){ ?>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Attach Supporting Bill's Copy</label>
                                        <input type="file" class="form-control" name="userfile">
                                        <?php if($fileName){?>
                                        <a href="<?php echo base_url() ?>expenses/<?php echo $fileName;?>" target="_blank" download="<?php echo $fileName;?>">Download</a>  
                                        <?php } ?>
                                    <input type="hidden" name="userfile" value="<?php echo $fileName;?>">
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                            <?php if((in_array(3, $userarray) || in_array(4, $userarray)) && ($currentUser != $userId)){ ?>
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Remark</label>
                                        <textarea name="accountRemark" id="accountRemark" class="form-control required"></textarea>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        <input type="hidden" value="<?php echo $id; ?>" name="id" id="id" />   
                             
                        <div class="box-footer">
                            <?php if((in_array(3, $userarray) || in_array(4, $userarray)) && ($currentUser != $userId)){ ?>
                          
                            <input type="submit" class="btn btn-primary" name="EditSave" value="Save" onclick="beforeApprove()" >
                            
                            <input type="button" class="btn btn-primary" value="Approved" onclick="beforeApprove()" />
                            
                            <input type="button" class="btn btn-default" value="Reject" onclick="pushApprove(<?=$id;?>,'reject')" />
                        <?php } else { ?>
                            <input type="submit" class="btn btn-primary" value="Save" />
                            <input type="reset" class="btn btn-default" value="Reset" />
                        <?php } ?>
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

<script type="text/javascript">
    
$(document).ready(function(){
    
    var editAdvanceForm = $("#editAdvance");
    
    var validator = editAdvanceForm.validate({
        
        rules:{
            makerAmount :{ required : true },
            makerRemark :{ required : true },
            }, 
        messages:{
            makerAmount :{ required : "This field is required" },
            makerRemark :{ required : "This field is required" }
        }
    });

    calculateTotal();

    
});


</script>
<script>
 
    function calculateTotal()
    {
        var arrayvalue = $("input[name='arrayval[]']").map(function(){return $(this).val();}).get();
        var total =0;
        var loaclsum =0;
    arrayvalue.forEach(function(e){

    var sum = 0;
        $('.'+e+'').each(function(){
            if(this.value != ''){
                        sum += parseFloat(this.value);
                    }  
        });
        $("#"+e+"").val(sum);
        total += parseFloat(sum); 
    });
    $('.amount').each(function(){
            if(this.value != ''){
                        loaclsum += parseFloat(this.value);
                    }  
        });
        $("#total_amount").val(loaclsum);
        total = total+loaclsum;
    $("#totaldiv").html(total);
    }

        function pushApprove(id,status){
            if(status==0){ $res = 'checker';}else{ $res = 'Account';}
            var confirmMsg = confirm('Are you sure, you want to reject this expenses ?');
            if(confirmMsg==true){
                $("#pushButton").hide();
                var accRemark = $("#accountRemark").val();
                $.ajax({
                    url: '<?=base_url()?>expenses/ajaxapprove',
                    data:{"id":id,'status':status,'accRemark':accRemark},
                    type: 'POST',
                    dataType: 'json',
                    success: function(response){
                        location.href="<?=base_url()?>exListing";
                    },
                    error: function(){ alert('Failed');}
                });
            }
        }

        function beforeApprove()
        {
           var conf = confirm('Are you sure, you want to approve this expenses ?');
            if(conf == true){
                        jQuery("#editAdvance").submit();
                    }
        }
    </script>
