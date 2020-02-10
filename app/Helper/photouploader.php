<?php
    use App\Image;

    function upload_image($file , $path ,$name)
    {
        if($file){
            try{
            $image = $file;  // your base64 encoded
            list($type, $image) = explode(';', $image);
            list(, $image)      = explode(',', $image);
            $data = base64_decode($image);
            $imageName = date("YmdHis"). '_'.$name . '.jpeg';
            file_put_contents(public_path() . '/' . $path . $imageName, $data);
            return $imageName ;
        } catch (\Throwable $th) {
            // return $th ;
        }
        }
        else{
            return null ;
        }
    }
?>