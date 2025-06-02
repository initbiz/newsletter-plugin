<?php

declare(strict_types=1);

namespace Initbiz\Newsletter\Notify\Conditions;

use Lang;
use Initbiz\Newsletter\Models\Tag;
use RainLab\Notify\Classes\ConditionBase;

class ParticularTag extends ConditionBase
{
    public function getConditionType()
    {
        // If the condition should appear only for some events
        return ConditionBase::TYPE_LOCAL;
    }

    public function getName()
    {
        return Lang::get('initbiz.newsletter::lang.particular_tag_condition.name');
    }

    public function getTitle()
    {
        return Lang::get('initbiz.newsletter::lang.particular_tag_condition.name');
    }

    public function getText()
    {
        $selectedTags = $this->host->tags ?? [];
        $tags = Tag::whereIn('slug', $selectedTags)->pluck('name')->toArray();
        return Lang::get('initbiz.newsletter::lang.particular_tag_condition.text', [
            'tags' => implode(', ', $tags)
        ]);
    }

    public function isTrue(&$params)
    {
        $validTags = $this->host->tags;
        foreach ($params['tags'] as $tag) {
            if (in_array($tag->slug, $validTags)) {
                return true;
            }
        }

        return false;
    }

    public function getTagsOptions(): array
    {
        return Tag::pluck('name', 'slug')->toArray();
    }
}
