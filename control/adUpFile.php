<?php
if($_FILES['file']['error'] == 0){
    $type_array = array('image/jpeg','image/pjpeg','image/gif','image/png','application/octet-stream'); 
    if($_FILES['file']['size'] <= 3000000){   //判断图片大小
        $name = $_FILES['file']['name'];
        $name_arr = explode('.', $name);
        $new_name = time().uniqid().'.'.$name_arr[count($name_arr)-1];
        $path = '../file/admin/'.date('Ym');   //上传图片路径
        $new_path = $path.'/'.$new_name;
        
        if(!is_dir($path)){
            mkdir($path,0777,true);
        }
        if(move_uploaded_file($_FILES['file']['tmp_name'],$new_path)){
            echo substr($new_path,3);die;    //返回路径
        }else{
            echo "<script>alert('上传图片失败！')</script>";
        }
    }else{
        echo "<script>alert('上传图片过大！')</script>";
    }
}
?>