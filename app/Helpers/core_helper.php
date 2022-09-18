<?php

use CodeIgniter\HTTP\RedirectResponse;
use Config\Services;
use CodeIgniter\I18n\Time;

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

function typeDiscountOption(): array
{
    return [
        '' => 'Loại giảm giá',
        TYPE_DISCOUNT_VND => 'Theo số tiền (VNĐ)',
        TYPE_DISCOUNT_PERCENT => 'Theo phần trăm (%)'
    ];
}

function uppercaseFirstCharacter($string): string
{
    return ucwords(strtolower($string));
}

function featuredOption(): array
{
    return [
        '' => '[-- Chọn nổi bật --]',
        FEATURED_ACTIVE => 'Nổi bật',
        FEATURED_INACTIVE => 'Không nổi bật'
    ];
}

function genderOption(): array
{
    return [
        GENDER_MALE => 'Nam',
        GENDER_FEMALE => 'Nữ'
    ];
}

function arraySearchValues($value, $array)
{
    if (is_array($array)) {
        foreach ($array as $k => $v) {
            if ($k === intval($value)) {
                return $v;
            }
        }
    }

    return false;
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
    if (file_exists($path) && $path !== PATH_IMAGE_DEFAULT) {
        unlink($path);
    }
}

function deleteMultipleImage($path, $array)
{
    if (count($array) > 0) {
        foreach ($array as $item) {
            delete_files(FCPATH . $path . $item->relation_id . '/', true);
            is_dir($path . $item->relation_id) && rmdir($path . $item->relation_id . '/');
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
    $image->resize($data['resize']['resizeX'], $data['resize']['resizeY'], true);
    $image->convert(IMAGETYPE_WEBP);
    deleteImage($withFile);

    return $image->save($data['savePath']);
}

/**
 * @throws Exception
 */
function dateFormat($date)
{
    return Time::parse($date)->toLocalizedString('dd-MM-yyyy');
}