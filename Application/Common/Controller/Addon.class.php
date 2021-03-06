<?php
namespace Common\Controller;
use Think\Controller;

/**
 * 插件类
 */
abstract class Addon extends Controller{
    /**
     * 视图实例对象
     * @var view
     * @access protected
     */
    protected $view = null;

    /**
     * $info = array(
     *  'name'=>'Editor',
     *  'title'=>'编辑器',
     *  'description'=>'用于增强整站长文本的输入和显示',
     *  'status'=>1,
     *  'author'=>'thinkphp',
     *  'version'=>'0.1'
     *  )
     */
    public $info                =   array();
    public $addon_path          =   '';
    public $config_file         =   '';
    public $custom_config        =   '';

    public function _initialize(){
        $this->view         =   \Think\Think::instance('Think\View');
        $this->addon_path   =   ADDON_PATH.$this->getName().'/';
        $TMPL_PARSE_STRING = C('TMPL_PARSE_STRING');
        $TMPL_PARSE_STRING['__ADDONROOT__'] = __ROOT__ . '/Addons/'.$this->getName();
        C('TMPL_PARSE_STRING', $TMPL_PARSE_STRING);
        C('TAGLIB_BUILD_IN', 'MyTag,Cx');
        if(is_file($this->addon_path.'config.php')){
            $this->config_file = $this->addon_path.'config.php';
        }
        $config =   S('DB_CONFIG_DATA');
        if(!$config){
            $config =  config_lists();
            S('DB_CONFIG_DATA',$config);
        }
        C($config);
        define('MOBILE','web');
        define('UID',is_user_login());

        $web['webpath']=__ROOT__."/";
        $web['webtitle']=C("WEB_SITE_TITLE");
        $web['weblogo']=C("WEB_LOGO");
        $web['keywords']=C("WEB_SITE_KEYWORD");
        $web['description']=C("WEB_SITE_DESCRIPTION");
        $web['icp']=C("WEB_SITE_ICP");
        $web['weburl']=C("WEB_URL");
        $web['webname']=C("WEB_NAME");
        
        C('CACHE_PATH',RUNTIME_PATH."/Cache/".$this->getName()."/".ucfirst('web')."/");
        C('TMPL_ACTION_ERROR',APP_PATH.'User/View/web/Public/dispatch_jump.html');
        C('TMPL_ACTION_SUCCESS',APP_PATH.'User/View/web/Public/dispatch_jump.html');
        if(!C('WEB_SITE_CLOSE')){
            $this->error('站点已经关闭，请稍后访问~');
        }
        if(MODULE_NAME!='Admin'){
            \Think\Hook::add("view_filter","Home\\Behavior\\JsBehavior");
        }
        $this->assign($web);
        $this->assign('addon_path',substr($this->addon_path, 1));
    }

    /**
     * 模板主题设置
     * @access protected
     * @param string $theme 模版主题
     * @return Action
     */
    final protected function theme($theme){
        $this->view->theme($theme);
        return $this;
    }

    //显示方法
    final protected function display($template=''){
        if($template == '')
            $template = CONTROLLER_NAME;
        echo ($this->fetch($template));
    }

    /**
     * 模板变量赋值
     * @access protected
     * @param mixed $name 要显示的模板变量
     * @param mixed $value 变量的值
     * @return Action
     */
    final protected function assign($name,$value='') {
        $this->view->assign($name,$value);
        return $this;
    }


    //用于显示模板的方法
    final protected function fetch($templateFile = CONTROLLER_NAME){
        if(!is_file($templateFile)){
            $templateFile = $this->addon_path.$templateFile.C('TMPL_TEMPLATE_SUFFIX');
            if(!is_file($templateFile)){
                throw new \Exception("模板不存在:$templateFile");
            }
        }
        return $this->view->fetch($templateFile);
    }

    final public function getName(){
        $class = get_class($this);
        return substr($class,strrpos($class, '\\')+1, -5);
    }

    final public function checkInfo(){
        $info_check_keys = array('name','title','description','status','author','version');
        foreach ($info_check_keys as $value) {
            if(!array_key_exists($value, $this->info))
                return FALSE;
        }
        return TRUE;
    }

    /**
     * 获取插件的配置数组
     */
    final public function getConfig($name=''){
        static $_config = array();
        if(empty($name)){
            $name = $this->getName();
        }
        if(isset($_config[$name])){
            return $_config[$name];
        }
        $config =   array();
        $map['name']    =   $name;
        $map['status']  =   1;
        $config  =   M('Addons')->where($map)->getField('config');
        if($config){
            $config   =   json_decode($config, true);
        }else{
            $temp_arr = include $this->config_file;
            foreach ($temp_arr as $key => $value) {
                if($value['type'] == 'group'){
                    foreach ($value['options'] as $gkey => $gvalue) {
                        foreach ($gvalue['options'] as $ikey => $ivalue) {
                            $config[$ikey] = $ivalue['value'];
                        }
                    }
                }else{
                    $config[$key] = $temp_arr[$key]['value'];
                }
            }
        }
        $_config[$name]     =   $config;
        return $config;
    }

    //必须实现安装
    abstract public function install();

    //必须卸载插件方法
    abstract public function uninstall();
}
