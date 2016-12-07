<?php

namespace Flazzarotto\BinaryFilterBundle\BinaryFilter;

use Liip\ImagineBundle\Imagine\Data\DataManager;
use Liip\ImagineBundle\Imagine\Filter\FilterManager;

class BinaryFilter {

    private $dm;
    private $fm;

    private $originalData;
    private $finalPath;
    private $defaultFilter;

    private $result;

    private $mime_type;

    public function __construct(DataManager $dataManager, FilterManager $filterManager,
                                $originalData, $finalPath, $defaultFilter=null)
    {
        $this->dm = $dataManager;
        $this->fm = $filterManager;

        $this->result = $this->originalData = $originalData;
        $this->finalPath = realpath($finalPath);
        $this->defaultFilter = $defaultFilter;
    }

    public function applyFilter($filter=null) {
        if ($filter == null) {
            $filter = $this->defaultFilter;
        }
        $image = $this->loadImage($filter);
        // Get binary
        $result = $this->fm->applyFilter($image, $filter)->getContent();

        return new BinaryFilter($this->dm,$this->fm,$result,$this->finalPath,$this->defaultFilter);
    }

    public function getFilteredBinary() {
        return $this->result;
    }

    public function outputFile($override=false) {
        if (!is_writable(dirname($this->finalPath))) {
            throw new FilterException();
        }
        if (!isset($this->result)) {
            $this->result = file_get_contents($this->originalData);
        }

        // Removes potentially existing file
        if (is_file($this->finalPath)) {
            if ($override) {
                unlink($this->finalPath);
            }
            else {
                throw new FilterException("Output file already exists");
            }
        }

        $handle = fopen($this->finalPath, 'w');
        fwrite($handle, $this->result);
        fclose($handle);

        return $this->finalPath;
    }

    protected function setResult($result) {
        $this->result = $result;
    }

    public function getMimeType() {
        return $this->mime_type;
    }

    private function loadImage($filter) {
        try {
            $this->dm->getLoader($filter);
        }
        catch (\InvalidArgumentException $iae) {
            throw new FilterException($iae->getMessage(),404,$iae);
        }

        // Fetch image and determine its type
        $image = $this->dm->find($filter, $this->originalData);

        $this->mime_type = $image->getMimeType();

        return $image;
    }

}
