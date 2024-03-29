<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step;

use Orm\Zed\Currency\Persistence\Map\SpyCurrencyTableMap;
use Orm\Zed\Currency\Persistence\SpyCurrencyQuery;
use Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\DataSet\PriceProductScheduleDataSetInterface;

class CurrencyToIdCurrencyStep implements DataImportStepInterface
{
    /**
     * @var string
     */
    protected const EXCEPTION_MESSAGE = 'Could not find currency by code "%s"';

    /**
     * @var array<int>
     */
    protected $idCurrencyCache = [];

    /**
     * @param \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetInterface $dataSet
     *
     * @throws \Spryker\Zed\DataImport\Business\Exception\EntityNotFoundException
     *
     * @return void
     */
    public function execute(DataSetInterface $dataSet): void
    {
        $currencyCode = $dataSet[PriceProductScheduleDataSetInterface::KEY_CURRENCY];
        if (!isset($this->idCurrencyCache[$currencyCode])) {
            /** @var int|null $idCurrency */
            $idCurrency = $this->createProductQuery()
                ->select(SpyCurrencyTableMap::COL_ID_CURRENCY)
                ->findOneByCode($currencyCode);

            if ($idCurrency === null) {
                throw new EntityNotFoundException(sprintf(static::EXCEPTION_MESSAGE, $currencyCode));
            }

            $this->idCurrencyCache[$currencyCode] = $idCurrency;
        }

        $dataSet[PriceProductScheduleDataSetInterface::FK_CURRENCY] = $this->idCurrencyCache[$currencyCode];
    }

    /**
     * @return \Orm\Zed\Currency\Persistence\SpyCurrencyQuery
     */
    protected function createProductQuery(): SpyCurrencyQuery
    {
        return SpyCurrencyQuery::create();
    }
}
