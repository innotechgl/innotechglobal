<?php

/**
 * Class modelVideoGallery
 */
class modelVideoGallery extends videoGallery_class {

    /**
     * @return array
     */
    public function getAll(){
        $data = array();

        $dataRaw = $this->get_array(false);
        foreach($dataRaw as $val){
            $videoItem = new videoItem_class();
            $videoItem->fillMe($val);
            $videoItem->setThumb($val["thumb"]);
            $videoItem->setLang($val["language"]);
            $videoItem->setAllData(json_decode($val["options"],true));
            $data[] = $videoItem->__getArray();
        }

        return $data;
    }

    /**
     * @param Array $data
     * @return array
     */
    public function _add($data){
        $videoItem = new videoItem_class();
        $videoItem->fillMe($data);

        $id =  $this->addVideoToDb($videoItem);
        $videoItem->setID($id);

        return $videoItem->__getArray();
    }

    /**
     * @param Array $data
     * @return bool
     */
    public function _update($data){
        $videoItem = new videoItem_class();
        $videoItem->fillMe($data);

        $res =  $this->update($videoItem);

        return $res;
    }

    /**
     * @param Int $id
     * @return bool
     */
    public function _delete($id){
        $res = $this->deleteFromDb($id);
        return $res;
    }

    /**
     * @param Int $id
     * @return bool
     */
    public function activate($id){
        $res = $this->activate_deactivate($id,1);
        return $res;
    }

    /**
     * @param Int $id
     * @return bool
     */
    public function deactivate($id){
        $res = $this->activate_deactivate($id,0);
        return $res;
    }

    public function load(){
        $dataRaw = $this->get_array(false);
        $video = new videoItem_class();

        $video->fillMe($dataRaw[0]);
        $video->setAllData(json_decode($dataRaw[0]["options"], true));

        return $video->__getArray();
    }

}