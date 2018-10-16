<?php namespace Indikator\Paste\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Indikator\Paste\Models\Block as Item;
use Flash;
use Lang;
use File;
use Cms\Classes\Theme;

class Block extends Controller
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

    public function onActivate()
    {
        if ($this->isSelected()) {
            $this->changeStatus(post('checked'), 2, 1);
            $this->setMessage('activate');
        }

        return $this->listRefresh();
    }

    public function onDeactivate()
    {
        if ($this->isSelected()) {
            $this->changeStatus(post('checked'), 1, 2);
            $this->setMessage('deactivate');
        }

        return $this->listRefresh();
    }

    public function onRemove()
    {
        if ($this->isSelected()) {
            foreach (post('checked') as $itemId) {
                if (!$item = Item::whereId($itemId)) {
                    continue;
                }

                $item->delete();
            }

            $this->setMessage('remove');
        }

        return $this->listRefresh();
    }

    /**
     * @return bool
     */
    private function isSelected()
    {
        return ($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds);
    }

    /**
     * @param $action
     */
    private function setMessage($action)
    {
        Flash::success(Lang::get('indikator.paste::lang.flash.'.$action));
    }

    /**
     * @param $post
     * @param $from
     * @param $to
     */
    private function changeStatus($post, $from, $to)
    {
        foreach ($post as $itemId) {
            if (!$item = Item::where('status', $from)->whereId($itemId)) {
                continue;
            }

            $item->update(['status' => $to]);
        }
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

                if (substr_count($content, 'item.id == '.post('id')) > 0 || substr_count($content, "item.id == '".post('code')."'") || substr_count($content, 'item.id == "'.post('code').'"') > 0) {
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

        $this->vars['pages']    = $result['pages'];
        $this->vars['partials'] = $result['partials'];
        $this->vars['layouts']  = $result['layouts'];

        return $this->makePartial('show_stat');
    }
}
