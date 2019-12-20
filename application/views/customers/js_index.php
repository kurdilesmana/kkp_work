<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#dataTable').DataTable({
			select: true,
			processing: true,
			serverSide: true,
			ajax: {
				'url': '<?= base_url('customers/customers') ?>',
				'type': 'POST'
			},
			columns: [{
				data: 'nama'
			}, {
				data: 'alamat'
			}, {
                data: 'phone'
            }]
		});

		function reloadTable() {
			table.ajax.reload(null, false);
		}

		function resetForm() {
			$('#form')[0].reset();
			$('.btn').removeClass('btn-danger');
			$('.form-control').removeClass('is-invalid');
			$('.invalid-feedback').empty();
			$('#nama').removeAttr('readonly');
		}

		$('#response-message').hide();

		$('#btn-tambah').click(function() {
			resetForm();
			method = 'save';
			$('#formModal').modal('show');
			$('#formModalLabel').text("Tambah Data " + "<?= $title; ?>");
			$('#btn-submit').addClass('btn-primary');
			$('#btn-submit').text('Simpan');
		});

		$('#btn-edit').click(function() {
			try {
				resetForm();
				method = 'update';
				var id = table.row('.selected').data()['id_customers'];

				$.ajax({
					url: "<?= base_url('customers/getCustomers') ?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('[name="id_customers"]').val(data.id_customers);
						$('[name="nama"]').val(data.nama);
						$('[name="alamat"]').val(data.alamat);
                        $('[name="phone"]').val(data.phone);

						$('#formModal').modal('show');
						$('#formModalLabel').text("Edit Data " + "<?= $title; ?>");
						$('#btn-submit').addClass('btn-primary');
						$('#btn-submit').text('Ubah');

					},
					error: function(jqXHR, textStatus, errorThrown) {
						alert('Error get data from ajax');
					}
				});
			} catch (err) {
				$('#response-message').addClass('alert-danger')
				$('#response-message').html('Data Belum Dipilih!').fadeIn().delay(3000).fadeOut()
			}
		});

		$('#btn-hapus').click(function() {
			try {
				method = 'delete';
				var id = table.row('.selected').data()['id_customers'];
				$.ajax({
					url: "<?= base_url('customers/getcustomers') ?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('[name="id_customers"]').val(data.id_customers);
						$('[name="nama"]').val(data.nama);
						$('[name="alamat"]').val(data.alamat);
                        $('[name="phone"]').val(data.phone);
						
						$('[name="nama"]').attr('readonly', true);
						$('[name="alamat"]').attr('readonly', true);
                        $('[name="phone"]').attr('readonly', true);

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
				$('#response-message').addClass('alert-danger')
				$('#response-message').html('Data Belum Dipilih!').fadeIn().delay(3000).fadeOut()
			}
		});

		$('#btn-submit').click(function() {
			var url;
			if (method == 'save') {
				url = '<?= base_url('customers/addCustomers'); ?>'
			} else if (method == 'update') {
				url = '<?= base_url('customers/updateCustomers'); ?>'
			} else {
				url = '<?= base_url('customers/deleteCustomers'); ?>'
			}

			$('.form-control').removeClass('is-invalid');
			$('.invalid-feedback').empty();

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
						$('#response-message').addClass('alert-success')
						$('#response-message').html(response.pesan).fadeIn().delay(3000).fadeOut()
						$('#formModal').modal('hide')
						reloadTable()
					} else {
						for (var i = 0; i < response.inputerror.length; i++) {
							$('[name="' + response.inputerror[i] + '"]').addClass('is-invalid');
							$('[name="' + response.inputerror[i] + '-message"]').html(response.error_string[i]).show();
						}
					}
				},
				error: function(xhr, ajaxOptions, thrownError) {
					alert(xhr.responseText)
				}
			})
		});

	});
</script>