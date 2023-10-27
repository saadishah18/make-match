<?php
namespace App\Service;


use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Symfony\Component\HttpFoundation\File\File;

class UploadsService
{

    public static function uploadAccordingToStorageDisk($file,$directory,$filename,$replace_file_name_if_matched = false,$delete_if_name_is_matched = false)
    {
        $storageDriver = config('filesystems.default');
        $filename = str_replace(['!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '<', '>', '\\', '/', '//', ' '], '', $filename);

        if(config('filesystems.default') == 'wasabi'){
            if($delete_if_name_is_matched){

                $exists = Storage::disk($storageDriver)->exists($directory.'/'.$filename);
                if($exists){
                    Storage::disk($storageDriver)->delete($directory.'/'.$filename);
                }
            }

            if($replace_file_name_if_matched){

                $isUploadedWithSameName = Storage::disk($storageDriver)->exists($directory.'/'.$filename);
                $filename = $isUploadedWithSameName ? time() . '_' . $filename : $filename;

            }

            $uploadedFilePath =  $file->storeAs($directory,$filename,['disk' => 'wasabi', 'visibility' => 'public']);
            if($uploadedFilePath){
                $url = Storage::disk($storageDriver)->url($uploadedFilePath);
                return ['url' => $url, 'name' => $filename];
            }

            throw new \Exception('File could not be uploaded');

        }else{

            checkIfDirectoryIsAvailable($directory);
            if($delete_if_name_is_matched){
                checkIfFileIsUploadedThenDelete(asset($directory.'/'.$filename));
            }
            if($replace_file_name_if_matched){
                $isUploadedWithSameName = checkIfUploadedFileHasSameName(asset($directory .'/'. $filename));

                $filename = $isUploadedWithSameName ? time() . '_' . $filename : $filename;
            }
            $filename = time() .'_'.$filename;

            $uploaded = $file->move(
                public_path($directory) //directory from public path becomes location of the file in uploads
                ,$filename);
            if($uploaded){

                //dont use asset here otherwise domain (http://127.0.0.1:800) etc will be saved in db
                //use asset in accessor
                //this will avoid adding
//                return ['url' => asset($directory .'/'. $filename), 'name' => $filename];
                return ['url' => $directory .'/'. $filename, 'name' => $filename];
            }
            throw new \Exception('File could not be uploaded');
        }
    }

    public static function uploadMultiple($request, $path)
    {
        // iterate through files and upload to path as given
        // TODO: Implement uploadMultiple() method.
    }

    public static function uploadProfilePicture($file, $directory,$filename)
    {
        try {
            checkIfDirectoryIsAvailable($directory);
            checkIfFileIsUploadedThenDelete($directory.$filename);
            return $file->move(
                public_path($directory) //directory from public path becomes location of the file in uploads
                ,$filename);
        }catch (\Exception $e){
            Log::error($e->getMessage(). ' '.$e->getFile(). ' '. $e->getLine());
        }
    }

    public static function delete($filePath)
    {
        checkIfFileIsUploadedThenDelete($filePath);
    }

    public static function download($filePath)
    {
        //download file from local storage
        return response()->download(public_path($filePath));
    }

    public static function uploadBase64($image_64,$directory,$filename,$replace_file_name_if_matched = false,$delete_if_name_is_matched = false)
    {
        $decodedImage = self::base64ToImage($image_64);
        $file = self::decodedImageToUploadedFile($decodedImage);
        $extension = explode('/', explode(':', substr($image_64, 0, strpos($image_64, ';')))[1])[1];
        $filename = $filename.'.'.$extension;
//        ImageOptimizer::optimize($file->getPathname());
        $data =  self::uploadAccordingToStorageDisk($file,$directory,$filename,$replace_file_name_if_matched,$delete_if_name_is_matched);
        unset($file);
        return $data;
    }

    public static function decodedImageToUploadedFile($decodedImage)
    {
        //save in temp dir
        $tmpFilePath = sys_get_temp_dir() . '/' . Str::uuid()->toString();
        file_put_contents($tmpFilePath, $decodedImage);
        // this just to help us get file info.
        $tmpFile = new File($tmpFilePath);
        $file = new UploadedFile(
            $tmpFile->getPathname(),
            $tmpFile->getFilename(),
            $tmpFile->getMimeType(),
            0,
            true // Mark it as test, since the file isn't from real HTTP POST.
        );
        unset($tmpFile);
        return $file;
    }


    public static function base64ToImage($image_64)
    {
        $replace = substr($image_64, 0, strpos($image_64, ',')+1);
        $image = str_replace($replace, '', $image_64);
        $image = str_replace(' ', '+', $image);
        $decodedImage = base64_decode($image);
        return $decodedImage;
    }


    public static function pathToUploadedImage($filePath){
        //$filePath e.g. /uploads/...
        $tmpFile = new File(public_path($filePath));
        $file = new UploadedFile(
            $tmpFile->getPathname(),
            $tmpFile->getFilename(),
            $tmpFile->getMimeType(),
            0,
            true // Mark it as test, since the file isn't from real HTTP POST.
        );

        return $file;
    }

}
