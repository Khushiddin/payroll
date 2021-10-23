<link href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css" rel="stylesheet" type="text/css" />
<link href="https://cdn.datatables.net/buttons/1.5.6/css/buttons.dataTables.min.css" rel="stylesheet" type="text/css" />
<style>
    .dt-buttons{
        margin-left: 9px !important;
    }
</style>
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        <i class="fa fa-users"></i> Vendor Report
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
                    <h3 class="box-title">Vendor List</h3>
                    <div class="box-tools">
                        <form action="<?php echo base_url() ?>vendorTotal" method="POST" id="searchList">
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
                        <th>ID</th>
                        <th>Month</th>
                        <th>Year</th>
                        <th>Vendor Name</th>
                        <th>Maker Name</th>
                        <th>Request Amount</th>
                        <th>Approved Amount</th>
                        <th>Created On</th>
                    </thead> 
                    <?php
                    if(!empty($expDetail))
                    {
                        foreach($expDetail as $record)
                        {
                    ?> 
                    <tr>
                        <td><?php echo $record->id ?></td>
                        <td><?php echo $month ?></td>
                        <td><?php echo $year ?></td>
                        <td><?php echo $record->vendorName ?></td>
                        <td><?php echo $record->name ?></td>
                        <td><?php echo $record->makerAmount ?></td>
                        <td><?php echo $record->checkerAmount ?></td>
                        <td><?php echo date('d-m-Y', strtotime($record->createdDtm)) ?></td>
                        
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
    