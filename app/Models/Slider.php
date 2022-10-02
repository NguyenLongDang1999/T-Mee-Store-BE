<?php

namespace App\Models;

use App\Entities\SliderEntity;
use CodeIgniter\Model;

class Slider extends Model
{
    protected $table = 'slider';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = SliderEntity::class;
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'url',
        'sort',
        'description',
        'image_uri',
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
        'url' => 'is_unique[slider.url,id,{id}]',
        'description' => 'max_length[160]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getList($input = array()): array
    {
        $model = $this->select('slider.id, slider.name, slider.sort, slider.status, slider.created_at, slider.updated_at, slider.image_uri as image');

        return $this->searchSliderList($input, $model);
    }

    /**
     * @param $input
     * @param Slider $model
     * @return array
     */
    private function searchSliderList($input, Slider $model): array
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
                '4' => 'sort',
                '6' => 'created_at',
                '7' => 'updated_at',
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

    public function getListRecycle($input = array()): array
    {
        $model = $this->select('slider.id, slider.name, slider.sort, slider.status, slider.created_at, slider.updated_at, slider.image_uri as image')->onlyDeleted();

        return $this->searchSliderList($input, $model);
    }

    public function getSliderByID($id)
    {
        return $this->select('slider.id, slider.name, slider.url, slider.sort, slider.description, slider.status, slider.image_uri as image')
            ->where('slider.id', $id)
            ->withDeleted()
            ->first();
    }
}
