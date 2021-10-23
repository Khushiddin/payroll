<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Report
        <small></small>
      </h1>
    </section>
    <section class="content">
        <!-- <div class="row">
            <div class="col-xs-12 text-right">
                <div class="form-group">
                    <a class="btn btn-primary" href="<?php //echo base_url(); ?>addNew"><i class="fa fa-plus"></i> Add New</a>
                </div>
            </div>
        </div> -->
        <div class="row">
            <div class="col-xs-12">
              <div class="box">
                <div class="box-header">
                    <h3 class="box-title">Employees List</h3>
                    <div class="box-tools">
                        <?php
                        if($role=='3'){?>
                            <form action="<?php echo base_url() ?>expenseTotal?userId=<?=$this->session->userdata('userId')?>" method="POST" id="searchList">
                        <?php        
                        }else{
                        ?>
                        <form action="<?php echo base_url() ?>expenseTotal" method="POST" id="searchList">
                        <?php
                        }
                        ?>    
                            <div class="input-group">
                                <input type="text" name="searchText" value="<?php echo $searchText; ?>" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
                                <select name="month" class="form-control input-sm pull-right" style="width: 150px;">
                                    <option value="0">Select Month</option>
                  <?php
                      if(!empty($months)){
                          foreach ($months as $k=>$m)
                          {
                          ?>
                            <option value="<?php echo $k ?>" <?php if($k==$month){echo "selected=selected";}?>><?php echo $m ?></option>
                          <?php
                        }
                      }
                  ?>
                                </select>
                                <select name="year" class="form-control input-sm pull-right" style="width: 150px;">
                                    <option value="0">Select Year</option>
                  <?php
                      if(!empty($years)){
                          foreach ($years as $y)
                          {
                          ?>
                            <option value="<?php echo $y ?>" <?php if($y==$year){echo "selected=selected";}?>><?php echo $y ?></option>
                          <?php
                        }
                      }
                  ?>
                                </select>
                              
                              <div class="input-group-btn">
                                <button class="btn btn-sm btn-default searchList"><i class="fa fa-search"></i></button>
                              </div>
                            </div>
                        </form>
                    </div>
                </div><!-- /.box-header -->
                <div class="box-body table-responsive no-padding">
                  <table id="example" class="display nowrap" style="width:100%">
                    <thead>
                        <?php
                        if($role=='3'){?>
                            <th>Emp Code</th>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Name</th>
                            <th>Maker Date</th>
                            <th>Checker Date</th>
                            <th>Finance Date</th>
                            <th>Approved By</th>
                            <th>Approved Date</th> 
                            <th>Expense Total</th>
                            <th>Advance Amount</th>
                            <th>Rimbursement</th>
                            <th>Courier Track Number</th>
                            <th>Courier Received Date</th>  

                        <?php        
                            }else{
                        ?>
                            <th>Emp Code</th>
                            <th>Month</th>
                            <th>Year</th>
                            <th>Name</th>
                            <th>Department</th>
                            <th>Designation</th>
                            <th>Maker Date</th>
                            <th>Checker Date</th>
                            <th>Finance Date</th>
                            <th>Approved By</th>
                            <th>Approved Date</th> 
                            <th>Bank</th>
                            <th>Account</th>
                            <th>IFSC</th>
                            <th>Mobile Exp/ Courier/ Printing Stationary</th>
                            <th>Travel</th>
                            <th>Fooding</th>
                            <th>Lodging</th>
                            <th>Room Rent& Office Rent</th>
                            <th>Transport / Freight Charges</th>
                            <th>Assets(Tablet / New Mobile / Office Equipments)</th>
                            <th>Labour Loading & Unloading Charges</th>
                            <th>Dunnage /Tarpaulin /chemical</th>
                            <th>Warehouse Sweeper / Samplier / Cleaning Charges</th>
                            <th>Lock & key /Repair/ Other Charges</th>
                            <th>Local Amount</th>
                            <th>Expense Total</th>
                            <th>Advance Amount</th>
                            <th>Rimbursement</th>
                            <th>Courier Track Number</th>
                            <th>Courier Received Date</th>
                        <?php
                        }
                        ?>    
                        
                    </thead> 
                    <?php
                    if(!empty($expDetail))
                    {
                        foreach($expDetail as $record)
                        {
                    ?> 
                    <tr>
                        <?php
                        if($role=='3'){?>
                            <td><?php if(!empty($record->empCode)) echo $record->empCode ?></td>
                            <td><?php echo $month ?></td>
                            <td><?php echo $year ?></td>
                            <td><?php if(!empty($record->name)) echo $record->name ?></td>
                            <td><?php 
                                if(!empty($record->createdDtm)){ 
                                    echo date('d-m-Y',strtotime($record->createdDtm));
                                }else{
                                    echo "Pending";
                                }
                                ?>
                                        
                            </td>
                            <td><?php 
                                if(!empty($record->checker_approved_date)){ 
                                    echo date('d-m-Y',strtotime($record->checker_approved_date));
                                }else{
                                    echo "Pending";
                                }
                                ?>
                                        
                            </td>
                            <td><?php 
                                    if(!empty($record->finance_approved_date)){
                                     echo date('d-m-Y',strtotime($record->finance_approved_date));
                                     }else{
                                        echo "Pending";
                                     }
                                   
                            ?></td>
                            <td><?php if(!empty($record->approvedBy)) echo approveName($record->approvedBy)?></td>
                            <td><?php if(!empty($record->updatedDtm)) echo date('d-m-Y',strtotime($record->updatedDtm))?></td>  
                            <?php $expTotal = $record->mobile+$record->travel+$record->fooding+$record->lodging+$record->local_conv+$record->printing+$record->courier+$record->labour_charge+$record->dunnage+$record->wh_cleaning+$record->lock_key+$record->amount; ?>
                            <td><?php echo $expTotal ?></td>
                            <td><?php if(!empty($record->advanceamount)) echo $record->advanceamount ?></td>
                            <td><?php  echo ($expTotal-$record->advanceamount) ?></td>
                            <td><?php  echo ($record->courier_track_no) ?></td>
                            <td>
                                <?php  
                                if(!empty($record->courier_date)){
                                    echo $record->courier_date; 
                                }
                                ?>                                
                            </td>

                        <?php        
                            }else{
                        ?>    
                            <td><?php if(!empty($record->empCode)) echo $record->empCode ?></td>
                            <td><?php echo $month ?></td>
                            <td><?php echo $year ?></td>
                            <td><?php if(!empty($record->name)) echo $record->name ?></td>
                            <td><?php if(!empty($record->departmentName)) echo $record->departmentName?></td>
                            <td><?php if(!empty($record->designationName)) echo $record->designationName?></td>
                            <td><?php 
                                if(!empty($record->createdDtm)){ 
                                    echo date('d-m-Y',strtotime($record->createdDtm));
                                }else{
                                    echo "Pending";
                                }
                                ?>
                                        
                            </td>
                            <td><?php 
                                if(!empty($record->checker_approved_date)){ 
                                    echo date('d-m-Y',strtotime($record->checker_approved_date));
                                }else{
                                    echo "Pending";
                                }
                                ?>
                                        
                            </td>
                            <td><?php 
                                    if(!empty($record->finance_approved_date)){
                                     echo date('d-m-Y',strtotime($record->finance_approved_date));
                                     }else{
                                        echo "Pending";
                                     }
                                   
                            ?></td>
                            <td><?php if(!empty($record->approvedBy)) echo approveName($record->approvedBy)?></td>
                            <td><?php if(!empty($record->updatedDtm)) echo date('d-m-Y',strtotime($record->updatedDtm))?></td>  
                            <td><?php if(!empty($record->bankName)) echo $record->bankName ?></td>
                            <td><?php if(!empty($record->acNum)) echo $record->acNum ?></td>
                            <td><?php if(!empty($record->ifscCode)) echo $record->ifscCode ?></td>
                            <td><?php if(!empty($record->mobile)) echo $record->mobile ?></td>
                            <td><?php if(!empty($record->travel)) echo $record->travel ?></td>
                            <td><?php if(!empty($record->fooding)) echo $record->fooding ?></td>
                            <td><?php if(!empty($record->lodging)) echo $record->lodging ?></td>
                            <td><?php if(!empty($record->local_conv)) echo $record->local_conv ?></td>
                            <td><?php if(!empty($record->printing)) echo $record->printing ?></td>
                            <td><?php if(!empty($record->courier)) echo $record->courier?></td>
                            <td><?php if(!empty($record->labour_charge)) echo $record->labour_charge ?></td>
                            <td><?php if(!empty($record->dunnage)) echo $record->dunnage ?></td>
                            <td><?php if(!empty($record->wh_cleaning)) echo $record->wh_cleaning ?></td>
                            <td><?php if(!empty($record->lock_key)) echo $record->lock_key ?></td>
                            <td><?php if(!empty($record->amount)) echo $record->amount ?></td>
                            <?php $expTotal = $record->mobile+$record->travel+$record->fooding+$record->lodging+$record->local_conv+$record->printing+$record->courier+$record->labour_charge+$record->dunnage+$record->wh_cleaning+$record->lock_key+$record->amount; ?>
                            <td><?php echo $expTotal ?></td>
                            <td><?php if(!empty($record->advanceamount)) echo $record->advanceamount ?></td>
                            <td><?php  echo ($expTotal-$record->advanceamount) ?></td>
                            <td><?php  echo ($record->courier_track_no) ?></td>
                            <td>
                                <?php  
                                if(!empty($record->courier_date)){
                                    echo $record->courier_date; 
                                }
                                ?>
                            </td>
                       <?php
                        }
                        ?> 
                    </tr>
                    <?php
                        }
                    }
                    ?>
                  </table>
                  
                </div><!-- /.box-body -->
                <div class="box-footer clearfix">
                    <?php echo $this->pagination->create_links(); ?>
                </div>
              </div><!-- /.box -->
            </div>
        </div>
    </section>
</div>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/common.js" charset="utf-8"></script>
<script type="text/javascript">

$(document).ready(function() {
    $('#example').DataTable( {
        dom: 'lBfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
            'pdfHtml5'
        ],
        "scrollX": true
    } );
} );



/*    jQuery(document).ready(function(){
        jQuery('ul.pagination li a').click(function (e) {
            e.preventDefault();            
            var link = jQuery(this).get(0).href;            
            var value = link.substring(link.lastIndexOf('/') + 1);
            jQuery("#searchList").attr("action", baseURL + "employeeListing/" + value);
            jQuery("#searchList").submit();
        });
    });*/
</script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/dataTables.buttons.min.js" type="text/javascript"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js" type="text/javascript"></script>
<script src="https://cdn.datatables.net/buttons/1.5.6/js/buttons.html5.min.js" type="text/javascript"></script>
    