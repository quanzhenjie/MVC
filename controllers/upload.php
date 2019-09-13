<?php
class uploadController extends Controller{
    
    //文件上传到文件夹路径
    private $UploadDir;
    //文件上传到具体路径
    private $UploadPath;
    //本地文件完整名称
    private $LocalFileName;
    //上传文件到临时目录
    private $UploadTempPath;
    //上传文件是否为图片并改变分辨率
    private $IfImage;
    //上传图片的宽度
    private $Width;
    //上传图片的高度
    private $Height;
    //文件来源 数组$_FILES
    private $FileForm = array();
    //$_Files数组索引
    private $IndexOf;
    //上传文件存放文件夹类型
    private $DirCategory = 1;//0：直接上传根目录 1:按天存入目录 2:按月存入目录 3:按扩展名存目录  建议使用按天存
    //扩展名
    private $extension = "txt,rar,zip,jpg,jpeg,gif,png,swf,wmv,avi,wma,mp3,mid";
    //当前上传扩展名
    private $UploadFileExtension;
    //上传大小限制 字节为单位
    private $MaxUploadSize = 2500000;
    //上传是否成功
    private $success = false;
    //上传失败原因
    private $error = "上传失败.";
    
    public function xheditor(){
        $this->UploadDir = "uploads/xheditor";
        $this->UploadTempPath = "uploads/".time().".tmp";
        $this->IndexOf = "filedata";
        $this->FileForm = $_FILES;
        $this->success = $this->result();
        if($this->success){
            $this->load_model("upload");
            $result_data = $this->model_action("xheditor",array("table_name"=>$_GET['table_name'],"pid"=>$_GET['pid'],"upload_path"=>$this->UploadPath));
            echo $this->return_json(array("err"=>"","msg"=>array("url"=>$this->UploadPath,"localname"=>$this->LocalFileName,"id"=>"")));
            //echo "{'err':'','msg':{'url':'".$this->UploadPath."','localname':'".$this->LocalFileName."','id':''}}";
        }else{
            echo $this->return_json(array("err"=>$this->error,"msg"=>""));
            //echo "{'err':'".$this->error."','msg':{}}";
        }
    }
    
    public function gifts_thumb(){
        $this->UploadDir = "uploads/gifts";
        $this->UploadTempPath = "uploads/".time().".tmp";
        $this->IfImage = true;
        $this->Width = 300;
        $this->Height = 300;
        $this->IndexOf = $_POST['index'];
        $this->FileForm = $_FILES;
        $this->success = $this->result();
        if($this->success){
            $this->load_model("upload");
            $result_data = $this->model_action("gifts_thumb",array("id"=>$_POST['table_id'],"path"=>$this->UploadPath));
            echo "<script type=\"text/javascript\">window.parent.upload_back(".$this->return_json(array("result"=>1,"sql_result"=>$result_data,"id"=>$_POST['table_id'])).");</script>";
        }else{
            echo "<script type=\"text/javascript\">window.parent.upload_back(".$this->return_json(array("result"=>0,"sql_result"=>0,"id"=>$_POST['table_id'])).");</script>";
        }
    }
    
    public function temp(){
        $this->UploadDir = "uploads/temp";
        $this->UploadTempPath = "uploads/".time().".tmp";
        $this->IndexOf = "UF_Name";
        $this->FileForm = $_FILES;
        $this->success = $this->result();
        if($this->success){
            echo $this->return_json(array("err"=>"","msg"=>array("url"=>$this->UploadPath,"localname"=>$this->LocalFileName,"id"=>"")));
            //echo "{'err':'','msg':{'url':'".$this->UploadPath."','localname':'".$this->LocalFileName."','id':''}}";
        }else{
            echo $this->return_json(array("err"=>$this->error,"msg"=>""));
            //echo "{'err':'".$this->error."','msg':{}}";
        }
    }
    
