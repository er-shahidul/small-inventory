<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints AS Assert;

/**
 * History
 *
 * @ORM\Table(name="inv_histories")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\HistoryRepository")
 */
class History       // work order accept and product receive than add a row
{
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
     * @ORM\Column(name="QUANTITY", type="integer", length=11 , nullable=false)
     */
    protected $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="IN_OUT", type="string", length=255 , nullable=false)
     */
    protected $inOut;

    /**
     * @var string
     *
     * @ORM\Column(name="TYPE", type="string", length=255 , nullable=true)
     */
    protected $type; // pin or plastic

    /**
     * @var string
     *
     * @ORM\Column(name="WORK_ORDER", type="string", length=255 , nullable=true)
     */
    protected $workOrder;

    /**
     * @var string
     *
     * @ORM\Column(name="CHALLAN", type="string", length=255 , nullable=true)
     */
    protected $challan;

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
     * @return string
     */
    public function getInOut()
    {
        return $this->inOut;
    }

    /**
     * @param string $inOut
     */
    public function setInOut($inOut)
    {
        $this->inOut = $inOut;
    }

    /**
     * @return string
     */
    public function getWorkOrder()
    {
        return $this->workOrder;
    }

    /**
     * @param string $workOrder
     */
    public function setWorkOrder($workOrder)
    {
        $this->workOrder = $workOrder;
    }

    /**
     * @return string
     */
    public function getChallan()
    {
        return $this->challan;
    }

    /**
     * @param string $challan
     */
    public function setChallan($challan)
    {
        $this->challan = $challan;
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