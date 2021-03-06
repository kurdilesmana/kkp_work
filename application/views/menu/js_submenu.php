<script type="text/javascript">
	var table;
	var method;

	$(document).ready(function() {
		table = $('#dataTable').DataTable({
			select: true,
			processing: true,
			serverSide: true,
			ajax: {
				'url': '<?= base_url('menu/subMenuList') ?>',
				'type': 'POST'
			},
			columns: [{
				data: 'header_menu'
			}, {
				data: 'no_order'
			}, {
				data: 'title'
			}, {
				data: 'url'
			}, {
				data: 'icon'
			}, {
				"render": function(data, type, row) {
					var status = ""
					if (row.is_active == 1) {
						status = 'Aktif'
					} else {
						status = 'Tidak Aktif'
					}
					return status;
				}
			}]
		});

		function reloadTable() {
			table.ajax.reload(null, false);
		}

		function resetForm() {
			$('#form')[0].reset();
			$('#header_id').val(null).trigger('change');
			$('#parent_id').val(null).trigger('change');
			$('.btn').removeClass('btn-danger');
			$('.form-control').removeClass('is-invalid');
			$('.invalid-feedback').empty();
		};

		function enableForm() {
			$("#form :input").prop("readonly", false);
		};

		function disableForm() {
			$("#form :input").prop("readonly", true);
			$("#form :select").prop("readonly", true);
		};

		$('#response-message').hide();

		$('#header_id').select2({
			theme: 'classic',
			placeholder: 'Pilih Header Menu',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url('menu/searchHeader'); ?>',
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

		$('#parent_id').select2({
			theme: 'classic',
			placeholder: 'Pilih Parent Menu',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url('menu/searchSubMenu'); ?>',
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

		$('#is_active').select2({
			theme: 'classic',
			data: [{
					id: 1,
					text: 'Aktif'
				},
				{
					id: 0,
					text: 'Tidak Aktif'
				}
			]
		});

		$('#btn-tambah').click(function() {
			resetForm();
			enableForm();
			method = 'save';
			$('#formModal').modal('show');
			$('#formModalLabel').text("Tambah Data " + "<?= $title; ?>");
			$('#btn-submit').addClass('btn-primary');
			$('#btn-submit').text('Simpan');
		});

		$('#btn-edit').click(function() {
			try {
				resetForm();
				enableForm();
				method = 'update';
				var id = table.row('.selected').data()['id'];

				$.ajax({
					url: "<?= base_url('menu/getSubMenu') ?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('[name="id"]').val(data.id);
						$('[name="title"]').val(data.title);
						$('[name="url"]').val(data.url);
						$('[name="icon"]').val(data.icon);
						$('[name="no_order"]').val(data.no_order);

						// set select2
						$.ajax({
							type: 'GET',
							url: "<?= base_url('menu/getHeaderMenu') ?>/" + data.header_id,
							dataType: "JSON"
						}).then(function(data) {
							// create the option and append to Select2
							var option = new Option(data.header_menu, data.id, true, true);
							$('#header_id').append(option).trigger('change');

							// manually trigger the `select2:select` event
							$('#header_id').trigger({
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
					url: "<?= base_url('menu/getSubMenu') ?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('[name="id"]').val(data.id);
						$('[name="title"]').val(data.title);
						$('[name="url"]').val(data.url);
						$('[name="icon"]').val(data.icon);
						$('[name="no_order"]').val(data.no_order);
						$.ajax({
							type: 'GET',
							url: "<?= base_url('menu/getHeaderMenu') ?>/" + data.header_id,
							dataType: "JSON"
						}).then(function(data) {
							var option = new Option(data.header_menu, data.id, true, true);
							$('#header_id').append(option).trigger('change');
							$('#header_id').trigger({
								type: 'select2:select',
								params: {
									data: data
								}
							});
						});

						disableForm();
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
				url = '<?= base_url('menu/addSubMenu'); ?>'
			} else if (method == 'update') {
				url = '<?= base_url('menu/updateSubMenu'); ?>'
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
						$('#response-message').removeClass('alert-danger');
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