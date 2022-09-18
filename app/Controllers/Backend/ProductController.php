<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\Attribute;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Images;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use ReflectionException;

class ProductController extends BaseController
{
    protected Product $product;
    protected Images $images;
    protected Category $category;
    protected Brand $brand;
    protected Attribute $attribute;

    public function __construct()
    {
        $this->product = new Product();
        $this->images = new Images();
        $this->category = new Category();
        $this->brand = new Brand();
        $this->attribute = new Attribute();
    }

    public function index(): string
    {
        $data['getCategoryList'] = $this->getCategoryList();
        $data['getBrandList'] = $this->getBrandList();
        return view('backend/product/index', $data);
    }

    public function getList(): ResponseInterface
    {
        $input = $this->request->getGet();
        $results = $this->product->getList($input);

        return $this->getListProduct($results);
    }

    public function recycle(): string
    {
        $data['getCategoryList'] = $this->getCategoryList();
        $data['getBrandList'] = $this->getBrandList();
        $data['recyclePage'] = true;
        return view('backend/product/index', $data);
    }

    public function getListRecycle(): ResponseInterface
    {
        $input = $this->request->getGet();
        $results = $this->product->getListRecycle($input);

        return $this->getListProduct($results);
    }

    public function create(): string
    {
        $data['routePost'] = route_to('admin.product.store');
        $data['getCategoryList'] = $this->getCategoryList();
        $data['getBrandList'] = $this->getBrandList();
        return view('backend/product/create_edit', $data);
    }

    /**
     * @throws ReflectionException
     */
    public function store(): RedirectResponse
    {
        $input = $this->request->getPost();

        if (!$this->product->insert($input)) {
            return redirectMessage('admin.product.index', 'error', MESSAGE_ERROR);
        }

        $file = $this->request->getFile('image');
        $getID = $this->product->getInsertID();
        $imageURL = PATH_IMAGE_DEFAULT;

        if ($file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $imageURL = $this->serviceUploadImage($getID, $file);
            }
        }

        if (!$this->images->insert([
            'url' => $imageURL,
            'relation_id' => $getID,
            'image_type' => MODULE_PRODUCT
        ])) {
            return redirectMessage('admin.product.index', 'error', MESSAGE_ERROR);
        }

