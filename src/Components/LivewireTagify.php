<?php

namespace Codekinz\LivewireTagify\Components;

use Livewire\Component;

abstract class LivewireTagify extends Component
{
    abstract protected function configurations(): array;
}
