<?php namespace Indikator\Paste;

use System\Classes\PluginBase;
use System\Classes\SettingsManager;
use Backend;
use Indikator\Paste\Models\Text;
use Indikator\Paste\Models\Code;

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
                'permissions' => ['indikator.paste.*'],
                'order'       => 500,
                'sideMenu' => [
                    'text' => [
                        'label'       => 'indikator.paste::lang.menu.text',
                        'url'         => Backend::url('indikator/paste/text'),
                        'icon'        => 'icon-file-text',
                        'permissions' => ['indikator.paste.text']
                    ],
                    'code' => [
                        'label'       => 'indikator.paste::lang.menu.code',
                        'url'         => Backend::url('indikator/paste/code'),
                        'icon'        => 'icon-file-code-o',
                        'permissions' => ['indikator.paste.code']
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
            ],
            'indikator.paste.code' => [
                'tab'   => 'indikator.paste::lang.plugin.name',
                'label' => 'indikator.paste::lang.permission.code'
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
        $text = $this->pasteContent($text, 'text');
        $text = $this->pasteContent($text, 'code');

        return $text;
    }

    public function pasteContent($text = '', $type = 'text')
    {
        if ($type == 'text') {
            $sql = Text::get();
        }
        else {
            $sql = Code::get();
        }

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
