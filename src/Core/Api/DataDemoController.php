<?php declare(strict_types=1);

namespace SwagShopFinder\Core\Api;

use Faker\Factory;
use Shopware\Core\Framework\Context;

use Shopware\Core\Framework\DataAbstractionLayer\EntityRepositoryInterface;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Criteria;
use Shopware\Core\Framework\DataAbstractionLayer\Search\Filter\EqualsFilter;
use Shopware\Core\Framework\Uuid\Uuid;
use Shopware\Core\System\Country\CountryEntity;
use Shopware\Core\System\Country\Exception\CountryNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Shopware\Core\Framework\Routing\Annotation\RouteScope;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @RouteScope(scopes={"api"})
 */
class DataDemoController extends AbstractController
{
    /**
     * @var EntityRepositoryInterface
     */
    private $countryRepository;

    /**
     * @var EntityRepositoryInterface
     */
    private $shopFinderRepository;

    public function __construct(EntityRepositoryInterface $countryRepository, EntityRepositoryInterface $shopFinderRepository)
    {
        $this->countryRepository = $countryRepository;
        $this->shopFinderRepository = $shopFinderRepository;
    }

    /**
     * @Route("/api/v{version}/_action/swag-shop-finder/generate", name="api.custom.swag_shop_finder.generate", methods={"POST"})
     * @return Response
     */
    public function generate(Context $context): Response
    {
        $faker = Factory::create();
        $country = $this->getActiveCountry($context);

        $data = [];

        for ($i = 0; $i < 50; $i++) {
            $data[] = [
                'id' => Uuid::randomHex(),
                'active' => true,
                'name' => $faker->name,
                'street' => $faker->streetAddress,
                'postCode' => $faker->postcode,
                'city' => $faker->city,
                'country_id' => $country->getId(),
            ];
        }

        $this->shopFinderRepository->create($data, $context);

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    /**
     * @param Context $context
     * @return CountryEntity
     * @throw CountryNotFoundException
     */
    public function getActiveCountry(Context $context): CountryEntity
    {
        $criteria = new Criteria();
        $criteria->addFilter(new EqualsFilter('active', '1'));
        $criteria->setLimit(1);

        $country = $this->countryRepository->search($criteria, $context)->getEntities()->first();

        if ($country === null) {
            throw new CountryNotFoundException('');
        }

        return $country;
    }
}
