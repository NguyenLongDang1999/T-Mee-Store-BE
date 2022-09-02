<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Users as User;

class UsersController extends BaseController
{
    protected User $user;

    public function __construct()
    {
        $this->user = new User();
    }

    public function index(): string
    {
        return view('backend/users/profile');
    }

    public function update($id): ResponseInterface
    {
        if ($this->request->isAJAX()) {
            $input = $this->request->getPost(setting('Auth.personalFields'));

            $user = $this->user->findById($id);
            $file = $this->request->getFile('image');
            $input['avatar'] = empty($input['imageRoot']) ? NULL : $input['imageRoot'];

            if ($file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $input['avatar'] = $this->userUploadImage($id, $file);
                    deleteImage($input['imageRoot']);
                }
            }

            $user->fill($input);

            if ($this->user->save($user)) {
                $input['gender'] = isset($input['gender']) ? arraySearchValues($input['gender'], genderOption()) : '';
                $input['avatar'] = base_url($input['avatar'] ?? PATH_IMAGE_DEFAULT);

                $data['result'] = true;
                $data['message'] = '<span class="text-capitalize">Cập nhật thông tin cá nhân thành công.</span>';
                $data['input'] = $input;
                return $this->response->setJSON($data);
            }
        }

        $data['result'] = false;
        return $this->response->setJSON($data);
    }

    private function userUploadImage($id, UploadedFile $file): string
    {
        $path = PATH_USER_IMAGE . $id . '/';

        $fileName = $file->getRandomName();
        $file->move($path, $fileName);

        $savePath = $path . convertImageWebp($fileName);
        $data = [
            'path' => $path,
            'fileName' => $fileName,
            'savePath' => $savePath,
            'resize' => [
                'resizeX' => '250',
                'resizeY' => '250',
                'ratio' => false,
                'masterDim' => 'auto'
            ]
        ];

        imageManipulation($data);
        return $savePath;
    }
}
