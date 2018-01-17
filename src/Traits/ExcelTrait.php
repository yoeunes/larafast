<?php

namespace Yoeunes\Larafast\Traits;

trait ExcelTrait
{
    /** @var array */
    protected $excelAttributes = [];

    /** @var array  */
    protected $excelAttributesCasting = [];

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

    /**
     * @return array
     */
    public function getExcelAttributesCasting(): array
    {
        return $this->excelAttributesCasting;
    }

    /**
     * @param array $excelAttributesCasting
     *
     * @return $this
     */
    public function setExcelAttributesCasting(array $excelAttributesCasting = [])
    {
        $this->excelAttributesCasting = $excelAttributesCasting;

        return $this;
    }
}
