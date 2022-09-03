<?php

namespace App\Models;

use App\Entities\ImageEntity;
use CodeIgniter\Model;

class Images extends Model
{
    protected $table = 'images';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = ImageEntity::class;
    protected $protectFields = true;
    protected $allowedFields = [
        'url',
        'relation_id',
        'image_type'
    ];

    // Validation
    protected $validationRules = [];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getImagesMultiple($id, $image_type): array
    {
        return $this->select('relation_id')->whereIn('relation_id', $id)->where('image_type', $image_type)->findAll();
    }
}
