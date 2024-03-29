<?php

namespace App\entities;

use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\InverseJoinColumn;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use http\Client\Curl\User;

#[ORM\Entity]
#[ORM\Table(name: '`groups`')]
class Groups
{
    #[ORM\Id, ORM\Column(type: 'integer'), ORM\GeneratedValue]
    private int|null $id = null;

    #[ORM\Column(type: 'string')]
    private string|null $name = null;

    /**
     * Many  have Many Users.
     * @var Collection<int, Groups>
     */
    #[ManyToMany(targetEntity: Users::class, inversedBy: 'groups')]
    #[JoinTable(name: 'user_groups')]
    #[JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'user_id', referencedColumnName: 'id')]
    private Collection $users;

    /**
     * Many Groups have Many Permissions.
     * @var Collection<int, Groups>
     */
    #[ManyToMany(targetEntity: Permissions::class, inversedBy: 'groups')]
    #[JoinTable(name: 'group_permissions')]
    #[JoinColumn(name: 'group_id', referencedColumnName: 'id')]
    #[InverseJoinColumn(name: 'permission_id', referencedColumnName: 'id')]
    private Collection $permissions;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->permissions = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return Collection
     */
    public function getPermissions(): ArrayCollection|Collection
    {
        return $this->permissions;
    }

    public function getPermissionsAsArray(): array
    {
        $permissions = [];
        foreach ($this->getPermissions() as $permission) {
            /* @var Permissions $permission */
            $permissions[] = [
                "id" => $permission->getId(),
                "name" => $permission->getName(),
            ];
        }

        return $permissions;
    }

    public function addUser(Users $user)
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
//            $user->addGroup($this);
        }
    }

    public function removeUser(Users $user): void
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
//            $user->removeGroup($this);
        }
    }

    public function addPermission(Permissions $permission)
    {
        if (!$this->permissions->contains($permission)) {
            $this->permissions->add($permission);
        }
    }

    public function setId(?int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;
        return $this;
    }
}
