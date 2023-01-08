<?php

namespace App\Traits;

trait UploadFile {

    public function storeFile($file,$directory) {

        $name=time().rand(1,10000000000);
        $file->move(public_path($directory), $name.".".$file->getClientOriginalExtension());
        $file_path=$directory.'/'.$name.".".$file->getClientOriginalExtension();
        return $file_path;
    }

}
