<?php

namespace App\Controllers;

use App\Models\CategoryModel;
use App\Models\ProviderModel;

class Categories extends BaseController
{
    protected CategoryModel $categoryModel;
    protected ProviderModel $providerModel;

    public function __construct()
    {
        $this->categoryModel = new CategoryModel();
        $this->providerModel = new ProviderModel();
    }

    public function index(): string
    {
        $categories = $this->categoryModel->getCategoriesWithProviderCount();

        return view('categories/index', [
            'title'      => lang('App.category_management'),
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $data = [
            'name'        => trim((string) $this->request->getPost('name')),
            'slug'        => trim((string) $this->request->getPost('slug')),
            'icon'        => trim((string) $this->request->getPost('icon')) ?: 'bi-tag',
            'description' => trim((string) $this->request->getPost('description')) ?: null,
        ];

        if (!$this->categoryModel->save($data)) {
            return redirect()->back()
                             ->withInput()
                             ->with('errors', $this->categoryModel->errors());
        }

        return redirect()->to(site_url('categories'))
                         ->with('success', lang('App.flash_category_created'));
    }

    public function delete(int $id)
    {
        $category = $this->categoryModel->find($id);
        if (!$category) {
            return redirect()->to(site_url('categories'))->with('error', 'Category not found.');
        }

        $providerCount = $this->providerModel->where('category_id', $id)->countAllResults();
        if ($providerCount > 0) {
            return redirect()->to(site_url('categories'))
                             ->with('error', lang('App.flash_category_has_providers'));
        }

        $this->categoryModel->delete($id);

        return redirect()->to(site_url('categories'))
                         ->with('success', lang('App.flash_category_deleted'));
    }
}
