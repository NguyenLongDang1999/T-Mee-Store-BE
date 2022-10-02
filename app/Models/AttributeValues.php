<?php

namespace App\Models;

use App\Entities\AttributeValuesEntity;
use CodeIgniter\Model;

class AttributeValues extends Model
{
    protected $table = 'attribute_values';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = AttributeValuesEntity::class;
    protected $useSoftDeletes = false;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'attribute_id',
        'status'
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
        'attribute_id' => 'required',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getAttributeValueList($attribute_id) {
        return $this->select('id, name')->where('attribute_id', $attribute_id)->findAll();
    }
}
