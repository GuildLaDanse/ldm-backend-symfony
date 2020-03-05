<?php declare(strict_types=1);
/**
 * @license  http://opensource.org/licenses/gpl-license.php GNU Public License
 * @link     https://github.com/GuildLaDanse
 */

namespace App\Infrastructure\Doctrine;


use DateTime;
use DateTimeZone;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\DateTimeType;
use Doctrine\DBAL\Types\Types;

class UTCDateTimeType extends DateTimeType
{
    static private $utc;

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return "utc_datetime";
    }

    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

    public function canRequireSQLConversion()
    {
        return true;
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return string
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform): string
    {
        if ($value instanceof DateTime) {
            $value->setTimezone(self::getUtcDateTimeZone());
        }

        return parent::convertToDatabaseValue($value, $platform);
    }

    /**
     * @param mixed $value
     * @param AbstractPlatform $platform
     *
     * @return DateTime
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform): DateTime
    {
        if (null === $value || $value instanceof DateTime) {
            return $value;
        }

        $converted = DateTime::createFromFormat(
            $platform->getDateTimeFormatString(),
            $value,
            self::getUtcDateTimeZone()
        );

        if (! $converted) {
            throw ConversionException::conversionFailedFormat(
                $value,
                $this->getName(),
                $platform->getDateTimeFormatString()
            );
        }

        return $converted;
    }

    static private function getUtcDateTimeZone() : DateTimeZone
    {
        self::$utc ? self::$utc : self::$utc = new DateTimeZone('UTC');

        return self::$utc;
    }
}