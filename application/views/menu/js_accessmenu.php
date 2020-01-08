<script type="text/javascript">
	var table;
	var method;

	$(document).ready(function() {
		table = $('#dataTable').DataTable({
			select: true,
			processing: true,
			serverSide: true,
			ajax: {
				'url': '<?= base_url('menu/accessMenuList') ?>',
				'type': 'POST'
			},
			columns: [{
				data: 'menu'
			}, {
				data: 'role'
			}]
		});

		function reloadTable() {
			table.ajax.reload(null, false);
		}

		function resetForm() {
			$('#form')[0].reset();
			$('#menu_id').val(null).trigger('change');
			$('#role_id').val(null).trigger('change');
			$('.btn').removeClass('btn-danger');
			$('.form-control').removeClass('is-invalid');
			$('.invalid-feedback').empty();
		};

		$('#response-message').hide();

		$('#menu_id').select2({
			theme: 'classic',
			placeholder: 'Pilih Menu',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url('menu/searchMenu'); ?>',
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
					url: "<?= base_url('menu/getAccessMenu') ?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('[name="id"]').val(data.id);
						$.ajax({
							type: 'GET',
							url: "<?= base_url('menu/getSubMenu') ?>/" + data.menu_id,
							dataType: "JSON"
						}).then(function(data) {
							// create the option and append to Select2
							var option = new Option(data.title, data.id, true, true);
							$('#menu_id').append(option).trigger('change');

							// manually trigger the `select2:select` event
							$('#menu_id').trigger({
								type: 'select2:select',
								params: {
									data: data
								}
							});
						});

						$.ajax({
							type: 'GET',
							url: "<?= base_url('users/getRole') ?>/" + data.role_id,
							dataType: "JSON"
						}).then(function(data) {
							// create the option and append to Select2
							var option = new Option(data.name, data.id, true, true);
							$('#role_id').append(option).trigger('change');

							// manually trigger the `select2:select` event
							$('#role_id').trigger({
								type: 'select2:select',
								params: {
									data: data
								}
							});
						});

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
				var id = table.row('.selected').data()['id'];

				$.ajax({
					url: "<?= base_url('menu/getAccessMenu') ?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('[name="id"]').val(data.id);
						$.ajax({
							type: 'GET',
							url: "<?= base_url('menu/getSubMenu') ?>/" + data.menu_id,
							dataType: "JSON"
						}).then(function(data) {
							// create the option and append to Select2
							var option = new Option(data.title, data.id, true, true);
							$('#menu_id').append(option).trigger('change');

							// manually trigger the `select2:select` event
							$('#menu_id').trigger({
								type: 'select2:select',
								params: {
									data: data
								}
							});
						});

						$.ajax({
							type: 'GET',
							url: "<?= base_url('users/getRole') ?>/" + data.role_id,
							dataType: "JSON"
						}).then(function(data) {
							// create the option and append to Select2
							var option = new Option(data.name, data.id, true, true);
							$('#role_id').append(option).trigger('change');

							// manually trigger the `select2:select` event
							$('#role_id').trigger({
								type: 'select2:select',
								params: {
									data: data
								}
							});
						});

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

		$('#btn-submit').click(function() {
			var url;
			if (method == 'save') {
				url = '<?= base_url('menu/addAccessMenu'); ?>'
			} else if (method == 'update') {
				url = '<?= base_url('menu/updateAccessMenu'); ?>'
			} else {
				url = '<?= base_url('menu/deleteSubMenu'); ?>'
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
						$('#response-message').removeClass('alert-danger')
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