<?php

namespace Yoeunes\Larafast\Traits;

trait ExcelTrait
{
    /** @var array */
    protected $excelAttributes = [];

    /**
     * @return array
     */
    public function getExcelAttributes(): array
    {
        return ! empty($this->excelAttributes)
            ? $this->excelAttributes
            : array_fill_keys(array_keys(array_flip($this->fillable)), null);
    }

    /**
     * @param array $excelAttributes
     *
     * @return $this
     */
    public function setExcelAttributes(array $excelAttributes = [])
    {
        $this->excelAttributes = $excelAttributes;

        return $this;
    }
}
