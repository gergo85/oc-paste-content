<?php namespace Indikator\Paste;

use System\Classes\PluginBase;
use Backend;
use Lang;
use Indikator\Paste\Models\Text;
use Indikator\Paste\Models\Code;
use Indikator\Paste\Models\Block;
use Backend\FormWidgets\RichEditor;

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
                'url'         => Backend::url('indikator/paste/block'),
                'icon'        => 'icon-clipboard',
                'iconSvg'     => 'plugins/indikator/paste/assets/images/paste-icon.svg',
                'permissions' => ['indikator.paste.*'],
                'order'       => 201,

                'sideMenu' => [
                    'block' => [
                        'label'       => 'indikator.paste::lang.menu.block',
                        'url'         => Backend::url('indikator/paste/block'),
                        'icon'        => 'icon-th-large',
                        'permissions' => ['indikator.paste.block']
                    ],
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

    public function registerComponents()
    {
        return [
            'Indikator\Paste\Components\Blocks' => 'blocks'
        ];
    }

    public function registerPermissions()
    {
        return [
            'indikator.paste.block' => [
                'tab'   => 'indikator.paste::lang.plugin.name',
                'label' => 'indikator.paste::lang.permission.block'
            ],
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

    public function registerListColumnTypes()
    {
        return [
            'paste_status' => function($value) {
                $text = [
                    1 => 'active',
                    2 => 'inactive'
                ];

                $class = [
                    1 => 'text-info',
                    2 => 'text-danger'
                ];

                return '<span class="oc-icon-circle '.$class[$value].'">'.Lang::get('indikator.content::lang.form.status_'.$text[$value]).'</span>';
            }
        ];
    }

    public function registerMarkupTags()
    {
        return [
            'filters' => [
                'paste' => [$this, 'paste']
            ],
            'functions' => [
                'paste' => function($filter = 'text', $param = 0) {
                    if ($filter == 'text') {
                        if (is_string($param) && Text::where(['code' => $param, 'status' => 1])->count() == 1) {
                            return Text::where('code', $param)->pluck('content');
                        }
                        else if (is_numeric($param) && Text::where(['id' => $param, 'status' => 1])->count() == 1) {
                            return Text::where('id', $param)->pluck('content');
                        }
                    }

                    else if ($filter == 'code') {
                        if (is_string($param) && Code::where(['code' => $param, 'status' => 1])->count() == 1) {
                            return Code::where('code', $param)->pluck('content');
                        }
                        else if (is_numeric($param) && Code::where(['id' => $param, 'status' => 1])->count() == 1) {
                            return Code::where('id', $param)->pluck('content');
                        }
                    }

                    return '';
                }
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

    public function boot()
    {
        RichEditor::extend(function($widget) {
            $widget->addJs('/indikator/paste/list.js');
            $widget->addJs('/plugins/indikator/paste/assets/js/froala.paste.plugin.js');
        });
    }
}
