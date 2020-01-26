<?php
  class Model {
   	public function __construct() {
     	$this->db = new Database();
   	}

  	public function getNavData() {
  		$user_id = Session::get('id');

  		$sth = $this->db->prepare("SELECT name, surname, email FROM users WHERE id = ?");
      $sth->execute(array($user_id));

      $data = $sth->fetch();
      return $data;
  	}

    public function getSiteConstants($needableConstants = []) {
      //$needableConstants = 
      $out;
      for ($i=0; $i < count($needableConstants); $i++) { 
        $sth = $this->getFromDBOne("SELECT * FROM settings WHERE paramName = ?", [$needableConstants[$i]]);
        $out[$needableConstants[$i]] = $sth['paramValue'];
      }
      return $out;
    }

    public function getUri() {
      $uri = explode('?',$_SERVER['REQUEST_URI'])[0];
      $uri = explode('/',$uri); // URI
      $url = $uri;
      $uri = array();
      unset($url[0]);
      foreach ($url as $key)
        array_push($uri, $key);
      return $uri;
    }

    public function getFromDB($sqlq, $arr = array()) {
      $sth = $this->db->prepare($sqlq);
      $sth->execute($arr);
      $sth = $sth->fetchAll();

      return $sth;
    }
    public function getFromDBOne($sqlq, $arr = array()) {
      $sth = $this->db->prepare($sqlq);
      $sth->execute($arr);
      $sth = $sth->fetch();

      return $sth;
    }

    public function photoMaster ($directory, $name, $maxPhotos = -1, $replacement = false, $oldPhotos = []) {
        echo "lol";
        $photos = [];

        $date = time();
        $user_id = Session::get('id');

        if (!$replacement) {
            if (isset($_FILES[$name]['tmp_name'][0])) {
                if (count($_FILES[$name]['tmp_name'])) {
                    for ($i = 0; $i < count($_FILES[$name]['tmp_name']); $i++) {
                        if(is_uploaded_file($_FILES[$name]['tmp_name'][$i])) {
                            if(preg_match('/[.](JPG)|(jpg)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES[$name]['name'][$i])) {

                                //$path_to_90_directory = "public/images/product_photos/";
                                $path_to_90_directory = $directory;
                                $filename =    $_FILES[$name]['name'][$i];
                                $source =    $_FILES[$name]['tmp_name'][$i];
                                //$target =    $path_to_90_directory.$filename;
                                //move_uploaded_file($source, $target);
                                $filename = explode('.', $filename);
                                $photoExtension = array_pop($filename);
                                $target = $path_to_90_directory.$date."_".$user_id."_".$i.".".$photoExtension;
                                move_uploaded_file($source, $target);

                                array_push($photos, $directory.$date."_".$user_id."_".$i.".".$photoExtension);

                                /*if(preg_match('/[.](GIF)|(gif)$/', $filename)) {
                                    $im    = imagecreatefromgif($path_to_90_directory.$filename);
                                }
                                if(preg_match('/[.](PNG)|(png)$/', $filename)) {
                                    $im =    imagecreatefrompng($path_to_90_directory.$filename);
                                }
                                             
                                if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/', $filename)) {
                                    $im =    imagecreatefromjpeg($path_to_90_directory.$filename);
                                }        
                         
                                //$w    = 500;
                                $w_src    = imagesx($im);
                                $h_src    = imagesy($im);
                                $dest = imagecreatetruecolor($w_src,$h_src);           
                                
                                imagecopyresampled($dest, $im, 0, 0, 0, 0, $w_src, $w_src, $w_src, $w_src);
                                imagejpeg($dest,    $path_to_90_directory.$date."-".$i.".jpg");

                                array_push($photos, $directory.$date."-".$i.".jpg");

                                $delfull = $path_to_90_directory.$filename; 
                                unlink ($delfull);*/

                                if (count($photos) > $maxPhotos - 1 and $maxPhotos != -1) {
                                    break;
                                }
                            }
                        }
                    }
                } else {
                    $answer = array(
                        'status' => 'error'
                    );
                    return json_encode($answer);
                    exit();
                }
            } else {
                $photos[0] = '';
            }

        } else {
            $urlLenght = strlen(URL);
            $currentPhotosLength = count($oldPhotos);

            for ($i=0; $i < $currentPhotosLength; $i++) {
                if (is_uploaded_file($_FILES[$name]['tmp_name'][$i])) {
                    if (preg_match('/[.](JPG)|(jpg)|(gif)|(GIF)|(png)|(PNG)$/',$_FILES[$name]['name'][0]) or $i!=0) {
                        //$path_to_90_directory = "public/images/product_photos/";
                        $path_to_90_directory = $directory;
                        $filename =    $_FILES[$name]['name'][$i];
                        $source =    $_FILES[$name]['tmp_name'][$i];

                        $filename = explode('.', $filename);
                        $photoExtension = array_pop($filename);
                        $target = $path_to_90_directory.$date."_".$user_id."_".$i.".".$photoExtension;
                        move_uploaded_file($source, $target);

                        array_push($photos, $directory.$date."_".$user_id."_".$i.".".$photoExtension);

                        /*$target =    $path_to_90_directory.$filename;
                        move_uploaded_file($source, $target);

                        if(preg_match('/[.](GIF)|(gif)$/', $filename)) {
                            $im    = imagecreatefromgif($path_to_90_directory.$filename);
                        }
                        if(preg_match('/[.](PNG)|(png)$/', $filename)) {
                            $im =    imagecreatefrompng($path_to_90_directory.$filename);
                        }
                                     
                        if(preg_match('/[.](JPG)|(jpg)|(jpeg)|(JPEG)$/', $filename)) {
                            $im =    imagecreatefromjpeg($path_to_90_directory.$filename);
                        }        
                 
                        $w    = 500;
                        $w_src    = imagesx($im);
                        $h_src    = imagesy($im); 
                        $dest = imagecreatetruecolor($w_src,$h_src);           
                        
                        imagecopyresampled($dest, $im, 0, 0, 0, 0, $w_src, $w_src, $w_src, $w_src);
                        imagejpeg($dest,    $path_to_90_directory.$date."-".$i.".jpg");

                        $photos[$i] = $directory.$date."-".$i.".jpg";
                        $delfull = $path_to_90_directory.$filename; 
                        unlink ($delfull);*/

                        if ($oldPhotos[$i] != '') {
                            $deleteTurn = substr($oldPhotos[$i], $urlLenght);
                            unlink($deleteTurn);
                        }
                    } else {
                        $photos[$i] = $oldPhotos[$i];
                    }
                } else {
                    $photos[$i] = $oldPhotos[$i];
                }
            }
        }

        return $photos;
    }

    public function getNumberOfRows($table) {
      $sth = $this->db->prepare("SELECT id FROM ".$table." WHERE 1");
      $sth->execute(array());
      $count_ch = $sth->rowCount();
      return $count_ch;
    }




    public function getTableData($tname = '', $conditions = array()) {
      $cond_str = '';

      for ($i=0; $i < count($conditions); $i++) { 
        $cond_str = $cond_str.$conditions[$i];
        //if ($i < count($conditions) - 1) $cond_str = $cond_str.' AND ';
        //лучше писать в массиве
      }
      if ($cond_str == '') $cond_str = '1';
      $sth = $this->db->prepare("SELECT * FROM ".$tname." WHERE ".$cond_str);
      $sth->execute();

      $data = array();
      $i = 0;
      while ($row = $sth->fetch()) {
        //$data[$row['name']] = $row['value'];
        foreach ($row as $key => $value) {
          $data[$i][$key] = $value;
        }
        $i++;
      }
      return $data;
    }
    public function getSql($query, $array) {
      $sth = $this->db->prepare($query);
      $sth->execute($array);
      //return $sth;
      $i = 0;
      $data = array();
      while ($row = $sth->fetch()) {
        foreach ($row as $key => $value) {
          $data[$i][$key] = $value;
        }
        $i++;
      }
      return $data;
    }

  }
?>