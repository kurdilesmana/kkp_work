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
				data: 'nama'
			}, {
				data: 'jenis'
			},{
                data: 'spesifikasi'
            },{
                data: 'serial_number'
            },{
                data: 'kelengkapan_barang'
            },{
                data: 'keluhan'
            },{
                data: 'tgl_masuk'
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