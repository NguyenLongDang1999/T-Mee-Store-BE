<?php

namespace App\Models;

use App\Entities\BrandEntity;
use CodeIgniter\Model;

class Brand extends Model
{
    protected $table = 'brand';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = BrandEntity::class;
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'slug',
        'description',
        'image',
        'status',
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
        'slug' => 'is_unique[brand.slug,id,{id}]',
        'description' => 'max_length[160]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getList($input = array()): array
    {
        $model = $this->select('id, name, image, status, created_at, updated_at');
        return $this->searchBrandList($input, $model);
    }

    public function getListRecycle($input = array()): array
    {
        $model = $this->select('id, name, image, status, created_at, updated_at')->onlyDeleted();
        return $this->searchBrandList($input, $model);
    }

    public function getBrandByID($id)
    {
        return $this->select('id, name, slug, description, status, image')
            ->where('id', $id)
            ->withDeleted()
            ->first();
    }

    public function getMultiImageBrand($id): array
    {
        return $this->select('id, image')->whereIn('id', $id)->withDeleted()->findAll();
    }

    /**
     * @param $input
     * @param Brand $model
     * @return array
     */
    private function searchBrandList($input, Brand $model): array
    {
        if (isset($input['search']['name']) && $input['search']['name'] != "") {
            $model->like('name', trim($input['search']['name']));
        }

        if (isset($input['search']['status']) && $input['search']['status'] != "") {
            $model->where('status', $input['search']['status']);
        }

        $result['total'] = $model->countAllResults(false);

        if (isset($input['iSortCol_0'])) {
            $sorting_mapping_array = array(
                '3' => 'name',
                '5' => 'created_at',
                '6' => 'updated_at',
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

    public function brandExistSlug($input)
    {
        return $this->select('id')->where('slug', $input)->countAllResults();
    }
}
