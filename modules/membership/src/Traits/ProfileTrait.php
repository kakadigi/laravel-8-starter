<?php

namespace Modules\Membership\Traits;

trait ProfileTrait
{
    function createIcon($first_letter, $second_letter, $save_as)
    {
        $image = ImageCreate(64, 64) or die("Error making image"); //Make the base image (64 x 64)
        $text_color = ImageColorAllocate($image, 255, 255, 255); //White text for font
        $font = __DIR__ . '/../../resources/fonts/Roboto-Regular.ttf'; //Defined font file
        if ($second_letter == '') { //Positioning for one vs two letters
            $string = "$first_letter";
            $img = imagettftext($image, 36, 0, 17, 47, $text_color, $font, $string);
        } else {
            $string = "$first_letter" . $second_letter . "";
            $img = imagettftext($image, 36, 0, 4, 47, $text_color, $font, $string);
        }
        $img = Imagepng($image, storage_path("app/public/profiles/$save_as")); //Finish the image generation
        imagedestroy($image); //Clear memory?
        return true;
    }

    public function splitFullName($fullName)
    {
        $names = collect(preg_split("/[\s]+/", $fullName));
        $lastName = $names->filter(function ($val, $index) {
            return $index > 0;
        })->join(' ');
        return collect([
            'first_name' => $names->first(),
            'last_name' => $names->count() > 1 ? $lastName : ''
        ]);
    }
}
