<?php

namespace App\Entity;

use App\Repository\ContactRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ContactRepository::class)]
class Contact
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $lname = null;

    #[ORM\Column(length: 255)]
    private ?string $fname = null;

    #[ORM\Column(length: 255)]
    private ?string $tel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $mail = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $photo = null;

    #[ORM\ManyToMany(targetEntity: Group::class, inversedBy: 'contacts')]
    private Collection $contactGroup;

    #[ORM\OneToMany(mappedBy: 'contact', targetEntity: AddFields::class)]
    private Collection $addFields;

    public function __construct()
    {
        $this->contactGroup = new ArrayCollection();
        $this->addFields = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLname(): ?string
    {
        return $this->lname;
    }

    public function setLname(string $lname): self
    {
        $this->lname = $lname;

        return $this;
    }

    public function getFname(): ?string
    {
        return $this->fname;
    }

    public function setFname(string $fname): self
    {
        $this->fname = $fname;

        return $this;
    }

    public function getTel(): ?string
    {
        return $this->tel;
    }

    public function setTel(string $tel): self
    {
        $this->tel = $tel;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(?string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getContactGroup(): Collection
    {
        return $this->contactGroup;
    }

    public function addContactGroup(Group $contactGroup): self
    {
        if (!$this->contactGroup->contains($contactGroup)) {
            $this->contactGroup->add($contactGroup);
        }

        return $this;
    }

    public function removeContactGroup(Group $contactGroup): self
    {
        $this->contactGroup->removeElement($contactGroup);

        return $this;
    }

    /**
     * @return Collection<int, AddFields>
     */
    public function getAddFields(): Collection
    {
        return $this->addFields;
    }

    public function addAddField(AddFields $addField): self
    {
        if (!$this->addFields->contains($addField)) {
            $this->addFields->add($addField);
            $addField->setContact($this);
        }

        return $this;
    }

    public function removeAddField(AddFields $addField): self
    {
        if ($this->addFields->removeElement($addField)) {
            // set the owning side to null (unless already changed)
            if ($addField->getContact() === $this) {
                $addField->setContact(null);
            }
        }

        return $this;
    }

    
}
