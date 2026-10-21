<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
    $isEdit = !empty($provider);
    $actionUrl = $isEdit ? site_url('providers/' . $provider['id']) : site_url('providers');
?>

<div class="row justify-content-center">
    <div class="col-lg-9">
        <!-- BREADCRUMB -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= site_url('providers') ?>">Providers</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $isEdit ? 'Edit Provider' : 'Register Provider' ?></li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 fw-bold mb-0"><?= esc($title) ?></h2>
            <a href="<?= $isEdit ? site_url('providers/' . $provider['id']) : site_url('providers') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Cancel
            </a>
        </div>

        <form method="post" action="<?= $actionUrl ?>" class="card shadow-sm p-4">
            <?= csrf_field() ?>

            <!-- CATEGORY & NAME -->
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-shop text-primary me-2"></i>Provider Profile
            </h5>

            <div class="row g-3 mb-4">
                <div class="col-md-7">
                    <label class="form-label fw-semibold">Company / Provider Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" placeholder="e.g. Gourmet Haven Catering" required
                           value="<?= old('name', $provider['name'] ?? '') ?>">
                </div>

                <div class="col-md-5">
                    <label class="form-label fw-semibold">Service Category</label>
                    <select name="category_id" class="form-select">
                        <option value="">-- Select Category --</option>
                        <?php $selectedCat = old('category_id', $provider['category_id'] ?? ''); ?>
                        <?php foreach ($categories as $cat): ?>
                            <option value="<?= $cat['id'] ?>" <?= (string)$selectedCat === (string)$cat['id'] ? 'selected' : '' ?>>
                                <?= esc($cat['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Price Range</label>
                    <select name="price_range" class="form-select">
                        <?php
                            $ranges = [
                                '$'    => '$ (Budget Friendly)',
                                '$$'   => '$$ (Moderate / Standard)',
                                '$$$'  => '$$$ (Premium / High End)',
                                '$$$$' => '$$$$ (Luxury / Ultra Exclusive)',
                            ];
                            $selectedPrice = old('price_range', $provider['price_range'] ?? '$$');
                        ?>
                        <?php foreach ($ranges as $k => $lbl): ?>
                            <option value="<?= $k ?>" <?= $selectedPrice === $k ? 'selected' : '' ?>><?= $lbl ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Rating</label>
                    <select name="rating" class="form-select">
                        <?php $selectedRating = (int) old('rating', $provider['rating'] ?? 5); ?>
                        <option value="5" <?= $selectedRating === 5 ? 'selected' : '' ?>>★★★★★ (5 Stars - Top Tier)</option>
                        <option value="4" <?= $selectedRating === 4 ? 'selected' : '' ?>>★★★★☆ (4 Stars - Great)</option>
                        <option value="3" <?= $selectedRating === 3 ? 'selected' : '' ?>>★★★☆☆ (3 Stars - Average)</option>
                        <option value="2" <?= $selectedRating === 2 ? 'selected' : '' ?>>★★☆☆☆ (2 Stars)</option>
                        <option value="1" <?= $selectedRating === 1 ? 'selected' : '' ?>>★☆☆☆☆ (1 Star)</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <?php
                            $statuses = [
                                'preferred' => 'Preferred Vendor (Recommended)',
                                'active'    => 'Active',
                                'inactive'  => 'Inactive / Unavailable',
                            ];
                            $selectedStatus = old('status', $provider['status'] ?? 'active');
                        ?>
                        <?php foreach ($statuses as $val => $lbl): ?>
                            <option value="<?= $val ?>" <?= $selectedStatus === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- CONTACT DETAILS -->
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-person-lines-fill text-primary me-2"></i>Contact Information
            </h5>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Primary Contact Person</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-person"></i></span>
                        <input type="text" name="contact_person" class="form-control" placeholder="e.g. Alex Morales"
                               value="<?= old('contact_person', $provider['contact_person'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="email" class="form-control" placeholder="booking@provider.com"
                               value="<?= old('email', $provider['email'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Phone Number</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="phone" class="form-control" placeholder="+1 (555) 123-4567"
                               value="<?= old('phone', $provider['phone'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Website or Social Profile</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-globe"></i></span>
                        <input type="url" name="website" class="form-control" placeholder="https://provider.com"
                               value="<?= old('website', $provider['website'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Street Address / Base Location</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" name="address" class="form-control" placeholder="123 Music Ave, Suite 4B"
                               value="<?= old('address', $provider['address'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">City / Region</label>
                    <input type="text" name="city" class="form-control" placeholder="Springfield"
                           value="<?= old('city', $provider['city'] ?? '') ?>">
                </div>
            </div>

            <!-- NOTES -->
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-file-text text-primary me-2"></i>Service Notes & Specialties
            </h5>

            <div class="mb-4">
                <label class="form-label fw-semibold">Notes, Specialties & Equipment Details</label>
                <textarea name="notes" rows="3" class="form-control" placeholder="Include dietary capability, DJ genres, sound equipment provided, cancellation terms, etc."><?= old('notes', $provider['notes'] ?? '') ?></textarea>
            </div>

            <!-- SUBMIT -->
            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="<?= $isEdit ? site_url('providers/' . $provider['id']) : site_url('providers') ?>" class="btn btn-light border">Cancel</a>
                <button type="submit" class="btn btn-primary px-4 fw-semibold">
                    <i class="bi bi-check2 me-1"></i> <?= $isEdit ? 'Save Changes' : 'Register Provider' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
