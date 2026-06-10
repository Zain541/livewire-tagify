<?php

namespace Codekinz\LivewireTagify\Traits;

use Illuminate\Support\Facades\Gate;
use Spatie\Tags\Tag;

trait AuthorizesTagOperations
{
    protected function allowsTagAction(string $action, ?Tag $tag = null, array $payload = []): bool
    {
        $permission = config("livewire-tagify.permissions.{$action}", true);

        if ($permission === false || ! $this->configuredPermissionAllows($permission, $tag, $payload)) {
            return false;
        }

        if ($gate = $this->configuredGate($action)) {
            return $this->gateAllows($gate, $tag, $payload);
        }

        return $this->tagPolicyAllows($action, $tag, $payload);
    }

    protected function configuredPermissionAllows(mixed $permission, ?Tag $tag, array $payload): bool
    {
        if (! is_string($permission) || $permission === '') {
            return true;
        }

        return $this->gateAllows($permission, $tag, $payload);
    }

    protected function configuredGate(string $action): ?string
    {
        $gate = config("livewire-tagify.permission_gates.{$action}");

        return is_string($gate) && $gate !== '' ? $gate : null;
    }

    protected function tagPolicyAllows(string $action, ?Tag $tag, array $payload): bool
    {
        $ability = $this->tagPolicyAbility($action);

        if (! $this->tagPolicyHasAbility($ability)) {
            return true;
        }

        return $this->gateAllows($ability, $tag, $payload);
    }

    protected function tagPolicyHasAbility(string $ability): bool
    {
        $policy = Gate::getPolicyFor(Tag::class);

        return $policy !== null && is_callable([$policy, $ability]);
    }

    protected function tagPolicyAbility(string $action): string
    {
        return [
            'create' => 'create',
            'read' => 'viewAny',
            'update' => 'update',
            'delete' => 'delete',
            'change_color' => 'update',
        ][$action] ?? $action;
    }

    protected function gateAllows(string $ability, ?Tag $tag, array $payload): bool
    {
        return Gate::inspect($ability, $this->tagOperationArguments($tag, $payload))->allowed();
    }

    protected function tagOperationArguments(?Tag $tag, array $payload): array
    {
        $subject = $tag ?? Tag::class;

        return [$subject, $this->modelCollection, $payload, $this->tagType];
    }
}
