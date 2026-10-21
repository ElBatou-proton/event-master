<?php

namespace App\Models;

use CodeIgniter\Model;

class EventProviderModel extends Model
{
    protected $table            = 'event_providers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'event_id',
        'provider_id',
        'service_description',
        'agreed_cost',
        'deposit_paid',
        'payment_status',
        'contract_status',
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
        'event_id'        => 'required|is_natural_no_zero',
        'provider_id'     => 'required|is_natural_no_zero',
        'agreed_cost'     => 'permit_empty|numeric',
        'deposit_paid'    => 'permit_empty|numeric',
        'payment_status'  => 'permit_empty|in_list[pending,partial,paid]',
        'contract_status' => 'permit_empty|in_list[draft,sent,signed,completed]',
    ];

    public function getByEvent(int $eventId): array
    {
        return $this->select('event_providers.*, providers.name as provider_name, providers.email as provider_email, providers.phone as provider_phone, providers.contact_person, categories.name as category_name, categories.icon as category_icon')
                    ->join('providers', 'providers.id = event_providers.provider_id')
                    ->join('categories', 'categories.id = providers.category_id', 'left')
                    ->where('event_providers.event_id', $eventId)
                    ->orderBy('categories.name', 'ASC')
                    ->findAll();
    }
}
