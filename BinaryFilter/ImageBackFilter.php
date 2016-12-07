<?php

namespace Flazzarotto\BinaryFilterBundle\BinaryFilter;

use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;

class ImageBackFilter {

    private $webDir;

    private $dm;

    private $fm;

    private $defaultFilter = "resize";

    private $defaultOutput;

    public function __construct(DataManager $dataManager,FilterManager $filterManager, $rootDir){
        $this->webDir = $rootDir."/../web/";
        $this->dm = $dataManager;
        $this->fm = $filterManager;
    }

    /**
     * @param $defaultFilter
     * @return $this
     */
    public function setDefaultFilter($defaultFilter) {
        $this->defaultFilter = $defaultFilter;
        return $this;
    }

    /**
     * @param $webDirRelativeOutputPath
     * @return $this
     */
    public function setDefaultOutput($webDirRelativeOutputPath) {
        $this->defaultOutput = $this->generateFinalPath($webDirRelativeOutputPath);
        return $this;
    }

    /**
     * @param $absolutePath
     * @param null $webDirRelativeOutputPath
     * @return BinaryFilter
     */
    public function loadFile($absolutePath, $webDirRelativeOutputPath=null) {
        if (!is_file($absolutePath)) {
            throw new FileNotFoundException('Input path '.$absolutePath. ' is not a file');
        }

        return $this->loadBinary(file_get_contents($absolutePath), $webDirRelativeOutputPath);
    }

    public function loadBinary($binary, $webDirRelativeOutputPath=null) {
        return new BinaryFilter($this->dm, $this->fm, $binary,
            $this->generateFinalPath($webDirRelativeOutputPath!==null?$webDirRelativeOutputPath:$this->defaultOutput),
            $this->defaultFilter);
    }

    /**
     * @param $webDirRelativeOutputPath
     * @return string
     */
    private function generateFinalPath($webDirRelativeOutputPath) {
        return preg_replace("#/+#","/",$this->webDir."/").$webDirRelativeOutputPath;
    }

}

