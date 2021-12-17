<?php

/**
 * -----------------------------------------------------------------
 * 验证码类
 * -----------------------------------------------------------------
 * @author 尔玉 QQ 136066669
 * 
 */
class vcode
{
    public    $level         = 1;                           // 验证码级别 1，数字 2，字母 3，数字+字母
    public    $width         = 60;                          // 默认宽度
    public    $height        = 20;                          // 默认高度
    public    $length        = 4;                           // 默认字符长度 多个数字用逗号分隔 例'3,4'即3位或4位随机长度
    public    $font_id       = 5;                           // 字体id 0为随机
    public    $border_color  = 'auto';                      // 边框颜色 auto为自动    
    public    $session_name  = '__VCODE__';                 // session 保存字段

    private   $char_num      = '2345678';                   // 随机因子 数字部分
    private   $char_en_lower = 'abcdefghjkmnpqrstuvwxyz';   // 随机因子 英文小写部分
    private   $char_en_upper = 'ABCDEFGHJKMNPQRSTUVWXYZ';   // 随机因子 英文大写部分
    private   $font_id_max   = 9;                           // 最大字体id    
    protected $code          = '';                          // 验证码字符串
    protected $area;                                        // 验证码面积
    protected $image;                                       // 验证码资源句柄
    protected $font_size;                                   // 文字大小
    protected $font_color;                                  // 文字颜色
    protected $char_string;                                 // 随机因子组合
    protected $char_length;                                 // 生成文字长度
    protected $background_color;                            // 背景颜色
    
    /**
     * -----------------------------------------------------------------
     * 公有 function 生成验证码 并且输出到页面
     * -----------------------------------------------------------------
     * 
     */
    public function view()
    {   
        session_start();
        // 随机出验证码字符
        $this->create_code();
        // 创建背景
        $this->create_background();
        // 创建文字
        $this->create_font();
        // 生成杂点
        $this->create_dot();
        // 生成前景线条
        $this->create_cover();
        // 生成边框
        $this->create_border( $this->border_color );
        // 保存到session
        $_SESSION[$this->session_name] = strtolower($this->code);
        // 指定文件头
        header('Content-type:image/png');
        // 输出图片
        imagepng($this->image);
        // 注销资源
        imagedestroy($this->image);
    }
    
    /**
     * -----------------------------------------------------------------
     * 公有 function 构造函数
     * -----------------------------------------------------------------
     * 
     */
    public function __construct($params = array())
    {       
        if (count($params) > 0)
        {
            $this->initialize($params);
        }
        // 生成随机因子串
        $this->set_code_string();
        // 计算验证码面积
        $this->area = $this->width * $this->height;
    }
    
    /**
     * -----------------------------------------------------------------
     * 公有 function 类初始化
     * -----------------------------------------------------------------
     * 
     */
    public function initialize($params = array())
    {
        if (count($params) > 0)
        {
            foreach ($params as $key => $val)
            {
                if (isset($this->$key))
                {
                    $this->$key = $val;
                }
            }
        }
    }
    
    /**
     * -----------------------------------------------------------------
     * 私有 function 返回字体路径
     * -----------------------------------------------------------------
     * 
     */
    private function font_path()
    {
        $font_id = intval($this->font_id);
        // 未指定字体id随机一个字体
        if($font_id == 0)
        {
            $font_id = mt_rand(1, $this->font_id_max);
        }
        return "fonts/font.ttf";
    }
    
    /**
     * -----------------------------------------------------------------
     * 私有 function hex 颜色转 rgb 颜色
     * -----------------------------------------------------------------
     * 
     */
    private function hex_to_rgb($hex = '')
    {
        $rgb = array('r' => 255, 'g' => 255, 'b' => 255);
        if(empty($hex))
        {
            return $rgb;
        }
        if(strlen($hex) >= 6)
        {
            $hex_split = str_split($hex, 2);
            $rgb['r']  = hexdec($hex_split[0]);
            $rgb['g']  = hexdec($hex_split[1]);
            $rgb['b']  = hexdec($hex_split[2]);
        }
        else
        {
            $hex_split = str_split($hex, 1);
            $rgb['r']  = hexdec(str_repeat($hex_split[0], 2));
            $rgb['g']  = hexdec(str_repeat($hex_split[1], 2));
            $rgb['b']  = hexdec(str_repeat($hex_split[2], 2));
        }
        return $rgb;
    }
    
    /**
     * -----------------------------------------------------------------
     * 私有 function 创建验证码字符串
     * -----------------------------------------------------------------
     * 
     */
    private function create_code()
    {
        $length_data = explode(',', $this->length);
        if(count($length_data) == 1)
        {
            $this->char_length = $length_data[0];
        }
        else
        {
            $this->char_length = intval( $length_data[ array_rand($length_data, 1) ] );
        }
        $char_length = strlen( $this->char_string ) - 1;
        for ($i = 0; $i < $this->char_length; $i ++)
        {
            $this->code .= $this->char_string[ mt_rand(0, $char_length) ];
        }  
        return $this->code;
    }
    
