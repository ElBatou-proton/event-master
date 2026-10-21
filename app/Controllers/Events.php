<?php

namespace App\Controllers;

use App\Models\EventModel;
use App\Models\ProviderModel;
use App\Models\EventProviderModel;
use App\Models\EventTaskModel;

class Events extends BaseController
{
    protected EventModel $eventModel;
    protected ProviderModel $providerModel;
    protected EventProviderModel $eventProviderModel;
    protected EventTaskModel $taskModel;

    public function __construct()
    {
        $this->eventModel         = new EventModel();
        $this->providerModel      = new ProviderModel();
        $this->eventProviderModel = new EventProviderModel();
        $this->taskModel          = new EventTaskModel();
    }

    public function index(): string
    {
        $status    = $this->request->getGet('status');
        $type      = $this->request->getGet('type');
        $search    = $this->request->getGet('search');
        $sort      = $this->request->getGet('sort') ?? 'event_date';
        $direction = $this->request->getGet('dir') ?? 'ASC';

        $events = $this->eventModel->getFilteredEvents($status, $type, $search, $sort, $direction);

        return view('events/index', [
            'title'     => 'All Events',
            'events'    => $events,
            'status'    => $status,
            'type'      => $type,
            'search'    => $search,
            'sort'      => $sort,
            'dir'       => $direction,
        ]);
    }

    public function new(): string
    {
        return view('events/form', [
            'title' => 'Create New Event',
            'event' => null,
        ]);
    }

    public function create()
    {
        $data = [
            'title'          => trim((string) $this->request->getPost('title')),
            'event_type'     => $this->request->getPost('event_type') ?: 'other',
            'client_name'    => trim((string) $this->request->getPost('client_name')),
            'client_email'   => trim((string) $this->request->getPost('client_email')) ?: null,
            'client_phone'   => trim((string) $this->request->getPost('client_phone')) ?: null,
            'event_date'     => $this->request->getPost('event_date'),
            'start_time'     => $this->request->getPost('start_time') ?: null,
            'end_time'       => $this->request->getPost('end_time') ?: null,
            'location_venue' => trim((string) $this->request->getPost('location_venue')) ?: null,
            'guest_count'    => (int) $this->request->getPost('guest_count'),
            'budget'         => (float) $this->request->getPost('budget'),
            'status'         => $this->request->getPost('status') ?: 'planning',
            'description'    => trim((string) $this->request->getPost('description')) ?: null,
            'notes'          => trim((string) $this->request->getPost('notes')) ?: null,
        ];

        if (!$this->eventModel->save($data)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->eventModel->errors());
        }

        $id = $this->eventModel->getInsertID();

