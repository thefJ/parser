<?php
declare(strict_types = 1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use SixDreams\RichModel\Traits\RichModelTrait;

/**
 * @ORM\Table(
 *    indexes={
 *       @ORM\Index(name="ip_date_idx", columns={"ip", "date"})
 *    }
 * )
 * @ORM\Entity(repositoryClass="App\Repository\VisitRepository")
 *
 * @method int getId()
 *
 * @method self setDate(\DateTimeImmutable $date)
 * @method \DateTimeImmutable getDate()
 *
 * @method self setIp(string $ip)
 * @method string getIp()
 *
 * @method self setReferrer(string $referrer)
 * @method string getReferrer()
 *
 * @method self setUrl(string $url)
 * @method string getUrl()
 */
class Visit
{
    use RichModelTrait;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private int $id;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    private \DateTimeImmutable $date;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $ip;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $referrer;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private string $url;
}
