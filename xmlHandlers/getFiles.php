<?php
    /**
     * Gets archived folders (from previous) 
     * @param boolean $fh : default is true. If false tenant folder is chosen 
     * @return array files 
     */
    function getArchiveFiles($fh = true) {
        $folder = $fh == true ? "fhXml" : "tenants";
        $path = "xml/" . $folder . "/previous/";
        $files = scandir($path, SCANDIR_SORT_DESCENDING);
        $files = array_diff($files, array('..', '.'));

        return $files;
    }

    /**
     * @param string $xml path incl.filename xml file
     * @return SimpleXMLElement root
    */
    function getXmlRoot($xml) {
        $root = new SimpleXMLElement($xml, false, true);

        return $root;
    }