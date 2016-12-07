<?php
/**
 * Created by PhpStorm.
 * User: fabrice
 * Date: 07/12/16
 * Time: 10:33
 */

namespace Flazzarotto\BinaryFilterBundle\BinaryFilter;


use Liip\ImagineBundle\Binary\Loader\LoaderInterface;

class BinaryLoader implements LoaderInterface
{

    /**
     * Retrieve the Image represented by the given path.
     *
     * The path may be a file path on a filesystem, or any unique identifier among the storage engine
     * implemented by this Loader.
     *
     * @param mixed $binary
     *
     * @return \Liip\ImagineBundle\Binary\BinaryInterface|string An image binary content
     */
    public function find($binary)
    {
        return $binary;
    }
}
