<?php
namespace App\Helpers;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

 class FileUploads{       
    public function uploadDocument($image, $newname)
    {
        //Store a Document of Justified Proof
        $file = $image;
        $filename = Str::slug($newname);
        $filename = time() .'-'.$filename;
        $image = $filename;
        $file->storeAs('public', $filename);
        return $filename;
    }

    public function hashChecker($hash){
        if(strlen($hash) == 60 && preg_match('/^\$2y\$/', $hash )){
            return 'true';
        }
        return 'false';
    }

    public function uploadPhotos($img, $path='posts', $name)
    {

        try {
            $file = $img;
            $newName = Str::slug($name);
            $filenameWithExt = $img->getClientOriginalName();
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            $extension = $img->getClientOriginalExtension();
            $fileNameToStore= $newName.'-'.$filename.'-'.time().'.'.$extension;
            $file->storeAs('public/'.$path, $fileNameToStore);
        } catch (\Exception $e) {
            return $e;
        }

        return $fileNameToStore;
        // file_put_contents(public_path() . '/' . 'images/items/' . $imageName, $data);
    }

    public function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
 }
?>