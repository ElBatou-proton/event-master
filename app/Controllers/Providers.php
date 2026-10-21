<?php

namespace App\Controllers;

use App\Models\ProviderModel;
use App\Models\CategoryModel;

class Providers extends BaseController
{
    protected ProviderModel $providerModel;
    protected CategoryModel $categoryModel;

    public function __construct()
    {
        $this->providerModel = new ProviderModel();
        $this->categoryModel = new CategoryModel();
    }

    public function index(): string
    {
        $categoryId = $this->request->getGet('category_id') ? (int) $this->request->getGet('category_id') : null;
        $status     = $this->request->getGet('status');
        $search     = $this->request->getGet('search');

        $providers  = $this->providerModel->getFiltered($categoryId, $status, $search);
        $categories = $this->categoryModel->orderBy('name', 'ASC')->findAll();

        return view('providers/index', [
            'title'      => 'Service Providers Directory',
            'providers'  => $providers,
            'categories' => $categories,
            'categoryId' => $categoryId,
            'status'     => $status,
            'search'     => $search,
        ]);
    }

    public function new(): string
    {
        $categories = $this->categoryModel->orderBy('name', 'ASC')->findAll();

        return view('providers/form', [
            'title'      => 'Register New Provider',
            'provider'   => null,
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $data = [
            'category_id'    => $this->request->getPost('category_id') ?: null,
            'name'           => trim((string) $this->request->getPost('name')),
            'contact_person' => trim((string) $this->request->getPost('contact_person')) ?: null,
            'email'          => trim((string) $this->request->getPost('email')) ?: null,
            'phone'          => trim((string) $this->request->getPost('phone')) ?: null,
            'website'        => trim((string) $this->request->getPost('website')) ?: null,
            'address'        => trim((string) $this->request->getPost('address')) ?: null,
            'city'           => trim((string) $this->request->getPost('city')) ?: null,
            'price_range'    => $this->request->getPost('price_range') ?: '$$',
            'rating'         => (int) ($this->request->getPost('rating') ?: 5),
            'status'         => $this->request->getPost('status') ?: 'active',
            'notes'          => trim((string) $this->request->getPost('notes')) ?: null,
        ];

        if (!$this->providerModel->save($data)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->providerModel->errors());
        }

        $id = $this->providerModel->getInsertID();

        return redirect()->to(site_url("providers/{$id}"))
                         ->with('success', 'Provider profile registered successfully.');
    }

    public function show(int $id): string
    {
        $provider = $this->providerModel->getWithCategory($id);
        if (!$provider) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Provider #{$id} not found.");
        }

        $bookedEvents = $this->providerModel->getEventsForProvider($id);

        return view('providers/show', [
            'title'        => $provider['name'],
            'provider'     => $provider,
            'bookedEvents' => $bookedEvents,
        ]);
    }

    public function edit(int $id): string
    {
        $provider = $this->providerModel->find($id);
        if (!$provider) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Provider #{$id} not found.");
        }

        $categories = $this->categoryModel->orderBy('name', 'ASC')->findAll();

        return view('providers/form', [
            'title'      => 'Edit Provider: ' . $provider['name'],
            'provider'   => $provider,
            'categories' => $categories,
        ]);
    }

    public function update(int $id)
    {
        $provider = $this->providerModel->find($id);
        if (!$provider) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound("Provider #{$id} not found.");
        }

        $data = [
            'id'             => $id,
            'category_id'    => $this->request->getPost('category_id') ?: null,
            'name'           => trim((string) $this->request->getPost('name')),
            'contact_person' => trim((string) $this->request->getPost('contact_person')) ?: null,
            'email'          => trim((string) $this->request->getPost('email')) ?: null,
            'phone'          => trim((string) $this->request->getPost('phone')) ?: null,
            'website'        => trim((string) $this->request->getPost('website')) ?: null,
            'address'        => trim((string) $this->request->getPost('address')) ?: null,
            'city'           => trim((string) $this->request->getPost('city')) ?: null,
            'price_range'    => $this->request->getPost('price_range') ?: '$$',
            'rating'         => (int) ($this->request->getPost('rating') ?: 5),
            'status'         => $this->request->getPost('status') ?: 'active',
            'notes'          => trim((string) $this->request->getPost('notes')) ?: null,
        ];

        if (!$this->providerModel->save($data)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->providerModel->errors());
        }

        return redirect()->to(site_url("providers/{$id}"))
                         ->with('success', 'Provider profile updated successfully.');
    }

    public function delete(int $id)
    {
        $provider = $this->providerModel->find($id);
        if (!$provider) {
            return redirect()->to(site_url('providers'))->with('error', 'Provider not found.');
        }

        $this->providerModel->delete($id);

        return redirect()->to(site_url('providers'))
                         ->with('success', "Provider '{$provider['name']}' removed.");
    }
}
