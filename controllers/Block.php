<?php namespace Indikator\Paste\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use Indikator\Paste\Models\Block as Item;
use Flash;
use Lang;

class Block extends Controller
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

    public function onActivate()
    {
        if (($checkedIds = post('checked')) && is_array($checkedIds) && count($checkedIds)) {
            foreach ($checkedIds as $itemId) {
                if (!$item = Item::where('status', 2)->whereId($itemId)) {
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
                if (!$item = Item::where('status', 1)->whereId($itemId)) {
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
                if (!$item = Item::whereId($itemId)) {
                    continue;
                }

                $item->delete();
            }

            Flash::success(Lang::get('indikator.paste::lang.flash.remove'));
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
    {% if item.id == '.post('id').' %}
    
    '.Lang::get('indikator.paste::lang.popup.2step_code').'

    {% endif %}
{% endfor %}
                    </pre>
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
                     <strong>{{ item.image|media }}</strong> - '.Lang::get('indikator.paste::lang.form.image').'
                </div>
                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="popup">'.Lang::get('backend::lang.form.close').'</button>
            </div>
        ';
    }
}
