<?php namespace DanielFarina\Instagram;

use Backend;
use System\Classes\PluginBase;

/**
 * PageSpeedOptimizer Plugin Information File
 */
class Plugin extends PluginBase
{

    /**
     * Returns information about this plugin.
     *
     * @return array
     */
    public function pluginDetails()
    {
        return [
            'name'        => 'Instagram',
            'description' => 'Shows the latest instagram pictures of a specified user ',
            'author'      => 'DanielFarina',
            'icon'        => 'icon-leaf'
        ];
    }


    public function registerSettings(){
        return [
            'settings' => [
                'label'       => 'Instragram Swiper',
                'description' => 'Shows the latest instagram pictures of a specified user',
                'icon'        => 'icon-bar-chart-o',
                'class'       => 'DanielFarina\Instagram\Models\InstagramSettings',
                'order'       => 1
            ]
        ];
    }





    /**
     * Registers any front-end components implemented in this plugin.
     *
     * @return array
     */
    public function registerComponents()
    {

        return [
            'DanielFarina\Instagram\Components\Slider' => 'Slider',
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
            'danielfarina.instagram.enable' => [
                'tab' => 'Instagram',
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
            'instagram' => [
                'label'       => 'Instagram',
                'url'         => Backend::url('danielfarina/instagram/mycontroller'),
                'icon'        => 'icon-leaf',
                'permissions' => ['danielfarina.Instagram.*'],
                'order'       => 500,
            ],
        ];
    }

}
