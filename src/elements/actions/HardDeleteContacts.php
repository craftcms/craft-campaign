<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */
namespace putyourlightson\campaign\elements\actions;

use Craft;
use craft\elements\actions\Delete;
use craft\elements\db\ElementQueryInterface;

/**
 *
 *
 * @property-read string $triggerLabel
 */
class HardDeleteContacts extends Delete
{
    /**
     * @inheritdoc
     */
    public function getTriggerLabel(): string
    {
        return Craft::t('campaign', 'Delete permanently');
    }

    /**
     * @inheritdoc
     */
    public function getConfirmationMessage(): ?string
    {
        return Craft::t('campaign', 'Are you sure you want to permanently delete the selected contacts? This action cannot be undone.');
    }

    /**
     * @inheritdoc
     */
    public function performAction(ElementQueryInterface $query): bool
    {
        $elementsService = Craft::$app->getElements();

        foreach ($query->all() as $contact) {
            $elementsService->deleteElement($contact, true);
        }

        $this->setMessage(Craft::t('campaign', 'Contacts permanently deleted.'));

        return true;
    }
}
