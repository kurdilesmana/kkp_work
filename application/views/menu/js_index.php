<script type="text/javascript">
	var table;
	var method;

	$(document).ready(function() {
		table = $('#dataTable').DataTable({
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

		function reloadTable() {
			table.ajax.reload(null, false);
		}

		function resetForm() {
			$('#form')[0].reset();
			$('.form-control').removeClass('is-invalid');
			$('.invalid-feedback').empty();
		}

		$('#pesan-sukses').hide();

		$('#btn-tambah').click(function() {
			resetForm();
			method = 'save';
			$('#formModal').modal('show');
			$('#formModalLabel').text("Tambah Data " + "<?= $title; ?>");
		});

		$('#btn-edit').click(function() {
			try {
				resetForm();
				method = 'update';
				console.log(method);
				var id = table.row('.selected').data()['id'];

				$.ajax({
					url: "<?= base_url('menu/getHeaderMenu') ?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data) {

						$('[name="id"]').val(data.id);
						$('[name="header_menu"]').val(data.header_menu);
						$('#formModal').modal('show');
						$('#formModalLabel').text("Edit Data " + "<?= $title; ?>");

					},
					error: function(jqXHR, textStatus, errorThrown) {
						alert('Error get data from ajax');
					}
				});
			} catch (err) {
				$('#respon-message').addClass('alert-danger')
				$('#respon-message').html('Data Belum Dipilih!').fadeIn().delay(3000).fadeOut()
			}
		});

		$('#btn-hapus').click(function() {
			try {
				method = 'delete';
				var id = table.row('.selected').data()['id'];
				$.ajax({
					url: "<?= base_url('menu/getHeaderMenu') ?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('[name="id"]').val(data.id);
						$('[name="header_menu"]').val(data.header_menu);
						$('[name="header_menu"]').attr('readonly', true);

						$('#formModal').modal('show');
						$('#formModalLabel').text("Hapus Data " + "<?= $title; ?>");
						$('#btn-submit').addClass('btn-danger');
						$('#btn-submit').text('Hapus!');
					},
					error: function(jqXHR, textStatus, errorThrown) {
						alert('Error get data from ajax');
					}
				});
			} catch (err) {
				$('#respon-message').addClass('alert-danger')
				$('#respon-message').html('Data Belum Dipilih!').fadeIn().delay(3000).fadeOut()
			}
		});

		$('#btn-submit').click(function() {
			var url;
			if (method == 'save') {
				url = '<?= base_url('menu/addHeaderMenu'); ?>'
			} else if (method == 'update') {
				url = '<?= base_url('menu/updateHeaderMenu'); ?>'
			} else {
				url = '<?= base_url('menu/deleteHeaderMenu'); ?>'
			}

			$.ajax({
				url: url,
				type: 'POST',
				data: $("#form").serialize(),
				dataType: 'json',
				beforeSend: function(e) {
					if (e && e.overrideMimeType) {
						e.overrideMimeType('application/jsoncharset=UTF-8')
					}
				},
				success: function(response) {
					if (response.status == 0) {
						$('#respon-message').addClass('alert-success')
						$('#respon-message').html(response.pesan).fadeIn().delay(3000).fadeOut()
						$('#formModal').modal('hide')
						reloadTable()
					} else {
						$('[name="' + response.inputerror + '"]').addClass('is-invalid');
						$('[name="' + response.inputerror + '"]').next().html(response.error_string);
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText)
				}
			})
		});

	});
</script>