    /**
     * -----------------------------------------------------------------
     * 私有 function 按验证码级别生成随机因子
     * -----------------------------------------------------------------
     * 
     */
    private function set_code_string()
    {
        $level = intval($this->level);
        switch ($level) {
            case 1:
                $this->char_string = $this->char_num;
                break;
            case 2:
                $this->char_string = $this->char_en_lower . $this->char_en_upper;
                break;
            case 3:
                $this->char_string = $this->char_num . $this->char_en_lower . $this->char_en_upper;
                break;
            default:
                $this->char_string = $this->char_num;
                break;
        }
    }
    
    /**
     * -----------------------------------------------------------------
     * 私有 function 生成背景
     * -----------------------------------------------------------------
     * 
     */
    private function create_background() 
    {  
        // 创建画布
        $this->image = imagecreatetruecolor(
            $this->width, 
            $this->height
        );
        // 背景颜色随机
        $this->background_color = imagecolorallocate(
            $this->image,
            mt_rand(230, 255),
            mt_rand(230, 255),
            mt_rand(230, 255)
        );
        // 填充背景颜色
        imagefill(
            $this->image, 
            0, 
            0, 
            $this->background_color
        );
    } 
    
    /**
     * -----------------------------------------------------------------
     * 私有 function 生成文字
     * -----------------------------------------------------------------
     * 
     */
    private function create_font() 
    {
        $char_width      = $this->width * 0.86 / $this->char_length;
        $char_margin     = $this->width * 0.07;
        $this->font_size = intval($this->width / $this->char_length);

        // 统一字体颜色
        $this->font_color = imagecolorallocate(
            $this->image, 
            mt_rand(0, 80),
            mt_rand(0, 80),
            mt_rand(0, 80)
        ); 
        
        // 生成文字
        for ($i = 0; $i < $this->char_length; $i ++)
        {
            imagettftext(
                $this->image, 
                $this->font_size,
                mt_rand(- 20, 20), // 字体角度
                $char_margin + $char_width * $i,   // 字体位置
                ($this->height - $this->font_size) / 2 + $this->font_size, // 字体基线 
                $this->font_color,
                $this->font_path(),
                $this->code[$i]
            );  
        }
        unset($char_width, $char_margin);
    }  
    
    /**
     * -----------------------------------------------------------------
     * 私有 function 生成杂点
     * -----------------------------------------------------------------
     * 
     */
    private function create_dot()
    {  
        for ($i = 0; $i < intval($this->area / 225); $i ++)
        {              
            imagestring($this->image, mt_rand(1, 5), mt_rand(0, $this->width), mt_rand(0, $this->height), '.', $this->font_color);
        }
    }
    
    /**
     * -----------------------------------------------------------------
     * 私有 function 生成前景线条
     * -----------------------------------------------------------------
     * 
     */
    private function create_cover()
    {  
        $rand_angle = mt_rand(0, 270);
        // 线条宽度
        imagesetthickness($this->image, $this->font_size / 8);
        
        // 随机弧线
        imagearc($this->image, 
            mt_rand(0, $this->width), 
            mt_rand(0, $this->height), 
            mt_rand($this->width / 2, $this->width), 
            mt_rand($this->width / 2, $this->width), 
            $rand_angle, 
            360 - $rand_angle, 
            $this->font_color
        );      
        unset($rand_angle);
    }  
    
    /**
     * -----------------------------------------------------------------
     * 私有 function 生成边框
     * -----------------------------------------------------------------
     * 
     */
    private function create_border($border_color)
    {
        if($border_color == '')
        {
            return;
        }
        else if($border_color == 'auto')
        {
            $background_color = imagecolorsforindex($this->image, $this->background_color);
            $color = imagecolorallocate(
                $this->image, 
                ($background_color['red']   - 20), 
                ($background_color['green'] - 20), 
                ($background_color['blue']  - 20)
            ); 
            unset($background_color);
        }
        else
        {
            $rgb   = $this->hex_to_rgb($border_color);
            $color = imagecolorallocate($this->image, $rgb['r'], $rgb['g'], $rgb['b']);
        }
        // 线条宽度
        imagesetthickness($this->image, 1);
        // 上
        imageline($this->image, 0, 0, $this->width, 0, $color);
        // 下
        imageline($this->image, 0, ($this->height - 1), $this->width, ($this->height - 1), $color);
        // 左
        imageline($this->image, 0, 0, 0, $this->height, $color);
        // 右
        imageline($this->image, ($this->width - 1), 0, ($this->width - 1), $this->height, $color);

        unset($color);
    }
    
} // END class Captcha

$vcode = new vcode();
$vcode->view();
