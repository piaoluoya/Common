<?php
/*
 * 日志：同时支持静态调用和对象调用
 * @author: zhangzheng@somel.cn
 * @createdate: 2011-03-18 10:20:04
 * @lastchagedate:
*/
class Loger {
    private static $LOGFILE = "log/%s.log";
    public  $logfile;
    /**
     * Constructor
     * @param string $logfile：日志文件路径
     */
    function __construct($logfile='') {
        if(!empty ($logfile)) {
            $this->logfile = $logfile;
        }
    }
    /**
     * 【对象】通用日志记录方法
     * @param multiple $message：需要记录的信息（数组，对象，字符串等）
     * @param string $filename：日志文件路径
     */
    public function w($message,$filename='') {
        if(empty ($filename)) $filename = $this->logfile;
        $type = gettype($message);
        if($type == 'array'||$type == 'object') {
            Loger::writeobj($message,$filename);
        }else {
            Loger::write($message,$filename);
        }
    }
    /**
     * 静态方法：记录字符串格式日志
     * @param string $message：需要记录的字符串
     * @param string $filename：日志文件路径
     */
    public static function write($message,$filename='') {#for express
        if(empty ($filename)) $filename = sprintf(self::$LOGFILE, date('YmdH')) ;
        $dir = trim(dirname($filename),'/').'/';
        Loger::forceDirectory($dir);
        if($fd = @fopen($filename, "a")) {
            $file_size=filesize($filename);
            if($file_size<=1000000000) {
                $date = date("Y-m-d H:i:s",time());
                $str =  $date ." ". $message ."\r\n" ;
                fputs($fd, $str);//fwrite($fd, $str);
            }
            fclose($fd);
        }
    }
    /**
     * 静态方法：记录对象数组格式日志
     * @param object $message：需要记录的对象或数组
     * @param string $filename：日志文件路径
     */
    public static function writeobj($obj,$filename='') {
        if(empty ($filename)) $filename = sprintf(self::$LOGFILE, date('YmdH')) ;
        self::write(__FUNCTION__.print_r($obj,true),$filename);
    }
    /**
     * 静态方法：创建目录
     * @param string $dir：文件路径
     */
    static function forceDirectory($dir) { // force directory structure
        return is_dir($dir) or (Loger::forceDirectory(dirname($dir)) and mkdir($dir, 0777));
    }
}

?>