        return redirect()->to(site_url("events/{$id}"))
                         ->with('success', 'Event created successfully! You can now assign providers and create tasks.');
    }

    public function show(int $id): string
    {
        $event = $this->eventModel->getEventWithStats($id);
        if (!$event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Event #{$id} not found.");
        }

        $assignedProviders = $this->eventProviderModel->getByEvent($id);
        $allProviders      = $this->providerModel->getWithCategory();
        $tasks             = $this->taskModel->getByEvent($id);

        return view('events/show', [
            'title'             => $event['title'],
            'event'             => $event,
            'assignedProviders' => $assignedProviders,
            'allProviders'      => $allProviders,
            'tasks'             => $tasks,
        ]);
    }

    public function edit(int $id): string
    {
        $event = $this->eventModel->find($id);
        if (!$event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Event #{$id} not found.");
        }

        return view('events/form', [
            'title' => 'Edit Event: ' . $event['title'],
            'event' => $event,
        ]);
    }

    public function update(int $id)
    {
        $event = $this->eventModel->find($id);
        if (!$event) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Event #{$id} not found.");
        }

        $data = [
            'id'             => $id,
            'title'          => trim((string) $this->request->getPost('title')),
            'event_type'     => $this->request->getPost('event_type') ?: 'other',
            'client_name'    => trim((string) $this->request->getPost('client_name')),
            'client_email'   => trim((string) $this->request->getPost('client_email')) ?: null,
            'client_phone'   => trim((string) $this->request->getPost('client_phone')) ?: null,
            'event_date'     => $this->request->getPost('event_date'),
            'start_time'     => $this->request->getPost('start_time') ?: null,
            'end_time'       => $this->request->getPost('end_time') ?: null,
            'location_venue' => trim((string) $this->request->getPost('location_venue')) ?: null,
            'guest_count'    => (int) $this->request->getPost('guest_count'),
            'budget'         => (float) $this->request->getPost('budget'),
            'status'         => $this->request->getPost('status') ?: 'planning',
            'description'    => trim((string) $this->request->getPost('description')) ?: null,
            'notes'          => trim((string) $this->request->getPost('notes')) ?: null,
        ];

        if (!$this->eventModel->save($data)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->eventModel->errors());
        }

        return redirect()->to(site_url("events/{$id}"))
                         ->with('success', 'Event details updated successfully.');
    }

    public function delete(int $id)
    {
        $event = $this->eventModel->find($id);
        if (!$event) {
            return redirect()->to(site_url('events'))->with('error', 'Event not found.');
        }

        $this->eventModel->delete($id);

        return redirect()->to(site_url('events'))
                         ->with('success', "Event '{$event['title']}' deleted successfully.");
    }

    public function addProvider(int $eventId)
    {
        $event = $this->eventModel->find($eventId);
        if (!$event) {
            return redirect()->to(site_url('events'))->with('error', 'Event not found.');
        }

        $providerId = (int) $this->request->getPost('provider_id');
        if (!$providerId) {
            return redirect()->to(site_url("events/{$eventId}"))->with('error', 'Please select a provider.');
        }

        $data = [
            'event_id'            => $eventId,
            'provider_id'         => $providerId,
            'service_description' => trim((string) $this->request->getPost('service_description')) ?: null,
            'agreed_cost'         => (float) $this->request->getPost('agreed_cost'),
            'deposit_paid'        => (float) $this->request->getPost('deposit_paid'),
            'payment_status'      => $this->request->getPost('payment_status') ?: 'pending',
            'contract_status'     => $this->request->getPost('contract_status') ?: 'draft',
            'notes'               => trim((string) $this->request->getPost('notes')) ?: null,
        ];

        if (!$this->eventProviderModel->save($data)) {
            return redirect()->to(site_url("events/{$eventId}"))
                             ->with('error', 'Failed to assign provider: ' . implode(', ', $this->eventProviderModel->errors()));
        }

        return redirect()->to(site_url("events/{$eventId}"))
                         ->with('success', 'Provider assigned to event successfully.');
    }

    public function updateProvider(int $eventId, int $eventProviderId)
    {
        $ep = $this->eventProviderModel->where('id', $eventProviderId)->where('event_id', $eventId)->first();
        if (!$ep) {
            return redirect()->to(site_url("events/{$eventId}"))->with('error', 'Provider assignment not found.');
        }

        $data = [
            'id'                  => $eventProviderId,
            'service_description' => trim((string) $this->request->getPost('service_description')) ?: null,
            'agreed_cost'         => (float) $this->request->getPost('agreed_cost'),
            'deposit_paid'        => (float) $this->request->getPost('deposit_paid'),
            'payment_status'      => $this->request->getPost('payment_status') ?: 'pending',
            'contract_status'     => $this->request->getPost('contract_status') ?: 'draft',
            'notes'               => trim((string) $this->request->getPost('notes')) ?: null,
        ];

        $this->eventProviderModel->save($data);

        return redirect()->to(site_url("events/{$eventId}"))
                         ->with('success', 'Provider assignment updated successfully.');
    }

    public function removeProvider(int $eventId, int $eventProviderId)
    {
        $ep = $this->eventProviderModel->where('id', $eventProviderId)->where('event_id', $eventId)->first();
        if ($ep) {
            $this->eventProviderModel->delete($eventProviderId);
        }

        return redirect()->to(site_url("events/{$eventId}"))
                         ->with('success', 'Provider removed from event.');
    }

    public function addTask(int $eventId)
    {
        $title = trim((string) $this->request->getPost('title'));
        if (empty($title)) {
            return redirect()->to(site_url("events/{$eventId}"))->with('error', 'Task title is required.');
        }

        $data = [
            'event_id'    => $eventId,
            'provider_id' => $this->request->getPost('provider_id') ?: null,
            'title'       => $title,
            'due_date'    => $this->request->getPost('due_date') ?: null,
            'priority'    => $this->request->getPost('priority') ?: 'medium',
            'status'      => $this->request->getPost('status') ?: 'todo',
        ];

        $this->taskModel->save($data);

        return redirect()->to(site_url("events/{$eventId}"))
                         ->with('success', 'Task added to checklist.');
    }

    public function toggleTask(int $taskId)
    {
        $task = $this->taskModel->find($taskId);
        if (!$task) {
            if ($this->request->isAJAX()) {
                return $this->response->setJSON(['status' => 'error', 'message' => 'Task not found']);
            }
            return redirect()->back()->with('error', 'Task not found.');
        }

        $newStatus = $task['status'] === 'completed' ? 'todo' : 'completed';
        $this->taskModel->update($taskId, ['status' => $newStatus]);

        if ($this->request->isAJAX()) {
            return $this->response->setJSON([
                'status'    => 'success',
                'newStatus' => $newStatus,
            ]);
        }

        return redirect()->back()->with('success', 'Task status updated.');
    }

    public function deleteTask(int $eventId, int $taskId)
    {
        $this->taskModel->where('id', $taskId)->where('event_id', $eventId)->delete();
        return redirect()->to(site_url("events/{$eventId}"))
                         ->with('success', 'Task deleted.');
    }
}
