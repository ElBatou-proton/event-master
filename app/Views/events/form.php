<?= $this->extend('layouts/main') ?>

<?= $this->section('content') ?>

<?php
    $isEdit = !empty($event);
    $actionUrl = $isEdit ? site_url('events/' . $event['id']) : site_url('events');
?>

<div class="row justify-content-center">
    <div class="col-lg-10">
        <!-- BREADCRUMB & TITLE -->
        <nav aria-label="breadcrumb" class="mb-3">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?= site_url('events') ?>">Events</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= $isEdit ? 'Edit Event' : 'New Event' ?></li>
            </ol>
        </nav>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="h3 fw-bold mb-0"><?= esc($title) ?></h2>
            <a href="<?= $isEdit ? site_url('events/' . $event['id']) : site_url('events') ?>" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-1"></i> Cancel
            </a>
        </div>

        <form method="post" action="<?= $actionUrl ?>" class="card shadow-sm p-4">
            <?= csrf_field() ?>

            <!-- SECTION 1: EVENT BASICS -->
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-info-circle text-primary me-2"></i>Event Information
            </h5>

            <div class="row g-3 mb-4">
                <div class="col-md-8">
                    <label class="form-label fw-semibold">Event Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Jenkins & Smith Wedding Reception" required
                           value="<?= old('title', $event['title'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Event Type</label>
                    <select name="event_type" class="form-select">
                        <?php
                            $types = [
                                'wedding'     => 'Wedding',
                                'corporate'   => 'Corporate Summit / Meeting',
                                'birthday'    => 'Birthday Celebration',
                                'conference'  => 'Conference / Seminar',
                                'concert'     => 'Concert / Performance',
                                'party'       => 'Private Party / Gala',
                                'anniversary' => 'Anniversary',
                                'other'       => 'Other Event',
                            ];
                            $selectedType = old('event_type', $event['event_type'] ?? 'wedding');
                        ?>
                        <?php foreach ($types as $key => $label): ?>
                            <option value="<?= $key ?>" <?= $selectedType === $key ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Event Date <span class="text-danger">*</span></label>
                    <input type="date" name="event_date" class="form-control" required
                           value="<?= old('event_date', $event['event_date'] ?? date('Y-m-d', strtotime('+30 days'))) ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Start Time</label>
                    <input type="time" name="start_time" class="form-control"
                           value="<?= old('start_time', isset($event['start_time']) ? substr($event['start_time'], 0, 5) : '18:00') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">End Time</label>
                    <input type="time" name="end_time" class="form-control"
                           value="<?= old('end_time', isset($event['end_time']) ? substr($event['end_time'], 0, 5) : '23:30') ?>">
                </div>

                <div class="col-md-8">
                    <label class="form-label fw-semibold">Venue / Location Name</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                        <input type="text" name="location_venue" class="form-control" placeholder="e.g. The Grand Glasshouse Gardens"
                               value="<?= old('location_venue', $event['location_venue'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Status</label>
                    <select name="status" class="form-select">
                        <?php
                            $statuses = [
                                'draft'       => 'Draft',
                                'planning'    => 'Planning',
                                'confirmed'   => 'Confirmed',
                                'in_progress' => 'In Progress',
                                'completed'   => 'Completed',
                                'cancelled'   => 'Cancelled',
                            ];
                            $selectedStatus = old('status', $event['status'] ?? 'planning');
                        ?>
                        <?php foreach ($statuses as $val => $lbl): ?>
                            <option value="<?= $val ?>" <?= $selectedStatus === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- SECTION 2: CLIENT & GUESTS -->
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-person text-primary me-2"></i>Client Details & Attendance
            </h5>

            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-semibold">Client / Organizer Name <span class="text-danger">*</span></label>
                    <input type="text" name="client_name" class="form-control" placeholder="e.g. Sarah Jenkins" required
                           value="<?= old('client_name', $event['client_name'] ?? '') ?>">
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Client Email</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                        <input type="email" name="client_email" class="form-control" placeholder="client@example.com"
                               value="<?= old('client_email', $event['client_email'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-4">
                    <label class="form-label fw-semibold">Client Phone</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-telephone"></i></span>
                        <input type="text" name="client_phone" class="form-control" placeholder="+1 (555) 000-0000"
                               value="<?= old('client_phone', $event['client_phone'] ?? '') ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Estimated Guest Count</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-people"></i></span>
                        <input type="number" min="0" name="guest_count" class="form-control" placeholder="150"
                               value="<?= old('guest_count', $event['guest_count'] ?? '0') ?>">
                    </div>
                </div>

                <div class="col-md-6">
                    <label class="form-label fw-semibold">Total Event Budget ($ USD)</label>
                    <div class="input-group">
                        <span class="input-group-text">$</span>
                        <input type="number" step="0.01" min="0" name="budget" class="form-control" placeholder="15000.00"
                               value="<?= old('budget', $event['budget'] ?? '0.00') ?>">
                    </div>
                </div>
            </div>

            <!-- SECTION 3: NOTES & DESCRIPTION -->
            <h5 class="fw-bold text-dark border-bottom pb-2 mb-3">
                <i class="bi bi-journal-text text-primary me-2"></i>Event Scope & Private Notes
            </h5>

            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Event Description / Program</label>
                    <textarea name="description" rows="4" class="form-control" placeholder="Provide event program, itinerary, special instructions..."><?= old('description', $event['description'] ?? '') ?></textarea>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Planner Internal Notes</label>
                    <textarea name="notes" rows="4" class="form-control" placeholder="Dietary restrictions, VIP guest preferences, setup timings..."><?= old('notes', $event['notes'] ?? '') ?></textarea>
                </div>
            </div>

            <!-- BUTTONS -->
            <div class="d-flex justify-content-end gap-2 border-top pt-3">
                <a href="<?= $isEdit ? site_url('events/' . $event['id']) : site_url('events') ?>" class="btn btn-light border">Cancel</a>
                <button type="submit" class="btn btn-primary px-4 fw-semibold">
                    <i class="bi bi-check2 me-1"></i> <?= $isEdit ? 'Save Changes' : 'Create Event' ?>
                </button>
            </div>
        </form>
    </div>
</div>

<?= $this->endSection() ?>
