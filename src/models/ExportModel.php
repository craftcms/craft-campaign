<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\campaign\models;

use putyourlightson\campaign\Campaign;
use putyourlightson\campaign\elements\MailingListElement;

use Craft;
use craft\base\Model;

/**
 *
 * @property-read MailingListElement[] $mailingLists
 */
class ExportModel extends Model
{
    /**
     * @var string File path
     */
    public string $filePath;

    /**
     * @var array|null Mailing list IDs
     */
    public ?array $mailingListIds;

    /**
     * @var array|null Fields
     */
    public ?array $fields;

    /**
     * @var bool|null
     */
    public ?bool $subscribedDate;

    /**
     * @inheritdoc
     */
    public function attributeLabels(): array
    {
        $labels = parent::attributeLabels();

        // Set the field labels
        $labels['mailingListIds'] = Craft::t('campaign', 'Mailing Lists');

        return $labels;
    }

    /**
     * @inheritdoc
     */
    protected function defineRules(): array
    {
        return [
            [['filePath', 'mailingListIds'], 'required'],
            [['filePath'], 'string', 'max' => 255],
        ];
    }

    /**
     * Returns the mailing lists.
     *
     * @return MailingListElement[]
     */
    public function getMailingLists(): array
    {
        if ($this->mailingListIds === null) {
            return [];
        }

        $mailingLists = [];

        foreach ($this->mailingListIds as $mailingListId) {
            $mailingLists[] = Campaign::$plugin->mailingLists->getMailingListById($mailingListId);
        }

        return $mailingLists;
    }
}
