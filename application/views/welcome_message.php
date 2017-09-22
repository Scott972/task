<div class="row-fluid">
	<div class="span12">
			<h3>Listing All Users</h3>
			<div class="widget-content nopadding">
				<div class="span12">
					<? $this->table->set_template(
						array(
							'table_open' => '<table id="datatable-item-sales" class="table table-bordered">'
						)
					); ?>
					<? $this->table->set_heading('ID','Username','First Name', 'Last Name', 'Email'); ?>
					<?= $this->table->generate(); ?>
					<?//= $this->table->clear(); ?>
				</div>
			</div>
	</div>
</div>