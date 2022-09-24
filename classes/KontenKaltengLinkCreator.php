<?php namespace Yfktn\TulisanRss\Classes;

use Yfktn\Tulisan\Models\Tulisan;

class KontenKaltengLinkCreator implements ILinkCreator
{
    public static function createLinkOf(Tulisan $tulisanModel)
    {
        $cat = $tulisanModel->kategori()->first();
        if($cat == null) {
            // kalau tidak ada kategorinya, kita berikan saja ke berita indeks nya!
            return sprintf("%s/berita/baca/%s", 
                config('yfktn.tulisanrss::host'), 
                $tulisanModel->slug
            );
        }

        return sprintf("%s/%s/baca/%s", 
            config('yfktn.tulisanrss::host'), 
            $cat->slug, 
            $tulisanModel->slug);
    }
}