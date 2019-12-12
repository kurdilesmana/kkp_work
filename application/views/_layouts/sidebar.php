<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

	<!-- Sidebar - Brand -->
	<a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
		<div class="sidebar-brand-icon rotate-n-15">
			<i class="fas fa-briefcase"></i>
		</div>
		<div class="sidebar-brand-text mx-2">WorkFLow <sup>MS</sup></div>
	</a>

	<!-- Divider -->
	<hr class="sidebar-divider">

	<!-- Query Header Menu -->
	<?php $headerMenu = $this->db->get('user_header_menu')->result_array(); ?>

	<!-- Heading Menu -->
	<?php foreach ($headerMenu as $Hm) { ?>
		<div class="sidebar-heading">
			<?= strtoupper($Hm['header_menu']) ?>
		</div>

		<!-- Query Menu -->
		<?php
			$headerID = $Hm['id'];
			$roleID = $this->session->userdata('role_id');

			$this->db->select('*');
			$this->db->from('user_menu');
			$this->db->join('user_access_menu', 'user_menu.id = user_access_menu.menu_id', 'inner');
			$this->db->where('user_menu.header_id', $headerID);
			$this->db->where('user_menu.parent_id', Null);
			$this->db->where('user_access_menu.role_id', $roleID);
			$this->db->where('user_menu.is_active', 1);
			$this->db->order_by('no_order', 'ASC');
			$menu = $this->db->get()->result_array();

			foreach ($menu as $m) {
				$parentID = $m['menu_id'];

				$this->db->select('*');
				$this->db->from('user_menu');
				$this->db->join('user_access_menu', 'user_menu.id = user_access_menu.menu_id', 'inner');
				$this->db->where('user_menu.header_id', $headerID);
				$this->db->where('user_menu.parent_id', $parentID);
				$this->db->where('user_access_menu.role_id', $roleID);
				$this->db->where('user_menu.is_active', 1);
				$this->db->order_by('no_order', 'ASC');
				$subMenu = $this->db->get()->result_array();

				if ($subMenu) { ?>
				<li class="nav-item <?= $this->uri->segment(1) == $m['url'] ? 'active' : '';  ?>">
					<a class="nav-link <?= $this->uri->segment(1) == $m['url'] ? '' : 'collapsed'; ?>" href="#" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="collapseTwo">
						<i class="<?= $m['icon']; ?>"></i>
						<span><?= $m['title']; ?></span>
					</a>
					<div id="collapseTwo" class="collapse <?= $this->uri->segment(1) == $m['url'] ? 'show' : '';  ?>" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
						<div class="bg-white py-2 collapse-inner rounded">
							<?php foreach ($subMenu as $sm) { ?>
								<a class="collapse-item <?= $title == $sm['title'] ? 'active' : '' ?>" href="<?= base_url($sm['url']); ?>"><?= $sm['title']; ?></a>
							<?php } ?>
						</div>
					</div>
				</li>
			<?php } else { ?>
				<li class="nav-item <?= $title == $m['title'] ? 'active' : '' ?>">
					<a class="nav-link" href="<?= base_url($m['url']); ?>">
						<i class="<?= $m['icon']; ?>"></i>
						<span><?= $m['title']; ?></span></a>
				</li>
		<?php }
			} ?>

		<!-- Divider -->
		<hr class="sidebar-divider">

	<?php } ?>

</ul>