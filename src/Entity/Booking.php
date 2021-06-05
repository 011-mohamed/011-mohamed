<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\BookingRepository;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Constraints\GreaterThan;

/**
 * @ORM\Entity(repositoryClass=BookingRepository::class)
 * @ORM\HaslifecycleCallbacks()
 */
class Booking
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $booker;

    /**
     * @ORM\ManyToOne(targetEntity=Ad::class, inversedBy="bookings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ad;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan("today",message ="La date d'arrivée doit etre ulterieure a la date d'aujourd'hui !")
     *
     */
    private $startDate;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\GreaterThan(propertyPath="endDate",message ="La date de départ  doit etre plus éloignée que la date d'arrivée !")
     */
    private $endDate;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="float")
     */
    private $amount;

    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     */
    private $comment;
    
    /**
     * Callback appelé a chaque fois qu'on crée une réservation
     * 
     *@ORM\PrePersist
     * @return void
     */
    public function prePersist(){
        if(empty($this->createdAt)){
            $this->createdAt = new \DateTime();
        }
        if (empty($this->amount)) {
            $this->amount = $this->ad->getPrice() * $this->getDuration();
        }
    }

    public function isBookableDates(){
        // 1) il faut connaitre les dates qui sont impopssibles pour l'annonce
        $notAvailableDays = $this-> ad-> getNotAvailableDays();
        // 2) il faut comparer les date choisies avec les dates impossibles
        $bookingDays = $this->getDays();

        // tableaux de chaines de caracteres de mes journées
        $days = array_map(function($day){
            return $day->format('Y-m-d');

        }, $bookingDays);

        $notAvailable = array_map(function($day){
            return $day->format('Y-m-d');
        }, $notAvailableDays);

        foreach($days as $day){
            if(array_search($day, $notAvailable) !== false) return false;
        }
        return true ;

    }


    /**
     * permet de recuperer un tableau des journées qui correspondent a ma reservation 
     *
     * @return array Un tableau d'objets DateTime representant les jours de la reservation
     */
    public function getDays(){
      
      
            //Calculer les jours qui se trouvent entre la date d'arrivée et de depart
            $resultat = range(
                $this->getStartDate()->getTimestamp(),
                $this->getEndDate()->getTimestamp(),
                24 * 60 * 60 
            );

            $days = array_map(function($dayTimestamp){
                return new \DateTime(date('y-m-d', $dayTimestamp));
            }, $resultat);

            return $days;
        }
        
    

    public function getDuration(){
        $diff = $this->endDate->diff($this->startDate);
        return $diff->days;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBooker(): ?User
    {
        return $this->booker;
    }

    public function setBooker(?User $booker): self
    {
        $this->booker = $booker;

        return $this;
    }

    public function getAd(): ?Ad
    {
        return $this->ad;
    }

    public function setAd(?Ad $ad): self
    {
        $this->ad = $ad;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getAmount(): ?float
    {
        return $this->amount;
    }

    public function setAmount(float $amount): self
    {
        $this->amount = $amount;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }
}
