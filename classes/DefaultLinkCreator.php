<?php namespace Yfktn\TulisanRss\Classes;

use Yfktn\Tulisan\Models\Tulisan;

class DefaultLinkCreator implements ILinkCreator
{
    public static function createLinkOf(Tulisan $tulisanModel)
    {
        return sprintf("%s/%s/%s", 
            config('yfktn.tulisanrss::host'), 
            config('yfktn.tulisanrss::defaultLinkCreatorPage'), 
            $tulisanModel->slug);
    }
}