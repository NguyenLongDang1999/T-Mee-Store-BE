<?php

use CodeIgniter\HTTP\RedirectResponse;
use Config\Services;

function getMenuActive($patterns, $open = false, $activeClass = "active")
{
    if (empty($open) && url_is($patterns)) {
        return $activeClass;
    } elseif (!empty($open) && url_is($patterns . $open)) {
        return $activeClass . ' open';
    }

    return false;
}

function statusOption(): array
{
    return [
        '' => '[-- Chọn trạng thái --]',
        STATUS_ACTIVE => 'Hiển thị',
        STATUS_INACTIVE => 'Không hiển thị'
    ];
}

function featuredOption(): array
{
    return [
        '' => '[-- Chọn nổi bật --]',
        FEATURED_ACTIVE => 'Nổi bật',
        FEATURED_INACTIVE => 'Không nổi bật'
    ];
}

function convertImageWebp($fileName): string
{
    $parts = explode('.', $fileName);
    $parts[count($parts) - 1] = 'webp';
    return implode('.', $parts);
}

function redirectMessage($route, $key, $message): RedirectResponse
{
    return redirect()->route($route)->with($key, $message);
}

function deleteImage($path)
{
    if (file_exists($path)) {
        unlink($path);
    }
}

function deleteMultipleImage($path, $array)
{
    if (count($array) > 0) {
        foreach ($array as $item) {
            delete_files(FCPATH . $path . $item->id . '/', true);
            is_dir($path . $item->id) && rmdir($path . $item->id . '/');
        }
    }
}

function imageManipulation($data): bool
{
    $image = Services::image();
    $withFile = $data['path'] . $data['fileName'];

    if (!file_exists($data['path'])) {
        mkdir($data['path'], 0755);
    }

    $image->withFile($withFile);
    $image->resize($data['resize']['resizeX'], $data['resize']['resizeY'], $data['resize']['ratio'], $data['resize']['masterDim']);
    $image->convert(IMAGETYPE_WEBP);
    deleteImage($withFile);

    return $image->save($data['savePath']);
}