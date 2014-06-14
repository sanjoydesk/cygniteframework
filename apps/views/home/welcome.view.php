<!DOCTYPE html>
<?php
use Cygnite\AssetManager\Asset;
use Cygnite\Common\UrlManager\Url;
use Cygnite\AssetManager\AssetCollection;

 $asset =  AssetCollection::make(function($asset)
    {
        $asset->add('style', array('path' => 'assets/css/cygnite/style.css', 'media' => '', 'title' => ''))
                    ->add('style', array('path' => 'assets/css/test/*', 'media' => '', 'title' => ''))
                    ->add('script', array('path' => 'assets/js/angular/*', 'attributes' => ''))
                    ->add('link', array('path' => 'uses/', 'name' => 'hello', 'attributes' => array()))
                    ->add('link', array('path' => 'uses/list', 'name' => 'hl', 'attributes' => array()));

        return $asset;
    });

    //$asset->dump('style');
    //$asset->dump('script');
     //$asset->dump('link');
     //echo Asset::link('home/index', 'welcome');
?>