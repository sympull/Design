<?php

// dateorder
// invoice
// payer_email
// first_name
// last_name
// payment_type
// payment_status
// payment_currency
// payment_amount
// custom

?>
<?php if ($bsc_order_header->Visible) { ?>
<table cellspacing="0" id="t_bsc_order_header" class="ewGrid"><tr><td>
<table id="tbl_bsc_order_headermaster" class="table table-bordered table-striped">
	<tbody>
<?php if ($bsc_order_header->dateorder->Visible) { // dateorder ?>
		<tr id="r_dateorder">
			<td><?php echo $bsc_order_header->dateorder->FldCaption() ?></td>
			<td<?php echo $bsc_order_header->dateorder->CellAttributes() ?>><span id="el_bsc_order_header_dateorder" class="control-group">
<span<?php echo $bsc_order_header->dateorder->ViewAttributes() ?>>
<?php echo $bsc_order_header->dateorder->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_order_header->invoice->Visible) { // invoice ?>
		<tr id="r_invoice">
			<td><?php echo $bsc_order_header->invoice->FldCaption() ?></td>
			<td<?php echo $bsc_order_header->invoice->CellAttributes() ?>><span id="el_bsc_order_header_invoice" class="control-group">
<span<?php echo $bsc_order_header->invoice->ViewAttributes() ?>>
<?php echo $bsc_order_header->invoice->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_order_header->payer_email->Visible) { // payer_email ?>
		<tr id="r_payer_email">
			<td><?php echo $bsc_order_header->payer_email->FldCaption() ?></td>
			<td<?php echo $bsc_order_header->payer_email->CellAttributes() ?>><span id="el_bsc_order_header_payer_email" class="control-group">
<span<?php echo $bsc_order_header->payer_email->ViewAttributes() ?>>
<?php echo $bsc_order_header->payer_email->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_order_header->first_name->Visible) { // first_name ?>
		<tr id="r_first_name">
			<td><?php echo $bsc_order_header->first_name->FldCaption() ?></td>
			<td<?php echo $bsc_order_header->first_name->CellAttributes() ?>><span id="el_bsc_order_header_first_name" class="control-group">
<span<?php echo $bsc_order_header->first_name->ViewAttributes() ?>>
<?php echo $bsc_order_header->first_name->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_order_header->last_name->Visible) { // last_name ?>
		<tr id="r_last_name">
			<td><?php echo $bsc_order_header->last_name->FldCaption() ?></td>
			<td<?php echo $bsc_order_header->last_name->CellAttributes() ?>><span id="el_bsc_order_header_last_name" class="control-group">
<span<?php echo $bsc_order_header->last_name->ViewAttributes() ?>>
<?php echo $bsc_order_header->last_name->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_order_header->payment_type->Visible) { // payment_type ?>
		<tr id="r_payment_type">
			<td><?php echo $bsc_order_header->payment_type->FldCaption() ?></td>
			<td<?php echo $bsc_order_header->payment_type->CellAttributes() ?>><span id="el_bsc_order_header_payment_type" class="control-group">
<span<?php echo $bsc_order_header->payment_type->ViewAttributes() ?>>
<?php echo $bsc_order_header->payment_type->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_order_header->payment_status->Visible) { // payment_status ?>
		<tr id="r_payment_status">
			<td><?php echo $bsc_order_header->payment_status->FldCaption() ?></td>
			<td<?php echo $bsc_order_header->payment_status->CellAttributes() ?>><span id="el_bsc_order_header_payment_status" class="control-group">
<span<?php echo $bsc_order_header->payment_status->ViewAttributes() ?>>
<?php echo $bsc_order_header->payment_status->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_order_header->payment_currency->Visible) { // payment_currency ?>
		<tr id="r_payment_currency">
			<td><?php echo $bsc_order_header->payment_currency->FldCaption() ?></td>
			<td<?php echo $bsc_order_header->payment_currency->CellAttributes() ?>><span id="el_bsc_order_header_payment_currency" class="control-group">
<span<?php echo $bsc_order_header->payment_currency->ViewAttributes() ?>>
<?php echo $bsc_order_header->payment_currency->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_order_header->payment_amount->Visible) { // payment_amount ?>
		<tr id="r_payment_amount">
			<td><?php echo $bsc_order_header->payment_amount->FldCaption() ?></td>
			<td<?php echo $bsc_order_header->payment_amount->CellAttributes() ?>><span id="el_bsc_order_header_payment_amount" class="control-group">
<span<?php echo $bsc_order_header->payment_amount->ViewAttributes() ?>>
<?php echo $bsc_order_header->payment_amount->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
<?php if ($bsc_order_header->custom->Visible) { // custom ?>
		<tr id="r_custom">
			<td><?php echo $bsc_order_header->custom->FldCaption() ?></td>
			<td<?php echo $bsc_order_header->custom->CellAttributes() ?>><span id="el_bsc_order_header_custom" class="control-group">
<span<?php echo $bsc_order_header->custom->ViewAttributes() ?>>
<?php echo $bsc_order_header->custom->ListViewValue() ?></span>
</span></td>
		</tr>
<?php } ?>
	</tbody>
</table>
</td></tr></table>
<?php } ?>
