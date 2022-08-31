<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\Slider;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use ReflectionException;

class SliderController extends BaseController
{
    protected Slider $slider;

    public function __construct()
    {
        $this->slider = new Slider();
    }

    public function index(): string
    {
        return view('backend/slider/index');
    }

    public function getList(): ResponseInterface
    {
        $input = $this->request->getGet();
        $results = $this->slider->getList($input);

        return $this->getListSlider($results);
    }

    public function recycle(): string
    {
        $data['recyclePage'] = true;
        return view('backend/slider/index', $data);
    }

    public function getListRecycle(): ResponseInterface
    {
        $input = $this->request->getGet();
        $results = $this->slider->getListRecycle($input);

        return $this->getListSlider($results);
    }

    public function create(): string
    {
        $data['routePost'] = route_to('admin.slider.store');
        return view('backend/slider/create_edit', $data);
    }

    /**
     * @throws ReflectionException
     */
    public function store(): RedirectResponse
    {
        $input = $this->request->getPost([
            'name',
            'url',
            'description',
            'status',
            'sort'
        ]);

        if (!$this->slider->insert($input)) {
            return redirectMessage('admin.slider.index', 'error', MESSAGE_ERROR);
        }

        $file = $this->request->getFile('image');
        $getID = $this->slider->getInsertID();
        $imageFile = NULL;

        if ($file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $imageFile = $this->SliderUploadImage($getID, $file);
            }
        }

        if ($this->slider->update($getID, ['image' => $imageFile])) {
            return redirectMessage('admin.slider.index', 'success', "Slider <strong class='text-capitalize'>" . esc($input['name']) . "</strong> đã được thêm.");
        }

        return redirectMessage('admin.slider.index', 'error', MESSAGE_ERROR);
    }

    public function edit($id): string
    {
        $data['row'] = $this->slider->getSliderByID($id);
        $data['routePost'] = route_to('admin.slider.update', $id);
        return view('backend/slider/create_edit', $data);
    }

    /**
     * @throws ReflectionException
     */
    public function update($id): RedirectResponse
    {
        $input = $this->request->getPost([
            'name',
            'slug',
            'description',
            'status',
            'sort',
            'imageRoot'
        ]);

        $input['id'] = $id;

        $file = $this->request->getFile('image');

        if ($file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $input['image'] = $this->SliderUploadImage($id, $file);
                deleteImage($input['imageRoot']);
            }
        }
        if ($this->slider->update($id, $input)) {
            return redirectMessage('admin.slider.index', 'success', "Slider <strong class='text-capitalize'>" . esc($input['name']) . "</strong> đã được cập nhật.");
        }

        return redirectMessage('admin.slider.index', 'error', MESSAGE_ERROR);
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
            if ($this->slider->update($result['chk'], [$type => $status === 'NULL' ? NULL : $status])) {
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
                $getMultiImageSlider = $this->slider->getMultiImageSlider($result['chk']);
                deleteMultipleImage(PATH_SLIDER_IMAGE, $getMultiImageSlider);
            }

            if ($this->slider->delete($result['chk'], $purge)) {
                $data['result'] = true;
                $data['message'] = '<span class="text-capitalize">Xóa thành công dữ liệu được chọn.</span>';
                return $this->response->setJSON($data);
            }
        }

        $data['result'] = false;
        return $this->response->setJSON($data);
    }

    public function SliderExistSlug(): ResponseInterface
    {
        $input = $this->request->getPost('slug');
        $result = $this->slider->sliderExistSlug($input);
        $isValid = !($result > 0);

        return $this->response->setJSON([
            'valid' => var_export($isValid, 1)
        ]);
    }

    private function SliderUploadImage($id, UploadedFile $file): string
    {
        $path = PATH_SLIDER_IMAGE . $id . '/';

        $fileName = $file->getRandomName();
        $file->move($path, $fileName);

        $savePath = $path . convertImageWebp($fileName);
        $data = [
            'path' => $path,
            'fileName' => $fileName,
            'savePath' => $savePath,
            'resize' => [
                'resizeX' => '1900',
                'resizeY' => '600',
                'ratio' => false,
                'masterDim' => 'auto'
            ]
        ];

        imageManipulation($data);
        return $savePath;
    }

    /**
     * @param array $results
     * @return ResponseInterface
     */
    private function getListSlider(array $results): ResponseInterface
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
                        'width' => '100%',
                        'height' => 100
                    ]),
                    'name' => esc($item->name),
                    'sort' => esc($item->sort),
                    'status' => esc($item->status),
                    'created_at' => esc($item->created_at->format(FORMAT_DATE)),
                    'updated_at' => esc($item->updated_at->format(FORMAT_DATE)),
                    'edit_pages' => route_to('admin.slider.edit', esc($item->id))
                ];
            }
        }

        return $this->response->setJSON($data);
    }
}
