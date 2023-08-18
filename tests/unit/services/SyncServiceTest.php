<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\campaigntests\unit\services;

use Craft;
use craft\elements\User;
use craft\models\UserGroup;
use putyourlightson\campaign\Campaign;
use putyourlightson\campaign\elements\ContactElement;
use putyourlightson\campaign\elements\MailingListElement;
use putyourlightson\campaigntests\fixtures\MailingListsFixture;
use putyourlightson\campaigntests\unit\BaseUnitTest;

/**
 * @since 2.8.4
 */
class SyncServiceTest extends BaseUnitTest
{
    public function _fixtures(): array
    {
        return [
            'mailingLists' => [
                'class' => MailingListsFixture::class,
            ],
        ];
    }

    /**
     * @var MailingListElement
     */
    protected MailingListElement $mailingList;

    protected function _before(): void
    {
        parent::_before();

        $this->mailingList = MailingListElement::find()->one();

        Craft::$app->setEdition(Craft::Pro);
        Campaign::$plugin->edition = Campaign::EDITION_PRO;
        Campaign::$plugin->sync->registerUserEvents();
    }

    public function testSyncUser(): void
    {
        $userGroup = new UserGroup([
            'name' => 'My User Group',
            'handle' => 'myUserGroup',
        ]);
        $this->assertTrue(Craft::$app->userGroups->saveGroup($userGroup));

        $this->mailingList->syncedUserGroupId = $userGroup->id;
        $this->assertTrue(Craft::$app->elements->saveElement($this->mailingList));

        $email = 'syncuser@test.com';
        $user = new User([
            'active' => true,
            'username' => 'syncuser',
            'email' => $email,
        ]);

        $this->assertTrue(Craft::$app->elements->saveElement($user));
        $this->assertFalse(ContactElement::find()->email($email)->exists());

        $this->assertTrue(Craft::$app->users->assignUserToGroups($user->id, [$userGroup->id]));
        $contact = ContactElement::find()->email($email)->one();
        $this->assertNotNull($contact);

        $mailingLists = $contact->getSubscribedMailingLists();
        $mailingListIds = collect($mailingLists)->pluck('id')->all();
        $this->assertEquals([$this->mailingList->id], $mailingListIds);
    }
}