<?php
declare(strict_types=1);

namespace App\Entity;

class BugReport extends Entity
{

    private $id;
    private $report_type;
    private $email;
    private $link;
    private $message;
    private $created_at;

    public function getId(): int
    {
        return $this->id;
    }

    public function getReportType(): string
    {
        return $this->report_type;
    }

    public function setReportType(string $report_type): static
    {
        $this->report_type = $report_type;
        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(?string $link): static
    {
        $this->link = $link;
        return $this;
    }

    public function getMessage(): string
    {
        return $this->message;
    }

    public function setMessage($message): static
    {
        $this->message = $message;
        return $this;
    }

    public function getCreatedAt()
    {
        return $this->created_at;
    }

    public function toArray(): array
    {
        return [
            'report_type' => $this->getReportType(),
            'email' => $this->getEmail(),
            'message' => $this->getMessage(),
            'link' => $this->getLink(),
            'created_at' => date('Y-m-d H:i:s')
        ];
    }
}