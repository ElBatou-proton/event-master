<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- HEADER -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="h3 fw-bold mb-1">Service Providers & Vendors</h2>
        <p class="text-muted mb-0">Manage caterers, venues, DJs, photographers, florists, and production crews.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= site_url('categories') ?>" class="btn btn-outline-secondary d-inline-flex align-items-center gap-1">
            <i class="bi bi-grid"></i> Categories
        </a>
        <a href="<?= site_url('providers/new') ?>" class="btn btn-primary d-inline-flex align-items-center gap-1 shadow-sm">
            <i class="bi bi-person-plus-fill"></i>
            <span>Register Provider</span>
        </a>
    </div>
</div>

<!-- FILTERS -->
<div class="card mb-4 p-3 bg-white">
    <form method="get" action="<?= site_url('providers') ?>" class="row g-2 align-items-center">
        <!-- Search -->
        <div class="col-md-5">
            <div class="input-group">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" name="search" class="form-control border-start-0 ps-0" placeholder="Search provider, contact person, city..." value="<?= esc($search ?? '') ?>">
            </div>
        </div>

        <!-- Category -->
        <div class="col-sm-6 col-md-3">
            <select name="category_id" class="form-select" onchange="this.form.submit()">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($categoryId ?? 0) == $cat['id'] ? 'selected' : '' ?>>
                        <?= esc($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Status -->
        <div class="col-sm-6 col-md-2">
            <select name="status" class="form-select" onchange="this.form.submit()">
                <option value="">All Statuses</option>
                <option value="preferred" <?= ($status ?? '') === 'preferred' ? 'selected' : '' ?>>Preferred</option>
                <option value="active" <?= ($status ?? '') === 'active' ? 'selected' : '' ?>>Active</option>
                <option value="inactive" <?= ($status ?? '') === 'inactive' ? 'selected' : '' ?>>Inactive</option>
            </select>
        </div>

        <!-- Action -->
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
            <?php if (!empty($search) || !empty($categoryId) || !empty($status)): ?>
                <a href="<?= site_url('providers') ?>" class="btn btn-outline-secondary" title="Clear Filters"><i class="bi bi-x-lg"></i></a>
            <?php endif; ?>
        </div>
    </form>
</div>

<!-- PROVIDERS GRID -->
<?php if (empty($providers)): ?>
    <div class="card shadow-sm text-center py-5">
        <div class="card-body">
            <i class="bi bi-person-x fs-1 text-muted d-block mb-2"></i>
            <h5 class="fw-semibold">No service providers found</h5>
            <p class="text-muted mb-3">Try adjusting your filters or register a new vendor.</p>
            <a href="<?= site_url('providers/new') ?>" class="btn btn-primary">Register New Provider</a>
        </div>
    </div>
<?php else: ?>
    <div class="row g-3">
        <?php foreach ($providers as $prov): ?>
            <div class="col-md-6 col-xl-4">
                <div class="card h-100 shadow-sm border metric-card d-flex flex-column justify-content-between p-3">
                    <div>
                        <!-- TOP ROW: Category & Rating -->
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="category-pill">
                                <i class="bi <?= esc($prov['category_icon'] ?? 'bi-tag') ?> text-primary"></i>
                                <?= esc($prov['category_name'] ?? 'General') ?>
                            </span>
                            <div class="d-flex align-items-center gap-1">
                                <?php if ($prov['status'] === 'preferred'): ?>
                                    <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle me-1">Preferred</span>
                                <?php endif; ?>
                                <span class="badge bg-light text-warning border">
                                    <?= str_repeat('★', $prov['rating'] ?? 5) ?>
                                </span>
                            </div>
                        </div>

                        <!-- Provider Name -->
                        <h5 class="fw-bold mb-1">
                            <a href="<?= site_url('providers/' . $prov['id']) ?>" class="text-dark text-decoration-none">
                                <?= esc($prov['name']) ?>
                            </a>
                        </h5>

                        <?php if ($prov['contact_person']): ?>
                            <div class="small text-muted mb-2">
                                <i class="bi bi-person me-1"></i><?= esc($prov['contact_person']) ?>
                                <?php if ($prov['city']): ?>
                                    <span class="ms-1">&bull; <i class="bi bi-geo-alt"></i> <?= esc($prov['city']) ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <!-- Notes / Description -->
                        <p class="small text-secondary mb-3 text-truncate-2" style="min-height: 40px;">
                            <?= esc($prov['notes'] ?? 'Available for private and corporate event bookings.') ?>
                        </p>

                        <!-- Contact Details -->
                        <div class="small bg-light p-2 rounded mb-3 border">
                            <?php if ($prov['phone']): ?>
                                <div class="mb-1 text-truncate">
                                    <i class="bi bi-telephone text-primary me-2"></i>
                                    <a href="tel:<?= esc($prov['phone']) ?>" class="text-dark text-decoration-none"><?= esc($prov['phone']) ?></a>
                                </div>
                            <?php endif; ?>
                            <?php if ($prov['email']): ?>
                                <div class="mb-1 text-truncate">
                                    <i class="bi bi-envelope text-primary me-2"></i>
                                    <a href="mailto:<?= esc($prov['email']) ?>" class="text-dark text-decoration-none"><?= esc($prov['email']) ?></a>
                                </div>
                            <?php endif; ?>
                            <?php if ($prov['website']): ?>
                                <div class="text-truncate">
                                    <i class="bi bi-globe text-primary me-2"></i>
                                    <a href="<?= esc($prov['website']) ?>" target="_blank" rel="noopener" class="text-dark text-decoration-none"><?= esc($prov['website']) ?></a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <!-- FOOTER ACTIONS -->
                    <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                        <span class="fw-bold text-success fs-6"><?= esc($prov['price_range'] ?? '$$') ?></span>
                        <div class="btn-group btn-group-sm">
                            <a href="<?= site_url('providers/' . $prov['id']) ?>" class="btn btn-outline-primary" title="View Bookings & Details">
                                <i class="bi bi-eye"></i> View
                            </a>
                            <a href="<?= site_url('providers/' . $prov['id'] . '/edit') ?>" class="btn btn-outline-secondary" title="Edit Provider">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <button type="button" class="btn btn-outline-danger" data-bs-toggle="modal" data-bs-target="#deleteProvModal-<?= $prov['id'] ?>" title="Delete">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- DELETE MODAL -->
                <div class="modal fade" id="deleteProvModal-<?= $prov['id'] ?>" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered text-start">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title fw-bold text-danger">Delete Provider?</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>Are you sure you want to delete <strong><?= esc($prov['name']) ?></strong>?</p>
                                <p class="text-muted small mb-0">This will remove this provider from the directory and any associated event records.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                                <form method="post" action="<?= site_url('providers/' . $prov['id'] . '/delete') ?>">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-danger">Yes, Delete</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<?= $this->endSection() ?>
