<?php

namespace App\Controllers\Backend;

use App\Controllers\BaseController;
use App\Models\AuthLogin;
use CodeIgniter\HTTP\Files\UploadedFile;
use CodeIgniter\HTTP\RedirectResponse;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\Users as User;

class UsersController extends BaseController
{
    protected User $user;
    protected AuthLogin $authLogins;

    public function __construct()
    {
        $this->user = new User();
        $this->authLogins = new AuthLogin();
    }

    public function activity(): string
    {
        return view('backend/users/activity');
    }

    public function changePassword(): string
    {
        $data['getDeviceUser'] = $this->authLogins->getDeviceUser(user_id());
        return view('backend/users/change_password', $data);
    }

    public function update($id): ResponseInterface
    {
        if ($this->request->isAJAX()) {
            $input = $this->request->getPost(setting('Auth.personalFields'));

            $user = $this->user->findById($id);
            $file = $this->request->getFile('image');
            $input['avatar'] = empty($input['imageRoot']) ? NULL : $input['imageRoot'];
            $input['birthdate'] = empty($input['birthdate']) ? NULL : $input['birthdate'];

            if ($file) {
                if ($file->isValid() && !$file->hasMoved()) {
                    $input['avatar'] = $this->serviceUploadImage($id, $file);
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

    public function postChangePassword($id): RedirectResponse
    {
        $input = $this->request->getPost('new_password');
        $user = $this->user->findById($id);
        $user->setPassword($input);

        if ($this->user->save($user)) {
            return redirectMessage('admin.users.changePassword', 'message', "Đổi mật khẩu thành công.");
        }

        return redirectMessage('admin.users.changePassword', 'error', MESSAGE_ERROR);
    }

    private function serviceUploadImage($id, UploadedFile $file): string
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
            ]
        ];

        imageManipulation($data);
        return $savePath;
    }
}
