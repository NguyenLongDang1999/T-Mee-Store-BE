<?php

namespace App\Models;

use App\Entities\ProductEntity;
use CodeIgniter\Model;

class Product extends Model
{
    protected $table = 'product';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = ProductEntity::class;
    protected $useSoftDeletes = true;
    protected $protectFields = true;
    protected $allowedFields = [
        'name',
        'slug',
        'description',
        'content',
        'category_id',
        'brand_id',
        'sku',
        'price',
        'discount',
        'quantity',
        'start_at',
        'end_at',
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
//        'name' => 'required|min_length[2]|max_length[30]',
//        'slug' => 'is_unique[product.slug,id,{id}]',
//        'parent_id' => 'required',
//        'description' => 'max_length[160]',
//        'meta_title' => 'max_length[60]',
//        'meta_keyword' => 'max_length[160]',
//        'meta_description' => 'max_length[160]'
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;
    protected $cleanValidationRules = true;

    public function getList($input = array()): array
    {
        $model = $this->select('product.id, product.name, images.url as image, category.name as categoryName, product.status, product.featured, product.created_at, product.updated_at')
            ->where('images.image_type', MODULE_PRODUCT)
            ->join('category', 'category.id = product.category_id', 'left')
            ->join('images', 'images.relation_id = product.id', 'left');

        return $this->searchProductList($input, $model);
    }

    /**
     * @param $input
     * @param Category $model
     * @return array
     */
    private function searchProductList($input, Category $model): array
    {
        if (isset($input['search']['name']) && $input['search']['name'] != "") {
            $model->like('product.name', trim($input['search']['name']));
        }

        if (isset($input['search']['category_id']) && $input['search']['category_id'] != "") {
            $model->where('product.category_id', $input['search']['category_id']);
        }

        if (isset($input['search']['brand_id']) && $input['search']['brand_id'] != "") {
            $model->where('product.brand_id', $input['search']['brand_id']);
        }

        if (isset($input['search']['status']) && $input['search']['status'] != "") {
            $model->where('product.status', $input['search']['status']);
        }

        if (isset($input['search']['featured']) && $input['search']['featured'] != "") {
            $model->where('product.featured', $input['search']['featured']);
        }

        $result['total'] = $model->countAllResults(false);

        if (isset($input['iSortCol_0'])) {
            $sorting_mapping_array = array(
                '3' => 'product.name',
                '7' => 'product.created_at',
                '8' => 'product.updated_at',
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
        $model = $this->select('product.id, product.name, images.url as image, parent.name as parentName, product.status, product.featured, product.created_at, product.updated_at')
            ->where('images.image_type', MODULE_PRODUCT)
            ->join('category as parent', 'parent.id = product.parent_id', 'left')
            ->join('images', 'images.relation_id = product.id', 'left')
            ->onlyDeleted();

        return $this->searchProductList($input, $model);
    }

    public function getProductByID($id)
    {
        return $this->select('product.id, product.name, product.slug, product.category_id, product.brand_id, product.description, 
            product.status, product.featured, product.meta_title, product.meta_keyword, product.meta_description, images.url as image,
            product.content, product.start_at, product.end_at, product.sku, product.price, product.quantity, product.discount')
            ->where('images.image_type', MODULE_PRODUCT)
            ->where('product.id', $id)
            ->join('images', 'images.relation_id = product.id', 'left')
            ->withDeleted()
            ->first();
    }
}
