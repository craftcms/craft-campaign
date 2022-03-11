<?php
/**
 * @copyright Copyright (c) PutYourLightsOn
 */

namespace putyourlightson\campaign\records;

use craft\db\ActiveQuery;
use craft\records\Element;
use craft\records\User;
use DateTime;
use putyourlightson\campaign\base\BaseActiveRecord;

/**
 * @property int $id ID
 * @property int|null $userId User ID
 * @property string $cid Contact ID
 * @property string $email Email
 * @property string $country Country
 * @property string $geoIp GeoIP
 * @property string $device Device
 * @property string $os OS
 * @property string $client Client
 * @property DateTime|null $lastActivity Last activity
 * @property DateTime|null $verified Verified
 * @property DateTime|null $complained Complained
 * @property DateTime|null $bounced Bounced
 * @property DateTime|null $blocked Blocked
 * @property-read ActiveQuery $element
 * @property-read ActiveQuery $user
 */
class ContactRecord extends BaseActiveRecord
{
    /**
     * @var null|int
     */
    public ?int $count = null;

    /**
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return '{{%campaign_contacts}}';
    }

    /**
     * @inheritdoc
     */
    public static function find(): ActiveQuery
    {
        return parent::find()
            ->innerJoinWith(['element element'])
            ->where(['element.dateDeleted' => null]);
    }

    /**
     * Returns the related element.
     */
    public function getElement(): ActiveQuery
    {
        return $this->hasOne(Element::class, ['id' => 'id']);
    }

    /**
     * Returns the related user record.
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'userId']);
    }
}
