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
        $input = $this->request->getPost();

        if (!$this->slider->insert($input)) {
            return redirectMessage('admin.slider.index', 'error', MESSAGE_ERROR);
        }

        $file = $this->request->getFile('image');
        $getID = $this->slider->getInsertID();

        if ($file) {
            if ($file->isValid() && !$file->hasMoved()) {
                $imageURL = $this->serviceUploadImage($getID, $file);
            }
        }

        if (!$this->slider->update($getID, [
            'image_uri' => $imageURL ?? NULL
        ])) {
            return redirectMessage('admin.slider.index', 'error', MESSAGE_ERROR);
        }

        return redirectMessage('admin.slider.index', 'success', "Slider <strong class='text-capitalize'>" . esc($input['name']) . "</strong> đã được thêm.");
    }

    private function serviceUploadImage($id, UploadedFile $file): string
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
                'resizeY' => '600'
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
        if (!$this->slider->update($id, $input)) {
            return redirectMessage('admin.slider.index', 'error', MESSAGE_ERROR);
        }

        return redirectMessage('admin.slider.index', 'success', "Slider <strong class='text-capitalize'>" . esc($input['name']) . "</strong> đã được cập nhật.");
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
                $getImagesMultiple = $this->slider->select('id')->whereIn('id', $result['chk'])->withDeleted()->findAll();
                deleteMultipleImage(PATH_SLIDER_IMAGE, $getImagesMultiple);
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

    public function validateExistSlug(): ResponseInterface
    {
        $input = $this->request->getPost();
        $result = $this->slider
            ->select('id')
            ->where('id !=', $input['id'])
            ->where('url', $input['url'])
            ->countAllResults();

        $isValid = !($result > 0);

        return $this->response->setJSON([
            'valid' => var_export($isValid, 1)
        ]);
    }
}
