<?php

namespace Yoeunes\Larafast\Traits;

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
}
