<?php
/**
 * Modify: caozha.com
 * 代码仓库: https://gitee.com/caozha/ueditor
 * User: Jinqn
 * Date: 5-11-2020
 * UEditor编辑器通用上传类
 * 新增水印图片
 */
class Uploader {
	private $water; //是否添加水印(属性)
	private $fileField; //文件域名
	private $file; //文件上传对象
	private $base64; //文件上传对象
	private $config; //配置信息
	private $oriName; //原始文件名
	private $fileName; //新文件名
	private $fullName; //完整文件名,即从当前配置目录开始的URL
	private $filePath; //完整文件名,即从当前配置目录开始的URL
	private $fileSize; //文件大小
	private $fileType; //文件类型
	private $stateInfo; //上传状态信息,
	private $stateMap = array( //上传状态映射表，国际化用户需考虑此处数据的国际化
		"SUCCESS", //上传成功标记，在UEditor中内不可改变，否则flash判断会出错
		"文件大小超出 upload_max_filesize 限制",
		"文件大小超出 MAX_FILE_SIZE 限制",
		"文件未被完整上传",
		"没有文件被上传",
		"上传文件为空",
		"ERROR_TMP_FILE" => "临时文件错误",
		"ERROR_TMP_FILE_NOT_FOUND" => "找不到临时文件",
		"ERROR_SIZE_EXCEED" => "文件大小超出网站限制",
		"ERROR_TYPE_NOT_ALLOWED" => "文件类型不允许",
		"ERROR_CREATE_DIR" => "目录创建失败",
		"ERROR_DIR_NOT_WRITEABLE" => "目录没有写权限",
		"ERROR_FILE_MOVE" => "文件保存时出错",
		"ERROR_FILE_NOT_FOUND" => "找不到上传文件",
		"ERROR_WRITE_CONTENT" => "写入文件内容错误",
		"ERROR_UNKNOWN" => "未知错误",
		"ERROR_DEAD_LINK" => "链接不可用",
		"ERROR_HTTP_LINK" => "链接不是http链接",
		"ERROR_HTTP_CONTENTTYPE" => "链接contentType不正确"
	);

	/**
	 * 构造函数
	 * @param string $fileField 表单名称
	 * @param array $config 配置项
	 * @param string $type	处理文件上传的方式
	 */
	public

	function __construct( $fileField, $config, $type = "upload", $watermark = false,$iswater ) {

	    //检测是否登陆管理员
        @session_start();
        if(!$_SESSION["caozha_admin_id"] || !$_SESSION["caozha_admin_name"]){
            echo "您未登陆管理员账户，无法使用编辑器上传功能。";exit();
        }
        //end

		$this->fileField = $fileField;
		$this->config = $config;
		$this->type = $type;
		
		if(isset($iswater)){
			if($iswater==1){
				$this->water = true;
			}else{
				$this->water = false;
			}
		}else{
			$this->water = $watermark;
		}
		
		if ( $type == "remote" ) {
			$this->saveRemote();
		} else if ( $type == "base64" ) {
			$this->upBase64();
		} else {
			$this->upFile();
		}

		$this->stateMap[ 'ERROR_TYPE_NOT_ALLOWED' ] = mb_convert_encoding( $this->stateMap[ 'ERROR_TYPE_NOT_ALLOWED' ], 'utf-8', 'auto' );
	}

	/**
	 * 上传文件的主处理方法
	 * @return mixed
	 */
	private

