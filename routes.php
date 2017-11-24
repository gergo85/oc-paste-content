<?php

Route::get('/indikator/paste/list.js', function()
{
    if (!BackendAuth::getUser()) {
        return response('Forbidden', 401);
    }

    $paste = '';

    if (BackendAuth::getUser()->hasAccess('indikator.paste.text')) {
        $texts = \Indikator\Paste\Models\Text::where('status', 1)->get()->all();
        foreach ($texts as $item) {
            $paste .= ',{name:"'.$item->name.'", code:"'.$item->code.'", type:1}';
        }
    }

    if (BackendAuth::getUser()->hasAccess('indikator.paste.code')) {
        $codes = \Indikator\Paste\Models\Code::where('status', 1)->get()->all();
        foreach ($codes as $item) {
            $paste .= ',{name:"'.$item->name.'", code:"'.$item->code.'", type:2}';
        }
    }

    return '$.oc.paste = ['.substr($paste, 1).']';
});
