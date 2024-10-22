<?php

namespace Okipa\LaravelTable\Traits\Column;

use Okipa\LaravelTable\Column;

trait IsHidden
{
    protected bool $is_hidden = false;

    public function hidden(bool $hidden = true): Column
    {
        $this->is_hidden = $hidden;

        /** @var \Okipa\LaravelTable\Column $this */
        return $this;
    }
}
