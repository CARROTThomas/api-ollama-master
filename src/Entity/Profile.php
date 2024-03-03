<?php

namespace App\Entity;

use App\Repository\ProfileRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProfileRepository::class)]
class Profile
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(inversedBy: 'profile', cascade: ['persist', 'remove'])]
    private ?User $ofUser = null;

    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'author')]
    private Collection $messages;

    #[ORM\OneToMany(targetEntity: ConversationEntry::class, mappedBy: 'profile', orphanRemoval: true)]
    private Collection $conversationEntries;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->conversationEntries = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOfUser(): ?User
    {
        return $this->ofUser;
    }

    public function setOfUser(?User $ofUser): static
    {
        $this->ofUser = $ofUser;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setAuthor($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getAuthor() === $this) {
                $message->setAuthor(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ConversationEntry>
     */
    public function getConversationEntries(): Collection
    {
        return $this->conversationEntries;
    }

    public function addConversationEntry(ConversationEntry $conversationEntry): static
    {
        if (!$this->conversationEntries->contains($conversationEntry)) {
            $this->conversationEntries->add($conversationEntry);
            $conversationEntry->setProfile($this);
        }

        return $this;
    }

    public function removeConversationEntry(ConversationEntry $conversationEntry): static
    {
        if ($this->conversationEntries->removeElement($conversationEntry)) {
            // set the owning side to null (unless already changed)
            if ($conversationEntry->getProfile() === $this) {
                $conversationEntry->setProfile(null);
            }
        }

        return $this;
    }

    public function addPromptAndResponseToConversation(string $question, string $response): static
    {
        $conversationEntry = new ConversationEntry();
        $conversationEntry
            ->setProfile($this)
            ->setQuestion($question)
            ->setResponse($response);

        $this->addConversationEntry($conversationEntry);

        return $this;
    }

    public function getConversationHistory(): array
    {
        $conversationHistory = [];

        foreach ($this->getConversationEntries() as $entry) {
            $conversationHistory[] = [
                'question' => $entry->getQuestion(),
                'response' => $entry->getResponse(),
            ];
        }

        return $conversationHistory;
    }
}
