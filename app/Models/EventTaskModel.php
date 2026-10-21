<?php

namespace App\Models;

use CodeIgniter\Model;

class EventTaskModel extends Model
{
    protected $table            = 'event_tasks';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'event_id',
        'provider_id',
        'title',
        'due_date',
        'priority',
        'status',
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
        'event_id'    => 'required|is_natural_no_zero',
        'provider_id' => 'permit_empty|is_natural_no_zero',
        'title'       => 'required|min_length[2]|max_length[200]',
        'due_date'    => 'permit_empty|valid_date',
        'priority'    => 'permit_empty|in_list[low,medium,high]',
        'status'      => 'permit_empty|in_list[todo,in_progress,completed]',
    ];

    public function getByEvent(int $eventId): array
    {
        return $this->select('event_tasks.*, providers.name as provider_name')
                    ->join('providers', 'providers.id = event_tasks.provider_id', 'left')
                    ->where('event_tasks.event_id', $eventId)
                    ->orderBy('event_tasks.due_date', 'ASC')
                    ->findAll();
    }

    public function getPendingTasks(int $limit = 6): array
    {
        return $this->select('event_tasks.*, events.title as event_title, events.event_date')
                    ->join('events', 'events.id = event_tasks.event_id')
                    ->where('event_tasks.status !=', 'completed')
                    ->orderBy('event_tasks.due_date', 'ASC')
                    ->limit($limit)
                    ->findAll();
    }
}
