<?php namespace Indikator\Paste;

use System\Classes\PluginBase;
use Backend;
use Lang;
use App;
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
                'label' => 'indikator.paste::lang.permission.block',
                'order' => 100,
                'roles' => ['publisher']
            ],
            'indikator.paste.text' => [
                'tab'   => 'indikator.paste::lang.plugin.name',
                'label' => 'indikator.paste::lang.permission.text',
                'order' => 200,
                'roles' => ['publisher']
            ],
            'indikator.paste.code' => [
                'tab'   => 'indikator.paste::lang.plugin.name',
                'label' => 'indikator.paste::lang.permission.code',
                'order' => 300,
                'roles' => ['publisher']
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
                            return Text::where('code', $param)->value('content');
                        }
                        else if (is_numeric($param) && Text::where(['id' => $param, 'status' => 1])->count() == 1) {
                            return Text::where('id', $param)->value('content');
                        }
                    }

                    else if ($filter == 'code') {
                        if (is_string($param) && Code::where(['code' => $param, 'status' => 1])->count() == 1) {
                            return Code::where('code', $param)->value('content');
                        }
                        else if (is_numeric($param) && Code::where(['id' => $param, 'status' => 1])->count() == 1) {
                            return Code::where('id', $param)->value('content');
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
        if (App::hasDatabase() && \Schema::hasTable('indikator_paste_text') && Text::count() + Code::count() > 0) {
            RichEditor::extend(function($widget) {
                $widget->addJs('/indikator/paste/list.js');
                $widget->addJs('/plugins/indikator/paste/assets/js/froala.paste.plugin.js');
            });
        }
    }
}
