<?php

namespace Codekinz\LivewireTagify\Traits;

use Illuminate\Support\Facades\Gate;
use Spatie\Tags\Tag;

trait AuthorizesTagOperations
{
    protected function canPerformTagOperation(string $operation, ?Tag $tag = null, array $payload = []): bool
    {
        $permission = config("livewire-tagify.permissions.{$operation}", true);

        if ($permission === false) {
            return false;
        }

        if (is_string($permission) && $permission !== '' && ! Gate::allows($permission, $this->tagOperationArguments($operation, $tag, $payload))) {
            return false;
        }

        $gate = config("livewire-tagify.permission_gates.{$operation}");

        if (is_string($gate) && $gate !== '') {
            return Gate::allows($gate, $this->tagOperationArguments($operation, $tag, $payload));
        }

        return $this->tagPolicyAllows($operation, $tag, $payload);
    }

    protected function tagPolicyAllows(string $operation, ?Tag $tag, array $payload): bool
    {
        $policy = Gate::getPolicyFor(Tag::class);

        if ($policy === null) {
            return true;
        }

        $ability = $this->tagPolicyAbility($operation);

        if (! is_callable([$policy, $ability])) {
            return true;
        }

        return Gate::allows($ability, $this->tagOperationArguments($operation, $tag, $payload));
    }

    protected function tagPolicyAbility(string $operation): string
    {
        return [
            'create' => 'create',
            'read' => 'viewAny',
            'update' => 'update',
            'delete' => 'delete',
            'change_color' => 'update',
        ][$operation] ?? $operation;
    }

    protected function tagOperationArguments(string $operation, ?Tag $tag, array $payload): array
    {
        $subject = $tag ?? Tag::class;

        return [$subject, $this->modelCollection, $payload, $this->tagType];
    }
}
