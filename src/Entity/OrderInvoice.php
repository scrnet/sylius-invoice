<?php


namespace Behappy\InvoicePlugin\Entity;

use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Order\Model\OrderInterface;
use Sylius\Component\Order\Model\Order;
/**
 * ProductPriceModifier
 *
 * @ORM\Table(name="behappy_orderinvoice")
 * @ORM\Entity(repositoryClass="Behappy\InvoicePlugin\Repository\OrderInvoiceRepository")
 */
class OrderInvoice
{


    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var OrderInterface
     *
     * @ORM\ManyToOne(targetEntity="Sylius\Component\Order\Model\Order")
     * @ORM\JoinColumn(name="order_id", referencedColumnName="id")
     */
    protected $order;

    /**
     * @var integer
     *
     * @ORM\Column(name="number", type="integer")
     */
    protected $number;



    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set order.
     *
     * @param \Sylius\Component\Order\Model\Order|null $order
     *
     * @return OrderInvoice
     */
    public function setOrder(\Sylius\Component\Order\Model\Order $order = null)
    {
        $this->order = $order;

        return $this;
    }

    /**
     * Get order.
     *
     * @return \Sylius\Component\Order\Model\Order|null
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * Set number.
     *
     * @param int $number
     *
     * @return OrderInvoice
     */
    public function setNumber($number)
    {
        $this->number = $number;

        return $this;
    }

    /**
     * Get number.
     *
     * @return int
     */
    public function getNumber()
    {
        return $this->number;
    }
}
