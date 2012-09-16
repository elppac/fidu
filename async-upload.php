<?
header('Content-Type: text/html; charset=UTF-8');
$upload_file=$_FILES['async-upload']['tmp_name'];
$upload_file_name= date('YmdHis',time()).'-'.time().'.'.get_extension($_FILES['async-upload']['name']);

function get_extension($file){
	return strtolower(substr(strrchr($file, '.'), 1));
}

function delete_old_files($path_dir){
	$handle  = opendir($path_dir);
	while( false !== ($file = readdir($handle)))
	{
		if($file != '.'  &&  $file!='..')
		{	
			$filename = $path_dir.$file;
			if( time() - fileatime( $filename ) > 86400 ){
				unlink( $filename );
			}
		}
	}
}

if($upload_file){
	$file_size_max = 1000*1000;// 1M限制文件上传最大容量(bytes) 
	$path_dir = dirname(dirname(dirname(__FILE__))).'/wp-content/uploads/excel/'; // 上传文件的储存位置
	if( !file_exists( $path_dir ) ){
		mkdir( $path_dir );
	}
	//echo $path_dir;
	
	$accept_overwrite = 1;//是否允许覆盖相同文件
 
	// 检查读写文件
	if (file_exists($path_dir . $upload_file_name) && !$accept_overwrite) {
		echo "存在相同文件名的文件";
		exit;
	}
 
	//复制文件到指定目录
	if (!move_uploaded_file($upload_file,$path_dir.$upload_file_name)) {
		echo "复制文件失败";
		exit;
	}
	//删除前一天的文件
	delete_old_files( $path_dir );
}
 
/* echo "<p>你上传了文件:";
echo$_FILES['async-upload']['name'];
echo "<br/>";
//客户端机器文件的原名称。 
 
echo "文件的 MIME 类型为:";
echo $_FILES['async-upload']['type'];
//文件的 MIME 类型，需要浏览器提供该信息的支持，例如“image/gif”。 
echo "<br/>";
 
echo "上传文件大小:";
echo $_FILES['async-upload']['size'];
//已上传文件的大小，单位为字节。 
echo "<br/>";
 
echo "文件上传后被临时储存为:";
echo $_FILES['async-upload']['tmp_name'];
//文件被上传后在服务端储存的临时文件名。 
echo "<br/>";  */
$Error=$_FILES['async-upload']['error'];
echo '<div class="output">';
switch($Error){
	case 0:
		echo "上传成功"; break;
	case 1:
		echo "上传的文件超过了 php.ini 中 upload_max_filesize 选项限制的值."; break;
	case 2:
		echo "上传文件的大小超过了 HTML 表单中 MAX_FILE_SIZE 选项指定的值。";break;
	case 3:
		echo "文件只有部分被上传";break;
	case 4:
		echo "没有文件被上传";break;
} 
if( $Error == 0 ){ 
	echo '&nbsp;&nbsp;<button class="button" onclick="toHtml(event,\''.$upload_file_name.'\')">To Html</button> ';
	//<button class="button"  onclick="toJson(event,\''.$upload_file_name.'\')">To JSON</button>
}
echo '</div>';
?> 