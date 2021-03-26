<?php declare(strict_types=1);

namespace SwagShopFinder\Core\Content\ShopFinder;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\BoolField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\FkField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\LongTextField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\ManyToOneAssociationField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;
use Shopware\Core\System\Country\CountryDefinition;

class ShopFinderDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'swag_shop_finder';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getCollectionClass(): string
    {
        return ShopFinderCollection::class;
    }

    public function getEntityClass(): string
    {
        return ShopFinderEntity::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new Required(), new PrimaryKey()),
            new BoolField('active', 'active'),
            (new StringField('name', 'name'))->addFlags(new Required()),
            (new StringField('street', 'street'))->addFlags(new Required()),
            (new StringField('post_code', 'postCode'))->addFlags(new Required()),
            (new StringField('city', 'city'))->addFlags(new Required()),
            new StringField('url', 'url'),
            new StringField('phone', 'phone'),
            new LongTextField('open_times', 'openTimes'),

            new FkField('country_id', 'CountryId', CountryDefinition::class),
            new ManyToOneAssociationField(
                'country',
                'country_id',
                CountryDefinition::class,
                'id'
            )
        ]);
    }
}
