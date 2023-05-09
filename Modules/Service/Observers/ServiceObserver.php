<?php

namespace Modules\Service\Observers;

use Modules\Service\Models\Service;
use Illuminate\Support\Str;

class ServiceObserver
{
    public function creating(Service $service): void
    {
        if ($service->alias === null)
            $service->alias = Str::slug($service->name);

        if ($service->url === null && $service->type != null)
            $service->url = '/' . Str::slug($service->name);
    }
}
