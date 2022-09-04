<?php

namespace App\Models;

use App\Entities\AttributeEntity;
use CodeIgniter\Model;

class Attribute extends Model
{
    protected $table = 'attribute';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = AttributeEntity::class;
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'category_id',
        'description',
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
        'category_id' => 'required',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getList($input = array()): array
    {
        $model = $this->select('attribute.id, attribute.name, attribute.status, attribute.created_at, attribute.updated_at, category.name as categoryName')
            ->join('category', 'category.id = attribute.category_id', 'left');

        return $this->searchAttributeList($input, $model);
    }

    public function getListRecycle($input = array()): array
    {
        $model = $this->select('attribute.id, attribute.name, attribute.status, attribute.created_at, attribute.updated_at, category.name as categoryName')
            ->join('category', 'category.id = attribute.category_id', 'left')
            ->onlyDeleted();

        return $this->searchAttributeList($input, $model);
    }

    public function getAttributeByID($id)
    {
        return $this->select('attribute.id, attribute.name, attribute.description, attribute.category_id, attribute.status')
            ->where('attribute.id', $id)
            ->withDeleted()
            ->first();
    }

    /**
     * @param $input
     * @param Attribute $model
     * @return array
     */
    private function searchAttributeList($input, Attribute $model): array
    {
        if (isset($input['search']['name']) && $input['search']['name'] != "") {
            $model->like('attribute.name', trim($input['search']['name']));
        }

        if (isset($input['search']['category_id']) && $input['search']['category_id'] != "") {
            $model->where('category_id', $input['search']['category_id']);
        }

        if (isset($input['search']['status']) && $input['search']['status'] != "") {
            $model->where('status', $input['search']['status']);
        }

        $result['total'] = $model->countAllResults(false);

        if (isset($input['iSortCol_0'])) {
            $sorting_mapping_array = array(
                '2' => 'attribute.name',
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
}
