<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#dataTable').DataTable({
			select: true,
			processing: true,
			serverSide: true,
			ajax: {
				'url': '<?= base_url('users/usersList') ?>',
				'type': 'POST'
			},
			columns: [{
				data: 'name'
			}, {
				data: 'email'
			}, {
				"render": function(data, type, row) {
					var role = ""
					if (row.is_active == 1) {
						role = 'Administrator'
					} else {
						role = 'Users'
					}
					return role;
				}
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
		}

		$('#role_id').select2({
			theme: 'classic',
			placeholder: 'Pilih Role',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url('users/searchRole'); ?>',
				delay: 250,
				data: function(params) {
					return {
						q: params.term
					}
				},
				processResults: function(data, page) {
					return {
						results: data
					};
				},
			}
		});

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
				var id = table.row('.selected').data()['id'];

				$.ajax({
					url: "<?= base_url('users/getUsers') ?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('[name="id_karyawan"]').val(data.id_karyawan);
						$('[name="nama_karyawan"]').val(data.nama_karyawan);
						$('[name="bagian_karyawan"]').val(data.bagian_karyawan);

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
				var id = table.row('.selected').data()['id_karyawan'];
				$.ajax({
					url: "<?= base_url('karyawan/getkaryawan') ?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('[name="id_karyawan"]').val(data.id_karyawan);
						$('[name="nama_karyawan"]').val(data.nama_karyawan);
						$('[name="bagian_karyawan"]').val(data.bagian_karyawan);

						$('[name="nama_karyawan"]').attr('readonly', true);
						$('[name="bagian_karyawan"]').attr('readonly', true);

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
				url = '<?= base_url('users/add'); ?>'
			} else if (method == 'update') {
				url = '<?= base_url('karyawan/updateKaryawan'); ?>'
			} else {
				url = '<?= base_url('karyawan/deleteKaryawan'); ?>'
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
					} else if (response.status == 1) {
						$('#response-message').addClass('alert-danger')
						$('#response-message').html(response.pesan).fadeIn().delay(3000).fadeOut()
						$('#formModal').modal('hide')
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