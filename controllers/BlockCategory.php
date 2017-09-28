<?php namespace Indikator\Paste\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Indikator\Paste\Models\BlockCategory as Item;
use Flash;
use Lang;
use File;
use Cms\Classes\Theme;

class BlockCategory extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = ['indikator.paste.block'];

    public $bodyClass = 'compact-container';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Indikator.Paste', 'paste', 'block');
    }

    public function onRemove()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {
            foreach ($checkedIds as $itemId) {
                if (!$item = Item::whereId($itemId)) {
                    continue;
                }

                $item->delete();

                \Indikator\Paste\Models\Block::where('category', $itemId)->update([
                    'category' => 0
                ]);
            }

            Flash::success(Lang::get('indikator.content::lang.flash.remove'));
        }

        return $this->listRefresh();
    }

    public function onShowCode()
    {
        return '
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="popup">×</button>
                <h4 class="modal-title">'.Lang::get('indikator.paste::lang.form.code').'</h4>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    <strong>'.Lang::get('indikator.paste::lang.popup.1step_title').'</strong><br>
                    '.Lang::get('indikator.paste::lang.popup.1step_desc').'<br><br>
                </div>
                <div class="col-md-6">
                    <strong>'.Lang::get('indikator.paste::lang.popup.2step_title').'</strong><br>
                    '.Lang::get('indikator.paste::lang.popup.2step_desc').'<br>
                    <pre>
{% for item in blocks %}
    {% if item.category == '.post('id').' %}
    
    '.Lang::get('indikator.paste::lang.popup.2step_code').'

    {% endif %}
{% endfor %}</pre>
                    '.Lang::get('indikator.paste::lang.popup.2step_comment').'
                </div>
                <div class="col-md-6">
                    <strong>'.Lang::get('indikator.paste::lang.popup.3step_title').'</strong><br>
                    '.Lang::get('indikator.paste::lang.popup.3step_desc').'<br><br>
                    <strong>{{ item.title }}</strong> - '.Lang::get('indikator.paste::lang.form.title').'<br>
                    <strong>{{ item.subtitle }}</strong> - '.Lang::get('indikator.paste::lang.form.subtitle').'<br>
                    <strong>{{ item.content|raw }}</strong> - '.Lang::get('indikator.paste::lang.form.content').'<br>
                    <strong>{{ item.button_name }}</strong> - '.Lang::get('indikator.paste::lang.form.button_name').'<br>
                    <strong>{{ item.button_link }}</strong> - '.Lang::get('indikator.paste::lang.form.button_link').'<br>
                    <strong>{{ item.button_class }}</strong> - '.Lang::get('indikator.paste::lang.form.button_class').'<br>
                    <strong>{{ item.button_position }}</strong> - '.Lang::get('indikator.paste::lang.form.button_position').'<br>
                     <strong>{{ item.image|media }}</strong> - '.Lang::get('indikator.paste::lang.form.image').'<br>
                    <strong>{{ item.color }}</strong> - '.Lang::get('indikator.paste::lang.form.color').'
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="popup">'.Lang::get('backend::lang.form.close').'</button>
            </div>
        ';
    }

    public function onShowStat()
    {
        $types = ['pages', 'partials', 'layouts'];

        foreach ($types as $type) {
            $items[$type] = [];
            $result[$type] = '';

            $theme = Theme::getEditTheme()->getDirName();
            $path = base_path().'/themes/'.$theme.'/'.$type;
            $files = File::allFiles($path);

            foreach ($files as $file) {
                $content = File::get((string)$file);
                
                if (substr_count($content, 'item.category == '.post('id')) > 0 || substr_count($content, "item.category == '".post('code')."'") || substr_count($content, 'item.category == "'.post('code').'"') > 0) {
                    if (!isset($items[$type][(string)$file])) {
                        $items[$type][(string)$file] = true;
                    }
                }
            }

            natsort($items[$type]);

            foreach ($items[$type] as $name => $count) {
                $result[$type] .= str_replace($path.'/', '', $name).'<br>';
            }

            if ($result[$type] == '') {
                $result[$type] = '<em>'.Lang::get('indikator.paste::lang.popup.none').'</em><br>';
            }
        }

        return '
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="popup">×</button>
                <h4 class="modal-title">'.Lang::get('indikator.paste::lang.popup.statistics').'</h4>
            </div>
            <div class="modal-body">
                <strong>'.Lang::get('cms::lang.page.menu_label').'</strong><br>
                '.$result['pages'].'
                <br>
                <strong>'.Lang::get('cms::lang.partial.menu_label').'</strong><br>
                '.$result['partials'].'
                <br>
                <strong>'.Lang::get('cms::lang.layout.menu_label').'</strong><br>
                '.$result['layouts'].'
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="popup">'.Lang::get('backend::lang.form.close').'</button>
            </div>
        ';
    }
}
