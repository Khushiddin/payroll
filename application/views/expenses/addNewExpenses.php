<?php 
$name = $userInfo->name;
$empCode = $userInfo->empCode;
$bankName = $userInfo->bankName;
$acNum = $userInfo->acNum;
$userId = $userInfo->userId;
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
                    <?php foreach($field as $rec){ ?>
                            <input type="hidden"  name="arrayval[]" value="<?php echo $rec->field_name; ?>">
                                            <?php } ?>
                    <!-- form start -->
                    <?php $this->load->helper("form"); ?>
                    <form method="post" action="<?php echo base_url() ?>addNewExpenses" >
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fname">Date</label>
                                        <select name="month" id="month" onchange="submitFrm();">
                                        <?php foreach($month as $k=>$val){ ?>
                                            <option value="<?=$k?>" <?php if($cmon==$k){ echo 'selected'; }?>><?=$val?></option>
                                        <?php } ?>
                                        </select>
                                        <select name="year" id="year">
                                        <?php foreach($year as $y){ ?>
                                            <option value="<?=$y?>" <?php if($cyear==$y){ echo 'selected'; }?>><?=$y?></option>
                                        <?php } ?>
                                        </select>
                                        <input type="submit" name="submit" value="Click">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <form role="form" id="addExpenses" action="<?php echo base_url() ?>addNewExpensesSave" method="post" role="form" enctype='multipart/form-data'>
                        <input type="hidden" name="expMonth" value="<?= $cmon ?>">
                        <!-- <input type="hidden" name="expYear" value="<?= $cyear ?>"> -->
                        <div class="box-body">
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
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Grand Total(Table 1,Table 2)</label>
                                        <div id="totaldiv"></div>
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
                                        <?php foreach($dates as $date){?>
                                        <tr>
                                            <td>
                                                <input type="text" name="entry_date[]"  value="<?php echo date('d-m-Y',strtotime($date));?>" class="form-control" readonly></td>
                                            <td>
                                                <input type="text" name="location[]" class="form-control"></td>
                                            <td>
                                                <input type="text" name="description[]" class="required form-control"></td>
                                            <?php foreach($field as $rec){ ?>
                                            <td><input type="text" name="<?=$rec->field_name?>[]" class="required form-control <?php echo $rec->field_name?>" onChange="calculateTotal()"></td>
                                            <?php } ?>
                                        </tr>
                                    <?php } ?>
                                    
                                   </tbody>
                                </table>
                                <table class='display dataTable' width="100%">
                                    <thead>
                                        <tr>
                                            <td>
                                                <input type="text" name="" class="form-control" value="Total" readonly></td>
                                            <td>
                                                <input type="text" name="" class="form-control" readonly=""></td>
                                            <td>
                                                <input type="text" name="" class="required form-control" readonly="">
                                            </td>
                                            
                                            <?php foreach($field as $rec){ ?>
                                            <td><input type="text" id="<?=$rec->field_name?>" class="required form-control "></td> 
                                            <?php } ?>
                                           
                                        </tr>
                                    </thead>
                                </table>
                            </div>
                            <hr>
                            <label>Local Expenses</label>
                            <div class="row table-responsive">
                                <table  class='display dataTable' width="100%">
                                    <thead>
                                        <tr>
                                            <th style="width: 16%">Date</label> </th>
                                            <th style="width: 15%"><label> Location</label></th>
                                            <th style="width: 15%">From</th>
                                            <th style="width: 15%">To</th>
                                            <th style="width: 10%">KM</th>
                                            <th style="width: 20%">Mode of Transport</th>
                                            <th style="width: 10%">Amount</th>
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($dates as $date){?>
                                        <tr>
                                            <td>
                                                <input type="text" name="l_entry_date[]"  value="<?php echo date('d-m-Y',strtotime($date));?>" class="form-control" readonly></td>
                                            <td>
                                                <input type="text" name="l_location[]" class="form-control"></td>
                                            <td>
                                                <input type="text" name="from[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="to[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="km[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="transport[]" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" name="amount[]" class="form-control amount" onChange="calculateTotal()">
                                            </td>
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
                            <div class="row">
                                <div class="col-md-6">                                
                                    <div class="form-group">
                                        <label for="fname">Attach Supporting Bill's Copy</label>
                                        <input type="file" class="form-control" name="userfile">
                                    </div>
                                </div>
                            </div>

                            </div>
                        <!-- /.box-body -->
                        <input type="hidden" name="userId" value="<?php echo $userId ?>">
                        <div class="box-footer">
                            <input type="submit" class="btn btn-primary" value="Save" />
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
<script src="<?php echo base_url(); ?>assets/bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js"></script>
<script type="text/javascript">
    
$(document).ready(function(){
    
    var addAdvanceForm = $("#addAdvance");
    
    var validator = addAdvanceForm.validate({
        
        rules:{
            makerAmount :{ required : true, digits : true  },
            makerRemark :{ required : true }
            },
        messages:{
            makerAmount :{ required : "This field is required",digits : "Please enter numbers only" },
            makerRemark :{ required : "This field is required" }
      
        }
    });
});

jQuery('.datepicker').datepicker({
          autoclose: true,
          format : "dd-mm-yyyy"
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
</script>