        return redirectMessage('admin.product.index', 'success', "Sản phẩm <strong class='text-capitalize'>" . esc($input['name']) . "</strong> đã được thêm.");
    }

    public function edit($id): string
    {
        $data['row'] = $this->product->getProductByID($id);
        $data['routePost'] = route_to('admin.product.update', $id);
        $data['getCategoryList'] = $this->getCategoryList();
        $data['getBrandList'] = $this->getBrandList();
        return view('backend/product/create_edit', $data);
    }

    /**
     * @throws ReflectionException
     */
    public function update($id): RedirectResponse
    {
        $input = $this->request->getPost();
        $input['id'] = $id;

        $file = $this->request->getFile('image');

        if ($file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $this->images->set('url', $this->serviceUploadImage($id, $file));
                $this->images->where('relation_id', $id);
                $this->images->where('image_type', MODULE_PRODUCT);

                if (!$this->images->update()) {
                    return redirectMessage('admin.product.index', 'error', MESSAGE_ERROR);
                }

                deleteImage($input['imageRoot']);
            }
        }

        if ($this->product->update($id, $input)) {
            return redirectMessage('admin.product.index', 'success', "Sản phẩm <strong class='text-capitalize'>" . esc($input['name']) . "</strong> đã được cập nhật.");
        }

        return redirectMessage('admin.product.index', 'error', MESSAGE_ERROR);
    }

    /**
     * @throws ReflectionException
     */
    public function multiStatus(): ResponseInterface
    {
        $input = $this->request->getPost('data');
        $status = $this->request->getPost('status');
        $type = $this->request->getPost('type');
        parse_str($input, $result);

        if (isset($result['chk']) && is_array($result['chk'])) {
            if ($this->product->update($result['chk'], [$type => $status === 'NULL' ? NULL : $status])) {
                $data['result'] = true;
                $data['message'] = '<span class="text-capitalize">Cập nhật thành công tất cả dữ liệu được chọn.</span>';
                return $this->response->setJSON($data);
            }
        }

        $data['result'] = false;
        return $this->response->setJSON($data);
    }

    public function multiDelete(): ResponseInterface
    {
        $input = $this->request->getPost('data');
        $purge = filter_var($this->request->getPost('purge'), FILTER_VALIDATE_BOOLEAN);
        parse_str($input, $result);

        if (isset($result['chk']) && is_array($result['chk'])) {
            if ($purge) {
                $getImagesMultiple = $this->images->getImagesMultiple($result['chk'], MODULE_PRODUCT);
                deleteMultipleImage(PATH_product_IMAGE, $getImagesMultiple);
            }

            if ($this->product->delete($result['chk'], $purge)) {
                $data['result'] = true;
                $data['message'] = '<span class="text-capitalize">Xóa thành công dữ liệu được chọn.</span>';
                return $this->response->setJSON($data);
            }
        }

        $data['result'] = false;
        return $this->response->setJSON($data);
    }

    public function productExistSlug(): ResponseInterface
    {
        $input = $this->request->getPost();
        $result = $this->product->select('id')
            ->where('id !=', $input['id'])
            ->where('slug', $input['slug'])
            ->countAllResults();

        $isValid = !($result > 0);

        return $this->response->setJSON([
            'valid' => var_export($isValid, 1)
        ]);
    }

    public function attributeLoadList(): string
    {
        $input = $this->request->getPost();
        $result = $this->attribute->select('id, name')
            ->where('category_id', $input['data'])
            ->where('status', STATUS_ACTIVE)
            ->findAll();

        $option = ['' => '[-- Chọn Thuộc Tính --]'];

        foreach ($result as $item) {
            $option[$item->id] = esc($item->name);
        }

        return form_dropdown(
            'attribute_id',
            $option ?? [],
            '',
            [
                'class' => 'bootstrap-select text-capitalize w-100',
                'data-style' => 'btn-default text-capitalize',
                'data-size' => 8,
                'id' => 'attribute_id'
            ]);
    }

    private function serviceUploadImage($id, UploadedFile $file): string
    {
        $path = PATH_PRODUCT_IMAGE . $id . '/';

        $fileName = $file->getRandomName();
        $file->move($path, $fileName);

        $savePath = $path . convertImageWebp($fileName);
        $data = [
            'path' => $path,
            'fileName' => $fileName,
            'savePath' => $savePath,
            'resize' => [
                'resizeX' => '200',
                'resizeY' => '200',
            ]
        ];

        imageManipulation($data);
        return $savePath;
    }

    private function getCategoryList(): array
    {
        $getCategoryList = $this->category->getCategoryList();
        $option = [
            '' => '[-- Chọn Danh Mục --]'
        ];

        foreach ($getCategoryList as $item) {
            $option[$item->id] = esc($item->name);
        }

        return $option;
    }

    private function getBrandList(): array
    {
        $getBrandList = $this->brand->getBrandList();
        $option = [
            '' => '[-- Chọn Thương Hiệu --]'
        ];

        foreach ($getBrandList as $item) {
            $option[$item->id] = esc($item->name);
        }

        return $option;
    }

    /**
     * @param array $results
     * @return ResponseInterface
     */
    private function getListProduct(array $results): ResponseInterface
    {
        $data = array();
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $results['total'];
        $data['aaData'] = array();

        if (count($results['model']) > 0) {
            foreach ($results['model'] as $item) {
                $data['aaData'][] = [
                    'checkbox' => '',
                    'responsive_id' => esc($item->id),
                    'image' => img($item->image ?? PATH_IMAGE_DEFAULT, false, [
                        'alt' => esc($item->name),
                        'title' => esc($item->name),
                        'width' => 100,
                        'height' => 100
                    ]),
                    'name' => esc($item->name),
                    'status' => esc($item->status),
                    'created_at' => esc($item->created_at->format(FORMAT_DATE)),
                    'updated_at' => esc($item->updated_at->format(FORMAT_DATE)),
                    'edit_pages' => route_to('admin.product.edit', esc($item->id))
                ];
            }
        }

        return $this->response->setJSON($data);
    }
}
