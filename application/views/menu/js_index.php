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

		function reload_table() {
			table.ajax.reload(null, false);
		}

		$('#pesan-sukses').hide();
		$('#btn-simpan').click(function() {
			$.ajax({
				url: '<?= base_url('menu/addHeaderMenu'); ?>',
				type: 'POST',
				data: $("#AddModal form").serialize(),
				dataType: 'json',
				beforeSend: function(e) {
					if (e && e.overrideMimeType) {
						e.overrideMimeType('application/jsoncharset=UTF-8')
					}
				},
				success: function(response) {
					if (response.status == 0) {
						$('#pesan-sukses').html(response.pesan).fadeIn().delay(1000).fadeOut()
						$('#AddModal').modal('hide')
						reload_table()
					} else {
						$('[name="' + response.inputerror + '"]').addClass('is-invalid');
						$('[name="' + response.inputerror + '"]').next().html(response.error_string);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText)
				}
			})
		})

	});
</script>