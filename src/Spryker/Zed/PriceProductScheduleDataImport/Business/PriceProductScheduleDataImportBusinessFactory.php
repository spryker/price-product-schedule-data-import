<?php

/**
 * MIT License
 * For full license information, please view the LICENSE file that was distributed with this source code.
 */

namespace Spryker\Zed\PriceProductScheduleDataImport\Business;

use Spryker\Zed\DataImport\Business\DataImportBusinessFactory;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\DataImportStepInterface;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\PriceProductScheduleWriterStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\AbstractSkuToIdProductAbstractStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\ConcreteSkuToIdProductStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\CurrencyToIdCurrencyStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\DateValidatorStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\PreparePriceDataStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\PriceProductScheduleListNameToIdStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\PriceTypeToIdPriceTypeStep;
use Spryker\Zed\PriceProductScheduleDataImport\Business\Model\Step\StoreNameToIdStoreStep;

/**
 * @method \Spryker\Zed\PriceProductScheduleDataImport\PriceProductScheduleDataImportConfig getConfig()
 */
class PriceProductScheduleDataImportBusinessFactory extends DataImportBusinessFactory
{
    /**
     * @return \Spryker\Zed\DataImport\Business\Model\DataImporterAfterImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterBeforeImportAwareInterface|\Spryker\Zed\DataImport\Business\Model\DataImporterInterface|\Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerAwareInterface
     */
    public function getPriceProductScheduleDataImporter()
    {
        /** @var \Spryker\Zed\DataImport\Business\Model\DataImporter $dataImporter */
        $dataImporter = $this->getCsvDataImporterFromConfig(
            $this->getConfig()->getPriceProductScheduleDataImporterConfiguration(),
        );

        /** @var \Spryker\Zed\DataImport\Business\Model\DataSet\DataSetStepBrokerTransactionAware $dataSetStepBroker */
        $dataSetStepBroker = $this->createTransactionAwareDataSetStepBroker();
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createDateValidatorStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createPriceProductScheduleListNameToIdStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createAbstractSkuToIdProductAbstractStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createConcreteSkuToIdProductStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createStoreNameToIdStoreStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createCurrencyToIdCurrencyStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createPriceTypeToIdPriceTypeStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createPreparePriceDataStep());
        $dataSetStepBroker = $dataSetStepBroker->addStep($this->createPriceProductScheduleWriterStep());

        $dataImporter = $dataImporter->addDataSetStepBroker($dataSetStepBroker);

        return $dataImporter;
    }

    public function createAbstractSkuToIdProductAbstractStep(): DataImportStepInterface
    {
        return new AbstractSkuToIdProductAbstractStep();
    }

    public function createConcreteSkuToIdProductStep(): DataImportStepInterface
    {
        return new ConcreteSkuToIdProductStep();
    }

    public function createStoreNameToIdStoreStep(): DataImportStepInterface
    {
        return new StoreNameToIdStoreStep();
    }

    public function createCurrencyToIdCurrencyStep(): DataImportStepInterface
    {
        return new CurrencyToIdCurrencyStep();
    }

    public function createPriceProductScheduleListNameToIdStep(): DataImportStepInterface
    {
        return new PriceProductScheduleListNameToIdStep($this->getConfig());
    }

    public function createPriceTypeToIdPriceTypeStep(): DataImportStepInterface
    {
        return new PriceTypeToIdPriceTypeStep();
    }

    public function createPreparePriceDataStep(): DataImportStepInterface
    {
        return new PreparePriceDataStep();
    }

    public function createPriceProductScheduleWriterStep(): DataImportStepInterface
    {
        return new PriceProductScheduleWriterStep();
    }

    public function createDateValidatorStep(): DataImportStepInterface
    {
        return new DateValidatorStep();
    }
}
