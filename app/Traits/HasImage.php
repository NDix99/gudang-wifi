<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait HasImage
{
    public function uploadImage($request, $path, $name)
    {
        $image = null;

        if($request->file($name)){
            $image = $request->file($name);
            // Simpan ke disk public agar dapat diakses via public/storage
            Storage::disk('public')->putFileAs($path, $image, $image->hashName());
        }

        return $image;
    }

    public function updateImage($path, $name, $data, $url)
    {
        // Hapus file lama dari disk public
        Storage::disk('public')->delete($path. basename($data->image));
        $data->update([
            $name => $url,
        ]);
    }
}
