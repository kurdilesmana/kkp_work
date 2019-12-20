<script type="text/javascript">
	$(document).ready(function() {
		var table = $('#dataTable').DataTable({
			select: true,
			processing: true,
			serverSide: true,
			ajax: {
				'url': '<?= base_url('sparepart/sparepart') ?>',
				'type': 'POST'
			},
			columns: [{
				data: 'nama'
			}, {
				data: 'jenis'
			}, {
                data: 'stock'
            }, {
                data: 'harga'
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
				var id = table.row('.selected').data()['id_sparepart'];

				$.ajax({
					url: "<?= base_url('sparepart/getSparepart') ?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('[name="id_sparepart"]').val(data.id_sparepart);
						$('[name="nama"]').val(data.nama);
						$('[name="jenis"]').val(data.jenis);
                        $('[name="stock"]').val(data.stock);
                        $('[name="harga"]').val(data.harga);

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
				var id = table.row('.selected').data()['id_sparepart'];
				$.ajax({
					url: "<?= base_url('sparepart/getSparepart') ?>/" + id,
					type: "GET",
					dataType: "JSON",
					success: function(data) {
						$('[name="id_sparepart"]').val(data.id_sparepart);
						$('[name="nama"]').val(data.nama);
						$('[name="jenis"]').val(data.jenis);
                        $('[name="stock"]').val(data.stock);
                        $('[name="harga"]').val(data.harga);
						
						$('[name="nama"]').attr('readonly', true);
						$('[name="jenis"]').attr('readonly', true);
                        $('[name="stock"]').attr('readonly', true);
                        $('[name="harga"]').attr('readonly', true);

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
				url = '<?= base_url('sparepart/addSparepart'); ?>'
			} else if (method == 'update') {
				url = '<?= base_url('sparepart/updateSparepart'); ?>'
			} else {
				url = '<?= base_url('sparepart/deleteSparepart'); ?>'
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