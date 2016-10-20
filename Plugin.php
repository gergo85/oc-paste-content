<?php namespace Indikator\Paste;

use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use Backend;
use Indikator\Paste\Models\Text;

class Plugin extends PluginBase
{
    public function pluginDetails()
    {
        return [
            'name'        => 'indikator.paste::lang.plugin.name',
            'description' => 'indikator.paste::lang.plugin.description',
            'author'      => 'indikator.paste::lang.plugin.author',
            'icon'        => 'icon-clipboard',
            'homepage'    => 'https://github.com/gergo85/oc-paste-content'
        ];
    }

    public function registerNavigation()
    {
        return [
            'paste' => [
                'label'       => 'indikator.paste::lang.plugin.name',
                'url'         => Backend::url('indikator/paste/text'),
                'icon'        => 'icon-clipboard',
                'permissions' => ['indikator.paste'],
                'order'       => 500,
                'sideMenu' => [
                    'text' => [
                        'label'       => 'indikator.paste::lang.menu.text',
                        'url'         => Backend::url('indikator/paste/text'),
                        'icon'        => 'icon-file-text',
                        'permissions' => ['indikator.paste.text']
                    ]
                ]
            ]
        ];
    }

    public function registerPermissions()
    {
        return [
            'indikator.paste.text' => [
                'tab'   => 'indikator.paste::lang.plugin.name',
                'label' => 'indikator.paste::lang.permission.text'
            ]
        ];
    }

    public function registerMarkupTags()
    {
        return [
            'filters' => [
                'paste' => [$this, 'paste']
            ]
        ];
    }

    public function paste($text)
    {
        $sql = Text::get();

        foreach ($sql as $item) {
            if ($item->status == 1) {
                $replace = $item->content;
            }
            else {
                $replace = '';
            }

            $text = str_replace(
                [
                    '{{'.$item->code.'}}',
                    '{{ '.$item->code.'}}',
                    '{{'.$item->code.' }}',
                    '{{ '.$item->code.' }}'
                ],
                $replace,
                $text
            );
        }

        return $text;
    }
}
