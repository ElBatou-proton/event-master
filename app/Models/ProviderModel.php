<?php

namespace App\Models;

use CodeIgniter\Model;

class ProviderModel extends Model
{
    protected $table            = 'providers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'category_id',
        'name',
        'contact_person',
        'email',
        'phone',
        'website',
        'address',
        'city',
        'price_range',
        'rating',
        'status',
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
        'name'        => 'required|min_length[2]|max_length[150]',
        'category_id' => 'permit_empty|is_natural_no_zero',
        'email'       => 'permit_empty|valid_email|max_length[100]',
        'phone'       => 'permit_empty|max_length[50]',
        'rating'      => 'permit_empty|in_list[1,2,3,4,5]',
        'status'      => 'permit_empty|in_list[active,preferred,inactive]',
        'price_range' => 'permit_empty|in_list[$,$$,$$$,$$$$]',
    ];

    public function getWithCategory(?int $id = null)
    {
        $builder = $this->select('providers.*, categories.name as category_name, categories.icon as category_icon, categories.slug as category_slug')
                        ->join('categories', 'categories.id = providers.category_id', 'left');

        if ($id !== null) {
            return $builder->where('providers.id', $id)->first();
        }

        return $builder->orderBy('providers.name', 'ASC')->findAll();
    }

    public function getFiltered(?int $categoryId = null, ?string $status = null, ?string $search = null): array
    {
        $builder = $this->select('providers.*, categories.name as category_name, categories.icon as category_icon')
                        ->join('categories', 'categories.id = providers.category_id', 'left');

        if (!empty($categoryId)) {
            $builder->where('providers.category_id', $categoryId);
        }

        if (!empty($status)) {
            $builder->where('providers.status', $status);
        }

        if (!empty($search)) {
            $builder->groupStart()
                    ->like('providers.name', $search)
                    ->orLike('providers.contact_person', $search)
                    ->orLike('providers.city', $search)
                    ->orLike('providers.email', $search)
                    ->groupEnd();
        }

        return $builder->orderBy('providers.name', 'ASC')->findAll();
    }

    public function getEventsForProvider(int $providerId): array
    {
        return $this->db->table('event_providers')
                        ->select('event_providers.*, events.title as event_title, events.event_type, events.event_date, events.status as event_status, events.client_name')
                        ->join('events', 'events.id = event_providers.event_id')
                        ->where('event_providers.provider_id', $providerId)
                        ->orderBy('events.event_date', 'DESC')
                        ->get()
                        ->getResultArray();
    }
}
