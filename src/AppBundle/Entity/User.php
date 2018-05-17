<?php
/**
 * Created by PhpStorm.
 * User: Oriane.NJILIE_ADAMOU
 * Date: 04/05/2018
 * Time: 13:55
 */

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use FOS\UserBundle\Model\User as FosUser;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends FosUser
{
    
    /**
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    /**
     * @ORM\Column(length=100)
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Assert\Length(max=100)
     */
    protected $color;

    /**
     * @ORM\Column()
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     *
     */
    protected  $race;


    /**
     * @ORM\Column(type="integer")
     * @Assert\Type("integer")
     * @Assert\NotNull()
     * @Assert\Length(max=255)
     */
    protected  $age;


    /**
     * @ORM\Column()
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     *
     */
    protected  $family;

    /**
     * @ORM\Column()
     * @Assert\NotNull()
     * @Assert\Type("string")
     * @Assert\Length(max=255)
     *
     */
    protected  $food;

    /**
     * @ORM\ManyToMany(targetEntity="User")
     *  @JoinTable(name="friends",
     *     joinColumns={@JoinColumn(name="user_a_id", referencedColumnName="id")},
     *     inverseJoinColumns={@JoinColumn(name="user_b_id", referencedColumnName="id")}
     * )
     *  @var \Doctrine\Common\Collections\ArrayCollection
     */
    protected $friends;



    public function __construct()
    {
        parent::__construct();
        $this->friends = new ArrayCollection();
		$this->food = 'default';
		$this->color = 'default';
		$this->family = 'default';
		$this->age = 0;
		$this->race= 'default';
        
    }

    public function getId()
    {
        return $this->id;
    }

    public function setUsername($username)
    {
        parent:: setUsername($username);
        //parent:: setEmail($username);
        //parent:: setPassword($username);
        
    }
    public function getColor()
    {
        return $this->color;
    }
    public function setColor($color)
    {
        $this->color = $color;
    }


    public function getRace()
    {
        return $this->race;
    }
    public function setRace($race)
    {
        $this->race = $race;
    }


    public function getAge()
    {
        return $this->age;
    }
    public function setAge($age)
    {
        $this->age = $age;
    }


    public function getFamily()
    {
        return $this->family;
    }
    public function setFamily($family)
    {
        $this->family = $family;
    }


    public function getFood()
    {
        return $this->food;
    }
    public function setFood($food)
    {
        $this->food = $food;
    }

    /**
     * @return array
     */
    public function getFriends()
    {
        return $this->friends;
    }

    /**
     * @param  User $user
     * @return void
     */

    public function removeFriends(User $user)
    {
        if ($this->friends->contains($user)) {
            $this->friends->removeElement($user);
            $user->removeFriends($this);
        }
        return $this;
    }
   /**
     * @param  User $user
     * @return void
     */
    public function addFriends(User $user)
    {
        if (!$this->friends->contains($user)) {
            $this->friends->add($user);
            $user->addFriends($this);
        }
        return $this;
    }



}