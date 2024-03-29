<?php

use Attla\{
    Pincryp\Config,
    Pincryp\Factory as Pincryp
};
use Illuminate\Support\Arr;

uses(Tests\TestCase::class)->in(__DIR__);

dataset('var-types', $types = [
    'alfa'      => $string = 'Now I am become Death, the destroyer of worlds.',
    'alfanum'   => '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ',
    'special'   => '`~!@#$%^&*()\\][+={}/|:;"\'<>,.?-_',
    'acents'    => 'àáâãäÀÁÂÃÄ çÇ èéêëÈÉÊË ìíîïÌÍÎÏ ñÑ òóôõöÒÓÔÕÖ ùúûüÙÚÛÜ ýÿÝ',
    'japanese'  => '今、私は世界の破壊者である死になりました。',
    'mandarin'  => '现在我变成了死神，世界的毁灭者。',
    'hindi'     => 'अब मैं मृत्यु बन गया हूँ, संसारों का नाश करने वाला।',
    'int'       => 42,
    'float'     => 4.2,
    'array (SEQ)'   => [$seq = [4,2]],
    'array (ASSOC)' => [$assoc = ['four' => 4,'two' => 2]],
    'stdClass'      => $stdClass = (object) $assoc,
    'bool (FALSE)'          => false,
    'bool (TRUE)'           => true,
    'int (FALSE)'           => 0,
    'int (TRUE)'            => 1,
    'array (empty)'         => [[]],
    'stdClass (empty)'      => new \stdClass(),
    'GMP class'             => new \GMP(0),
    'string numeric (FALSE)' => '0',
    'string numeric (TRUE)'  => '1',
    'null' => null,
    'null (byte)' => chr(0),
    'zero (byte)' => 0x0,
    'null string (byte)' => "\x00",
    'separator (byte)' => "\x1c",
    'byte' => 0x2A,
    'others' => " \t\n\r\0\x0B\x0c\xc2\xa0",
]);

dataset('value', [Arr::random($types)]);
dataset('string', [$string]);

function pincryp(Config $config)
{
    static $instances = [];

    $hash = spl_object_hash($config);
    if (!is_null($instance = $instances[$hash] ?? null)) {
        return $instance;
    }

    return $instances[$hash] = new Pincryp($config);
}

function encode(Config $config, $value): string
{
    return pincryp($config)->encode($value);
}

function decode(Config $config, $value)
{
    return pincryp($config)->decode($value);
}

function encodeAndDecode(Config $config, $value, bool $associative = false)
{
    $pincryp = pincryp($config);

    return $pincryp->decode(
        $pincryp->encode($value),
        $associative
    );
}
