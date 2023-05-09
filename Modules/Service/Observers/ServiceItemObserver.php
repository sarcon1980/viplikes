<?php

namespace Modules\Service\Observers;

use Modules\Service\Models\ServiceItem;

class ServiceItemObserver
{
    public function creating(ServiceItem $serviceItem): void
    {
        $serviceItem->position = ServiceItem::query()
                ->where('service_id', '=', $serviceItem->service_id)
                ->max('position') + 1;
    }
}
