<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#dataTable').DataTable({
			select: true,
			processing: true,
			serverSide: true,
			ajax: {
				'url': '<?= base_url('menu/headerMenu') ?>',
				'type': 'POST'
			},
			columns: [{
				data: 'header_menu'
			}]
		});

		$('#dataTable tbody').on('click', 'tr', function() {
			var data_row = table.row($(this).closest('tr')).data();
			console.log(data_row['id_karyawan']);
		});

	});
</script>