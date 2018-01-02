<?php

namespace Yoeunes\Larafast\Traits;

trait ViewTrait
{
    /** @var string */
    protected $view;

    /**
     * @param string $action
     *
     * @return string
     */
    public function getView(string $action): string
    {
        return view()->exists(session('uri')) ? session('uri') : 'admin.default.'.$action;
    }

    /**
     * @param string $view
     *
     * @return $this
     */
    public function setView(string $view)
    {
        $this->view = $view;

        return $this;
    }
}
