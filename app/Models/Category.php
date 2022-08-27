<?php

namespace App\Models;

use App\Entities\CategoryEntity;
use CodeIgniter\Model;

class Category extends Model
{
    protected $table = 'category';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = CategoryEntity::class;
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'slug',
        'parent_id',
        'description',
        'image',
        'status',
        'featured',
        'meta_title',
        'meta_keyword',
        'meta_description',
        'deleted_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    // Validation
    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[30]',
        'slug' => 'is_unique[category.slug,id,{id}]',
        'parent_id' => 'required',
        'description' => 'max_length[160]',
        'meta_title' => 'max_length[60]',
        'meta_keyword' => 'max_length[160]',
        'meta_description' => 'max_length[160]'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getList($input = array()): array
    {
        $model = $this->select('category.id, category.name, parent.name as parentName, category.image, category.status, category.featured, category.created_at, category.updated_at')
            ->join('category as parent', 'parent.id = category.parent_id', 'left');

        return $this->searchCategoryList($input, $model);
    }

    public function getListRecycle($input = array()): array
    {
        $model = $this->select('category.id, category.name, parent.name as parentName, category.image, category.status, category.featured, category.created_at, category.updated_at')
            ->onlyDeleted()
            ->join('category as parent', 'parent.id = category.parent_id', 'left');

        return $this->searchCategoryList($input, $model);
    }

    public function getCategoryList($parent_id = 0): array
    {
        return $this->select('id, name')->where('parent_id', $parent_id)->findAll();
    }

    public function getCategoryByID($id)
    {
        return $this->select('id, name, slug, parent_id, description, status, featured, image, meta_title, meta_keyword, meta_description')
            ->where('id', $id)
            ->withDeleted()
            ->first();
    }

    public function categoryCountParentID($id, $recycle = false)
    {
        $model = $this->select('id')->whereIn('parent_id', $id);

        if ($recycle) {
            $model->withDeleted();
        }

        return $model->countAllResults();
    }

    public function getMultiImageCategory($id): array
    {
        return $this->select('id, image')->whereIn('id', $id)->withDeleted()->findAll();
    }

    /**
     * @param $input
     * @param Category $model
     * @return array
     */
    private function searchCategoryList($input, Category $model): array
    {
        if (isset($input['search']['name']) && $input['search']['name'] != "") {
            $model->like('category.name', trim($input['search']['name']));
        }

        if (isset($input['search']['parent_id']) && $input['search']['parent_id'] != "") {
            $model->where('category.parent_id', $input['search']['parent_id']);
        }

        if (isset($input['search']['status']) && $input['search']['status'] != "") {
            $model->where('category.status', $input['search']['status']);
        }

        if (isset($input['search']['featured']) && $input['search']['featured'] != "") {
            $model->where('category.featured', $input['search']['featured']);
        }

        $result['total'] = $model->countAllResults(false);

        if (isset($input['iSortCol_0'])) {
            $sorting_mapping_array = array(
                '3' => 'category.name',
                '7' => 'category.created_at',
                '8' => 'category.updated_at',
            );

            $order = "desc";
            if (isset($input['sSortDir_0'])) {
                $order = $input['sSortDir_0'];
            }

            if (isset($sorting_mapping_array[$input['iSortCol_0']])) {
                $model->orderBy($sorting_mapping_array[$input['iSortCol_0']], $order);
            }
        }

        $result['model'] = $model->findAll($input['iDisplayLength'], $input['iDisplayStart']);

        return $result;
    }

    public function categoryExistSlug($input)
    {
        return $this->select('id')->where('slug', $input)->countAllResults();
    }
}
