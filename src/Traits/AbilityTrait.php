<?php

namespace Yoeunes\Larafast\Traits;

use Yoeunes\Larafast\Policies\Policy;

trait AbilityTrait
{
    protected $abilityMap = [];

    protected $defaultAbilities = [
        'index'         => 'view',
        'show'          => 'view',
        'excelDownload' => 'view',

        'create'        => 'create',
        'store'         => 'create',
        'excelCreate'   => 'create',
        'excelStore'    => 'create',

        'edit'          => 'update',
        'update'        => 'update',
        'activate'      => 'update',
        'deactivate'    => 'update',

        'destroy'       => 'delete',
    ];

    public function getAbilityMap()
    {
        return array_merge($this->defaultAbilities, $this->abilityMap);
    }

    /**
     * @param array $abilityMap
     *
     * @return $this
     */
    public function setAbilityMap(array $abilityMap)
    {
        $this->abilityMap = $abilityMap;

        return $this;
    }

    public function getPermission(string $method)
    {
        return array_key_exists($method, $map = $this->getAbilityMap()) ? $map[$method] : $method;
    }

    /**
     * @param string $function
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public function allow(string $function)
    {
        if (is_a($this->getPolicy(), Policy::class, true)) {
            $this->authorize($this->getPermission($function), $this->entityName());
        }
    }

    /**
     * Authorize a given action for the current user.
     *
     * @param  mixed  $ability
     * @param  mixed|array  $arguments
     * @return \Illuminate\Auth\Access\Response
     *
     * @throws \Illuminate\Auth\Access\AuthorizationException
     */
    public abstract function authorize($ability, $arguments = []);
}