	function upFile() {

		$file = $this->file = $_FILES[ $this->fileField ];
		if ( !$file ) {
			$this->stateInfo = $this->getStateInfo( "ERROR_FILE_NOT_FOUND" );
			return;
		}
		if ( $this->file[ 'error' ] ) {
			$this->stateInfo = $this->getStateInfo( $file[ 'error' ] );
			return;
		} else if ( !file_exists( $file[ 'tmp_name' ] ) ) {
			$this->stateInfo = $this->getStateInfo( "ERROR_TMP_FILE_NOT_FOUND" );
			return;
		} else if ( !is_uploaded_file( $file[ 'tmp_name' ] ) ) {
			$this->stateInfo = $this->getStateInfo( "ERROR_TMPFILE" );
			return;
		}

		$this->oriName = $file[ 'name' ];
		$this->fileSize = $file[ 'size' ];
		$this->fileType = $this->getFileExt();
		$this->fullName = $this->getFullName();
		$this->filePath = $this->getFilePath();
		$this->fileName = $this->getFileName();
		$dirname = dirname( $this->filePath );

		//检查文件大小是否超出限制
		if ( !$this->checkSize() ) {
			$this->stateInfo = $this->getStateInfo( "ERROR_SIZE_EXCEED" );
			return;
		}

		//检查是否不允许的文件格式
		if ( !$this->checkType() ) {
			$this->stateInfo = $this->getStateInfo( "ERROR_TYPE_NOT_ALLOWED" );
			return;
		}

		//创建目录失败
		if ( !file_exists( $dirname ) && !mkdir( $dirname, 0777, true ) ) {
			$this->stateInfo = $this->getStateInfo( "ERROR_CREATE_DIR" );
			return;
		} else if ( !is_writeable( $dirname ) ) {
			$this->stateInfo = $this->getStateInfo( "ERROR_DIR_NOT_WRITEABLE" );
			return;
		}

		//移动文件
		if ( !( move_uploaded_file( $file[ "tmp_name" ], $this->filePath ) && file_exists( $this->filePath ) ) ) { //移动失败
			$this->stateInfo = $this->getStateInfo( "ERROR_FILE_MOVE" );
		} else { //移动成功
			$this->stateInfo = $this->stateMap[ 0 ];
		}

		if($this->water){//水印
			$this->watermark($this->filePath,$this->filePath);
		}

	}

	/**
	 * 处理base64编码的图片上传
	 * @return mixed
	 */
	private

	function upBase64() {
		$base64Data = $_POST[ $this->fileField ];
		$img = base64_decode( $base64Data );

		$this->oriName = $this->config[ 'oriName' ];
		$this->fileSize = strlen( $img );
		$this->fileType = $this->getFileExt();
		$this->fullName = $this->getFullName();
		$this->filePath = $this->getFilePath();
		$this->fileName = $this->getFileName();
		$dirname = dirname( $this->filePath );

		//检查文件大小是否超出限制
		if ( !$this->checkSize() ) {
			$this->stateInfo = $this->getStateInfo( "ERROR_SIZE_EXCEED" );
			return;
		}

		//创建目录失败
		if ( !file_exists( $dirname ) && !mkdir( $dirname, 0777, true ) ) {
			$this->stateInfo = $this->getStateInfo( "ERROR_CREATE_DIR" );
			return;
		} else if ( !is_writeable( $dirname ) ) {
			$this->stateInfo = $this->getStateInfo( "ERROR_DIR_NOT_WRITEABLE" );
			return;
		}

		//移动文件
		if ( !( file_put_contents( $this->filePath, $img ) && file_exists( $this->filePath ) ) ) { //移动失败
			$this->stateInfo = $this->getStateInfo( "ERROR_WRITE_CONTENT" );
		} else { //移动成功
			$this->stateInfo = $this->stateMap[ 0 ];
		}

	}

	/**
	 * 拉取远程图片
	 * @return mixed
	 */
	private

