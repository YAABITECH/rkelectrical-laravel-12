<?php

namespace App\Traits;

use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

trait FileUploadTrait
{
    public function handleFileUpload(Request $request, $fieldName, $fileFolder, $fileType, array $metadata = [])
    {
        if ($request->hasFile($fieldName)) {
            $files = $request->file($fieldName);
            $prefix = $metadata['prefix'] ?? 'file';

            if (isset($metadata['multiple']) && $metadata['multiple'] === true) {
                $fileNames = [];
    
                foreach ($files as $uploadedFile) {
                    $fileName = $this->generateFileName($uploadedFile, $prefix);
                    $this->processFileUpload($uploadedFile, $fileName, $fileFolder, $fileType, $metadata);
                    $fileNames[] = $fileName;
                }
                return $fileNames;
            } else {
                $fileName = $this->generateFileName($files, $prefix);
                $this->processFileUpload($files, $fileName, $fileFolder, $fileType, $metadata);
        
                return $fileName;
            }
        }
        return null;
    }

    public function handleExistBulkUpload($files, $fileFolder, $fileType, array $metadata = [])
    {
        $prefix = $metadata['prefix'] ?? 'file';
        $fileNames = [];
        foreach ($files as $file) {
            $fileName = $this->generateFileNameExist($file, $prefix);
            $this->processFileUpload($file, $fileName, $fileFolder, $fileType, $metadata);
            $fileNames[] = $fileName;
        }
        return isset($metadata['multiple']) && $metadata['multiple'] === true ? $fileNames : $fileNames[0];
    }

    protected function processFileUpload($uploadedFile, $fileName, $fileFolder, $fileType, $metadata)
    {
        if ($fileType == 'image') {
            $this->handleImageUpload($uploadedFile, $fileName, $fileFolder, $metadata);
        } else {
            if (!is_dir(public_path($fileFolder))) {
                mkdir(public_path($fileFolder), 0775, true);
            }
            $uploadedFile->move(public_path($fileFolder), $fileName);
            if (!empty($metadata['deletefile'])) {
                $oldFileName = $metadata['deletefile'];
                $oldFilePath = public_path($fileFolder . $oldFileName);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }

        }
    }

    protected function generateFileName($uploadedFile, $prefix)
    {
        $extension = $uploadedFile->getClientOriginalExtension();
        return $prefix . '-' . uniqid() . '.' . $extension;
    }
    protected function generateFileNameExist($uploadedFile, $prefix)
    {
        $extension = pathinfo($uploadedFile->getFilename(), PATHINFO_EXTENSION);
        return $prefix . '-' . uniqid() . '.' . $extension;
    }

    protected function handleImageUpload($uploadedFile, $fileName, $fileFolder, $metadata)
    {
        $imageManager = new ImageManager(new Driver());
        $img = $imageManager->read($uploadedFile);
        if (!is_dir(public_path($fileFolder))) {
            mkdir(public_path($fileFolder), 0775, true);
        }
        if ($metadata['original'] ?? false) {
            $uploadedFile->move(public_path($fileFolder), $fileName);

        }
        if (!empty($metadata['sizes'])) {
            foreach ($metadata['sizes'] as $size) {
                $width = $size['width'] ?? null;
                $height = $size['height'] ?? null;
                $path = $size['path'] ?? '/';
                $mode = $metadata['mode'] ?? 'scale';

                $targetFolder = $fileFolder . $path;

                if (!is_dir(public_path($targetFolder))) {
                    mkdir(public_path($targetFolder), 0775, true);
                }

                $resizedImg = $this->resizeImage($img, $width, $height, $mode);
                $resizedImg->save(public_path($targetFolder . $fileName));

                if (!empty($metadata['deletefile'])) {
                    $oldFileName = $metadata['deletefile'];
                    $oldFilePath = public_path($targetFolder . $oldFileName);
                    if (file_exists($oldFilePath)) {
                        unlink($oldFilePath);
                    }
                }

            }
        }
    }
    protected function resizeImage($img, $width, $height, $mode)
    {
        switch ($mode) {
            case 'resize':
                return $img->resize($width, $height);
            case 'resizeDown':
                return $img->resizeDown($width, $height);
            case 'scale':
                return $img->scale($width, $height);
            case 'scaleDown':
                return $img->scaleDown($width, $height);
            case 'cover':
                return $img->cover($width, $height);
            case 'coverDown':
                return $img->coverDown($width, $height);
            case 'pad':
                return $img->pad($width, $height);
            case 'contain':
                return $img->contain($width, $height);
            case 'crop':
                return $img->crop($width, $height);
            case 'resizeCanvas':
                return $img->resizeCanvas($width, $height);
            case 'resizeCanvasRelative':
                return $img->resizeCanvasRelative($width, $height);
            default:
                return $img->scale($width, $height);
        }
    }
}
