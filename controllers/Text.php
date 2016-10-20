<?php namespace Indikator\Paste\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Indikator\Paste\Models\Text as Item;
use Flash;
use Lang;

class Text extends Controller
{
    public $implement = [
        'Backend.Behaviors.FormController',
        'Backend.Behaviors.ListController'
    ];

    public $formConfig = 'config_form.yaml';
    public $listConfig = 'config_list.yaml';

    public $requiredPermissions = ['indikator.paste'];

    public $bodyClass = 'compact-container';

    public function __construct()
    {
        parent::__construct();

        BackendMenu::setContext('Indikator.Paste', 'paste', 'text');
    }

    public function onActivate()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {
            foreach ($checkedIds as $itemId) {
                if (!$item = Item::where('status', 2)->find($itemId)) {
                    continue;
                }

                $item->update(['status' => 1]);
            }

            Flash::success(Lang::get('indikator.paste::lang.flash.activate'));
        }

        return $this->listRefresh();
    }

    public function onDeactivate()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {
            foreach ($checkedIds as $itemId) {
                if (!$item = Item::where('status', 1)->find($itemId)) {
                    continue;
                }

                $item->update(['status' => 2]);
            }

            Flash::success(Lang::get('indikator.paste::lang.flash.deactivate'));
        }

        return $this->listRefresh();
    }

    public function onRemove()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {
            foreach ($checkedIds as $itemId) {
                if (!$item = Item::find($itemId)) {
                    continue;
                }

                $item->delete();
            }

            Flash::success(Lang::get('indikator.paste::lang.flash.remove'));
        }

        return $this->listRefresh();
    }
}
