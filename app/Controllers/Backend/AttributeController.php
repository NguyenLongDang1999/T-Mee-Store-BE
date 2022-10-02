<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\Attribute;
use App\Models\Category;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use ReflectionException;

class AttributeController extends BaseController
{
    protected Attribute $attribute;
    protected Category $category;

    public function __construct()
    {
        $this->attribute = new Attribute();
        $this->category = new Category();
    }

    public function index(): string
    {
        $data['getCategoryList'] = $this->getCategoryList();
        return view('backend/attribute/index', $data);
    }

    public function getList(): ResponseInterface
    {
        $input = $this->request->getGet();
        $results = $this->attribute->getList($input);

        return $this->getListAttribute($results);
    }

    public function recycle(): string
    {
        $data['recyclePage'] = true;
        $data['getCategoryList'] = $this->getCategoryList();
        return view('backend/attribute/index', $data);
    }

    public function getListRecycle(): ResponseInterface
    {
        $input = $this->request->getGet();
        $results = $this->attribute->getListRecycle($input);

        return $this->getListAttribute($results);
    }

    public function create(): string
    {
        $data['routePost'] = route_to('admin.attribute.store');
        $data['getCategoryList'] = $this->getCategoryList();
        return view('backend/attribute/create_edit', $data);
    }

    /**
     * @throws ReflectionException
     */
    public function store(): RedirectResponse
    {
        $input = $this->request->getPost();

        if (!$this->attribute->insert($input)) {
            return redirectMessage('admin.attribute.index', 'error', MESSAGE_ERROR);
        }

        return redirectMessage('admin.attribute.index', 'success', "Thuộc tính <strong class='text-capitalize'>" . esc($input['name']) . "</strong> đã được thêm.");
    }

    public function edit($id): string
    {
        $data['row'] = $this->attribute->getAttributeByID($id);
        $data['routePost'] = route_to('admin.attribute.update', $id);
        $data['getCategoryList'] = $this->getCategoryList();
        return view('backend/attribute/create_edit', $data);
    }

    /**
     * @throws ReflectionException
     */
    public function update($id): RedirectResponse
    {
        $input = $this->request->getPost();
        dd($input);

        if ($this->attribute->update($id, $input)) {
            return redirectMessage('admin.attribute.index', 'success', "Thuộc tính <strong class='text-capitalize'>" . esc($input['name']) . "</strong> đã được cập nhật.");
        }

        return redirectMessage('admin.attribute.index', 'error', MESSAGE_ERROR);
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
            if ($this->attribute->update($result['chk'], [$type => $status === 'NULL' ? NULL : $status])) {
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
            if ($this->attribute->delete($result['chk'], $purge)) {
                $data['result'] = true;
                $data['message'] = '<span class="text-capitalize">Xóa thành công dữ liệu được chọn.</span>';
                return $this->response->setJSON($data);
            }
        }

        $data['result'] = false;
        return $this->response->setJSON($data);
    }

    private function getCategoryList(): array
    {
        $getCategoryList = $this->category->getCategoryList();
        $option = [
            '' => '[-- Chọn danh mục --]'
        ];

        foreach ($getCategoryList as $item) {
            $option[$item->id] = esc($item->name);
        }

        return $option;
    }

    /**
     * @param array $results
     * @return ResponseInterface
     */
    private function getListAttribute(array $results): ResponseInterface
    {
        $data = array();
        $data['iTotalRecords'] = $data['iTotalDisplayRecords'] = $results['total'];
        $data['aaData'] = array();

        if (count($results['model']) > 0) {
            foreach ($results['model'] as $item) {
                $data['aaData'][] = [
                    'checkbox' => '',
                    'responsive_id' => esc($item->id),
                    'name' => esc($item->name),
                    'category_name' => esc($item->categoryName),
                    'status' => esc($item->status),
                    'created_at' => esc($item->created_at->format(FORMAT_DATE)),
                    'updated_at' => esc($item->updated_at->format(FORMAT_DATE)),
                    'edit_pages' => route_to('admin.attribute.edit', esc($item->id))
                ];
            }
        }

        return $this->response->setJSON($data);
    }
}