    private function result(){
        if(isset($_SERVER['HTTP_CONTENT_DISPOSITION'])&&preg_match('/attachment;\s+name="(.+?)";\s+filename="(.+?)"/i',$_SERVER['HTTP_CONTENT_DISPOSITION'],$info)){
            //HTML5上传
            file_put_contents($this->UploadTempPath,file_get_contents("php://input"));
            $this->LocalFileName = urldecode($info[2]);
        }else{
            //标准上传
            if($this->FileForm[$this->IndexOf]['error']>0 || $this->FileForm[$this->IndexOf]['size']>$this->MaxUploadSize){
                switch($this->FileForm[$this->IndexOf]['error']){
                    case 1:
                        $this->error = "上传的文件大小超过了服务器限制的值";break;
                    case 2:
                        $this->error = "上传文件的大小超过了HTML限制的值";break;
                    case 3:
                        $this->error = "文件只有部分被上传";break;
                    case 4:
                        $this->error = "没有文件被上传";break;
                    case 6:
                        $this->error = "找不到临时文件夹";break;
                    case 7:
                        $this->error = "文件写入失败";break;
                    case 8:
                        $this->error = "上传被其他扩展名中断";break;
                    default:
                        $this->error = "上传失败..";break;
                }
                if($this->FileForm[$this->IndexOf]['error']==0){
                    $this->error = "你超过了上传文件大小的限制";
                }
                return false;
            }else{
                if(!move_uploaded_file($this->FileForm[$this->IndexOf]['tmp_name'],$this->UploadTempPath)){
                    $this->error = "上传失败...";
                    return false;
                }
                $this->LocalFileName = $this->FileForm[$this->IndexOf]['name'];
            }
        }
        if(filesize($this->UploadTempPath)>$this->MaxUploadSize){
            $this->error = "你超过了上传文件大小的限制";
            return false;
        }
        $fileInfo=pathinfo($this->LocalFileName);
        $this->UploadFileExtension = $fileInfo['extension'];
        if(preg_match('/^('.str_replace(',','|',$this->extension).')$/i',$fileInfo['extension'])){
            switch($this->DirCategory){
                case 1:
                    $attachSubDir = 'day_'.date('ymd');break;
                case 2:
                    $attachSubDir = 'month_'.date('ym');break;
                case 3:
                    $attachSubDir = 'extension_'.$this->UploadFileExtension;break;
                default:
                    break;
            }
            $attachDir = $this->UploadDir."/".$attachSubDir;
            if(!is_dir($attachDir)){
                @mkdir($attachDir, 0777);
                @fclose(fopen($attachDir.'/index.htm', 'w'));
            }
            PHP_VERSION < '4.2.0' && mt_srand((double)microtime() * 1000000);
            $newFilename = date("YmdHis").mt_rand(1000,9999).'.'.$this->UploadFileExtension;
            $this->UploadPath = $attachDir.'/'.$newFilename;
            rename($this->UploadTempPath,$this->UploadPath);
            if($this->IfImage){
                $newimage = new ResizeImage($this->UploadPath,$this->Width,$this->Height,true,$this->UploadPath);
            }
			@chmod($this->UploadPath,0755);
            @unlink($this->UploadTempPath);
            return true;
        }else{
            $this->error = "你上传文件的扩展名不被允许";
            return false;
        }
    }
}

class ResizeImage
{
    //图片类型
    var $type;
    //实际宽度
    var $width;
    //实际高度
    var $height;
    //改变后的宽度
    var $resize_width;
    //改变后的高度
    var $resize_height;
    //是否裁图
    var $cut;
    //源图象
    var $srcimg;
    //目标图象地址
    var $dstimg;
    //临时创建的图象
    var $im;

    function ResizeImage($img, $wid, $hei,$c,$dstpath)
    {
        $this->srcimg = $img;
        $this->resize_width = $wid;
        $this->resize_height = $hei;
        $this->cut = $c;
        //图片的类型
    
$this->type = strtolower(substr(strrchr($this->srcimg,"."),1));

        //初始化图象
        $this->initi_img();
        //目标图象地址
        $this -> dst_img($dstpath);
        //--
        $this->width = imagesx($this->im);
        $this->height = imagesy($this->im);
        //生成图象
        $this->newimg();
        ImageDestroy ($this->im);
    }
    function newimg()
    {
        //改变后的图象的比例
        $resize_ratio = ($this->resize_width)/($this->resize_height);
        //实际图象的比例
        $ratio = ($this->width)/($this->height);
        if(($this->cut)=="1")
        //裁图
        {
            if($ratio>=$resize_ratio)
            //高度优先
            {
                $newimg = imagecreatetruecolor($this->resize_width,$this->resize_height);
                imagecopyresampled($newimg, $this->im, 0, 0, 0, 0, $this->resize_width,$this->resize_height, (($this->height)*$resize_ratio), $this->height);
                ImageJpeg ($newimg,$this->dstimg);
            }
            if($ratio<$resize_ratio)
            //宽度优先
            {
                $newimg = imagecreatetruecolor($this->resize_width,$this->resize_height);
                imagecopyresampled($newimg, $this->im, 0, 0, 0, 0, $this->resize_width, $this->resize_height, $this->width, (($this->width)/$resize_ratio));
                ImageJpeg ($newimg,$this->dstimg);
            }
        }
        else
        //不裁图
        {
            if($ratio>=$resize_ratio)
            {
                $newimg = imagecreatetruecolor($this->resize_width,($this->resize_width)/$ratio);
                imagecopyresampled($newimg, $this->im, 0, 0, 0, 0, $this->resize_width, ($this->resize_width)/$ratio, $this->width, $this->height);
                ImageJpeg ($newimg,$this->dstimg);
            }
            if($ratio<$resize_ratio)
            {
                $newimg = imagecreatetruecolor(($this->resize_height)*$ratio,$this->resize_height);
                imagecopyresampled($newimg, $this->im, 0, 0, 0, 0, ($this->resize_height)*$ratio, $this->resize_height, $this->width, $this->height);
                ImageJpeg ($newimg,$this->dstimg);
            }
        }
    }
    //初始化图象
    function initi_img()
    {
        if($this->type=="jpg")
        {
            $this->im = imagecreatefromjpeg($this->srcimg);
        }
        if($this->type=="gif")
        {
            $this->im = imagecreatefromgif($this->srcimg);
        }
        if($this->type=="png")
        {
            $this->im = imagecreatefrompng($this->srcimg);
        }
    }
    //图象目标地址
    function dst_img($dstpath)
    {
        $full_length  = strlen($this->srcimg);

        $type_length  = strlen($this->type);
        $name_length  = $full_length-$type_length;


        $name         = substr($this->srcimg,0,$name_length-1);
        $this->dstimg = $dstpath;


//echo $this->dstimg;
    }
}