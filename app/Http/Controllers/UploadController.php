<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Input;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Storage;



class UploadController extends Controller {

    public function postImage() {
        if (Input::hasFile('featured_upload')) {
            $image = Input::file('featured_upload');

            $width = Input::has("width") ? Input::get("width") : 720;
            $height = Input::has("height") ? Input::get("height") : 720;

            $url = $this->uploadFeatured($image, $width, $height);
            return response()->json(['path' => $url], 200);
        }

        return response()->json(['path' => ''], 500);
    }

    private function uploadFeatured($image, $width, $height) {

//        $directory = '/uploads/doctors/' . date("Y") . '/' . date("m");
//
//        $destination = public_path() . $directory;
//
//        if (!file_exists($destination)) {
//            mkdir($destination, 0777, true);
//      }
//
        $timestamp = time();
        $info = getimagesize($image);
        $extension = image_type_to_extension($info[2]);
        $name = 'doctor_' . $timestamp . $extension;
//
//        $image->move($destination, $name);
//        $angle = $this->rightOrientation($destination, $name);

        /*Image::make($destination . '/' . $name)->rotate($angle)->fit($width, $height, function($constraint){
            $constraint->upsize();
        },'center')->save($destination . '/' . $name);*/

        Storage::disk('s3')->put($name, fopen($image->path(), 'r'), 'public');

        return Storage::disk('s3')->url($name);

    }

    private function rightOrientation($destination, $name)
    {

        $extension = pathinfo($name, PATHINFO_EXTENSION);
        $exifExts = array("jpeg", "jpg", "JPG", "JPEG");

        if (in_array($extension, $exifExts)) $exif = exif_read_data($destination . '/' . $name);

        if(!empty($exif['Orientation'])) {

            switch($exif['Orientation']) {

                default:
                    $angle = 0;
                    break;

                case 8:
                    $angle = 90;
                    break;

                case 3:
                    $angle = 180;
                    break;

                case 6:
                    $angle = -90;
                    break;

            }

        } else $angle = 0;

        return $angle;

    }
}
