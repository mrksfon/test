<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=AlbumRepository::class)
 */
class Album
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $spotify_album_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $release_date;

    /**
     * @ORM\Column(type="integer")
     */
    private $total_tracks;

    /**
     * @ORM\ManyToOne(targetEntity=Artist::class, inversedBy="albums")
     * @ORM\JoinColumn(nullable=false)
     */
    private $artist;

    /**
     * @ORM\OneToMany(targetEntity=Favorite::class, mappedBy="album", orphanRemoval=true)
     */
    private $favorite;

    public function __construct()
    {
        $this->favorite = new ArrayCollection();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSpotifyAlbumId(): ?string
    {
        return $this->spotify_album_id;
    }

    public function setSpotifyAlbumId(string $spotify_album_id): self
    {
        $this->spotify_album_id = $spotify_album_id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getReleaseDate(): ?string
    {
        return $this->release_date;
    }

    public function setReleaseDate(string $release_date): self
    {
        $this->release_date = $release_date;

        return $this;
    }

    public function getTotalTracks(): ?int
    {
        return $this->total_tracks;
    }

    public function setTotalTracks(int $total_tracks): self
    {
        $this->total_tracks = $total_tracks;

        return $this;
    }

    public function getArtist(): ?Artist
    {
        return $this->artist;
    }

    public function setArtist(?Artist $artist): self
    {
        $this->artist = $artist;

        return $this;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorite(): Collection
    {
        return $this->favorite;
    }

    public function addFavorite(Favorite $favorite): self
    {
        if (!$this->favorite->contains($favorite)) {
            $this->favorite[] = $favorite;
            $favorite->setAlbum($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): self
    {
        if ($this->favorite->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getAlbum() === $this) {
                $favorite->setAlbum(null);
            }
        }

        return $this;
    }
}
