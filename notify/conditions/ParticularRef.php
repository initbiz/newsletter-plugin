<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Notify\Conditions;

use Lang;
use RainLab\Notify\Classes\ConditionBase;

class ParticularRef extends ConditionBase
{
    public function getConditionType()
    {
        // If the condition should appear only for some events
        return ConditionBase::TYPE_LOCAL;
    }

    public function getName()
    {
        return Lang::get('initbiz.newsletter::lang.particular_ref_condition.name');
    }

    public function getTitle()
    {
        return Lang::get('initbiz.newsletter::lang.particular_ref_condition.name');
    }

    public function getText()
    {
        return Lang::get('initbiz.newsletter::lang.particular_ref_condition.text', ['ref' => $this->host->ref]);
    }

    public function isTrue(&$params)
    {
        return $params['ref'] === $this->host->ref;
    }
}