	function saveRemote() {
		$imgUrl = htmlspecialchars( $this->fileField );
		$imgUrl = str_replace( "&amp;", "&", $imgUrl );

		//获取带有GET参数的真实图片url路径
		$pathRes = parse_url( $imgUrl );
		$queryString = isset( $pathRes[ 'query' ] ) ? $pathRes[ 'query' ] : '';
		$imgUrl = str_replace( '?' . $queryString, '', $imgUrl );

		//http开头验证
		if ( strpos( $imgUrl, "http" ) !== 0 ) {
			$this->stateInfo = $this->getStateInfo( "ERROR_HTTP_LINK" );
			return;
		}
		//获取请求头并检测死链
		$heads = get_headers( $imgUrl, 1 );
		if ( !( stristr( $heads[ 0 ], "200" ) && stristr( $heads[ 0 ], "OK" ) ) ) {
			$this->stateInfo = $this->getStateInfo( "ERROR_DEAD_LINK" );
			return;
		}
		//格式验证(扩展名验证和Content-Type验证)
		$fileType = strtolower( strrchr( $imgUrl, '.' ) );
		if ( !in_array( $fileType, $this->config[ 'allowFiles' ] ) || !isset( $heads[ 'Content-Type' ] ) || !stristr( $heads[ 'Content-Type' ], "image" ) ) {
			$this->stateInfo = $this->getStateInfo( "ERROR_HTTP_CONTENTTYPE" );
			return;
		}

		//打开输出缓冲区并获取远程图片
		ob_start();
		$context = stream_context_create(
			array( 'http' => array(
				'follow_location' => false // don't follow redirects
			) )
		);
		readfile( $imgUrl . '?' . $queryString, false, $context );
		$img = ob_get_contents();
		ob_end_clean();
		preg_match( "/[\/]([^\/]*)[\.]?[^\.\/]*$/", $imgUrl, $m );

		$this->oriName = $m ? $m[ 1 ] : "";
		$this->fileSize = strlen( $img );
		$this->fileType = $this->getFileExt();
		$this->fullName = $this->getFullName();
		$this->filePath = $this->getFilePath();
		$this->fileName = $this->getFileName();
		$dirname = dirname( $this->filePath );

		//检查文件大小是否超出限制
		if ( !$this->checkSize() ) {
			$this->stateInfo = $this->getStateInfo( "ERROR_SIZE_EXCEED" );
			return;
		}

		//检查文件内容是否真的是图片
		if ( substr( mime_content_type( $this->filePath ), 0, 5 ) != 'image' ) {
			$this->stateInfo = $this->getStateInfo( "ERROR_TYPE_NOT_ALLOWED" );
			return;
		}

		//创建目录失败
		if ( !file_exists( $dirname ) && !mkdir( $dirname, 0777, true ) ) {
			$this->stateInfo = $this->getStateInfo( "ERROR_CREATE_DIR" );
			return;
		} else if ( !is_writeable( $dirname ) ) {
			$this->stateInfo = $this->getStateInfo( "ERROR_DIR_NOT_WRITEABLE" );
			return;
		}

		//移动文件
		if ( !( file_put_contents( $this->filePath, $img ) && file_exists( $this->filePath ) ) ) { //移动失败
			$this->stateInfo = $this->getStateInfo( "ERROR_WRITE_CONTENT" );
		} else { //移动成功
			$this->stateInfo = $this->stateMap[ 0 ];
		}

	}

	/**
	 * 上传错误检查
	 * @param $errCode
	 * @return string
	 */
	private

	function getStateInfo( $errCode ) {
		return !$this->stateMap[ $errCode ] ? $this->stateMap[ "ERROR_UNKNOWN" ] : $this->stateMap[ $errCode ];
	}

	/**
	 * 获取文件扩展名
	 * @return string
	 */
	private

	function getFileExt() {
		return strtolower( strrchr( $this->oriName, '.' ) );
	}

	/**
	 * 重命名文件
	 * @return string
	 */
	private

	function getFullName() {
		//替换日期事件
		$t = time();
		$d = explode( '-', date( "Y-y-m-d-H-i-s" ) );
		$format = $this->config[ "pathFormat" ];
		$format = str_replace( "{yyyy}", $d[ 0 ], $format );
		$format = str_replace( "{yy}", $d[ 1 ], $format );
		$format = str_replace( "{mm}", $d[ 2 ], $format );
		$format = str_replace( "{dd}", $d[ 3 ], $format );
		$format = str_replace( "{hh}", $d[ 4 ], $format );
		$format = str_replace( "{ii}", $d[ 5 ], $format );
		$format = str_replace( "{ss}", $d[ 6 ], $format );
		$format = str_replace( "{time}", $t, $format );

		//过滤文件名的非法字符,并替换文件名
		$oriName = substr( $this->oriName, 0, strrpos( $this->oriName, '.' ) );
		$oriName = preg_replace( "/[\|\?\"\<\>\/\*\\\\]+/", '', $oriName );
		$format = str_replace( "{filename}", $oriName, $format );

		//替换随机字符串
		$randNum = rand( 1, 10000000000 ) . rand( 1, 10000000000 );
		if ( preg_match( "/\{rand\:([\d]*)\}/i", $format, $matches ) ) {
			$format = preg_replace( "/\{rand\:[\d]*\}/i", substr( $randNum, 0, $matches[ 1 ] ), $format );
		}

		if ( $this->fileType ) {
			$ext = $this->fileType;
		} else {
			$ext = $this->getFileExt();
		}
		return $format . $ext;
	}

