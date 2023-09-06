<?php
    require_once 'includes/header.php';
?>
<script type="text/javascript" src="<?=base_url()?>assets/js/datatables/jquery-1.12.3.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/js/datatables/jquery.datatables.min.js"></script>
<link href="<?=base_url()?>assets/js/datatables/jquery.datatables.min.css" rel="stylesheet">
<script type="text/javascript">
	$(document).ready(function() {
	    $('#example').DataTable();
	} );
</script>
<style>

.dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter {
    float: none;
    text-align: right;
}
.ciniki { 
	border: solid 1px #5cb85c;
    padding: 4px 4px 4px 4px;
	
} 
@media print {
	.sidebar-collapse {
    display: none;
  }
  .example_filter{
	display: none;
  }
  #hide{
	  display: none;
	  }
}

</style>

<div class="col-sm-9 col-sm-offset-3 col-lg-10 col-lg-offset-2 main">
	<div class="row">
		<div class="col-lg-12">
			<h1 class="page-header"><?php echo $lang_opened_bill; ?></h1>
		</div>
	</div><!--/.row-->
	
	<div class="row">
		<div class="col-md-12">
			<div class="panel panel-default">
				<div class="panel-body">
					
					<?php
                        if (!empty($alert_msg)) {
                            $flash_status = $alert_msg[0];
                            $flash_header = $alert_msg[1];
                            $flash_desc = $alert_msg[2];

                            if ($flash_status == 'failure') {
                                ?>
							<div class="row" id="notificationWrp">
								<div class="col-md-12">
									<div class="alert bg-warning" role="alert">
										<i class="icono-exclamationCircle" style="color: #FFF;"></i> 
										<?php echo $flash_desc; ?> <i class="icono-cross" id="closeAlert" style="cursor: pointer; color: #FFF; float: right;"></i>
									</div>
								</div>
							</div>
					<?php	
                            }
                            if ($flash_status == 'success') {
                                ?>
							<div class="row" id="notificationWrp">
								<div class="col-md-12">
									<div class="alert bg-success" role="alert">
										<i class="icono-check" style="color: #FFF;"></i> 
										<?php echo $flash_desc; ?> <i class="icono-cross" id="closeAlert" style="cursor: pointer; color: #FFF; float: right;"></i>
									</div>
								</div>
							</div>
					<?php

                            }
                        }
                    ?>
					
					<div class="row" style="margin-top: 10px;">
						<div class="col-md-12">
							
							<div class="">
							<table id="example"cellspacing="0" width="100%" class="table table-bordered">
									<thead>
										<tr>
									    	<th width="14%"><?php echo $lang_date; ?></th>
									    	<th class="hidden-xs" width="11%"><?php echo $lang_customer; ?></th>
									    	<th width="14%"><?php echo $lang_outlets; ?></th>
										    <th class="hidden-xs"width="14%"><?php echo $lang_ref_number; ?></th>
										    <th class="hidden-xs"width="5%"><?php echo $lang_items; ?></th>
										    <th class="hidden-xs"width="7%"><?php echo $lang_sub_total; ?></th>
										    <th class="hidden-xs"width="7%"><?php echo $lang_tax; ?></th>
										    <th width="10%"><?php echo $lang_grand_total; ?></th>
										    <th width="10%"><?php echo $lang_action; ?></th>
										</tr>
									</thead>
									<tbody>
<?php
    $billResult = $this->db->query("SELECT * FROM suspend WHERE status = '0' ORDER BY id DESC ");
    $billRows = $billResult->num_rows();
	$alltotal = 0;	

    if (count($billRows) > 0) {
        $billData = $billResult->result();

        foreach ($billData as $data) {
            $sus_id = $data->id;
            $cust_id = $data->customer_id;
            $ref_number = $data->ref_number;
            $outlet_id = $data->outlet_id;
            $subTotal = $data->subtotal;
            $tax = $data->tax;
            $grandTotal = $data->grandtotal;
			$alltotal += $grandTotal;
            $created_datetime = date("$setting_dateformat H:i A", strtotime($data->created_datetime));
            $total_items = $data->total_items;

            $outlet_name = '';
            $outletNameData = $this->Constant_model->getDataOneColumn('outlets', 'id', $outlet_id);
            $outlet_name = $outletNameData[0]->name;

            $customer_name = '';
            $customerData = $this->Constant_model->getDataOneColumn('customers', 'id', $cust_id);
            $customer_name = $customerData[0]->fullname; ?>
			<tr>
				<td><?php echo $created_datetime; ?></td>
				<td class="hidden-xs"><?php echo $customer_name; ?></td>
				<td><?php echo $outlet_name; ?></td>
				<td class="hidden-xs"><?php echo $ref_number; ?></td>
				<td class="hidden-xs"><?php echo $total_items; ?></td>
				<td class="hidden-xs"><?php echo $subTotal; ?></td>
				<td class="hidden-xs"><?php echo $tax; ?></td>
				<td><?php echo $grandTotal; ?></td>
				<td>
<a href="<?=base_url()?>pos?suspend_id=<?php echo $sus_id; ?>" style="text-decoration: none; cursor: pointer;" title="Bring to POS">
<i class="icono-list" style="color: #005b8a;"></i>
</a>
					
<a href="<?=base_url()?>sales/deleteSuspended?id=<?php echo $sus_id; ?>" style="text-decoration: none; margin-left: 5px;" title="Delete" onclick="return confirm('<?php echo $lang_confirm_to_delete_bill; ?>')">
<i class="icono-crossCircle" style="color: #F00"></i>
</a>
				</td>
			</tr>
<?php

        }
        unset($billData);
    }
    unset($billResult);
    unset($billRows);
?>
<div class="">
<h3 class="ciniki"><?php echo "Total Bill Amount"; ?> :<br> N <?php echo number_format($alltotal, 2); ?> </h3>
</div>
									</tbody>
								</table>
							</div>
							
						</div>
					</div>
					
					
				</div><!-- Panel Body // END -->
			</div><!-- Panel Default // END -->
		</div><!-- Col md 12 // END -->
	</div><!-- Row // END -->
	
	<br /><br /><br />
	
</div><!-- Right Colmn // END -->
	
	
	
<?php
    require_once 'includes/footer.php';
?>