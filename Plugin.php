<?php
if (!defined('__TYPECHO_ROOT_DIR__')) exit;
/**
 * Title Tips
 * 博客失焦或聚焦时的标题状态
 * 
 * @package TitleTips
 * @author Jefsky
 * @version 1.0.0
 * @link https://jefsky.com
 */
class TitleTips_Plugin implements Typecho_Plugin_Interface
{
    /**
     * 激活插件方法,如果激活失败,直接抛出异常
     *
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function activate()
    {
        Typecho_Plugin::factory('Widget_Archive')->footer = array('TitleTips_Plugin', 'footer');
    }

    /**
     * 禁用插件方法,如果禁用失败,直接抛出异常
     *
     * @static
     * @access public
     * @return void
     * @throws Typecho_Plugin_Exception
     */
    public static function deactivate(){}

    /**
     * 获取插件配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form 配置面板
     * @return void
     */
    public static function config(Typecho_Widget_Helper_Form $form)
    {
        /** 分类名称 */
        $jquery = new Typecho_Widget_Helper_Form_Element_Checkbox('jquery', array('jquery' => '禁止加载jQuery'), null, _t('Js设置'), _t('插件需要加载jQuery，如果主题模板已经引用加载JQuery，则可以勾选。'));
        $form->addInput($jquery);
        $outOfFocus = new Typecho_Widget_Helper_Form_Element_Text('outOfFocus', NULL, '我失宠了', _t('失宠说点什么'));
        $inFocus = new Typecho_Widget_Helper_Form_Element_Text('inFocus', NULL, '我被宠了', _t('聚焦说点什么'));
        $form->addInput($outOfFocus);
        $form->addInput($inFocus);
    }

    /**
     * 个人用户的配置面板
     *
     * @access public
     * @param Typecho_Widget_Helper_Form $form
     * @return void
     */
    public static function personalConfig(Typecho_Widget_Helper_Form $form){}

    /**
     * 插件实现方法
     *
     * @access public
     * @return void
     */
    public static function footer()
    {
        $Options = Helper::options()->plugin('TitleTips');
        if (!$Options->jquery || !in_array('jquery', $Options->jquery)) {
            echo '<script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>';
        }
        echo '<script type="text/javascript">$(document).ready(function(){';
        echo 'var OriginTitile = document.title; ';
        echo 'var titleTime;';
        echo "document.addEventListener('visibilitychange', function(){
                if (document.hidden){
                    document.title = '$Options->outOfFocus';
                    clearTimeout(titleTime);
                }else{
                    document.title = '$Options->inFocus';
                    titleTime = setTimeout(function() {
                        document.title = OriginTitile;
                    }, 2000); // 2秒后恢复原标题  
                }
            });";
        echo '});</script>';
    }
}
