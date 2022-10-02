<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\Category;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use ReflectionException;

class CategoryController extends BaseController
{
    protected Category $category;

    public function __construct()
    {
        $this->category = new Category();
    }

    public function index(): string
    {
        $data['getCategoryList'] = $this->getCategoryList();
        return view('backend/category/index', $data);
    }

    private function getCategoryList(): array
    {
        $getCategoryList = $this->category->getCategoryList();
        $option = [
            '' => '[-- Vui Lòng Chọn --]',
            '0' => 'Danh mục cha'
        ];

        foreach ($getCategoryList as $item) {
            $option[$item->id] = esc($item->name);
        }

        return $option;
    }

    public function getList(): ResponseInterface
    {
        $input = $this->request->getGet();
        $results = $this->category->getList($input);

        return $this->getListCategory($results);
    }

    /**
     * @param array $results
     * @return ResponseInterface
     */
    private function getListCategory(array $results): ResponseInterface
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
                    'parent_id' => esc($item->parentName ?? '-'),
                    'status' => esc($item->status),
                    'featured' => esc($item->featured),
                    'created_at' => esc($item->created_at->format(FORMAT_DATE)),
                    'updated_at' => esc($item->updated_at->format(FORMAT_DATE)),
                    'edit_pages' => route_to('admin.category.edit', esc($item->id))
                ];
            }
        }

        return $this->response->setJSON($data);
    }

    public function recycle(): string
    {
        $data['getCategoryList'] = $this->getCategoryList();
        $data['recyclePage'] = true;
        return view('backend/category/index', $data);
    }

    public function getListRecycle(): ResponseInterface
    {
        $input = $this->request->getGet();
        $results = $this->category->getListRecycle($input);

        return $this->getListCategory($results);
    }

    public function create(): string
    {
        $data['getCategoryList'] = $this->getCategoryList();
        $data['routePost'] = route_to('admin.category.store');
        return view('backend/category/create_edit', $data);
    }

    /**
     * @throws ReflectionException
     */
    public function store(): RedirectResponse
    {
        $input = $this->request->getPost();

        if (!$this->category->insert($input)) {
            return redirectMessage('admin.category.index', 'error', MESSAGE_ERROR);
        }

        $file = $this->request->getFile('image');
        $getID = $this->category->getInsertID();

        if ($file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $imageURL = $this->serviceUploadImage($getID, $file);
            }
        }

        if (!$this->category->update($getID, [
            'image_uri' => $imageURL ?? NULL
        ])) {
            return redirectMessage('admin.category.index', 'error', MESSAGE_ERROR);
        }

        return redirectMessage('admin.category.index', 'success', "Danh mục <strong class='text-capitalize'>" . esc($input['name']) . "</strong> đã được thêm.");
    }

    private function serviceUploadImage($id, UploadedFile $file): string
    {
        $path = PATH_CATEGORY_IMAGE . $id . '/';

        $fileName = $file->getRandomName();
        $file->move($path, $fileName);

        $savePath = $path . convertImageWebp($fileName);
        $data = [
            'path' => $path,
            'fileName' => $fileName,
            'savePath' => $savePath,
            'resize' => [
                'resizeX' => '200',
                'resizeY' => '200'
            ]
        ];

        imageManipulation($data);
        return $savePath;
    }

    /**
     * @throws ReflectionException
     */
    public function update($id): RedirectResponse
    {
        $input = $this->request->getPost();
        $file = $this->request->getFile('image');

        if ($file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $input['image_uri'] = $this->serviceUploadImage($id, $file);
                deleteImage($input['imageRoot']);
            }
        }

        if (!$this->category->update($id, $input)) {
            return redirectMessage('admin.category.index', 'error', MESSAGE_ERROR);
        }

        return redirectMessage('admin.category.index', 'success', "Danh mục <strong class='text-capitalize'>" . esc($input['name']) . "</strong> đã được cập nhật.");
    }

    public function edit($id): string
    {
        $data['row'] = $this->category->getCategoryByID($id);
        $data['getCategoryList'] = $this->getCategoryList();
        $data['routePost'] = route_to('admin.category.update', $id);
        return view('backend/category/create_edit', $data);
    }

    /**
     * @throws ReflectionException
     */
    public function multiStatus(): ResponseInterface
    {
        $input = $this->request->getPost('data');
        $status = $this->request->getPost('status');
        $type = $this->request->getPost('type');
        $recycle = filter_var($this->request->getPost('recycle'), FILTER_VALIDATE_BOOLEAN);
        parse_str($input, $result);

        if (isset($result['chk']) && is_array($result['chk'])) {
            if ($this->category->categoryCountParentID($result['chk'], $recycle) == 0) {
                if ($this->category->update($result['chk'], [$type => $status === 'NULL' ? NULL : $status])) {
                    $data['result'] = true;
                    $data['message'] = '<span class="text-capitalize">Cập nhật thành công tất cả dữ liệu được chọn.</span>';
                    return $this->response->setJSON($data);
                }
            } else {
                $data['result'] = false;
                $data['message'] = '<span class="text-capitalize">Danh mục có thể vẫn có danh mục con bên trong. Vui lòng kiểm tra lại.</span>';
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
            if ($this->category->categoryCountParentID($result['chk']) == 0) {
                if ($purge) {
                    $getImagesMultiple = $this->category->select('id')->whereIn('id', $result['chk'])->withDeleted()->findAll();
                    deleteMultipleImage(PATH_CATEGORY_IMAGE, $getImagesMultiple);
                }

                if ($this->category->delete($result['chk'], $purge)) {
                    $data['result'] = true;
                    $data['message'] = '<span class="text-capitalize">Xóa thành công dữ liệu được chọn.</span>';
                    return $this->response->setJSON($data);
                }
            } else {
                $data['result'] = false;
                $data['message'] = '<span class="text-capitalize">Danh mục có thể vẫn có danh mục con bên trong. Vui lòng kiểm tra lại.</span>';
                return $this->response->setJSON($data);
            }
        }

        $data['result'] = false;
        return $this->response->setJSON($data);
    }

    public function validateExistSlug(): ResponseInterface
    {
        $input = $this->request->getPost();
        $result = $this->category->select('id')
            ->where('id !=', $input['id'])
            ->where('slug', $input['slug'])
            ->countAllResults();

        $isValid = !($result > 0);

        return $this->response->setJSON([
            'valid' => var_export($isValid, 1)
        ]);
    }
}
