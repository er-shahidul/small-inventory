<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints AS Assert;

/**
 * WorkOrder
 *
 * @ORM\Table(name="inv_work_orders")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\WorkOrderRepository")
 */
class WorkOrder     // work order place than add row 
{
    /**
     * @ORM\Id
     * @ORM\Column(name="ID", type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="WORK_ORDER_NO", type="string", length=255 , nullable=true)
     */
    protected $workOrderNo;

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
     * @ORM\Column(name="BATCH_NO", type="string", length=255 , nullable=true)
     */
    protected $batchNo;

    /**
     * @var string
     *
     * @ORM\Column(name="STATUS", type="string", length=255 , nullable=true)
     */
    protected $status; // accept by itcl
    
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
    public function getBatchNo()
    {
        return $this->batchNo;
    }

    /**
     * @param string $batchNo
     */
    public function setBatchNo($batchNo)
    {
        $this->batchNo = $batchNo;
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

    /**
     * @return string
     */
    public function getWorkOrderNo()
    {
        return $this->workOrderNo;
    }

    /**
     * @param string $workOrderNo
     */
    public function setWorkOrderNo($workOrderNo)
    {
        $this->workOrderNo = $workOrderNo;
    }

    /**
     * @return string
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param string $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }
}