<?php namespace Yfktn\TulisanRss\Classes;

interface ILinkCreator
{
    public static function createLinkOf(\Yfktn\Tulisan\Models\Tulisan $tulisanModel);
}