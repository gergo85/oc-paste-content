<?php namespace Indikator\Paste\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Indikator\Paste\Models\BlockCategory as Item;
use Indikator\Paste\Models\Block;
use Flash;
use Lang;
use File;
use Cms\Classes\Theme;

class BlockCategory extends Controller
{
    public $implement = [
        \Backend\Behaviors\FormController::class,
        \Backend\Behaviors\ListController::class
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

                Block::where('category', $itemId)->update(['category' => 0]);
            }

            Flash::success(Lang::get('indikator.content::lang.flash.remove'));
        }

        return $this->listRefresh();
    }

    public function onShowCode()
    {
        $this->vars['id']   = post('id');
        $this->vars['code'] = post('code');

        return $this->makePartial('show_code');
    }

    public function onShowStat()
    {
        $types = ['pages', 'partials', 'layouts'];

        foreach ($types as $type) {
            $items[$type]  = [];
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

        $result['blocks'] = '';
        $items = Block::where('category', post('id'))->orderBy('sort_order', 'asc')->get();

        foreach ($items as $item) {
            $result['blocks'] .= $item->name.'<br>';
        }

        if ($result['blocks'] == '') {
            $result['blocks'] = '<em>'.Lang::get('indikator.paste::lang.popup.none').'</em><br>';
        }

        $this->vars['pages']    = $result['pages'];
        $this->vars['partials'] = $result['partials'];
        $this->vars['layouts']  = $result['layouts'];
        $this->vars['blocks']   = $result['blocks'];

        return $this->makePartial('show_stat');
    }
}