	/**
	 * 获取文件名
	 * @return string
	 */
	private

	function getFileName() {
		return substr( $this->filePath, strrpos( $this->filePath, '/' ) + 1 );
	}

	/**
	 * 获取文件完整路径
	 * @return string
	 */
	private

	function getFilePath() {
		$fullname = $this->fullName;
		$rootPath = $_SERVER[ 'DOCUMENT_ROOT' ];

		if ( substr( $fullname, 0, 1 ) != '/' ) {
			$fullname = '/' . $fullname;
		}

		return $rootPath . $fullname;
	}

	/**
	 * 文件类型检测
	 * @return bool
	 */
	private

	function checkType() {
		return in_array( $this->getFileExt(), $this->config[ "allowFiles" ] );
	}

	/**
	 * 文件大小检测
	 * @return bool
	 */
	private

	function checkSize() {
		return $this->fileSize <= ( $this->config[ "maxSize" ] );
	}

	/**
	 * 获取当前上传成功文件的各项信息
	 * @return array
	 */
	public

	function getFileInfo() {
		return array(
			"state" => $this->stateInfo,
			"url" => $this->fullName,
			"title" => $this->fileName,
			"original" => $this->oriName,
			"type" => $this->fileType,
			"size" => $this->fileSize
		);
	}


