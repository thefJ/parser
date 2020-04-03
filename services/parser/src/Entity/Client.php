<?php
declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use SixDreams\RichModel\Traits\RichModelTrait;

/**
 * @ORM\Table(
 *    uniqueConstraints={
 *        @ORM\UniqueConstraint(name="unique_client",
 *            columns={"ip"})
 *    }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\ClientRepository")
 *
 * @method int getId()
 *
 * @method self setIp(string $ip)
 * @method string getIp()
 *
 * @method self setBrowser(string $browser)
 * @method string getBrowser()
 *
 * @method self setOs(string $os)
 * @method string getOs()
 */
class Client
{
    use RichModelTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $ip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $browser;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $os;
}
