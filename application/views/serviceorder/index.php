<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800"><?= $title ?></h1>
<p class="mb-4"><?= $caption ?></p>

<!-- DataTales Example -->
<div class="card shadow mb-4">
	<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		<h6 class="m-0 font-weight-bold text-primary">Daftar Data <?= $title ?></h6>
		<div class="dropdown">
			<a class="btn btn-sm btn-primary dropdown-toggle" href="" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				Action
			</a>
			<div class="dropdown-menu dropdown-menu-right shadow animated--fade-in" aria-labelledby="dropdownMenuLink">
				<a class="dropdown-item font-weight-bold" href="#" id="btn-tambah">Add <i class="fa fa-btn fa-plus"></i></a>
				<a class="dropdown-item font-weight-bold" href="#" id="btn-edit">Edit <i class="fa fa-btn fa-edit"></i></a>
				<div class="dropdown-divider"></div>
				<a class="dropdown-item text-danger" href="#" id="btn-hapus">Delete <i class="fa fa-btn fa-trash"></i></a>
			</div>
		</div>
	</div>
	<div class="card-body">
		<div id="response-message" class="alert" role="alert"></div>
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Tanggal Masuk</th>
						<th>Nama</th>
						<th>Jenis</th>
						<th>Spesifikasi</th>
						<th>Serial Number</th>
						<th>Kelengkapan Barang</th>
						<th>Keluhan</th>
					</tr>
				</thead>
			</table>
		</div>
	</div>
</div>

<div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-labelledby="formModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="formModalLabel"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="form">
				<div class="modal-body">
					<input type="hidden" class="form-control" id="id_barang" name="id_barang">
					<div class="form-group">
						<input type="text" class="form-control" id="nama" name="nama" placeholder="Nama" value="<?= set_value('nama') ?>">
						<div class="invalid-feedback" name="nama-message"></div>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" id="jenis" name="jenis" placeholder="Jenis" value="<?= set_value('jenis') ?>">
						<div class="invalid-feedback" name="jenis-message"></div>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" id="spesifikasi" name="spesifikasi" placeholder="Spesifikasi" value="<?= set_value('spesifikasi') ?>">
						<div class="invalid-feedback" name="spesifikasi-message"></div>
					</div>
                    <div class="form-group">
						<input type="text" class="form-control" id="serial_number" name="serial_number" placeholder="Serial Number" value="<?= set_value('serial_number') ?>">
						<div class="invalid-feedback" name="serial_number-message"></div>
                    </div>
                    <div class="form-group">
						<input type="text" class="form-control" id="kelengkapan_barang" name="kelengkapan_barang" placeholder="Kelengkapan Barang" value="<?= set_value('kelengkapan_barang') ?>">
						<div class="invalid-feedback" name="kelengkapan_barang-message"></div>
                    </div>
                    <div class="form-group">
						<input type="text" class="form-control" id="keluhan" name="keluhan" placeholder="Keluhan" value="<?= set_value('keluhan') ?>">
						<div class="invalid-feedback" name="keluhan-message"></div>
                    </div>
                    <div class="form-group">
						<input type="date" class="form-control datepicker" id="tgl_masuk" name="tgl_masuk" placeholder="Tanggal Masuk" value="<?= set_value('tgl_masuk') ?>">
						<div class="invalid-feedback" name="tgl_masuk-message"></div>
                    </div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
					<button type="button" id="btn-submit" class="btn">Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>