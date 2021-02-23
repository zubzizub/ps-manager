<?php

declare(strict_types=1);

namespace App\Domain\Auth\UseCase\SignUpByEmail\Request;

use Symfony\Component\Validator\Constraints;

class Command
{
    /**
     * @var string
     * @Constraints\NotBlank()
     * @Constraints\Email()
     */
    public string $email;
    /**
     * @var string
     * @Constraints\NotBlank
     * @Constraints\Length(min="6")
     */
    public string $password;
}
