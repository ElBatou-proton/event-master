<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<!-- HEADER -->
<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="h3 fw-bold mb-1">Service & Provider Categories</h2>
        <p class="text-muted mb-0">Organize your vendors by service specialty (Catering, Venues, DJs, AV, Photography, etc.).</p>
    </div>
    <div>
        <button type="button" class="btn btn-primary d-inline-flex align-items-center gap-1 shadow-sm" data-bs-toggle="modal" data-bs-target="#newCategoryModal">
            <i class="bi bi-folder-plus"></i>
            <span>Add Category</span>
        </button>
    </div>
</div>

<!-- CATEGORIES GRID -->
<div class="row g-3 mb-4">
    <?php foreach ($categories as $cat): ?>
        <div class="col-md-6 col-lg-4">
            <div class="card h-100 shadow-sm border metric-card p-3 d-flex flex-column justify-content-between">
                <div>
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <span class="icon-box bg-primary-subtle text-primary" style="width: 44px; height: 44px; font-size: 1.25rem;">
                                <i class="bi <?= esc($cat['icon'] ?: 'bi-tag') ?>"></i>
                            </span>
                            <div>
                                <h5 class="fw-bold mb-0 text-dark"><?= esc($cat['name']) ?></h5>
                                <span class="badge bg-secondary-subtle text-secondary small"><?= esc($cat['slug']) ?></span>
                            </div>
                        </div>
                        <?php if ($cat['provider_count'] == 0): ?>
                            <form method="post" action="<?= site_url('categories/' . $cat['id'] . '/delete') ?>" onsubmit="return confirm('Delete this empty category?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm text-danger p-0" title="Delete category">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <p class="small text-muted mb-3" style="min-height: 40px;">
                        <?= esc($cat['description'] ?? 'Event service specialty providers.') ?>
                    </p>
                </div>

                <div class="d-flex justify-content-between align-items-center pt-2 border-top">
                    <span class="small fw-semibold text-dark">
                        <i class="bi bi-people-fill text-primary me-1"></i><?= esc($cat['provider_count']) ?> registered vendors
                    </span>
                    <a href="<?= site_url('providers?category_id=' . $cat['id']) ?>" class="btn btn-sm btn-outline-primary">
                        Browse Vendors &rarr;
                    </a>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<!-- MODAL: NEW CATEGORY -->
<div class="modal fade" id="newCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form method="post" action="<?= site_url('categories') ?>">
                <?= csrf_field() ?>
                <div class="modal-header">
                    <h5 class="modal-title fw-bold">Create New Service Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Category Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" placeholder="e.g. Fireworks & Special FX" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Icon (Bootstrap Icon Class)</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-stars"></i></span>
                            <input type="text" name="icon" class="form-control" placeholder="bi-stars" value="bi-tag">
                        </div>
                        <div class="form-text">Choose from <a href="https://icons.getbootstrap.com/" target="_blank">Bootstrap Icons</a> (e.g. bi-cup-hot, bi-camera, bi-music-note-beamed).</div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Description</label>
                        <textarea name="description" rows="3" class="form-control" placeholder="Describe the type of vendors in this category..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary fw-semibold">Create Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
