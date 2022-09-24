<?php namespace Yfktn\TulisanRss;

use Backend;
use Event;
use Queue;
use System\Classes\PluginBase;
use Yfktn\TulisanRss\Classes\QueueCreateRSS;

/**
 * This is Tulisan RSS Feeder. Heavy inspired from https://github.com/jbh/oc-blog-rss-plugin, I decided to
 * create my own RSS Feeder, for my Yfktn.Tulisan plugin.
 */
class Plugin extends PluginBase
{
    public $require = ['Yfktn.Tulisan'];
    
    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Tulisan RSS Feeder',
            'description' => 'Provide RSS Feeder For Tulisan Plugin',
            'author'      => 'Yfktn',
            'icon'        => 'icon-leaf'
        ];
    }

    /**
     * Register method, called when the plugin is first registered.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Boot method, called right before the request route.
     *
     * @return void
     */
    public function boot()
    {

        // Event Listeners for Tulisan
        Event::listen('eloquent.created: Yfktn\Tulisan\Models\Tulisan', function($model) {
            $this->createRss();
        });
        Event::listen('eloquent.saved: Yfktn\Tulisan\Models\Tulisan', function($model) {
            $this->createRss();
        });
        Event::listen('eloquent.deleted: Yfktn\Tulisan\Models\Tulisan', function($model) {
            $this->createRss();
        });

    }

    public function createRss()
    {
        $date = \Carbon\Carbon::now()->addMinute();
        Queue::later($date, QueueCreateRSS::class);
    }

    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {
        return []; // Remove this line to activate

        return [
            'Yfktn\TulisanRss\Components\MyComponent' => 'myComponent',
        ];
    }

    /**
     * Registers any back-end permissions used by this plugin.
     *
     * @return array
     */
    public function registerPermissions()
    {
        return []; // Remove this line to activate

        return [
            'yfktn.tulisanrss.some_permission' => [
                'tab' => 'TulisanRss',
                'label' => 'Some permission'
            ],
        ];
    }

    /**
     * Registers back-end navigation items for this plugin.
     *
     * @return array
     */
    public function registerNavigation()
    {
        return []; // Remove this line to activate

        return [
            'tulisanrss' => [
                'label'       => 'TulisanRss',
                'url'         => Backend::url('yfktn/tulisanrss/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['yfktn.tulisanrss.*'],
                'order'       => 500,
            ],
        ];
    }
}
