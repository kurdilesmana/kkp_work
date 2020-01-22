<!-- Page Heading -->
<h1 class="h3 mb-2 text-gray-800"><?= $title ?></h1>
<p class="mb-4"><?= $caption ?></p>

<!-- DataTales Example -->
<div class="card shadow mb-4">
	<div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
		<h6 class="m-0 font-weight-bold text-primary">Daftar Data <?= $title ?></h6>
		<div class="dropdown">
			<a class="btn btn-sm btn-info" href="#" role="button" id="btn-check">Check <i class="fa fa-btn fa-check-circle"></i></a>
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
						<th>Customer</th>
						<th>Jenis</th>
						<th>Spesifikasi</th>
						<th>Serial Number</th>
						<th>Teknisi</th>
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
						<select class="form-control form-control-lg select2-hidden-accessible" id="customer_id" name="customer_id" style="width: 100%;" tabindex="-1" aria-hidden="true" required></select>
						<div class="invalid-feedback" name="customer_id-message"></div>
					</div>
					<div class="form-group">
						<input type="text" class="form-control" id="tgl_masuk" name="tgl_masuk" placeholder="Tanggal Masuk" value="<?= set_value('tgl_masuk') ?>">
						<div class="invalid-feedback" name="tgl_masuk-message"></div>
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
						<select class="form-control select2-hidden-accessible col-sm" id="karyawan_id" name="karyawan_id" style="width: 100%;" tabindex="-1" aria-hidden="true" required></select>
						<div class="invalid-feedback" name="karyawan_id-message"></div>
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

<div class="modal fade" id="formCheckModal" tabindex="-1" role="dialog" aria-labelledby="formCheckModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-xl" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="formCheckModalLabel"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form id="formCheck">
				<div class="modal-body">
					<div class="row">
						<div class="col-md-4">
							<div id="cardOrder" class="card">
								<h6 class="card-header text-center">Service Order</h6>
								<div class="card-body">
									<input type="hidden" class="form-control" name="id_barang">
									<div class="form-group row mb-1">
										<label for="tgl_masuk" class="col-sm-4 text-xs">Tanggal Masuk</label>
										<div class="col-sm-8">
											<input type="text" class="form-control form-control-sm" name="tgl_masuk">
										</div>
									</div>
									<div class="form-group row mb-1">
										<label for="tgl_masuk" class="col-sm-4 text-xs">Customer</label>
										<div class="col-sm-8">
											<input type="text" class="form-control form-control-sm" name="customer">
										</div>
									</div>
									<div class="form-group row mb-1">
										<label for="tgl_masuk" class="col-sm-4 text-xs">Jenis</label>
										<div class="col-sm-8">
											<input type="text" class="form-control form-control-sm" name="jenis">
										</div>
									</div>
									<div class="form-group row mb-1">
										<label for="tgl_masuk" class="col-sm-4 text-xs">Spesifikasi</label>
										<div class="col-sm-8">
											<input type="text" class="form-control form-control-sm" name="spesifikasi">
										</div>
									</div>
									<div class="form-group row mb-1">
										<label for="tgl_masuk" class="col-sm-4 text-xs">Serial Number</label>
										<div class="col-sm-8">
											<input type="text" class="form-control form-control-sm" name="serial_number">
										</div>
									</div>
									<div class="form-group row mb-1">
										<label for="tgl_masuk" class="col-sm-4 text-xs">Kelengkapan Barang</label>
										<div class="col-sm-8">
											<textarea class="form-control form-control-sm" name="kelengkapan_barang" rows="3"></textarea>
										</div>
									</div>
									<div class="form-group row mb-1">
										<label for="tgl_masuk" class="col-sm-4 text-xs">Keluhan</label>
										<div class="col-sm-8">
											<textarea class="form-control form-control-sm" name="keluhan" rows="3"></textarea>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-8">
							<div id="cardCheck" class="card">
								<h6 class="card-header">Service Check</h6>
								<div class="card-body">
									<div class="form-group row">
										<div class="col-md-10">
											<input type="text" class="form-control" id="diagnosis_kerusakan" name="diagnosis_kerusakan" placeholder="Diagnosis Kerusakan">
											<div class="invalid-feedback" name="diagnosis_kerusakan-message"></div>
										</div>
									</div>
									<div class="form-group row">
										<div class="col-sm-8">
											<select class="form-control" id="sparepart" name="sparepart" style="width: 100%" aria-hidden="true"></select>
										</div>
										<div class="col-sm-2">
											<input type="number" class="form-control form-control-sm" name="jml_sparepart" id="jml_sparepart" value="1">
										</div>
										<button class="btn btn-sm btn-info mr-1" href="#" type="button" id="btn-AddSparepart"><i class="fa fa-btn fa-plus-circle"></i></button>
										<button class="btn btn-sm btn-danger" href="#" type="button" id="btn-DelSparepart"><i class="fa fa-btn fa-trash"></i></button>
									</div>
									<div id="sparepart-message" class="alert alert-danger" role="alert"></div>
									<div class="table-responsive">
										<table class="table table-bordered" id="dataSparepart" width="100%" cellspacing="0" style="font-size:14px;">
											<thead>
												<tr>
													<th>Sparepart</th>
													<th>Jumlah</th>
													<th>Harga</th>
													<th>Total</th>
												</tr>
											</thead>
										</table>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class=" modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
					<button type="button" class="btn btn-submit">Simpan</button>
				</div>
			</form>
		</div>
	</div>
</div>