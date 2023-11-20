<?php

namespace Modules\HRM\Service;

use Modules\HRM\Interface\CommonServiceInterface;

class CommonService implements CommonServiceInterface
{
    /**showAvatarImage
     * @param string $imagePath Path of the image inside a public directory.
     * @param string $imageName Name of the Image.
     *
     * Eg. $image = showImage('uploads/employees/', 'avatar.jpg');
     *
     * @return image Returns an Html avatar Images with proper validation.
     */
    public function showAvatarImage($imagePath, $imageName)
    {
        $fileExists = file_exists($imagePath . $imageName);

        if (!is_null($imageName)) {
            return $fileExists ? '<img src="' . asset($imagePath . $imageName) . '">' : '<img class="rounded-circle" src="' . asset('images/profile-picture.jpg') . '" alt="User" >';
        } else {
            return '<img class="rounded-circle" src="' . asset('images/profile-picture.jpg') . '" alt="User" >';
        }
    }
}
