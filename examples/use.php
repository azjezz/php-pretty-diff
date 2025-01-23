<?php

use PrettyDiff\PrettyDiff;

require_once __DIR__ . '/../src/lib.php';

class User {
  public function __construct(
    public string $name,
    public int $age,
    public string $email,
    public string $password
  ) {}
}

$a = new User('John Doe', 20, 'john.doe@example.com', 'password');
$b = new User('Jane Doe', 20, 'jane.doe@example.com', 'password');

$diff = PrettyDiff::diffWords($a, $b, true);

echo $diff;
echo PHP_EOL;

$diff = PrettyDiff::diffChars($a, $b, true);

echo $diff;
echo PHP_EOL;


$diff = PrettyDiff::diffLines($a, $b, true);

echo $diff;
echo PHP_EOL;
