<?php

namespace Xguard\Coordinator\Actions\Supervisors;

use Lorisleiva\Actions\Action;
use Xguard\Coordinator\AWSStorage\S3Storage;

class GetUserShiftOdometerImagesAction extends Action
{
    public function handle(): array
    {
        $disk = app(S3Storage::class);
        $url = $disk->getDriver()->getConfig()->get('url');
        $dir = $this->userId.'/'.$this->shiftId.'/';
        $allFiles = $disk->allFiles($dir);
        array_walk($allFiles, function (&$path) use ($url) {
            $path = $url.'/'.$path;
        });
        return $allFiles;
    }
}
