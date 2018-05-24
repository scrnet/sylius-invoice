<?php

namespace Behappy\InvoicePlugin\Repository;

use SomosduchaBundle\Entity\Product;
use SomosduchaBundle\Entity\ProductVariant;
use Sylius\Bundle\CoreBundle\Doctrine\ORM\ProductVariantRepository as BaseProductVariantRepository;

class OrderInvoiceRepository extends \Doctrine\ORM\EntityRepository
{
    public function findLastNumber()
    {
        $query = $this->createQueryBuilder('oi');
        $query->select( 'MAX(oi.number) AS max_number');
        $query->setMaxResults(1);

        return $query->getQuery()->getOneOrNullResult();
    }
}