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
		<div id="response-message" class="alert alert-danger" role="alert"></div>
		<div class="table-responsive">
			<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
				<thead>
					<tr>
						<th>Name</th>
						<th>Email</th>
						<th>Role</th>
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
					<input type="hidden" class="form-control" id="id" name="id">
					<div class="form-group">
						<input type="text" class="form-control" id="name" name="name" placeholder="Nama" value="<?= set_value('name') ?>">
						<div class="invalid-feedback" name="name-message"></div>
					</div>
					<div class="form-group">
						<input type="email" class="form-control" id="email" name="email" placeholder="Email" value="<?= set_value('email') ?>">
						<div class="invalid-feedback" name="email-message"></div>
					</div>
					<div class="form-group">
						<input type="password" class="form-control" id="password" name="password" placeholder="Password">
						<div class="invalid-feedback" name="password-message"></div>
					</div>
					<div class="form-group">
						<input type="password" class="form-control" id="confirm-password" name="confirm-password" placeholder="Konfirmasi Password">
						<div class="invalid-feedback" name="confirm-password-message"></div>
					</div>
					<div class="form-group ">
						<select class="form-control select2-hidden-accessible" id="role_id" name="role_id" style="width: 100%;" tabindex="-1" aria-hidden="true" required></select>
						<div class="invalid-feedback" name="role_id-message"></div>
					</div>
					<div class="form-group">
						<div class="custom-control custom-checkbox">
							<input type="checkbox" class="custom-control-input" id="is_active" name="is_active" value="1" checked>
							<label class="custom-control-label" for="is_active">Aktif</label>
						</div>
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