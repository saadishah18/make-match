<?php
namespace App\Traits;

use App\Models\Khulu;
use App\Service\Facades\Api;
use Illuminate\Support\Str;
use App\Service\UploadsService;
trait ImageTrait
{
    public function uploadImage($request,$fileNameColumn=null,$deleteOldFile = false)
    {
        if ($request->hasFile($fileNameColumn)) {
            $uploadsPath = '/uploads/certificates';

            $fileNamePrefix = $fileNameColumn;

            $filename = $fileNamePrefix . ($this->id) . '_'  .'.'. $request->file($fileNameColumn)->getClientOriginalExtension();
            if ($deleteOldFile){
                $this->deleteOld($this->{$fileNameColumn});
            }
            $fileFullPathWithName = UploadsService::uploadAccordingToStorageDisk($request->{$fileNameColumn},$uploadsPath,$filename,true,true);
            if(!empty($fileFullPathWithName)){
               /* $this->{$fileNameColumn} = $fileFullPathWithName['url'];
                $this->save();*/
                return $fileFullPathWithName['name'];
            }

        }

        if ($request->has($fileNameColumn)  && (preg_match('/^data:image\/(\w+);base64,/', $request->{$fileNameColumn}, $type) || preg_match('/^data:video\/(\w+);base64,/', $request->{$fileNameColumn}, $type))) {
            $dir = '/uploads/certificates';
            $fileName = Str::kebab ($this->id.uniqid('pp').time());
            $this->deleteOld($this->{$fileNameColumn});
            $details  = UploadsService::uploadBase64($request->{$fileNameColumn},$dir,$fileName,true,true);
           /* $this->{$fileNameColu mn} = $details['url'];
            $this->save();*/
            return $details['url'];
        }
    }

    public function deleteOld($filepath)
    {
        if(!is_null($filepath)){
            UploadsService::delete($filepath);
        }
    }

    public function getCardImageUrl()
    {
        $imagePath = null;
        if (!is_null($this->card_image)) {
            $imagePath =  asset($this->card_image);
        }
        return $imagePath;

    }

    public function getDetailPageImageUrl()
    {
        $imagePath = null;
        if (!is_null($this->detail_page_image)) {
            $imagePath =  asset($this->detail_page_image);
        }
        return $imagePath;
    }

    public function getSmallImageUrl()
    {
        $imagePath = null;
        if (!is_null($this->small_image)) {
            $imagePath =  asset($this->small_image);
        }
        return $imagePath;
    }

    public function image_validation($image)
    {
        $fileExtension = substr(strrchr($image->getClientOriginalName(), '.'), 1);
        if (in_array($fileExtension,['jpeg','jpg','mp4','MP4'.'png','docs','pdf','gif'])) {
            return Api::error('Image extension should be jpeg,jpg,png,and gif');
        }
        $filesize = \File::size($image);
        if ($filesize >= 1024 * 1024 * 5) {
            return Api::error('Image size should less than 5 mb');
        }
        return true;
    }

}
