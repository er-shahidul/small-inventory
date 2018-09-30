<?php
namespace AppBundle\Entity;

use AppBundle\Traits\BlameableEntity;
use AppBundle\Traits\TimestampableEntity;
use Doctrine\ORM\Mapping as ORM;
use AppBundle\Traits\SoftDeleteableEntity;
use Symfony\Component\Validator\Constraints AS Assert;

/**
 * Inventory
 *
 * @ORM\Table(name="inv_inventories")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\InventoryRepository")
 */
class Inventory     // work order accept and product receive than modify field
{
    use BlameableEntity, TimestampableEntity, SoftDeleteableEntity;

    /**
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var Institution
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Institution")
     * @ORM\JoinColumn(name="INSTITUTIONS", referencedColumnName="ID")
     */
    protected $institution;

    /**
     * @var Product
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Product")
     * @ORM\JoinColumn(name="PRODUCTS", referencedColumnName="ID")
     */
    protected $product;

    /**
     * @var integer
     *
     * @ORM\Column(name="QUANTITY", type="integer", length=11 , nullable=true)
     */
    protected $quantity;

    /**
     * @var integer
     *
     * @ORM\Column(name="ON_HAND", type="integer", length=11 , nullable=true)
     */
    protected $onHand;

    /**
     * @var integer
     *
     * @ORM\Column(name="ON_LOCK", type="integer", length=11 , nullable=true)
     */
    protected $onLock;

    /**
     * @var string
     *
     * @ORM\Column(name="TYPE", type="string", length=255 , nullable=true)
     */
    protected $type; // pin or plastic

    /**
     * @var string
     *
     * @ORM\Column(name="NOTE", type="string", length=512 , nullable=true)
     */
    protected $note;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Institution
     */
    public function getInstitution()
    {
        return $this->institution;
    }

    /**
     * @param Institution $institution
     */
    public function setInstitution($institution)
    {
        $this->institution = $institution;
    }

    /**
     * @return Product
     */
    public function getProduct()
    {
        return $this->product;
    }

    /**
     * @param Product $product
     */
    public function setProduct($product)
    {
        $this->product = $product;
    }

    /**
     * @return integer
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param integer $quantity
     */
    public function setQuantity($quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return integer
     */
    public function getOnHand()
    {
        return $this->onHand;
    }

    /**
     * @param integer $onHand
     */
    public function setOnHand($onHand)
    {
        $this->onHand = $onHand;
    }

    /**
     * @return integer
     */
    public function getOnLock()
    {
        return $this->onLock;
    }

    /**
     * @param integer $onLock
     */
    public function setOnLock($onLock)
    {
        $this->onLock = $onLock;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    /**
     * @param string $note
     */
    public function setNote($note)
    {
        $this->note = $note;
    }
}