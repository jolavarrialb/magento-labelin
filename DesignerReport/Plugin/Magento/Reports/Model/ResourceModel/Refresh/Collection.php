<?php

declare(strict_types=1);

namespace Labelin\DesignerReport\Plugin\Magento\Reports\Model\ResourceModel\Refresh;

use Labelin\DesignerReport\Model\Flag;
use Magento\Framework\Data\Collection as MagentoCollection;
use Magento\Framework\Data\Collection\EntityFactory;
use Magento\Framework\DataObject;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Reports\Model\FlagFactory;

class Collection extends MagentoCollection
{
    /** @var TimezoneInterface */
    protected $localeDate;

    /** @var FlagFactory */
    protected $reportsFlagFactory;

    public function __construct(
        EntityFactory $entityFactory,
        TimezoneInterface $localeDate,
        FlagFactory $reportsFlagFactory
    ) {
        parent::__construct($entityFactory);
        $this->localeDate = $localeDate;
        $this->reportsFlagFactory = $reportsFlagFactory;
    }

    public function afterLoadData(MagentoCollection $subject, $result): MagentoCollection
    {
        if ($this->_items) {
            return $subject;
        }

        $data = [
            [
                'id' => 'designerreport',
                'report' => __('Designer Report'),
                'comment' => __('Designer Report'),
                'updated_at' => $this->getUpdatedAt(Flag::REPORT_DESIGNER_FLAG_CODE),
            ],
        ];

        foreach ($data as $value) {
            $item = new DataObject();
            $item->setData($value);
            $this->addItem($item);
            $subject->addItem($item);
        }

        return $subject;
    }

    protected function getUpdatedAt(string $reportCode): string
    {
        $flag = $this->reportsFlagFactory
            ->create()
            ->setReportFlagCode($reportCode)
            ->loadSelf();

        return $flag->hasData() ? $flag->getLastUpdate() : '';
    }
}
