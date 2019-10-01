<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Employee Salary
        <small>Salary Structure</small>
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
                        <form action="<?php echo base_url() ?>employeeListing" method="POST" id="searchList">
                            <div class="input-group">
                              <input type="text" name="searchText" value="<?php echo $searchText; ?>" class="form-control input-sm pull-right" style="width: 150px;" placeholder="Search"/>
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
                        <th>Emp Code</th>
                        <th>Name</th>
                        <th>Bank</th>
                        <th>Account</th>
                        <th>IFSC</th>
                        <th>Structure Basic</th>
                        <th>Structure Transport Allow</th>
                        <th>Structure Spcl Allow</th>
                        <th>Structure LTA</th>
                        <th>Structure HRA</th>
                        <th>Structure Bonus</th>
                        <th>Structure Gross Salary</th>
                        <th>Present Days</th>
                        <th>Basic</th>
                        <th>Transport Allow</th>
                        <th>Spcl Allow</th>
                        <th>LTA</th>
                        <th>HRA</th>
                        <th>Bonus</th>
                        <th>Gross Salary</th>
                        <th>EPF</th>
                        <th>ESI</th>
                        <th>TDS</th>
                        <th>Advance</th>
                        <th>PT</th>
                        <th>Total Deduction</th>
                        <th>Payable</th>
                    </thead>
                    <?php
                    if(!empty($userRecords))
                    {
                        foreach($userRecords as $record)
                        {
                            $pDays = $record->presentDays;
                            $days = cal_days_in_month(CAL_GREGORIAN, $record->month, $record->year);
                            $gross_structure = $record->basic+$record->transAllow+$record->spclAllow+$record->lta+$record->hra+$record->bonus;
                            $basic      = round($record->basic/$days*$pDays);
                            $transAllow = round($record->transAllow/$days*$pDays);
                            $spclAllow  = round($record->spclAllow/$days*$pDays);
                            $lta        = round($record->lta/$days*$pDays);
                            $hra        = round($record->hra/$days*$pDays);
                            $bonus      = round($record->bonus/$days*$pDays);
                            $gross = $basic+$transAllow+$spclAllow+$lta+$hra+$bonus;
                            $pf = round(($basic+$transAllow)*12/100);
                            if($gross_structure>21000){
                                $esic =0;    
                            }else{
                                $esic = ceil($gross*0.75/100);
                            }

                            $tds = 0; $advance=0;
                            if($record->tds>0){
                                $tds = $record->tds;
                            }
                            if($record->advance_deduction>0){
                                $advance = $record->advance_deduction;
                            }
                            $PT = 0;
                            if($record->PT>0){
                                $PT = $record->PT;
                            }
                            $deduction =$pf+$esic+$tds+$advance+$PT;  
                            $netPayment = $gross-$deduction;
                    ?>
                    <tr>
                        <td><?php echo $record->empCode ?></td>
                        <td><?php echo $record->name ?></td>
                        <td><?php echo $record->bankName ?></td>
                        <td><?php echo $record->acNum ?></td>
                        <td><?php echo $record->ifscCode ?></td>
                        <td><?php echo $record->basic ?></td>
                        <td><?php echo $record->transAllow ?></td>
                        <td><?php echo $record->spclAllow ?></td>
                        <td><?php echo $record->lta ?></td>
                        <td><?php echo $record->hra ?></td>
                        <td><?php echo $record->bonus ?></td>
                        <td><?php echo $gross_structure?></td>
                        <td><?php echo $record->presentDays ?></td>
                        <td><?php echo $basic ?></td>
                        <td><?php echo $transAllow ?></td>
                        <td><?php echo $spclAllow ?></td>
                        <td><?php echo $lta ?></td>
                        <td><?php echo $hra ?></td>
                        <td><?php echo $bonus ?></td>
                        <td><?php echo $gross ?></td>
                        <td><?php echo $pf ?></td>
                        <td><?php echo $esic ?></td>
                        <td><?php echo $tds ?></td>
                        <td><?php echo $advance ?></td>
                        <td><?php echo $PT ?></td>
                        <td><?php echo $deduction ?></td>
                        <td><?php echo $netPayment ?></td>                        
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
    