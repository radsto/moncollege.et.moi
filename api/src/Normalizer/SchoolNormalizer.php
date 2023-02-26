<?php

namespace App\Normalizer;
use App\Entity\School\School;
use CrEOF\Spatial\PHP\Types\Geometry\Point;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class SchoolNormalizer implements DenormalizerInterface
{
    private ObjectNormalizer $normalizer;
    public function __construct(ObjectNormalizer $normalizer)
    {
        $this->normalizer = $normalizer;
    }

    public function denormalize(mixed $data, string $type, string $format = null, array $context = []): School
    {
        $filteredData = array_filter($data, static fn(string $key) => !in_array($key, [
            'position',
            'date_ouverture'
        ]), ARRAY_FILTER_USE_KEY);

        /** @var School $school */
        $school = $this->normalizer->denormalize($filteredData, $type, $format, $context);

        $school
            ->setDateOuverture(\DateTimeImmutable::createFromFormat('Y-m-d', $data['date_ouverture']))
            ->setPosition(null === $data['position'] ? null : new Point($data['position']['lon'], $data['position']['lat']))
        ;

        return $school;
    }

    public function supportsDenormalization(mixed $data, string $type, string $format = null, array $context = []): bool
    {
        return is_array($data) && $type === School::class;
    }
}