	/*
    * 图片加水印
    * $source  string  图片资源
    * $target  string  添加水印后的名字
    * $w_pos   int     水印位置安排（1-10）【1:左头顶；2:中间头顶；3:右头顶...值空:随机位置】
    * $w_img   string  水印图片路径
    * $w_text  string  显示的文字
    * $w_font  int     字体大小
    * $w_color string  字体颜色
   */
   public function watermark($source, $target = '', $w_pos = '', $w_img = '', $w_text = 'caozha.com',$w_font = 10, $w_color = '#CC0000') {
       $this->w_img = 'water/watermark.png';//水印图片
       $this->w_pos = 9;
       $this->w_minwidth = 220;//最少宽度
       $this->w_minheight = 220;//最少高度
       $this->w_quality = 80;//图像质量
       $this->w_pct = 50;//透明度
         
       $w_pos = $w_pos ? $w_pos : $this->w_pos;
       $w_img = $w_img ? $w_img : $this->w_img;
       if(!$this->check($source)) return false;
       if(!$target) $target = $source;
       $source_info = getimagesize($source);//图片信息
       $source_w  = $source_info[0];//图片宽度
       $source_h  = $source_info[1];//图片高度
       if($source_w < $this->w_minwidth || $source_h < $this->w_minheight) return false;
       switch($source_info[2]) { //图片类型
           case 1 : //GIF格式
           $source_img = imagecreatefromgif($source);
           break;
           case 2 : //JPG格式
           $source_img = imagecreatefromjpeg($source);
           break;
           case 3 : //PNG格式
           $source_img = imagecreatefrompng($source);
           //imagealphablending($source_img,false); //关闭混色模式
              imagesavealpha($source_img,true); //设置标记以在保存 PNG 图像时保存完整的 alpha 通道信息（与单一透明色相反）
           break;
           default :
           return false;
       }
       if(!empty($w_img) && file_exists($w_img)) { //水印图片有效
           $ifwaterimage = 1; //标记
           $water_info  = getimagesize($w_img);
           $width    = $water_info[0];
           $height    = $water_info[1];
           switch($water_info[2]) {
               case 1 :
                   $water_img = imagecreatefromgif($w_img);
               break;
               case 2 :
                   $water_img = imagecreatefromjpeg($w_img);
               break;
               case 3 :
                   $water_img = imagecreatefrompng($w_img);
                   imagealphablending($water_img,false);
                   imagesavealpha($water_img,true);
               break;
               default :
               return;
           }
       }else{
       $ifwaterimage = 0;
       $temp = imagettfbbox(ceil($w_font*2.5), 0, './water/elephant.ttf', $w_text); //imagettfbbox返回一个含有 8 个单元的数组表示了文本外框的四个角
       $width = $temp[2] - $temp[6];
       $height = $temp[3] - $temp[7];
       unset($temp);
       }
         
       switch($w_pos) {
           case 1:
           $wx = 5;
           $wy = 5;
           break;
           case 2:
           $wx = ($source_w - $width) / 2;
           $wy = 0;
           break;
           case 3:
           $wx = $source_w - $width;
           $wy = 0;
           break;
           case 4:
           $wx = 0;
           $wy = ($source_h - $height) / 2;
           break;
           case 5:
           $wx = ($source_w - $width) / 2;
           $wy = ($source_h - $height) / 2;
           break;
           case 6:
           $wx = $source_w - $width;
           $wy = ($source_h - $height) / 2;
           break;
           case 7:
           $wx = 0;
           $wy = $source_h - $height;
           break;
           case 8:
           $wx = ($source_w - $width) / 2;
           $wy = $source_h - $height;
           break;
           case 9:
           $wx = $source_w - ($width+5);
           $wy = $source_h - ($height+5);
           break;
           case 10:
           $wx = rand(0,($source_w - $width));
           $wy = rand(0,($source_h - $height));
           break;      
           default:
           $wx = rand(0,($source_w - $width));
           $wy = rand(0,($source_h - $height));
           break;
       }
       /*
           dst_im  目标图像
           src_im  被拷贝的源图像
           dst_x   目标图像开始 x 坐标
           dst_y   目标图像开始 y 坐标，x,y同为 0 则从左上角开始
           src_x   拷贝图像开始 x 坐标
           src_y   拷贝图像开始 y 坐标，x,y同为 0 则从左上角开始拷贝
           src_w   （从 src_x 开始）拷贝的宽度
           src_h   （从 src_y 开始）拷贝的高度
           pct 图像合并程度，取值 0-100 ，当 pct=0 时，实际上什么也没做，反之完全合并。
       */
       if($ifwaterimage) {
           if($water_info[2] == 3) {
               imagecopy($source_img, $water_img, $wx + 10, $wy , 0, 0, $width, $height);
           }else{
               imagecopymerge($source_img, $water_img, $wx, $wy, 0, 0, $width, $height, $this->w_pct);
           }
           }else{
           if(!empty($w_color) && (strlen($w_color)==7)) {
           $r = hexdec(substr($w_color,1,2));
           $g = hexdec(substr($w_color,3,2));
           $b = hexdec(substr($w_color,5));
       }else{
       return;
       }
           imagestring($source_img,$w_font,$wx,$wy,$w_text,imagecolorallocate($source_img,$r,$g,$b));
       }
          
       switch($source_info[2]) {
           case 1 :
               imagegif($source_img, $target);
               //GIF 格式将图像输出到浏览器或文件(欲输出的图像资源, 指定输出图像的文件名)
           break;
           case 2 :
               imagejpeg($source_img, $target, $this->w_quality);
           break;
           case 3 :
               imagepng($source_img, $target);
           break;
           default :
           return;
       }
        
       // $font = "./water/elephant.ttf";
 
       // $image->open($name)->text("caozha.com",$font,27,'#82797A',array(95,$height))->save($name);
 
       if(isset($water_info)){
           unset($water_info);
       }
       if(isset($water_img)) {
           imagedestroy($water_img);
       }
       unset($source_info);
       imagedestroy($source_img);
       return true;
   }
   public function check($image){
       return extension_loaded('gd') && preg_match("/\.(jpg|jpeg|gif|png)/i", $image, $m) && file_exists($image) && function_exists('imagecreatefrom'.($m[1] == 'jpg' ? 'jpeg' : $m[1]));
   }


}