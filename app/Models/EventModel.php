<?php

namespace App\Models;

use CodeIgniter\Model;

class EventModel extends Model
{
    protected $table            = 'events';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title',
        'event_type',
        'client_name',
        'client_email',
        'client_phone',
        'event_date',
        'start_time',
        'end_time',
        'location_venue',
        'guest_count',
        'budget',
        'status',
        'description',
        'notes',
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'title'        => 'required|min_length[3]|max_length[200]',
        'client_name'  => 'required|min_length[2]|max_length[150]',
        'event_date'   => 'required|valid_date',
        'client_email' => 'permit_empty|valid_email|max_length[100]',
        'client_phone' => 'permit_empty|max_length[50]',
        'budget'       => 'permit_empty|numeric',
        'guest_count'  => 'permit_empty|is_natural',
        'status'       => 'permit_empty|in_list[draft,planning,confirmed,in_progress,completed,cancelled]',
        'event_type'   => 'permit_empty|in_list[wedding,corporate,birthday,conference,concert,party,anniversary,other]',
    ];

    public function getFilteredEvents(?string $status = null, ?string $type = null, ?string $search = null, string $sort = 'event_date', string $direction = 'ASC'): array
    {
        $builder = $this->builder();

        if (!empty($status)) {
            $builder->where('status', $status);
        }

        if (!empty($type)) {
            $builder->where('event_type', $type);
        }

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('title', $search)
                    ->orLike('client_name', $search)
                    ->orLike('location_venue', $search)
                    ->groupEnd();
        }

        $validSorts = ['event_date', 'title', 'budget', 'created_at', 'status'];
        $sortCol = in_array($sort, $validSorts, true) ? $sort : 'event_date';
        $sortDir = strtoupper($direction) === 'DESC' ? 'DESC' : 'ASC';

        $events = $builder->orderBy($sortCol, $sortDir)->get()->getResultArray();

        // Attach lightweight provider cost summary to each event
        foreach ($events as &$evt) {
            $costRow = $this->db->table('event_providers')
                                ->select('SUM(agreed_cost) as total_cost, COUNT(id) as provider_count')
                                ->where('event_id', $evt['id'])
                                ->get()
                                ->getRowArray();

            $evt['total_provider_cost'] = (float) ($costRow['total_cost'] ?? 0);
            $evt['provider_count']      = (int) ($costRow['provider_count'] ?? 0);
        }

        return $events;
    }

    public function getEventWithStats(int $id): ?array
    {
        $event = $this->find($id);
        if (!$event) {
            return null;
        }

        $costRow = $this->db->table('event_providers')
                            ->select('SUM(agreed_cost) as total_cost, SUM(deposit_paid) as total_deposit, COUNT(id) as provider_count')
                            ->where('event_id', $id)
                            ->get()
                            ->getRowArray();

        $event['total_provider_cost'] = (float) ($costRow['total_cost'] ?? 0);
        $event['total_deposit_paid']  = (float) ($costRow['total_deposit'] ?? 0);
        $event['remaining_balance']   = $event['total_provider_cost'] - $event['total_deposit_paid'];
        $event['provider_count']      = (int) ($costRow['provider_count'] ?? 0);
        $event['budget_variance']     = (float) $event['budget'] - $event['total_provider_cost'];

        $taskRow = $this->db->table('event_tasks')
                            ->select('COUNT(id) as total_tasks, SUM(CASE WHEN status = "completed" THEN 1 ELSE 0 END) as completed_tasks')
                            ->where('event_id', $id)
                            ->get()
                            ->getRowArray();

        $event['total_tasks']     = (int) ($taskRow['total_tasks'] ?? 0);
        $event['completed_tasks'] = (int) ($taskRow['completed_tasks'] ?? 0);
        $event['task_percentage'] = $event['total_tasks'] > 0
            ? round(($event['completed_tasks'] / $event['total_tasks']) * 100)
            : 0;

        return $event;
    }

    public function getUpcomingEvents(int $limit = 5): array
    {
        $today = date('Y-m-d');
        return $this->where('event_date >=', $today)
                    ->where('status !=', 'cancelled')
                    ->orderBy('event_date', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }

    public function getOverallStats(): array
    {
        $today = date('Y-m-d');

        $totalEvents     = $this->countAllResults(false);
        $upcomingEvents  = $this->where('event_date >=', $today)->where('status !=', 'cancelled')->countAllResults(false);
        $completedEvents = $this->where('status', 'completed')->countAllResults(false);

        $budgetRow = $this->select('SUM(budget) as total_budget')->get()->getRowArray();
        $totalBudget = (float) ($budgetRow['total_budget'] ?? 0);

        $costsRow = $this->db->table('event_providers')
                             ->select('SUM(agreed_cost) as total_committed, SUM(deposit_paid) as total_paid')
                             ->get()
                             ->getRowArray();
        $totalCommitted = (float) ($costsRow['total_committed'] ?? 0);
        $totalPaid      = (float) ($costsRow['total_paid'] ?? 0);

        $totalProviders = $this->db->table('providers')->countAllResults();

        $pendingPayments = $this->db->table('event_providers')
                                    ->where('payment_status !=', 'paid')
                                    ->countAllResults();

        return [
            'total_events'      => $totalEvents,
            'upcoming_events'   => $upcomingEvents,
            'completed_events'  => $completedEvents,
            'total_budget'      => $totalBudget,
            'total_committed'   => $totalCommitted,
            'total_paid'        => $totalPaid,
            'total_providers'   => $totalProviders,
            'pending_payments'  => $pendingPayments,
        ];
    }
}
