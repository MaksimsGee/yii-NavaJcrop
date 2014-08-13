<?php
/**
 * NavaJcrop class file.
 * This extension is a wrapper of http://blueimp.github.io/JavaScript-Load-Image/
 *
 * @author Le Phuong <notteen@gmail.com> & Dao Bui <dao.hunter@gmail.com>
 * @website http://navagroup.net
 * @version 0.1
 * @license http://www.opensource.org/licenses/MIT
 *
 * How to use:
$this->widget('ext.NavaJcrop.ImageJcrop', array(
'config' => array(
'id'=>'nava-jcrop',
'title'=>$model->name,
'image'=>$model->image_url, //required
'unique'=>true,
'buttons'=>array(
'cancel'=>array(
'name'=>'Cancel',
'class'=>'button-crop',
'style'=>'',
),
'edit'=>array(
'name'=>'Edit',
'class'=>'button-crop',
'style'=>'',
),
'crop'=>array(
'name'=>'Crop',
'class'=>'button-crop',
'style'=>'',
)
),
'options'=>array(
'imageWidth'=>120,
'imageHeight'=>160,
'resultStyle'=>'position: fixed;top: 50px;max-width:350px;max-height:350px;z-index: 9999;',
'resultMaxWidth'=>350,
'resultMinWidth'=>350,
),
'callBack'=> array(
'success'=>"function(object,response){console.log(object,response);}",
'error'=>"function(){alert('error');}",
)
)
));

 */
class ImageJcrop extends CWidget{
    private $navaJcrop           = 'nava.jcrop.js';
    private $cssJcrop           = 'jquery.Jcrop.css';
    private $jsJcrop            = 'jquery.Jcrop.js';
    private $jsLoadImage        = 'load-image.min.js';
    private $jsLoadImageMeta    = 'load-image-meta.js';

    public $config     = array();

    public function init(){
        if(isset($this->config['options'])){
            $options = $this->config['options'];
            if(isset($options['imageWidth'])){
                $imageWidth = $options['imageWidth'];
            } else {
                $imageWidth = 120;
            }
            if(isset($options['imageHeight'])){
                $imageHeight = $options['imageHeight'];
            } else {
                $imageHeight = 160;
            }
            if(isset($options['resultStyle'])){
                $resultStyle = $options['resultStyle'];
            } else {
                $resultStyle = 'position: fixed;top: 50px;max-width:350px;max-height:350px;z-index: 9999;';
            }
            if(isset($options['resultMaxWidth'])){
                $resultMaxWidth = $options['resultMaxWidth'];
            } else {
                $resultMaxWidth = 350;
            }
            if(isset($options['resultMinWidth'])){
                $resultMinWidth = $options['resultMinWidth'];
            } else {
                $resultMinWidth = 350;
            }
        }
        if(isset($this->config['id'])){
            $id = $this->config['id'];
        } else {
            $id = 'nava-jcrop';
        }
        if(isset($this->config['unique'])){
            $unique = '_'.time();
        } else {
            $unique = '';
        }
        $html = '<div id="'.$id.''.$unique.'">';
        $html .= '<div id="load_image'.$unique.'" class="load_image"><div id="load_image_hover'.$unique.'" class="load_image_hover hide" style="width:'.$imageWidth.'px;height:'.$imageHeight.'px;"></div>';
        $html .= '<div id="jcrop_result'.$unique.'" style="'.$resultStyle.'"><p id="jcrop_actions'.$unique.'" style="display:none;text-align: center;">';
        if(isset($this->config['buttons'])){
            foreach($this->config['buttons'] as $key => $button){
                $html .= '<button type="button" class="'.(isset($button['class'])?$button['class']:'').'" style="'.(isset($button['style'])?$button['style']:'').'" id="jcrop_'.$key.''.$unique.'">'.(isset($button['name'])?$button['name']:'Cancel').'</button>';
            }
        } else {
            $html .= '<button type="button" id="jcrop_cancel'.$unique.'">Cancel</button>';
            $html .= '<button type="button" id="jcrop_edit'.$unique.'">Edit</button>';
            $html .= '<button type="button" id="jcrop_crop'.$unique.'">Crop</button>';
        }
        $html .= '</p></div>';
        $html .= '<div id="JcropOverlay'.$unique.'" class="JcropOverlay hide"></div>';
        $html .= '<input type="file" id="jcrop_fileinput'.$unique.'" style="position: absolute; cursor: pointer; opacity: 0;width: '.$imageWidth.'px;height: '.$imageHeight.'px;"><img id="jcrop_image'.$unique.'" alt="'.(isset($this->config['title'])?$this->config['title']:'').'" src="'.$this->config['image'].'" style="width:'.$imageWidth.'px;height:'.$imageHeight.'px">';
        $html .= '</div></div>';
        echo $html;
        $script = 'var unique = "'.$unique.'", resultStyle = "'.$resultStyle.'",resultMaxWidth = "'.$resultMaxWidth.'",resultMinWidth = "'.$resultMinWidth.'", aspectRatio = '.($imageWidth/$imageHeight).';';
        if(isset($this->config['callBack'])){
            $callBack = $this->config['callBack'];
            if(isset($callBack['success'])){
                $script .= 'var successFunction = '.$callBack['success'].';';
            }
            if(isset($callBack['error'])){
                $script .= 'var errorFunction = '.$callBack['error'].';';
            }
        }
        $assets = dirname(__FILE__).'/assets';
        $baseUrl = Yii::app()->assetManager->publish($assets);
        $cs = Yii::app()->getClientScript();
        $cs->registerCssFile($baseUrl.'/'.$this->cssJcrop);
        $cs->registerScriptFile($baseUrl.'/'.$this->jsJcrop,CClientScript::POS_BEGIN);
        $cs->registerScriptFile($baseUrl.'/'.$this->jsLoadImage,CClientScript::POS_BEGIN);
        $cs->registerScriptFile($baseUrl.'/'.$this->jsLoadImageMeta,CClientScript::POS_BEGIN);
        $cs->registerScriptFile($baseUrl.'/'.$this->navaJcrop,CClientScript::POS_BEGIN);
        $cs->registerScript('jcrop',$script,CClientScript::POS_END);
		Yii::app()->clientScript->registerCoreScript('jquery');
    }
} 