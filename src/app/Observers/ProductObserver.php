<?php

namespace App\Observers;

use App\Models\Creator;
use App\Models\Template;

class ProductObserver
{
    /**
     * Handle the Creator "created" event.
     *
     * @param  \App\Models\Creator  $creator
     * @return void
     */
    public function created(Creator $creator)
    {
        $template = Template::find($creator->TemplateID);
        $template->update([
            'usedTimes' => $template->usedTimes +1
        ]);
    }


}
