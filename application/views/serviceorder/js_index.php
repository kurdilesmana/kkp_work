<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#dataTable').DataTable({
			select: true,
			processing: true,
			serverSide: true,
			ajax: {
				'url': '<?= base_url('serviceorder/serviceorder') ?>',
				'type': 'POST'
			},
			columns: [{
				data: 'tgl_masuk'
			}, {
				data: 'customer'
			}, {
				data: 'jenis'
			}, {
				data: 'spesifikasi'
			}, {
				data: 'serial_number'
			}, {
				data: 'karyawan'
			}]
		});

		function reloadTable() {
			table.ajax.reload(null, false);
		}

		function resetForm() {
			$('#form')[0].reset();
			$('#customer_id').val(null).trigger('change');
			$('#karyawan_id').val(null).trigger('change');
			$('.btn').removeClass('btn-danger');
			$('.form-control').removeClass('is-invalid');
			$('.invalid-feedback').empty();
			$('#nama').removeAttr('readonly');
		}

		$('#tgl_masuk').datepicker({
			format: 'dd/mm/yyyy',
			autoclose: true
		});

		$('#customer_id').select2({
			theme: 'classic',
			placeholder: 'Pilih Customers',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url('customers/searchCustomer'); ?>',
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

		$('#karyawan_id').select2({
			theme: 'classic',
			placeholder: 'Pilih Karyawan',
			ajax: {
				dataType: 'json',
				url: '<?php echo base_url('karyawan/searchKaryawan'); ?>',
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
				var id = table.row('.selected').data()['id_barang'];

				$.ajax({
					url: "<?= base_url('serviceorder/getServiceorder') ?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('[name="id_barang"]').val(data.id_barang);
						$('[name="tgl_masuk"]').val(data.tgl_masuk);
						$('[name="jenis"]').val(data.jenis);
						$('[name="spesifikasi"]').val(data.spesifikasi);
						$('[name="serial_number"]').val(data.serial_number);
						$('[name="kelengkapan_barang"]').val(data.kelengkapan_barang);
						$('[name="keluhan"]').val(data.keluhan);
						$.ajax({
							type: 'GET',
							url: "<?= base_url('customers/getCustomers') ?>/" + data.customer_id,
							dataType: "JSON"
						}).then(function(data) {
							// create the option and append to Select2
							var option = new Option(data.nama, data.id_customers, true, true);
							$('#customer_id').append(option).trigger('change');

							// manually trigger the `select2:select` event
							$('#customer_id').trigger({
								type: 'select2:select',
								params: {
									data: data
								}
							});
						});
						$.ajax({
							type: 'GET',
							url: "<?= base_url('karyawan/getKaryawan') ?>/" + data.karyawan_id,
							dataType: "JSON"
						}).then(function(data) {
							// create the option and append to Select2
							var option = new Option(data.nama_karyawan, data.id_karyawan, true, true);
							$('#karyawan_id').append(option).trigger('change');

							// manually trigger the `select2:select` event
							$('#karyawan_id').trigger({
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

		$('#btn-check').click(function() {
			try {
				resetForm();
				method = 'check';
				var id = table.row('.selected').data()['id_barang'];
				$.ajax({
					url: "<?= base_url('serviceorder/getServiceorder') ?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('[name="id_barang"]').val(data.id_barang);
						$('[name="tgl_masuk"]').val(data.tgl_masuk);
						$('[name="jenis"]').val(data.jenis);
						$('[name="spesifikasi"]').val(data.spesifikasi);
						$('[name="serial_number"]').val(data.serial_number);
						$('[name="kelengkapan_barang"]').val(data.kelengkapan_barang);
						$('[name="keluhan"]').val(data.keluhan);
						$.ajax({
							type: 'GET',
							url: "<?= base_url('customers/getCustomers') ?>/" + data.customer_id,
							dataType: "JSON"
						}).then(function(data) {
							$('[name="customer"]').val(data.nama);
						});
						$.ajax({
							type: 'GET',
							url: "<?= base_url('karyawan/getKaryawan') ?>/" + data.karyawan_id,
							dataType: "JSON"
						}).then(function(data) {
							$('[name="karyawan"]').val(data.nama_karyawan);
						});

						$('#formCheckModal').modal('show');
						$('#formCheckModalLabel').text("Checking Data " + "<?= $title; ?>");
						$('.btn-submit').addClass('btn-primary');
						$('.btn-submit').text('Check');

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
				var id = table.row('.selected').data()['id_barang'];
				$.ajax({
					url: "<?= base_url('serviceorder/getServiceorder') ?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('[name="id_barang"]').val(data.id_barang);
						$('[name="nama"]').val(data.nama);
						$('[name="jenis"]').val(data.jenis);
						$('[name="spesifikasi"]').val(data.spesifikasi);
						$('[name="serial_number"]').val(data.serial_number);
						$('[name="kelengkapan_barang"]').val(data.kelengkapan_barang);
						$('[name="keluhan"]').val(data.keluhan);
						$('[name="tgl_masuk"]').val(data.tgl_masuk);

						$('[name="nama"]').attr('readonly', true);
						$('[name="jenis"]').attr('readonly', true);
						$('[name="spesifikasi"]').attr('readonly', true);
						$('[name="serial_number"]').attr('readonly', true);
						$('[name="kelengkapan_barang"]').attr('readonly', true);
						$('[name="keluhan"]').attr('readonly', true);
						$('[name="tgl_masuk"]').attr('readonly', true);

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
				url = '<?= base_url('serviceorder/addServiceorder'); ?>'
			} else if (method == 'update') {
				url = '<?= base_url('serviceorder/updateServiceorder'); ?>'
			} else {
				url = '<?= base_url('serviceorder/deleteServiceorder'); ?>'
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